<?php

echo $this->Form->create('Remedio', array('inputDefaults' => array('div' => false)));
echo $this->Form->input('nome', array('class' => 'form-control', 'label' => 'Nome:', 'autofocus'));
echo $this->Form->input('Indicacao.Indicacao', array('class' => 'form-control', 'label' => 'Indicações:', 'multiple' => true, 'options' => $indicacoes));
echo $this->Form->input('posologia', array('class' => 'form-control', 'label' => 'Posologia:'));
echo $this->Form->input('contraIndicacao', array('class' => 'form-control', 'label' => 'Contra-Indicação:'));
echo $this->Form->input('validade', array('class' => 'form-control data', 'label' => 'Validade:', 'type' => 'text', 'id' => 'data'));
echo "<center>";
echo $this->Html->link('OK !', '#', array('class' => 'btn btn-primary', 'onclick' => 'document.getElementById("RemedioNovoForm").submit(); return false;'));
echo "</center>";
echo $this->Form->end();

?>
<script>
	$(function() {
	    $("#data").datepicker({
	        changeMonth: true,
	        changeYear: true,
	        regional: "pt-BR"
	    });
	});
</script>