<?php
/*
 * Copyright 2005-20012 MERETHIS
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
 * SVN : http://svn.modules.centreon.com/centreon-discovery/
 * 
 *
 *
 * {DESCRIPTION}
 *
 * PHP version 5
 *
 * @package Centreon-Discovery
 * @version Centreon-Discovery: 0.1
 * @copyright (c) 2007-20012 Centreon
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 */
 
require_once'./modules/Discovery/include/DB-Func.php';

/* Variable indiquant la position de l'agent Python, elle est modifiée par le fichier install.sh lors de l'installation du module. */
$agentDir = "@AGENT_DIR@/DiscoveryAgent_central.py";
//$agentDir = "./modules/Discovery/include/agent/DiscoveryAgent_central.py";

 ?>

<html lang="fr">
    <head>
            <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
            <title>Discovery</title>
            <link href="./Themes/Centreon-2/style.css" rel="stylesheet" type="text/css"/>
            <meta content="Service Auto Discovery - mod_discovery_results Page" name="Nicolas DIETRICH">
            <!-- Fonctions JavaScript -->
            <script type="text/javascript" src="./modules/Discovery/include/JS-Func.js"></script>
			<script type="text/javascript" src="./modules/Discovery/include/jquery-1.6.4.min.js"></script>
            <script type="text/javascript" src="./modules/Discovery/include/script.js"></script>
            <!-- End Fonctions JavaScript -->
    </head>
    <body style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); height: 158px;" alink="#ff6600" link="#ff6600" vlink="#ff6600">
        <span class="" style="font-family: Candara;  font-weight: bold;"><br>
            <?php

            if (!isset ($oreon)) {
            	exit ();
            }

            ?>

            <br>
            <table width="100%">
                <tr>
                    <td width="30%"><img src="./modules/Discovery/include/images/img_CentrESIEEon.png" alt="CentrESIEEon" /></td>
                    <td width="40%"><h1 ALIGN=CENTER>Discovery Results</h1></td>
                    <td width="30%"></td>
                </tr>
            </table>
            <br><br>

            <?php
			
                function createHost($listHost){
                    $result = Array();
                    $result["success"] = Array();
                    $result["error"] = Array();

                    foreach ($listHost as $key => $value){
                        $id=$listHost[$key];
                        $template_name = $_POST['select'.$id];
                        $templateId=mysql_query("SELECT host_id FROM host WHERE host_name='".$template_name."';");
                        $templateIdData = mysql_fetch_array($templateId);
                        $host = mysql_query("SELECT * FROM mod_discovery_results,mod_discovery_rangeip WHERE mod_discovery_rangeip.id = mod_discovery_results.plage_id AND mod_discovery_results.id=".$listHost[$key].";");
                        $hostData = mysql_fetch_array($host,MYSQL_ASSOC);
                        if ($hostData["hostname"]=='N/A' || $hostData["OS"]=='N/A'){
                             $result["error"]["id"][]=$listHost[$key];
                             $result["error"]["sqlErr"][]="SNMP Unreachable";
                        }
                        else if ($template_name=='None'){
                            $result["error"]["id"][]=$listHost[$key];
                            $result["error"]["sqlErr"][]="No Template Selected";
                        }
                        else {
                            if (!mysql_query("INSERT INTO host (host_template_model_htm_id,host_name, host_alias, host_address,host_snmp_version,host_snmp_community) VALUES (".$templateIdData["host_id"].",'".$hostData["hostname"]."','".$hostData["hostname"]."','".$hostData["IP"]."','".$hostData["SNMP_VERSION"]."','".$hostData["SNMP_COMMUNITY"]."');")){
                                $result["error"]["id"][]=$listHost[$key];
                                $result["error"]["sqlErr"][]=mysql_error();
                            }
                            else {
                                $host_id= mysql_query("SELECT host_id FROM host WHERE host_address='".$hostData["IP"]."' AND host_name='".$hostData["hostname"]."';");
                                $poller_id=mysql_query("SELECT mod_discovery_rangeip.nagios_server_id FROM mod_discovery_rangeip,mod_discovery_results WHERE mod_discovery_rangeip.id=mod_discovery_results.plage_id AND mod_discovery_rangeip.id ='".$hostData["plage_id"]."' limit 0,1;");
                                $poller=mysql_fetch_array($poller_id,MYSQL_ASSOC);
                                $id=mysql_fetch_array($host_id,MYSQL_ASSOC);
                                if (!mysql_query("UPDATE host SET host_register='1' WHERE host_id='".$id["host_id"]."'")) {
                                    $result["error"]["id"][]=$listHost[$key];
                                    $result["error"]["sqlErr"][]=mysql_error();
                                }
                                if (!mysql_query("INSERT INTO extended_host_information (host_host_id) VALUES('".$id["host_id"]."');")){
                                    $result["error"]["id"][]=$listHost[$key];
                                    $result["error"]["sqlErr"][]=mysql_error();
                                }
                                if (!mysql_query("INSERT INTO host_template_relation (host_host_id,host_tpl_id,`order`) VALUES('".$id["host_id"]."','".$templateIdData["host_id"]."','1');")){
                                    $result["error"]["id"][]=$listHost[$key];
                                    $result["error"]["sqlErr"][]=mysql_error();
                                }
                                if (!mysql_query("INSERT INTO ns_host_relation (nagios_server_id,host_host_id) VALUES('".$poller["nagios_server_id"]."','".$id["host_id"]."');")){
                                    $result["error"]["id"][]=$listHost[$key];
                                    $result["error"]["sqlErr"][]=mysql_error();
                                }
                                $result["success"][]=$listHost[$key];
                            }

                        }
                    }
                    $_POST=Array();
                    return $result;
                }

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

                    echo '       <form method="POST">',"\n";
                    echo '           <table align="right">',"\n";
                    echo '              <tr>',"\n";
                    echo '                   <td>&nbsp;<p align="right"><input type=button value=" << Back " onClick="self.location=\'./main.php?p=61201\'">&nbsp;&nbsp;</p></td>',"\n";
//                    echo '                   <td>&nbsp;<p align="right"><input type="submit" value=" Create Selected Hosts ">&nbsp;&nbsp;</p></td>',"\n";
                    echo '              </tr>',"\n";
                    echo '           </table>',"\n";
                    echo '           <br><br><br>'," \n ";
                    //on cherche les subnets marqués comme achevés : done!=0 ou 2
                    $subnetsDone = mysql_query("SELECT id,plage,masque,nagios_server_id,done FROM mod_discovery_rangeip WHERE id!=0 AND done!='0';");
                    while($subnetDoneData = mysql_fetch_array($subnetsDone,MYSQL_ASSOC)){
                        $subnetPoller=mysql_query("SELECT name,ns_ip_address FROM nagios_server WHERE id='".$subnetDoneData["nagios_server_id"]."';");
                        $subnetPollerData=mysql_fetch_array($subnetPoller,MYSQL_ASSOC);
                        $cbgroup++;

						//Si done=2 alors le scan de la plage en question est terminé
						if ($subnetDoneData["done"]==2){
                            $netoctets=explode(".",$subnetDoneData["plage"]);
                            $maskoctets=explode(".",$subnetDoneData["masque"]);
                            $subnetHostsList = mysql_query("SELECT * FROM mod_discovery_results WHERE SUBSTRING_INDEX(`ip`, '.', 1)&".$maskoctets[0]." = ".$netoctets[0]." AND SUBSTRING_INDEX(SUBSTRING_INDEX(`ip`, '.',-3),'.',1)&".$maskoctets[1]." = ".$netoctets[1]." AND SUBSTRING_INDEX(SUBSTRING_INDEX(`ip`, '.',-2),'.',1)&".$maskoctets[2]." = ".$netoctets[2]." AND SUBSTRING_INDEX(`ip`,'.',-1)&".$maskoctets[3]." = ".$netoctets[3]." ORDER BY new_host desc ;");

							//Si il y a des adresses découvertes
                            if (mysql_num_rows($subnetHostsList)>0){
                                $GlobalCbDisable="";
                                $nbSNMPKo=mysql_query("SELECT Count(*) FROM mod_discovery_results WHERE hostname='N/A' AND SUBSTRING_INDEX(`ip`, '.', 1)&".$maskoctets[0]." = ".$netoctets[0]." AND SUBSTRING_INDEX(SUBSTRING_INDEX(`ip`, '.',-3),'.',1)&".$maskoctets[1]." = ".$netoctets[1]." AND SUBSTRING_INDEX(SUBSTRING_INDEX(`ip`, '.',-2),'.',1)&".$maskoctets[2]." = ".$netoctets[2]." AND SUBSTRING_INDEX(`ip`,'.',-1)&".$maskoctets[3]." = ".$netoctets[3]." ;");
                                $nbSNMPKoData=mysql_fetch_array($nbSNMPKo,MYSQL_ASSOC);
								//On compte le nombre d'IP découvertes dans la plage
								$reqNbIpFound=mysql_query("SELECT Count(*) FROM mod_discovery_results WHERE plage_id=".$subnetDoneData["id"].";");
								$nbIpFound=mysql_fetch_array($reqNbIpFound);
                                if ($nbSNMPKoData['Count(*)']==mysql_num_rows($subnetHostsList)) $GlobalCbDisable="disabled";
                                echo '          <br><font size="3px">'.$subnetDoneData["plage"].' polled by '.$subnetPollerData["name"].' ('.$subnetPollerData["ns_ip_address"].') | <a href="#" title="View addresses" onClick="$(\'#scan'.$subnetDoneData["id"].'\').slideToggle(\'fast\');"><font color="red" size="2px"> '.$nbIpFound[0].' address(es) discovered.</font></a><br>'," \n ";
								echo'			<div style="display:none" id="scan'.$subnetDoneData["id"].'">'," \n ";
								echo '          <br><br><table class="ListTable">'," \n ";
                                echo '             <tr class="ListHeader">'," \n ";
								echo '                  <td width="12%" class="ListColHeaderCenter"><INPUT TYPE=CHECKBOX NAME="cb'.$cbgroup.'[]" VALUE="'. $subnetDoneData["plage"] .'" OnClick="checkall(document.getElementsByName(\'cb'.$cbgroup.'[]\'));" '.$GlobalCbDisable.'/></td>'," \n ";
                                echo '                  <INPUT TYPE=HIDDEN NAME="group_list[]" VALUE="'. $cbgroup .'" />'," \n ";
                                echo '                  <td width="22%" class="ListColHeaderCenter">Host</td>'," \n ";
                                echo '                  <td width="22%" class="ListColHeaderCenter">Hostname</td>'," \n ";
                                echo '                  <td width="22%" class="ListColHeaderCenter">Operating System</td>'," \n ";
                                echo '                  <td width="22%" class="ListColHeaderCenter">Host Template</td>'," \n ";
								echo '					<td width="22%" class="ListColHeaderCenter"><a href="#" ><img style="border:none" type="image" src="./modules/Discovery/include/images/clearAll1.png" title="Delete all from list" onmouseover="javascript:this.src=\'./modules/Discovery/include/images/clearAll2.png\';" onmouseout="javascript:this.src=\'./modules/Discovery/include/images/clearAll1.png\';" onClick="self.location=\'./main.php?p=61202&clearall='.$subnetDoneData["id"].'\'"></td>'," \n";
                                echo '              </tr>'," \n ";
                                while ($subnetHostData = mysql_fetch_array($subnetHostsList,MYSQL_ASSOC)){
                                    $elt_number++;
                                    $cbDisable="";
                                    if ($subnetHostData["hostname"]=='N/A') {
                                        $cbDisable="disabled";
                                    }
                                    echo '            	<tr class="list_one">'," \n";
                                    if ($subnetHostData["new_host"]==1){
                                        echo '                  <td class="ListColCenter"><INPUT TYPE=CHECKBOX NAME="cb'.$cbgroup.'[]" VALUE="'.$subnetHostData["id"].'" onChange="checkone(document.getElementsByName(\'cb'.$cbgroup.'[]\'));" '.$cbDisable.' /></td>',"\n";

                                    } else {
                                        echo '                  <td class="ListColCenter">already exist</td>',"\n";
                                    }
                                    echo '                  <td width="22%" class="ListColCenter">'.$subnetHostData["ip"].'</td>'."\n ";
                                    echo '                  <td width="22%" class="ListColCenter">'.$subnetHostData["hostname"].'</td>'."\n ";
                                    echo '                  <td width="22%" class="ListColCenter">'.$subnetHostData["os"].'</td>'."\n ";
                                    
									//Liste de templates
									echo '                  <td width="12%" class="ListColCenter"><select name=select'.$subnetHostData["id"].' width=95%><option>None</option>'."\n ";
                                    $listTemplate=mysql_query("SELECT * FROM host WHERE host_register='0'");
                                    while ($listTemplateData=mysql_fetch_array($listTemplate,MYSQL_ASSOC)){
                                        echo "template ".$listTemplateData["host_name"]."<br>\n";
                                        echo "                  <option value='".$listTemplateData["host_name"]."'>".$listTemplateData["host_name"]."</option>"."\n ";
                                    }
                                    echo '                  </select></td>'."\n ";
									echo ' 					<td width="22%" class="ListColCenter"><a href="#"><img style="border:none" type="image" src="./modules/Discovery/include/images/delete16x16.png" title="Delete one from list" name="ClearRow" onClick="self.location=\'./main.php?p=61202&id='.$subnetHostData["id"].'\'"></a></td>',"\n ";
                                    echo '              </tr>'," \n ";
                                }
                                echo '          </table>'," \n ";
								echo '			</div>'," \n ";
                            }
							
							//Si le scan est terminé, mais que aucune IP n'est découverte
							if (mysql_num_rows($subnetHostsList)==0){
								echo '          <br><font size="3px">'.$subnetDoneData["plage"]." polled by ".$subnetPollerData["name"]." (".$subnetPollerData["ns_ip_address"].") | <font color=\"red\"> 0 address discovered.</font><br><br> \n ";
								echo '          <table class="ListTable">'," \n ";
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
							}     
						}
						
						//Si le scanne n'est pas fini on affiche un logo "patientez"
                        else if ($subnetDoneData["done"]==1) {
							echo '          <br><font size="3px">'.$subnetDoneData["plage"]." polled by ".$subnetPollerData["name"]." (".$subnetPollerData["ns_ip_address"].")<br><br> \n ";
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
                    }
                    echo '          <table align="right">',"\n";
                    echo '              <tr>',"\n";
                    echo '                  <td>&nbsp;<p align="right"><input type=button value=" << Back " onClick="self.location=\'./main.php?p=61201\'">&nbsp;&nbsp;</p></td>',"\n";
 //                   echo '                  <td>&nbsp;<p align="right"><input type="submit" value=" Create Selected Hosts ">&nbsp;&nbsp;</p></td>',"\n";
                    echo '              </tr>',"\n";
                    echo '          </table>',"\n";
                    echo '         </form>',"\n";
                }

                function getListHost() {
                    $listhost=Array();
                     foreach ($_POST['group_list'] as $cbgroup => $cbgroup_number){
                        $cbgroup_name='cb'.$cbgroup_number;
                        if (count($_POST["$cbgroup_name"])>1){
                            foreach ($_POST["$cbgroup_name"] as $key => $value){
                                if ($key>0){
                                    $listHost[]=$value;
                                }
                            }
                        }
                    }
                    return $listHost;
                }
				
				/* 	Cette fonction doit récuperer les informations du formulaire lors de la saisie des plages à scanner.
					Elle doit les saisir dans la bdd.
				*/
				function setScanValues(){				
					global $agentDir;
					
					$req=mysql_query("SELECT count(*) FROM mod_discovery_rangeip WHERE id!=0;");
                    $nbPlage=mysql_fetch_array($req);
					for ($i=0;$i<$nbPlage[0];$i++){
						if ((isset($_POST["RangeToScan".$i])) && ($_POST["RangeToScan".$i] == 'true')){
							if (($_POST["ping_count".$i] != '') && ($_POST["ping_wait".$i] != '') && ($_POST["snmp_method".$i] != '') && ($_POST["snmp_version".$i] != '') && ($_POST["snmp_community".$i] != '') && ($_POST["oid_os".$i] != '') && ($_POST["oid_hostname".$i] != '')){
								mysql_query("UPDATE mod_discovery_rangeip SET ping_count='".$_POST["ping_count".$i]."', ping_wait='".$_POST["ping_wait".$i]."', snmp_method='".$_POST["snmp_method".$i]."', snmp_version='".$_POST["snmp_version".$i]."', snmp_community='".$_POST["snmp_community".$i]."', oid_os='".$_POST["oid_os".$i]."', oid_hostname='".$_POST["oid_hostname".$i]."', done=1 WHERE id='".$_POST["id".$i]."'");
								//Vider la table mod_discovery_results
								mysql_query("DELETE FROM mod_discovery_results;");
							}
						}else{
							mysql_query("UPDATE mod_discovery_rangeip SET done=0 WHERE id='".$_POST["id".$i]."'");
						}
					}
					//Executer le shell
					if (file_exists($agentDir)) {
//						shell_exec('python '.$agentDir.' SCAN_RANGEIP > /dev/null 2>&1 &');
						shell_exec('python '.$agentDir.' SCAN_RANGEIP >> /tmp/agent_central.log 2>&1 &');
					}
					else { echo "<CENTER><b><font size=\"3px\" color=\"red\">ERROR</b> : File $agentDir not found...</font></CENTER>\n"; }
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
					
					/* Partie modifiée	*/	

					if (isset($_GET["clearall"])){
						clearRangeResult($_GET["clearall"]);
					}

					if (isset($_GET["id"])){
						clearResult($_GET["id"]);
					}
					
					if (!empty($_POST)){
						setScanValues();
					}
					
					echo '<table align="center">'."\n";
					echo '  <tr>'."\n";
					echo '      <td>'."\n";
					echo "          <form>\n";
					echo '              <input type=button value=" Refresh " onClick="self.location=\'./main.php?p=61202\'">  Auto refresh in 10 seconds...'."\n";
					echo "          </form>\n";
					echo '      <td>'."\n";
					echo '  <tr>'."\n";
					echo '</table>'."\n";
					
					//Rafraichissement automatique de la page toutes les 10 secondes
					echo '<script type="text/javascript">';
                    echo '    setTimeout("window.location=\'./main.php?p=61202\';",10000);';
                    echo '</script>';
					
					doFormTab();
					
					/* Fin de partie modifiée */
					
                    //Recherche des hosts deja configures et mise à jour du champ new_host de la table mod_discovery_results
                    /*$newHost=mysql_query("SELECT mod_discovery_results.ip FROM host,mod_discovery_results WHERE host.host_address=mod_discovery_results.ip AND mod_discovery_results.new_host=1;");
                    while ($newHostData = mysql_fetch_array($newHost,MYSQL_ASSOC)){
                        mysql_query("UPDATE mod_discovery_results SET new_host=0 WHERE ip='".$newHostData["ip"]."';");
                    }

                    //Verification que des pages on etees saisies sinon on redirige vers la page 1
                    $isPlage=mysql_query("SELECT count(*) FROM mod_discovery_rangeip WHERE id!=0;");
                    $isPlageData=mysql_fetch_array($isPlage);
                    if ($isPlageData[0]==0){
						echo "<h3>il faut repasser par la page IP Addresses. Redirection automatique ...</h3>" ;
                        echo '<script type="text/javascript">';
                        echo '    setTimeout("window.location = \'main.php?p=61201\';",10000);';
                        echo '</script>';
                        echo '<table align="center">'."\n";
                        echo '  <tr>'."\n";
                        echo '      <td>'."\n";
                        echo "          <form>\n";
                        echo '              <input type=button value=" Step back " onClick="self.location=\'./main.php?p=61201\'">'."\n";
                        echo "          </form>\n";
                        echo '      <td>'."\n";
                        echo '  <tr>'."\n";
                        echo '</table>'."\n";
                        exit();
                    }
                    //Verification que toutes les plages saisies ont etes configurees sinon on redirige vers la page 2
					else {
						$isConfig=mysql_query("SELECT plage FROM mod_discovery_rangeip WHERE id!=0 AND tcp='0' AND ping='0';");
                        if (mysql_num_rows($isConfig)>0){
                            while ($isConfigData=mysql_fetch_array($isConfig)){
                                echo $isConfigData["plage"]."<br>\n";
                            }
                            echo "<br><br>\n";
							echo "<h3>Ces plages ne sont pas configurees.<br>Il faut repasser par la page Plugins pour configurer toutes les plages.<br>Redirection automatique ...</h3>\n" ;
                            echo '<script type="text/javascript">';
                            echo '    setTimeout("window.location = \'main.php?p=61201\';",10000);';
                            echo '</script>';
                            echo '<table align="center">'."\n";
                            echo '  <tr>'."\n";
                            echo '      <td>'."\n";
                            echo "          <form>\n";
                            echo '              <input type=button value=" Step back " onClick="self.location=\'./main.php?p=61201\'">'."\n";
                            echo "          </form>\n";
                            echo '      <td>'."\n";
                            echo '  <tr>'."\n";
                            echo '</table>'."\n";
                            exit();
                        }

                        //A ce stade les donnees presentes pour la decouverte du ou des subnet sont bonnes
                        else{
                            //on liste les subnet a explorer
                            $subnetToExplore=mysql_query("SELECT plage, masque FROM mod_discovery_rangeip WHERE id!=0 AND done=0 ORDER BY id;");
                            //si il y en a on appele la fonction callAgent()
                            if (mysql_num_rows($subnetToExplore)>0){
                                //on verifie que l'appel des agents a fonctionne
                                if (callAgent($subnetToExplore)){
                                    //on regarde si la decouverte est achevee
                                    $sql = mysql_query("SELECT done FROM mod_discovery_rangeip WHERE id=0;");
                                    $statusData = mysql_fetch_array($sql,MYSQL_ASSOC);
                                    $status = $statusData['done'];
                                    if($status!=1){
                                        //mise a jour de la barre de progression et rafraichissement de la page
                                        displayInitProgressBar();
                                        displayProgressBar();
                                        echo "<script type=\"text/JavaScript\">setTimeout(\"location.reload(true);\", 5000);</script>";
                                    }
                                }
                            }
                            else {
                                //on regarde si la decouverte est achevee mod_discovery_rangeip.done == 1 pour la plage 'fin'
                                $discoverStatus = mysql_query("SELECT done FROM mod_discovery_rangeip WHERE id=0;");
                                $statusData = mysql_fetch_array($discoverStatus,MYSQL_ASSOC);
                                $status = $statusData['done'];
                                //mise a jour de la barre de progression et rafraichissement de la page
                                if($status!=1){
                                    mysql_query("UPDATE mod_discovery_rangeip SET CIDR=CIDR+1 WHERE id=0;");
                                    displayInitProgressBar();
                                    displayProgressBar();
                                    //echo "<script type=\"text/JavaScript\">setTimeout(\"location.reload(true);\", 5000);</script>";
                                }

                            }

                            //recuperation des hotes selectiones pour la creation
                            $hostlist=getListHost();
                            if (count($hostlist)>0){
                                //appel de la fonction createHost
                                $erreur = createHost($hostlist);

                                //mise en forme du rapport de creation des hotes
                                echo '<form method="POST">',"\n";
                                echo '  <table align="right">',"\n";
                                echo '      <tr>',"\n";
                                echo '          <td>&nbsp;<p align="right"><input type=button value=" CDiscovery " onClick="self.location=\'./main.php?p=612\'"></p></td>',"\n";
                                echo '      </tr>',"\n";
                                echo '  </table>',"\n";
                                echo '  <br><br><br>'," \n ";
                                echo '  <table class="ListTable" align="left">'." \n";
                                echo '      <tr class="ListHeader">'." \n";
                                echo '          <td class="ListColHeaderCenter">mod_discovery_results</td>'." \n";
                                echo '          <td class="ListColHeaderCenter">Description</td>'." \n";
                                echo '      </tr> '." \n";
                                foreach ($erreur["error"]["id"] as $i=>$val){
                                    $err = mysql_query("SELECT ip, hostname FROM mod_discovery_results WHERE id=".$val.";");
                                    $errData = mysql_fetch_array($err,MYSQL_ASSOC);
                                    echo '      <tr class="list_one">'." \n";
                                    echo '          <td class="ListColHeaderCenter"><b style="color:#D80000">Error</b> while creating <b>'.$errData["hostname"]."</b> - <b>".$errData["ip"]."</b></td> \n";
                                    echo '          <td class="ListColHeaderCenter">'.$erreur["error"]["sqlErr"][$i].'</td>'." \n";
                                    echo '      </tr>';
                                }
                                foreach ($erreur["success"] as $i=>$val){
                                    $err = mysql_query("SELECT ip, hostname FROM mod_discovery_results WHERE id=".$val.";");
                                    $errData = mysql_fetch_array($err,MYSQL_ASSOC);
                                    echo '      <tr class="list_one">'." \n";
                                    echo '          <td class="ListColHeaderCenter"><b style="color:#33CC33">Success</b> for creating <b>'.$errData["hostname"]."</b> - <b>".$errData["ip"]."</b></td>\n";
                                    echo '          <td class="ListColHeaderCenter">&nbsp;</td>'." \n";
                                    echo '      </tr>';
                                }
                                echo '  </table>',"\n";
                                if (count($erreur["success"])>0){
                                    //affichage d'un message de rappel pour faire un nagios export
                                    echo '  <br><br><br><center>'," \n ";
                                    echo '              <p>'," \n ";
                                    echo '              <span style="font-family: Candara; font-weight: bold; color: red; font-style: italic;">'," \n ";
                                    echo '              <center><big>Votre configuration est d&eacute;sormais appliqu&eacute;e...</center></big>'," \n ";
                                    echo '              <br>'," \n ";
                                    echo '              <center>Afin de l\'activer, pensez &agrave; l\'exporter au sein de nagios</center>'," \n ";
                                    echo '              <br>'," \n ";
                                    echo '              </span>'," \n ";
                                    echo '              <center><img style="border: 0px solid ; width: 600px;" alt="Centreon" src="./modules/Discovery/pictures/screenshot_application_config_nagios.png" align="middle"></center>'," \n ";
                                    echo '              </p>'," \n ";
                                    echo '  </center><br><br><br>'," \n ";
                                }
                                echo '  <table align="right">',"\n";
                                echo '      <tr>',"\n";
                                echo '          <td>&nbsp;<p align="right"><input type=button value=" CDiscovery " onClick="self.location=\'./main.php?p=612\'"></p></td>',"\n";
                                echo '      </tr>',"\n";
                                echo '  </table>',"\n";
                                echo '</form>',"\n";
                            }
                            //affichage de la liste des hotes detectes en attendant des hotes a creer
                            else {
                                doFormTab();
                            }
                        }
                    }*/
                    dbClose($db);
                }

                doPost();
             ?>
        </span>
    </body>
</html>
