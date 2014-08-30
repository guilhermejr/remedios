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