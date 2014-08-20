<?php

class Usuario extends AppModel {

	// --- Encripta a senha ---------------------------------------------------
	public function beforeSave($options = array()) {

		$senha = $this->data[$this->alias]['senha'];

		if (!empty($senha)) {
			$senha = AuthComponent::password($senha);
			$this->data[$this->alias]['senha'] = $senha;
		}

		return parent::beforeSave($options);

	}

	public $useTable = 'usuarios';
	public $hasMany = array('Remedio');
	public $displayField = 'nome';

	public $validade = array (
		'nome' => array (
			'rule' => 'notEmpty',
			'message' => 'É necessário informar um NOME.'
		),
		'email' => array (
			array (
				'rule' => 'notEmpty',
				'message' => 'É necessário informar um E-MAIL.'
			),
			array (
				'rule' => 'email',
				'message' => 'Este não é um E-MAIL válido.'
			),
			array (
				'rule' => 'isUnique',
				'message' => 'Está nome de usuário já está em uso.'
			)
		),
		'senha' => array (
			'rule' => 'notEmpty',
			'message' => 'É necessário informar um SENHA.'
		),
		'novaSenha' => array (
			'rule' => 'notEmpty',
			'message' => 'É necessário informar uma NOVA SENHA.'
		)
	);

}