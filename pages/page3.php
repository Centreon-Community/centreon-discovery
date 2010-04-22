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
	// * Page 3 : Host sélectionné - Résumé des éléments supervisés *
	// **************************************************************************
	
		// Requetes SQL - Récupération des Hosts
		$reshost = mysql_query("SELECT host_id,host_name,host_address,host_snmp_community FROM host WHERE host_address IS NOT NULL AND host_register = '1'");
			
	
		$type = $_POST['type'];
		$id = $_POST['id'];

		echo '<FORM NAME="FORM3" METHOD=POST ACTION="#">'," \n ";
		// Host Sélectionné
		echo '  <table class="ListTable">'," \n ";
		echo '	<tr class="ListHeader">'," \n ";
		echo '		<td class="ListColHeaderCenter">Host</td>'," \n ";
		echo '		<td class="ListColHeaderCenter">Community</td>'," \n ";
		echo '		<td class="ListColHeaderCenter">IP Address</td>'," \n ";
		echo '		<td class="ListColHeaderCenter">Type</td>'," \n ";
		echo '	</tr>'," \n ";

		// Traitements des données
		while ( $datahost = mysql_fetch_assoc($reshost)  ){
			
			// Récupération de l'ID de l'host
			$host_id =  $datahost['host_id'];
				
			// Si l'host est celui sélectionné ( radiobox checked )
			if ( $id == $host_id ) {
				// Récupération des informations de l'host
				$host_name = $datahost['host_name'];
				$host_address = $datahost['host_address'];
				$host_snmp_community =  $datahost['host_snmp_community'];
				
				// Mise en forme des informations
				echo '<tr class="list_one">';
				echo '  <td class="ListColCenter">' . $host_name . '<INPUT TYPE=HIDDEN NAME="host_name" VALUE="'. $host_name .'"/><INPUT TYPE=HIDDEN NAME="host_id" VALUE="'. $host_id .'"/></td>'," \n ";
				echo '  <td class="ListColCenter">' . $host_snmp_community . '</td>'," \n ";
				echo '  <td class="ListColCenter">' . $host_address . '</td>'," \n ";
				echo '  <td class="ListColCenter">' . $type . '<INPUT TYPE=HIDDEN NAME="type" VALUE="'. $type .'"/></td>'," \n ";
				echo '</tr>'," \n ";
			}
		}	

		echo '</table>'," \n ";
		echo '<br>'," \n ";
		echo '<br>'," \n ";
		echo '<p align="center">'," \n ";
		echo '	<span style="font-family: Candara; font-size: 1.5em; font-weight: bold; color: black; font-style: italic;">'," \n ";
		echo '		<big>R&eacute;sum&eacute; des &eacute;l&eacute;ments &agrave; superviser</big>'," \n ";
		echo '	</span>'," \n ";
		echo '</p>'," \n ";
		echo '<br>'," \n ";
		echo '<br>'," \n ";

		// *****************************************************************
		//							RESUME
		// *****************************************************************
			$group_number = $_POST["group_number"];				
			$group_description = $_POST["group_description"];
			$elt_name = $_POST["elt_name"];
			$services = $_POST["services"];

			$group_checked_number = 0;
			for($i=0;$i<sizeof($group_description);$i++) {
			// Pour chaque groupe
				$test_groupe_checked = 1;
				$group_path = substr(strrchr($group_description[$i],'|'),1);
				$group_name = substr($group_description[$i],0,strrpos($group_description[$i],'|'));				
				
				for($g=1;$g<=$group_number;$g++) {
					$eltschecked = $_POST["cb$g"];					
					for($g1=0;$g1<sizeof($eltschecked);$g1++) {				
						if ( $eltschecked[0] == $group_path ) { 
							$test_groupe_checked = 0;						
							break 2;
						}
					}
				}				
				
				if ( $test_groupe_checked == 0 ) {
				// Si le groupe a été sélectionné 
					echo '<table class="ListTable">'," \n ";
					/* PARAM */ echo '<INPUT TYPE=HIDDEN NAME="group_description['.$group_checked_number.']" VALUE="'. $group_name .'"/>'," \n ";																													
					echo '	<tr class="ListHeader">'," \n ";
					echo '		<td class="ListColHeaderCenter" width=30px><INPUT TYPE=CHECKBOX disabled="disabled" CHECKED /></td>'," \n ";
					echo '		<td class="ListColHeader"><big>'. $group_name .'</big></td>'," \n ";
					echo '	</tr>'," \n ";

					$elt_checked_number = 0;
					for($j=0;$j<sizeof($elt_name[$i]);$j++) {
					// Pour chaque élement de ce groupe
						$test_element_checked = 1;
						$eltschecked = $_POST["cb$g"];		
				
						for($e=1;$e<sizeof($eltschecked);$e++) {
							$element_name = substr($eltschecked[$e],0,strpos($eltschecked[$e],'|'));
							if ( $elt_name[$i][$j] == $element_name ) { 
								$test_element_checked = 0;
								break 1;
							}
						}




						if ( $test_element_checked == 0 ) {
						// Si l'élément a été sélectionné
						
							echo '	<tr class="list_one">'," \n ";
							echo '  	<td class="ListColCenter"></td>'," \n ";
							echo ' 		<td class="ListCol">'," \n ";
							echo '			<table WIDTH=100% BORDER="0" ALIGN="LEFT" VALIGN="MIDDLE" CELLPADDING="0" CELLSPACING="0">'," \n ";
							echo '				<tr>'," \n ";
							echo '  				<td WIDTH=30px ALIGN="CENTER">'," \n ";
							echo '						<img style="border: 0px solid; width: 15px;" alt="-" src="./modules/CentreonDiscovery/pictures/double_puce.png">'," \n ";
							echo ' 					</td>'," \n ";
							echo '  				<td><b>' . $element_name .' :</b></td>'," \n ";								
							/* PARAM */ echo '<INPUT TYPE=HIDDEN NAME="elt_name['.$group_checked_number.']['.$elt_checked_number.']" VALUE="'. $element_name .'"/>'," \n ";
						
							$service_checked_number = 0;
							for($k=0;$k<sizeof($services[$i][$j]);$k++) {
							// Pour chaque service de cet élément
								$test_service_checked = 1;
								$eltschecked = $_POST["cb$g"];	
								
								for($s=1;$s<sizeof($eltschecked);$s++) {
									$service_alias = substr(strrchr($eltschecked[$s],'|'),1);
									$service_id = substr($eltschecked[$s],(strpos($eltschecked[$e],'|')+1),strrpos($eltschecked[$s],'|') - strpos($eltschecked[$e],'|') - 1);
									
									if ( $service_id == $services[$i][$j][$k] ) { 
										$test_service_checked = 0;
										break 1;
									}
								}
								
								if ( $test_service_checked == 0 ) {
								// Si le service a été sélectionné							
									/* PARAM */ echo '<INPUT TYPE=HIDDEN NAME="services['.$group_checked_number.']['.$elt_checked_number.']['.$service_checked_number.']" VALUE="'. $service_id .'|'.$service_alias.'"/>'," \n ";								
									echo '				<tr>'," \n ";
									echo '					<td class="ListColHeaderCenter" width=30px><INPUT TYPE=CHECKBOX disabled="disabled" CHECKED /></td>'," \n ";
									echo '					<td>'.$service_alias.'</td>'," \n ";
									echo '				</tr>'," \n ";
									
									$service_checked_number++;
								}
							}
							
							echo '				<tr HEIGHT="10px";></tr>'," \n ";
							echo '			</table>'," \n ";
							echo '		</td>'," \n ";
							echo '	</tr>'," \n ";
							
							$elt_checked_number++;
						}
					}
					echo '</table>'," \n ";
					echo '<br>'," \n ";
					$group_checked_number++;
				}
			}
		
		
		
		
		

		echo '<br>'," \n ";
		echo '<p align="right"><INPUT TYPE=SUBMIT VALUE="Configurer" NAME="send3"/></p>'," \n ";
		echo '</FORM>'," \n ";
?>