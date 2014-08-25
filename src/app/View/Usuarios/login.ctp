<?php

echo $this->Session->flash();
echo $this->Session->flash('auth');

echo $this->Form->create('Usuario', array('inputDefaults' => array('div' => false), 'class' => 'form-signin'));
echo $this->Form->input('email', array('type' => 'email', 'class' => 'form-control', 'label' => 'E-mail:', 'autofocus'));
echo $this->Form->input('senha', array('type' => 'password', 'class' => 'form-control', 'label' => 'Senha:'));
//echo $this->Form->input('permanecerConectado', array('type' => 'checkbox', 'class' => 'checkbox', 'label' => 'Permanecer Conectado'));
echo $this->Form->submit('Entrar !', array('class' => 'btn btn-lg btn-primary btn-block'));
echo $this->Form->end();