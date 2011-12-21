<?php
$filepath = '/etc/centreon/centreon.conf.php';

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
	shell_exec('python '.$currentpath[0].'/www/modules/Centreon-Discovery/include/agent/DiscoveryAgent_central.py STATUS_POLLER > /dev/null 2>&1 &');
}
?>