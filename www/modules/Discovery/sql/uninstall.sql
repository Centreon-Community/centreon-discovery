-- This file is part of Centreon-Discovery module.
--
-- Centreon-Discovery is free software; you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by the
--  Free Software Foundation, either version 2 of the License.
--
-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License
-- along with this program; if not, see <http://www.gnu.org/licenses>.
--
-- Linking this program statically or dynamically with other modules is making a
-- combined work based on this program. Thus, the terms and conditions of the GNU
-- General Public License cover the whole combination.
--
-- Module name: Centreon-Discovery
--
-- Developped by : Jonathan Teste - Cedric Dupre
-- Modified by : Sub2.13
--
-- WEBSITE: http://community.centreon.com/projects/centreon-discovery
-- SVN: http://svn.modules.centreon.com/centreon-discovery


-- DATABASE : @DB_NAME_CENTREON@
-- TABLE : topology
--	
-- Suppression des pages du module Centreon Discovery
DELETE FROM `@DB_NAME_CENTREON@`.`topology` WHERE `topology_page` = '612';
DELETE FROM `@DB_NAME_CENTREON@`.`topology` WHERE `topology_page` = '61201';
DELETE FROM `@DB_NAME_CENTREON@`.`topology` WHERE `topology_page` = '61202';
DELETE FROM `@DB_NAME_CENTREON@`.`topology` WHERE `topology_page` = '61203';
DELETE FROM `@DB_NAME_CENTREON@`.`topology` WHERE `topology_page` = '61204';
DELETE FROM `@DB_NAME_CENTREON@`.`topology` WHERE `topology_page` = '61205';

-- DATABASE : @DB_NAME_CENTREON@
-- TABLE : mod_discovery_*
--	
-- Suppression des tables utiles au fonctionnement du module

-- DATABASE : @DB_NAME_CENTREON@
-- TABLE : mod_discovery_rangeip
--	
-- Suppression de la table mod_discovery_rangeip
DROP TABLE `mod_discovery_rangeip`;


-- DATABASE : @DB_NAME_CENTREON@
-- TABLE : mod_discovery_results
--	
-- Suppression de la table mod_discovery_results
DROP TABLE `@DB_NAME_CENTREON@`.`mod_discovery_results`;


-- DATABASE : @DB_NAME_CENTREON@
-- TABLE : mod_discovery_template_os_relation
--	
-- Suppression des tables mod_discovery_template_os_relation et mod_discovery_config
DROP TABLE `@DB_NAME_CENTREON@`.`mod_discovery_template_os_relation`;
DROP TABLE `@DB_NAME_CENTREON@`.`mod_discovery_config`;
