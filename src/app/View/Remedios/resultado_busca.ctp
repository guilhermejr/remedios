<?php

	foreach ($indicacoes as $indicacao) {

		echo "<div id=indicacao>" . $indicacao['Indicacao']['descricao'] . "</div>";

		if (count($indicacao['Remedio'])) {
			echo "<ul>";
			foreach ($indicacao['Remedio'] as $remedio) {
				echo "<li>" . $this->Html->link($remedio['nome'], array('controller' => 'remedios', 'action' => 'ver', $remedio['id'])) . "</li>";
			}
			echo "</ul>";
		} else {
			echo "Nenhum remédio encontrado.";
		}

		echo "<br>";
		
	}

	echo "<center>";
	echo $this->Html->link('Cancelar', array('controller' => 'remedios', 'action' => 'buscar'), array('class' => 'btn btn-primary'));
	echo "</center>";