<?php

echo $this->Form->create('Configuracao', array('inputDefaults' => array('div' => false)));
echo $this->Form->checkbox('vencido') ." <label for=ConfiguracaoVencido>Enviar e-mail quando um remédios estiver vencido.</label><br>";
echo $this->Form->checkbox('avencer') ." <label for=ConfiguracaoAvencer>Enviar e-mail quando um remédios estiver para vencido.</label><br>";
echo $this->Form->input('dias', array('type' => 'text', 'class' => 'form-control', 'label' => 'Dias:'));
$options = array('dia' => 'Dia', 'semana' => 'Semana', 'mes' => 'Mês');
echo $this->Form->input('periodicidade', array('options' => $options, 'class' => 'form-control', 'label' => 'Periodicidade:'));
echo $this->Form->submit('OK !', array('class' => 'btn btn-primary'));
echo $this->Form->end();