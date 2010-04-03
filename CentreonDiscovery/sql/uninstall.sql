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
