<?php

class Indicacao extends AppModel {

	public $hasAndBelongsToMany = array(
		'Remedio' => array (
			'className' 			=> 'Remedio',
			'joinTable'				=> 'indicacoes_remedios',
			'foreignKey'			=> 'indicacao_id',
			'associationForeignKey'	=> 'remedio_id'
		)
	);
	public $useTable = 'indicacoes';
	public $displayField = 'descricao';


}