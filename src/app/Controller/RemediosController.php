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
		$this->set('remedios', $remedios);

	}

	// --- ver ----------------------------------------------------------------
	public function ver($id) {

		// --- Busca o remédio com o id indicado ---
		$this->Remedio->id = $id;
		$dados = $this->Remedio->read();

		// --- Envia para a view ---
		$this->set('dados', $dados);

	}

}