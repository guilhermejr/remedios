<?php

	// --- Inicia as variáveis ---
	$chave = array();
	$valor = array();

	// --- Preenche as variáveis ---
	foreach ($indicacoes as $indicacao) {

		$chave[] = $indicacao['Indicacao']['id'];
		$valor[] = $indicacao['Indicacao']['descricao'];

	}

	// --- Cria a variável ---
	$checkbox = array_combine($chave, $valor);

	// --- Lista as Indicações ---
	echo $this->Form->create('Remedio', array('action' => 'resultadoBusca', 'inputDefaults' => array('div' => false), 'class' => 'form-signin'));
	echo $this->Form->select('Remedio.Indicacao', $checkbox, array('multiple' => 'checkbox'));
	echo $this->Form->submit('Buscar !', array('class' => 'btn btn-primary'));
	echo $this->Form->end();