<?php

class UsuariosController extends AppController {

	public $scaffold;
	public $uses = 'Usuario';
	public $name = 'Usuarios';

	// --- login --------------------------------------------------------------
	public function login() {

		// --- Seta o layout dessa action ---
		$this->layout = 'login';
		
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {

				// --- Carrega modelo Remedio ----
				$this->loadModel('Remedio');
				$this->Session->write('qtdRemedios' ,$this->Remedio->find('count', array('conditions' => array('Usuario.id' => $this->Auth->user('id')))));

				// --- Atualiza a data e hora do ultimo acesso ---
				$this->Usuario->id = $this->Auth->user('id');
				$this->Usuario->save(array('ultimoAcesso' => date('Y-m-d H:i:s')));

				// --- Redireciona para a tela principal ---
				$this->redirect('/remedios/listar');

			} else {
				$this->Session->setFlash('Inválida combinação de <br> E-mail e Senha!', 'default', array('class' => 'alert alert-danger'));
			}
		}

	}

	// --- logout -------------------------------------------------------------
	public function logout() {
		$this->redirect($this->Auth->logout());
	}

	// --- trocarSenha --------------------------------------------------------
	public function trocarSenha() {

		// --- Se o formulário for submetido ---
		if ($this->request->is('post') && !empty($this->request->data)) {

			$dados = $this->request->data;

			// --- Checa se os campos foram preenchidos ---
			if (empty($dados['Usuario']['senhaAtual']) || empty($dados['Usuario']['novaSenha']) || empty($dados['Usuario']['ConfirmarNovaSenha'])) {
				$this->Session->setFlash('Todos os campos têm que serem preenchidos.', 'default', array('class' => 'alert alert-danger'));
				return $this->redirect($this->referer());
			}

			// --- Checa se as senhas são iguais ---
			if ($dados['Usuario']['novaSenha'] != $dados['Usuario']['ConfirmarNovaSenha']) {
				$this->Session->setFlash('A Nova Senha e sua Confirmação devem ser iguais.', 'default', array('class' => 'alert alert-danger'));
				return $this->redirect($this->referer());
			}

			// --- Checa se a senha tem pelo menos 6 caracteres ---
			if (strlen($dados['Usuario']['novaSenha']) < 6) {
				$this->Session->setFlash('A nova senha deve ter no mínimo 6 caracteres.', 'default', array('class' => 'alert alert-danger'));
				return $this->redirect($this->referer());
			}

			// --- Checa se a senha atual está correta ----
			if (!$this->Usuario->find('count', array('conditions' => array('Usuario.id' => $this->Auth->user('id'), 'Usuario.senha' => AuthComponent::password($dados['Usuario']['senhaAtual']))))) {
				$this->Session->setFlash('A Senha Atual está incorreta.', 'default', array('class' => 'alert alert-danger'));
				return $this->redirect($this->referer());
			}

			// --- Atualiza a senha de acesso ---
			if ($this->Usuario->save(array('id' => $this->Auth->user('id'), 'senha' => $dados['Usuario']['novaSenha']))) {
				$this->Session->setFlash('Senha atualizada com sucesso.', 'default', array('class' => 'alert alert-danger'));
				//return $this->redirect('/remedios/listar');
				return $this->redirect($this->referer());
			} else {
				$this->Session->setFlash('A Senha não pode ser atualizada.', 'default', array('class' => 'alert alert-danger'));
				return $this->redirect($this->referer());
			}
		}

	}

	// --- adicionar ----------------------------------------------------------
	public function adicionar() {
		
		$isPost = $this->request->is('post');
		
		if ($isPost && !empty($this->request->data)) {

			if ($this->Usuario->save($this->request->data)) {
				//$this->redirect($this->referer());
			} 
		}
	}

}