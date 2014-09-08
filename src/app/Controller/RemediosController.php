<?php

class RemediosController extends AppController {

	public $scaffold;
	public $uses = 'Remedio';

	// --- listar -------------------------------------------------------------
	public function listar() {

		// --- Parâmetros da busca ---
		$params = array (
			'conditions' => array ('Remedio.usuario_id' => $this->Auth->user('id'))
		);

		// --- Realiza a busca ---
		$remedios = $this->Remedio->find('all', $params);
		
		// --- Envia para a view ---
		$dados = array (
			'remedios' => $remedios,
			'titulo' => 'Lista de remédios'
		);
		$this->set($dados);

	}

	// --- ver ----------------------------------------------------------------
	public function ver($id) {

		// --- Verifica se o remédio existe ---
		$this->Remedio->id = $id;
		if (!$this->Remedio->exists()) {
			return $this->redirect('/remedios/listar');
		}

		// --- Busca o remédio com o id indicado ---
		$this->Remedio->id = $id;
		$remedio = $this->Remedio->read();

		// --- Envia para a view ---
		$dados = array (
			'remedio' => $remedio,
			'titulo' => $remedio['Remedio']['nome']
		);
		$this->set($dados);

	}

	// --- buscar -------------------------------------------------------------
	public function buscar() {

		// --- Busca as indicações ---
		$this->loadModel('Indicacao');
		$indicacoes = $this->Indicacao->findAllByUsuarioId($this->Auth->user('id'));

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
						'Indicacao.id' => $dados['Remedio']['Indicacao']
					)				)
			);

			// --- Envia para a view ---
			$dados = array (
				'indicacoes' => $remedios,
				'titulo' => 'Resultado da busca'
			);
			$this->set($dados);

			
		} else {

			// --- Se não for post redireciona para buscar ---
			return $this->redirect('/remedios/buscar');

		}

	}

}