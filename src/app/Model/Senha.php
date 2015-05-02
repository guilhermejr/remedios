<?php

class Senha extends AppModel {

	public $belongsTo = array('Usuario');
	public $useTable = 'senhas';


}