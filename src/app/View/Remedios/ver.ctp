<?php

	$nome = $remedio['Remedio']['nome'];
	$id = $remedio['Remedio']['id'];

?>
<script>
	function apagar() {
		if(confirm('Deseja realmente apagar o remédio <?php echo $nome; ?>?')) {
			window.location.href='/remedios/apagar/<?php echo $id; ?>'
		}
	}

	function acabou() {
		if(confirm('Deseja realmente informar que o remédio <?php echo $nome; ?> acabou?')) {
			window.location.href='/remedios/acabou/<?php echo $id; ?>'
		}
	}
</script>

<p>
	<b>Nome: </b><br>
	<?php echo $remedio['Remedio']['nome']; ?>
</p>

<p>
	<b>Indicado para: </b>
	<ul>
		<?php
			foreach ($remedio['Indicacao'] as $indicacao) {
				echo "<li>" . $indicacao['descricao'] . "</li>";
			}
		?>
	</ul>
</p>

<p>
	<b>Posologia: </b><br>
	<?php echo $remedio['Remedio']['posologia']; ?>
</p>

<p>
	<b>Contra-Indicação: </b><br>
	<?php echo $remedio['Remedio']['contraIndicacao']; ?>
</p>

<p>
	<b>Validade:</b><br>
	<?php echo $this->Time->format($remedio['Remedio']['validade'], '%d/%m/%Y'); ?> <small>(<?php echo $this->Time->timeAgoInWords($remedio['Remedio']['validade'], array('format' => 'd/m/Y', 'end' => '+10 year')); ?>)</small>
</p>

<center>
<?php echo $this->Html->link('Cancelar', 'javascript:window.history.back()', array('class' => 'btn btn-default')); ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php echo $this->Html->link('Editar', array('controller' => 'remedios', 'action' => 'novo', $id), array('class' => 'btn btn-default')); ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php echo $this->Html->link('Apagar', '#', array('class' => 'btn btn-danger', 'onclick' => 'apagar();')); ?>
<?php if (!$acabou) { ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php echo $this->Html->link('Acabou', '#', array('class' => 'btn btn-warning', 'onclick' => 'acabou();')); ?>
<?php } ?>
</center>