<?php

class RemediosController extends AppController {

	public $uses = 'Remedio';

	// --- index --------------------------------------------------------------
	public function index() {

		// --- Parâmetros da busca ---
		$params = array (
			'conditions' => array ('Remedio.usuario_id' => $this->Auth->user('id'), 'Remedio.qtd >' => 0),
			'recursive' => -1
		);

		// --- Realiza a busca ---
		$remedios = $this->Remedio->find('all', $params);

		// --- Envia para a view ---
		$dados = array (
			'remedios' => $remedios,
			'titulo' => 'Remédios'
		);
		$this->set($dados);

	}

	// --- ver ----------------------------------------------------------------
	public function ver($id, $acabou = false) {

		// --- Verifica se o remédio existe e se pertence ao usuário ---
		$this->Remedio->id = $id;
		if (!$this->Remedio->exists() || !$this->Remedio->find('count', array('conditions' => array('Remedio.id' => $id, 'Remedio.usuario_id' => $this->Auth->user('id'))))) {
			return $this->redirect(array('controller' => 'remedios', 'action' => 'index'));
		}

		// --- Busca o remédio com o id indicado ---
		$this->Remedio->id = $id;
		$remedio = $this->Remedio->read();

		// --- Envia para a view ---
		$dados = array (
			'acabou' => $acabou,
			'remedio' => $remedio,
			'titulo' => 'Remédios'
		);
		$this->set($dados);

	}

	// --- buscar -------------------------------------------------------------
	public function buscar() {

		// --- Busca as indicações ---
		$this->loadModel('Indicacao');
		$indicacoes = $this->Indicacao->findAllByUsuarioId($this->Auth->user('id'), array(), array('Indicacao.descricao' => 'ASC'), array(), array(), -1);

		// --- Envia para a view ---
		$dados = array (
			'indicacoes' => $indicacoes,
			'titulo' => 'Buscar'
		);
		$this->set($dados);

	}

	// --- resultadoBusca -----------------------------------------------------
	public function resultadoBusca() {

		// --- Se o formulário for submetido ---
		if ($this->request->is('post') && !empty($this->request->data)) {

			$dados = $this->request->data;

			// --- Busca os remédios com as indicações escolhidas ---
			$remedios = $this->Remedio->Indicacao->find('all', array(
					'conditions' => array(
						'Indicacao.id' => $dados['Remedio']['Indicacao'],
						)
					)
			);

			// --- Envia para a view ---
			$dados = array (
				'indicacoes' => $remedios,
				'titulo' => 'Resultado da busca'
			);
			$this->set($dados);

		} else {

			// --- Se não for post redireciona para buscar ---
			return $this->redirect(array('controller' => 'remedios', 'action' => 'buscar'));

		}

	}

	// --- novo ---------------------------------------------------------------
	public function novo($id = 0) {

		// --- Se o formulário for submetido ---
		if ($this->request->is(array('post', 'put')) && !empty($this->request->data)) {

			$dados = $this->request->data;
			$valida = true;

			// --- Checa se os campos foram preenchidos ---
			if (empty($dados['Remedio']['nome']) || empty($dados['Indicacao']['Indicacao']) || empty($dados['Remedio']['posologia']) || empty($dados['Remedio']['contraIndicacao']) || empty($dados['Remedio']['qtd']) || empty($dados['Remedio']['validade'])) {
				$this->Session->setFlash('Todos os campos devem ser preenchidos.', 'default', array('class' => 'alert alert-danger'));
				$valida = false;
			}

			// --- formata data ---
			$dados['Remedio']['validade'] = @date_format(date_create_from_format('d/m/Y', $dados['Remedio']['validade']), 'Y-m-d');

			// --- Se passou pela validação ---
			if ($valida) {

				// --- Monta o vetor com as informações a serem persistidas ---
				$dados['Remedio']['usuario_id'] = $this->Auth->user('id');
				if ($id) {
					$dados['Remedio']['id'] = $id;
					$msg = "O remédio <b>". $dados['Remedio']['nome'] ."</b> foi atualizada com sucesso.";
				} else {
					$msg = "O remédio <b>". $dados['Remedio']['nome'] ."</b> foi cadastrada com sucesso.";
				}

				// --- Cria ou atualiza a indicação ---
				if ($this->Remedio->save($dados)) {
					$this->Session->setFlash($msg, 'default', array('class' => 'alert alert-success'));

					// --- Nova quantidade de remédios ---
					$this->Session->write('qtdRemedios' ,$this->Remedio->find('count', array('conditions' => array('Usuario.id' => $this->Auth->user('id'), 'Remedio.qtd >' => 0))));
					$this->Session->write('qtdCompras' ,$this->Remedio->find('count', array('conditions' => array('Usuario.id' => $this->Auth->user('id'), 'Remedio.qtd' => 0))));

					return $this->redirect(array('controller' => 'remedios', 'action' => 'index'));
				} else {
					$this->Session->setFlash('Erro.', 'default', array('class' => 'alert alert-danger'));
					return $this->redirect(array('controller' => 'remedios', 'action' => 'index'));
				}
			}

		}

		// --- Recupera o valor para preencher o input ---
		if ($id) {
			$this->Remedio->id = $id;
			$remedio = $this->Remedio->read();
			$remedio['Remedio']['validade'] = date_format(date_create_from_format('Y-m-d', $remedio['Remedio']['validade']), 'd/m/Y');
			$this->request->data = $remedio;
		}

		// --- Envia para a view ---
		$this->loadModel('Indicacao');
		$dados = array (
			'titulo' => 'Remédios',
			'indicacoes' => $this->Indicacao->find('list', array ('conditions' => array ('Indicacao.usuario_id' => $this->Auth->user('id'))))
		);

		$this->set($dados);

	}

	// --- apagar -------------------------------------------------------------
	public function apagar($id) {

		// --- Checa se o id existe e se pertence ao usuário ---
		$this->Remedio->id = $id;
		if (!$this->Remedio->exists() || !$this->Remedio->find('count', array('conditions' => array('Remedio.id' => $id, 'Remedio.usuario_id' => $this->Auth->user('id'))))) {
			return $this->redirect(array('controller' => 'remedios', 'action' => 'index'));
		} else {
			// --- Recupera o nome do remédio ---
			$this->Remedio->id = $id;
			$remedio = $this->Remedio->read();

			// --- Apaga o remédio ---
			$this->Remedio->delete($id);
			$this->Session->setFlash('O remédio <b>'. $remedio['Remedio']['nome'] .'</b> foi apagado com sucesso.', 'default', array('class' => 'alert alert-success'));

			// --- Nova quantidade de remédios ---
			$this->Session->write('qtdRemedios' ,$this->Remedio->find('count', array('conditions' => array('Usuario.id' => $this->Auth->user('id'), 'Remedio.qtd >' => 0))));
			$this->Session->write('qtdCompras' ,$this->Remedio->find('count', array('conditions' => array('Usuario.id' => $this->Auth->user('id'), 'Remedio.qtd' => 0))));

			// --- Redireciona ---
			return $this->redirect(array('controller' => 'remedios', 'action' => 'index'));
		}

	}

	// --- acabou -------------------------------------------------------------
	public function acabou($id) {

		// --- Checa se o id existe e se pertence ao usuário ---
		$this->Remedio->id = $id;
		if (!$this->Remedio->exists() || !$this->Remedio->find('count', array('conditions' => array('Remedio.id' => $id, 'Remedio.usuario_id' => $this->Auth->user('id'))))) {
			return $this->redirect(array('controller' => 'remedios', 'action' => 'index'));
		} else {
			// --- Recupera remédio ---
			$this->Remedio->id = $id;
			$remedio = $this->Remedio->read();

			// --- Atualiza o remédio ---
			$this->Remedio->save(array('id' => $id, 'qtd' => 0));
			$this->Session->setFlash('O remédio <b>'. $remedio['Remedio']['nome'] .'</b> Acabou.', 'default', array('class' => 'alert alert-success'));

			// --- Nova quantidade de remédios ---
			$this->Session->write('qtdRemedios' ,$this->Remedio->find('count', array('conditions' => array('Usuario.id' => $this->Auth->user('id'), 'Remedio.qtd >' => 0))));
			$this->Session->write('qtdCompras' ,$this->Remedio->find('count', array('conditions' => array('Usuario.id' => $this->Auth->user('id'), 'Remedio.qtd' => 0))));

			// --- Redireciona ---
			return $this->redirect(array('controller' => 'remedios', 'action' => 'index'));
		}
	}

	// --- menos1 ----------------------------------------------------------------
	public function menos1($id) {

		// --- Checa se o id existe e se pertence ao usuário ---
		$this->Remedio->id = $id;
		if (!$this->Remedio->exists() || !$this->Remedio->find('count', array('conditions' => array('Remedio.id' => $id, 'Remedio.usuario_id' => $this->Auth->user('id'))))) {
			return $this->redirect(array('controller' => 'remedios', 'action' => 'index'));
		} else {
			// --- Recupera remédio ---
			$this->Remedio->id = $id;
			$remedio = $this->Remedio->read();

			// --- Atualiza o remédio ---
			$this->Remedio->save(array('id' => $id, 'qtd' => $remedio['Remedio']['qtd']-1));
			if ($remedio['Remedio']['qtd']-1 == 0) {
				// --- Nova quantidade de remédios ---
				$this->Session->write('qtdRemedios' ,$this->Remedio->find('count', array('conditions' => array('Usuario.id' => $this->Auth->user('id'), 'Remedio.qtd >' => 0))));
				$this->Session->write('qtdCompras' ,$this->Remedio->find('count', array('conditions' => array('Usuario.id' => $this->Auth->user('id'), 'Remedio.qtd' => 0))));
				$this->Session->setFlash('O remédio <b>'. $remedio['Remedio']['nome'] .'</b> Acabou.', 'default', array('class' => 'alert alert-success'));
			} else {
				$this->Session->setFlash('Foi retirado 1 do estoque de <b>'. $remedio['Remedio']['nome'] .'</b>.', 'default', array('class' => 'alert alert-success'));
			}

			// --- Redireciona ---
			return $this->redirect(array('controller' => 'remedios', 'action' => 'index'));
		}

	}

}
