<?php

class Configuracao extends AppModel {

	public $belongsTo = array('Usuario');
	public $useTable = 'configuracoes';


}