<script>
	function apagar() {
		if(confirm('Deseja realmente apagar a indicação <?php echo $descricao; ?>?')) {
			window.location.href='/indicacoes/apagar/<?php echo $id; ?>'
		}
	}
</script>
<?php

echo $this->Form->create('Indicacao', array('inputDefaults' => array('div' => false)));
echo $this->Form->input('descricao', array('class' => 'form-control', 'label' => 'Descrição:', 'autofocus'));
echo "<center>";
echo $this->Html->link('Cancelar', array('controller' => 'indicacoes', 'action' => 'index'), array('class' => 'btn btn-default'));
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
if (!empty($id)) {
	echo $this->Html->link('Apagar', '#', array('class' => 'btn btn-danger', 'onclick' => 'apagar();'));
	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
}
echo $this->Html->link('OK !', '#', array('class' => 'btn btn-primary', 'onclick' => 'document.getElementById("IndicacaoNovoForm").submit(); return false;'));
echo "</center>";
echo $this->Form->end();