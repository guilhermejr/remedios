<?php

	foreach ($indicacoes as $indicacao) {

		echo "<div id=indicacao>" . $indicacao['Indicacao']['descricao'] . "</div>";

		if (count($indicacao['Remedio'])) {
			echo "<ul>";
			foreach ($indicacao['Remedio'] as $remedio) {
				$hoje = new DateTime(date('Y-m-d'));
				$validade = new DateTime($remedio['validade']);
				if ($hoje > $validade) {
					echo "<li><strike>" . $this->Html->link($remedio['nome'], array('controller' => 'remedios', 'action' => 'ver', $remedio['id'])) . "</strike></li>";
				} else {
					echo "<li>" . $this->Html->link($remedio['nome'], array('controller' => 'remedios', 'action' => 'ver', $remedio['id'])) . "</li>";
				}
				
			}
			echo "</ul>";
		} else {
			echo "Nenhum remédio encontrado.";
		}

		echo "<br>";
		
	}

	echo "<center>";
	echo $this->Html->link('Cancelar', array('controller' => 'remedios', 'action' => 'buscar'), array('class' => 'btn btn-default'));
	echo "</center>";