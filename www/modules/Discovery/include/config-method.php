<?php
$subnet_array=array();
require_once './modules/Discovery/include/DB-Func.php';
dbConnect($conf_centreon['hostCentreon'], $conf_centreon['user'], $conf_centreon['password'],$conf_centreon['db'], true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Step 2 - Scan parameters</title>
		<link href="./Themes/Centreon-2/style.css" rel="stylesheet" type="text/css"/>
        <!--<link rel="stylesheet" media="screen" type="text/css" title="Discovery" href="./modules/Discovery/css/discovery.css"/>-->
    </head>
    <body>
        <div>
            <table width="100%">
                <tr>
                    <td width="30%"><img src="./modules/Discovery/include/images/img_CentrESIEEon.png" alt="CentrESIEEon"/></td>
                    <td width="40%"><h1 ALIGN=RIGHT>Step 2 - Scan parameters</h1></td>
                    <td width="30%"></td>
                </tr>
            </table>
        </div>
		<?php
		//Verification que des plages sont presentes
		$isPlage=mysql_query("SELECT count(*) FROM mod_discovery_rangeip WHERE id!=0;");
		$isPlageData=mysql_fetch_array($isPlage);
		if ($isPlageData[0]==0){
			echo "Il faut repasser par la page IP Addresses afin de saisir les plages à scanner. Redirection automatique ..." ;
			echo '<script type="text/javascript">';
			echo '    setTimeout("window.location = \'main.php?p=61201\';",2000);';
			echo '</script>';
			echo "</body></html>";
			exit();
		}
		
		$values=mysql_query("SELECT * FROM mod_discovery_rangeip WHERE id=0");
		$valuesData=mysql_fetch_array($values,MYSQL_ASSOC);
		$error=array();
		
		//Mise à jour de la base de données

		//Si l'utilisateur a appuyer sur le bouton 'Save' ou 'Apply to all', alors mise à jour de la base de données
		if(isset($_POST["Submit"])||isset($_POST["Apply"])){
			//Paramètres PING
			
			//Si l'utilisateur a selectionné le 'Ping' comme méthode de découverte, alors initialisations des paramètres du ping
			if($_POST["ping"]=="true"){
				//Si l'utilisateur n'a pas renseigné le nombre de tentative de ping, alors initialisation du paramètre à 3
				if(empty ($_POST["count"])){
					$count=$valuesData["ping_count"];
				}
				//Sinon si l'utilisateur a renseigné un entier inférieur ou égal à 0, alors stockage du message d'erreur
				elseif ($_POST["count"]<=0){
					array_push($error, "Le nombre de tentative de ping doit être supérieur à 0.");
				}
				//Sinon, initialisation du paramètre avec la valeur renseignée par l'utilisateur
				else{
					$count=$_POST["count"];
				}
				//Si l'utilisateur n'a pas renseigné le timeout du ping, alors initialisation du paramètre à 1 sec.
				if(empty ($_POST["wait"])){
					$wait=$valuesData["ping_timeout"];
				}
				//Sinon si l'utilisateur a renseigné un entier inférieur ou égal à 0, alors stockage du message d'erreur
				elseif ($_POST["wait"]<=0){
					array_push($error, "Le timeout du ping doit être supérieur à 0.");
				}
				//Sinon, initialisation du paramètre avec la valeur renseignée par l'utilisateur
				else{
					$wait=$_POST["wait"];
				}
				$ping="ping='1', ping_count='".$count."', ping_wait='".$wait."'";
			}
			//Sinon, initialiser le paramètre ping de la requête à 0
			else{
				$ping="ping='0'";
			}

			//Paramètres TCP

			//Si l'utilisateur a selectionné 'TCP' comme méthode de découverte, alors initialisations des paramètres du TCP
			if($_POST["tcp"]=="true"){
				//Si l'utilisateur n'a pas renseigné le(s) numéro(s) de port TCP, alors initialisation du paramètre à 80
				if(empty ($_POST["port"])){
					$port=$valuesData["tcp_port"];
				}
				//Sinon si l'utilisateur n'a pas respecté le format, alors stockage du message d'erreur
				elseif(!ereg("^(6553[0-5]|655[0-2][0-9]|65[0-4][0-9]{1,2}|6[0-4][0-9]{1,3}|[1-5][0-9]{1,4}|[1-9][0-9]{1,3}|[0-9])(-(6553[0-5]|655[0-2][0-9]|65[0-4][0-9]{1,2}|6[0-4][0-9]{1,3}|[1-5][0-9]{1,4}|[1-9][0-9]{1,3}|[0-9]))?(;(6553[0-5]|655[0-2][0-9]|65[0-4][0-9]{1,2}|6[0-4][0-9]{1,3}|[1-5][0-9]{1,4}|[1-9][0-9]{1,3}|[0-9])(-(6553[0-5]|655[0-2][0-9]|65[0-4][0-9]{1,2}|6[0-4][0-9]{1,3}|[1-5][0-9]{1,4}|[1-9][0-9]{1,3}|[0-9]))?)*$",$_POST["port"])){
					array_push($error, "Le(s) numéro(s) de port TCP doivent respecter le format requis. Exemple pour un scan des ports 21, 80 et 1024 à 2048 : 21;80;1024-2048");
				}
				//Sinon, initialisation du paramètre avec la valeur renseignée par l'utilisateur
				else{
					$port=$_POST["port"];
				}
				$tcp="tcp='1', tcp_port='".$port."'";
			}
			//Sinon, initialiser le paramètre tcp de la requête à 0
			else{
				$tcp="tcp='0'";
			}

			//Paramètres SNMP

			//Si l'utilisateur n'a pas renseigné la communauté SNMP, alors initialisation du paramètre à public
			if(empty($_POST["community"])){
				$community=$valuesData["snmp_community"];
			}
			//Sinon, initialisation du paramètre avec la valeur renseignée par l'utilisateur
			else{
				$community=$_POST["community"];
			}
			//Si l'utilisateur n'a pas renseigné l'oid, alors initialisation du paramètre
			if(empty($_POST["oid_hostname"])){
				$oid_hostname=$valuesData["oid_hostname"];
			}
			//Sinon si l'utilisateur n'a pas respecté le format du oid, alors stockage du message d'erreur
			elseif(!ereg("^(\.([1-9][0-9]+|[0-9]))+$",$_POST["oid_hostname"])){
				array_push($error, "Le oid du hostname doit respecter le format requis. Exemple : .1.3.6.1.4.1.5518.1.5.47");
			}
			//Sinon, initialisation du paramètre avec la valeur renseignée par l'utilisateur
			else{
				$oid_hostname=$_POST["oid_hostname"];
			}
                        //Si l'utilisateur n'a pas renseigné l'oid, alors initialisation du paramètre
			if(empty($_POST["oid_os"])){
				$oid_os=$valuesData["oid_os"];
			}
			//Sinon si l'utilisateur n'a pas respecté le format du oid, alors stockage du message d'erreur
			elseif(!ereg("^(\.([1-9][0-9]+|[0-9]))+$",$_POST["oid_os"])){
				array_push($error, "Le oid de l'OS doit respecter le format requis. Exemple : .1.3.6.1.4.1.5518.1.5.47");
			}
			//Sinon, initialisation du paramètre avec la valeur renseignée par l'utilisateur
			else{
				$oid_os=$_POST["oid_os"];
			}
			$snmp="snmp='1', snmp_version='".$_POST["version"]."', snmp_method='".$_POST["method"]."', snmp_community='".$community."', oid_hostname='".$oid_hostname."', oid_os='".$oid_os."'";

			if(count($error)){
				echo "Les erreurs suivantes ont été détectées :<br>";
				while($error){
					echo array_shift($error)."<br>";
				}
				echo "Redirection de la page dans quelques secondes...";
				echo '<script type="text/javascript">';
				echo '    setTimeout("window.location = \'main.php?p=61202\';",8000);';
				echo '</script>';
				echo "</body></html>";
				exit();
			}
			//Sinon, création de la requête SQL et mise à jour de la base de données
			else{
				/* Si l'utilisateur a appuyé sur 'Save', alors création d'une requête qui met à jour les paramètres de scan pour le
				 * sous-réseau de l'onglet sélectionné*/
				if(isset($_POST["Submit"])){
					$query="UPDATE mod_discovery_rangeip SET ".$ping.", ".$tcp.", ".$snmp." WHERE id='".$_GET["id"]."';";
				}
				//Sinon, création d'une requête qui met à jour les paramètres de scan pour tout les sous-réseaux
				else{
					$query="UPDATE mod_discovery_rangeip SET ".$ping.", ".$tcp.", ".$snmp." WHERE id!=0;";
				}

				//Mise à jour de la base de données
				
				mysql_query($query);
			}
		}

		//Récupération des paramètres stockées dans la base de données

		$result=mysql_query("SELECT id, plage, cidr, ping, ping_count, ping_wait, tcp, tcp_port, snmp, snmp_version, snmp_community, oid_hostname, oid_os, snmp_method FROM mod_discovery_rangeip WHERE id!=0;");
		if(!$result){ //Si la requête renvoie une erreur, alors affichage du message d'erreur
			die("Invalid query: ".mysql_error());
		}
		while($row=mysql_fetch_array($result)){ //Stocke toute les lignes du résultat de la requête dans un tableau
			$subnet_array[]=$row;
		}

		//Identification de l'onglet sélectionné

		//Si le paramètre 'id' existe dans l'url, alors récupération de l'id de l'onglet sélectionné
		if(isset ($_GET["id"])){
			//Si le paramètre est vide, alors récupération de l'id du premier onglet
			if(empty($_GET["id"])){
				$id=$subnet_array[0]["id"];
			}
			//Sinon, récupération du paramètre comme id
			else{
				$id=$_GET["id"];
			}
		}
		//Sinon, récupération de l'id du premier onglet
		else{
			$id=$subnet_array[0]["id"];
		}
		?>
        <div>
            <ul id="mainnav">
            <?php
            //Pour chaque sous-réseau, création d'un onglet
            foreach($subnet_array as $row){
                /* Si l'id du sous-réseau est identique à l'id de l'onglet sélectionné, alors mettre l'onglet du sous-réseau
                 * comme sélectionné et stocker les paramètres de ce sous-réseau*/
                if($row["id"]==$id){
                    $row_temp=$row;
                    echo "<li class=\"active\"><a href=\"main.php?p=61202&id=".$row["id"]."\">".$row["plage"]."/".$row["cidr"]."</a></li>";
                }
                //Sinon, crée un onglet pour le sous-réseau
                else{
                    echo "<li class=\"a\"><a href=\"main.php?p=61202&id=".$row["id"]."\">".$row["plage"]."/".$row["cidr"]."</a></li>";
                }
            }
            ?>
            </ul>
        </div>
		<div id="tab1" class="tab">
			<table class="ListTable">
				<tr class="ListHeader">
					<td class="FormHeader">&nbsp;&nbsp;</td>
				</tr>
				<tr>
					<td align="center">
        <?php
        echo "<form action=\"main.php?p=61202&id=".$id."\" method=\"post\">";
        ?>
            <table>
                <tr>
                    <td>
                        <?php
                        /* Si le scan de type ping a été activé pour ce sous-réseau, alors création d'un checkbox sélectionné
                         * pour le ping*/
                        if($row_temp["ping"]==1){
                            echo "<input type=\"checkbox\" name=\"ping\" value=\"true\" checked=\"checked\" tabindex=\"1\" title=\"Check if you want to use ping for the scan\">";
                        }
                        //Sinon, création d'un checkbox pour le ping
                        else{
                            echo "<input type=\"checkbox\" name=\"ping\" value=\"true\" tabindex=\"1\" title=\"Check if you want to use ping for the scan\">";
                        }
                        ?>
                    </td>
                    <td>Ping</td>
                    <td align="right">Ping count:</td>
                    <td>
                        <?php
                        /* Si le nombre de tentative de ping n'a pas été renseigné, alors création d'un text box vide pour ce
                         * paramètre*/
                        if(empty($row_temp["ping_count"])){
                            echo "<input type=\"text\" name=\"count\" tabindex=\"2\" title=\"Enter the number of ICMP ECHO packets to send\">";
                        }
                        //Sinon, création d'un text box prérempli avec le nombre de tentative de ping
                        else{
                            echo "<input type=\"text\" name=\"count\" value=\"".$row_temp["ping_count"]."\" tabindex=\"2\" title=\"Enter the number of ICMP ECHO packets to send\">";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td align="right">Wait:</td>
                    <td>
                        <?php
                        //Si le timeout du ping n'a pas été renseigné, alors création d'un text box vide pour ce paramètre
                        if(empty($row_temp["ping_wait"])){
                            echo "<input type=\"text\" name=\"wait\" tabindex=\"3\" title=\"Enter the number of milliseconds to wait for response\">";
                        }
                        //Sinon, création d'un text box prérempli avec le timeout du ping
                        else{
                            echo "<input type=\"text\" value=\"".$row_temp["ping_wait"]."\" name=\"wait\" tabindex=\"3\" title=\"Enter the number of milliseconds to wait for response\">";
                        }
                        ?>
                    </td>
                </tr>


                <!--  PARTIE NON IMPLEMENTEE DANS LA PARTIE PERL AGENT SAD
                <tr>
                    <td> -->
                        <?php
                        /* Si le scan de type tcp a été activé pour ce sous-réseau, alors création d'un checkbox sélectionné
                         * pour le tcp*/
                        /*if($row_temp["tcp"]==1){
                            echo "<input type=\"checkbox\" name=\"tcp\" checked=\"checked\" value=\"true\" tabindex=\"4\" title=\"Check if you want to use TCP for the scan\">";
                        }
                        //Sinon, création d'un checkbox pour le tcp
                        else{
                            echo "<input type=\"checkbox\" name=\"tcp\" value=\"true\" tabindex=\"4\" title=\"Check if you want to use TCP for the scan\">";
                        }*/
                        ?>
                    <!--</td>
                    <td>TCP</td>
                    <td align="right">TCP port(s) to scan:</td>
                    <td>-->
                        <?php
                        /* Si le(s) port(s) pour le scan tcp n'a pas été renseigné, alors création d'un text box vide pour ce
                         * paramètre*/
                        /*if(empty($row_temp["tcp_port"])){
                            echo "<input type=\"text\" name=\"port\" tabindex=\"5\" title=\"Example: 21-25;80;445\">";
                        }
                        //Sinon, création d'un text box prérempli avec le(s) port(s) pour le scan tcp
                        else{
                            echo "<input type=\"text\" name=\"port\" value=\"".$row_temp["tcp_port"]."\" tabindex=\"5\" title=\"Example: 21-25;80;445\">";
                        }*/
                        ?>
                    <!--</td>
                </tr>-->
                <tr>
                    <td></td>
                    <td>SNMP</td>
                    <td align="right">Method:</td>
                    <td>
                        <select name="method" tabindex="7" title="Choose the SNMP method to use">
                            <?php
                            /* Si la méthode pour le scan SNMP n'a pas été renseignée ou que c'est la méthode Get, alors
                             * sélection de l'option Get du combo box de la méthode SNMP*/
                            if(empty($row_temp["snmp_method"])){
                                if($valuesData["snmp_method"]=="get"){
                                    echo "<option selected=\"selected\" value=\"get\">Get</option>";
                                    echo "<option value=\"walk\">Walk</option>";
                                }
                                else{
                                    echo "<option value=\"get\">Get</option>";
                                    echo "<option selected=\"selected\" value=\"walk\">Walk</option>";
                                }
                            }
                            elseif($row_temp["snmp_method"]=="get"){
                                echo "<option selected=\"selected\" value=\"get\">Get</option>";
                                echo "<option value=\"walk\">Walk</option>";
                            }
                            //Sinon, sélection de l'option Walk du combo box de la méthode SNMP
                            else{
                                echo "<option value=\"get\">Get</option>";
                                echo "<option selected=\"selected\" value=\"walk\">Walk</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td align="right">Hostname OID:</td>
                    <td>
                        <?php
                        //Si l'OID du hostname n'a pas été renseigné, alors création d'un text box vide pour ce paramètre
                        if(empty($row_temp["oid_hostname"])){
                            echo "<input type=\"text\" name=\"oid_hostname\" tabindex=\"8\" title=\"Exemple: .1.3.6.1.4.1.5518.1.5.47\">";
                        }
                        //Sinon, création d'un text box prérempli avec l'OID pour le scan SNMP
                        else{
                            echo "<input type=\"text\" name=\"oid_hostname\" value=\"".$row_temp["oid_hostname"]."\" tabindex=\"8\" title=\"Exemple: .1.3.6.1.4.1.5518.1.5.47\">";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td align="right">OS OID:</td>
                    <td>
                        <?php
                        //Si l'OID de l'OS n'a pas été renseigné, alors création d'un text box vide pour ce paramètre
                        if(empty($row_temp["oid_os"])){
                            echo "<input type=\"text\" name=\"oid_os\" tabindex=\"9\" title=\"Exemple: .1.3.6.1.4.1.5518.1.5.47\">";
                        }
                        //Sinon, création d'un text box prérempli avec l'OID pour le scan SNMP
                        else{
                            echo "<input type=\"text\" name=\"oid_os\" value=\"".$row_temp["oid_os"]."\" tabindex=\"9\" title=\"Exemple: .1.3.6.1.4.1.5518.1.5.47\">";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td align="right">Version:</td>
                    <td>
                        <select name="version" tabindex="10" title="Choose the SNMP version to use">
                            <?php
                            if(empty($row_temp["snmp_version"])){
                                if($valuesData["snmp_version"]==1){
                                    echo "<option selected=\"selected\" value=\"1\">v1</option>";
                                    echo "<option value=\"2\">v2c</option>";
                                }
                                else{
                                    echo "<option value=\"1\">v1</option>";
                                    echo "<option selected=\"selected\" value=\"2\">v2c</option>";
                                }
                            }
                            elseif($row_temp["snmp_version"]==2){
                                echo "<option value=\"1\">v1</option>";
                                echo "<option selected=\"selected\" value=\"2\">v2c</option>";
                            }
                            else{
                                echo "<option selected=\"selected\" value=\"1\">v1</option>";
                                echo "<option value=\"2\">v2c</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td align="right">Community:</td>
                    <td>
                        <?php
                        /* Si la communauté pour le scan SNMP n'a pas été renseigné, alors création d'un text box vide pour ce
                         * paramètre*/
                        if(empty($row_temp["snmp_community"])){
                            echo "<input type=\"text\" name=\"community\" tabindex=\"11\" title=\"Enter your SNMP community\">";
                        }
                        //Sinon, création d'un text box prérempli avec la communauté pour le scan SNMP
                        else{
                            echo "<input type=\"text\" name=\"community\" value=\"".$row_temp["snmp_community"]."\" tabindex=\"11\" title=\"Enter your SNMP community\">";
                        }
                        ?>
                    </td>
                </tr>
            </table>
            <input type="reset" value="Reset" tabindex="12" title="Reset the subnet scan configurations">
            <input type="submit" name="Submit" value="Save" tabindex="13" title="Save the subnet scan configurations">
            <input type="submit" value="Apply to all" name="Apply" tabindex="14" title="Apply the scan configurations to all subnets">
        <?php
        echo "</form>";
        ?>
					</td>
				</tr>
			</table>
		</div>
        <table width="100%">
            <td align="left">
                <?php
                echo "<input type=\"button\" value=\"<< Step 1\" onclick=\"self.location='./main.php?p=61201'\" tabindex=\"15\" title=\"Go back to the previous step\">";
                ?>
            </td>
            <td align="right">
                <?php
                echo "<input type=\"button\" value=\"Step 3 >>\" onclick=\"self.location='./main.php?p=61203'\" tabindex=\"16\" title=\"Go to the next step\">";
                ?>
            </td>
        </table>
    </body>
</html>
