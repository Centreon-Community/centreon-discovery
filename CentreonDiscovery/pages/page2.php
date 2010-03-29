<?
	// ***************************************************************************************************
	// * Page 2 : Rappel de l'host sélectionné - Enumération des éléments supervisables de l'host *
	// ***************************************************************************************************
	
		// Requetes SQL - Récupération des Hosts
		$reshost = mysql_query("SELECT host_id,host_name,host_address,host_snmp_community FROM host WHERE host_address IS NOT NULL AND host_register = '1'");
		

		// Récupération de la selection de la radiobox
		$selected_radio = $_POST['choice'];						

		// Mise en forme des informations
		echo '<FORM NAME="FORM2" METHOD=POST ACTION="#">'," \n ";
		echo '	<table class="ListTable">'," \n ";
		echo '		<tr class="ListHeader">'," \n ";
		echo '			<td class="ListColHeaderCenter">Host</td>'," \n ";
		echo '			<td class="ListColHeaderCenter">Community</td>'," \n ";
		echo '			<td class="ListColHeaderCenter">IP Address</td>'," \n ";
		echo '			<td class="ListColHeaderCenter">Type</td>'," \n ";
		echo '		</tr>'," \n ";

		// Traitements des données
		$host_type = 'Nothing';
		while ( $datahost = mysql_fetch_assoc($reshost)  ){
			
			// Récupération de l'ID de l'host
			$host_id =  $datahost['host_id'];
				
			// Si l'host est celui sélectionné ( radiobox checked )
					

			if ( $selected_radio == $host_id ) {
				// Récupération des informations de l'host
				$host_type = $_POST['select'.$host_id];
				$host_name = $datahost['host_name'];
				$host_address = $datahost['host_address'];
				$host_snmp_community =  $datahost['host_snmp_community'];
				
				if ( $host_snmp_community == NULL ) {
					$host_src = $host_id;				
					while ( $host_snmp_community == NULL ) {						
						$reshost_tpl = mysql_query("SELECT DISTINCT host_tpl_id FROM host AS HOST INNER JOIN host_template_relation AS TEMPLATE ON TEMPLATE.host_host_id = HOST.host_id WHERE HOST.host_id = ". $host_src );
						if ( mysql_num_rows($reshost_tpl) == 0 ) { break 1; }
						while ( $datahost_tpl = mysql_fetch_assoc($reshost_tpl) ) {
							$host_src =  $datahost_tpl['host_tpl_id'];
						}
						$reshost_tpl = mysql_query('SELECT host_snmp_community FROM host WHERE host_id = '.$host_src);
						while ( $datahost_tpl = mysql_fetch_assoc($reshost_tpl) ) {
							$host_snmp_community =  $datahost_tpl['host_snmp_community'];
						}
					}
				}
						
				// Mise en forme des informations
				echo '<tr class="list_one">'," \n ";
				echo '  <td class="ListColCenter">' . $host_name . '<INPUT TYPE=HIDDEN NAME="id" VALUE="'. $host_id .'"/></td>'," \n ";
				echo '  <td class="ListColCenter">' . $host_snmp_community . '</td>'," \n ";
				echo '  <td class="ListColCenter">' . $host_address . '</td>'," \n ";
				echo '  <td class="ListColCenter">' . $host_type . '<INPUT TYPE=HIDDEN NAME="type" VALUE="'. $host_type .'"/></td>'," \n ";
				echo '</tr>'," \n ";
			}
		}	

		echo '</table>'," \n ";
		echo '<br>'," \n ";
		echo '<br>'," \n ";

		// Traitement de l'information $host_type
		if ( $host_type == 'Nothing' ) { 
		// Si le type de l'host n'est pas sélectionné
			echo '<p>'," \n ";
			echo '<span style="font-family: Candara; font-weight: bold; color: red; font-style: italic;">'," \n ";
			echo '  <center><big>Veuillez s&eacute;lectionner un type pour l\'host &agrave; superviser" !</center></big>'," \n ";
			echo '</span>'," \n ";
			echo '</p>'," \n ";
			echo '<br><br>'," \n ";
		}
		else {
			// Requetes SQL - Récupération des OID				
			$resoid = mysql_query("SELECT OIDGroup_id,OIDGroup_name,OIDGroup_path FROM CDType INNER JOIN OIDGroup ON CDType_id = OIDGroup_device WHERE CDType_type = '".$host_type."' ORDER BY OIDGroup_path");
			
			$cbgroup = 0;
			$elt_number = 0;
			$group_number = 0;
			while ( $dataoid = mysql_fetch_assoc($resoid)  ){
			// Pour chaque groupe
				$cbgroup++;	
				// Récupération des informations de l'host
				$oid_groupid = $dataoid['OIDGroup_id'];
				$oid_path =  $dataoid['OIDGroup_path'];
				$oid_desc = $dataoid['OIDGroup_name'];
		
				$resoidgroup = mysql_query("SELECT ServiceOID_id FROM ServiceOID WHERE OIDGroup_id_id = ".$oid_groupid);
				$num_row = mysql_num_rows($resoidgroup);
				
			
				
				if ( $num_row > 0 ) {
				// S'il existe des services supervisables
				
					/* GROUPE */ echo '<INPUT TYPE=HIDDEN NAME="group_description['.$group_number.']" VALUE="'. $oid_desc .'|'. $oid_path .'"/>'," \n ";			

					$retour = HostConnect($host_snmp_community,$host_address,$oid_path);
					$sizeretour = sizeof($retour);
					
					if ( $oid_desc == 'CPU' || $oid_desc == 'Memoire' ) {
						if ( $sizeretour > 0 ) { $sizeretour = 2; }
						else { $sizeretour = 0; }
					}
					
					if ( $sizeretour > 0 ) {
						echo '<br>'," \n ";
						echo '<table class="ListTable">'," \n ";
						echo '	<tr class="ListHeader">'," \n ";
						echo '		<td class="ListColHeaderCenter" width=30px><INPUT TYPE=CHECKBOX NAME="cb'.$cbgroup.'[]" VALUE="'. $oid_path .'" OnClick="checkall(document.getElementsByName(\'cb'.$cbgroup.'[]\'));"  CHECKED /></td>'," \n ";
						echo '		<td class="ListColHeader"><big>' . $oid_desc . '</big></td>'," \n ";
						echo '	</tr>'," \n ";					
					}
					
					$processus[] = '';
					for($i=1; $i<$sizeretour; $i++) {
					// Pour chaque éléments supervisables
						$elt_number++;
						$index = substr($retour[($i-1)],1);
						$elt_path_name = substr($oid_path,0,strlen($oid_path)-1)."2.".$index;
						$eltname = HostConnect($host_snmp_community,$host_address,$elt_path_name);
						
						if ( $oid_desc == 'CPU' || $oid_desc == 'Memoire' ) { $eltname[0] = $oid_desc.' '.$i; }
						else if ( $oid_desc == 'Processus' ) { $eltname[0] = substr($eltname[0],2,strlen($eltname[0])-3); }

						if ( $oid_desc == "Partitions" ) {
							$elt_path_disk_desc = substr($oid_path,0,strlen($oid_path)-1)."3.".$index;
								$disk_desc = HostConnectPartitions($host_snmp_community,$host_address,$elt_path_disk_desc);				
								if ( $eltname[0] == "hrStorageFixedDisk" ) {
									echo '	<tr class="list_one">'," \n ";
									echo '  	<td class="ListColCenter"></td>'," \n ";
									echo ' 		<td class="ListCol">'," \n ";
									echo '			<table WIDTH=100% BORDER="0" ALIGN="LEFT" VALIGN="MIDDLE" CELLPADDING="0" CELLSPACING="0">'," \n ";
									echo '				<tr>'," \n ";
									echo '  				<td WIDTH=30px ALIGN="CENTER">'," \n ";
									echo '						<img style="border: 0px solid; width: 15px;" alt="-" src="./modules/CentreonDiscovery/pictures/double_puce.png">'," \n ";									
									echo ' 					</td>'," \n ";
									echo '  				<td><b>' . $eltname[0] . '     ( '. $disk_desc[0] .' ) :</b></td>'," \n ";
									echo '				</tr>'," \n ";	
								}

								/* ELEMENTS - Partitions */ echo '<INPUT TYPE=HIDDEN NAME="elt_name['.$group_number.']['.($i-1).']" VALUE="'. $disk_desc[0] .'"/>'," \n ";
						}
						else if ( $oid_desc == "Processus" ) {
							if ( !in_array($eltname[0],$processus) ) {
								echo '	<tr class="list_one">'," \n ";
								echo '  	<td class="ListColCenter"></td>'," \n ";
								echo ' 		<td class="ListCol">'," \n ";
								echo '			<table WIDTH=100% BORDER="0" ALIGN="LEFT" VALIGN="MIDDLE" CELLPADDING="0" CELLSPACING="0">'," \n ";
								echo '				<tr>'," \n ";
								echo '  				<td WIDTH=30px ALIGN="CENTER">'," \n ";
								echo '						<img style="border: 0px solid; width: 15px;" alt="-" src="./modules/CentreonDiscovery/pictures/double_puce.png">'," \n ";
								echo ' 					</td>'," \n ";
								echo '  				<td><b>' . $eltname[0] .' :</b></td>'," \n ";
								echo '				</tr>'," \n ";

								/* ELEMENTS - Processus */ echo '<INPUT TYPE=HIDDEN NAME="elt_name['.$group_number.']['.($i-1).']" VALUE="'. $eltname[0] .'"/>'," \n ";
							}
							else {
								/* ELEMENTS - Processus - false */ echo '<INPUT TYPE=HIDDEN NAME="elt_name['.$group_number.']['.($i-1).']" VALUE="NULL"/>'," \n ";
							}
						}
						else {
							echo '	<tr class="list_one">'," \n ";
							echo '  	<td class="ListColCenter"></td>'," \n ";
							echo ' 		<td class="ListCol">'," \n ";
							echo '			<table WIDTH=100% BORDER="0" ALIGN="LEFT" VALIGN="MIDDLE" CELLPADDING="0" CELLSPACING="0">'," \n ";
							echo '				<tr>'," \n ";
							echo '  				<td WIDTH=30px ALIGN="CENTER">'," \n ";
							echo '						<img style="border: 0px solid; width: 15px;" alt="-" src="./modules/CentreonDiscovery/pictures/double_puce.png">'," \n ";
							echo ' 					</td>'," \n ";
							echo '  				<td><b>' . $eltname[0] .' :</b></td>'," \n ";
							echo '				</tr>'," \n ";
							
							/* ELEMENTS - Autres */ echo '<INPUT TYPE=HIDDEN NAME="elt_name['.$group_number.']['.($i-1).']" VALUE="'. $eltname[0] .'"/>'," \n ";							
						}
						
						
						$resoidgroup = mysql_query("SELECT service_id,service_alias,service_description FROM (SELECT ServiceOID_id FROM ServiceOID WHERE OIDGroup_id_id = ".$oid_groupid.") AS GROUPS INNER JOIN service AS TEMPLATE ON GROUPS.ServiceOID_id = TEMPLATE.service_id");
						$n_service = 0;
						while ( $dataoidservice = mysql_fetch_assoc($resoidgroup)  ){
						// Pour chaque service porposé
							$oid_service_alias =  $dataoidservice['service_alias'];
							$oid_service_id =  $dataoidservice['service_id'];
							$oid_service_description =  $dataoidservice['service_description'];	
							
							if ( $oid_desc == "Partitions" ) {			
								if ( $eltname[0] == "hrStorageFixedDisk" ) {
									echo '				<tr>'," \n ";
									echo '					<td WIDTH=30px ALIGN="CENTER"><INPUT TYPE=CHECKBOX NAME="cb'.$cbgroup.'[]" VALUE="'. $disk_desc[0] .'|'. $oid_service_id .'|'. $oid_service_alias .'" onChange="checkone(document.getElementsByName(\'cb'.$cbgroup.'[]\'));"  CHECKED /></td>'," \n ";
									echo '					<td>'.$oid_service_alias.'</td>'," \n ";
									echo '				</tr>'," \n ";
									echo '				<tr HEIGHT="5px";></tr>'," \n ";
									echo '			</table>'," \n ";
									echo '		</td>'," \n ";
									echo '	</tr>'," \n ";
																		
									/* SERVICES  - Partitions */ echo '<INPUT TYPE=HIDDEN NAME="services['.$group_number.']['.($i-1).']['.$n_service.']" VALUE="'. $oid_service_id .'"/>'," \n ";								
								}
							}
							else if ( $oid_desc == "Processus" ) {	
								if ( !in_array($eltname[0],$processus) ) {				
									$processus[] =  $eltname[0];
									echo '				<tr>'," \n ";
									echo '					<td WIDTH=30px ALIGN="CENTER"><INPUT TYPE=CHECKBOX NAME="cb'.$cbgroup.'[]" VALUE="'. $eltname[0] .'|'. $oid_service_id .'|'. $oid_service_alias .'" onChange="checkone(document.getElementsByName(\'cb'.$cbgroup.'[]\'));"  CHECKED /></td>'," \n ";
									echo '					<td>'.$oid_service_alias.'</td>'," \n ";
									echo '				</tr>'," \n ";
									echo '				<tr HEIGHT="5px";></tr>'," \n ";
									echo '			</table>'," \n ";
									echo '		</td>'," \n ";
									echo '	</tr>'," \n ";		
									
									/* SERVICES - Autres */ echo '<INPUT TYPE=HIDDEN NAME="services['.$group_number.']['.($i-1).']['.$n_service.']" VALUE="'. $oid_service_id .'"/>'," \n ";								
								}
							}
							else {
								echo '				<tr>'," \n ";
								echo '					<td WIDTH=30px ALIGN="CENTER"><INPUT TYPE=CHECKBOX NAME="cb'.$cbgroup.'[]" VALUE="'. $eltname[0] .'|'. $oid_service_id .'|'. $oid_service_alias .'" onChange="checkone(document.getElementsByName(\'cb'.$cbgroup.'[]\'));"  CHECKED /></td>'," \n ";
								echo '					<td>'.$oid_service_alias.'</td>'," \n ";
								echo '				</tr>'," \n ";
								echo '				<tr HEIGHT="5px";></tr>'," \n ";
								echo '			</table>'," \n ";
								echo '		</td>'," \n ";
								echo '	</tr>'," \n ";		
								
								/* SERVICES - Autres */ echo '<INPUT TYPE=HIDDEN NAME="services['.$group_number.']['.($i-1).']['.$n_service.']" VALUE="'. $oid_service_id .'"/>'," \n ";								
							}
							$n_service++;
						}
					}
					
					if ( $sizeretour > 0 ) {
						echo '</table>'," \n ";
						echo '<br>'," \n ";
					}
					$group_number++;	
				}
			}
			if ( $group_number == 0 ) {
				// Si aucun service n'est proposé pour le type d'host sélectionné
				echo '<p>'," \n ";
				echo '<span style="font-family: Candara; font-weight: bold; color: red; font-style: italic;">'," \n ";
				echo '  <center><big>Auncun service propos&eacute; pour l\'host s&eacute;lectionn&eacute; </center></big>'," \n ";
				echo '</span>'," \n ";
				echo '</p>'," \n ";
				echo '<br><br>'," \n ";
			}
			echo '<INPUT TYPE=HIDDEN NAME="group_number" VALUE="'. $cbgroup .'"/>'," \n ";
		}

		// Bouton de validation page 2
		echo '<p align="right"><INPUT TYPE=SUBMIT VALUE="Valider" NAME="send2" OnClick="return verifselection(document.FORM2);"></p>'," \n ";
		echo '</FORM>'," \n ";
?>