/* This file is part of Centreon-Discovery module.
 *
 * Centreon-Discovery is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, see <http://www.gnu.org/licenses>.
 *
 * Module name: Centreon-Discovery
 *
 * Developped by: Sub2.13
 *
 * WEBSITE: http://community.centreon.com/projects/centreon-discovery
 * SVN: http://svn.modules.centreon.com/centreon-discovery
 *
 * Command : mysql -u root --password=admin centreon < install/template.sql > /dev/null
 *
 */

/*
 * DATABASE: @DB_NAME_CENTREON@
 * TABLE: mod_discovery_template_os_relation
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