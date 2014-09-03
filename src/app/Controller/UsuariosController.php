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
				$this->Session->setFlash('Todos os campos devem ser preenchidos.', 'default', array('class' => 'alert alert-danger'));
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

		// --- Título da página ---
		$this->set('titulo', 'Trocar Senha');

	}

	// --- lembrar ------------------------------------------------------------
	function lembrar() {

		// --- Seta o layout dessa action ---
		$this->layout = 'login';

		// --- Se o formulário for submetido ---
		if ($this->request->is('post') && !empty($this->request->data)) {

			$dados = $this->request->data;

			// --- Checa se o e-mail foi preenchidos ---
			if (empty($dados['Usuario']['email'])) {
				$this->Session->setFlash('É necessário informar um E-mail', 'default', array('class' => 'alert alert-danger'));
				return $this->redirect($this->referer());
			}

			// --- Pega os dados do usuário do banco de dados ---
			$usuario = $this->Usuario->findByEmail($dados['Usuario']['email']);

			// --- Checa se o e-mail é válido ---
			if (empty($usuario)) {
				$this->Session->setFlash('E-mail inválido.', 'default', array('class' => 'alert alert-danger'));
				return $this->redirect($this->referer());
			} else {

				App::uses('CakeEmail', 'Network/Email');

				$Email = new CakeEmail();
				$Email->from(array('remedios@guilhermejr.net' => 'Remédios'));
				$Email->to($usuario['Usuario']['email']);
				$Email->subject('Remédios - Solicitação de troca de senha.');
				$texto = "Olá ". $usuario['Usuario']['nome'] ."\n\n";
				$texto.= "Para cadastrar uma nova senha click no link abaixo:\n";
				$texto.= Router::fullbaseUrl() . "/usuarios/novaSenha/". sha1(date('dmYHisu')) ."\n\n";
				$texto.="Mas se não tiver pedido para trocar de senha, é só ignorar este e-mail e continuar usando a sua senha atual.\n\n";
				$texto.="Guilherme Jr.";
				$Email->send($texto);

				$this->Session->setFlash('Foi enviado um e-mail para <b>'. $usuario['Usuario']['email'] .'</b> com as instruções e o link para você trocar a senha.', 'default', array('class' => 'alert alert-success'));
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