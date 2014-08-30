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
			<td><?php echo $this->Html->link($remedio['Remedio']['nome'], array('controller' => 'remedios', 'action' => 'ver', $remedio['Remedio']['id'])); ?></td>
			<td><?php echo $this->Time->timeAgoInWords($remedio['Remedio']['validade'], array('format' => 'd/m/Y', 'end' => '+10 year')); ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>