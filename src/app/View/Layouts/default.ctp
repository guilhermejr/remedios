 <!DOCTYPE html>
<html lang="pt-BR">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Remédios</title>
		<?php
			echo $this->Html->css(array('bootstrap.min.css', 'bootstrap-theme.min.css', 'jquery-ui.css', 'estilo.css'));
			echo $this->Html->script(array('jquery.js', 'bootstrap.min.js', 'jquery-ui.js'));
		?>
		<script>
			function logout() {
				if(confirm('Deseja realmente sair do sistema?')) {
					window.location.href='/usuarios/logout'
				}
			}
		</script>
	</head>
	<body>
		<div class="container">
			<div class="header">
				<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
				  <div class="container-fluid">
				    <!-- Brand and toggle get grouped for better mobile display -->
				    <div class="navbar-header">
				      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				        <span class="sr-only">Toggle navigation</span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				      </button>
				      <a class="navbar-brand" href="/remedios/listar">Principal <span class="badge"><?php echo $this->Session->read('qtdRemedios'); ?></span></a>
				    </div>

				    <!-- Collect the nav links, forms, and other content for toggling -->
				    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				      <ul class="nav navbar-nav">
				        <li class="dropdown">
				          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Novo <span class="caret"></span></a>
				          <ul class="dropdown-menu" role="menu">
				            <li><a href="#">Remédio</a></li>
				            <li><a href="/indicacoes">Indicação</a></li>
				          </ul>
				        </li>
				        <li><a href="/remedios/buscar">Buscar</a></li>
				      </ul>
				      <ul class="nav navbar-nav navbar-right">
				        <li class="dropdown">
				          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo AuthComponent::user('nome'); ?> <span class="caret"></span></a>
				          <ul class="dropdown-menu" role="menu">
				            <li><a href="/usuarios/trocarSenha">Trocar Senha</a></li>
				            <li><a href="#" onclick="logout();">Sair</a></li>
				          </ul>
				        </li>
				      </ul>
				    </div><!-- /.navbar-collapse -->
				  </div><!-- /.container-fluid -->
				</nav>
			</div>
			<div class="content">
				<div class="panel panel-primary">
					<div class="panel-heading"><h3><?php echo $titulo; ?></h3></div>
					<div class="panel-body">
						<?php echo $this->Session->flash(); ?>
						<?php echo $this->fetch('content'); ?>
					</div>
				</div>
			</div>
			<div class="footer">
				desenvolvido por <?php echo $this->Html->link('Guilherme Jr.', 'http://www.guilhermejr.net', array('target' => '_blank')); ?>
			</div>
		</div>
		<?php //echo $this->element('sql_dump'); ?>
	</body>
</html>