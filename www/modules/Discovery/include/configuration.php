<?php
/*
 * Copyright 2005-2009 MERETHIS
 * Centreon is developped by : Julien Mathis and Romain Le Merlus under
 * GPL Licence 2.0.
 * 
 * This program is free software; you can redistribute it and/or modify it under 
 * the terms of the GNU General Public License as published by the Free Software 
 * Foundation ; either version 2 of the License.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A 
 * PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along with 
 * this program; if not, see <http://www.gnu.org/licenses>.
 * 
 * Linking this program statically or dynamically with other modules is making a 
 * combined work based on this program. Thus, the terms and conditions of the GNU 
 * General Public License cover the whole combination.
 * 
 * As a special exception, the copyright holders of this program give MERETHIS 
 * permission to link this program with independent modules to produce an executable, 
 * regardless of the license terms of these independent modules, and to copy and 
 * distribute the resulting executable under terms of MERETHIS choice, provided that 
 * MERETHIS also meet, for each linked independent module, the terms  and conditions 
 * of the license of that module. An independent module is a module which is not 
 * derived from this program. If you modify this program, you may extend this 
 * exception to your version of the program, but you are not obliged to do so. If you
 * do not wish to do so, delete this exception statement from your version.
 * 
 * For more information : contact@centreon.com
 * 
 * SVN : $URL$
 * SVN : $Id$
 * 
 */
 
/*
 * {DESCRIPTION}
 *
 * PHP version 5
 *
 * @package {PACKAGE_NAME}
 * @version $Id: 0.6
 * @copyright (c) 2007-2009 Centreon
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 */
 ?>

<html lang="fr">
	<head>
		<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
		<title>Discovery</title>
        <link rel="stylesheet" media="screen" type="text/css" title="Discovery" href="./modules/Discovery/css/discovery.css"/>
		<link href="./Themes/Centreon-2/style.css" rel="stylesheet" type="text/css"/>
		<meta content="Discovery - Configuration Page" name="Nicolas DIETRICH">
	</head>
	<body style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); height: 158px;" alink="#ff6600" link="#ff6600" vlink="#ff6600">  
		<span class="" style="font-family: Candara;  font-weight: bold;"><br>
		
			<?php

			if (!isset ($oreon)) {
				exit ();
			}

			require_once './modules/Discovery/include/DB-Func.php';
			
			?>

			<br>
			<table width="100%">
				<tr>
					<td width="30%"><img alt ="CentrESIEEon" src="./modules/Discovery/include/images/img_CentrESIEEon.png"/></td>
					<td width="40%"><h1 ALIGN=CENTER>Discovery - Configuration page</h1></td>
					<td width="30%"></td>
				</tr>
			</table>

			<br><br>

			<?php
			
			function doFormTab($id) {
				echo '<div>'."\n";
				echo '  <ul id="onglets">'."\n";
				if ($id==1) { echo '<li class="active">'; } else { echo '<li class="a" style="">'; }
				echo '<a href="./main.php?p=61203&id=1">  Default Values  </a></li>'."\n";
				if ($id==2) { echo '<li class="active">'; } else { echo '<li class="a" style="">'; }
				echo '<a href="./main.php?p=61203&id=2">  Template / OS Relation  </a></li>'."\n";
				echo '  </ul>'."\n";
				echo '</div>'."\n";
				echo '<div id="tab1" class="tab">'."\n";
				echo '    <table class="ListTable">'."\n";
				echo '        <tr class="ListHeader">'."\n";
				echo '            <td class="FormHeader">&nbsp;&nbsp;</td> '."\n";
				echo '        </tr>'."\n";
				echo '        <tr>'."\n";
				echo '            <td align="center">'."\n";
			}
			
			function doForm($id,$error){
				$select1="";
				$select2="";
				switch ($id) {
					case 1: {
						#Default values configuration
						$values=mysql_query("SELECT * FROM mod_discovery_rangeip WHERE id=0;");
						$valuesData=mysql_fetch_array($values,MYSQL_ASSOC);
						echo '<form method="post" action="">'."\n";
						echo '  <table>'."\n";
						echo '      <tr>'."\n";
						echo '          <td>Ping count : </td>'."\n";
						echo '          <td>&nbsp;</td>'."\n";
						echo '          <td><input style="text-align:center;"  type="text" name="pingcount" size="12" value="'.$valuesData["ping_count"].'"></td>'."\n";
						echo '      </tr>'."\n";
						echo '      <tr>'."\n";
						echo '          <td>Ping timeout (milliseconds) : </td>'."\n";
						echo '          <td>&nbsp;</td>'."\n";
						echo '          <td><input style="text-align:center;"  type="text" name="pingtimeout" size="12" value="'.$valuesData["ping_wait"].'"></td>'."\n";
						echo '      </tr>'."\n";
						/*echo '      <tr>'."\n";
						echo '          <td>TCP port : </td>'."\n";
						echo '          <td>&nbsp;</td>'."\n";
						echo '          <td><input style="text-align:center;"  type="text" name="tcpport" size="12" value="'.$valuesData["tcp_port"].'" title="Example: 21-25;80;445"></td>'."\n";
						echo '      </tr>'."\n";*/
						echo '      <tr>'."\n";
						echo '          <td>Hostname OID : </td>'."\n";
						echo '          <td>&nbsp;</td>'."\n";
						echo '          <td><input style="text-align:center;"  type="text" name="hostname" size="12" value="'.$valuesData["oid_hostname"].'"></td>'."\n";
						echo '      </tr>'."\n";
						echo '      <tr><td colspan=2>&nbsp;</td></tr>',"\n";
						echo '      <tr>'."\n";
						echo '          <td>OS Version OID : </td>'."\n";
						echo '          <td>&nbsp;</td>'."\n";
						echo '          <td><input style="text-align:center;" type="text" name="OS" size="12" value="'.$valuesData["oid_os"].'"></td>'."\n";
						echo '      </tr>'."\n";
						echo '      <tr><td colspan=2>&nbsp;</td></tr>',"\n";
						echo '      <tr>'."\n";
						echo '          <td>SNMP version : </td>'."\n";
						echo '          <td>&nbsp;</td>'."\n";
						if ($valuesData["snmp_version"]==1){
							$select1="selected";
						}
						else $select2="selected";
						echo '          <td><select name="version">'."\n";
						echo '              <option '.$select1.'>v1</option>'."\n";
						echo '              <option '.$select2.'>v2c</option>'."\n";
						echo '          </select></td>'."\n";
						echo '      </tr>'."\n";
						echo '      <tr><td colspan=2>&nbsp;</td></tr>',"\n";
						echo '      <tr>'."\n";
						echo '          <td>SNMP method : </td>'."\n";
						echo '          <td>&nbsp;</td>'."\n";
						$select3="";
						$select4="";
						if ($valuesData["snmp_method"]=="walk"){
							$select3="selected";
						}
						else{
							$select4="selected";
						}
						echo '          <td><select name="method">'."\n";
						echo '              <option '.$select3.'>Walk</option>'."\n";
						echo '              <option '.$select4.'>Get</option>'."\n";
						echo '          </select></td>'."\n";
						echo '      </tr>'."\n";
						echo '      <tr><td colspan=2>&nbsp;</td></tr>',"\n";
						echo '      <tr>'."\n";
						echo '          <td>SNMP Comumnity : </td>'."\n";
						echo '          <td>&nbsp;</td>'."\n";
						echo '          <td><input style="text-align:center;"  type="text" name="community" size="12" value="'.$valuesData["snmp_community"].'"></td>'."\n";
						echo '      </tr>'."\n";
						echo '      <tr><td colspan=2>&nbsp;</td></tr>',"\n";
						echo '      <tr>'."\n";
						echo '          <td>&nbsp;</td>'."\n";
						echo '          <td>&nbsp;</td>'."\n";
						echo '          <td><input name="save" type="submit" value=" Save "></td>'."\n";
						echo '          <td><input name="defaults" type="submit" value=" Defaults "></td>'."\n";
						echo '  </table>'."\n";
						echo '</form>'."\n";
						
					} break;
					case 2: {
						#template os relation configuration
						$relations=mysql_query("SELECT * FROM mod_discovery_template_os_relation");
						
						echo ' <form method="post" action="">'."\n";
						echo '     <center>'."\n";
						echo '     <table>'."\n";
						echo '         <tr>'."\n";
						echo '             <td align=center colspan=3><h3>New OS - Template Relation: </h3></td>'."\n";
						echo '         </tr>'."\n";
						echo '         <tr><td colspan=3>&nbsp;</td></tr>',"\n";
						echo '         <tr>'."\n";
                        echo '		       <td>&nbsp;Template Name : <select name="template"><option>None</option>'."\n ";
                        $listTemplate=mysql_query("SELECT * FROM host WHERE host_register='0'");
                        while ($listTemplateData=mysql_fetch_array($listTemplate,MYSQL_ASSOC)){
                            echo "<option value='".$listTemplateData["host_name"]."'>".$listTemplateData["host_name"]."</option>"."\n ";
                        }
                        echo '  		   </select></td>'."\n ";
						echo '             <td>SNMP OS Name : <input type="text" name="os" value="">&nbsp;</td>'."\n";						
						echo '             <td>&nbsp;<input type="submit" title="Add to List" name="submit" value="Add"></td>'."\n";
						echo '         </tr>'."\n";
						echo '     </table>'."\n";
						if ($error==1){
							echo '<b><font color="blue">INFO</b></font> : Error in your entry'."\n";
						}
						echo '     </center>'."\n";
						echo ' <br><br><br>'."\n";
						echo '    <table class="ListTable">'."\n ";
						echo '         <tr class="ListHeader">'."\n ";
						echo '             <td class="ListColHeaderCenter">Template Name</td>'."\n ";
						echo '             <td class="ListColHeaderCenter">SNMP OS Version</td>'."\n ";
						echo '             <td class="ListColHeaderCenter"><input type="submit" title="Delete All from List" name="clear" value=" Clear All " onClick="self.location=\'./main.php?p=61204&id=2\'"></td>'," \n";
						echo '         </tr>'."\n";
						while ($relationsData=mysql_fetch_array($relations,MYSQL_ASSOC)){
							echo ' <tr class="list_one">'." \n ";
							echo '  <td class="ListColHeaderCenter">'.$relationsData["template"].'</td>'."\n";
							echo '  <td class="ListColHeaderCenter">'.$relationsData["os"].'</td>'."\n";
							echo '  <td class="ListColHeaderCenter"><input style="border:none" type="image" src="./modules/Discovery/include/images/delete16x16.png" title="Delete One from List" name="'.$relationsData["id"].'" value="'.$relationsData["id"].'" onClick="self.location=\'./main.php?p=61204&id=2\'"></td>',"\n";
							echo ' </tr>'." \n ";
						}
						echo '    </table>'."\n";
						echo ' </form>'."\n";
						
					} break;
				}
				echo '            </td>'."\n";
				echo '         </tr>'."\n";
				echo '      </table>'."\n";
				echo '  </div>'."\n";
			}
			
			function doPost(){
				global $conf_centreon;
				$db = dbConnect($conf_centreon['hostCentreon'], $conf_centreon['user'], $conf_centreon['password'],$conf_centreon['db'], true);
				$error=0;

				if (isset($_POST["submit"]) && $_POST["submit"]=="Add"){
					if (isset($_POST["os"]) && isset($_POST["template"])){
						mysql_query("INSERT INTO mod_discovery_template_os_relation (os,template) VALUES('".$_POST["os"]."','".$_POST["template"]."');");
						$_POST=Array();
					}
					else $error =1;
				}

				if(isset($_POST["save"])){
					if (isset($_POST["pingcount"])){
						if(empty($_POST["pingcount"])){
							mysql_query("UPDATE mod_discovery_rangeip SET ping_count=DEFAULT WHERE id=0;");
						}
						elseif($_POST["pingcount"]<=0){
							mysql_query("UPDATE mod_discovery_rangeip SET ping_count=DEFAULT WHERE id=0;");
						}
						else{
							mysql_query("UPDATE mod_discovery_rangeip SET ping_count='".$_POST["pingcount"]."' WHERE id=0;");
						}
					}

					if (isset($_POST["pingtimeout"])){
						if(empty($_POST["pingtimeout"])){
							mysql_query("UPDATE mod_discovery_rangeip SET ping_wait=DEFAULT WHERE id=0;");
						}
						elseif($_POST["pingtimeout"]<=0){
							mysql_query("UPDATE mod_discovery_rangeip SET ping_wait=DEFAULT WHERE id=0;");
						}
						else{
							mysql_query("UPDATE mod_discovery_rangeip SET ping_wait='".$_POST["pingtimeout"]."' WHERE id=0;");
						}
					}

					if (isset($_POST["tcpport"])){
						if(empty($_POST["tcpport"])){
							mysql_query("UPDATE mod_discovery_rangeip SET tcp_port=DEFAULT WHERE id=0;");
						}
						elseif(!ereg("^(6553[0-5]|655[0-2][0-9]|65[0-4][0-9]{1,2}|6[0-4][0-9]{1,3}|[1-5][0-9]{1,4}|[1-9][0-9]{1,3}|[0-9])(-(6553[0-5]|655[0-2][0-9]|65[0-4][0-9]{1,2}|6[0-4][0-9]{1,3}|[1-5][0-9]{1,4}|[1-9][0-9]{1,3}|[0-9]))?(;(6553[0-5]|655[0-2][0-9]|65[0-4][0-9]{1,2}|6[0-4][0-9]{1,3}|[1-5][0-9]{1,4}|[1-9][0-9]{1,3}|[0-9])(-(6553[0-5]|655[0-2][0-9]|65[0-4][0-9]{1,2}|6[0-4][0-9]{1,3}|[1-5][0-9]{1,4}|[1-9][0-9]{1,3}|[0-9]))?)*$", $_POST["tcpport"])){
							mysql_query("UPDATE mod_discovery_rangeip SET tcp_port=DEFAULT WHERE id=0;");
						}
						else{
							mysql_query("UPDATE mod_discovery_rangeip SET tcp_port='".$_POST["tcpport"]."' WHERE id=0;");
						}
					}

					if (isset($_POST["hostname"])){
						if(empty($_POST["hostname"])){
							mysql_query("UPDATE mod_discovery_rangeip SET oid_hostname=DEFAULT WHERE id=0;");
						}
						elseif(!ereg("^(\.([1-9][0-9]+|[0-9]))+$", $_POST["hostname"])){
							mysql_query("UPDATE mod_discovery_rangeip SET oid_hostname=DEFAULT WHERE id=0;");
						}
						else{
							mysql_query("UPDATE mod_discovery_rangeip SET oid_hostname='".$_POST["hostname"]."' WHERE id=0;");
						}
					}
					
					if (isset($_POST["OS"])){
						if(empty($_POST["OS"])){
							mysql_query("UPDATE mod_discovery_rangeip SET oid_os=DEFAULT WHERE id=0;");
						}
						elseif(!ereg("^(\.([1-9][0-9]+|[0-9]))+$", $_POST["OS"])){
							mysql_query("UPDATE mod_discovery_rangeip SET oid_os=DEFAULT WHERE id=0;");
						}
						else{
							mysql_query("UPDATE mod_discovery_rangeip SET oid_os='".$_POST["OS"]."' WHERE id=0;");
						}
					}
					
					if (isset($_POST["version"])){
						if ($_POST["version"] == 'v1') {
							$version =1;
						}
						else if ($_POST["version"] == 'v2c') {
							$version =2;
						}
						mysql_query("UPDATE mod_discovery_rangeip SET snmp_version='".$version."' WHERE id=0;");
					}
					if (isset($_POST["method"])){
						if ($_POST["method"] == 'Walk') {
							$method ="walk";
						}
						else if ($_POST["method"] == 'Get') {
							$method ="get";
						}
						mysql_query("UPDATE mod_discovery_rangeip SET snmp_method='".$method."' WHERE id=0;");
					}
					
					if (isset($_POST["community"])){
						if(empty($_POST["community"])){
							mysql_query("UPDATE mod_discovery_rangeip SET snmp_community=DEFAULT WHERE id=0;");
						}
						else{
							mysql_query("UPDATE mod_discovery_rangeip SET snmp_community='".$_POST["community"]."' WHERE id=0;");
						}
					}
					
					if (isset($_POST["poller"])){
						if(empty($_POST["poller"])){
							mysql_query("UPDATE mod_discovery_rangeip SET def_poller=DEFAULT WHERE id=0;");
						}
						elseif(!ereg("^(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9][0-9]|[1-9])(.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9][0-9]|[0-9])){3}$", $_POST["community"])){
							mysql_query("UPDATE mod_discovery_rangeip SET def_poller=DEFAULT WHERE id=0;");
						}
						else{
							mysql_query("UPDATE mod_discovery_rangeip SET def_poller='".$_POST["poller"]."' WHERE id=0;");
						}
					}
				}
				
				if(isset($_POST["defaults"])){
					mysql_query("UPDATE mod_discovery_rangeip SET def_poller=DEFAULT, snmp_community=DEFAULT, snmp_method=DEFAULT, snmp_version=DEFAULT, oid_os=DEFAULT, oid_hostname=DEFAULT, tcp_port=DEFAULT, ping_timeout=DEFAULT, ping_count=DEFAULT WHERE id=0;");
				}

				if (isset($_POST["clear"]) && $_POST["clear"]==" Clear All ") {
					mysql_query("DELETE FROM mod_discovery_template_os_relation");
				}
				
                                if (!empty($_POST)){
                                    $templateIDList=mysql_query("SELECT id FROM mod_discovery_template_os_relation;");
                                    while ($templateIDListData=mysql_fetch_array($templateIDList,MYSQL_ASSOC)) {
                                        $id=$templateIDListData["id"];
                                        $postVar=$id."_x";
                                        if (isset($_POST[$postVar]) || isset($_POST[$id])){
                                            mysql_query("DELETE FROM mod_discovery_template_os_relation WHERE id='".$id."';");
                                        }
                                    }
                                    unset($_POST);
                                }

				if (isset($_GET["id"])) {
					$id=$_GET["id"];
					if ($id<1 || $id >2) $id=1;
                                        
					doFormTab($id);
					doForm($id,$error);
				}
				else {
					doFormTab(1);
					doForm(1,$error);
				}
				dbClose($db);
			}
			
			doPost();
			?>
		</span>
	</body>
</html>
