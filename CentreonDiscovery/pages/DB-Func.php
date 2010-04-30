<?php
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

	/* Connection à la database Centreon2	
	 * @parm : $host - Serveur MySQL
	 * @parm : $user - Utilisateur accédant au serveur
	 * @parm : $pass - Mot de passe de l'utilisateur
	 * @parm : $bdd  - Base de données 
	*/
	function DBconnect($host,$user,$pass,$bdd) {
			$my = mysql_connect($host, $user, $pass) OR DIE ('ERREUR DE CONNEXION AU SERVEUR !<br>'.mysql_error().'<br>');
			mysql_select_db($bdd,$my) OR DIE ('ERREUR SELECTION DATABASE DU SERVEUR');		
	}
	
	/* Déonnection à la database Centreon2	
	*/	
	function DBdisconnect() {
			mysql_close();		
	}
	
	/* Requete snmpwalk	
	 * @parm : $host_snmp_community - Communauté snmp
	 * @parm : $host_address - Adresse IP de l'host
	 * @parm : $oid_path - OID
	*/
	function HostConnect($host_snmp_community,$host_address,$oid_path) {
	 	$cmd = "snmpwalk -v 2c -c $host_snmp_community $host_address $oid_path";
		exec($cmd,$res);		
		$result = array();
		$i = 0;
		foreach($res as $row) {                                  
			$pos = strrpos($row,':') + 1;
			$result[$i] = substr($row,$pos);
			$i++;
		}       
		return $result;
	}
	
	/* Requete snmpwalk pour l'obtention des Nom de partitions
	 * @parm : $host_snmp_community - Communauté snmp
	 * @parm : $host_address - Adresse IP de l'host
	 * @parm : $oid_path - OID
	*/
	function HostConnectPartitions($host_snmp_community,$host_address,$oid_path) {
	 	$cmd = "snmpwalk -v 2c -c $host_snmp_community $host_address $oid_path";
		exec($cmd,$res);		
		$result = array();
		$i = 0;
		foreach($res as $row) {                                  
			$pos = strpos($row,'STRING:');
			$chaine = substr($row,$pos+8);
			$result[$i] = substr($chaine,0,1);
			$i++;
		}       
		return $result;
	}
	
	/*  Vérification si la requête retourne une donnée
	 * @parm : $cmd - Requête
	*/
	function queryIsEmpty($cmd) {
		$empty = 0;
		$res = mysql_query($cmd);
		$num_row = mysql_num_rows($res);
		if ( $num_row > 0 ) { $empty = 1; }
		return $empty;
	}
	
	/*  Création des services host à partir des Templates de services
	 * @parm : $host_id - ID de l'host
	 * @parm : $service_id - ID du template de service
	*/
	function InsertionRelationServiceBDD($host_id,$service_id,$elt_name,$group_description,$index,$service_display) {
		$arguments = 'NULL';
		
		$template_description = mysql_query("SELECT service_description FROM service WHERE service_id = ".$service_id." LIMIT 1");
		while ( $data = mysql_fetch_assoc($template_description) ) {
			$description = $data['service_description'];
		}
		
		$host_description = mysql_query("SELECT host_name FROM host WHERE host_id = ".$host_id." LIMIT 1");
		while ( $data = mysql_fetch_assoc($host_description) ) {
			$host_name = $data['host_name'];
		}

		$action = substr($description,(strrpos($description,"_")+1),strlen($description));
		//$service_description = substr($description,18,strlen($description)).'_'.$elt_name;
		
		if ( strlen($service_display) == 0 ) {
			$service_display = substr($description,18,strlen($description)).'_'.$elt_name;
		}
		
		if ( queryIsEmpty('SELECT service_id FROM host_service_relation INNER JOIN service ON service_service_id = service_id WHERE service_template_model_stm_id = '.$service_id.' AND host_host_id = '.$host_id.' AND service_description = '.$service_display) == 0 ) {
					
			if ( $group_description == "Interface Reseaux" ) {
				if ( $action == "state" ) {
					$arguments = '"!'.substr($elt_name,1).'"';
				}
				else if ( $action == "traffic" ) {
					$arguments = '"!'.$index.'!80!90!1"';
				}
			}
			else if ( $group_description == "Partitions" ) {
				$arguments = '"!'.$elt_name.'!80!90!$USER2$!1"';
			}
			else if ( $group_description == "Processus" ) {
				$arguments =  '"!$USER2$!'.$elt_name.'!"';
			}
			
			/* Insertion du nouveau service */
			if ( !mysql_query("INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,".$service_id.",NULL,NULL,NULL,NULL,'".$service_display."',NULL,NULL,'2',NULL,NULL,NULL,'2','2',NULL,'2','2','2',NULL,'2',NULL,NULL,'2','2','2','2',NULL,NULL,'2',NULL,NULL,NULL,".$arguments.",NULL,'1','1')") ) { echo mysql_error(); }
			
			/* Recherche de l'ID du nouveau service */
			$newservice = mysql_query("SELECT service_id FROM service ORDER BY service_id desc LIMIT 1");
			while ( $data = mysql_fetch_assoc($newservice) ) {
				$newservice_id = $data['service_id'];
			}
			
			/* Insertion de l'extention du nouveau service */
			if ( !mysql_query("INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,".$newservice_id.",NULL,NULL,NULL,NULL,NULL,NULL)") ) { echo mysql_error(); }
			
			/* Association Service - Host */
			if ( !mysql_query("INSERT INTO host_service_relation (hsr_id,hostgroup_hg_id,host_host_id,servicegroup_sg_id,service_service_id) VALUES (NULL,NULL,".$host_id.",NULL,".$newservice_id.")") ) { echo mysql_error(); }	
			
			$result[0] = 0;
			$result[1] = $service_display;
		}
		else {
			$result[0] = 1;
			$result[1] = $service_display;
		}
		
		return $result;
	} 
?>