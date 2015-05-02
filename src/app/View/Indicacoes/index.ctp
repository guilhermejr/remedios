<table class="table table-striped">
	<thead>
		<tr>
			<th>Nome</th>
		</tr>
	</thead>
	<tbody>
		<?php if (count($indicacoes)) { ?>
		<?php foreach ($indicacoes as $indicacao) { ?>
		<tr>
			<td><?php echo $this->Html->link($indicacao['Indicacao']['descricao'], array('controller' => 'indicacoes', 'action' => 'novo', $indicacao['Indicacao']['id'])); ?></td>
		</tr>
		<?php } ?>
		<?php } else { ?>
		<tr>
			<td>Nenhuma indicação encontrada.</td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<center><?php echo $this->Html->link('Novo !', array('controller' => 'indicacoes', 'action' => 'novo'), array('class' => 'btn btn-primary')); ?></center>
