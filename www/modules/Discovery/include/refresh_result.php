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
 * Developped by: Nicolas Dietrich & Vincent Van Den Bossche
 *
 * WEBSITE: http://community.centreon.com/projects/centreon-discovery
 * SVN: http://svn.modules.centreon.com/centreon-discovery
 */
 
$filepath = '@CENTREON_ETC@/centreon.conf.php';
//$filepath = '/etc/centreon/centreon.conf.php';

if (file_exists($filepath)) {
	include($filepath);
	$connect = mysql_connect($conf_centreon['hostCentreon'], $conf_centreon['user'], $conf_centreon['password']) or die('Impossible de se connecter à la base de donnees'.mysql_error());
	mysql_select_db($conf_centreon['db']) or die('Impossible de trouver la base de donnees '.mysql_error());
	
	$sql1 = mysql_query('SELECT id,done,plage FROM mod_discovery_rangeip;');
	echo '<p style="text-align:center"> ';
	$oneScanDone = 0;
	while($rangeToScan = mysql_fetch_array($sql1,MYSQL_ASSOC)){
		if (($rangeToScan["done"]==2) && ($rangeToScan["id"]==0)){
			echo 'Scan done<br><br>';
			echo '<input type=button value=" Show results " onClick="self.location=\'./main.php?p=61202\'">';
			echo '<p style="display:none">end</p>';
			return;
		}
		else{		
			/* Si done = 2 alors le scan est terminé */
			if (($rangeToScan["done"]==2) && ($rangeToScan["id"]!=0)){
					echo 'Range : '.$rangeToScan["plage"].' | <b style="color:green">Scan done</b><br>';
					$oneScanDone = 1;
			}
			/* Sinon si done = 3 alors il y a une erreur pendant le scan */
			else if (($rangeToScan["id"]!=0) && ($rangeToScan["done"]==3)){ 
					echo 'Range : '.$rangeToScan["plage"].' | <b style="color:red">ERROR : Connection lost with the poller agent...</b><br>';
			}
			/* Sinon le scan est en cours */
			else if (($rangeToScan["id"]!=0) && ($rangeToScan["done"]!=0)){ 
					echo 'Range : '.$rangeToScan["plage"].' | <b style="color:orange">Scanning in progress...</b><br>';
			}
		}
	}
	if ($oneScanDone == 1){
		echo '<br><br><input type=button value=" Show current results " onClick="self.location=\'./main.php?p=61202\'">';
	}
	echo '</p>';
}
?>