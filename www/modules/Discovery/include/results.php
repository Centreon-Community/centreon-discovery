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
<link href="./modules/Discovery/css/discovery.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="./modules/Discovery/include/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="./modules/Discovery/include/JS-Func.js"></script>
<script type="text/javascript" src="./modules/Discovery/include/script.js"></script>
<script type="text/javascript">
	var hostsNameExist=new Array();
	var hostsIPExist=new Array();
	var hostsNew=new Array();
	var hostsNewOrig=new Array();
</script>
<?php
if (!isset ($oreon)) {
	exit ();
}
require_once'./modules/Discovery/include/DB-Func.php';
require_once "HTML/QuickForm.php";
require_once 'HTML/QuickForm/select.php';
require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
$form = new HTML_QuickForm('form_import','post','main.php?p=61202');

/* Variable indiquant la position de l'agent Python, elle est modifiée par le fichier install.sh lors de l'installation du module. */
$agentDir = "@AGENT_DIR@/DiscoveryAgent_central.py";
//$agentDir = "/usr/share/centreon-discovery/DiscoveryAgent_central.py";


$CONFIG=getConfig();
if (!empty($_POST)){
	setScanValues();
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
			Fonction qui récupère et classe les Hôtes et les Templates d'hôtes
		*/

?>
<table width="100%">
	<tr>
		<td width="30%"></td>
		<td width="40%"><h1 align="center">Discovery Results</h1></td>
		<td width="30%"><img align="right" alt="Centreon-Discovery" src="./modules/Discovery/include/images/logo.png"/><td>					
	</tr>
</table>
<table width="100%">
	<tr>
		<td width="30%">&nbsp;</td>
		<td width="40%"><div id="mon_div" style="font-weight:bold;"></div></td>
		<td width="30%">&nbsp;</td>
	</tr>
</table>
<form method="POST" action="main.php?p=61202" name="results">
<?php
	if(isset($_GET['delid'])){
		$delid=(int)$_GET['delid'];
		if(mysql_query("DELETE FROM mod_discovery_results WHERE id='".$delid."'")){
			echo 'Resultat Supprime';
		}
	}
	elseif(isset($_GET['delall'])){
		$delid=(int)$_GET['delall'];
		if(mysql_query("DELETE FROM mod_discovery_results WHERE plage_id='".$delid."'")){
			echo 'Resultats suprimes';
		}
	}
	//if (!empty($_POST) && isset($_POST['cb1'])){
	if (!empty($_POST)){
		//Création des hosts 
		function getListHost() {
			$listhost=Array();
			$req=mysql_query("SELECT count(*) FROM mod_discovery_rangeip WHERE id!=0 AND done!=1;");
			$nbPlage=mysql_fetch_array($req);
			for ($i=1;$i<=$nbPlage[0];$i++){
				$cbgroup_name='cb'.$i;
				if (isset($_POST["$cbgroup_name"]) && count($_POST["$cbgroup_name"])>0)
				{
					foreach ($_POST["$cbgroup_name"] as $key => $value){
						// Correction du bug lorsqu'on cocher la checkbox globale (bug #1637)
						if (($key>=0) && !(preg_match('#^([0-9]{1,3}\.){3}[0-9]{1,3}$#', $value))){
							$listHost[]=$value;
						}
					}
				}
			}
			return $listHost;
		}
		$hostList = getListHost();
		if ($hostList != null){
			foreach ($hostList as $host)
			{
				//On récupere les infos sur l'hote à créer (hostname, ip, template, group)
				$reqHostToCreate = mysql_query("SELECT * FROM mod_discovery_results WHERE id=".$host.";");
				$hostToCreate = mysql_fetch_array($reqHostToCreate,MYSQL_ASSOC);
				
				//On récupere des infos sur l'hotes à créer
				$reqHostInfos = mysql_query("SELECT snmp_community,snmp_version,snmp_v3login, snmp_v3level, snmp_v3authtype, snmp_v3authpass, snmp_v3privtype, snmp_v3privpass, nagios_server_id FROM mod_discovery_rangeip WHERE id=".$hostToCreate["plage_id"].";");
				$hostInfos = mysql_fetch_array($reqHostInfos,MYSQL_ASSOC);
				
				//On rempli la variable tmpConf qui sera envoyée à la fonction callInsertHostInDb()
				$tmpConf = array();
				$id=$hostToCreate["id"];
				$tmpConf["host"]["host_name"] = $_POST["hostname"][$id];
				$tmpConf["host"]["host_alias"] = $_POST["hostname"][$id];
				$tmpConf["host"]["host_address"] = $hostToCreate["ip"];
				$tmpConf["host"]["nagios_server_id"] = $hostInfos["nagios_server_id"];
				if($hostToCreate["snmp_community"]!='nocomm'){
					$tmpConf["host"]["host_snmp_community"] =  $hostToCreate["snmp_community"];
				}
				else {
					// Ajout macros
				}
				$tmpConf["host"]["host_snmp_version"] =  $hostInfos["snmp_version"];
				$tmpConf["host"]["host_register"]["host_register"] = "1";
				$tmpConf["host"]["host_activate"]["host_activate"] = "1";
				$tmpConf["host"]["host_template_model_htm_id"] = $_POST["select_template".$host];
				if ($_POST["select_group".$host]!= -1)
				{
					$tmpConf["host"]["host_hgs"] = array($_POST["select_group".$host]);
				}
				$tmpConf["host"]["dupSvTplAssoc"]["dupSvTplAssoc"] = "1";
				
				$tmpConf["macro"]["macroInput_0"] = 'HOSTSNMPV3_LOGIN';
				$tmpConf["macro"]["macroValue_0"] = $hostInfos["snmp_v3login"];				
				$tmpConf["macro"]["macroInput_1"] = 'HOSTSNMPV3_LEVEL';
				$tmpConf["macro"]["macroValue_1"] = $hostInfos["snmp_v3level"];				
				$tmpConf["macro"]["macroInput_2"] = 'HOSTSNMPV3_AUTHTYPE';
				$tmpConf["macro"]["macroValue_2"] = $hostInfos["snmp_v3authtype"];				
				$tmpConf["macro"]["macroInput_3"] = 'HOSTSNMPV3_AUTHPASS';
				$tmpConf["macro"]["macroValue_3"] = $hostInfos["snmp_v3authpass"];				
				$tmpConf["macro"]["macroInput_4"] = 'HOSTSNMPV3_PRIVTYPE';
				$tmpConf["macro"]["macroValue_4"] = $hostInfos["snmp_v3privtype"];				
				$tmpConf["macro"]["macroInput_5"] = 'HOSTSNMPV3_PRIVPASS';
				$tmpConf["macro"]["macroValue_5"] = $hostInfos["snmp_v3privpass"];
				$tmpConf["macro"]["nbOfMacro"] = 6;

				callInsertHostInDb($tmpConf);
			}
			if (count($hostList)>1){
				$hostMsgAlert = '<script>alert("'.count($hostList).' hosts were successfully created");</script>';
			} else {
				$hostMsgAlert = '<script>alert("The host has been created successfully");</script>';
			}
			echo $hostMsgAlert;
		}
	}
	
	function getTemplateHostsGroups(){
		global $Hosts_Templates;
		global $Hosts;
		global $HostGroup;
			echo '<script type="text/javascript">'."\n";
			$GetGroups_SQL=mysql_query("SELECT * FROM hostgroup")or die(mysql_error());
			$GetHosts_SQL=mysql_query("SELECT * FROM host ORDER BY host_id DESC")or die(mysql_error());
			while($Hosts_SQL=mysql_fetch_array($GetHosts_SQL,MYSQL_ASSOC)){
				if($Hosts_SQL["host_register"]=='0'){
					$Hosts_Templates[]=$Hosts_SQL;
				}
				else {
					$Hosts[]=$Hosts_SQL;
					echo 'hostsNameExist['.$Hosts_SQL["host_id"].']=\''.strtoupper($Hosts_SQL["host_name"]).'\';'."\n";
					echo 'hostsIPExist['.$Hosts_SQL["host_id"].']=\''.strtoupper($Hosts_SQL["host_address"]).'\';'."\n";
				}
			}
			echo '</script>';
			while($Groups_SQL=mysql_fetch_array($GetGroups_SQL,MYSQL_ASSOC)){
				$HostGroup[]=$Groups_SQL;
			}
		}
		
		
		function getIllegalChars(){
			global $IllegalChars;
				$GetIllegalChars_SQL=mysql_query("SELECT illegal_object_name_chars FROM cfg_nagios LIMIT 1") or die(mysql_error());
				$IllegalCharsArray=mysql_fetch_array($GetIllegalChars_SQL,MYSQL_ASSOC);
				$IllegalChars=html_entity_decode($IllegalCharsArray['illegal_object_name_chars']);
				$IllegalChars=str_replace('&#039;', "'", $IllegalChars);
				$IllegalChars=str_split($IllegalChars);
				$IllegalCharsjs=implode("', '", $IllegalChars);
				$IllegalCharsjsRegex='['.implode("\\", $IllegalChars).']';
				echo '<script type="text/javascript">'."\n";
					echo "var IllegalCharsRegex = new RegExp(/".$IllegalCharsjsRegex."+/);"."\n";
				echo '</script>'."\n";
		}
		
	getTemplateHostsGroups();
	getIllegalChars();
	?>
	<table align="right">
		<tr>
			<td>&nbsp;<p align="right"><input type="button" value=" << Back " onClick="self.location='./main.php?p=61201'">&nbsp;&nbsp;</p></td>
			<td>&nbsp;<p align="right"><input type="button" value=" Create Selected Hosts " onClick="verifselection(results)">&nbsp;&nbsp;</p></td>
		</tr>
	</table>
	<br /><br /><br />
	<?php
	$cbgroup = 0;
	$subnetsDone = mysql_query("SELECT count(plage_id) as cnt, disco.id,plage,masque,cidr,done,nagios_server_id FROM (mod_discovery_rangeip AS disco) LEFT JOIN mod_discovery_results AS res ON disco.id=res.plage_id WHERE disco.id!=0 AND disco.done!=0 GROUP BY disco.id") or die(mysql_error());
	while($subnetDoneData = mysql_fetch_array($subnetsDone,MYSQL_ASSOC)){
	
	$pollerSql = mysql_query("SELECT ns_ip_address, name FROM nagios_server WHERE id='".$subnetDoneData['nagios_server_id']."'")or die(mysql_error());
	$poller = mysql_fetch_array($pollerSql); // Informations du poller
	
	$netoctets=explode(".",$subnetDoneData["plage"]);
	$maskoctets=explode(".",$subnetDoneData["masque"]);
	if (isset($_GET['orderby'])){
		switch ($_GET['orderby']){
			case "ip_asc":
			$subnetHostsList = mysql_query("SELECT * FROM mod_discovery_results WHERE SUBSTRING_INDEX(`ip`, '.', 1)&".$maskoctets[0]." = ".$netoctets[0]." AND SUBSTRING_INDEX(SUBSTRING_INDEX(`ip`, '.',-3),'.',1)&".$maskoctets[1]." = ".$netoctets[1]." AND SUBSTRING_INDEX(SUBSTRING_INDEX(`ip`, '.',-2),'.',1)&".$maskoctets[2]." = ".$netoctets[2]." AND SUBSTRING_INDEX(`ip`,'.',-1)&".$maskoctets[3]." = ".$netoctets[3]." ORDER BY INET_ATON(ip) ASC ;");
			break;

			case "ip_desc":
			$subnetHostsList = mysql_query("SELECT * FROM mod_discovery_results WHERE SUBSTRING_INDEX(`ip`, '.', 1)&".$maskoctets[0]." = ".$netoctets[0]." AND SUBSTRING_INDEX(SUBSTRING_INDEX(`ip`, '.',-3),'.',1)&".$maskoctets[1]." = ".$netoctets[1]." AND SUBSTRING_INDEX(SUBSTRING_INDEX(`ip`, '.',-2),'.',1)&".$maskoctets[2]." = ".$netoctets[2]." AND SUBSTRING_INDEX(`ip`,'.',-1)&".$maskoctets[3]." = ".$netoctets[3]." ORDER BY INET_ATON(ip) DESC ;");
			break;
			
			case "hostname_asc":
			$subnetHostsList = mysql_query("SELECT * FROM mod_discovery_results WHERE SUBSTRING_INDEX(`ip`, '.', 1)&".$maskoctets[0]." = ".$netoctets[0]." AND SUBSTRING_INDEX(SUBSTRING_INDEX(`ip`, '.',-3),'.',1)&".$maskoctets[1]." = ".$netoctets[1]." AND SUBSTRING_INDEX(SUBSTRING_INDEX(`ip`, '.',-2),'.',1)&".$maskoctets[2]." = ".$netoctets[2]." AND SUBSTRING_INDEX(`ip`,'.',-1)&".$maskoctets[3]." = ".$netoctets[3]." ORDER BY hostname ASC ;");
			break;

			case "hostname_desc":
			$subnetHostsList = mysql_query("SELECT * FROM mod_discovery_results WHERE SUBSTRING_INDEX(`ip`, '.', 1)&".$maskoctets[0]." = ".$netoctets[0]." AND SUBSTRING_INDEX(SUBSTRING_INDEX(`ip`, '.',-3),'.',1)&".$maskoctets[1]." = ".$netoctets[1]." AND SUBSTRING_INDEX(SUBSTRING_INDEX(`ip`, '.',-2),'.',1)&".$maskoctets[2]." = ".$netoctets[2]." AND SUBSTRING_INDEX(`ip`,'.',-1)&".$maskoctets[3]." = ".$netoctets[3]." ORDER BY hostname DESC ;");
			break;
			
			case "os_asc":
			$subnetHostsList = mysql_query("SELECT * FROM mod_discovery_results WHERE SUBSTRING_INDEX(`ip`, '.', 1)&".$maskoctets[0]." = ".$netoctets[0]." AND SUBSTRING_INDEX(SUBSTRING_INDEX(`ip`, '.',-3),'.',1)&".$maskoctets[1]." = ".$netoctets[1]." AND SUBSTRING_INDEX(SUBSTRING_INDEX(`ip`, '.',-2),'.',1)&".$maskoctets[2]." = ".$netoctets[2]." AND SUBSTRING_INDEX(`ip`,'.',-1)&".$maskoctets[3]." = ".$netoctets[3]." ORDER BY os ASC ;");
			break;
			
			case "os_desc":
			$subnetHostsList = mysql_query("SELECT * FROM mod_discovery_results WHERE SUBSTRING_INDEX(`ip`, '.', 1)&".$maskoctets[0]." = ".$netoctets[0]." AND SUBSTRING_INDEX(SUBSTRING_INDEX(`ip`, '.',-3),'.',1)&".$maskoctets[1]." = ".$netoctets[1]." AND SUBSTRING_INDEX(SUBSTRING_INDEX(`ip`, '.',-2),'.',1)&".$maskoctets[2]." = ".$netoctets[2]." AND SUBSTRING_INDEX(`ip`,'.',-1)&".$maskoctets[3]." = ".$netoctets[3]." ORDER BY os DESC ;");
			break;
		}
	}
	else {
		$subnetHostsList = mysql_query("SELECT * FROM mod_discovery_results WHERE SUBSTRING_INDEX(`ip`, '.', 1)&".$maskoctets[0]." = ".$netoctets[0]." AND SUBSTRING_INDEX(SUBSTRING_INDEX(`ip`, '.',-3),'.',1)&".$maskoctets[1]." = ".$netoctets[1]." AND SUBSTRING_INDEX(SUBSTRING_INDEX(`ip`, '.',-2),'.',1)&".$maskoctets[2]." = ".$netoctets[2]." AND SUBSTRING_INDEX(`ip`,'.',-1)&".$maskoctets[3]." = ".$netoctets[3]." ORDER BY INET_ATON(ip) ASC ;");
	}
	if($subnetDoneData["done"]==2){ // Recherche terminée
			$cbgroup++;
	?>
	<font size="3px" style="font-weight:bold;"><?php echo $subnetDoneData["plage"].' / '.$subnetDoneData["cidr"].' polled by '.$poller["name"].' ('.$poller["ns_ip_address"].') | <a href="#" title="View addresses" onClick="$j(\'#scan'.$subnetDoneData["id"].'\').slideToggle(\'fast\');"><font color="green" size="2px"> '.$subnetDoneData["cnt"].' address(es) discovered</font></a><br>'; ?>
			<div id="scan<?php echo $subnetDoneData["id"]; ?>">
				<br><br><table class="ListTable">
				<tr class="ListHeader">
					<td width="5%" class="ListColHeaderCenter"><input type="checkbox" name="cb<?php echo $cbgroup; ?>[]" value="<?php echo $subnetDoneData["plage"]; ?>" OnClick="checkall(document.getElementsByName('cb<?php echo $cbgroup; ?>[]'));"/></td>
						<input type="hidden" name="group_list[]" value="'. $cbgroup .'" />
						<td width="7%" class="ListColHeaderCenter"><a href="./main.php?p=61202&orderby=ip_asc"><img src="./img/icones/7x7/sort_asc.gif" style="position:relative;bottom:5px;left:-3px;"></a>Host<a href="./main.php?p=61202&orderby=ip_desc"><img src="./img/icones/7x7/sort_desc.gif" style="position:relative;bottom:5px;left:3px;"></a></td>
						<td width="15%" class="ListColHeaderCenter"><a href="./main.php?p=61202&orderby=hostname_asc"><img src="./img/icones/7x7/sort_asc.gif" style="position:relative;bottom:5px;left:-3px;"></a>Hostname<a href="./main.php?p=61202&orderby=hostname_desc"><img src="./img/icones/7x7/sort_desc.gif" style="position:relative;bottom:5px;left:3px;"></a></td>
						<td width="5%" class="ListColHeaderCenter">Communaut&eacute;</td>
						<td width="15%" class="ListColHeaderCenter"><a href="./main.php?p=61202&orderby=os_asc"><img src="./img/icones/7x7/sort_asc.gif" style="position:relative;bottom:5px;left:-3px;"></a>Operating System<a href="./main.php?p=61202&orderby=os_desc"><img src="./img/icones/7x7/sort_desc.gif" style="position:relative;bottom:5px;left:3px;"></a></td>
						<td width="15%" class="ListColHeaderCenter">Host Template</td>
						<td width="15%" class="ListColHeaderCenter">Host Group</td>
						<td width="8%" class="ListColHeaderCenter">Status</td>
						<td width="5%" class="ListColHeaderCenter"><a href="#">
						<img style="border:none" type="image" src="./modules/Discovery/include/images/clearAll1.png" title="Delete all from list" onMouseOver="javascript:this.src='./modules/Discovery/include/images/clearAll2.png';" onMouseOut="javascript:this.src='./modules/Discovery/include/images/clearAll1.png';" onClick="self.location='./main.php?p=61202&delall=<?php echo $subnetDoneData["id"]; ?>'" /></a></td>
						</tr>
				<?php
				$list1="list_one";
				$list2="list_two";
				$listcnt=1;
				while($subnetHostData=mysql_fetch_array($subnetHostsList,MYSQL_ASSOC)){
					if($CONFIG['consider_fqdn']==1){
						$subnetHostData["hostname"]=explode('.', $subnetHostData["hostname"]);
						$subnetHostData["hostname"]=$subnetHostData["hostname"][0];
						$subnetHostData["hostname"]=str_replace('"', '', $subnetHostData["hostname"]);
					}
					if($listcnt%2==0){
						$list=$list2;
					}
					else {
						$list=$list1;
					}
				?>
					<script type="text/javascript">
						hostsNew[<?php echo $subnetHostData["id"]; ?>]='<?php echo $subnetHostData["hostname"]; ?>';
						hostsNewOrig[<?php echo $subnetHostData["id"]; ?>]='<?php echo $subnetHostData["hostname"]; ?>';
					</script>
				<?php
						echo '            	<tr class="'.$list.'">'," \n";
						echo '                  <td width="5%" class="ListColCenter"><input type="checkbox" name="cb'.$cbgroup.'[]" value="'.$subnetHostData["id"].'"/></td>';
						echo '                  <td width="7%" class="ListColCenter" name="ip'.$subnetHostData["id"].'">'.$subnetHostData["ip"].'</td>';
						echo '                  <td width="15%" class="ListColCenter"><input type="text" name="hostname['.$subnetHostData["id"].']" value="'.htmlspecialchars($subnetHostData["hostname"]).'" onKeyUp="updateNameBox(\''.$subnetHostData["id"].'\');" />&nbsp;<a href="#"><img src="./modules/Discovery/include/images/undo_16.png" title="Rollback to detected hostname" onClick="Hostname_rollback(\''.$subnetHostData["id"].'\');" /></a></td>';
						echo '                  <td width="5%" class="ListColCenter">'.$subnetHostData["snmp_community"].'</td>';
						echo '                  <td width="15%" class="ListColCenter" title="'.$subnetHostData["os"].'">'.strCut($subnetHostData["os"]).'</td>';
						echo '					<td width="15%" class="ListColCenter"><select name="select_template'.$subnetHostData["id"].'" width="95%"><option value="-1">None</option>';
						$default = findOs($subnetHostData["os"]);
						foreach($Hosts_Templates as $Hosts_TmPL){
						?>
							<option value="<?php echo $Hosts_TmPL["host_id"]; ?>" <?php echo ($default == $Hosts_TmPL["host_name"])? 'selected':''; ?> ><?php echo $Hosts_TmPL["host_name"]; ?></option>
						<?php
						}
						echo '					</select></td>';
						echo '					<td width="15%" class="ListColCenter"><select name="select_group'.$subnetHostData["id"].'" width="95%"><option value="-1">None</option>';
						foreach($HostGroup as $Host_Group){
							echo '<option value="'.$Host_Group["hg_id"].'">'.$Host_Group["hg_name"].'</option>';
						}					
						echo 					'</select></td>';
						echo '					<td width="8%" class="ListColCenter" name="exist'.$subnetHostData["id"].'" style="font-weight:bold;">';
						echo '</td>';
						echo ' 					<td width="5%" class="ListColCenter"><a href="#"><img style="border:none" type="image" src="./modules/Discovery/include/images/delete16x16.png" title="Delete one from list" name="ClearRow" onClick="self.location=\'./main.php?p=61202&delid='.$subnetHostData["id"].'\'"></a></td>';
						echo '				</tr>';
				$listcnt++;
				}
			?>
		</table>
		</div>
	</form>
	<br />
		<?php
		}
		elseif ($subnetDoneData["done"]==1){ // Recherche en cours
				echo '<script type="text/javascript">refresh_div();</script>';
			echo '          <br><font size="3px" style="font-weight:bold;">'.$subnetDoneData["plage"]." /".$subnetDoneData["cidr"]." polled by ".$poller["name"]." (".$poller["ns_ip_address"].")<br><br> \n ";
			echo '          <table class="ListTable">';
			echo '             <tr class="ListHeader">';
			echo '                  <td width="10%" class="ListColHeaderCenter">Host</td>';
			echo '                  <td width="15%" class="ListColHeaderCenter">Hostname</td>';
			echo '                  <td width="15%" class="ListColHeaderCenter">Operating System</td>';
			echo '                  <td width="15%" class="ListColHeaderCenter">Host Template</td>';
			echo '					<td width="15%" class="ListColHeaderCenter">Host Group</td>';
			echo '					<td width="8%" class="ListColHeaderCenter">Status</td>';
			echo '              </tr>';
			echo '             <tr class="list_one">';
			echo '                  <td width="22%" colspan="6" class="ListColCenter"><img style="border:none" type="image" src="./modules/Discovery/include/images/loading2.gif" title="Loading..."></td>';
			echo '              </tr>';
			echo '          </table>';
			echo '          <br>';
		}
		elseif ($subnetDoneData["done"]==3) { // Recherche terminée -> No result
			echo '          <br><font size="3px" style="font-weight:bold;">'.$subnetDoneData["plage"].' /'.$subnetDoneData["cidr"].' polled by '.$poller["name"].' ('.$poller["ns_ip_address"].') | <a href="#" title="View addresses" onClick="$j(\'#scan'.$subnetDoneData["id"].'\').slideToggle(\'fast\');"><font color="red" size="2px"> ERROR : Connection lost with the poller agent</font></a><br>';
			echo '			<div id="scan'.$subnetDoneData["id"].'">';
			echo '          <br><br><table class="ListTable">';
			echo '             <tr class="ListHeader">';
			echo '                  <td width="22%" class="ListColHeaderCenter">Host</td>';
			echo '                  <td width="22%" class="ListColHeaderCenter">Hostname</td>';
			echo '                  <td width="22%" class="ListColHeaderCenter">Operating System</td>';
			echo '                  <td width="22%" class="ListColHeaderCenter">Host Template</td>';
			echo '              </tr>';
			echo '             <tr class="list_one">';
			echo '                  <td width="22%" colspan="4" class="ListColCenter"> No result </td>';
			echo '              </tr>';
			echo '          </table>';
			echo '          <br>';
			echo '			</div>';
		}  	
	}
?>
	<table align="right">
		<tr>
			<td>&nbsp;<p align="right"><input type="button" value=" << Back " onClick="self.location='./main.php?p=61201'">&nbsp;&nbsp;</p></td>
			<td>&nbsp;<p align="right"><input type="button" value=" Create Selected Hosts " onClick="verifselection(results)">&nbsp;&nbsp;</p></td>
		</tr>
	</table>
	<br />
<?php

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
				
				function setScanValues(){
					global $agentDir;
					
					$req=mysql_query("SELECT count(*) FROM mod_discovery_rangeip WHERE id!=0;");
                    $nbPlage=mysql_fetch_array($req);
					for ($i=0;$i<$nbPlage[0];$i++){
						if ((isset($_POST["RangeToScan".$i])) && ($_POST["RangeToScan".$i] == 'true')){
							/* NMAP */
							$upd_SQL="UPDATE mod_discovery_rangeip SET ";
							if (isset($_POST["nmap_retries".$i]) && is_int(intval($_POST["nmap_retries".$i])) && $_POST["nmap_retries".$i]>=0 && $_POST["nmap_retries".$i]<100 && isset($_POST["nmap_timeout_rtt".$i]) && is_int(intval($_POST["nmap_timeout_rtt".$i])) && $_POST["nmap_timeout_rtt".$i]>=100 && $_POST["nmap_timeout_rtt".$i]<10000 && isset($_POST["profil_nmap".$i]) && isset($_POST["nmap_timeout".$i]) && is_int(intval($_POST["nmap_timeout".$i])) && $_POST["nmap_timeout".$i]>=15000 && $_POST["nmap_timeout".$i]<100000){
								$upd_SQL.="nmap_max_retries='".$_POST["nmap_retries".$i]."', nmap_profil='".$_POST["profil_nmap".$i]."', nmap_host_timeout='".$_POST["nmap_timeout".$i]."', nmap_max_rtt_timeout='".$_POST["nmap_timeout_rtt".$i]."', ";
							}
							/* SNMP */
							if (isset($_POST["snmp_community".$i]) && !strpos($_POST["snmp_community".$i]," ") && !empty($_POST["snmp_community".$i]) && isset($_POST["timeout".$i]) && is_int(intval($_POST["timeout".$i])) && $_POST["timeout".$i]>0 && $_POST["timeout".$i]<100 && isset($_POST["retries".$i]) && is_int(intval($_POST["retries".$i])) && $_POST["retries".$i]>=0 && $_POST["retries".$i]<100 && isset($_POST["port".$i]) && is_int(intval($_POST["port".$i])) && $_POST["port".$i]>0 && $_POST["port".$i]<65536 && isset($_POST["snmp_version".$i])){
								$upd_SQL.="snmp_community='".$_POST["snmp_community".$i]."', snmp_timeout='".$_POST["timeout".$i]."', snmp_retries='".$_POST["retries".$i]."', snmp_version='".$_POST["snmp_version".$i]."', snmp_port='".$_POST["port".$i]."', snmp_v3login='".$_POST["snmp_v3login".$i]."', snmp_v3level='".$_POST["snmp_v3level".$i]."', snmp_v3authtype='".$_POST["snmp_v3authtype".$i]."', snmp_v3authpass='".$_POST["snmp_v3authpass".$i]."', snmp_v3privtype='".$_POST["snmp_v3privtype".$i]."', snmp_v3privpass='".$_POST["snmp_v3privpass".$i]."', ";
							}
							/* OID */
							if (isset($_POST["oid_os".$i]) && !empty($_POST["oid_os".$i]) && ereg("^(\.([1-9][0-9]+|[0-9]))+$", $_POST["oid_os".$i]) && isset($_POST["oid_hostname".$i]) && !empty($_POST["oid_hostname".$i]) && ereg("^(\.([1-9][0-9]+|[0-9]))+$", $_POST["oid_hostname".$i])){
								$upd_SQL.="oid_os='".$_POST["oid_os".$i]."', oid_hostname='".$_POST["oid_hostname".$i]."', ";
							}
							//Mise à jour du champs done à 1 pour dire qu'il faut le scanner
							$upd_SQL.="done=1 ";
							$upd_SQL.="WHERE id='".$_POST["id".$i]."';";
							mysql_query($upd_SQL)or die(mysql_error());
							//Vider la table mod_discovery_results
							mysql_query("DELETE FROM mod_discovery_results;");
						}else{
							if(isset($_POST["id".$i])){
								mysql_query("UPDATE mod_discovery_rangeip SET done=0 WHERE id='".$_POST["id".$i]."'");
							}
						}
					}
					if (file_exists($agentDir)) {
						shell_exec('python '.$agentDir.' SCAN_RANGEIP > /dev/null 2>&1 &');
					}else { echo "<CENTER><b><font size=\"3px\" color=\"red\">ERROR</b> : File $agentDir not found...</font></CENTER>\n"; }
				}
	?>
	<br /><br />
	
	<script type="text/javascript">
		updateNameBox('ALL');
	</script>