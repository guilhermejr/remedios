<?php

class UsuariosController extends AppController {

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
				$this->Session->write('qtdRemedios' ,$this->Remedio->find('count', array('conditions' => array('Usuario.id' => $this->Auth->user('id'), 'Remedio.qtd >' => 0))));
				$this->Session->write('qtdCompras' ,$this->Remedio->find('count', array('conditions' => array('Usuario.id' => $this->Auth->user('id'), 'Remedio.qtd' => 0))));

				// --- Atualiza a data e hora do ultimo acesso ---
				$this->Usuario->id = $this->Auth->user('id');
				$this->Usuario->save(array('ultimoAcesso' => date('Y-m-d H:i:s')));

				// --- Redireciona para a tela principal ---
				return $this->redirect(array('controller' => 'remedios', 'action' => 'index'));

			} else {
				$this->Session->setFlash('Inválida combinação de <br> E-mail e Senha!', 'default', array('class' => 'alert alert-danger'));
			}
		}

	}

	// --- logout -------------------------------------------------------------
	public function logout() {
		return $this->redirect($this->Auth->logout());
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
				$this->Session->setFlash('Senha atualizada com sucesso.', 'default', array('class' => 'alert alert-success'));
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

				// --- Carrega o model Senha ---
				$this->loadModel("Senha");
				$senha = array();

				// --- Gera o hash para trocar senha ---
				$hash = sha1(date('dmYHisu'));

				// --- Verifica se já foi solicitado a troca da senha por esse usuário ---
				$senhas = $this->Senha->findByUsuarioId($usuario['Usuario']['id']);
				if (count($senhas)) {

					$senha['Senha']['id'] = $senhas['Senha']['id'];

				}

				// --- Salva informação no banco de dados ---
				$senha['Senha']['hash'] = $hash;
				$senha['Senha']['usuario_id'] = $usuario['Usuario']['id'];
				$this->Senha->save($senha);

				// --- Envia email ---
				App::uses('CakeEmail', 'Network/Email');
				$Email = new CakeEmail();
				$Email->from(array('remedios@guilhermejr.net' => 'Remédios'));
				$Email->to($usuario['Usuario']['email']);
				$Email->subject('Remédios - Solicitação de troca de senha.');
				$texto = "Olá ". $usuario['Usuario']['nome'] ."\n\n";
				$texto.= "Para cadastrar uma nova senha click no link abaixo:\n";
				$texto.= Router::fullbaseUrl() . "/usuarios/novaSenha/". $hash ."\n\n";
				$texto.="Mas se não tiver pedido para trocar de senha, é só ignorar este e-mail e continuar usando a sua senha atual.\n\n";
				$texto.="Remédios - https://remedios.guilhermejr.net";
				$Email->send($texto);

				// --- Mensagem de confirmação ---
				$this->Session->setFlash('Foi enviado um e-mail para <b>'. $usuario['Usuario']['email'] .'</b> com as instruções e o link para você trocar a senha.', 'default', array('class' => 'alert alert-success'));
				return $this->redirect($this->referer());
			}

		}

	}

	// --- novaSenha ----------------------------------------------------------
	public function novaSenha($hash) {

		// --- Seta o layout dessa action ---
		$this->layout = 'login';

		// --- Carrega o model Senha ---
		$this->loadModel('Senha');

		// --- Checa se o hash é válido ---
		$senhas = $this->Senha->findByHash($hash);
		if (count($senhas)) {

			// --- Se o formulário for submetido ---
			if ($this->request->is('post') && !empty($this->request->data)) {

				$dados = $this->request->data;

				// --- Checa se os campos foram preenchidos ---
				if (empty($dados['Usuario']['novaSenha']) || empty($dados['Usuario']['ConfirmarNovaSenha'])) {
					$this->Session->setFlash('Todos os campos devem ser preenchidos.', 'default', array('class' => 'alert alert-danger'));
					return $this->redirect($this->referer());
				}

				// --- Checa se as senhas são iguais ---
				if ($dados['Usuario']['novaSenha'] != $dados['Usuario']['ConfirmarNovaSenha']) {
					$this->Session->setFlash('A Senha e sua Confirmação devem ser iguais.', 'default', array('class' => 'alert alert-danger'));
					return $this->redirect($this->referer());
				}

				// --- Checa se a senha tem pelo menos 6 caracteres ---
				if (strlen($dados['Usuario']['novaSenha']) < 6) {
					$this->Session->setFlash('A nova senha deve ter no mínimo 6 caracteres.', 'default', array('class' => 'alert alert-danger'));
					return $this->redirect($this->referer());
				}

				// --- Atualiza a senha de acesso ---
				if ($this->Usuario->save(array('id' => $senhas['Senha']['usuario_id'], 'senha' => $dados['Usuario']['novaSenha']))) {

					// --- Apaga o registro que permite a troca da senha ---
					$this->Senha->delete($senhas['Senha']['id']);

					// --- Redireciona ---
					$this->Session->setFlash('Senha atualizada com sucesso.', 'default', array('class' => 'alert alert-success'));
					return $this->redirect('/usuarios/login');
				} else {
					$this->Session->setFlash('A Senha não pode ser atualizada.', 'default', array('class' => 'alert alert-danger'));
					return $this->redirect($this->referer());
				}

			}


		} else {

			// --- Redireciona para a tela de login ---
			return $this->redirect(array('controller' => 'usuarios', 'action' => 'login'));

		}

	}

	// --- adicionar ----------------------------------------------------------
	public function novo() {

		// --- Seta o layout dessa action ---
		$this->layout = 'login';

		if ($this->request->is('post') && !empty($this->request->data)) {

			$dados = $this->request->data;

			// --- Checa se os campos foram preenchidos ---
			if (empty($dados['Usuario']['nome']) || empty($dados['Usuario']['email']) || empty($dados['Usuario']['senha']) || empty($dados['Usuario']['confirmarSenha'])) {
				$this->Session->setFlash('Todos os campos devem ser preenchidos.', 'default', array('class' => 'alert alert-danger'));
				return $this->redirect($this->referer());
			}

			// --- Checa se as senhas são iguais ---
			if ($dados['Usuario']['senha'] != $dados['Usuario']['confirmarSenha']) {
				$this->Session->setFlash('A Senha e sua Confirmação devem ser iguais.', 'default', array('class' => 'alert alert-danger'));
				return $this->redirect($this->referer());
			}

			// --- Checa se a senha tem pelo menos 6 caracteres ---
			if (strlen($dados['Usuario']['senha']) < 6) {
				$this->Session->setFlash('A nova senha deve ter no mínimo 6 caracteres.', 'default', array('class' => 'alert alert-danger'));
				return $this->redirect($this->referer());
			}

			// --- Checa se o e-mail já foi cadastrado ---
			if ($this->Usuario->find('count', array('conditions' => array('Usuario.email' => $dados['Usuario']['email'])))) {
				$this->Session->setFlash('O e-mail<br> <b>'. $dados['Usuario']['email'] .'</b> <br> já está cadastrado.', 'default', array('class' => 'alert alert-danger'));
				return $this->redirect($this->referer());
			}

			// --- Salva os dados do usuário ---
			if ($this->Usuario->save($this->request->data)) {

				// --- Salva opções default ---
				$this->request->data['Configuracao']['id'] = $this->Usuario->id;
				$this->request->data['Configuracao']['usuario_id'] = $this->Usuario->id;
				if ($this->Usuario->Configuracao->save($this->request->data)) {

					// --- Envia email ---
					App::uses('CakeEmail', 'Network/Email');
					$Email = new CakeEmail();
					$Email->from(array('remedios@guilhermejr.net' => 'Remédios'));
					$Email->to($dados['Usuario']['email']);
					$Email->subject('Remédios - Novo usuário.');
					$texto = "Olá ". $dados['Usuario']['nome'] ."\n\n";
					$texto.= "Obrigado por ter se cadastrado. A partir de agora você pode acessar o sistema.\n\n";
					$texto.="Remédios - https://remedios.guilhermejr.net";
					$Email->send($texto);

					// --- Redireciona para a tela de login ---
					return $this->redirect(array('controller' => 'usuarios', 'action' => 'login'));

				}
			}
		}
	}

	// --- configuracoes ------------------------------------------------------
	public function configuracoes() {

		if ($this->request->is('put') && !empty($this->request->data)) {

			$dados = $this->request->data;

			// --- Checa se os campos foram preenchidos ---
			if (empty($dados['Configuracao']['dias']) || empty($dados['Configuracao']['periodicidade'])) {
				$this->Session->setFlash('Todos os campos devem ser preenchidos.', 'default', array('class' => 'alert alert-danger'));
				return $this->redirect($this->referer());
			}

			// --- Atualiza as configurações ---
			$this->request->data['Configuracao']['id'] = $this->Auth->user('id');
			$this->request->data['Configuracao']['usuario_id'] = $this->Auth->user('id');
			if ($this->Usuario->Configuracao->save($this->request->data)) {

				// --- Redireciona ---
				$this->Session->setFlash('Configurações atualizadas com sucesso.', 'default', array('class' => 'alert alert-success'));
				return $this->redirect('/usuarios/configuracoes');

			}
		}

		// --- Recupera as informações ---
		$this->Usuario->Configuracao->id = $this->Auth->user('id');
		$configuracao = $this->Usuario->Configuracao->read();
		$this->request->data = $configuracao;

		// --- Título da página ---
		$this->set('titulo', 'Configurações');

	}

	// --- compras ------------------------------------------------------------
	public function compras() {

		// --- Seleciona remédios ---
		$this->loadModel('Remedio');
		$remedios = $this->Remedio->find('all', array('order' => array('Remedio.nome' => 'ASC'), 'recursive' => -1, 'conditions' => array('qtd' => 0, 'usuario_id' => $this->Auth->user('id'))));

		// --- Envia para a view ---
		$dados = array (
			'remedios' => $remedios,
			'titulo' => 'Lista de Compras'
		);
		$this->set($dados);
	}

}
