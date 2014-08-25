<div class="panel panel-primary">
	<div class="panel-heading">
		<h3><?php echo $dados['Remedio']['nome']; ?></h3>
	</div>
	<div class="panel-body">

		<p>
			<b>Indicado para: </b>
			<ul>
				<?php
					foreach ($dados['Indicacao'] as $indicacao) {
						echo "<li>" . $indicacao['descricao'] . "</li>";
					}
				?>
			</ul>
		</p>

		<p>
			<b>Posologia: </b><br>
			<?php echo $dados['Remedio']['posologia']; ?>
		</p>

		<p>
			<b>Contra-Indicação: </b><br>
			<?php echo $dados['Remedio']['contraIndicacao']; ?>
		</p>

		<p>
			<b>Validade:</b><br>
			<?php echo $this->Time->format($dados['Remedio']['validade'], '%d/%m/%Y'); ?> <small>(<?php echo $this->Time->timeAgoInWords($dados['Remedio']['validade'], array('format' => 'd/m/Y', 'end' => '+10 year')); ?>)</small>
		</p>

	</div>
</div>