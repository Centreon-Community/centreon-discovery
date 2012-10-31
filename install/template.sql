/* This file is part of Centreon-Discovery module.
 *
 * Centreon-Discovery is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, see <http://www.gnu.org/licenses>.
 *
 * Module name: Centreon-Discovery
 *
 * Developped by: Sub2.13
 *
 * WEBSITE: http://community.centreon.com/projects/centreon-discovery
 * SVN: http://svn.modules.centreon.com/centreon-discovery
 *
 * Command : mysql -u root --password=admin centreon < install/template.sql > /dev/null
 *
 */

/* ************************************************************************************************* 
 * -------------------------------------------------------------------------------------------------
 *
 * DATABASE: @DB_NAME_CENTREON@
 * TABLE: command / command_arg_description
 *	
 * Create generic commands
 *
 * -------------------------------------------------------------------------------------------------
 * ************************************************************************************************* */
  
 /* Command to monitor CPU/LOAD */
INSERT INTO `command` (`command_name`, `command_line`, `command_example`, `command_type`, `graph_id`) VALUES ('discovery_load', '$USER1$/discovery/check_snmp_load.pl -H $HOSTADDRESS$ -C $_HOSTSNMPCOMMUNITY$ -2 -T $ARG1$ -w $ARG2$ -c $ARG3$ -f', '!cisco!50,40,30!80,70,60', '2', '');
SELECT @newCmdId := MAX(command_id) FROM command;
DELETE FROM `command_arg_description` WHERE cmd_id = '@newCmdId';
INSERT INTO `command_arg_description` (cmd_id, macro_name, macro_description) VALUES (@newCmdId, 'ARG1', 'type : stand, netsl, netsc, as400, cisco, cata, etc\r'),(@newCmdId, 'ARG2', 'Warning for 5s (or 1min), Warning for 1min (or 5min), Warning for 5min (or 15min)\r'),(@newCmdId, 'ARG3', 'Critical for 5s (or 1min), Critical for 1min (or 5min), Critical for 5min (or 15min)');

/* ------------------------------------------------------------------------------------------------- */

/* Command to monitor Environment */
INSERT INTO `command` (`command_name`, `command_line`, `command_example`, `command_type`, `graph_id`) VALUES ('discovery_env', '$USER1$/discovery/check_snmp_env.pl -H $HOSTADDRESS$ -C $_HOSTSNMPCOMMUNITY$ -2 -T $ARG1 -f', '!cisco', '2', '');
SELECT @newCmdId := MAX(command_id) FROM command;
DELETE FROM `command_arg_description` WHERE cmd_id = '@newCmdId';
INSERT INTO `command_arg_description` (cmd_id, macro_name, macro_description) VALUES (@newCmdId, 'ARG1', 'type : cisco / nokia / bc / iron / foundry');

/* ------------------------------------------------------------------------------------------------- */
 
/* Command to monitor Disk */
INSERT INTO `command` (`command_name`, `command_line`, `command_example`, `command_type`, `graph_id`) VALUES ('discovery_disk', '$USER1$/discovery/check_centreon_snmp_remote_storage.pl -H $HOSTADDRESS$ -C $_HOSTSNMPCOMMUNITY$ -v 2 -d "$ARG1$" -n -w $ARG2$ -c $ARG3$', '!C:!80!90', '2', '');
SELECT @newCmdId := MAX(command_id) FROM command;
DELETE FROM `command_arg_description` WHERE cmd_id = '@newCmdId';
INSERT INTO `command_arg_description` (cmd_id, macro_name, macro_description) VALUES (@newCmdId, 'ARG1', 'disk name : "C:" / "/" / ...'),(@newCmdId, 'ARG2', 'warning level'),(@newCmdId, 'ARG3', 'critical level'); 

/* ------------------------------------------------------------------------------------------------- */

/* Command to monitor Memory */
INSERT INTO `command` (`command_name`, `command_line`, `command_example`, `command_type`, `graph_id`) VALUES ('discovery_memory', '$USER1$/discovery/check_centreon_snmp_memory.pl -H $HOSTADDRESS$ -C $_HOSTSNMPCOMMUNITY$ -v 2 -w $ARG1$ -c $ARG2$', '!80!90', '2', '');
SELECT @newCmdId := MAX(command_id) FROM command;
DELETE FROM `command_arg_description` WHERE cmd_id = '@newCmdId';
INSERT INTO `command_arg_description` (cmd_id, macro_name, macro_description) VALUES (@newCmdId, 'ARG1', 'warning level'),(@newCmdId, 'ARG2', 'critical level'); 

/* Command to monitor Memory on Cisco equipment */
INSERT INTO `command` (`command_name`, `command_line`, `command_example`, `command_type`, `graph_id`) VALUES ('discovery_memory_cisco', '$USER1$/discovery/check_snmp_mem.pl -H $HOSTADDRESS$ -C $_HOSTSNMPCOMMUNITY$ -2 -I -w $ARG1$ -c $ARG2$ -f', '!80!90', '2', '');
SELECT @newCmdId := MAX(command_id) FROM command;
DELETE FROM `command_arg_description` WHERE cmd_id = '@newCmdId';
INSERT INTO `command_arg_description` (cmd_id, macro_name, macro_description) VALUES (@newCmdId, 'ARG1', 'warning level'), (@newCmdId, 'ARG2', 'critical level');
/* ------------------------------------------------------------------------------------------------- */

/* Command to monitor process */
INSERT INTO `command` (`command_name`, `command_line`, `command_example`, `command_type`, `graph_id`) VALUES ('discovery_proc', '$USER1$/discovery/check_centreon_snmp_process.pl -H $HOSTADDRESS$ -C $_HOSTSNMPCOMMUNITY$ -v 2 -p $ARG1$', '!sshd', '2', '');
SELECT @newCmdId := MAX(command_id) FROM command;
DELETE FROM `command_arg_description` WHERE cmd_id = '@newCmdId';
INSERT INTO `command_arg_description` (cmd_id, macro_name, macro_description) VALUES (@newCmdId, 'ARG1', 'process name\r');

/* ------------------------------------------------------------------------------------------------- */

/* Command to monitor Bandwidth by interface name */
INSERT INTO `command` (`command_name`, `command_line`, `command_example`, `command_type`, `graph_id`) VALUES ('discovery_bandwidth_by_name', '$USER1$/discovery/check_centreon_snmp_traffic.pl -H $HOSTADDRESS$ -C $_HOSTSNMPCOMMUNITY$ -v 2 -i $ARG1$ -n -w $ARG2$ -c $ARG3$ -S', '!FastEthernet0/1!80!90', '2', '');
SELECT @newCmdId := MAX(command_id) FROM command;
DELETE FROM `command_arg_description` WHERE cmd_id = '@newCmdId';
INSERT INTO `command_arg_description` (cmd_id, macro_name, macro_description) VALUES (@newCmdId, 'ARG1', 'interface name'),(@newCmdId, 'ARG2', 'warning bandwidth'),(@newCmdId, 'ARG3', 'critical bandwidth');

/* ------------------------------------------------------------------------------------------------- */

/* Command to monitor Bandwidth by interface number */
INSERT INTO `command` (`command_name`, `command_line`, `command_example`, `command_type`, `graph_id`) VALUES ('discovery_bandwidth_by_number', '$USER1$/discovery/check_centreon_snmp_traffic.pl -H $HOSTADDRESS$ -C $_HOSTSNMPCOMMUNITY$ -v 2 -i $ARG1$ -w $ARG2$ -c $ARG3$ -S', '!1!80!90', '2', '');
SELECT @newCmdId := MAX(command_id) FROM command;
DELETE FROM `command_arg_description` WHERE cmd_id = '@newCmdId';
INSERT INTO `command_arg_description` (cmd_id, macro_name, macro_description) VALUES (@newCmdId, 'ARG1', 'interface number'),(@newCmdId, 'ARG2', 'warning bandwidth'),(@newCmdId, 'ARG3', 'critical bandwidth');

/* ------------------------------------------------------------------------------------------------- */

/* Command to monitor service on Windows server */
INSERT INTO `command` (`command_name`, `command_line`, `command_example`, `command_type`, `graph_id`) VALUES ('discovery_win_service', '$USER1$/discovery/check_snmp_win.pl -H $HOSTADDRESS$ -C $_HOSTSNMPCOMMUNITY$ -2 -T service -n "$ARG1$" -f', '!Client DNS', '2', '');
SELECT @newCmdId := MAX(command_id) FROM command;
DELETE FROM `command_arg_description` WHERE cmd_id = '@newCmdId';
INSERT INTO `command_arg_description` (cmd_id, macro_name, macro_description) VALUES (@newCmdId, 'ARG1', 'service name (complete name)');
 
/* *************************************************************************************************
 * -------------------------------------------------------------------------------------------------
 *
 * DATABASE: @DB_NAME_CENTREON@
 * TABLE: host / command / command_arg_description / service / host_service_relation / servicegroup_relation / extended_service_information
 *	
 * Create template
 *
 * -------------------------------------------------------------------------------------------------
 * ************************************************************************************************* */


/* ---------------------------- *
 * Template for Cisco switch    *
 * -----------------------------*/
 
INSERT INTO host (host_template_model_htm_id, command_command_id, command_command_id_arg1, timeperiod_tp_id, timeperiod_tp_id2, command_command_id2, command_command_id_arg2,host_name, host_alias, host_address, host_max_check_attempts, host_check_interval, host_retry_check_interval, host_active_checks_enabled, host_passive_checks_enabled, host_checks_enabled, host_obsess_over_host, host_check_freshness, host_freshness_threshold, host_event_handler_enabled, host_low_flap_threshold, host_high_flap_threshold, host_flap_detection_enabled, host_process_perf_data, host_retain_status_information, host_retain_nonstatus_information, host_notification_interval, host_first_notification_delay, host_notification_options, host_notifications_enabled, host_stalking_options, host_snmp_community, host_snmp_version, host_location, host_comment, host_register, host_activate) VALUES ( '2', NULL, NULL, NULL, NULL, NULL, NULL, 'Discovery-Cisco-Switch', 'Model for Cisco switch from Discovery module', NULL, NULL, NULL, NULL, '2', '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, NULL, '2', NULL, NULL, '0', NULL, NULL, '0', '1');
SELECT @newHostId := MAX(host_id) FROM host;
SELECT @genTemplateId := host_id FROM host where ( host_name = "generic-host" );
INSERT INTO host_template_relation (`host_host_id`, `host_tpl_id`, `order`) VALUES (@newHostId, @genTemplateId, 1);
DELETE FROM host_hostparent_relation WHERE host_host_id = '@newHostId';
DELETE FROM host_hostparent_relation WHERE host_parent_hp_id = '@newHostId';
DELETE FROM contactgroup_host_relation WHERE host_host_id = '@newHostId';
DELETE FROM contact_host_relation WHERE host_host_id = '@newHostId';
DELETE FROM hostgroup_relation WHERE host_host_id = '@newHostId';
DELETE FROM host_service_relation WHERE host_host_id = '@newHostId';
UPDATE `acl_resources` SET `changed` = '1';
INSERT INTO `extended_host_information` ( `ehi_id` , `host_host_id` , `ehi_notes` , `ehi_notes_url` , `ehi_action_url` , `ehi_icon_image` , `ehi_icon_image_alt` , `ehi_vrml_image` , `ehi_statusmap_image` , `ehi_2d_coords` , `ehi_3d_coords` )VALUES ( NULL, @newHostId, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL );

/*
 * Load on Cisco switch
 *
 * ./check_snmp_load.pl -H 192.168.0.94 -C public -w 40,30,20 -c 60,50,40 -T cisco -f
 * CPU : 1 1 1 : OK | load_5_sec=1%;40;60 load_1_min=1%;30;50 load_5_min=1%;20;40
 */
 
/* Command to monitor CPU on Cisco switch */
SELECT @newCmdId := command_id FROM command where command_name = 'discovery_load';

/* Relation between Template and Command for Cisco switch */
INSERT INTO service (service_template_model_stm_id, command_command_id, timeperiod_tp_id, command_command_id2, timeperiod_tp_id2, service_description, service_alias, service_is_volatile, service_max_check_attempts, service_normal_check_interval, service_retry_check_interval, service_active_checks_enabled, service_passive_checks_enabled, service_obsess_over_service, service_check_freshness, service_freshness_threshold, service_event_handler_enabled, service_low_flap_threshold, service_high_flap_threshold, service_flap_detection_enabled, service_process_perf_data, service_retain_status_information, service_retain_nonstatus_information, service_notification_interval, service_notification_options, service_notifications_enabled, service_stalking_options, service_first_notification_delay ,service_comment, command_command_id_arg, command_command_id_arg2, service_register, service_activate) VALUES ( '1', @newCmdId, NULL, NULL, NULL, 'Discovery_Cisco_sw_load', 'Cisco_sw_load', '2', NULL, NULL, NULL, '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, '2', NULL, NULL, NULL, '!cisco!50,40,30!80,70,60', NULL, '0', '1');
SELECT @newServiceId := MAX(service_id) FROM service;
INSERT INTO host_service_relation (hostgroup_hg_id, host_host_id, servicegroup_sg_id, service_service_id) VALUES (NULL, @newHostId, NULL, @newServiceId);
DELETE FROM servicegroup_relation WHERE service_service_id = '@newServiceId';
INSERT INTO `extended_service_information` ( `esi_id` , `service_service_id`, `esi_notes` , `esi_notes_url` , `esi_action_url` , `esi_icon_image` , `esi_icon_image_alt`, `graph_id` )VALUES ( NULL, @newServiceId, NULL, NULL, NULL, NULL, NULL, NULL);

/*
 * Bandwidth on Cisco switch (disabled)
 *
 * ./check_centreon_snmp_traffic -H 192.168.0.94 -C public -i "FastEthernet0/21" -n -w 80 -c 90 -S
 * Traffic In : 2.09 kb/s (0.0 %), Out : 1.50 kb/s (0.0 %)  - Link Speed : 100000000|traffic_in=2090,7Bits/s;80000000;90000000;0;100000000 traffic_out=1501,3Bits/s;80000000;90000000;0;100000000 
 */
 
/* Command to monitor Bandwidth on Cisco switch */
SELECT @newCmdId := command_id FROM command where command_name = 'discovery_bandwidth_by_name';

/* Relation between Template and Command for Cisco switch */
INSERT INTO service (service_template_model_stm_id, command_command_id, timeperiod_tp_id, command_command_id2, timeperiod_tp_id2, service_description, service_alias, service_is_volatile, service_max_check_attempts, service_normal_check_interval, service_retry_check_interval, service_active_checks_enabled, service_passive_checks_enabled, service_obsess_over_service, service_check_freshness, service_freshness_threshold, service_event_handler_enabled, service_low_flap_threshold, service_high_flap_threshold, service_flap_detection_enabled, service_process_perf_data, service_retain_status_information, service_retain_nonstatus_information, service_notification_interval, service_notification_options, service_notifications_enabled, service_stalking_options, service_first_notification_delay ,service_comment, command_command_id_arg, command_command_id_arg2, service_register, service_activate) VALUES ( '1', @newCmdId, NULL, NULL, NULL, 'Discovery_Cisco_sw_bandwidth', 'Cisco_sw_bandwidth', '2', NULL, NULL, NULL, '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, '2', NULL, NULL, NULL, '!FastEthernet0/1!80!90', NULL, '0', '1');
SELECT @newServiceId := MAX(service_id) FROM service;
INSERT INTO host_service_relation (hostgroup_hg_id, host_host_id, servicegroup_sg_id, service_service_id) VALUES (NULL, @newHostId, NULL, @newServiceId);
DELETE FROM servicegroup_relation WHERE service_service_id = '@newServiceId';
INSERT INTO `extended_service_information` ( `esi_id` , `service_service_id`, `esi_notes` , `esi_notes_url` , `esi_action_url` , `esi_icon_image` , `esi_icon_image_alt`, `graph_id` )VALUES ( NULL, @newServiceId, NULL, NULL, NULL, NULL, NULL, NULL);

/*
 *  Environment on Cisco switch
 *
 * ./check_snmp_env.pl -H 192.168.0.94 -C public -T cisco -f
 * 1 Fan OK, 1 ps OK, temp chassis:notPresent  : OK
 */

 /* OK */

/*
 * Memory on Cisco switch
 *
 * ./check_snmp_mem.pl -H 192.168.0.94 -C public -2 -I -w 80% -c 90% -f
 * Processor:62%,I/O:33% : 52% :  ; OK | ram_used=3139728;5458046;5943206;0;6064496
 */
 
/* Command to monitor Memory on Cisco switch */
SELECT @newCmdId := command_id FROM command where command_name = 'discovery_memory_cisco';

/* Relation between Template and Command for Cisco switch */
INSERT INTO service (service_template_model_stm_id, command_command_id, timeperiod_tp_id, command_command_id2, timeperiod_tp_id2, service_description, service_alias, service_is_volatile, service_max_check_attempts, service_normal_check_interval, service_retry_check_interval, service_active_checks_enabled, service_passive_checks_enabled, service_obsess_over_service, service_check_freshness, service_freshness_threshold, service_event_handler_enabled, service_low_flap_threshold, service_high_flap_threshold, service_flap_detection_enabled, service_process_perf_data, service_retain_status_information, service_retain_nonstatus_information, service_notification_interval, service_notification_options, service_notifications_enabled, service_stalking_options, service_first_notification_delay ,service_comment, command_command_id_arg, command_command_id_arg2, service_register, service_activate) VALUES ( '1', @newCmdId, NULL, NULL, NULL, 'Discovery_Cisco_sw_mem', 'Cisco_sw_mem', '2', NULL, NULL, NULL, '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, '2', NULL, NULL, NULL, '!80!90', NULL, '0', '1');
SELECT @newServiceId := MAX(service_id) FROM service;
INSERT INTO host_service_relation (hostgroup_hg_id, host_host_id, servicegroup_sg_id, service_service_id) VALUES (NULL, @newHostId, NULL, @newServiceId);
DELETE FROM servicegroup_relation WHERE service_service_id = '@newServiceId';
INSERT INTO `extended_service_information` ( `esi_id` , `service_service_id`, `esi_notes` , `esi_notes_url` , `esi_action_url` , `esi_icon_image` , `esi_icon_image_alt`, `graph_id` )VALUES ( NULL, @newServiceId, NULL, NULL, NULL, NULL, NULL, NULL);

/* ---------------------------- *
 * Template for Cisco routeur   *
 * -----------------------------*/

INSERT INTO host (host_template_model_htm_id, command_command_id, command_command_id_arg1, timeperiod_tp_id, timeperiod_tp_id2, command_command_id2, command_command_id_arg2,host_name, host_alias, host_address, host_max_check_attempts, host_check_interval, host_retry_check_interval, host_active_checks_enabled, host_passive_checks_enabled, host_checks_enabled, host_obsess_over_host, host_check_freshness, host_freshness_threshold, host_event_handler_enabled, host_low_flap_threshold, host_high_flap_threshold, host_flap_detection_enabled, host_process_perf_data, host_retain_status_information, host_retain_nonstatus_information, host_notification_interval, host_first_notification_delay, host_notification_options, host_notifications_enabled, host_stalking_options, host_snmp_community, host_snmp_version, host_location, host_comment, host_register, host_activate) VALUES ( '2', NULL, NULL, NULL, NULL, NULL, NULL, 'Discovery-Cisco-Router', 'Model for Cisco router from Discovery module', NULL, NULL, NULL, NULL, '2', '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, NULL, '2', NULL, NULL, '0', NULL, NULL, '0', '1');
SELECT @newHostId := MAX(host_id) FROM host;
SELECT @genTemplateId := host_id FROM host where ( host_name = "generic-host" );
INSERT INTO host_template_relation (`host_host_id`, `host_tpl_id`, `order`) VALUES (@newHostId, @genTemplateId, 1);
DELETE FROM host_hostparent_relation WHERE host_host_id = '@newHostId';
DELETE FROM host_hostparent_relation WHERE host_parent_hp_id = '@newHostId';
DELETE FROM contactgroup_host_relation WHERE host_host_id = '@newHostId';
DELETE FROM contact_host_relation WHERE host_host_id = '@newHostId';
DELETE FROM hostgroup_relation WHERE host_host_id = '@newHostId';
DELETE FROM host_service_relation WHERE host_host_id = '@newHostId';
UPDATE `acl_resources` SET `changed` = '1';
INSERT INTO `extended_host_information` ( `ehi_id` , `host_host_id` , `ehi_notes` , `ehi_notes_url` , `ehi_action_url` , `ehi_icon_image` , `ehi_icon_image_alt` , `ehi_vrml_image` , `ehi_statusmap_image` , `ehi_2d_coords` , `ehi_3d_coords` )VALUES ( NULL, @newHostId, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL );

/*
 * Load on Cisco router
 *
 * ./check_snmp_load.pl -H 192.168.0.94 -C public -w 40,30,20 -c 60,50,40 -T cisco -f
 * CPU : 1 1 1 : OK | load_5_sec=1%;40;60 load_1_min=1%;30;50 load_5_min=1%;20;40
 */
 
/* Command to monitor CPU on Cisco router */
SELECT @newCmdId := command_id FROM command where command_name = 'discovery_load';

/* Relation between Template and Command for Cisco router */
INSERT INTO service (service_template_model_stm_id, command_command_id, timeperiod_tp_id, command_command_id2, timeperiod_tp_id2, service_description, service_alias, service_is_volatile, service_max_check_attempts, service_normal_check_interval, service_retry_check_interval, service_active_checks_enabled, service_passive_checks_enabled, service_obsess_over_service, service_check_freshness, service_freshness_threshold, service_event_handler_enabled, service_low_flap_threshold, service_high_flap_threshold, service_flap_detection_enabled, service_process_perf_data, service_retain_status_information, service_retain_nonstatus_information, service_notification_interval, service_notification_options, service_notifications_enabled, service_stalking_options, service_first_notification_delay ,service_comment, command_command_id_arg, command_command_id_arg2, service_register, service_activate) VALUES ( '1', @newCmdId, NULL, NULL, NULL, 'Discovery_Cisco_rt_load', 'Cisco_rt_load', '2', NULL, NULL, NULL, '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, '2', NULL, NULL, NULL, '!cisco!80,70,60!90,80,70', NULL, '0', '1');
SELECT @newServiceId := MAX(service_id) FROM service;
INSERT INTO host_service_relation (hostgroup_hg_id, host_host_id, servicegroup_sg_id, service_service_id) VALUES (NULL, @newHostId, NULL, @newServiceId);
DELETE FROM servicegroup_relation WHERE service_service_id = '@newServiceId';
INSERT INTO `extended_service_information` ( `esi_id` , `service_service_id`, `esi_notes` , `esi_notes_url` , `esi_action_url` , `esi_icon_image` , `esi_icon_image_alt`, `graph_id` )VALUES ( NULL, @newServiceId, NULL, NULL, NULL, NULL, NULL, NULL);

/*
 * Bandwidth on Cisco router (disabled)
 *
 * ./check_centreon_snmp_traffic -H 192.168.0.94 -C public -i "Ethernet0/0" -n -w 80 -c 90 -S
 * Traffic In : 40.13 b/s (0.0 %), Out : 70.67 b/s (0.0 %)  - Link Speed : 10000000|traffic_in=40,1Bits/s;8000000;9000000;0;10000000 traffic_out=70,7Bits/s;8000000;9000000;0;10000000 
 */
 
/* Command to monitor Bandwidth on Cisco router */
SELECT @newCmdId := command_id FROM command where command_name = 'discovery_bandwidth_by_name';

/* Relation between Template and Command for Cisco router */
INSERT INTO service (service_template_model_stm_id, command_command_id, timeperiod_tp_id, command_command_id2, timeperiod_tp_id2, service_description, service_alias, service_is_volatile, service_max_check_attempts, service_normal_check_interval, service_retry_check_interval, service_active_checks_enabled, service_passive_checks_enabled, service_obsess_over_service, service_check_freshness, service_freshness_threshold, service_event_handler_enabled, service_low_flap_threshold, service_high_flap_threshold, service_flap_detection_enabled, service_process_perf_data, service_retain_status_information, service_retain_nonstatus_information, service_notification_interval, service_notification_options, service_notifications_enabled, service_stalking_options, service_first_notification_delay ,service_comment, command_command_id_arg, command_command_id_arg2, service_register, service_activate) VALUES ( '1', @newCmdId, NULL, NULL, NULL, 'Discovery_Cisco_rt_bandwidth', 'Cisco_rt_bandwidth', '2', NULL, NULL, NULL, '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, '2', NULL, NULL, NULL, '!Ethernet0/0!80!90', NULL, '0', '1');
SELECT @newServiceId := MAX(service_id) FROM service;
INSERT INTO host_service_relation (hostgroup_hg_id, host_host_id, servicegroup_sg_id, service_service_id) VALUES (NULL, @newHostId, NULL, @newServiceId);
DELETE FROM servicegroup_relation WHERE service_service_id = '@newServiceId';
INSERT INTO `extended_service_information` ( `esi_id` , `service_service_id`, `esi_notes` , `esi_notes_url` , `esi_action_url` , `esi_icon_image` , `esi_icon_image_alt`, `graph_id` )VALUES ( NULL, @newServiceId, NULL, NULL, NULL, NULL, NULL, NULL);

/*
 * Environment on Cisco router
 *
 * ./check_snmp_env.pl -H 192.168.0.94 -C public -T cisco -f
 * 1 ps OK, 1 temp OK : OK
 */

 /* OK */

/*
 * Memory on Cisco router
 *
 * ./check_snmp_mem.pl -H 192.168.0.94 -C public -2 -I -w 80% -c 90% -f
 * Processor:42%,I/O:44% : 43% :  ; OK | ram_used=5891736;12419068;13522985;0;13798964
 */
 
/* Command to monitor Memory on Cisco routeur */
SELECT @newCmdId := command_id FROM command where command_name = 'discovery_memory_cisco';

/* Relation between Template and Command for Cisco routeur */
INSERT INTO service (service_template_model_stm_id, command_command_id, timeperiod_tp_id, command_command_id2, timeperiod_tp_id2, service_description, service_alias, service_is_volatile, service_max_check_attempts, service_normal_check_interval, service_retry_check_interval, service_active_checks_enabled, service_passive_checks_enabled, service_obsess_over_service, service_check_freshness, service_freshness_threshold, service_event_handler_enabled, service_low_flap_threshold, service_high_flap_threshold, service_flap_detection_enabled, service_process_perf_data, service_retain_status_information, service_retain_nonstatus_information, service_notification_interval, service_notification_options, service_notifications_enabled, service_stalking_options, service_first_notification_delay ,service_comment, command_command_id_arg, command_command_id_arg2, service_register, service_activate) VALUES ( '1', @newCmdId, NULL, NULL, NULL, 'Discovery_Cisco_rt_mem', 'Cisco_rt_mem', '2', NULL, NULL, NULL, '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, '2', NULL, NULL, NULL, '!80!90', NULL, '0', '1');
SELECT @newServiceId := MAX(service_id) FROM service;
INSERT INTO host_service_relation (hostgroup_hg_id, host_host_id, servicegroup_sg_id, service_service_id) VALUES (NULL, @newHostId, NULL, @newServiceId);
DELETE FROM servicegroup_relation WHERE service_service_id = '@newServiceId';
INSERT INTO `extended_service_information` ( `esi_id` , `service_service_id`, `esi_notes` , `esi_notes_url` , `esi_action_url` , `esi_icon_image` , `esi_icon_image_alt`, `graph_id` )VALUES ( NULL, @newServiceId, NULL, NULL, NULL, NULL, NULL, NULL);

/* ---------------------------- *
 * Template for Cisco fw ASA    *
 * -----------------------------*/

INSERT INTO host (host_template_model_htm_id, command_command_id, command_command_id_arg1, timeperiod_tp_id, timeperiod_tp_id2, command_command_id2, command_command_id_arg2,host_name, host_alias, host_address, host_max_check_attempts, host_check_interval, host_retry_check_interval, host_active_checks_enabled, host_passive_checks_enabled, host_checks_enabled, host_obsess_over_host, host_check_freshness, host_freshness_threshold, host_event_handler_enabled, host_low_flap_threshold, host_high_flap_threshold, host_flap_detection_enabled, host_process_perf_data, host_retain_status_information, host_retain_nonstatus_information, host_notification_interval, host_first_notification_delay, host_notification_options, host_notifications_enabled, host_stalking_options, host_snmp_community, host_snmp_version, host_location, host_comment, host_register, host_activate) VALUES ( '2', NULL, NULL, NULL, NULL, NULL, NULL, 'Discovery-Cisco-FW-ASA', 'Model for Cisco firewall ASA from Discovery module', NULL, NULL, NULL, NULL, '2', '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, NULL, '2', NULL, NULL, '0', NULL, NULL, '0', '1');
SELECT @newHostId := MAX(host_id) FROM host;
SELECT @genTemplateId := host_id FROM host where ( host_name = "generic-host" );
INSERT INTO host_template_relation (`host_host_id`, `host_tpl_id`, `order`) VALUES (@newHostId, @genTemplateId, 1);
DELETE FROM host_hostparent_relation WHERE host_host_id = '@newHostId';
DELETE FROM host_hostparent_relation WHERE host_parent_hp_id = '@newHostId';
DELETE FROM contactgroup_host_relation WHERE host_host_id = '@newHostId';
DELETE FROM contact_host_relation WHERE host_host_id = '@newHostId';
DELETE FROM hostgroup_relation WHERE host_host_id = '@newHostId';
DELETE FROM host_service_relation WHERE host_host_id = '@newHostId';
UPDATE `acl_resources` SET `changed` = '1';
INSERT INTO `extended_host_information` ( `ehi_id` , `host_host_id` , `ehi_notes` , `ehi_notes_url` , `ehi_action_url` , `ehi_icon_image` , `ehi_icon_image_alt` , `ehi_vrml_image` , `ehi_statusmap_image` , `ehi_2d_coords` , `ehi_3d_coords` )VALUES ( NULL, @newHostId, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL );

/*
 * Load on Cisco firewall ASA
 *
 * ./check_snmp_load.pl -H 192.168.199.1 -C public -w 40,30,20 -c 60,50,40 -T asa -f
 * CPU : 0 0 0 : OK | load_5_sec=0%;40;60 load_1_min=0%;30;50 load_5_min=0%;20;40
 */
 
/* Command to monitor CPU on Cisco firewall */
SELECT @newCmdId := command_id FROM command where command_name = 'discovery_load';

/* Relation between Template and Command for Cisco firewall */
INSERT INTO service (service_template_model_stm_id, command_command_id, timeperiod_tp_id, command_command_id2, timeperiod_tp_id2, service_description, service_alias, service_is_volatile, service_max_check_attempts, service_normal_check_interval, service_retry_check_interval, service_active_checks_enabled, service_passive_checks_enabled, service_obsess_over_service, service_check_freshness, service_freshness_threshold, service_event_handler_enabled, service_low_flap_threshold, service_high_flap_threshold, service_flap_detection_enabled, service_process_perf_data, service_retain_status_information, service_retain_nonstatus_information, service_notification_interval, service_notification_options, service_notifications_enabled, service_stalking_options, service_first_notification_delay ,service_comment, command_command_id_arg, command_command_id_arg2, service_register, service_activate) VALUES ( '1', @newCmdId, NULL, NULL, NULL, 'Discovery_Cisco_fw_asa_load', 'Cisco_fw_asa_load', '2', NULL, NULL, NULL, '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, '2', NULL, NULL, NULL, '!cisco!70,60,50!80,70,60', NULL, '0', '1');
SELECT @newServiceId := MAX(service_id) FROM service;
INSERT INTO host_service_relation (hostgroup_hg_id, host_host_id, servicegroup_sg_id, service_service_id) VALUES (NULL, @newHostId, NULL, @newServiceId);
DELETE FROM servicegroup_relation WHERE service_service_id = '@newServiceId';
INSERT INTO `extended_service_information` ( `esi_id` , `service_service_id`, `esi_notes` , `esi_notes_url` , `esi_action_url` , `esi_icon_image` , `esi_icon_image_alt`, `graph_id` )VALUES ( NULL, @newServiceId, NULL, NULL, NULL, NULL, NULL, NULL);

/*
 * Bandwidth on Cisco firewall
 *
 * ./check_centreon_snmp_traffic -H 192.168.199.1 -C public -i "Adaptive Security Appliance 'Outside' interface" -n -w 80 -c 90 -S
 * Traffic In : 1.14 kb/s (0.0 %), Out : 908.89 b/s (0.0 %)  - Link Speed : 100000000|traffic_in=1143,1Bits/s;80000000;90000000;0;100000000 traffic_out=908,9Bits/s;80000000;90000000;0;100000000
 */
 
/* Command to monitor Bandwidth on Cisco firewall */
SELECT @newCmdId := command_id FROM command where command_name = 'discovery_bandwidth_by_name';

/* Relation between Template and Command for Cisco firewall */
INSERT INTO service (service_template_model_stm_id, command_command_id, timeperiod_tp_id, command_command_id2, timeperiod_tp_id2, service_description, service_alias, service_is_volatile, service_max_check_attempts, service_normal_check_interval, service_retry_check_interval, service_active_checks_enabled, service_passive_checks_enabled, service_obsess_over_service, service_check_freshness, service_freshness_threshold, service_event_handler_enabled, service_low_flap_threshold, service_high_flap_threshold, service_flap_detection_enabled, service_process_perf_data, service_retain_status_information, service_retain_nonstatus_information, service_notification_interval, service_notification_options, service_notifications_enabled, service_stalking_options, service_first_notification_delay ,service_comment, command_command_id_arg, command_command_id_arg2, service_register, service_activate) VALUES ( '1', @newCmdId, NULL, NULL, NULL, 'Discovery_Cisco_fw_asa_bandwidth', 'Cisco_fw_asa_bandwidth', '2', NULL, NULL, NULL, '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, '2', NULL, NULL, NULL, '!Adaptive Security Appliance \'Outside\' interface!80!90', NULL, '0', '1');
SELECT @newServiceId := MAX(service_id) FROM service;
INSERT INTO host_service_relation (hostgroup_hg_id, host_host_id, servicegroup_sg_id, service_service_id) VALUES (NULL, @newHostId, NULL, @newServiceId);
DELETE FROM servicegroup_relation WHERE service_service_id = '@newServiceId';
INSERT INTO `extended_service_information` ( `esi_id` , `service_service_id`, `esi_notes` , `esi_notes_url` , `esi_action_url` , `esi_icon_image` , `esi_icon_image_alt`, `graph_id` )VALUES ( NULL, @newServiceId, NULL, NULL, NULL, NULL, NULL, NULL);

/*
 * Environment on Cisco firewall
 *
 */

 /* KO : not supported currently by Cisco on ASA equipment */

/*
 * Memory on Cisco firewall
 *
 * ./check_snmp_mem.pl -H 192.168.199.1 -C public -2 -I -w 80% -c 90% -f
 * MEMPOOL_DMA:76%,System memory:32%,MEMPOOL_GLOBAL_SHARED:15% : 26% :  ; OK | ram_used=513447752;1596167699;1795688662;0;1995209624
 */
 
/* Command to monitor Memory on Cisco firewall */
SELECT @newCmdId := command_id FROM command where command_name = 'discovery_memory_cisco';

/* Relation between Template and Command for Cisco firewall */
INSERT INTO service (service_template_model_stm_id, command_command_id, timeperiod_tp_id, command_command_id2, timeperiod_tp_id2, service_description, service_alias, service_is_volatile, service_max_check_attempts, service_normal_check_interval, service_retry_check_interval, service_active_checks_enabled, service_passive_checks_enabled, service_obsess_over_service, service_check_freshness, service_freshness_threshold, service_event_handler_enabled, service_low_flap_threshold, service_high_flap_threshold, service_flap_detection_enabled, service_process_perf_data, service_retain_status_information, service_retain_nonstatus_information, service_notification_interval, service_notification_options, service_notifications_enabled, service_stalking_options, service_first_notification_delay ,service_comment, command_command_id_arg, command_command_id_arg2, service_register, service_activate) VALUES ( '1', @newCmdId, NULL, NULL, NULL, 'Discovery_Cisco_fw_asa_mem', 'Cisco_fw_asa_mem', '2', NULL, NULL, NULL, '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, '2', NULL, NULL, NULL, '!80!90', NULL, '0', '1');
SELECT @newServiceId := MAX(service_id) FROM service;
INSERT INTO host_service_relation (hostgroup_hg_id, host_host_id, servicegroup_sg_id, service_service_id) VALUES (NULL, @newHostId, NULL, @newServiceId);
DELETE FROM servicegroup_relation WHERE service_service_id = '@newServiceId';
INSERT INTO `extended_service_information` ( `esi_id` , `service_service_id`, `esi_notes` , `esi_notes_url` , `esi_action_url` , `esi_icon_image` , `esi_icon_image_alt`, `graph_id` )VALUES ( NULL, @newServiceId, NULL, NULL, NULL, NULL, NULL, NULL);

/* ---------------------------- *
 * Template for Alcatel switch  *
 * -----------------------------*/

INSERT INTO host (host_template_model_htm_id, command_command_id, command_command_id_arg1, timeperiod_tp_id, timeperiod_tp_id2, command_command_id2, command_command_id_arg2,host_name, host_alias, host_address, host_max_check_attempts, host_check_interval, host_retry_check_interval, host_active_checks_enabled, host_passive_checks_enabled, host_checks_enabled, host_obsess_over_host, host_check_freshness, host_freshness_threshold, host_event_handler_enabled, host_low_flap_threshold, host_high_flap_threshold, host_flap_detection_enabled, host_process_perf_data, host_retain_status_information, host_retain_nonstatus_information, host_notification_interval, host_first_notification_delay, host_notification_options, host_notifications_enabled, host_stalking_options, host_snmp_community, host_snmp_version, host_location, host_comment, host_register, host_activate) VALUES ( '2', NULL, NULL, NULL, NULL, NULL, NULL, 'Discovery-Alcatel-Switch', 'Model for Alcatel switch from Discovery module', NULL, NULL, NULL, NULL, '2', '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, NULL, '2', NULL, NULL, '0', NULL, NULL, '0', '1');
SELECT @newHostId := MAX(host_id) FROM host;
SELECT @genTemplateId := host_id FROM host where ( host_name = "generic-host" );
INSERT INTO host_template_relation (`host_host_id`, `host_tpl_id`, `order`) VALUES (@newHostId, @genTemplateId, 1);
DELETE FROM host_hostparent_relation WHERE host_host_id = '@newHostId';
DELETE FROM host_hostparent_relation WHERE host_parent_hp_id = '@newHostId';
DELETE FROM contactgroup_host_relation WHERE host_host_id = '@newHostId';
DELETE FROM contact_host_relation WHERE host_host_id = '@newHostId';
DELETE FROM hostgroup_relation WHERE host_host_id = '@newHostId';
DELETE FROM host_service_relation WHERE host_host_id = '@newHostId';
UPDATE `acl_resources` SET `changed` = '1';
INSERT INTO `extended_host_information` ( `ehi_id` , `host_host_id` , `ehi_notes` , `ehi_notes_url` , `ehi_action_url` , `ehi_icon_image` , `ehi_icon_image_alt` , `ehi_vrml_image` , `ehi_statusmap_image` , `ehi_2d_coords` , `ehi_3d_coords` )VALUES ( NULL, @newHostId, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL );

/*
 * Load on Alcatel OmniSwitch
 *
 */
 
/* Command to monitor CPU on Alcatel OmniSwitch */
/* TO DO */

/* Relation between Template and Command for Alcatel OmniSwitch */
/* TO DO */

/*
 * Bandwidth on Alcatel OmniSwitch
 *
 * ./check_centreon_snmp_traffic -H 192.168.99.202 -C public -i "1" -w 80 -c 90 -S
 * Traffic In : 1.98 kb/s (0.0 %), Out : 2.01 kb/s (0.0 %)  - Link Speed : 100000000|traffic_in=1983,6Bits/s;80000000;90000000;0;100000000 traffic_out=2005,0Bits/s;80000000;90000000;0;100000000
 */
 
/* Command to monitor Bandwidth on Alcatel OmniSwitch */
SELECT @newCmdId := command_id FROM command where command_name = 'discovery_bandwidth_by_number';

/* Relation between Template and Command for Alcatel OmniSwitch */
INSERT INTO service (service_template_model_stm_id, command_command_id, timeperiod_tp_id, command_command_id2, timeperiod_tp_id2, service_description, service_alias, service_is_volatile, service_max_check_attempts, service_normal_check_interval, service_retry_check_interval, service_active_checks_enabled, service_passive_checks_enabled, service_obsess_over_service, service_check_freshness, service_freshness_threshold, service_event_handler_enabled, service_low_flap_threshold, service_high_flap_threshold, service_flap_detection_enabled, service_process_perf_data, service_retain_status_information, service_retain_nonstatus_information, service_notification_interval, service_notification_options, service_notifications_enabled, service_stalking_options, service_first_notification_delay ,service_comment, command_command_id_arg, command_command_id_arg2, service_register, service_activate) VALUES ( '1', @newCmdId, NULL, NULL, NULL, 'Discovery_Alcatel_sw_bandwidth', 'Alcatel_sw_bandwidth', '2', NULL, NULL, NULL, '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, '2', NULL, NULL, NULL, '!1!80!90', NULL, '0', '1');
SELECT @newServiceId := MAX(service_id) FROM service;
INSERT INTO host_service_relation (hostgroup_hg_id, host_host_id, servicegroup_sg_id, service_service_id) VALUES (NULL, @newHostId, NULL, @newServiceId);
DELETE FROM servicegroup_relation WHERE service_service_id = '@newServiceId';
INSERT INTO `extended_service_information` ( `esi_id` , `service_service_id`, `esi_notes` , `esi_notes_url` , `esi_action_url` , `esi_icon_image` , `esi_icon_image_alt`, `graph_id` )VALUES ( NULL, @newServiceId, NULL, NULL, NULL, NULL, NULL, NULL);

/*
 * Memory on Alcatel OmniSwitch
 *
 */
 
/* Command to monitor Memory on Alcatel OmniSwitch */
/* TO DO */

/* Relation between Template and Command for Alcatel OmniSwitch */
/* TO DO */

/* ---------------------------- *
 * Template for Linux server    *
 * -----------------------------*/

INSERT INTO host (host_template_model_htm_id, command_command_id, command_command_id_arg1, timeperiod_tp_id, timeperiod_tp_id2, command_command_id2, command_command_id_arg2,host_name, host_alias, host_address, host_max_check_attempts, host_check_interval, host_retry_check_interval, host_active_checks_enabled, host_passive_checks_enabled, host_checks_enabled, host_obsess_over_host, host_check_freshness, host_freshness_threshold, host_event_handler_enabled, host_low_flap_threshold, host_high_flap_threshold, host_flap_detection_enabled, host_process_perf_data, host_retain_status_information, host_retain_nonstatus_information, host_notification_interval, host_first_notification_delay, host_notification_options, host_notifications_enabled, host_stalking_options, host_snmp_community, host_snmp_version, host_location, host_comment, host_register, host_activate) VALUES ( '2', NULL, NULL, NULL, NULL, NULL, NULL, 'Discovery-Linux-Server', 'Model for Linux server from Discovery module', NULL, NULL, NULL, NULL, '2', '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, NULL, '2', NULL, NULL, '0', NULL, NULL, '0', '1');
SELECT @newHostId := MAX(host_id) FROM host;
SELECT @genTemplateId := host_id FROM host where ( host_name = "generic-host" );
INSERT INTO host_template_relation (`host_host_id`, `host_tpl_id`, `order`) VALUES (@newHostId, @genTemplateId, 1);
DELETE FROM host_hostparent_relation WHERE host_host_id = '@newHostId';
DELETE FROM host_hostparent_relation WHERE host_parent_hp_id = '@newHostId';
DELETE FROM contactgroup_host_relation WHERE host_host_id = '@newHostId';
DELETE FROM contact_host_relation WHERE host_host_id = '@newHostId';
DELETE FROM hostgroup_relation WHERE host_host_id = '@newHostId';
DELETE FROM host_service_relation WHERE host_host_id = '@newHostId';
UPDATE `acl_resources` SET `changed` = '1';
INSERT INTO `extended_host_information` ( `ehi_id` , `host_host_id` , `ehi_notes` , `ehi_notes_url` , `ehi_action_url` , `ehi_icon_image` , `ehi_icon_image_alt` , `ehi_vrml_image` , `ehi_statusmap_image` , `ehi_2d_coords` , `ehi_3d_coords` )VALUES ( NULL, @newHostId, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL );

/*
 * Memory on Linux server
 *
 * ./check_centreon_snmp_memory -H 192.168.0.7 -C public -v 2 -w 80 -c 90
 * Total memory used : 10%  ram used : 67%, swap used 0% | used=86720512o size=797990912o
 */
 
/* Command to monitor Memory on Linux server */
SELECT @newCmdId := command_id FROM command where command_name = 'discovery_memory';

/* Relation between Template and Command for Linux Server */
INSERT INTO service (service_template_model_stm_id, command_command_id, timeperiod_tp_id, command_command_id2, timeperiod_tp_id2, service_description, service_alias, service_is_volatile, service_max_check_attempts, service_normal_check_interval, service_retry_check_interval, service_active_checks_enabled, service_passive_checks_enabled, service_obsess_over_service, service_check_freshness, service_freshness_threshold, service_event_handler_enabled, service_low_flap_threshold, service_high_flap_threshold, service_flap_detection_enabled, service_process_perf_data, service_retain_status_information, service_retain_nonstatus_information, service_notification_interval, service_notification_options, service_notifications_enabled, service_stalking_options, service_first_notification_delay ,service_comment, command_command_id_arg, command_command_id_arg2, service_register, service_activate) VALUES ( '1', @newCmdId, NULL, NULL, NULL, 'Discovery_Linux_srv_mem', 'Linux_srv_mem', '2', NULL, NULL, NULL, '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, '2', NULL, NULL, NULL, '!80!90', NULL, '0', '1');
SELECT @newServiceId := MAX(service_id) FROM service;
INSERT INTO host_service_relation (hostgroup_hg_id, host_host_id, servicegroup_sg_id, service_service_id) VALUES (NULL, @newHostId, NULL, @newServiceId);
DELETE FROM servicegroup_relation WHERE service_service_id = '@newServiceId';
INSERT INTO `extended_service_information` ( `esi_id` , `service_service_id`, `esi_notes` , `esi_notes_url` , `esi_action_url` , `esi_icon_image` , `esi_icon_image_alt`, `graph_id` )VALUES ( NULL, @newServiceId, NULL, NULL, NULL, NULL, NULL, NULL);

/*
 * Load on Linux server
 *
 * ./check_snmp_load.pl -H 192.168.0.100 -C public -2 -w 3,2,1 -c 4,3,2 -T netsl -f
 * Load : 0.00 0.00 0.00 : OK | load_1_min=0.00;3;4 load_5_min=0.00;2;3 load_15_min=0.00;1;2
 */
 
/* Command to monitor Load on Linux server */
SELECT @newCmdId := command_id FROM command where command_name = 'discovery_load';

/* Relation between Template and Command for Linux Server */
INSERT INTO service (service_template_model_stm_id, command_command_id, timeperiod_tp_id, command_command_id2, timeperiod_tp_id2, service_description, service_alias, service_is_volatile, service_max_check_attempts, service_normal_check_interval, service_retry_check_interval, service_active_checks_enabled, service_passive_checks_enabled, service_obsess_over_service, service_check_freshness, service_freshness_threshold, service_event_handler_enabled, service_low_flap_threshold, service_high_flap_threshold, service_flap_detection_enabled, service_process_perf_data, service_retain_status_information, service_retain_nonstatus_information, service_notification_interval, service_notification_options, service_notifications_enabled, service_stalking_options, service_first_notification_delay ,service_comment, command_command_id_arg, command_command_id_arg2, service_register, service_activate) VALUES ( '1', @newCmdId, NULL, NULL, NULL, 'Discovery_Linux_srv_load', 'Linux_srv_load', '2', NULL, NULL, NULL, '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, '2', NULL, NULL, NULL, '!netsl!4,3,2!5,4,3', NULL, '0', '1');
SELECT @newServiceId := MAX(service_id) FROM service;
INSERT INTO host_service_relation (hostgroup_hg_id, host_host_id, servicegroup_sg_id, service_service_id) VALUES (NULL, @newHostId, NULL, @newServiceId);
DELETE FROM servicegroup_relation WHERE service_service_id = '@newServiceId';
INSERT INTO `extended_service_information` ( `esi_id` , `service_service_id`, `esi_notes` , `esi_notes_url` , `esi_action_url` , `esi_icon_image` , `esi_icon_image_alt`, `graph_id` )VALUES ( NULL, @newServiceId, NULL, NULL, NULL, NULL, NULL, NULL);

/*
 * Disk on Linux server
 *
 * ./check_centreon_snmp_remote_storage -H 192.168.0.7 -v 2 -C public -d "/" -n -w 98 -c 99
 * Disk OK - / TOTAL: 11.019GB USED: 1.449GB (13%) FREE: 9.569GB (87%)|size=11831304192B used=1556303872B;11594678108;11712991150;0;11831304192
 */
 
/* Command to monitor Load on Linux server */
SELECT @newCmdId := command_id FROM command where command_name = 'discovery_disk';

/* Relation between Template and Command for Linux Server */
INSERT INTO service (service_template_model_stm_id, command_command_id, timeperiod_tp_id, command_command_id2, timeperiod_tp_id2, service_description, service_alias, service_is_volatile, service_max_check_attempts, service_normal_check_interval, service_retry_check_interval, service_active_checks_enabled, service_passive_checks_enabled, service_obsess_over_service, service_check_freshness, service_freshness_threshold, service_event_handler_enabled, service_low_flap_threshold, service_high_flap_threshold, service_flap_detection_enabled, service_process_perf_data, service_retain_status_information, service_retain_nonstatus_information, service_notification_interval, service_notification_options, service_notifications_enabled, service_stalking_options, service_first_notification_delay ,service_comment, command_command_id_arg, command_command_id_arg2, service_register, service_activate) VALUES ( '1', @newCmdId, NULL, NULL, NULL, 'Discovery_Linux_srv_disk', 'Linux_srv_disk', '2', NULL, NULL, NULL, '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, '2', NULL, NULL, NULL, '!/!80!90', NULL, '0', '1');
SELECT @newServiceId := MAX(service_id) FROM service;
INSERT INTO host_service_relation (hostgroup_hg_id, host_host_id, servicegroup_sg_id, service_service_id) VALUES (NULL, @newHostId, NULL, @newServiceId);
DELETE FROM servicegroup_relation WHERE service_service_id = '@newServiceId';
INSERT INTO `extended_service_information` ( `esi_id` , `service_service_id`, `esi_notes` , `esi_notes_url` , `esi_action_url` , `esi_icon_image` , `esi_icon_image_alt`, `graph_id` )VALUES ( NULL, @newServiceId, NULL, NULL, NULL, NULL, NULL, NULL);

/*
 * Process on Linux server
 *
 * ./check_centreon_snmp_process -H 192.168.0.7 -C public -v 2 -p sshd
 * Process OK - sshd: runnable
 */
 
/* Command to monitor process on Linux server */
SELECT @newCmdId := command_id FROM command where command_name = 'discovery_proc';

/* Relation between Template and Command for Linux Server */
INSERT INTO service (service_template_model_stm_id, command_command_id, timeperiod_tp_id, command_command_id2, timeperiod_tp_id2, service_description, service_alias, service_is_volatile, service_max_check_attempts, service_normal_check_interval, service_retry_check_interval, service_active_checks_enabled, service_passive_checks_enabled, service_obsess_over_service, service_check_freshness, service_freshness_threshold, service_event_handler_enabled, service_low_flap_threshold, service_high_flap_threshold, service_flap_detection_enabled, service_process_perf_data, service_retain_status_information, service_retain_nonstatus_information, service_notification_interval, service_notification_options, service_notifications_enabled, service_stalking_options, service_first_notification_delay ,service_comment, command_command_id_arg, command_command_id_arg2, service_register, service_activate) VALUES ( '1', @newCmdId, NULL, NULL, NULL, 'Discovery_Linux_srv_proc', 'Linux_srv_proc', '2', NULL, NULL, NULL, '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, '2', NULL, NULL, NULL, '!sshd', NULL, '0', '1');
SELECT @newServiceId := MAX(service_id) FROM service;
INSERT INTO host_service_relation (hostgroup_hg_id, host_host_id, servicegroup_sg_id, service_service_id) VALUES (NULL, @newHostId, NULL, @newServiceId);
DELETE FROM servicegroup_relation WHERE service_service_id = '@newServiceId';
INSERT INTO `extended_service_information` ( `esi_id` , `service_service_id`, `esi_notes` , `esi_notes_url` , `esi_action_url` , `esi_icon_image` , `esi_icon_image_alt`, `graph_id` )VALUES ( NULL, @newServiceId, NULL, NULL, NULL, NULL, NULL, NULL);

/* ---------------------------- *
 * Template for Windows server  *
 * -----------------------------*/
 
INSERT INTO host (host_template_model_htm_id, command_command_id, command_command_id_arg1, timeperiod_tp_id, timeperiod_tp_id2, command_command_id2, command_command_id_arg2,host_name, host_alias, host_address, host_max_check_attempts, host_check_interval, host_retry_check_interval, host_active_checks_enabled, host_passive_checks_enabled, host_checks_enabled, host_obsess_over_host, host_check_freshness, host_freshness_threshold, host_event_handler_enabled, host_low_flap_threshold, host_high_flap_threshold, host_flap_detection_enabled, host_process_perf_data, host_retain_status_information, host_retain_nonstatus_information, host_notification_interval, host_first_notification_delay, host_notification_options, host_notifications_enabled, host_stalking_options, host_snmp_community, host_snmp_version, host_location, host_comment, host_register, host_activate) VALUES ( '2', NULL, NULL, NULL, NULL, NULL, NULL, 'Discovery-Win-Server', 'Model for Windows server from Discovery module', NULL, NULL, NULL, NULL, '2', '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, NULL, '2', NULL, NULL, '0', NULL, NULL, '0', '1');
SELECT @newHostId := MAX(host_id) FROM host;
SELECT @genTemplateId := host_id FROM host where ( host_name = "generic-host" );
INSERT INTO host_template_relation (`host_host_id`, `host_tpl_id`, `order`) VALUES (@newHostId, @genTemplateId, 1);
DELETE FROM host_hostparent_relation WHERE host_host_id = '@newHostId';
DELETE FROM host_hostparent_relation WHERE host_parent_hp_id = '@newHostId';
DELETE FROM contactgroup_host_relation WHERE host_host_id = '@newHostId';
DELETE FROM contact_host_relation WHERE host_host_id = '@newHostId';
DELETE FROM hostgroup_relation WHERE host_host_id = '@newHostId';
DELETE FROM host_service_relation WHERE host_host_id = '@newHostId';
UPDATE `acl_resources` SET `changed` = '1';
INSERT INTO `extended_host_information` ( `ehi_id` , `host_host_id` , `ehi_notes` , `ehi_notes_url` , `ehi_action_url` , `ehi_icon_image` , `ehi_icon_image_alt` , `ehi_vrml_image` , `ehi_statusmap_image` , `ehi_2d_coords` , `ehi_3d_coords` )VALUES ( NULL, @newHostId, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL );

/*
 * Memory on Windows server
 *
 * ./check_centreon_snmp_memory -H 192.168.0.4 -C public -v 2 -w 80 -c 90
 * Total memory used : 18%  ram used : 30%, swap used 11% | used=271056896o size=1444478976o
 */
 
/* Command to monitor Memory on Windows server */
SELECT @newCmdId := command_id FROM command where command_name = 'discovery_memory';

/* Relation between Template and Command for Windows server */
INSERT INTO service (service_template_model_stm_id, command_command_id, timeperiod_tp_id, command_command_id2, timeperiod_tp_id2, service_description, service_alias, service_is_volatile, service_max_check_attempts, service_normal_check_interval, service_retry_check_interval, service_active_checks_enabled, service_passive_checks_enabled, service_obsess_over_service, service_check_freshness, service_freshness_threshold, service_event_handler_enabled, service_low_flap_threshold, service_high_flap_threshold, service_flap_detection_enabled, service_process_perf_data, service_retain_status_information, service_retain_nonstatus_information, service_notification_interval, service_notification_options, service_notifications_enabled, service_stalking_options, service_first_notification_delay ,service_comment, command_command_id_arg, command_command_id_arg2, service_register, service_activate) VALUES ( '1', @newCmdId, NULL, NULL, NULL, 'Discovery_Win_srv_mem', 'Win_srv_mem', '2', NULL, NULL, NULL, '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, '2', NULL, NULL, NULL, '!80!90', NULL, '0', '1');
SELECT @newServiceId := MAX(service_id) FROM service;
INSERT INTO host_service_relation (hostgroup_hg_id, host_host_id, servicegroup_sg_id, service_service_id) VALUES (NULL, @newHostId, NULL, @newServiceId);
DELETE FROM servicegroup_relation WHERE service_service_id = '@newServiceId';
INSERT INTO `extended_service_information` ( `esi_id` , `service_service_id`, `esi_notes` , `esi_notes_url` , `esi_action_url` , `esi_icon_image` , `esi_icon_image_alt`, `graph_id` )VALUES ( NULL, @newServiceId, NULL, NULL, NULL, NULL, NULL, NULL);

/*
 * CPU on Windows server
 *
 * ./check_snmp_load.pl -H 192.168.0.4 -C public -2 -T stand -w 80 -c 90 -f
 * 1 CPU, load 5.0% < 80% : OK | cpu_prct_used=5%;80;90
 */
 
/* Command to monitor CPU on Windows server */
SELECT @newCmdId := command_id FROM command where command_name = 'discovery_load';

/* Relation between Template and Command for Windows server */
INSERT INTO service (service_template_model_stm_id, command_command_id, timeperiod_tp_id, command_command_id2, timeperiod_tp_id2, service_description, service_alias, service_is_volatile, service_max_check_attempts, service_normal_check_interval, service_retry_check_interval, service_active_checks_enabled, service_passive_checks_enabled, service_obsess_over_service, service_check_freshness, service_freshness_threshold, service_event_handler_enabled, service_low_flap_threshold, service_high_flap_threshold, service_flap_detection_enabled, service_process_perf_data, service_retain_status_information, service_retain_nonstatus_information, service_notification_interval, service_notification_options, service_notifications_enabled, service_stalking_options, service_first_notification_delay ,service_comment, command_command_id_arg, command_command_id_arg2, service_register, service_activate) VALUES ( '1', @newCmdId, NULL, NULL, NULL, 'Discovery_Win_srv_cpu', 'Win_srv_cpu', '2', NULL, NULL, NULL, '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, '2', NULL, NULL, NULL, '!stand!80!90', NULL, '0', '1');
SELECT @newServiceId := MAX(service_id) FROM service;
INSERT INTO host_service_relation (hostgroup_hg_id, host_host_id, servicegroup_sg_id, service_service_id) VALUES (NULL, @newHostId, NULL, @newServiceId);
DELETE FROM servicegroup_relation WHERE service_service_id = '@newServiceId';
INSERT INTO `extended_service_information` ( `esi_id` , `service_service_id`, `esi_notes` , `esi_notes_url` , `esi_action_url` , `esi_icon_image` , `esi_icon_image_alt`, `graph_id` )VALUES ( NULL, @newServiceId, NULL, NULL, NULL, NULL, NULL, NULL);

/*
 * Disk on Windows server
 *
 * ./check_centreon_snmp_remote_storage -H 192.168.0.4 -C public -v 2 -d "C:" -n -w 80 -c 90
 * Disk OK - C: TOTAL: 5.171GB USED: 3.643GB (70%) FREE: 1.527GB (30%)|size=5552029696B used=3911962624B;4441623756;4996826726;0;5552029696
 */
 
/* Command to monitor disk on Windows server */
SELECT @newCmdId := command_id FROM command where command_name = 'discovery_disk';

/* Relation between Template and Command for Windows server */
INSERT INTO service (service_template_model_stm_id, command_command_id, timeperiod_tp_id, command_command_id2, timeperiod_tp_id2, service_description, service_alias, service_is_volatile, service_max_check_attempts, service_normal_check_interval, service_retry_check_interval, service_active_checks_enabled, service_passive_checks_enabled, service_obsess_over_service, service_check_freshness, service_freshness_threshold, service_event_handler_enabled, service_low_flap_threshold, service_high_flap_threshold, service_flap_detection_enabled, service_process_perf_data, service_retain_status_information, service_retain_nonstatus_information, service_notification_interval, service_notification_options, service_notifications_enabled, service_stalking_options, service_first_notification_delay ,service_comment, command_command_id_arg, command_command_id_arg2, service_register, service_activate) VALUES ( '1', @newCmdId, NULL, NULL, NULL, 'Discovery_Win_srv_disk', 'Win_srv_disk', '2', NULL, NULL, NULL, '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, '2', NULL, NULL, NULL, '!C:!80!90', NULL, '0', '1');
SELECT @newServiceId := MAX(service_id) FROM service;
INSERT INTO host_service_relation (hostgroup_hg_id, host_host_id, servicegroup_sg_id, service_service_id) VALUES (NULL, @newHostId, NULL, @newServiceId);
DELETE FROM servicegroup_relation WHERE service_service_id = '@newServiceId';
INSERT INTO `extended_service_information` ( `esi_id` , `service_service_id`, `esi_notes` , `esi_notes_url` , `esi_action_url` , `esi_icon_image` , `esi_icon_image_alt`, `graph_id` )VALUES ( NULL, @newServiceId, NULL, NULL, NULL, NULL, NULL, NULL);

/*
 * Process on Windows server
 *
 * ./check_centreon_snmp_process -H 192.168.0.4 -C public -v 2 -p explorer.exe
 * Process OK - explorer.exe: runnable
 */
 
/* Command to monitor process on Windows server */
SELECT @newCmdId := command_id FROM command where command_name = 'discovery_proc';

/* Relation between Template and Command for Windows server */
INSERT INTO service (service_template_model_stm_id, command_command_id, timeperiod_tp_id, command_command_id2, timeperiod_tp_id2, service_description, service_alias, service_is_volatile, service_max_check_attempts, service_normal_check_interval, service_retry_check_interval, service_active_checks_enabled, service_passive_checks_enabled, service_obsess_over_service, service_check_freshness, service_freshness_threshold, service_event_handler_enabled, service_low_flap_threshold, service_high_flap_threshold, service_flap_detection_enabled, service_process_perf_data, service_retain_status_information, service_retain_nonstatus_information, service_notification_interval, service_notification_options, service_notifications_enabled, service_stalking_options, service_first_notification_delay ,service_comment, command_command_id_arg, command_command_id_arg2, service_register, service_activate) VALUES ( '1', @newCmdId, NULL, NULL, NULL, 'Discovery_Win_srv_proc', 'Win_srv_proc', '2', NULL, NULL, NULL, '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, '2', NULL, NULL, NULL, '!explorer.exe', NULL, '0', '1');
SELECT @newServiceId := MAX(service_id) FROM service;
INSERT INTO host_service_relation (hostgroup_hg_id, host_host_id, servicegroup_sg_id, service_service_id) VALUES (NULL, @newHostId, NULL, @newServiceId);
DELETE FROM servicegroup_relation WHERE service_service_id = '@newServiceId';
INSERT INTO `extended_service_information` ( `esi_id` , `service_service_id`, `esi_notes` , `esi_notes_url` , `esi_action_url` , `esi_icon_image` , `esi_icon_image_alt`, `graph_id` )VALUES ( NULL, @newServiceId, NULL, NULL, NULL, NULL, NULL, NULL);

/*
 * Service on Windows server
 *
 * ./check_snmp_win.pl -H 192.168.0.4 -C public -2 -T service -n "Journal des .v.nements"
 * 1 services active (matching "Journal des .v.nements") : OK
 */
 
/* Command to monitor service on Windows server */
SELECT @newCmdId := command_id FROM command where command_name = 'discovery_win_service';

/* Relation between Template and Command for Windows server */
INSERT INTO service (service_template_model_stm_id, command_command_id, timeperiod_tp_id, command_command_id2, timeperiod_tp_id2, service_description, service_alias, service_is_volatile, service_max_check_attempts, service_normal_check_interval, service_retry_check_interval, service_active_checks_enabled, service_passive_checks_enabled, service_obsess_over_service, service_check_freshness, service_freshness_threshold, service_event_handler_enabled, service_low_flap_threshold, service_high_flap_threshold, service_flap_detection_enabled, service_process_perf_data, service_retain_status_information, service_retain_nonstatus_information, service_notification_interval, service_notification_options, service_notifications_enabled, service_stalking_options, service_first_notification_delay ,service_comment, command_command_id_arg, command_command_id_arg2, service_register, service_activate) VALUES ( '1', @newCmdId, NULL, NULL, NULL, 'Discovery_Win_srv_service', 'Win_srv_service', '2', NULL, NULL, NULL, '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, '2', NULL, NULL, NULL, '!Journal des .v.nements', NULL, '0', '1');
SELECT @newServiceId := MAX(service_id) FROM service;
INSERT INTO host_service_relation (hostgroup_hg_id, host_host_id, servicegroup_sg_id, service_service_id) VALUES (NULL, @newHostId, NULL, @newServiceId);
DELETE FROM servicegroup_relation WHERE service_service_id = '@newServiceId';
INSERT INTO `extended_service_information` ( `esi_id` , `service_service_id`, `esi_notes` , `esi_notes_url` , `esi_action_url` , `esi_icon_image` , `esi_icon_image_alt`, `graph_id` )VALUES ( NULL, @newServiceId, NULL, NULL, NULL, NULL, NULL, NULL);


/* *************************************************************************************************
 * -------------------------------------------------------------------------------------------------
 *
 * DATABASE: @DB_NAME_CENTREON@
 * TABLE: service / host_service_relation / servicegroup_relation / extended_service_information
 *	
 * Create generic service
 *
 * -------------------------------------------------------------------------------------------------
 * ************************************************************************************************* */
 
 /* Command to monitor environment on Cisco equipment */
SELECT @newCmdId := command_id FROM command where command_name = 'discovery_env';

/* Relation between Template and Command for Cisco router */
INSERT INTO service (service_template_model_stm_id, command_command_id, timeperiod_tp_id, command_command_id2, timeperiod_tp_id2, service_description, service_alias, service_is_volatile, service_max_check_attempts, service_normal_check_interval, service_retry_check_interval, service_active_checks_enabled, service_passive_checks_enabled, service_obsess_over_service, service_check_freshness, service_freshness_threshold, service_event_handler_enabled, service_low_flap_threshold, service_high_flap_threshold, service_flap_detection_enabled, service_process_perf_data, service_retain_status_information, service_retain_nonstatus_information, service_notification_interval, service_notification_options, service_notifications_enabled, service_stalking_options, service_first_notification_delay ,service_comment, command_command_id_arg, command_command_id_arg2, service_register, service_activate) VALUES ( '1', @newCmdId, NULL, NULL, NULL, 'Discovery_Cisco_env', 'Cisco_env', '2', NULL, NULL, NULL, '2', '2', '2', '2', NULL, '2', NULL, NULL, '2', '2', '2', '2', NULL, NULL, '2', NULL, NULL, NULL, '!cisco', NULL, '0', '1');
SELECT @newServiceId := MAX(service_id) FROM service;
INSERT INTO `extended_service_information` ( `esi_id` , `service_service_id`, `esi_notes` , `esi_notes_url` , `esi_action_url` , `esi_icon_image` , `esi_icon_image_alt`, `graph_id` )VALUES ( NULL, @newServiceId, NULL, NULL, NULL, NULL, NULL, NULL);
/* for switch */
SELECT @newHostId := host_id FROM host WHERE host_name = 'Discovery-Cisco-Switch';
INSERT INTO host_service_relation (hostgroup_hg_id, host_host_id, servicegroup_sg_id, service_service_id) VALUES (NULL, @newHostId, NULL, @newServiceId);
DELETE FROM servicegroup_relation WHERE service_service_id = '@newServiceId';
/* for router */
SELECT @newHostId := host_id FROM host WHERE host_name = 'Discovery-Cisco-Router';
INSERT INTO host_service_relation (hostgroup_hg_id, host_host_id, servicegroup_sg_id, service_service_id) VALUES (NULL, @newHostId, NULL, @newServiceId);
DELETE FROM servicegroup_relation WHERE service_service_id = '@newServiceId';