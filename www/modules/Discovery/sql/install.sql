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
('', 'About', './img/icones/16x16/about.gif', 612, 61204, 101, 1, './modules/Discovery/include/informations.htm', NULL, '0', '1', '1');


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
DROP TABLE IF EXISTS `mod_discovery_rangeip`;
CREATE TABLE IF NOT EXISTS `mod_discovery_rangeip` (
  `id` int(11) AUTO_INCREMENT,
  `plage` varchar(15) COLLATE utf8_bin NOT NULL,
  `masque` varchar(15) COLLATE utf8_bin NOT NULL,
  `cidr` int(2) NOT NULL,
/* pour nmap */
  `nmap_profil` varchar(15) COLLATE utf8_bin NOT NULL DEFAULT 'Insane(T5)', /* [sneaky (T1)] [polite (T2)] [normal (T3)] [aggressive (T4)] [insane(T5) */
  `nmap_max_retries` int(6) NOT NULL DEFAULT 1,
  `nmap_host_timeout` int(6) NOT NULL DEFAULT 15000,
  `nmap_max_rtt_timeout` int(6) NOT NULL DEFAULT 100,
/* pour snmp */
  `oid_hostname` varchar(256) COLLATE utf8_bin DEFAULT '.1.3.6.1.2.1.1.5.0',
  `oid_os` varchar(256) COLLATE utf8_bin DEFAULT '.1.3.6.1.2.1.1.1.0', 
  `snmp_version` varchar(2) DEFAULT "2c",
  `snmp_port` int(5) DEFAULT 161,
  `snmp_community` varchar(256) COLLATE utf8_bin DEFAULT 'public',
  `snmp_timeout` int(6) NOT NULL DEFAULT 5,
  `snmp_retries` int(2) NOT NULL DEFAULT 1,
  `nagios_server_id` int(11) DEFAULT '0',
  `done` int(1) NOT NULL DEFAULT '0',
  `poller_status` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=0 ;

INSERT INTO `@DB_NAME_CENTREON@`.`mod_discovery_rangeip` (`id`, `plage`, `masque`, `cidr`, `nmap_profil`, `nmap_max_retries`, `nmap_host_timeout`, `nmap_max_rtt_timeout`, `oid_hostname`, `oid_os`, `snmp_version`, `snmp_port`, `snmp_community`, `snmp_timeout`, `snmp_retries`, `nagios_server_id`, `done`, `poller_status`) VALUES ('1', 'default', '0', '0', 'Insane(T5)', '1', '15000', '100', '.1.3.6.1.2.1.1.5.0', '.1.3.6.1.2.1.1.1.0', "2c", '161', 'public', '5', '1', '0', '0', '0');
UPDATE  `@DB_NAME_CENTREON@`.`mod_discovery_rangeip` SET  `id` =  '0' WHERE  `mod_discovery_rangeip`.`id` =1 LIMIT 1 ;

INSERT INTO `@DB_NAME_CENTREON@`.`mod_discovery_rangeip` (`id`, `plage`, `masque`, `cidr`, `nmap_profil`, `nmap_max_retries`, `nmap_host_timeout`, `nmap_max_rtt_timeout`, `oid_hostname`, `oid_os`, `snmp_version`, `snmp_port`, `snmp_community`, `snmp_timeout`, `snmp_retries`, `nagios_server_id`, `done`, `poller_status`) VALUES ('2', 'default', '0', '0', 'Insane(T5)', '1', '15000', '100', '.1.3.6.1.2.1.1.5.0', '.1.3.6.1.2.1.1.1.0', "2c", '161', 'public', '5', '1', '0', '0', '0');
UPDATE  `@DB_NAME_CENTREON@`.`mod_discovery_rangeip` SET  `id` =  '-1' WHERE  `mod_discovery_rangeip`.`id` =2 LIMIT 1 ;


/*
 * DATABASE : @DB_NAME_CENTREON@
 * TABLE : mod_discovery_results
 *	
 * Création de la table mod_discovery_results contenant les informations résultantes du scan
 *
 */
DROP TABLE IF EXISTS `mod_discovery_results`;
CREATE TABLE IF NOT EXISTS `mod_discovery_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(20) NOT NULL,
  `hostname` varchar(50) DEFAULT NULL,
  `os` varchar(400) DEFAULT NULL,
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
DROP TABLE IF EXISTS `mod_discovery_template_os_relation`;
CREATE TABLE IF NOT EXISTS `mod_discovery_template_os_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template` varchar(255) NOT NULL,
  `os` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


