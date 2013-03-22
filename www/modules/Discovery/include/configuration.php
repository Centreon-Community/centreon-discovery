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

 ?>

<html lang="fr">
	<head>
		<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
		<title>Discovery</title>
        <link rel="stylesheet" media="screen" type="text/css" title="Discovery" href="./modules/Discovery/css/discovery.css"/>
		<script type="text/javascript" src="./include/common/javascript/color_picker_mb.js"></script>
		<script type="text/javascript" src="./modules/Discovery/include/colorPicker.js"></script>
		<meta content="Discovery - Configuration Page" name="Nicolas DIETRICH">
	</head>
	<body style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); height: 158px;" alink="#ff6600" link="#ff6600" vlink="#ff6600">  
		<span class="" style="font-family: Candara;  font-weight: bold;"><br>
		
			<?php

			if (!isset ($oreon)) {
				exit ();
			}

			require_once './modules/Discovery/include/DB-Func.php';
			 $CONFIG=getConfig();
			?>

			<br>
			<table width="100%">
				<tr>
					<td width="30%"></td>
					<td width="40%"><h1 align="center">Discovery - Configuration page</h1></td>
					<td width="30%"><img align="right" alt="Centreon-Discovery" src="./modules/Discovery/include/images/logo.png"/><td>		
				<br><br></tr>
			<?php
			
			function doFormTab($id) {
				
				if ($id==1) {
				echo '	<tr>
							<td width="30%"></td>
							<td width="40%"><h2 align="center"><i>Default Values</i></h2></td>
						</tr>
					</table>
					<br><br>';
				}
				if ($id==2) {
				echo '	<tr>
							<td width="30%"></td>
							<td width="40%"><h2 align="center"><i>Template / OS Relation</i></h2></td>
						</tr>
					</table>
					<br><br>';
				}
				echo '<div>'."\n";
				echo '  <ul id="onglets">'."\n";
				if ($id==1) { echo '<li class="active">'; } else { echo '<li class="a" style="">'; }
				echo '<a href="./main.php?p=61203&id=1">  Default Values  </a></li>'."\n";
				if ($id==2) { echo '<li class="active">'; } else { echo '<li class="a" style="">'; }
				echo '<a href="./main.php?p=61203&id=2">  Template / OS Relation  </a></li>'."\n";
				echo '  </ul>'."\n";
				echo '</div>'."\n";
			}
			
			function doForm($id,$error){
				global $CONFIG;
				$select1="";
				$select2="";
				switch ($id) {
					case 1: {
						#Default values configuration
						$values=mysql_query("SELECT * FROM mod_discovery_rangeip WHERE id=0;");
						$valuesData=mysql_fetch_array($values,MYSQL_ASSOC);
						echo '<div id="tab1" class="tab">'."\n";
						echo '    <table class="ListTable">'."\n";
						echo '        <tr class="ListHeader"><td class="FormHeader" colspan="2">&nbsp;<img src="./img/icones/16x16/tool.gif">&nbsp;Modify General Options</td></tr>'."\n";
						echo '<form method="post" action="" name="Form">'."\n";
						echo '	<tr class="list_lvl_1"><td class="ListColLvl1_name" colspan="2">&nbsp;<img src="./img/icones/16x16/text_rich_colored.gif">&nbsp;&nbsp;Display Settings</td></tr>'."\n";
						?>
						<tr class="list_one"><td class="FormRowField">Host exists color</td><td class="FormRowValue">
							<input type="text" name="host_exists" size="7" maxlength="7" value="<?php echo $CONFIG['host_exists_color']; ?>" style="text-align:center;" />
							<input style="width:50px; height:15px; background-color: <?php echo $CONFIG['host_exists_color']; ?>; border-width:0px; padding-bottom:2px;" onclick="popup_color_picker('host_exists','Hosts Exists Color');" name="host_exists_color" value="" type="button" />
						</td>
						<tr class="list_two"><td class="FormRowField">IP exists color</td><td class="FormRowValue">
							<input type="text" name="ip_exists" size="7" maxlength="7" value="<?php echo $CONFIG['ip_exists_color']; ?>" style="text-align:center;" />
							<input style="width:50px; height:15px; background-color: <?php echo $CONFIG['ip_exists_color']; ?>; border-width:0px; padding-bottom:2px;" onclick="popup_color_picker('ip_exists','IP Exists Color');" name="ip_exists_color" value="" type="button" />
						</td>
						</td>
						<tr class="list_one"><td class="FormRowField">Host missing color</td><td class="FormRowValue">
							<input type="text" name="host_missing" size="7" maxlength="7" value="<?php echo $CONFIG['host_missing_color']; ?>" style="text-align:center;" />
							<input style="width:50px; height:15px; background-color: <?php echo $CONFIG['host_missing_color']; ?>; border-width:0px; padding-bottom:2px;" onclick="popup_color_picker('host_missing','Host Missing Color');" name="host_missing_color" value="" type="button" />
						</td>
						</td>
						<tr class="list_two"><td class="FormRowField">Filtering hostname's FQDN</td><td class="FormRowValue">
								<input type="radio" name="consider_fqdn" value="1" <?php echo ($CONFIG['consider_fqdn']=='1')? 'checked':''; ?>> Yes
								<input type="radio" name="consider_fqdn" value="0" <?php echo ($CONFIG['consider_fqdn']=='0')? 'checked':''; ?>> No
						</td>
<?php
						
						echo '	<tr class="list_lvl_1"><td class="ListColLvl1_name" colspan="2">&nbsp;<img src="./modules/Discovery/include/images/nmap_logo16x16.png">&nbsp;&nbsp;NMAP Settings</td></tr>'."\n";
						echo '	<tr class="list_one"><td class="FormRowField">Profil</td><td class="FormRowValue">'."\n";
						switch ($valuesData["nmap_profil"]){
							case 'Sneaky (T1)';
								$select1="selected";
								$select2=$select3=$select4=$select5="";
								break;
							case 'Polite (T2)';
								$select2="selected";
								$select1=$select3=$select4=$select5="";
								break;
							case 'Normal (T3)';
								$select3="selected";
								$select2=$select1=$select4=$select5="";
								break;
							case 'Aggressive (T4)';
								$select4="selected";
								$select2=$select3=$select1=$select5="";
								break;
							case 'Insane(T5)';
								$select5="selected";
								$select2=$select3=$select4=$select1="";
								break;
						}
						echo '          <select name="profil_nmap">'."\n";
						echo '              <option '.$select1.'>Sneaky (T1)</option>'."\n";
						echo '              <option '.$select2.'>Polite (T2)</option>'."\n";
						echo '              <option '.$select3.'>Normal (T3)</option>'."\n";
						echo '              <option '.$select4.'>Aggressive (T4)</option>'."\n";
						echo '              <option '.$select5.'>Insane(T5)</option>'."\n";
						echo '          </select>'."\n";	
						echo '      </tr>'."\n";
						echo '	<tr class="list_two"><td class="FormRowField">Host Timeout</td><td class="FormRowValue"><input style="text-align:center;"  type="text" name="nmap_timeout" size="12" value="'.$valuesData["nmap_host_timeout"].'">&nbsp;milliseconds</td></tr>'."\n";
						echo '	<tr class="list_one"><td class="FormRowField">Max RTT Timeout</td><td class="FormRowValue"><input style="text-align:center;"  type="text" name="nmap_timeout_rtt" size="12" value="'.$valuesData["nmap_max_rtt_timeout"].'">&nbsp;milliseconds</td></tr>'."\n";
						echo '	<tr class="list_two"><td class="FormRowField">Max Retries</td><td class="FormRowValue"><input style="text-align:center;"  type="text" name="nmap_retries" size="12" value="'.$valuesData["nmap_max_retries"].'"></td></tr>'."\n";						
						echo '	<tr class="list_lvl_1"><td class="ListColLvl1_name" colspan="2">&nbsp;<img src="./img/icones/16x16/oreon.gif">&nbsp;&nbsp;SNMP Settings</td></tr>'."\n";
						echo '	<tr class="list_two"><td class="FormRowField">Port</td><td class="FormRowValue"><input style="text-align:center;"  type="text" name="port" size="12" value="'.$valuesData["snmp_port"].'"></td></tr>'."\n";												
						echo '	<tr class="list_one"><td class="FormRowField">Version</td><td class="FormRowValue">'."\n";
						if ($valuesData["snmp_version"]==1){
							$select6="selected";
							$select7="";
						}
						else {
						$select7="selected";
						$select6="";
						}
						echo '         	<select name="version">'."\n";
						echo '              <option '.$select6.'>1</option>'."\n";
						echo '              <option '.$select7.'>2c</option>'."\n";
						echo '          </select></td></tr>'."\n";							
						echo '	<tr class="list_two"><td class="FormRowField">Community</td><td class="FormRowValue"><input style="text-align:center;"  type="text" name="community" size="12" value="'.$valuesData["snmp_community"].'"></td></tr>'."\n";
						echo '	<tr class="list_one"><td class="FormRowField">Timeout</td><td class="FormRowValue"><input style="text-align:center;"  type="text" name="timeout" size="12" value="'.$valuesData["snmp_timeout"].'">&nbsp;seconds</td></tr>'."\n";
						echo '	<tr class="list_two"><td class="FormRowField">Retries</td><td class="FormRowValue"><input style="text-align:center;"  type="text" name="retries" size="12" value="'.$valuesData["snmp_retries"].'"></td></tr>'."\n";
						echo '	<tr class="list_lvl_1"><td class="ListColLvl1_name" colspan="2">&nbsp;<img src="./modules/Discovery/include/images/logo_oid.gif">&nbsp;&nbsp;OID</td></tr>'."\n";
						echo '	<tr class="list_one"><td class="FormRowField">Hostname</td><td class="FormRowValue"><input style="text-align:center;"  type="text" name="hostname" size="12" value="'.$valuesData["oid_hostname"].'"></td></tr>'."\n";
						echo '	<tr class="list_two"><td class="FormRowField">OS Version</td><td class="FormRowValue"><input style="text-align:center;" type="text" name="OS" size="12" value="'.$valuesData["oid_os"].'"></td></tr>'."\n";
						echo ' </table>'."\n";
						echo '	<div id="validForm">'."\n";
						echo '		<p><input name="save" value=" Save " type="submit" />&nbsp;&nbsp;&nbsp;<input name="defaults" value=" Default Values " type="submit" /></p>'."\n";
						echo '	</div>'."\n";
						echo '</form>'."\n";
						
					} break;
					case 2: {
						#template os relation configuration
						$relations=mysql_query("SELECT * FROM mod_discovery_template_os_relation");
						echo '<div id="tab1" class="tab">'."\n";
						echo '    <table class="ListTable">'."\n";
						echo '        <tr class="ListHeader">'."\n";
						echo '            <td class="FormHeader">&nbsp;&nbsp;</td> '."\n";
						echo '            <td class="FormHeader">&nbsp;&nbsp;</td> '."\n";
						echo '        </tr>'."\n";
						echo '        <tr>'."\n";
						echo '            <td align="center">'."\n";
						echo ' <form method="post" action="">'."\n";
						echo '     <center>'."\n";
						echo '     <table>'."\n";
						echo '         <tr>'."\n";
						echo '             <td align=center colspan=3><h3>OS - Template Relation: </h3></td>'."\n";
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
						echo '             <td class="ListColHeaderCenter"><input type="submit" title="Delete All from List" name="clear" value=" Clear All " onClick="return window.confirm(\'Are you sure you want delete all ?\');self.location=\'./main.php?p=61204&id=2\'"></td>'," \n";
						echo '         </tr>'."\n";
					$list1="list_one";
					$list2="list_two";
					$listcnt=1;
						while ($relationsData=mysql_fetch_array($relations,MYSQL_ASSOC)){
						if($listcnt%2==0){
							$list=$list2;
						}
						else {
							$list=$list1;
						}
							echo ' <tr class="'.$list.'">'." \n ";
							echo '  <td class="ListColHeaderCenter">'.$relationsData["template"].'</td>'."\n";
							echo '  <td class="ListColHeaderCenter">'.$relationsData["os"].'</td>'."\n";
							echo '  <td class="ListColHeaderCenter"><input style="border:none" type="image" src="./modules/Discovery/include/images/delete16x16.png" title="Delete One from List" name="'.$relationsData["id"].'" value="'.$relationsData["id"].'" onClick="return window.confirm(\'Are you sure you want delete this item ?\n ('.$relationsData["template"].' | '.$relationsData["os"].')\');self.location=\'./main.php?p=61204&id=2\'"></td>',"\n";
							echo ' </tr>'." \n ";
							$listcnt++;
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
					/* Couleurs */
					
					if (isset($_POST['host_exists']) && isset($_POST['ip_exists']) && isset($_POST['host_missing']) && isset($_POST['consider_fqdn'])){
						mysql_query("UPDATE mod_discovery_config SET host_exists_color='".$_POST["host_exists"]."', ip_exists_color='".$_POST["ip_exists"]."', host_missing_color='".$_POST["host_missing"]."', consider_fqdn='".$_POST['consider_fqdn']."'")or die(mysql_error());
					}
				
					/* NMAP */
					
					if (isset($_POST["profil_nmap"])){
						mysql_query("UPDATE mod_discovery_rangeip SET nmap_profil='".$_POST["profil_nmap"]."' WHERE id=0;");
					}

					if (isset($_POST["nmap_timeout"]) && is_int(intval($_POST["nmap_timeout"])) && $_POST["nmap_timeout"]>=15000 && $_POST["nmap_timeout"]<100000){
						mysql_query("UPDATE mod_discovery_rangeip SET nmap_host_timeout='".$_POST["nmap_timeout"]."' WHERE id=0;");
					}

					if (isset($_POST["nmap_timeout_rtt"]) && is_int(intval($_POST["nmap_timeout_rtt"])) && $_POST["nmap_timeout_rtt"]>=100 && $_POST["nmap_timeout_rtt"]<10000){
						mysql_query("UPDATE mod_discovery_rangeip SET nmap_max_rtt_timeout='".$_POST["nmap_timeout_rtt"]."' WHERE id=0;");
					}

					if (isset($_POST["nmap_retries"]) && is_int(intval($_POST["nmap_retries"])) && $_POST["nmap_retries"]>=0 && $_POST["nmap_retries"]<100){
						mysql_query("UPDATE mod_discovery_rangeip SET nmap_max_retries='".$_POST["nmap_retries"]."' WHERE id=0;");
					}
					
					/* OID */
					
					if (isset($_POST["hostname"]) && !empty($_POST["hostname"]) && ereg("^(\.([1-9][0-9]+|[0-9]))+$", $_POST["hostname"])){
						mysql_query("UPDATE mod_discovery_rangeip SET oid_hostname='".$_POST["hostname"]."' WHERE id=0;");
					}
					
					if (isset($_POST["OS"]) && !empty($_POST["OS"]) && ereg("^(\.([1-9][0-9]+|[0-9]))+$", $_POST["OS"])){
						mysql_query("UPDATE mod_discovery_rangeip SET oid_os='".$_POST["OS"]."' WHERE id=0;");
					}
					
					/* SNMP */
					
					if (isset($_POST["version"])){
						mysql_query("UPDATE mod_discovery_rangeip SET snmp_version='".$_POST["version"]."' WHERE id=0;");
					}
					
					if (isset($_POST["port"]) && is_int(intval($_POST["port"])) && $_POST["port"]>0 && $_POST["port"]<65536){
						mysql_query("UPDATE mod_discovery_rangeip SET snmp_port='".$_POST["port"]."' WHERE id=0;");
					}

					if (isset($_POST["retries"]) && is_int(intval($_POST["retries"])) && $_POST["retries"]>=0 && $_POST["retries"]<100){
						mysql_query("UPDATE mod_discovery_rangeip SET snmp_retries='".$_POST["retries"]."' WHERE id=0;");
					}
					
					if (isset($_POST["timeout"]) && is_int(intval($_POST["timeout"])) && $_POST["timeout"]>0 && $_POST["timeout"]<100){
						mysql_query("UPDATE mod_discovery_rangeip SET snmp_timeout='".$_POST["timeout"]."' WHERE id=0;");
					}
					
					if (isset($_POST["community"]) && !strpos($_POST["community"]," ") && !empty($_POST["community"])){
						mysql_query("UPDATE mod_discovery_rangeip SET snmp_community='".$_POST["community"]."' WHERE id=0;");
					}
					echo '<META HTTP-EQUIV="Refresh" CONTENT="1; URL=main.php?p=61203">';
					echo '<META HTTP-EQUIV="Refresh" CONTENT="1; URL=main.php?p=61203">';
				}
				
				if(isset($_POST["defaults"])){
					$reqDefault=mysql_query("SELECT * FROM mod_discovery_rangeip WHERE id=-1;");
					while ($default=mysql_fetch_array($reqDefault,MYSQL_ASSOC)) {
						mysql_query("UPDATE mod_discovery_rangeip SET nmap_profil='".$default['nmap_profil']."', nmap_host_timeout='".$default['nmap_host_timeout']."', nmap_max_rtt_timeout='".$default['nmap_max_rtt_timeout']."', nmap_max_retries='".$default['nmap_max_retries']."', snmp_port='".$default['snmp_port']."', snmp_retries='".$default['snmp_retries']."', snmp_timeout='".$default['snmp_timeout']."', snmp_community='".$default['snmp_community']."', snmp_version='".$default['snmp_version']."', oid_os='".$default['oid_os']."', oid_hostname='".$default['oid_hostname']."' WHERE id=0;");
					}
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
