<?php

echo $this->Session->flash();
echo $this->Session->flash('auth');

echo $this->Form->create('Usuario', array('inputDefaults' => array('div' => false), 'class' => 'form-signin'));
echo $this->Form->input('email', array('type' => 'email', 'class' => 'form-control', 'label' => 'E-mail:', 'autofocus'));
echo $this->Form->input('senha', array('type' => 'password', 'class' => 'form-control', 'label' => 'Senha:'));
//echo $this->Form->input('permanecerConectado', array('type' => 'checkbox', 'class' => 'checkbox', 'label' => 'Permanecer Conectado'));
echo "<center>". $this->Html->link('Esqueceu a senha?', array('controller' => 'Usuarios', 'action' => 'lembrar')) ."</center>";
echo $this->Form->submit('Entrar !', array('class' => 'btn btn-lg btn-primary btn-block'));
echo "<center>". $this->Html->link('Criar uma conta', array('controller' => 'Usuarios', 'action' => 'novo')) ."</center>";
echo $this->Form->end();