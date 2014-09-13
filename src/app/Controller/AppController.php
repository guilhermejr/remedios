<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */

class AppController extends Controller {

	// --- Carrega o componente de Autenticação e Sessão ---
	public $components = array('Auth', 'Session');
	public $helpers = array ('Html', 'Form', 'Time');

	public function beforeFilter() { 

		// --- Faz o ajuste necessário para usar a tabela Usuarios para login ---
		$this->Auth->authenticate = array(
			'Form' => array(
				'userModel' => 'Usuario', 
				'fields' => array(
					'username' => 'email', 
					'password' => 'senha'
				),
				'scope' => array(
					'Usuario.ativo' => '1'
				)
			)
		);

		// --- Action onde fica a tela de login ---
		$this->Auth->loginAction = array(
			'controller' => 'usuarios', 
			'action' => 'login'
		);

		// --- Após login é redirecionado para esta action ---
		$this->Auth->loginRedirect = array(
			'controller' => 'remedios',
			'action' => 'listar'
		);

		// --- Após logout é redirecionado para esta action ---
		$this->Auth->logoutRedirect = array(
			'controller' => 'usuarios', 
			'action' => 'login'
		);

		// --- Mensagem de erro ao tentar acessar uma área sem efetuar o login antes ---
		$this->Auth->authError = 'É necessário realizar a autenticação.';
		$this->Auth->flash['params']['class'] = 'alert alert-danger';

		// --- Libera acesso as actions abaixo ---
		$this->Auth->allow(array('logout', 'lembrar', 'novaSenha', 'login'));
	}
}
