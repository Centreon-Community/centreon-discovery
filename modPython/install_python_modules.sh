#!/bin/bash

# Centreon-Discovery is free software; you can redistribute it and/or modify it under
# the terms of the GNU General Public License as published by the Free Software
# Foundation ; either version 2 of the License.
#
# This program is distributed in the hope that it will be useful, but WITHOUT ANY
# WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
# PARTICULAR PURPOSE. See the GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along with
# this program; if not, see <http://www.gnu.org/licenses>.
#
# Linking this program statically or dynamically with other modules is making a
# combined work based on this program. Thus, the terms and conditions of the GNU
# General Public License cover the whole combination.
#
# Module name: Centreon-Discovery
#
# First developpement by : Jean Marc Grisard - Christophe Coraboeuf
# Adaptation for Centreon 2.0 by : Merethis team
# Inspired from Watt's script.
#
# Modified by: Sub2.13
#
# SVN: http://svn.modules.centreon.com/centreon-discovery

list_modules="pycrypto-2.5 python-nmap-0.1.4 setuptools-0.6c11 MySQL-python-1.2.3"

echo -e "--------------------------------------------------------------"
echo -e "\nInstallation des modules python\n"
echo ""
for module in $list_modules
	do
		echo -e "\t$module"
	done
echo -e "\n--------------------------------------------------------------\n"

for module in $list_modules
	do
		echo -e "Do you wan install $module ? [y]"
		echo -en "> "
		read temp_read
		if [ -z $temp_read ] || [ "$temp_read" = "y" ]; then
			cd "$module"
			python setup.py install
			if [ $? -eq 0 ] ; then
				echo -e "\n\033[32mInstall OK for $module\033[00m\n"
			fi
			cd ..
		else	
			echo -e "\n\033[31mInstall KO for $module\033[00m\n"
		fi
		echo "--------------------------------------------------------------"
	done
	