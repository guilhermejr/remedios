# Variáveis para a criação do banco de dados
$usuarioBancodedados = "remedios"
$senhaBancodedados = "remedios"
$nomeBancodedados = "remedios"
$senhaRootBancodedados = "123"

# Mapeia o path dos programas
Exec { path => [ "/bin/", "/sbin/", "/usr/bin/", "/usr/sbin/" ] }

# Atualiza a lista do apt
exec { "apt-update": 
	command	=> "apt-get update",
}

# Instala os serviços necessários
package { ["mysql-server", "phpmyadmin", "apache2", "openssl"]:
	ensure	=> installed,
	require	=> Exec["apt-update"],
}

# Certifica que o mysql está rodando
service { "mysql":
	ensure		=> running,
	enable		=> true,
	hasstatus	=> true,
	hasrestart	=> true,
	require		=> Package["mysql-server"],
}

# Certifica que o apache2 está rodando
service { "apache2":
	ensure		=> running,
	enable		=> true,
	hasstatus	=> true,
	hasrestart	=> true,
	require		=> Package["apache2"],
}

# Adiciona o cake ao PATH
exec { "cake":
	unless	=> "grep -c 'PATH' /etc/bash.bashrc",
	command	=> "echo 'export PATH=\"\$PATH:/var/www/app/Console\"' >> /etc/bash.bashrc",
}

# Apaga o arquivo de lock do apache para que seja possível trocar o usuário do apache
exec { "apagarLockApache":
	onlyif	=> "grep -c 'www-data' /etc/apache2/envvars",
	command	=> "rm -rf /var/lock/apache2",
	require	=> Package["apache2"],
}

# Troca o usuário do apache para evitar erro de permissão
exec { "usuarioApache":
	onlyif	=> "grep -c 'www-data' /etc/apache2/envvars",
	command	=> "sed -i 's/www-data/vagrant/' /etc/apache2/envvars",
	require	=> Exec["apagarLockApache"],
	notify	=> Service["apache2"],
}

# Apaga o arquivo index.html dentro de /var/www
exec { "apagarIndex":
	onlyif	=> "cat /var/www/index.html",
	command	=> "rm -f /var/www/index.html",
	require	=> Package["apache2"],
	notify	=> Service["apache2"],
}

# Habilita o mod_rewrite no apache
exec { "habilitaModRewrite":
	command	=> "a2enmod rewrite",
	require	=> Package["apache2"],
	notify	=> Service["apache2"],
}

# Habilita o ssl no apache
exec { "habilitaSSL":
	command	=> "a2enmod ssl",
	require	=> Package["apache2"],
	notify	=> Service["apache2"],
}

# Cria a pasta para colocar o certificado
exec { "criarPastaCertificado":
	unless	=> "ls /etc/apache2/ssl",
	command	=> "mkdir -p /etc/apache2/ssl",
	require	=> Package["apache2"],
}

# Cria o certificado SSL
exec { "criarCertificadoSSL":
	unless	=> "cat /etc/apache2/ssl/apache.key",
	command => "openssl req -x509 -nodes -days 365 -newkey rsa:2048 -subj \"/C=BR/ST=Bahia/L=Salvador/O=GuilhermeJr/CN=guilhermejr\" -keyout /etc/apache2/ssl/apache.key -out /etc/apache2/ssl/apache.crt",
	require => Exec["criarPastaCertificado"],
	notify	=> Service["apache2"],
}

# Novas configurações para o apache
file { "/etc/apache2/sites-enabled/000-default":
	owner	=> root,
	group	=> root,
	mode 	=> 0644,
	content	=> template("/vagrant/manifests/000-default"),
	require	=> Package["apache2"],
}

# Configura o phpmyadmin
exec { "confPhpmyadmin":
	unless	=> "grep -c 'phpmyadmin' /etc/apache2/apache2.conf",
	command	=> "echo \"Include /etc/phpmyadmin/apache.conf\" >> /etc/apache2/apache2.conf",
	require	=> Package["apache2"],
	notify	=> Service["apache2"],
}

# Seta senha para o root do mysql
exec { "senhaRootMysql":
	onlyif	=> "mysql -uroot -e \"show databases;\"",
	command	=> "mysqladmin -u root password '$senhaRootBancodedados'",
	require	=> Package["mysql-server"],
}

# Cria o banco de dados 
exec { "bancodedados":
	unless	=> "mysql -uroot -p$senhaRootBancodedados $nomeBancodedados",
	command	=> "mysqladmin -uroot -p$senhaRootBancodedados create $nomeBancodedados",
	require	=> Exec["senhaRootMysql"],
}

# Cria o usuário para o banco de dados criado no comando anterior
exec { "criarUsuarioBancodedados":
	unless	=> "mysql -u$usuarioBancodedados -p$senhaBancodedados $nomeBancodedados",
	command	=> "mysql -uroot -p$senhaRootBancodedados -e \"GRANT ALL PRIVILEGES ON $nomeBancodedados.* TO '$usuarioBancodedados'@'localhost' IDENTIFIED BY '$senhaBancodedados';\"",
	require	=> Exec["bancodedados"],
}

# Importa a estrutura do banco de dados 
exec { "importarSQL":
	unless	=> "mysql -u$usuarioBancodedados -p$senhaBancodedados remedios -e \"SELECT * FROM usuarios\"",
	command	=> "mysql -u$usuarioBancodedados -p$senhaBancodedados -D $nomeBancodedados < /vagrant/docs/remedios.sql",
	require	=> Exec["criarUsuarioBancodedados"],
}
