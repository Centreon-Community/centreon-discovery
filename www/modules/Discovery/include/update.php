<?php

/* This file is part of Centreon-Discovery module.
 *
 * Centreon-Discovery is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by the
 *  Free Software Foundation, either version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, see <http://www.gnu.org/licenses>.
 *
 * Linking this program statically or dynamically with other modules is making a
 * combined work based on this program. Thus, the terms and conditions of the GNU
 * General Public License cover the whole combination.
 *
 * Module name: Centreon-Discovery
 *
 * Adapted by: Nicolas Dietrich & Vincent Van Den Bossche
 *
 * WEBSITE: http://community.centreon.com/projects/centreon-discovery
 * SVN: http://svn.modules.centreon.com/centreon-discovery
 */
 
$filepath = '@CENTREON_ETC@/centreon.conf.php';
//$filepath = '/etc/centreon/centreon.conf.php';

$agentDir = "@AGENT_DIR@/DiscoveryAgent_central.py";
//$agentDir = "/usr/share/centreon-discovery/DiscoveryAgent_central.py";



if (file_exists($filepath)) {
	include($filepath);
	$connect = mysql_connect($conf_centreon['hostCentreon'], $conf_centreon['user'], $conf_centreon['password']) or die('Impossible de se connecter à la base de donnees'.mysql_error());
	mysql_select_db($conf_centreon['db']) or die('Impossible de trouver la base de donnees '.mysql_error());
	//On met à jour le nagios_server_id dans la bdd
	mysql_query('UPDATE mod_discovery_rangeip SET nagios_server_id='.(int)$_POST['poller_id'].',poller_status=0 WHERE id='.(int)$_POST['id'].';') or die ('Erreur : '.mysql_error());
	
	//On recherche le chemin du fichier dans la bdd, car les chemins relatifs ne fonctionnent pas avec Ajax
	$sql = mysql_query("SELECT value FROM options WHERE options.key='oreon_path';");
	$currentpath = mysql_fetch_row($sql);
		
	//On relance le script python afin de vérifier le status du nouveau Discovery-Agent poller
	if (file_exists($agentDir)) {
//		shell_exec('python '.$agentDir.' STATUS_POLLER > /dev/null 2>&1 &');
		shell_exec('python '.$agentDir.' STATUS_POLLER '.(int)$_POST['poller_id'].' >> /tmp/agent_central.log 2>&1 &');
	}
	else {shell_exec('python '.$currentpath[0].'/www/modules/Discovery/include/agent/DiscoveryAgent_central.py STATUS_POLLER > /dev/null 2>&1 &');}
}
?>