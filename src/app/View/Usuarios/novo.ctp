<?php

echo $this->Session->flash();
echo $this->Form->create('Usuario', array('inputDefaults' => array('div' => false), 'class' => 'form-signin'));

echo $this->Form->input('nome', array('type' => 'text', 'class' => 'form-control', 'label' => 'Nome:', 'autofocus'));
echo $this->Form->input('email', array('type' => 'email', 'class' => 'form-control', 'label' => 'E-mail:'));
echo $this->Form->input('senha', array('type' => 'password', 'class' => 'form-control', 'label' => 'Senha:'));
echo $this->Form->input('confirmarSenha', array('type' => 'password', 'class' => 'form-control', 'label' => 'Confrmar Senha:'));
echo "<br><center>";
echo $this->Html->link('Cancelar', array('controller' => 'usuarios', 'action' => 'login'), array('class' => 'btn btn-default'));
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo $this->Html->link('OK !', '#', array('class' => 'btn btn-primary', 'onclick' => 'document.getElementById("UsuarioNovoForm").submit(); return false;'));
echo "</center>";
echo $this->Form->end();