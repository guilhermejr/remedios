 <!DOCTYPE html>
<html lang="pt-BR">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Remédios</title>
		<?php
			echo $this->Html->css(array('estilo.css', 'bootstrap.min.css', 'bootstrap-theme.min.css', 'jquery-ui.css'));
			echo $this->Html->script(array('bootstrap.min.js', 'jquery.js', 'jquery-ui.js'));
		?>
	</head>
	<body>
		<div class="container">
			<div class="header">
			</div>
			<div class="content">
				<?php echo $this->Session->flash(); ?>
				<?php echo $this->fetch('content'); ?>
			</div>
			<div class="footer">
				desenvolvido por <?php echo $this->Html->link('Guilherme Jr.', 'http://www.guilhermejr.net', array('target' => '_blank')); ?>
			</div>
		</div>
		<?php //echo $this->element('sql_dump'); ?>
	</body>
</html>