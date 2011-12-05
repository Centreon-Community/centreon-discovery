#!/bin/sh
#
#	install.sh
#
echo '
############################################
##               install.sh               ##
############################################
'

# Sélection du répertoire centreon
read -p "entrez le chemin du repertoire dans lequel est installe Centreon: (ex:/usr/share/centreon): " choix_rep_centreon

echo "PATH_MODULE : "$choix_rep_centreon"/www/modules"


echo '
  - Attribution des droits
'
# Droits du module Centreon Discovery
read -p "entrez le nom de votre utilisateur apache: (ex:apache): " user_apache
read -p "entrez le nom de votre groupe apache: (ex:apache): " group_apache
echo""

echo -e "Positionnement des droits du module Centreon Discovery"
chown $user_apache.$group_apache -R $choix_rep_centreon/www/modules/CentreonDiscovery
chmod 700 -R $choix_rep_centreon/www/modules/CentreonDiscovery
echo ""

read -p "entrez le nom de votre groupe Nagios: (ex:nagios): " group_nagios
echo""
read -p "entrez le chemin de vos plugins Nagios: (ex:/usr/lib/nagios/plugins): " choix_rep_plugins

echo "
  - Installation des plugins TeamReon
       ("$choix_rep_plugins")
"
# Installation des plugins TeamReon
cp -Rf $choix_rep_centreon/www/modules/CentreonDiscovery/install/Plugins/* $choix_rep_plugins

chown $user_apache.$group_nagios $choix_rep_plugins/check_teamreon_snmp_int.pl
chown $user_apache.$group_nagios $choix_rep_plugins/check_teamreon_snmp_mem_cisco.pl
chown $user_apache.$group_nagios $choix_rep_plugins/check_teamreon_snmp_load.pl
chown $user_apache.$group_nagios $choix_rep_plugins/check_teamreon_snmp_win.pl
chown $user_apache.$group_nagios $choix_rep_plugins/check_teamreon_snmp_storage.pl
chown $user_apache.$group_nagios $choix_rep_plugins/check_teamreon_process.pl
chown $user_apache.$group_nagios $choix_rep_plugins/check_teamreon_CPU_linux.pl
chown $user_apache.$group_nagios $choix_rep_plugins/check_teamreon_memory_linux.pl
chown $user_apache.$group_nagios $choix_rep_plugins/check_teamreon_process_linux.pl
chown $user_apache.$group_nagios $choix_rep_plugins/check_teamreon_interface_cisco_router.pl
chown $user_apache.$group_nagios $choix_rep_plugins/check_teamreon_interface_cisco_switch.pl
chown $user_apache.$group_nagios $choix_rep_plugins/check_teamreon_memory_cisco_router.pl
chown $user_apache.$group_nagios $choix_rep_plugins/check_teamreon_memory_cisco_switch.pl
chown $user_apache.$group_nagios $choix_rep_plugins/check_teamreon_memory_win.pl
chown $user_apache.$group_nagios $choix_rep_plugins/check_teamreon_ping.pl
chown $user_apache.$group_nagios $choix_rep_plugins/check_teamreon_process_win.pl
chown $user_apache.$group_nagios $choix_rep_plugins/check_teamreon_snmp_storage_linux.pl
chown $user_apache.$group_nagios $choix_rep_plugins/check_teamreon_snmp_storage_win.pl
chown $user_apache.$group_nagios $choix_rep_plugins/check_teamreon_interface_ping.pl
chown $user_apache.$group_nagios $choix_rep_plugins/check_teamreon_snmp_traffic.pl

chmod 770 $choix_rep_plugins/check_teamreon_snmp_int.pl
chmod 770 $choix_rep_plugins/check_teamreon_snmp_mem_cisco.pl
chmod 770 $choix_rep_plugins/check_teamreon_snmp_load.pl
chmod 770 $choix_rep_plugins/check_teamreon_snmp_win.pl
chmod 770 $choix_rep_plugins/check_teamreon_snmp_storage.pl
chmod 770 $choix_rep_plugins/check_teamreon_process.pl
chmod 770 $choix_rep_plugins/check_teamreon_CPU_linux.pl
chmod 770 $choix_rep_plugins/check_teamreon_memory_linux.pl
chmod 770 $choix_rep_plugins/check_teamreon_process_linux.pl
chmod 770 $choix_rep_plugins/check_teamreon_interface_cisco_router.pl
chmod 770 $choix_rep_plugins/check_teamreon_interface_cisco_switch.pl
chmod 770 $choix_rep_plugins/check_teamreon_memory_cisco_router.pl
chmod 770 $choix_rep_plugins/check_teamreon_memory_cisco_switch.pl
chmod 770 $choix_rep_plugins/check_teamreon_memory_win.pl
chmod 770 $choix_rep_plugins/check_teamreon_ping.pl
chmod 770 $choix_rep_plugins/check_teamreon_process_win.pl
chmod 770 $choix_rep_plugins/check_teamreon_snmp_storage_linux.pl
chmod 770 $choix_rep_plugins/check_teamreon_snmp_storage_win.pl
chmod 770 $choix_rep_plugins/check_teamreon_interface_ping.pl
chmod 770 $choix_rep_plugins/check_teamreon_snmp_traffic.pl



echo '
############################################
'

# Suppression des plugins lors de la déinstallation du module !!
#
# echo '<? exec("rm -f '$choix_rep_plugins'/check_teamreon_*"); ?>' > $choix_rep_centreon/www/modules/CentreonDiscovery/php/uninstall.php
#







