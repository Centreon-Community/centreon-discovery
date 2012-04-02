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
 * @version $Id: $
 * @copyright (c) 2007-2009 Centreon
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 */
 
require_once'./modules/Discovery/include/DB-Func.php';
require_once "HTML/QuickForm.php";
require_once 'HTML/QuickForm/select.php';
require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
$form = new HTML_QuickForm('form_import','post','main.php?p=61202');

/* Variable indiquant la position de l'agent Python, elle est modifiée par le fichier install.sh lors de l'installation du module. */
//$agentDir = "/usr/share/centreon-discovery/DiscoveryAgent_central.py";
$agentDir = "@AGENT_DIR@/DiscoveryAgent_central.py";

 ?>

<html lang="fr">
    <head>
            <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
            <title>Discovery</title>
            <link href="./Themes/Centreon-2/style.css" rel="stylesheet" type="text/css"/>
            <meta content="Discovery - Results Page" name="Nicolas DIETRICH">
            <!-- Fonctions JavaScript -->
            <script type="text/javascript" src="./modules/Discovery/include/JS-Func.js"></script>
			<script type="text/javascript" src="./modules/Discovery/include/jquery-1.6.4.min.js"></script>
            <script type="text/javascript" src="./modules/Discovery/include/script.js"></script>
            <!-- End Fonctions JavaScript -->
    </head>
    <body style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); height: 158px;" alink="#ff6600" link="#ff6600" vlink="#ff6600" <?php if ((isset($_GET['stop'])) && ($_GET['stop'] == 1)){ echo '';} else {echo 'onload="refresh_div();"';} ?>>
        <span class="" style="font-family: Candara;  font-weight: bold;"><br>
            <?php
            if (!isset ($oreon)) {
            	exit ();
            }
			?>
            <br>
            <table width="100%">
                <tr>
                 	<td width="30%"></td>
                    <td width="40%"><h1 ALIGN=CENTER>Discovery Results</h1></td>
					<td width="30%"><img ALIGN=right alt="Centreon-Discovery" src="./modules/Discovery/include/images/logo.png"/><td>					
                </tr>
            </table>
            <br><br>

            <?php
			
                /*
                 * {Display subnets detection mod_discovery_results as an array of IP,
                 * Hostname, OS and checkboxes for selecting hosts to create}
                 *
                 * {Display each input data in an array}
                 *
                 */

                function doFormTab(){
                    $cbgroup = 0;
                    $elt_number = 0;
                    $group_number = 0;

                    echo '       <form method="POST" action="main.php?p=61202&stop=1">',"\n";
                    echo '           <table align="right">',"\n";
                    echo '              <tr>',"\n";
                    echo '                   <td>&nbsp;<p align="right"><input type=button value=" << Back " onClick="self.location=\'./main.php?p=61201\'">&nbsp;&nbsp;</p></td>',"\n";
                    echo '                   <td>&nbsp;<p align="right"><input type="submit" value=" Create Selected Hosts ">&nbsp;&nbsp;</p></td>',"\n";
                    echo '              </tr>',"\n";
                    echo '           </table>',"\n";
                    echo '           <br><br><br>'," \n ";
                    //on cherche les subnets marqués comme achevés : done!=0 ou 2
                    $subnetsDone = mysql_query("SELECT id,plage,masque,cidr,nagios_server_id,done FROM mod_discovery_rangeip WHERE id!=0 AND done!='0';");
                    while($subnetDoneData = mysql_fetch_array($subnetsDone,MYSQL_ASSOC)){
                        $subnetPoller=mysql_query("SELECT name,ns_ip_address FROM nagios_server WHERE id='".$subnetDoneData["nagios_server_id"]."';");
                        $subnetPollerData=mysql_fetch_array($subnetPoller,MYSQL_ASSOC);
                        $cbgroup++;

						//Si done=2 alors le scan de la plage en question est terminé
						if ($subnetDoneData["done"]==2){
                            $netoctets=explode(".",$subnetDoneData["plage"]);
                            $maskoctets=explode(".",$subnetDoneData["masque"]);
                            $subnetHostsList = mysql_query("SELECT * FROM mod_discovery_results WHERE SUBSTRING_INDEX(`ip`, '.', 1)&".$maskoctets[0]." = ".$netoctets[0]." AND SUBSTRING_INDEX(SUBSTRING_INDEX(`ip`, '.',-3),'.',1)&".$maskoctets[1]." = ".$netoctets[1]." AND SUBSTRING_INDEX(SUBSTRING_INDEX(`ip`, '.',-2),'.',1)&".$maskoctets[2]." = ".$netoctets[2]." AND SUBSTRING_INDEX(`ip`,'.',-1)&".$maskoctets[3]." = ".$netoctets[3]." ORDER BY ip asc ;");

							//Si il y a des adresses découvertes
                            if (mysql_num_rows($subnetHostsList)>0){
                                $GlobalCbDisable="";
                                $nbSNMPKo=mysql_query("SELECT Count(*) FROM mod_discovery_results WHERE hostname='N/A' AND SUBSTRING_INDEX(`ip`, '.', 1)&".$maskoctets[0]." = ".$netoctets[0]." AND SUBSTRING_INDEX(SUBSTRING_INDEX(`ip`, '.',-3),'.',1)&".$maskoctets[1]." = ".$netoctets[1]." AND SUBSTRING_INDEX(SUBSTRING_INDEX(`ip`, '.',-2),'.',1)&".$maskoctets[2]." = ".$netoctets[2]." AND SUBSTRING_INDEX(`ip`,'.',-1)&".$maskoctets[3]." = ".$netoctets[3]." ;");
                                $nbSNMPKoData=mysql_fetch_array($nbSNMPKo,MYSQL_ASSOC);
								//On compte le nombre d'IP découvertes dans la plage
								$reqNbIpFound=mysql_query("SELECT Count(*) FROM mod_discovery_results WHERE plage_id=".$subnetDoneData["id"].";");
								$nbIpFound=mysql_fetch_array($reqNbIpFound);
                                if ($nbSNMPKoData['Count(*)']==mysql_num_rows($subnetHostsList)) $GlobalCbDisable="disabled";
                                echo '          <br><font size="3px">'.$subnetDoneData["plage"].' /'.$subnetDoneData["cidr"].' polled by '.$subnetPollerData["name"].' ('.$subnetPollerData["ns_ip_address"].') | <a href="#" title="View addresses" onClick="$(\'#scan'.$subnetDoneData["id"].'\').slideToggle(\'fast\');"><font color="green" size="2px"> '.$nbIpFound[0].' address(es) discovered</font></a><br>'," \n ";
								echo'			<div id="scan'.$subnetDoneData["id"].'">'," \n ";
								echo '          <br><br><table class="ListTable">'," \n ";
                                echo '             <tr class="ListHeader">'," \n ";
								echo '                  <td width="5%" class="ListColHeaderCenter"><input type=checkbox NAME="cb'.$cbgroup.'[]" VALUE="'. $subnetDoneData["plage"] .'" OnClick="checkall(document.getElementsByName(\'cb'.$cbgroup.'[]\'));" '.$GlobalCbDisable.'/></td>'," \n ";
                                echo '                  <INPUT TYPE=HIDDEN NAME="group_list[]" VALUE="'. $cbgroup .'" />'," \n ";
                                echo '                  <td width="10%" class="ListColHeaderCenter">Host</td>'," \n ";
                                echo '                  <td width="15%" class="ListColHeaderCenter">Hostname</td>'," \n ";
                                echo '                  <td width="15%" class="ListColHeaderCenter">Operating System</td>'," \n ";
                                echo '                  <td width="15%" class="ListColHeaderCenter">Host Template</td>'," \n ";
                                echo '                  <td width="15%" class="ListColHeaderCenter">Host Group</td>'," \n ";
                                echo '                  <td width="8%" class="ListColHeaderCenter">Exist</td>'," \n ";
								echo '					<td width="5%" class="ListColHeaderCenter"><a href="#" ><img style="border:none" type="image" src="./modules/Discovery/include/images/clearAll1.png" title="Delete all from list" onmouseover="javascript:this.src=\'./modules/Discovery/include/images/clearAll2.png\';" onmouseout="javascript:this.src=\'./modules/Discovery/include/images/clearAll1.png\';" onClick="self.location=\'./main.php?p=61202&clearall='.$subnetDoneData["id"].'\'"></td>'," \n";
                                echo '              </tr>'," \n ";
                                while ($subnetHostData = mysql_fetch_array($subnetHostsList,MYSQL_ASSOC)){
                                    $elt_number++;
                                    echo '            	<tr class="list_one">'," \n";
                                    echo '                  <td width="5%" class="ListColCenter"><INPUT TYPE=CHECKBOX NAME="cb'.$cbgroup.'[]" VALUE="'.$subnetHostData["id"].'"/></td>',"\n";
                                    echo '                  <td width="10%" class="ListColCenter">'.$subnetHostData["ip"].'</td>'."\n ";
                                    echo '                  <td width="17%" class="ListColCenter">'.$subnetHostData["hostname"].'</td>'."\n ";
                                    echo '                  <td width="17%" class="ListColCenter" title="'.$subnetHostData["os"].'">'.strCut($subnetHostData["os"]).'</td>'."\n ";
                                    
									/* Liste des templates */
									$default = findOs($subnetHostData["os"]);
									echo '                  <td width="10%" class="ListColCenter"><select name=select_template'.$subnetHostData["id"].' width=95%>'."\n ";
                                    $listTemplate=mysql_query("SELECT * FROM host WHERE host_register='0' ORDER BY host_id DESC;");
                                    while ($listTemplateData=mysql_fetch_array($listTemplate,MYSQL_ASSOC)){
                                        echo "                  <option value='".$listTemplateData["host_id"]."'";
										if ($default == $listTemplateData["host_name"]) echo ' selected = "selected" ';
										echo "					>".$listTemplateData["host_name"]."</option>"."\n ";
                                    }
                                    echo '                  </select></td>'."\n ";
									
									/* Liste des groupes */
									echo '                  <td width="10%" class="ListColCenter"><select name=select_group'.$subnetHostData["id"].' width=95%><option value="-1">None</option>'."\n ";
                                    $listGroup=mysql_query("SELECT * FROM hostgroup;");
                                    while ($listGroupData=mysql_fetch_array($listGroup,MYSQL_ASSOC)){
                                        echo "                  <option value='".$listGroupData["hg_id"]."'>".$listGroupData["hg_name"]."</option>"."\n ";
                                    }
                                    echo '                  </select></td>'."\n ";
									
									/* Vérification si l'hôte existe déja */
                                    $reqHost=mysql_query("SELECT * FROM host WHERE host_name='".$subnetHostData["hostname"]."';");
									if (mysql_num_rows($reqHost)==0){
										if ($subnetHostData["hostname"] == NULL){
											echo '                  <td width="10%" class="ListColCenter" style="color:orange"><b>Not Defined</b></td>'."\n ";
										}else{
											echo '                  <td width="10%" class="ListColCenter" style="color:green"><b>Not Exist</b></td>'."\n ";
										}
									}else{
										$host=mysql_fetch_array($reqHost);
										echo '                  <td width="10%" class="ListColCenter" style="color:red"><b>Already Exist</b></td>'."\n ";
									}
									
									echo ' 					<td width="10%" class="ListColCenter"><a href="#"><img style="border:none" type="image" src="./modules/Discovery/include/images/delete16x16.png" title="Delete one from list" name="ClearRow" onClick="self.location=\'./main.php?p=61202&id='.$subnetHostData["id"].'\'"></a></td>',"\n ";
                                    echo '              </tr>'," \n ";
                                }
                                echo '          </table>'," \n ";
								echo '			</div>'," \n ";
                            }
							
							//Si le scan est terminé, mais que aucune IP n'est découverte
							if (mysql_num_rows($subnetHostsList)==0){
								echo '          <br><font size="3px">'.$subnetDoneData["plage"].' /'.$subnetDoneData["cidr"].' polled by '.$subnetPollerData["name"].' ('.$subnetPollerData["ns_ip_address"].') | <a href="#" title="View addresses" onClick="$(\'#scan'.$subnetDoneData["id"].'\').slideToggle(\'fast\');"><font color="orange" size="2px"> 0 address discovered</font></a><br>'," \n ";
								echo '			<div id="scan'.$subnetDoneData["id"].'">'," \n ";
								echo '          <br><br><table class="ListTable">'," \n ";
								echo '             <tr class="ListHeader">'," \n ";
								echo '                  <td width="22%" class="ListColHeaderCenter">Host</td>'," \n ";
								echo '                  <td width="22%" class="ListColHeaderCenter">Hostname</td>'," \n ";
								echo '                  <td width="22%" class="ListColHeaderCenter">Operating System</td>'," \n ";
								echo '                  <td width="22%" class="ListColHeaderCenter">Host Template</td>'," \n ";
								echo '              </tr>'," \n ";
								echo '             <tr class="list_one">'," \n";
								echo '                  <td width="22%" colspan="5" class="ListColCenter"> No result </td>'."\n ";
								echo '              </tr>'," \n ";
								echo '          </table>'," \n ";
								echo '          <br>'," \n ";
								echo '			</div>'," \n ";
							}     
						}
						
						//Si le scan n'est pas fini on affiche un logo "patientez"
                        else if ($subnetDoneData["done"]==1) {
							echo '          <br><font size="3px">'.$subnetDoneData["plage"]." /".$subnetDoneData["cidr"]." polled by ".$subnetPollerData["name"]." (".$subnetPollerData["ns_ip_address"].")<br><br> \n ";
							echo '          <table class="ListTable">'," \n ";
							echo '             <tr class="ListHeader">'," \n ";
							echo '                  <td width="22%" class="ListColHeaderCenter">Host</td>'," \n ";
							echo '                  <td width="22%" class="ListColHeaderCenter">Hostname</td>'," \n ";
							echo '                  <td width="22%" class="ListColHeaderCenter">Operating System</td>'," \n ";
							echo '                  <td width="22%" class="ListColHeaderCenter">Host Template</td>'," \n ";
							echo '              </tr>'," \n ";
							echo '             <tr class="list_one">'," \n";
							echo '                  <td width="22%" colspan="5" class="ListColCenter"><img style="border:none" type="image" src="./modules/Discovery/include/images/loading2.gif" title="Loading..."></td>'."\n ";
							echo '              </tr>'," \n ";
							echo '          </table>'," \n ";
							echo '          <br>'," \n ";
                        }   
						/* Si une erreur est survenue pendant le scan */
                        else if ($subnetDoneData["done"]==3) {
							echo '          <br><font size="3px">'.$subnetDoneData["plage"].' /'.$subnetDoneData["cidr"].' polled by '.$subnetPollerData["name"].' ('.$subnetPollerData["ns_ip_address"].') | <a href="#" title="View addresses" onClick="$(\'#scan'.$subnetDoneData["id"].'\').slideToggle(\'fast\');"><font color="red" size="2px"> ERROR : Connection lost with the poller agent</font></a><br>'," \n ";
							echo '			<div id="scan'.$subnetDoneData["id"].'">'," \n ";
							echo '          <br><br><table class="ListTable">'," \n ";
							echo '             <tr class="ListHeader">'," \n ";
							echo '                  <td width="22%" class="ListColHeaderCenter">Host</td>'," \n ";
							echo '                  <td width="22%" class="ListColHeaderCenter">Hostname</td>'," \n ";
							echo '                  <td width="22%" class="ListColHeaderCenter">Operating System</td>'," \n ";
							echo '                  <td width="22%" class="ListColHeaderCenter">Host Template</td>'," \n ";
							echo '              </tr>'," \n ";
							echo '             <tr class="list_one">'," \n";
							echo '                  <td width="22%" colspan="5" class="ListColCenter"> No result </td>'."\n ";
							echo '              </tr>'," \n ";
							echo '          </table>'," \n ";
							echo '          <br>'," \n ";
							echo '			</div>'," \n ";
                        }  						
                    }
                    echo '          <table align="right">',"\n";
                    echo '              <tr>',"\n";
                    echo '                  <td>&nbsp;<p align="right"><input type=button value=" << Back " onClick="self.location=\'./main.php?p=61201\'">&nbsp;&nbsp;</p></td>',"\n";
                    echo '                  <td>&nbsp;<p align="right"><input type="submit" value=" Create Selected Hosts ">&nbsp;&nbsp;</p></td>',"\n";
                    echo '              </tr>',"\n";
                    echo '          </table>',"\n";
                    echo '         </form>',"\n";
                }

                function getListHost() {
                    $listhost=Array();
					$req=mysql_query("SELECT count(*) FROM mod_discovery_rangeip WHERE id!=0 AND done!=1;");
                    $nbPlage=mysql_fetch_array($req);
                    for ($i=1;$i<=$nbPlage[0];$i++){
						$cbgroup_name='cb'.$i;
                        if (isset($_POST["$cbgroup_name"]) && count($_POST["$cbgroup_name"])>0)
						{
                            foreach ($_POST["$cbgroup_name"] as $key => $value){
                                if ($key>=0){
                                    $listHost[]=$value;
                                }
                            }
                        }
                    }
                    return $listHost;
                }
				
				/*
					Fonction permettant de tronquer une chaine de caractère et de remplacer la fin par des points de suspentions.
				*/
				function strCut($string, $max = 36, $end = '...') 
				{
					if (strlen($string) > $max) {
						$string = substr($string, 0, $max - strlen($end)).$end;
					}
					return $string;
				}
				
				/* 	
					Cette fonction doit récuperer les informations du formulaire lors de la saisie des plages à scanner.
					Elle doit les saisir dans la bdd.
				*/
				function setScanValues(){				
					global $agentDir;
					
					$req=mysql_query("SELECT count(*) FROM mod_discovery_rangeip WHERE id!=0;");
                    $nbPlage=mysql_fetch_array($req);
					for ($i=0;$i<$nbPlage[0];$i++){
						if ((isset($_POST["RangeToScan".$i])) && ($_POST["RangeToScan".$i] == 'true')){
						
							/* NMAP */
							
							if (isset($_POST["profil_nmap".$i])){
								mysql_query("UPDATE mod_discovery_rangeip SET nmap_profil='".$_POST["profil_nmap".$i]."' WHERE id='".$_POST["id".$i]."';");
							}
							if (isset($_POST["nmap_timeout".$i]) && is_int(intval($_POST["nmap_timeout".$i])) && $_POST["nmap_timeout".$i]>=15000 && $_POST["nmap_timeout".$i]<100000){
								mysql_query("UPDATE mod_discovery_rangeip SET nmap_host_timeout='".$_POST["nmap_timeout".$i]."' WHERE id='".$_POST["id".$i]."';");
							}
							if (isset($_POST["nmap_timeout_rtt".$i]) && is_int(intval($_POST["nmap_timeout_rtt".$i])) && $_POST["nmap_timeout_rtt".$i]>=100 && $_POST["nmap_timeout_rtt".$i]<10000){
								mysql_query("UPDATE mod_discovery_rangeip SET nmap_max_rtt_timeout='".$_POST["nmap_timeout_rtt".$i]."' WHERE id='".$_POST["id".$i]."';");
							}
							if (isset($_POST["nmap_retries".$i]) && is_int(intval($_POST["nmap_retries".$i])) && $_POST["nmap_retries".$i]>=0 && $_POST["nmap_retries".$i]<100){
								mysql_query("UPDATE mod_discovery_rangeip SET nmap_max_retries='".$_POST["nmap_retries".$i]."' WHERE id='".$_POST["id".$i]."';");
							}
							
							/* SNMP */
							
							if (isset($_POST["version".$i])){
								mysql_query("UPDATE mod_discovery_rangeip SET snmp_version='".$_POST["version".$i]."' WHERE id=id='".$_POST["id".$i]."';");
							}
							
							if (isset($_POST["port".$i]) && is_int(intval($_POST["port".$i])) && $_POST["port".$i]>0 && $_POST["port".$i]<65536){
								mysql_query("UPDATE mod_discovery_rangeip SET snmp_port='".$_POST["port".$i]."' WHERE id='".$_POST["id".$i]."';");
							}
							
							if (isset($_POST["retries".$i]) && is_int(intval($_POST["retries".$i])) && $_POST["retries".$i]>=0 && $_POST["retries".$i]<100){
								mysql_query("UPDATE mod_discovery_rangeip SET snmp_retries='".$_POST["retries".$i]."' WHERE id='".$_POST["id".$i]."';");
							}
							
							if (isset($_POST["timeout".$i]) && is_int(intval($_POST["timeout".$i])) && $_POST["timeout".$i]>0 && $_POST["timeout".$i]<100){
								mysql_query("UPDATE mod_discovery_rangeip SET snmp_timeout='".$_POST["timeout".$i]."' WHERE id='".$_POST["id".$i]."';");
							}
							
							if (isset($_POST["snmp_community".$i]) && !strpos($_POST["snmp_community".$i]," ") && !empty($_POST["snmp_community".$i])){
								mysql_query("UPDATE mod_discovery_rangeip SET snmp_community='".$_POST["snmp_community".$i]."' WHERE id='".$_POST["id".$i]."';");
							}
							
							/* OID */
							
							if (isset($_POST["oid_hostname".$i]) && !empty($_POST["oid_hostname".$i]) && ereg("^(\.([1-9][0-9]+|[0-9]))+$", $_POST["oid_hostname".$i])){
								mysql_query("UPDATE mod_discovery_rangeip SET oid_hostname='".$_POST["oid_hostname".$i]."' WHERE id='".$_POST["id".$i]."';");
							}
							
							if (isset($_POST["oid_os".$i]) && !empty($_POST["oid_os".$i]) && ereg("^(\.([1-9][0-9]+|[0-9]))+$", $_POST["oid_os".$i])){
								mysql_query("UPDATE mod_discovery_rangeip SET oid_os='".$_POST["oid_os".$i]."' WHERE id='".$_POST["id".$i]."';");
							}
					
							//Mise à jour du champs done à 1 pour dire qu'il faut le scanner
							mysql_query("UPDATE mod_discovery_rangeip SET done=1 WHERE id='".$_POST["id".$i]."'");
							
							//Vider la table mod_discovery_results
							mysql_query("DELETE FROM mod_discovery_results;");
						}else{
							mysql_query("UPDATE mod_discovery_rangeip SET done=0 WHERE id='".$_POST["id".$i]."'");
						}
					}
					mysql_query("UPDATE mod_discovery_rangeip SET done=0 WHERE id='0'");
					//Executer le shell
					if (file_exists($agentDir)) {
						shell_exec('python '.$agentDir.' SCAN_RANGEIP > /dev/null 2>&1 &');
					}
					else { echo "<CENTER><b><font size=\"3px\" color=\"red\">ERROR</b> : File $agentDir not found...</font></CENTER>\n"; }
				}
				
				/* 	
					Cette fonction permet de déterminer quel est le template le plus approprié en fonction des informations fournie
					dans le sysDescr ainsi que de la table mod_discovery_template_os_relation
				*/
				function findOs($sysDescr)
				{
					$req=mysql_query("SELECT * FROM mod_discovery_template_os_relation;");
                    while ($values=mysql_fetch_array($req,MYSQL_ASSOC))
					{
						// Si on est dans le cas du $ (et)
						if (substr_count($values['os'],'&') >=1)
						{
							$explodeValuesAnd = explode('&',$values['os']);
							$exist = true;
							$j=0;
							while (($j<count($explodeValuesAnd)) && ($exist==true))
							{
								if (substr_count($sysDescr,$explodeValuesAnd[$j]) == 0)
								{
									$exist = false;
								}
								$j++;
							}
							if ($exist == true)
							{
								return $values['template'];
							}
						}
						// Si on est dans le cas du | (ou)
						else if(substr_count($values['os'],'|') >=1)
						{
							$explodeValuesOr = explode('|',$values['os']);
							for ($j=0;$j<count($explodeValuesOr);$j++)
							{
								if (substr_count($sysDescr,$explodeValuesOr[$j]) != 0)
								{
									return $values['template'];
								}							
							}
						}
						// Si on a qu'un seul mot clé
						else
						{
							if (substr_count($sysDescr,$values['os']) != 0)
							{
								return $values['template'];
							}
						}					
					}
					return 0;
				}
				
				function clearRangeResult(){
					$sql=mysql_query("DELETE FROM mod_discovery_results WHERE plage_id =".$_GET["clearall"].";");
				}
				
				function clearResult(){
					$sql=mysql_query("DELETE FROM mod_discovery_results WHERE id =".$_GET["id"].";");
				}
				
	            /*
                 * {main function}
                 *
                 */

                function doPost() {
                    global $conf_centreon;
                    //connexion à la base de données
                    $db = dbConnect($conf_centreon['hostCentreon'], $conf_centreon['user'], $conf_centreon['password'],$conf_centreon['db'], true);
					
					if (isset($_GET["clearall"])){
						clearRangeResult($_GET["clearall"]);
					}

					if (isset($_GET["id"])){
						clearResult($_GET["id"]);
					}
					
					if (!empty($_POST)){
					
						/* Création des hosts */
						$hostList = getListHost();
						if ($hostList != null){
							
							foreach ($hostList as $host)
							{
								//On récupere les infos sur l'hote à créer (hostname, ip, template, group)
								$reqHostToCreate = mysql_query("SELECT * FROM mod_discovery_results WHERE id=".$host.";");
								$hostToCreate = mysql_fetch_array($reqHostToCreate,MYSQL_ASSOC);
								
								//On récupere des infos sur l'hotes à créer
								$reqHostInfos = mysql_query("SELECT snmp_community,nagios_server_id FROM mod_discovery_rangeip WHERE id=".$hostToCreate["plage_id"].";");
								$hostInfos = mysql_fetch_array($reqHostInfos,MYSQL_ASSOC);
								
								//On rempli la variable tmpConf qui sera envoyée à la fonction callInsertHostInDb()
								$tmpConf = array();
								$tmpConf["host_name"] = $hostToCreate["hostname"];
								$tmpConf["host_alias"] = $hostToCreate["hostname"];
								$tmpConf["host_address"] = $hostToCreate["ip"];
								$tmpConf["nagios_server_id"] = $hostInfos["nagios_server_id"];
								$tmpConf["host_snmp_community"] =  $hostInfos["snmp_community"];
								$tmpConf["host_register"]["host_register"] = "1";
								$tmpConf["host_activate"]["host_activate"] = "1";
								$tmpConf["host_template_model_htm_id"] = $_POST["select_template".$host];
								if ($_POST["select_group".$host]!= -1)
								{
									$tmpConf["host_hgs"] = array($_POST["select_group".$host]);
								}
								$tmpConf["dupSvTplAssoc"]["dupSvTplAssoc"] = "1";
								callInsertHostInDb($tmpConf);
							}
							if (count($hostList)>1){
								$hostMsgAlert = '<script>alert("'.count($hostList).' hosts were successfully created");</script>';
							} else {
								$hostMsgAlert = '<script>alert("The host has been created successfully");</script>';
							}
						}
						else
						{					
							setScanValues();
						}
					}
					
					echo '	<div id="mon_div"></div>'."\n";
					
					doFormTab();	
					if (isset($hostMsgAlert)) echo $hostMsgAlert;
                    dbClose($db);
                }

                doPost();
             ?>
        </span>
    </body>
</html>
