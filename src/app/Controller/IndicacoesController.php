<?php

class IndicacoesController extends AppController {

	public $uses = 'Indicacao';

	// --- index --------------------------------------------------------------
	public function index() {

		// --- Parâmetros da busca ---
		$params = array (
			'conditions' => array ('Indicacao.usuario_id' => $this->Auth->user('id'))
		);

		// --- Realiza a busca ---
		$indicacoes = $this->Indicacao->find('all', $params);
		
		// --- Envia para a view ---
		$dados = array (
			'indicacoes' => $indicacoes,
			'titulo' => 'Indicações'
		);
		$this->set($dados);

	}

	// --- novo ---------------------------------------------------------------
	public function novo($id = 0) {

		// --- Inicializa a variável ---
		$indicacao = null;

		// --- Se o formulário for submetido ---
		if ($this->request->is('post') && !empty($this->request->data)) {

			$dados = $this->request->data;

			// --- Checa se os campos foram preenchidos ---
			$descricao = $dados['Indicacao']['descricao'];
			if (empty($descricao)) {
				$this->Session->setFlash('O campo deve ser preenchido.', 'default', array('class' => 'alert alert-danger'));
				return $this->redirect($this->referer());
			}

			// --- Monta o vetor com as informações a serem persistidas ---
			$indicacao = array('descricao' => $descricao, 'usuario_id' => $this->Auth->user('id'));
			if ($id) {
				$indicacao['id'] = $id;
				$msg = "A indicação <b>". $descricao ."</b> foi atualizada com sucesso.";
			} else {
				$msg = "A indicação <b>". $descricao ."</b> foi cadastrada com sucesso.";
			}

			// --- Cria ou atualiza a indicação ---
			if ($this->Indicacao->save($indicacao)) {
				$this->Session->setFlash($msg, 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('controller' => 'indicacoes', 'action' => 'index'));
			} else {
				$this->Session->setFlash('Erro.', 'default', array('class' => 'alert alert-danger'));
				return $this->redirect(array('controller' => 'indicacoes', 'action' => 'index'));
			}

		}

		// --- Recupera o valor para preencher o imnput ---
		if ($id) {
			$this->Indicacao->id = $id;
			$indicacao = $this->Indicacao->read();
		}

		// --- Envia para a view ---
		$dados = array (
			'descricao' => $indicacao['Indicacao']['descricao'],
			'id' => $id,
			'titulo' => 'Indicações'
		);

		$this->set($dados);

	}

	// --- apagar -------------------------------------------------------------
	public function apagar($id) {

		// --- Checa se o id existe ---
		$this->Indicacao->id = $id;
		if (!$this->Indicacao->exists()) {
			return $this->redirect(array('controller' => 'indicacoes', 'action' => 'index'));
		} else {
			// --- Recupera a descrição da indicação ---
			$this->Indicacao->id = $id;
			$indicacao = $this->Indicacao->read();

			// --- Apara a indicação e redireciona ---
			$this->Indicacao->delete($id);
			$this->Session->setFlash('A indicação <b>'. $indicacao['Indicacao']['descricao'] .'</b> foi apagada com sucesso.', 'default', array('class' => 'alert alert-success'));
			$this->redirect(array('controller' => 'indicacoes', 'action' => 'index'));
		}

	}	

}