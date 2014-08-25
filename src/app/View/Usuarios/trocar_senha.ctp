<div class="panel panel-primary">
	<div class="panel-heading"><h3>Trocar Senha</h3></div>
	<div class="panel-body">
<?php

echo $this->Form->create('Usuario', array('inputDefaults' => array('div' => false)));
echo $this->Form->input('senhaAtual', array('type' => 'password', 'class' => 'form-control', 'label' => 'Senha Atual:', 'autofocus'));
echo $this->Form->input('novaSenha', array('type' => 'password', 'class' => 'form-control', 'label' => 'Nova Senha:'));
echo $this->Form->input('ConfirmarNovaSenha', array('type' => 'password', 'class' => 'form-control', 'label' => 'Confirma Nova Senha:'));
echo $this->Form->submit('OK !', array('class' => 'btn btn-primary'));
echo $this->Form->end();

?>

	</div>
</div>