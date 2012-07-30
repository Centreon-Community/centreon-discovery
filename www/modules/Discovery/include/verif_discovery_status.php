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
 * Created by: Nicolas Dietrich & Vincent Van Den Bossche
 *
 * WEBSITE: http://community.centreon.com/projects/centreon-discovery
 * SVN: http://svn.modules.centreon.com/centreon-discovery
 */
 
/*	
 * Ce script permet de vérifier si l'agent Discovery du poller distant est joignable ou non.
 * Il sera exécuté toutes les 2 secondes, grace à une fonction ajax dans le fichier script.js
 */
	
$filepath = '@CENTREON_ETC@/centreon.conf.php';
//$filepath = '/etc/centreon/centreon.conf.php';

	if (file_exists($filepath)) {
		include($filepath);
		$connect = mysql_connect($conf_centreon['hostCentreon'], $conf_centreon['user'], $conf_centreon['password']) or die('Impossible de se connecter à la base de donnees'.mysql_error());
		mysql_select_db($conf_centreon['db']) or die('Impossible de trouver la base de donnees '.mysql_error());
		$sql = mysql_query('SELECT poller_status FROM mod_discovery_rangeip WHERE id='.$_POST['id'].';');
		while($data = mysql_fetch_array($sql)){
			switch($data['poller_status']){
				case 1: //Le poller est actif et joignable
					echo '<b style="color:green">&nbsp;&nbsp;UP&nbsp;&nbsp;</b>';
					break;
				case 2: //Le poller est inactif ou injoignable
					echo '<b style="color:#F91E05">&nbsp;&nbsp;DOWN&nbsp;&nbsp;</b>';
					break;
				default: //Aucune information sur le status du poller
					echo '<img style="border:none" type="image" src="./modules/Discovery/include/images/loading.gif" title="Loading...">';
			}
		}
	} else {
		echo "Le fichier $filepath n'existe pas.";
	}

?>

