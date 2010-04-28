/*  This file is part of CentreonDiscovery.
*
*	 CentreonDiscovery is developped with GPL Licence 3.0 :
*	 Developped by : Jonathan Teste - Cedric Dupre
*   
*/


/*
 * DATABASE : Centreon2
 * TABLE : topology
 *	
 *	Insertion des pages du module Centreon Discovery
 *
 *
 *	Page principale du module Centreon Discovery
 */
INSERT INTO `topology` (`topology_id`, `topology_name`, `topology_icone`, `topology_parent`, `topology_page`, `topology_order`, `topology_group`, `topology_url`, `topology_url_opt`, `topology_popup`, `topology_modules`, `topology_show`) VALUES ('', 'Centreon Discovery', NULL, 6, 610, 100, 1, './modules/CentreonDiscovery/pages/CentreonDiscovery.php', NULL, '0', '1', '1');
/*
*	Pages secondaires du module Centreon Discovery
*/
INSERT INTO `topology` (`topology_id`, `topology_name`, `topology_icone`, `topology_parent`, `topology_page`, `topology_order`, `topology_group`, `topology_url`, `topology_url_opt`, `topology_popup`, `topology_modules`, `topology_show`) VALUES ('', 'Setup', './modules/CentreonDiscovery/pictures/press.gif', 610, 61001, 1, 1, './modules/CentreonDiscovery/pages/CentreonDiscovery.php', NULL, '0', '1', '1');
/*INSERT INTO `topology` (`topology_id`, `topology_name`, `topology_icone`, `topology_parent`, `topology_page`, `topology_order`, `topology_group`, `topology_url`, `topology_url_opt`, `topology_popup`, `topology_modules`, `topology_show`) VALUES ('', 'Configuration', './modules/CentreonDiscovery/pictures/text_code.gif', 610, 61002, 1, 1, './modules/CentreonDiscovery/pages/Configuration.php', NULL, '0', '1', '1');*/
INSERT INTO `topology` (`topology_id`, `topology_name`, `topology_icone`, `topology_parent`, `topology_page`, `topology_order`, `topology_group`, `topology_url`, `topology_url_opt`, `topology_popup`, `topology_modules`, `topology_show`) VALUES ('', 'Documentation', './modules/CentreonDiscovery/pictures/book_green.gif', 610, 61009, 100, 1, './modules/CentreonDiscovery/pages/Documentation.htm', NULL, '0', '1', '1');
INSERT INTO `topology` (`topology_id`, `topology_name`, `topology_icone`, `topology_parent`, `topology_page`, `topology_order`, `topology_group`, `topology_url`, `topology_url_opt`, `topology_popup`, `topology_modules`, `topology_show`) VALUES ('', 'A propos', './modules/CentreonDiscovery/pictures/about.gif', 610, 61010, 101, 1, './modules/CentreonDiscovery/pages/TeamReon.htm', NULL, '0', '1', '1');



/*
* DATABASE : Centreon2
* TABLE : CDType
*	
*	Création de la table CDType
*/
CREATE TABLE IF NOT EXISTS CDType (CDType_id INT UNSIGNED NOT NULL PRIMARY KEY, CDType_type CHAR(100));
/*
*	Insertion des valeurs dans la table CDType
*/
INSERT INTO CDType (CDType_id, CDType_type) VALUES (1,"Nothing");
INSERT INTO CDType (CDType_id, CDType_type) VALUES (10,"Servers-Win2K3");
INSERT INTO CDType (CDType_id, CDType_type) VALUES (20,"Servers-Linux");
INSERT INTO CDType (CDType_id, CDType_type) VALUES (30,"Router-Cisco");
INSERT INTO CDType (CDType_id, CDType_type) VALUES (40,"Switchs-Cisco");



/*
* DATABASE : Centreon2
* TABLE : OIDGroup
*	
*	Création de la table OIDGroup
*/
CREATE TABLE IF NOT EXISTS OIDGroup (OIDGroup_id INT UNSIGNED NOT NULL PRIMARY KEY, OIDGroup_type CHAR(100), OIDGroup_name CHAR(100), OIDGroup_path CHAR(100), OIDGroup_device INT UNSIGNED NOT NULL, FOREIGN KEY (OIDGroup_device) references CDType(CDType_id));
/* Windows */
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (101,"NIC","Interface Reseaux","1.3.6.1.2.1.2.2.1.1",10);
/*INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (102,"CPU","CPU","1.3.6.1.2.1.25.3.3.1.2.3",10);*/
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (103,"Disk","Partitions","1.3.6.1.2.1.25.2.3.1.1",10);
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (104,"Memory","Memoire","1.3.6.1.2.1.25.2.2",10);
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (105,"Process","Processus","1.3.6.1.2.1.25.4.2.1.1",10);
/* Linux */
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (201,"NIC","Interface Reseaux","1.3.6.1.2.1.2.2.1.1",20);
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (202,"CPU","CPU","1.3.6.1.4.1.2021.10.1.3",20);
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (203,"Disk","Partitions","1.3.6.1.2.1.25.2.3.1.1",20);
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (204,"Memory","Memoire","1.3.6.1.4.1.2021.4.11",20);
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (205,"Process","Processus","1.3.6.1.2.1.25.4.2.1.1",20);
/* Router-Cisco */
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (301,"NIC","Interface Reseaux","1.3.6.1.2.1.2.2.1.1",30);
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (302,"CPU","CPU","1.3.6.1.4.1.9.9.109.1.1.1.1.6.1",30);
/*Switch-Cisco */
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (401,"NIC","Interface Reseaux","1.3.6.1.2.1.2.2.1.1",40);
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (402,"CPU","CPU","1.3.6.1.4.1.9.9.109.1.1.1.1.6.1",40);