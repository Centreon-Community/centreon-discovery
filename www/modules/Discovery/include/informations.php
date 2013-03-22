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
 * Created by: Baptiste Gazui
 * Adapted by: Nicolas Dietrich
 *
 * WEBSITE: http://community.centreon.com/projects/centreon-discovery
 * SVN: http://svn.modules.centreon.com/centreon-discovery
 */
    
require_once './modules/Discovery/include/DB-Func.php';
require_once './modules/Discovery/include/common.php';

if (!isset ($oreon)) {
	exit ();
}

/* Fonction qui retourne la version du module Discovery */
function findVersion (){
	global $error, $conf_centreon;
	$db = dbConnect($conf_centreon['hostCentreon'], $conf_centreon['user'], $conf_centreon['password'],$conf_centreon['db'], true);
	$reqVersion=mysql_query("SELECT mod_release FROM modules_informations WHERE name='Discovery';");
	$version=mysql_fetch_array($reqVersion);
	return $version['mod_release'];
	dbClose($db);
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
		<title>Centreon-Discovery</title>
		<meta content="Centreon-Discovery" name="Baptiste GAZUI"> 			
	</head>
	<center>
		<table border="0">
			<tr>
				<td bgcolor="#6D8C19"><center><font color="white" size="6">Centreon Discovery</font><br /><font color="black" size="4"><i>Version <?php echo findVersion();?></i></font></center></td>
				<td colspan="3"><img src="./modules/Discovery/include/images/Image-Gauche-Contenu.gif" height="300px" border="0"/></td>
			</tr>
			<tr>
				<td colspan="4"><hr width="95%" /></td>
			</tr>
			<tr>
				<td rowspan="5"><img src="./modules/Discovery/include/images/Images-ESIEE.gif" height="240px" /></td>
				<td>
					<tr><td colspan="3">&nbsp;</td></tr>
					<tr><td>&nbsp;&nbsp;&nbsp;<strong>ABOUT US</strong></td></tr>
					<tr><td></td>
						<td align="right">Project Leader&nbsp;&nbsp;&nbsp;</td>
						<td>Fran&ccedil;ois COUST&Eacute;</td>
					</tr>
					<tr><td></td>
						<td align="right" valign="top">Developers&nbsp;&nbsp;&nbsp;</td>
						<td valign="top">Nicolas DIETRICH<br />
							Baptiste GAZUI<br />
							Vincent VAN DEN BOSSCHE<br /></td>
					</tr>
				</td>
			</tr>
		</table>
	</center>
</html>


