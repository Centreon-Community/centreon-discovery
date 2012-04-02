#!/bin/bash

# By Sub2.13

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
	