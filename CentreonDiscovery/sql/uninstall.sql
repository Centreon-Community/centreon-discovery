/*
* DATABASE : Centreon2
* TABLE : topology
*	
*	Suppression des pages du module Centreon Discovery
*
*
*	Page principale du module Centreon Discovery
*/
DELETE FROM `topology` WHERE `topology_page` = '610';
/*
*	Pages secondaires du module Centreon Discovery
*/
DELETE FROM `topology` WHERE `topology_page` = '61001';
DELETE FROM `topology` WHERE `topology_page` = '61002';
DELETE FROM `topology` WHERE `topology_page` = '61003';



/*
* DATABASE : Centreon2
* TABLE : service, extended_service_information 
*	
*	Suppression des services et templates de service créés via le module
*/
DELETE FROM extended_service_information WHERE service_service_id = (SELECT service_id FROM service WHERE service_description LIKE "%TeamReon%");
DELETE FROM service WHERE service_description LIKE "%TeamReon%";



/*
* DATABASE : Centreon2
* TABLE : command
*	
*	Suppression des commandes créées via le module
*/
DELETE FROM command WHERE command_name LIKE "%teamreon%";



/*
* DATABASE : Centreon2
* TABLE : CommandOID
*	
*	Suppression de la table CommandOID
*/
DROP TABLE `ServiceOID`;



/*
* DATABASE : Centreon2
* TABLE : OIDGroup
*	
*	Suppression de la table OIDGroup
*/
DROP TABLE `OIDGroup`;



/*
* DATABASE : Centreon2
* TABLE : CDType
*	
*	Suppression de la table CDType
*/
DROP TABLE `CDType`;


DELETE FROM extended_service_information WHERE service_service_id = (SELECT service_id FROM service WHERE service_description LIKE "%TeamReon%");
