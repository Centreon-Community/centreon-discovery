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

/* Fonction PHP */	
function queryIsEmpty($cmd) {
	$empty = 0;
	$res = mysql_query($cmd);
	$num_row = mysql_num_rows($res);
	if ( $num_row > 0 ) { $empty = 1; }
	return $empty;
}

	
/*
* DATABASE : Centreon2
* TABLE : command
*	
*	Insertion des commandes
*/
	/* Windows */
		if ( queryIsEmpty('SELECT command_id FROM command WHERE command_name = "check_teamreon_interface_state_win"') == 0 ) {
			if ( !mysql_query('INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_interface_state_win","$USER1$#S#check_teamreon_snmp_int.pl -H $HOSTADDRESS$ -C $USER2$ -n $ARG1$","!eth0",2,NULL,NULL)') ) { echo mysql_error(); }
		}
		if ( queryIsEmpty('SELECT command_id FROM command WHERE command_name = "check_teamreon_snmp_traffic_win"') == 0 ) {
			if ( !mysql_query('INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_snmp_traffic_win","$USER1$#S#check_teamreon_snmp_traffic.pl -H $HOSTADDRESS$ -n -i $ARG1$ -w $ARG2$ -c $ARG3$ -C $USER2$ -v $ARG4$","!eth0!80!90!1",2,7,NULL)') ) { echo mysql_error(); }
		}
		//if ( queryIsEmpty('SELECT command_id FROM command WHERE command_name = "check_teamreon_snmp_load_CPU_Window"') == 0 ) {
		//	mysql_query('INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_snmp_load_CPU_Window","$USER1$#S#check_centreon_snmp_cpu.pl -H $HOSTADDRESS$ -v 1 -C $ARG1$ -c $ARG2$ -w $ARG3$","!$USER2$!95!96!",2,NULL,NULL)');
		//}
		if ( queryIsEmpty('SELECT command_id FROM command WHERE command_name = "check_teamreon_snmp_storage_win"') == 0 ) {
			if ( !mysql_query('INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_snmp_storage_win","$USER1$#S#check_teamreon_snmp_storage_win.pl -H $HOSTADDRESS$ -n -d $ARG1$ -w $ARG2$ -c $ARG3$ -C $ARG4$ -v $ARG5$","!#S#!80!90!$USER2$!1",2,NULL,NULL)')  ) { echo mysql_error(); }
		}
		if ( queryIsEmpty('SELECT command_id FROM command WHERE command_name = "check_teamreon_memory_win"') == 0 ) {
			if ( !mysql_query('INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_memory_win","$USER1$#S#check_teamreon_memory_win.pl -H $HOSTADDRESS$ -C $USER2$ -v 1 -w 80 -c 90"," ",2,NULL,NULL)')  ) { echo mysql_error(); }
		}
		if ( queryIsEmpty('SELECT command_id FROM command WHERE command_name = "check_teamreon_process_win"') == 0 ) {
			if ( !mysql_query('INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_process_win","$USER1$#S#check_teamreon_process_win.pl -H $HOSTADDRESS$ -C $ARG1$ -n $ARG2$","!$USER2$!explorer",2,NULL,NULL)')  ) { echo mysql_error(); }
		}
	/* Linux */
		if ( queryIsEmpty('SELECT command_id FROM command WHERE command_name = "check_teamreon_interface_state_linux"') == 0 ) {
			if ( !mysql_query('INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_interface_state_linux","$USER1$#S#check_teamreon_snmp_int.pl -H $HOSTADDRESS$ -C $USER2$ -n $ARG1$","!eth0",2,NULL,NULL)') ) { echo mysql_error(); }
		}
		if ( queryIsEmpty('SELECT command_id FROM command WHERE command_name = "check_teamreon_snmp_traffic_linux"') == 0 ) {
			if ( !mysql_query('INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_snmp_traffic_linux","$USER1$#S#check_teamreon_snmp_traffic.pl -H $HOSTADDRESS$ -n -i $ARG1$ -w $ARG2$ -c $ARG3$ -C $USER2$ -v $ARG4$","!eth0!80!90!1",2,7,NULL)') ) { echo mysql_error(); }
		}
		if ( queryIsEmpty('SELECT command_id FROM command WHERE command_name = "check_teamreon_CPU_linux"') == 0 ) {
			if ( !mysql_query('INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_CPU_linux","$USER1$#S#check_teamreon_CPU_linux.pl -H $HOSTADDRESS$ -v $ARG1$ -C $ARG2$ -w $ARG3$ -c $ARG4$","!1!$USER2$!4,3,2!6,5,4",2,NULL,NULL)')  ) { echo mysql_error(); }
		}
		if ( queryIsEmpty('SELECT command_id FROM command WHERE command_name = "check_teamreon_memory_linux"') == 0 ) {
			if ( !mysql_query('INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_memory_linux","$USER1$#S#check_teamreon_memory_linux.pl -H $HOSTADDRESS$ -C $ARG1$ -v 1 -w $ARG2$ -c $ARG3$","",2,NULL,NULL)')  ) { echo mysql_error(); }
		}
		if ( queryIsEmpty('SELECT command_id FROM command WHERE command_name = "check_teamreon_process_linux"') == 0 ) {
			if ( !mysql_query('INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_process_linux","$USER1$#S#check_teamreon_process_linux.pl -H $HOSTADDRESS$ -C $ARG1$ -n $ARG2$","!$USER2$!snmpd",2,NULL,NULL)')  ) { echo mysql_error(); }
		}
		if ( queryIsEmpty('SELECT command_id FROM command WHERE command_name = "check_teamreon_snmp_storage_linux"') == 0 ) {
			if ( !mysql_query('INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_snmp_storage_linux","$USER1$#S#check_teamreon_snmp_storage_linux.pl -H $HOSTADDRESS$ -n -d $ARG1$ -w $ARG2$ -c $ARG3$ -C $ARG4$ -v $ARG5$","!#S#!80!90!$USER2$!1",2,NULL,NULL)')  ) { echo mysql_error(); }
		}
	/* Cisco Switch*/
		if ( queryIsEmpty('SELECT command_id FROM command WHERE command_name = "check_teamreon_memory_cisco_switch"') == 0 ) {
			if ( !mysql_query('INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_memory_cisco_switch","$USER1$#S#check_teamreon_memory_cisco_switch.pl -H $HOSTADDRESS$ -C $ARG1$ -w $ARG2$ -c $ARG3$ -I","!$USER2$!95!96!",2,NULL, NULL)')  ) { echo mysql_error(); }
		}
		if ( queryIsEmpty('SELECT command_id FROM command WHERE command_name = "check_teamreon_interface_cisco_switch"') == 0 ) {
			if ( !mysql_query('INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_interface_cisco_switch","$USER1$#S#check_teamreon_interface_cisco_switch.pl -H $HOSTADDRESS$ -C $ARG1$ -n $ARG2$ -r" ,"!$USER2$!&quot;FastEthernet0/1&quot;!",2,NULL,NULL)')  ) { echo mysql_error(); }
		}
	/* Cisco Router*/
		if ( queryIsEmpty('SELECT command_id FROM command WHERE command_name = "check_teamreon_memory_cisco_router"') == 0 ) {
			if ( !mysql_query('INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_memory_cisco_router","$USER1$#S#check_teamreon_memory_cisco_router.pl -H $HOSTADDRESS$ -C $ARG1$ -w $ARG2$ -c $ARG3$ -I","!$USER2$!95!96!",2,NULL, NULL)') ) { echo mysql_error(); }
		}
		if ( queryIsEmpty('SELECT command_id FROM command WHERE command_name = "check_teamreon_interface_cisco_router"') == 0 ) {
			if ( !mysql_query('INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_interface_cisco_router","$USER1$#S#check_teamreon_interface_cisco_router.pl -H $HOSTADDRESS$ -C $ARG1$ -n $ARG2$ -r" ,"!$USER2$!&quot;FastEthernet0/0&quot;!",2,NULL,NULL)')  ) { echo mysql_error(); }
		}


	
	

/*
* DATABASE : Centreon2
* TABLE : service
*	
*	Creation de la table service
*/

	/* Template generic */
	if ( queryIsEmpty('SELECT service_id FROM service WHERE service_description = "Template_TeamReon_generic"') == 0 ) {
		if ( !mysql_query('INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,1,NULL,1,NULL,1,"Template_TeamReon_generic","Template_TeamReon_generic",NULL,"0",3,5,1,"1","0",NULL,"2","2","2",NULL,"2",NULL,NULL,"2","2","2","2",0,"w,c,r","0",NULL,NULL,NULL,NULL,NULL,"0","1")')  ) { echo mysql_error(); }
		if ( !mysql_query('INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = "Template_TeamReon_generic" LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL)')  ) { echo mysql_error(); }
	}


	$service_id_generic_answer = mysql_query('SELECT service_id FROM service WHERE service_description = "Template_TeamReon_generic" LIMIT 1');	
	$num_row_service_id_generic = mysql_num_rows($service_id_generic_answer);	
	if ( $num_row_service_id_generic > 0 ) {
	
		while ( $data_service_id_generic = mysql_fetch_assoc($service_id_generic_answer) ) {
			$service_id_generic = $data_service_id_generic['service_id'];
		}

		/*
		*  Windows 
		*/
			/*NIC*/
			if ( queryIsEmpty('SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Win2K3_int_state"') == 0 ) {
				if ( !mysql_query('INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,'.$service_id_generic.',(SELECT command_id FROM command WHERE command_name = "check_teamreon_interface_state_win" LIMIT 1),NULL,NULL,NULL,"Template_TeamReon_Win2K3_int_state","Etat de l\'interface",NULL,"2",NULL,NULL,NULL,"2","2",NULL,"2","2","2",NULL,"2",NULL,NULL,"2","2","2","2",NULL,NULL,"2",NULL,NULL,NULL,"!3!200,20%!400,50%",NULL,"0","1")')  ) { echo mysql_error(); }
				if ( !mysql_query('INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Win2K3_int_state" LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL)')  ) { echo mysql_error(); }
			}
			if ( queryIsEmpty('SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Win2K3_traffic"') == 0 ) {
				if ( !mysql_query('INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,'.$service_id_generic.',(SELECT command_id FROM command WHERE command_name = "check_teamreon_snmp_traffic_win" LIMIT 1),NULL,NULL,NULL,"Template_TeamReon_Win2K3_traffic","Bande Passante",NULL,"2",NULL,NULL,NULL,"2","2",NULL,"2","2","2",NULL,"2",NULL,NULL,"2","2","2","2",NULL,NULL,"2",NULL,NULL,NULL,"!eth0!80!90!1",NULL,"0","1")')  ) { echo mysql_error(); }
				if ( !mysql_query('INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Win2K3_traffic" LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL)')  ) { echo mysql_error(); }
			}
			

			/*DD*/
			if ( queryIsEmpty('SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Win2K3_Disk"') == 0 ) {
				if ( !mysql_query('INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,'.$service_id_generic.',(SELECT command_id FROM command WHERE command_name = "check_teamreon_snmp_storage_win" LIMIT 1),NULL,NULL,NULL,"Template_TeamReon_Win2K3_Disk","Taux Utilisation de la partition",NULL,"2",NULL,NULL,NULL,"2","2",NULL,"2","2","2",NULL,"2",NULL,NULL,"2","2","2","2",NULL,NULL,"2",NULL,NULL,NULL,"!C!80!90!$USER2$!1",NULL,"0","1")')  ) { echo mysql_error(); }
				if ( !mysql_query('INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Win2K3_Disk" LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL)')  ) { echo mysql_error(); }
			}

			/* CPU */
//			if ( queryIsEmpty('SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Win2K3_CPU"') == 0 ) {
//				if ( !mysql_query('INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,'.$service_id_generic.',(SELECT command_id FROM command WHERE command_name = "check_teamreon_snmp_load_CPU_Window" LIMIT 1),NULL,NULL,NULL,"Template_TeamReon_Win2K3_CPU","Taux Utilisation CPU",NULL,"2",NULL,NULL,NULL,"2","2",NULL,"2","2","2",NULL,"2",NULL,NULL,"2","2","2","2",NULL,NULL,"2",NULL,NULL,NULL,"!$USER2$!95!96!",NULL,"0","1")')  ) { echo mysql_error(); }
//				if ( !mysql_query('INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Win2K3_CPU" LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL)')  ) { echo mysql_error(); }
//			}

			/*memory*/
			if ( queryIsEmpty('SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Win2K3_Memory"') == 0 ) {
				if ( !mysql_query('INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,'.$service_id_generic.',(SELECT command_id FROM command WHERE command_name = "check_teamreon_memory_win" LIMIT 1),NULL,NULL,NULL,"Template_TeamReon_Win2K3_Memory","Taux Utilisation Memoire (Used, RAM, Swap)",NULL,"2",NULL,NULL,NULL,"2","2",NULL,"2","2","2",NULL,"2",NULL,NULL,"2","2","2","2",NULL,NULL,"2",NULL,NULL,NULL," ",NULL,"0","1")')  ) { echo mysql_error(); }
				if ( !mysql_query('INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Win2K3_Memory" LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL)')  ) { echo mysql_error(); }
			}
				
			/*process */
			if ( queryIsEmpty('SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Win2K3_Process"') == 0 ) {
				if ( !mysql_query('INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,'.$service_id_generic.',(SELECT command_id FROM command WHERE command_name = "check_teamreon_process_win" LIMIT 1),NULL,NULL,NULL,"Template_TeamReon_Win2K3_Process","Etat du processus",NULL,"2",NULL,NULL,NULL,"2","2",NULL,"2","2","2",NULL,"2",NULL,NULL,"2","2","2","2",NULL,NULL,"2",NULL,NULL,NULL,"!$USER2$!explorer",NULL,"0","1")')  ) { echo mysql_error(); }
				if ( !mysql_query('INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Win2K3_Process" LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL)')  ) { echo mysql_error(); }
			}


		/*
		*  Linux
		*/
			/*NIC*/
			if ( queryIsEmpty('SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Linux_int_state"') == 0 ) {
				if ( !mysql_query('INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,'.$service_id_generic.',(SELECT command_id FROM command WHERE command_name = "check_teamreon_interface_state_linux" LIMIT 1),NULL,NULL,NULL,"Template_TeamReon_Linux_int_state","Etat de l\'interface",NULL,"2",NULL,NULL,NULL,"2","2",NULL,"2","2","2",NULL,"2",NULL,NULL,"2","2","2","2",NULL,NULL,"2",NULL,NULL,NULL,"!3!200,20%!400,50%",NULL,"0","1")')  ) { echo mysql_error(); }
				if ( !mysql_query('INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Linux_int_state" LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL)')  ) { echo mysql_error(); }
			}
			if ( queryIsEmpty('SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Linux_traffic"') == 0 ) {
				if ( !mysql_query('INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,'.$service_id_generic.',(SELECT command_id FROM command WHERE command_name = "check_teamreon_snmp_traffic_linux" LIMIT 1),NULL,NULL,NULL,"Template_TeamReon_Linux_traffic","Bande Passante",NULL,"2",NULL,NULL,NULL,"2","2",NULL,"2","2","2",NULL,"2",NULL,NULL,"2","2","2","2",NULL,NULL,"2",NULL,NULL,NULL,"!eth0!80!90!1",NULL,"0","1")')  ) { echo mysql_error(); }
				if ( !mysql_query('INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Linux_traffic" LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL)')  ) { echo mysql_error(); }
			}
			
			
			/*CPU*/
			if ( queryIsEmpty('SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Linux_CPU"') == 0 ) {
				if ( !mysql_query('INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,'.$service_id_generic.',(SELECT command_id FROM command WHERE command_name = "check_teamreon_CPU_linux" LIMIT 1),NULL,NULL,NULL,"Template_TeamReon_Linux_CPU","Taux Utilisation CPU",NULL,"2",NULL,NULL,NULL,"2","2",NULL,"2","2","2",NULL,"2",NULL,NULL,"2","2","2","2",NULL,NULL,"2",NULL,NULL,NULL,"!1!$USER2$!4,3,2!6,5,4",NULL,"0","1")')  ) { echo mysql_error(); }
				if ( !mysql_query('INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Linux_CPU" LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL)')  ) { echo mysql_error(); }
			}

			/*memory*/
			if ( queryIsEmpty('SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Linux_Memory"') == 0 ) {
				if ( !mysql_query('INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,'.$service_id_generic.',(SELECT command_id FROM command WHERE command_name = "check_teamreon_memory_linux" LIMIT 1),NULL,NULL,NULL,"Template_TeamReon_Linux_Memory","Taux Utilisation Memoire (Used, RAM, Swap)",NULL,"2",NULL,NULL,NULL,"2","2",NULL,"2","2","2",NULL,"2",NULL,NULL,"2","2","2","2",NULL,NULL,"2",NULL,NULL,NULL,"!$USER2$!80!90",NULL,"0","1")')  ) { echo mysql_error(); }
				if ( !mysql_query('INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Linux_Memory" LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL)')  ) { echo mysql_error(); }
			}

			/*DD*/
			if ( queryIsEmpty('SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Linux_Disk"') == 0 ) {
				if ( !mysql_query('INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,'.$service_id_generic.',(SELECT command_id FROM command WHERE command_name = "check_teamreon_snmp_storage_linux" LIMIT 1),NULL,NULL,NULL,"Template_TeamReon_Linux_Disk","Taux Utilisation de la partition",NULL,"2",NULL,NULL,NULL,"2","2",NULL,"2","2","2",NULL,"2",NULL,NULL,"2","2","2","2",NULL,NULL,"2",NULL,NULL,NULL,"!/!80!90!$USER2$!1",NULL,"0","1")')  ) { echo mysql_error(); }
				if ( !mysql_query('INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Linux_Disk" LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL)')  ) { echo mysql_error(); }
			}

			/*process */
			if ( queryIsEmpty('SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Linux_Process"') == 0 ) {
				if ( !mysql_query('INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,'.$service_id_generic.',(SELECT command_id FROM command WHERE command_name = "check_teamreon_process_linux" LIMIT 1),NULL,NULL,NULL,"Template_TeamReon_Linux_Process","Etat du processus",NULL,"2",NULL,NULL,NULL,"2","2",NULL,"2","2","2",NULL,"2",NULL,NULL,"2","2","2","2",NULL,NULL,"2",NULL,NULL,NULL,"!$USER2$!snmpd",NULL,"0","1")')  ) { echo mysql_error(); }
				if ( !mysql_query('INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Linux_Process" LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL)')  ) { echo mysql_error(); }
			}

		/*
		*  Cisco switch
		*/
			/*CPU*/
			if ( queryIsEmpty('SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Cisco_CPU"') == 0 ) {
				if ( !mysql_query('INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,'.$service_id_generic.',(SELECT command_id FROM command WHERE command_name = "check_teamreon_memory_cisco_switch" LIMIT 1),NULL,NULL,NULL,"Template_TeamReon_Cisco_CPU","Taux Utilisation CPU",NULL,"2",NULL,NULL,NULL,"2","2",NULL,"2","2","2",NULL,"2",NULL,NULL,"2","2","2","2",NULL,NULL,"2",NULL,NULL,NULL,"!$USER2$!95!96!",NULL,"0","1")')  ) { echo mysql_error(); }
				if ( !mysql_query('INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Cisco_CPU" LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL)')  ) { echo mysql_error(); }
			}

			/*NIC*/
			if ( queryIsEmpty('SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Cisco_NIC"') == 0 ) {
				if ( !mysql_query('INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,'.$service_id_generic.',(SELECT command_id FROM command WHERE command_name = "check_teamreon_interface_cisco_switch" LIMIT 1),NULL,NULL,NULL,"Template_TeamReon_Cisco_NIC","Etat de l&quot;interface",NULL,"2",NULL,NULL,NULL,"2","2",NULL,"2","2","2",NULL,"2",NULL,NULL,"2","2","2","2",NULL,NULL,"2",NULL,NULL,NULL,"!$USER2$!&quot;FastEthernet0#S#1&quot;!",NULL,"0","1")')  ) { echo mysql_error(); }
				if ( !mysql_query('INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Cisco_NIC" LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL)')  ) { echo mysql_error(); }
			}



		/*
		*  Cisco Router
		*/
			/*CPU*/
			if ( queryIsEmpty('SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Cisco_router_CPU"') == 0 ) {
				if ( !mysql_query('INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,'.$service_id_generic.',(SELECT command_id FROM command WHERE command_name = "check_teamreon_memory_cisco_router" LIMIT 1),NULL,NULL,NULL,"Template_TeamReon_Cisco_router_CPU","Taux Utilisation CPU",NULL,"2",NULL,NULL,NULL,"2","2",NULL,"2","2","2",NULL,"2",NULL,NULL,"2","2","2","2",NULL,NULL,"2",NULL,NULL,NULL,"!$USER2$!95!96!",NULL,"0","1")')  ) { echo mysql_error(); }
				if ( !mysql_query('INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Cisco_router_CPU" LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL)')  ) { echo mysql_error(); }
			}

			/*NIC*/
			if ( queryIsEmpty('SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Cisco_NIC_router"') == 0 ) {
				if ( !mysql_query('INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,'.$service_id_generic.',(SELECT command_id FROM command WHERE command_name = "check_teamreon_interface_cisco_router" LIMIT 1),NULL,NULL,NULL,"Template_TeamReon_Cisco_NIC_router","Etat de l&quot;interface",NULL,"2",NULL,NULL,NULL,"2","2",NULL,"2","2","2",NULL,"2",NULL,NULL,"2","2","2","2",NULL,NULL,"2",NULL,NULL,NULL,"!$USER2$!&quot;FastEthernet0#S#1&quot;!",NULL,"0","1")')  ) { echo mysql_error(); }
				if ( !mysql_query('INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Cisco_NIC_router" LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL)')  ) { echo mysql_error(); }
			}
	

	
		/*
		* DATABASE : Centreon2
		* TABLE : CommandOID
		*	
		*	Creation de la table CommandOID
		*/
		if ( !mysql_query('CREATE TABLE IF NOT EXISTS ServiceOID (ServiceOID_id INT UNSIGNED NOT NULL, OIDGroup_id_id INT UNSIGNED NOT NULL, FOREIGN KEY (ServiceOID_id) REFERENCES service(service_id), FOREIGN KEY (OIDGroup_id_id) REFERENCES OIDGroup(OIDGroup_id))')  ) { echo mysql_error(); }
		
		/* Windows */
			if ( !mysql_query('INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Win2K3_int_state" LIMIT 1),101)')  ) { echo mysql_error(); }
/*TEST*///				if ( !mysql_query('INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Win2K3_traffic" LIMIT 1),101)')  ) { echo mysql_error(); }
			
//			if ( !mysql_query('INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Win2K3_CPU" LIMIT 1),102)')  ) { echo mysql_error(); }

			if ( !mysql_query('INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Win2K3_Disk" LIMIT 1),103)')  ) { echo mysql_error(); }
			
			if ( !mysql_query('INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Win2K3_Memory" LIMIT 1),104)')  ) { echo mysql_error(); }
			
			if ( !mysql_query('INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Win2K3_Process" LIMIT 1),105)')  ) { echo mysql_error(); }
			
			
		/* Linux */
			if ( !mysql_query('INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Linux_int_state" LIMIT 1),201)')  ) { echo mysql_error(); }
			if ( !mysql_query('INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Linux_traffic" LIMIT 1),201)')  ) { echo mysql_error(); }
			
			if ( !mysql_query('INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Linux_CPU" LIMIT 1),202)')  ) { echo mysql_error(); }
			
			if ( !mysql_query('INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Linux_Disk" LIMIT 1),203)')  ) { echo mysql_error(); }
			
			if ( !mysql_query('INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Linux_Memory" LIMIT 1),204)')  ) { echo mysql_error(); }
			
			if ( !mysql_query('INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Linux_Process" LIMIT 1),205)')  ) { echo mysql_error(); }
			
			

		/* Router-Cisco */
			if ( !mysql_query('INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Cisco_NIC_router" LIMIT 1),301)')  ) { echo mysql_error(); }
			
			if ( !mysql_query('INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Cisco_router_CPU" LIMIT 1),302)')  ) { echo mysql_error(); }
			
			
		/*Switch-Cisco */
			if ( !mysql_query('INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Cisco_NIC" LIMIT 1),401)')  ) { echo mysql_error(); }
			
			if ( !mysql_query('INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = "Template_TeamReon_Cisco_CPU" LIMIT 1),402)')  ) { echo mysql_error(); }
			
	}

?>