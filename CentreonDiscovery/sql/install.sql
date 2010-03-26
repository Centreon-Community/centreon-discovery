/*
* DATABASE : Centreon2
* TABLE : topology
*	
*	Insertion des pages du module Centreon Discovery
*
*
*	Page principale du module Centreon Discovery
*/
INSERT INTO `topology` (`topology_id`, `topology_name`, `topology_icone`, `topology_parent`, `topology_page`, `topology_order`, `topology_group`, `topology_url`, `topology_url_opt`, `topology_popup`, `topology_modules`, `topology_show`) VALUES ('', 'Centreon Discovery', NULL, 6, 610, 100, 1, './modules/CentreonDiscovery/pages/CentreonDiscovery.php', NULL, '0', '1', '1');
/*
*	Pages secondaires du module Centreon Discovery
*/
INSERT INTO `topology` (`topology_id`, `topology_name`, `topology_icone`, `topology_parent`, `topology_page`, `topology_order`, `topology_group`, `topology_url`, `topology_url_opt`, `topology_popup`, `topology_modules`, `topology_show`) VALUES ('', 'Setup', './modules/CentreonDiscovery/pictures/press.gif', 610, 61001, 1, 1, './modules/CentreonDiscovery/pages/CentreonDiscovery.php', NULL, '0', '1', '1');
INSERT INTO `topology` (`topology_id`, `topology_name`, `topology_icone`, `topology_parent`, `topology_page`, `topology_order`, `topology_group`, `topology_url`, `topology_url_opt`, `topology_popup`, `topology_modules`, `topology_show`) VALUES ('', 'Documentation', './modules/CentreonDiscovery/pictures/book_green.gif', 610, 61002, 100, 1, './modules/CentreonDiscovery/pages/Documentation.htm', NULL, '0', '1', '1');
INSERT INTO `topology` (`topology_id`, `topology_name`, `topology_icone`, `topology_parent`, `topology_page`, `topology_order`, `topology_group`, `topology_url`, `topology_url_opt`, `topology_popup`, `topology_modules`, `topology_show`) VALUES ('', 'A propos', './modules/CentreonDiscovery/pictures/about.gif', 610, 61003, 101, 1, './modules/CentreonDiscovery/pages/TeamReon.htm', NULL, '0', '1', '1');



/*
* DATABASE : Centreon2
* TABLE : CDType
*	
*	Création de la table CDType
*/
CREATE TABLE IF NOT EXISTS CDType (CDType_id INT UNSIGNED NOT NULL PRIMARY KEY, CDType_type CHAR(100));
/*
*	Insertion des valeurs dans la table CDType
*/
INSERT INTO CDType (CDType_id, CDType_type) VALUES (1,"Nothing");
INSERT INTO CDType (CDType_id, CDType_type) VALUES (10,"Servers-Win2K3");
INSERT INTO CDType (CDType_id, CDType_type) VALUES (20,"Servers-Linux");
INSERT INTO CDType (CDType_id, CDType_type) VALUES (30,"Router-Cisco");
INSERT INTO CDType (CDType_id, CDType_type) VALUES (40,"Switchs-Cisco");



/*
* DATABASE : Centreon2
* TABLE : OIDGroup
*	
*	Création de la table OIDGroup
*/
CREATE TABLE IF NOT EXISTS OIDGroup (OIDGroup_id INT UNSIGNED NOT NULL PRIMARY KEY, OIDGroup_type CHAR(100), OIDGroup_name CHAR(100), OIDGroup_path CHAR(100), OIDGroup_device INT UNSIGNED NOT NULL, FOREIGN KEY (OIDGroup_device) references CDType(CDType_id));
/* Windows */
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (101,"NIC","Interface Reseaux","1.3.6.1.2.1.2.2.1.1",10);
/*INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (102,"CPU","CPU","1.3.6.1.2.1.25.3.3.1.2.3",10);*/
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (103,"Disk","Partitions","1.3.6.1.2.1.25.2.3.1.1",10);
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (104,"Memory","Memoire","1.3.6.1.2.1.25.2.2",10);
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (105,"Process","Processus","1.3.6.1.2.1.25.4.2.1.1",10);
/* Linux */
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (201,"NIC","Interface Reseaux","1.3.6.1.2.1.2.2.1.1",20);
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (202,"CPU","CPU","1.3.6.1.4.1.2021.10.1.3",20);
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (203,"Disk","Partitions","1.3.6.1.2.1.25.2.3.1.1",20);
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (204,"Memory","Memoire","1.3.6.1.4.1.2021.4.11",20);
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (205,"Process","Processus","1.3.6.1.2.1.25.4.2.1.1",20);
/* Router-Cisco */
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (301,"NIC","Interface Reseaux","1.3.6.1.2.1.2.2.1.1",30);
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (302,"CPU","CPU","1.3.6.1.4.1.9.9.109.1.1.1.1.6.1",30);
/*Switch-Cisco */
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (401,"NIC","Interface Reseaux","1.3.6.1.2.1.2.2.1.1",40);
INSERT INTO OIDGroup (OIDGroup_id, OIDGroup_type, OIDGroup_name, OIDGroup_path, OIDGroup_device) VALUES (402,"CPU","CPU","1.3.6.1.4.1.9.9.109.1.1.1.1.6.1",40);



/*
* DATABASE : Centreon2
* TABLE : command
*	
*	Insertion des commandes
*/
/* Windows */

INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_interface_ping","$USER1$#S#check_teamreon_interface_ping-H$HOSTADDRESS$-n$ARG1$-w$ARG2$-c$ARG3$","!3!200,20%!400,50%",2,NULL,NULL);
/*INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_snmp_load_CPU_Window","$USER1$#S#check_centreon_snmp_cpu.pl -H $HOSTADDRESS$ -v 1 -C $ARG1$ -c $ARG2$ -w $ARG3$","!$USER2$!95!96!",2,NULL,NULL);*/
INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_snmp_storage_win","$USER1$#S#check_teamreon_snmp_storage_win.pl -H $HOSTADDRESS$ -n -d $ARG1$ -w $ARG2$ -c $ARG3$ -C $ARG4$ -v $ARG5$","!#S#!80!90!$USER2$!1",2,NULL,NULL);
INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_memory_win","$USER1$#S#check_teamreon_memory_win.pl -H $HOSTADDRESS$ -C $USER2$ -v 1 -w 80 -c 90"," ",2,NULL,NULL);
INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_process_win","$USER1$#S#check_teamreon_process_win.pl -H $HOSTADDRESS$ -C $ARG1$ -n $ARG2$","!$USER2$!explorer",2,NULL,NULL);
/* Linux */
INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_CPU_linux","$USER1$#S#check_teamreon_CPU_linux.pl -H $HOSTADDRESS$ -v $ARG1$ -C $ARG2$ -w $ARG3$ -c $ARG4$","!1!$USER2$!4,3,2!6,5,4",2,NULL,NULL);
INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_memory_linux","$USER1$#S#check_teamreon_memory_linux.pl -H $HOSTADDRESS$ -C $ARG1$ -v 1 -w $ARG2$ -c $ARG3$","",2,NULL,NULL);
INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_process_linux","$USER1$#S#check_teamreon_process_linux.pl -H $HOSTADDRESS$ -C $ARG1$ -n $ARG2$","!$USER2$!snmpd",2,NULL,NULL);
INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_snmp_storage_linux","$USER1$#S#check_teamreon_snmp_storage_linux.pl -H $HOSTADDRESS$ -n -d $ARG1$ -w $ARG2$ -c $ARG3$ -C $ARG4$ -v $ARG5$","!#S#!80!90!$USER2$!1",2,NULL,NULL);
/* Cisco Switch*/
INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_memory_cisco_switch","$USER1$#S#check_teamreon_memory_cisco_switch.pl -H $HOSTADDRESS$ -C $ARG1$ -w $ARG2$ -c $ARG3$ -I","!$USER2$!95!96!",2,NULL, NULL);
INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_interface_cisco_switch","$USER1$#S#check_teamreon_interface_cisco_switch.pl -H $HOSTADDRESS$ -C $ARG1$ -n $ARG2$ -r" ,"!$USER2$!&quot;FastEthernet0/1&quot;!",2,NULL,NULL);

/* Cisco Router*/
INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_memory_cisco_router","$USER1$#S#check_teamreon_memory_cisco_router.pl -H $HOSTADDRESS$ -C $ARG1$ -w $ARG2$ -c $ARG3$ -I","!$USER2$!95!96!",2,NULL, NULL);
INSERT INTO command (command_id,command_name,command_line,command_example,command_type,graph_id,cmd_cat_id) VALUES (NULL,"check_teamreon_interface_cisco_router","$USER1$#S#check_teamreon_interface_cisco_router.pl -H $HOSTADDRESS$ -C $ARG1$ -n $ARG2$ -r" ,"!$USER2$!&quot;FastEthernet0/0&quot;!",2,NULL,NULL);





/*
* DATABASE : Centreon2
* TABLE : service
*	
*	Creation de la table service
*/

/*
*  Windows 
*/
/*NIC*/
INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,1,(SELECT command_id FROM command WHERE command_name = 'check_teamreon_interface_ping' LIMIT 1),NULL,NULL,NULL,'Template_TeamReon_Win2K3_NIC','Ping de l&quot;interface',NULL,'2',NULL,NULL,NULL,'2','2',NULL,'2','2','2',NULL,'2',NULL,NULL,'2','2','2','2',NULL,NULL,'2',NULL,NULL,NULL,'!3!200,20%!400,50%',NULL,'0','1');
INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Win2K3_NIC' LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL);

/*DD*/
INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,1,(SELECT command_id FROM command WHERE command_name = 'check_teamreon_snmp_storage_win' LIMIT 1),NULL,NULL,NULL,'Template_TeamReon_Win2K3_Disk','Taux Utilisation de la partition',NULL,'2',NULL,NULL,NULL,'2','2',NULL,'2','2','2',NULL,'2',NULL,NULL,'2','2','2','2',NULL,NULL,'2',NULL,NULL,NULL,'!C!80!90!$USER2$!1',NULL,'0','1');
INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Win2K3_Disk' LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL);


/* CPU */
/* INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,1,(SELECT command_id FROM command WHERE command_name = 'check_teamreon_snmp_load_CPU_Window' LIMIT 1),NULL,NULL,NULL,'Template_TeamReon_Win2K3_CPU','Taux Utilisation CPU',NULL,'2',NULL,NULL,NULL,'2','2',NULL,'2','2','2',NULL,'2',NULL,NULL,'2','2','2','2',NULL,NULL,'2',NULL,NULL,NULL,'!$USER2$!95!96!',NULL,'0','1');*/
/* INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Win2K3_CPU' LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL);*/

/*memory*/
INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,1,(SELECT command_id FROM command WHERE command_name = 'check_teamreon_memory_win' LIMIT 1),NULL,NULL,NULL,'Template_TeamReon_Win2K3_Memory','Taux Utilisation Memoire (Used, RAM, Swap)',NULL,'2',NULL,NULL,NULL,'2','2',NULL,'2','2','2',NULL,'2',NULL,NULL,'2','2','2','2',NULL,NULL,'2',NULL,NULL,NULL,' ',NULL,'0','1');
INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Win2K3_Memory' LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL);

/*process */
INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,1,(SELECT command_id FROM command WHERE command_name = 'check_teamreon_process_win' LIMIT 1),NULL,NULL,NULL,'Template_TeamReon_Win2K3_Process','Etat du processus',NULL,'2',NULL,NULL,NULL,'2','2',NULL,'2','2','2',NULL,'2',NULL,NULL,'2','2','2','2',NULL,NULL,'2',NULL,NULL,NULL,'!$USER2$!explorer',NULL,'0','1');
INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Win2K3_Process' LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL);


/*
*  Linux
*/
/*CPU*/
INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,1,(SELECT command_id FROM command WHERE command_name = 'check_teamreon_CPU_linux' LIMIT 1),NULL,NULL,NULL,'Template_TeamReon_Linux_CPU','Taux Utilisation CPU',NULL,'2',NULL,NULL,NULL,'2','2',NULL,'2','2','2',NULL,'2',NULL,NULL,'2','2','2','2',NULL,NULL,'2',NULL,NULL,NULL,'!1!$USER2$!4,3,2!6,5,4',NULL,'0','1');
INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Linux_CPU' LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL);

/*memory*/
INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,1,(SELECT command_id FROM command WHERE command_name = 'check_teamreon_memory_linux' LIMIT 1),NULL,NULL,NULL,'Template_TeamReon_Linux_Memory','Taux Utilisation Memoire (Used, RAM, Swap)',NULL,'2',NULL,NULL,NULL,'2','2',NULL,'2','2','2',NULL,'2',NULL,NULL,'2','2','2','2',NULL,NULL,'2',NULL,NULL,NULL,'',NULL,'0','1');
INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Linux_Memory' LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL);

/*DD*/
INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,1,(SELECT command_id FROM command WHERE command_name = 'check_teamreon_snmp_storage_linux' LIMIT 1),NULL,NULL,NULL,'Template_TeamReon_Linux_Disk','Taux Utilisation de la partition',NULL,'2',NULL,NULL,NULL,'2','2',NULL,'2','2','2',NULL,'2',NULL,NULL,'2','2','2','2',NULL,NULL,'2',NULL,NULL,NULL,'!/!80!90!$USER2$!1',NULL,'0','1');
INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Linux_Disk' LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL);

/*process */
INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,1,(SELECT command_id FROM command WHERE command_name = 'check_teamreon_process_linux' LIMIT 1),NULL,NULL,NULL,'Template_TeamReon_Linux_Process','Etat du processus',NULL,'2',NULL,NULL,NULL,'2','2',NULL,'2','2','2',NULL,'2',NULL,NULL,'2','2','2','2',NULL,NULL,'2',NULL,NULL,NULL,'!$USER2$!snmpd',NULL,'0','1');
INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Linux_Process' LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL);

/*
*  Cisco switch
*/
/*CPU*/
INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,1,(SELECT command_id FROM command WHERE command_name = 'check_teamreon_memory_cisco_switch' LIMIT 1),NULL,NULL,NULL,'Template_TeamReon_Cisco_CPU','Taux Utilisation CPU',NULL,'2',NULL,NULL,NULL,'2','2',NULL,'2','2','2',NULL,'2',NULL,NULL,'2','2','2','2',NULL,NULL,'2',NULL,NULL,NULL,'!$USER2$!95!96!',NULL,'0','1');
INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Cisco_CPU' LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL);

/*NIC*/
INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,1,(SELECT command_id FROM command WHERE command_name = 'check_teamreon_interface_cisco_switch' LIMIT 1),NULL,NULL,NULL,'Template_TeamReon_Cisco_NIC','Etat de l&quot;interface',NULL,'2',NULL,NULL,NULL,'2','2',NULL,'2','2','2',NULL,'2',NULL,NULL,'2','2','2','2',NULL,NULL,'2',NULL,NULL,NULL,'!$USER2$!&quot;FastEthernet0#S#1&quot;!',NULL,'0','1');
INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Cisco_NIC' LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL);



/*
*  Cisco Router
*/
/*CPU*/
INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,1,(SELECT command_id FROM command WHERE command_name = 'check_teamreon_memory_cisco_router' LIMIT 1),NULL,NULL,NULL,'Template_TeamReon_Cisco_router_CPU','Taux Utilisation CPU',NULL,'2',NULL,NULL,NULL,'2','2',NULL,'2','2','2',NULL,'2',NULL,NULL,'2','2','2','2',NULL,NULL,'2',NULL,NULL,NULL,'!$USER2$!95!96!',NULL,'0','1');
INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Cisco_router_CPU' LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL);

/*NIC*/
INSERT INTO service (service_id,service_template_model_stm_id,command_command_id,timeperiod_tp_id,command_command_id2,timeperiod_tp_id2,service_description,service_alias,display_name,service_is_volatile,service_max_check_attempts,service_normal_check_interval,service_retry_check_interval,service_active_checks_enabled,service_passive_checks_enabled,initial_state,service_parallelize_check,service_obsess_over_service,service_check_freshness,service_freshness_threshold,service_event_handler_enabled,service_low_flap_threshold,service_high_flap_threshold,service_flap_detection_enabled,service_process_perf_data,service_retain_status_information,service_retain_nonstatus_information,service_notification_interval,service_notification_options,service_notifications_enabled,service_first_notification_delay,service_stalking_options,service_comment,command_command_id_arg,command_command_id_arg2,service_register,service_activate) VALUES (NULL,1,(SELECT command_id FROM command WHERE command_name = 'check_teamreon_interface_cisco_router' LIMIT 1),NULL,NULL,NULL,'Template_TeamReon_Cisco_NIC_router','Etat de l&quot;interface',NULL,'2',NULL,NULL,NULL,'2','2',NULL,'2','2','2',NULL,'2',NULL,NULL,'2','2','2','2',NULL,NULL,'2',NULL,NULL,NULL,'!$USER2$!&quot;FastEthernet0#S#1&quot;!',NULL,'0','1');
INSERT INTO extended_service_information (esi_id,service_service_id,esi_notes,esi_notes_url,esi_action_url,esi_icon_image,esi_icon_image_alt,graph_id) VALUES (NULL,(SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Cisco_NIC_router' LIMIT 1),NULL,NULL,NULL,NULL,NULL,NULL);



/*
* DATABASE : Centreon2
* TABLE : CommandOID
*	
*	Creation de la table CommandOID
*/
CREATE TABLE IF NOT EXISTS ServiceOID (ServiceOID_id INT UNSIGNED NOT NULL, OIDGroup_id_id INT UNSIGNED NOT NULL, FOREIGN KEY (ServiceOID_id) REFERENCES service(service_id), FOREIGN KEY (OIDGroup_id_id) REFERENCES OIDGroup(OIDGroup_id));
/* Windows */
/*INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Win2K3_NIC' LIMIT 1),101);*/
/*INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Win2K3_CPU' LIMIT 1),102);*/
INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Win2K3_Disk' LIMIT 1),103);
INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Win2K3_Memory' LIMIT 1),104);
INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Win2K3_Process' LIMIT 1),105);
/* Linux */
INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Linux_CPU' LIMIT 1),202);
INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Linux_Memory' LIMIT 1),204);
INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Linux_Process' LIMIT 1),205);
INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Linux_Disk' LIMIT 1),206);

/* Router-Cisco */
INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Cisco_NIC_router' LIMIT 1),301);
INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Cisco_router_CPU' LIMIT 1),302);
/*Switch-Cisco */
INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Cisco_NIC' LIMIT 1),401);
INSERT INTO ServiceOID (ServiceOID_id, OIDGroup_id_id) VALUES ((SELECT service_id FROM service WHERE service_description = 'Template_TeamReon_Cisco_CPU' LIMIT 1),402);


