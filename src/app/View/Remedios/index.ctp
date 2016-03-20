<table class="table table-striped">
	<thead>
		<tr>
			<th>Nome</th>
			<th>Quantidade</th>
			<th>Vencimento</th>
		</tr>
	</thead>
	<tbody>
		<?php if (count($remedios)) { ?>
		<?php foreach ($remedios as $remedio) { ?>
		<tr>
			<td>
				<?php echo $this->Html->link($remedio['Remedio']['nome'], array('controller' => 'remedios', 'action' => 'ver', $remedio['Remedio']['id'])); ?>
			</td>
			<td>
				<?php echo $remedio['Remedio']['qtd']; ?>
			</td>
			<td>
				<?php echo $this->Time->timeAgoInWords($remedio['Remedio']['validade'], array('format' => 'd/m/Y', 'end' => '+10 year')); ?>
			</td>
		</tr>
		<?php } ?>
		<?php } else { ?>
		<tr>
			<td colspan="3">Nenhum remédio encontrado.</td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<center><?php echo $this->Html->link('Novo !', array('controller' => 'remedios', 'action' => 'novo'), array('class' => 'btn btn-primary')); ?></center>
