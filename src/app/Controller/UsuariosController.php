<?php

class UsuariosController extends AppController {

	public $scaffold;
	public $layout = 'login';
	public $uses = 'Usuario';
	public $name = 'Usuarios';

	// --- login --------------------------------------------------------------
	public function login() {
		
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$this->redirect($this->Auth->redirectUrl());
			} else {
				$this->Session->setFlash('Inválida combinação de <br> E-mail e Senha!', 'default', array('class' => 'alert alert-danger'));
			}
		}

	}

	// --- logout -------------------------------------------------------------
	public function logout() {
		$this->redirect($this->Auth->logout());
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