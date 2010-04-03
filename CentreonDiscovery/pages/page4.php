<?
/*  This file is part of CentreonDiscovery.
 *
 *	 CentreonDiscovery is developped with GPL Licence 3.0 :
 *	 Developped by : Jonathan Teste - Cedric Dupre
 *   
 *   CentreonDiscovery is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License.
 *   
 *   CentreonDiscovery is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with CentreonDiscovery.  If not, see <http://www.gnu.org/licenses/>.
 */
 
 	// **************************************************************************
	// * Page 4 : Application de la configuration *
	// **************************************************************************

	$host_id = $_POST["host_id"];
	$host_name = $_POST["host_name"];
	$group_description = $_POST["group_description"];
	$elt_name = $_POST["elt_name"];
	$services = $_POST["services"];

	echo '<br><br>'," \n ";
	echo '	<span style="font-family: Candara; font-weight: bold; font-size: 24px; color: black; font-style: italic;">'," \n ";
	echo '    <center>'.$host_name.'</center>'," \n ";
	echo '	</span>'," \n ";
	echo '<br><br>'," \n ";
	
	
	
	for($i=0;$i<sizeof($group_description);$i++) {
	
	
		echo '<table class="ListTable">'," \n ";																											
		echo '	<tr class="ListHeader">'," \n ";
		echo '		<td class="ListColHeaderCenter" width=30px><INPUT TYPE=CHECKBOX disabled="disabled" CHECKED /></td>'," \n ";
		echo '		<td class="ListColHeader"><big>'. $group_description[$i] .'</big></td>'," \n ";
		echo '	</tr>'," \n ";
	
	
		for($j=0;$j<sizeof($elt_name[$i]);$j++) {
		
		
			echo '	<tr class="list_one">'," \n ";
			echo '  	<td class="ListColCenter"></td>'," \n ";
			echo ' 		<td class="ListCol">'," \n ";
			echo '			<table WIDTH=100% BORDER="0" ALIGN="LEFT" VALIGN="MIDDLE" CELLPADDING="0" CELLSPACING="0">'," \n ";
			echo '				<tr>'," \n ";
			echo '  				<td WIDTH=30px ALIGN="CENTER">'," \n ";
			echo '						<img style="border: 0px solid; width: 15px;" alt="-" src="./modules/CentreonDiscovery/pictures/double_puce.png">'," \n ";
			echo ' 					</td>'," \n ";
			echo '  				<td><b>' . $elt_name[$i][$j] .' :</b></td>'," \n ";

		
			for($k=0;$k<sizeof($services[$i][$j]);$k++) {
				/* TEST */ //echo '<br><p>group_description = '.$group_description[$i].'</p>';
				/* TEST */ //echo '<p>elt_name = '.$elt_name[$i][$j].'</p>';
				/* TEST */ //echo '<p>services = '.$services[$i][$j][$k].'</p><br>';
				
				$service_alias = substr(strrchr($services[$i][$j][$k], "|"), 1);
				$service_id = substr($services[$i][$j][$k],0,strpos($services[$i][$j][$k], "|"));
				$insert = InsertionRelationServiceBDD($host_id,$service_id,$elt_name[$i][$j],$group_description[$i]);
				
				echo '				<tr>'," \n ";
				if ( $insert[0] == 0 ) {
					echo '					<td class="ListColHeaderCenter" width=30px><img style="border: 0px solid; width: 15px;" alt="-" src="./modules/CentreonDiscovery/pictures/true.png"></td>'," \n ";
					echo '					<td>'.$service_alias.' : ce service est d&eacute;sormais configur&eacute; !</td>'," \n ";
				}
				else {
					echo '					<td class="ListColHeaderCenter" width=30px><img style="border: 0px solid; width: 15px;" alt="-" src="./modules/CentreonDiscovery/pictures/false.png"></td>'," \n ";
					echo '					<td>'.$service_alias.' : ce service existe d&eacute;j&agrave; !</td>'," \n ";
				}
				echo '				</tr>'," \n ";
				echo '				<tr HEIGHT="10px";></tr>'," \n ";
				echo '			</table>'," \n ";
				echo '		</td>'," \n ";
				echo '	</tr>'," \n ";
			}			
		}
		echo '</table>'," \n ";
		echo '<br>'," \n ";
	}
	echo '<br><br>'," \n ";
	echo '<p>'," \n ";
	echo '	<span style="font-family: Candara; font-weight: bold; color: red; font-style: italic;">'," \n ";
	echo '  	<center><big>Votre configuration est d&eacute;sormais appliqu&eacute;e...</center></big>'," \n ";
	echo '		<br>'," \n ";
	echo '  	<center>Afin de l\'activer, pensez &agrave; l\'exporter au sein de nagios</center>'," \n ";
	echo '		<br>'," \n ";
	echo '	</span>'," \n ";
	echo '	<center><img style="border: 0px solid ; width: 600px;" alt="Centreon" src="./modules/CentreonDiscovery/pictures/screenshot_application_config_nagios.png" align="middle"></center>'," \n ";
	echo '</p>'," \n ";
	echo '<br><br>'," \n ";
?>