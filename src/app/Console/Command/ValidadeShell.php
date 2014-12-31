<?php 

class ValidadeShell extends AppShell {

	// --- Carrega Model Remedio ---
	public $uses = array('Remedio', 'Usuario');

	// --- main ---------------------------------------------------------------
	public function main() {

		// --- Retorna os usuários cadastrados ativos ---
		$usuarios = $this->Usuario->findAllByAtivo('1');

		// --- Percorre os usuários ---
		foreach ($usuarios as $usuario) {

			// --- Remédios que estão a vencer ---
			$remedios = $this->Remedio->query("SELECT 
				usuarios.email,
			    usuarios.nome AS usuario,
			    remedios.nome AS remedio, 
			    DATE_FORMAT(remedios.validade, '%d/%m/%Y') AS validadeFormatada,
			    DATEDIFF(remedios.validade, CURDATE()) AS dias,
			    configuracoes.periodicidade AS periodicidade
			FROM 
			    usuarios  
				INNER JOIN remedios ON remedios.usuario_id = usuarios.id
				INNER JOIN configuracoes ON configuracoes.usuario_id = usuarios.id
			WHERE 
			    DATE_SUB(remedios.validade ,INTERVAL configuracoes.dias DAY) <= CURDATE() 
			    AND remedios.validade > CURDATE() 
			    AND configuracoes.avencer = '1'
			    AND usuarios.id = ". $usuario['Usuario']['id'] ." 
			ORDER BY 
			    remedios.validade");

			// --- Se houver mensagens a serem enviadas ---
			if (count($remedios)) {

				if (($remedios[0]['configuracoes']['periodicidade'] == 'mes' && date('d') == '01') || ($remedios[0]['configuracoes']['periodicidade'] == 'semana' && date('N') == '7') || $remedios[0]['configuracoes']['periodicidade'] == 'dia') {

					App::uses('CakeEmail', 'Network/Email');

					$Email = new CakeEmail();
					$Email->emailFormat('html');
					$Email->from(array('remedios@guilhermejr.net' => 'Remédios'));
					$Email->to($remedios[0]['usuarios']['email']);
					$Email->subject('Remédios - Aviso de validade.');
					$texto = "Olá <b>". $remedios[0]['usuarios']['usuario'] ."</b><br><br>";
					$texto.= "Só vim aqui avisar que o(s) remédio(s) abaixo está(ão) perto do vencimento:<br><br>";
					$texto.= "<table border=1 width=100%>";
					$texto.= "<tr>";
					$texto.= "<td><b>Remédio</b></td>";
					$texto.= "<td align=center><b>Validade</b></td>";
					$texto.= "<td align=center><b>Dias que Faltam</b></td>";
					$texto.= "</tr>";
					
					foreach ($remedios as $remedio) {

						$texto.= "<tr>";
						$texto.= "<td>". $remedio['remedios']['remedio'] ."</td>";
						$texto.= "<td align=center>". $remedio[0]['validadeFormatada'] ."</td>";
						$texto.= "<td align=center>". $remedio[0]['dias'] ."</td>";
						$texto.= "</tr>";

					}

					$texto.= "</table>";
					$texto.= "<br><br>";
					$texto.="Remédios - https://remedios.guilhermejr.net";
					$Email->send($texto);
				}
			}

			// --- Remédios com validade vencida ---
			$remedios = $this->Remedio->query("SELECT 
				usuarios.email,
			    usuarios.nome AS usuario,
			    remedios.nome AS remedio, 
			    DATE_FORMAT(remedios.validade, '%d/%m/%Y') AS validadeFormatada,
			    DATEDIFF(CURDATE(), remedios.validade) AS dias,
			    configuracoes.periodicidade AS periodicidade
			FROM 
			    usuarios  
				INNER JOIN remedios ON remedios.usuario_id = usuarios.id
				INNER JOIN configuracoes ON configuracoes.usuario_id = usuarios.id
			WHERE 
			    remedios.validade < CURDATE()
			    AND configuracoes.vencido = '1'
			    AND usuarios.id = ". $usuario['Usuario']['id'] ." 
			ORDER BY 
			    remedios.validade");

			// --- Se houver mensagens a serem enviadas ---
			if (count($remedios)) {

				if (($remedios[0]['configuracoes']['periodicidade'] == 'mes' && date('d') == '01') || ($remedios[0]['configuracoes']['periodicidade'] == 'semana' && date('N') == '7') || $remedios[0]['configuracoes']['periodicidade'] == 'dia') {

					App::uses('CakeEmail', 'Network/Email');

					$Email = new CakeEmail();
					$Email->emailFormat('html');
					$Email->from(array('remedios@guilhermejr.net' => 'Remédios'));
					$Email->to($remedios[0]['usuarios']['email']);
					$Email->subject('Remédios - Aviso de vencimento.');
					$texto = "Olá <b>". $remedios[0]['usuarios']['usuario'] ."</b><br><br>";
					$texto.= "Só vim aqui avisar que o(s) remédio(s) abaixo está(ão) vencido(s):<br><br>";
					$texto.= "<table border=1 width=100%>";
					$texto.= "<tr>";
					$texto.= "<td><b>Remédio</b></td>";
					$texto.= "<td align=center><b>Validade</b></td>";
					$texto.= "<td align=center><b>Dias de vencido</b></td>";
					$texto.= "</tr>";
					
					foreach ($remedios as $remedio) {

						$texto.= "<tr>";
						$texto.= "<td>". $remedio['remedios']['remedio'] ."</td>";
						$texto.= "<td align=center>". $remedio[0]['validadeFormatada'] ."</td>";
						$texto.= "<td align=center>". $remedio[0]['dias'] ."</td>";
						$texto.= "</tr>";

					}

					$texto.= "</table>";
					$texto.= "<br><br>";
					$texto.="Remédios - https://remedios.guilhermejr.net";
					$Email->send($texto);
				}
			}
		}
	}
}