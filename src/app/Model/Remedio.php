<?php

class Remedio extends AppModel {

	public $belongsTo = array('Usuario');
	public $hasAndBelongsToMany = array(
		'Indicacao' => array(
			'className' 			=> 'Indicacao',
			'joinTable'				=> 'indicacoes_remedios',
			'foreignKey'			=> 'remedio_id',
			'associationForeignKey'	=> 'indicacao_id'
		)
	);
	public $useTable = 'remedios';
	public $order = array('Remedio.nome' => 'ASC');
	public $displayField = 'nome';

}