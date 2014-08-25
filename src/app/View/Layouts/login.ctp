 <!DOCTYPE html>
<html lang="pt-BR">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Remédios</title>
		<?php
			echo $this->Html->css(array('bootstrap.min.css', 'bootstrap-theme.min.css', 'jquery-ui.css', 'login.css'));
			echo $this->Html->script(array('bootstrap.min.js', 'jquery.js', 'jquery-ui.js'));
		?>
	</head>
	<body>
		<div class="container">
			<div class="content">
				<div class="panel panel-primary">
					<div class="panel-heading"><h3>Acesso ao Sistema</h3></div>
					<div class="panel-body">
						<?php echo $this->fetch('content'); ?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>