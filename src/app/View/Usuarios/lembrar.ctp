<?php

echo $this->Session->flash();
echo $this->Form->create('Usuario', array('inputDefaults' => array('div' => false), 'class' => 'form-signin'));
?>

<div id="texto">Identifique-se para receber um e-mail com as instruções e o link para criar uma nova senha.</div>

<?php

echo $this->Form->input('email', array('type' => 'email', 'class' => 'form-control', 'label' => 'E-mail:', 'autofocus'));
echo "<center>";
echo $this->Html->link('Cancelar', array('controller' => 'usuarios', 'action' => 'login'), array('class' => 'btn btn-default'));
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo $this->Html->link('OK !', '#', array('class' => 'btn btn-primary', 'onclick' => 'document.getElementById("UsuarioLembrarForm").submit(); return false;'));
echo "</center>";
echo $this->Form->end();