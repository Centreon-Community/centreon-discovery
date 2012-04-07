/*  This file is part of Centreon-Discovery.
 *
 *	 Centreon-Discovery is developped with GPL Licence 3.0 :
 *	 Developped by : Sub2.13
 *
 *	 Command : mysql -u root --password=admin centreon < install/relations.sql
 * 
 */

/*
 * DATABASE : @DB_NAME_CENTREON@
 * TABLE : mod_discovery_template_os_relation
 *	
 */
 
 INSERT INTO `mod_discovery_template_os_relation` (`template`, `os`) VALUES
('discovery-cisco-switch', 'C2360|C2950|C2960|C3560|C3750|C4503|C4506|C4507|C4510|C6503|C6504|C6506|C6509|C6513'),
('discovery-cisco-router', 'C2600|C2800'),
('discovery-cisco-asa', 'Cisco&Adaptive&Security&Appliance'),
('discovery-cisco-pix', 'Cisco&PIX'),
('discovery-windows-server', 'Windows&Version'),
('discovery-linux-server', 'Linux'),
('discovery-vmware-esx', 'VMware&ESX');