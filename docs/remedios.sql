CREATE TABLE IF NOT EXISTS `indicacoes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `descricao` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;


CREATE TABLE IF NOT EXISTS `indicacoes_remedios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `indicacao_id` int(10) unsigned NOT NULL,
  `remedio_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`,`indicacao_id`,`remedio_id`),
  KEY `fk_indicacoes_has_remedios_remedios1_idx` (`remedio_id`),
  KEY `fk_indicacoes_has_remedios_indicacoes1_idx` (`indicacao_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=60 ;


CREATE TABLE IF NOT EXISTS `remedios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `contraIndicacao` text NOT NULL,
  `posologia` text NOT NULL,
  `validade` date NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `usuario_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_remedios_usuarios_idx` (`usuario_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` char(40) NOT NULL,
  `ativo` char(1) NOT NULL DEFAULT '1',
  `ultimoAcesso` datetime NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `ativo`, `ultimoAcesso`, `created`, `updated`) VALUES
(7, 'Guilherme Jr.', 'falecom@guilhermejr.net', '848d3ee0d1ac67f1f78c1b43e8001a7a6fae1050', '1', '2014-08-31 12:15:08', '2014-08-18 16:38:15', '2014-08-31 12:15:08');