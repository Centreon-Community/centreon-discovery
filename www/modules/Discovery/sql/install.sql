/*  This file is part of Centreon-Discovery.
 *
 *	 Centreon-Discovery is developped with GPL Licence 3.0 :
 *	 Developped by : Jonathan Teste - Cedric Dupre
 *	 Modified by : Sub2.13
 *   
 */


/*
 * DATABASE : @DB_NAME_CENTREON@
 * TABLE : topology
 *	
 *	Insertion des pages du module Centreon Discovery
 *
 */
INSERT INTO `topology` (`topology_id`, `topology_name`, `topology_icone`, `topology_parent`, `topology_page`, `topology_order`, `topology_group`, `topology_url`, `topology_url_opt`, `topology_popup`, `topology_modules`, `topology_show`) 
VALUES ('', 'Centreon Discovery', NULL, 6, 612, 100, 1, './modules/Discovery/include/ips.php', NULL, '0', '1', '1') ,
('', 'IP addresses', './img/icones/16x16/client_network.gif', 612, 61201, 1, 1, './modules/Discovery/include/ips.php', NULL, '0', '1', '1') ,
('', 'Results', './img/icones/16x16/column.gif', 612, 61202, 101, 1, './modules/Discovery/include/results.php', NULL, '0', '1', '1') ,
('', 'Configuration', './img/icones/16x16/gear.gif', 612, 61203, 101, 1, './modules/Discovery/include/configuration.php', NULL, '0', '1', '1') , 
('', 'A propos', './img/icones/16x16/about.gif', 612, 61204, 101, 1, './modules/Discovery/include/informations.htm', NULL, '0', '1', '1');


/*
 * DATABASE : @DB_NAME_CENTREON@
 * TABLE : mod_discovery_*
 *	
 *	Création des tables utiles au fonctionnement du module
 *
 */


/*
 * DATABASE : @DB_NAME_CENTREON@
 * TABLE : mod_discovery_rangeip
 *	
 * Création de la table mod_discovery_rangeip contenant les informations relatives aux plages IP à scanner
 *
 */
CREATE TABLE IF NOT EXISTS `mod_discovery_rangeip` (
  `id` int(11) AUTO_INCREMENT,
  `plage` varchar(15) COLLATE utf8_bin NOT NULL,
  `masque` varchar(15) COLLATE utf8_bin NOT NULL,
  `cidr` int(2) NOT NULL,
  `ping` int(1) NOT NULL,
  `ping_count` int(1) NOT NULL,
  `ping_wait` int(1) NOT NULL,
  `tcp` int(1) NOT NULL,
  `tcp_port` varchar(256) COLLATE utf8_bin DEFAULT NULL,
  `snmp` int(1) NOT NULL,
  `snmp_version` int(1) DEFAULT NULL,
  `snmp_community` varchar(256) COLLATE utf8_bin DEFAULT NULL,
  `oid_hostname` varchar(256) COLLATE utf8_bin DEFAULT NULL,
  `oid_os` varchar(256) COLLATE utf8_bin DEFAULT NULL,
  `snmp_method` varchar(5) COLLATE utf8_bin DEFAULT NULL,
  `nagios_server_id` int(11) DEFAULT NULL,
  `done` int(1) NOT NULL DEFAULT '0',
  `poller_status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=0 ;

INSERT INTO `mod_discovery_rangeip` (`id`, `plage`, `masque`, `cidr`, `ping`, `ping_count`, `ping_wait`, `tcp`, `tcp_port`, `snmp`, `snmp_version`, `snmp_community`, `oid_hostname`, `oid_os`, `snmp_method`, `nagios_server_id`, `done`) VALUES
(0, 'default', '0', 0, 0, 1, 500, 0, NULL, 0, 2, 'public', '.1.3.6.1.2.1.1.5.0', '.1.3.6.1.2.1.1.1.0', NULL, NULL, 0);
UPDATE  `@DB_NAME_CENTREON@`.`mod_discovery_rangeip` SET  `id` =  '0' WHERE  `mod_discovery_rangeip`.`id` =1 LIMIT 1 ;

/*
 * DATABASE : @DB_NAME_CENTREON@
 * TABLE : mod_discovery_results
 *	
 * Création de la table mod_discovery_results contenant les informations résultantes du scan
 *
 */
CREATE TABLE IF NOT EXISTS `mod_discovery_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(20) NOT NULL,
  `hostname` varchar(30) DEFAULT NULL,
  `os` varchar(30) DEFAULT NULL,
  `new_host` int(1) NOT NULL DEFAULT '1',
  `plage_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


/*
 * DATABASE : @DB_NAME_CENTREON@
 * TABLE : mod_discovery_template_os_relation
 *	
 * Création de la table mod_discovery_template_os_relation contenant les associations entre le type d'équipement et son template associé
 *
 */
CREATE TABLE IF NOT EXISTS `mod_discovery_template_os_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template` varchar(255) NOT NULL,
  `os` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

INSERT INTO `mod_discovery_template_os_relation` (`id`, `template`, `os`) VALUES
(1, 'Servers-Win2K3', 'Hardware: x86 Family 6 Model 23 Stepping 6 AT/AT COMPATIBLE - Software: Windows Version 6.1 (Build 7600 Multiprocessor Free)'),
(2, 'Servers-Linux', 'Linux'),
(3, 'Switchs-Cisco', 'C3500');


