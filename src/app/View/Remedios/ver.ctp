<h2 align="center"><?php echo $dados['Remedio']['nome']; ?></h2>
<h6 align="center"><?php echo $this->Time->format($dados['Remedio']['validade'], '%d/%m/%Y'); ?></h6>

<p>
	<b>Indicado para: </b><br>
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