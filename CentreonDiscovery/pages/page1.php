<?
	// ***********************************************************************
	// * Page 1 : liste des "hosts" - Choix de l'élément à superviser *
	// ***********************************************************************
	
		// Requetes SQL - Récupération des Hosts
		$reshost = mysql_query("SELECT host_id,host_name,host_address,host_snmp_community,host_template_model_htm_id FROM host WHERE host_address IS NOT NULL AND host_register = '1'");
		// Requetes SQL - Récupération des Templates
		$restemplate = mysql_query("SELECT * FROM CDType");
		
		
		// Stockage  des données - Templates
		$i = 0;
		$template = array();
		while ( $datatemplate = mysql_fetch_assoc($restemplate) ) {
			$template[$i] = $datatemplate['CDType_type'];
			$i++;
		}
	

		// Mise en forme des informations
		echo '<FORM NAME="FORM1" METHOD=POST ACTION="#">'," \n ";
		// Bouton de validation page 1
		echo '	<p align="right"><INPUT TYPE=SUBMIT VALUE="Ok" NAME="send1" OnClick="return radiovide(FORM1);"></p>'," \n ";
		echo '	<br>'," \n ";
		echo '    <table class="ListTable">'," \n ";
		echo '		<tr class="ListHeader">'," \n ";
		echo '			<td class="ListColHeaderCenter"></td>'," \n ";
		echo '			<td class="ListColHeaderCenter">Host</td>'," \n ";
		echo '			<td class="ListColHeaderCenter">Community</td>'," \n ";
		echo '			<td class="ListColHeaderCenter">IP Address</td>'," \n ";
		echo '			<td class="ListColHeaderCenter">Type</td>'," \n ";
		echo '		</tr>'," \n ";

		// Traitements des données
		while ( $datahost = mysql_fetch_assoc($reshost)  ){
			// Récupération des informations de l'host
			$host_name = $datahost['host_name'];
			$host_address = $datahost['host_address'];
			$host_id =  $datahost['host_id'];
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
			echo '  <td class="ListColCenter"><INPUT TYPE=RADIO NAME="choice" VALUE="'. $host_id .'"/></td>'," \n ";
			echo '  <td class="ListColCenter">' . $host_name . '</td>'," \n ";
			echo '  <td class="ListColCenter">' . $host_snmp_community . '</td>'," \n ";
			echo '  <td class="ListColCenter">' . $host_address . '</td>'," \n ";
			echo '  <td class="ListColCenter">'," \n ";
			echo '    <select name=select'.$host_id.' width=95%>'," \n ";
			foreach( $template as $elt ) 
			{
				echo '      <option value='.$elt.'>'.$elt.'</option>'," \n ";
			}
			echo '    </select>'," \n ";
			echo '  </td>'," \n ";
			echo '</tr>'," \n ";
		}

		echo '	</table>'," \n ";
		echo '	<br>'," \n ";
		// Bouton de validation page 1
		echo '	<p align="right"><INPUT TYPE=SUBMIT VALUE="Ok" NAME="send1" OnClick="return radiovide(FORM1);"></p>'," \n ";
		echo '</FORM>'," \n ";
?>