<?php

echo $this->Session->flash();
echo $this->Form->create('Usuario', array('inputDefaults' => array('div' => false), 'class' => 'form-signin'));
?>

<div id="texto">Crie uma nova senha para a sua conta.</div>

<?php

echo $this->Form->input('novaSenha', array('type' => 'password', 'class' => 'form-control', 'label' => 'Senha:', 'autofocus'));
echo $this->Form->input('ConfirmarNovaSenha', array('type' => 'password', 'class' => 'form-control', 'label' => 'Confirmar Senha:'));
echo "<center>";
echo $this->Form->submit('OK !', array('class' => 'btn btn-primary'));
echo "</center>";
echo $this->Form->end();