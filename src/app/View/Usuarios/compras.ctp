<table class="table table-striped">
	<thead>
		<tr>
			<th>Nome</th>
		</tr>
	</thead>
	<tbody>
		<?php if (count($remedios)) { ?>
		<?php foreach ($remedios as $remedio) { ?>
		<tr>
			<td>
				<?php echo $this->Html->link($remedio['Remedio']['nome'], array('controller' => 'remedios', 'action' => 'ver', $remedio['Remedio']['id'])); ?>
			</td>
		</tr>
		<?php } ?>
		<?php } else { ?>
		<tr>
			<td>Nenhuma remédio encontrado.</td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<center><a href="#" onclick="window.print();" class="btn btn-primary">Imprimir !</a></center>