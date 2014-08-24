<?php
/*
	echo "<pre>";
	print_r($remedios);
	echo "</pre>";
*/
?>

<h2 align="center">Lista de remédios</h2>

<table class="table table-striped">
	<thead>
		<tr>
			<th>Nome</th>
			<th>Vencimento</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($remedios as $remedio) { ?>
		<tr>
			<td><?php echo $this->Html->link($remedio['Remedio']['nome'], array('controller' => 'Remedios', 'action' => 'ver', $remedio['Remedio']['id'])); ?></td>
			<td><?php echo $this->Time->timeAgoInWords($remedio['Remedio']['validade'], array('format' => 'd/m/Y', 'end' => '+1 year')); ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>