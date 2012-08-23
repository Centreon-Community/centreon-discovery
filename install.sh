#!/bin/bash

# This file is part of Centreon-Discovery module.
#
# Centreon-Discovery is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 2 of the License.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, see <http://www.gnu.org/licenses>.
#
# Module name: Centreon-Discovery
#
# First developpement by: Jean Marc Grisard - Christophe Coraboeuf
# Adaptation for Centreon 2.0 by: Merethis team 
# Inspired from Watt's script.
#
# Modified by: Sub2.13
#
# WEBSITE: http://community.centreon.com/projects/centreon-discovery
# SVN: http://svn.modules.centreon.com/centreon-discovery

#---
## {Print help and usage}
##
## @Stdout Usage and Help program
#----
function usage() {
    local program=$0
    echo -e "\nUsage: $program -u <directory> / -r <directory> / -i -t <type> / -h"
    echo -e "  -i\tinstall Discovery module"
    echo -e "  -u\tupgrade Discovery with specify your directory with instCentDisco.conf file"
    echo -e "  -t\tdefine type install : central/poller/both"
    echo -e "  -r\tremove Discovery module"
    echo -e "  -h\tdisplay this message"
    echo -e "\nExample for poller:"
    echo -e "  ./install.sh -i -t poller"
    echo -e "\nExample for update:"    
    echo -e "  ./install.sh -u /usr/share/centreon-discovery"
    exit 1
}


# define where is a centreon-module source 
BASE_DIR=$(dirname $0)
## set directory
BASE_DIR=$( cd $BASE_DIR; pwd )
export BASE_DIR

if [ -z "${BASE_DIR#/}" ] ; then
    echo -e "I think it is not right to have Centreon-Discovery source on slash"
    exit 1
fi

INSTALL_DIR="$BASE_DIR/install"
export INSTALL_DIR

## load all functions used in this script
. $INSTALL_DIR/variables
. $INSTALL_DIR/display_functions
. $INSTALL_DIR/functions

if [ $# -eq 0 ] ; then
   echo -e "No options found !"
   usage;
   exit 1;
fi

## Getopts
error="1"
while getopts "ir:u:t:h" Options ; do
    case ${Options} in
	i) 
	    _tmp_install_opts="1"
	    UPDATE="1"
	    error="0"
	    ;;
	u)
	    UPDATE="0"
	    error="0"
	    DIR_CONF_DISCO=$OPTARG
	    if [ ! -d $DIR_CONF_DISCO ] ; then
		echo -e "\"$DIR_CONF_DISCO\" is not a valid directory !"
		usage;
		exit 1
	    fi
	    ;;
	t)
	    TYPE_INSTALL=$OPTARG
	    error="0"
	    if [ $TYPE_INSTALL != "poller" ] && [ $TYPE_INSTALL != "central" ] && [ $TYPE_INSTALL != "both" ] ; then
		echo -e "Type install incorrect !"
		usage;
		exit 1
	    fi
	    ;;
	r)
            UPDATE="2"
	    error="0"
            DIR_CONF_DISCO=$OPTARG
            if [ ! -d $DIR_CONF_DISCO ] ; then
                echo -e "\"$DIR_CONF_DISCO\" is not a valid directory !"
                usage;
                exit 1
            fi
            ;;
	\?|h)
	    usage; 
	    exit 0 
	    ;;
	*)
	    usage;
	    exit 1
	    ;;
    esac
done

if [ $error -eq 1 ] || [ $UPDATE -eq 1 ] && ( [ $_tmp_install_opts -eq 0 ] || ( [ "$TYPE_INSTALL" != "poller" ] && [ "$TYPE_INSTALL" != "central" ] && [ "$TYPE_INSTALL" != "both" ] )) ; then
    usage;
    exit 1
fi

### Main
echo "Waiting ..."

## Valid if you are root 
USERID=`id -u`
if [ "$USERID" != "0" ]; then
    echo -e "You must exec with root user"
    exit 1
fi

#Export variable for all programs
export UPDATE CENTREON_CONF

## Define a default log file
LOG_DIR="$BASE_DIR/log"
if [ $UPDATE -eq 0 ] ; then
    LOG_FILE="$PWD/update.log"
elif [ $UPDATE -eq 1 ] ; then
    LOG_FILE="$PWD/install.log"
else
    LOG_FILE="$PWD/uninstall.log"
fi

## init LOG_FILE
# backup old log file...
[ ! -d "$LOG_DIR" ] && mkdir -p "$LOG_DIR"
if [ -e "$LOG_FILE" ] ; then
    /bin/mv "$LOG_FILE" "$LOG_FILE.`date +%Y%m%d-%H%M%S`"
fi
# Clean (and create) my log file
${CAT} << __EOL__ > "$LOG_FILE"
###############################################################################

  command : $0 $@

###############################################################################
__EOL__

# Init GREP,CAT,SED,CHMOD,CHOWN variables
define_specific_binary_vars;

${CAT} << __EOT__
###############################################################################
#                                                                             #
#          http://community.centreon.com/projects/centreon-discovery          #
#                          Thanks for using Centreon                          #
#                                                                             #
#                                    v$VERSION                                     #
#                                                                             #
###############################################################################
__EOT__

## Find OS
find_OS;
if [ $? -eq 0 ] ; then
     echo_success "OS found: $DISTRIB" "$ok"
else
     echo_failure "OS not found" "$fail"
     exit 1
fi

BINARIES="rm cp mv ${CHMOD} ${CHOWN} echo more mkdir find ${GREP} ${CAT} ${SED} ${PYTHON} ${GCC}"
## binaries in function $TYPE_INSTALL
if [ "$TYPE_INSTALL" != "central" ] ; then
    BINARIES=$BINARIES" ${NMAP}"
fi    

## binaries/packages in function distrib 
if [ "$DISTRIB" == "DEBIAN" ] || [ "$DISTRIB" == "UBUNTU" ] ; then
    BINARIES=$BINARIES" ${DPKG}"
    if [ "$TYPE_INSTALL" == "poller" ] ; then
	PACKAGES="python-dev"
    else
	PACKAGES="python-dev libmysqlclient-dev"
    fi
elif [ "$DISTRIB" == "REDHAT" ] || [ "$DISTRIB" == "CENTOS" ] ; then
    BINARIES=$BINARIES" ${YUM}"
    if [ "$TYPE_INSTALL" == "poller" ] ; then
	PACKAGES="python-devel"
    else
	PACKAGES="python-devel mysql-devel"
    fi
fi

# Check binaries
write_header "Checking all needed binaries" ""
binary_fail=0
# For the moment, I check if all binary exists in path.
# After, I must look a solution to use complet path by binary
for binary in $BINARIES; do
    if [ ! -e ${binary} ] ; then 
	pathfind "$binary"
	if [ "$?" -eq 0 ] ; then
	    echo_success "${binary}" "$ok"
	else 
	    echo_failure "${binary}" "$fail"
	    log "ERR" "\$binary not found in \$PATH"
		binary_fail=1
	fi
    else
	echo_success "${binary}" "$ok"
    fi
done

# Script stop if one binary wasn't found
if [ "$binary_fail" -eq 1 ] ; then
    echo ""
    echo_info "Please check fail binary/package and retry"
    exit 1
fi

# Check packages for "install" and "update" cases
if [ $UPDATE -ne 2 ] ; then
    check_list_packages;
fi

#if [ $UPDATE -eq 0 ] ; then
#    write_header "Checking all needed packages"
#    error=0
#    for package in $PACKAGES; do
#	echo -n $package
#	check_package $package;
#	if [ $? -eq 0 ] ; then
# 	#package not installed !
#	    display_return "1" "$package"
#	    error=1
#	else
# 	#package installed !
#	    display_return "0" "$package"
#	fi
#    done
#    if [ $error == 1 ]; then
#	echo_info "\nPlease check fail packages and retry"
#	exit 1

if [ $UPDATE -eq 0 ] ; then
    # Case update
    check_old_install $DIR_CONF_DISCO/$FILE_CONF_DISCO;

    echo -n "Loading parameters"
    . $DIR_CONF_DISCO/$FILE_CONF_DISCO
    display_return "$?" "Loading parameters"

    get_centreon_parameters;
  
    # Check version number
    write_header "Checking version number" ""
    check_version "$INSTALL_DIR/bin_version"
    
    write_header "Install Python modules" ""
    install_modPython;
    if [ "$?" -eq 0 ] ; then
	install_agent;
	if [ "$TYPE_INSTALL" != "poller" ] ; then
	    execute_sql_update;
	    
	    install_module;
	fi
    else
	echo_failure "Modules Python weren't installed with success" "$fail"
	echo -e "\tINSTALL ABORT"
	exit 1
    fi
    
else
    if [ $UPDATE -eq 1 ] ; then
        # Case clean install
	
        # License
	write_header "Accepting licence" ""
	echo -e "You will now read Centreon Discovery module Licence.\\n\\tPress enter to continue."
	read 
	tput clear 
	more "$BASE_DIR/LICENSE"
	
	echo ""
	yes_no_default "Do you accept GPL license ?"
	if [ "$?" -ne 0 ] ; then 
	    echo_info "You do not agree to GPL license ? Okay... have a nice day."
	    echo -e "\tINSTALL ABORT"
	    exit 1
	else
	    log "INFO" "You accepted GPL license"
	fi
	
        # Check version number
	write_header "Checking version number"
	check_version "$INSTALL_DIR/bin_version"
	
	write_header "Install Python modules" ""
	install_modPython;
	if [ "$?" -eq 0 ] ; then
	    if [ "$TYPE_INSTALL" != "poller" ] ; then
		get_centreon_configuration_location;
		get_centreon_parameters;
		if [ "$?" -eq 0 ] ; then
		    echo ""
		    echo_success "Parameters were loaded with success" "$ok"
		    install_module;
		    # NE PAS FACTORISER : "install_agent"
		    install_agent;
		else
		    echo -e "\nUnable to load all parameters in \"$FILE_CONF\""
		    echo -e "\tINSTALL ABORT"
		    exit 1
		fi
	    else
		install_agent;
	    fi
	    create_CentDiscoConf;
	else
	    echo_failure "Modules Python weren't installed with success" "$fail"
	    echo -e "\tINSTALL ABORT"
	    exit 1
	fi
    else
        # Case uninstall
	write_header "Uninstall" ""
	yes_no_default "Are you sure to uninstall totally Discovery module ?"
	if [ "$?" -eq 0 ] ; then
	    write_header "Detect previous installation"
	    if [ -f $DIR_CONF_DISCO/$FILE_CONF_DISCO ] && [ `grep "# Discovery version" $DIR_CONF_DISCO/$FILE_CONF_DISCO | wc -l` -eq 1 ] ; then
		echo -n "Loading parameters"
		. $DIR_CONF_DISCO/$FILE_CONF_DISCO
		display_return "$?" "Loading parameters"
		
	    else
		echo_failure "Finding configuration file in: $DIR_CONF_DISCO" "$fail"
		exit 1;
	    fi
	    get_centreon_parameters;
	    
	    write_header "Deleting" ""
	    delete_module;
	else
	    echo -e "Uninstall process canceled"
	    exit 0;
	fi
    fi
fi

#    if [ "$TYPE_INSTALL" == "poller" ] ; then
#	install_modPython;
#	if [ "$?" -eq 0 ] ; then
#	    install_agent;
#        else
#	    echo_failure "Modules Python weren't installed with success" "$fail"
#	    echo -e "\tINSTALL ABORT"
#	    exit 1
#	fi
#    else
#	get_centreon_configuration_location;
#	get_centreon_parameters;
#	if [ "$?" -eq 0 ] ; then
#	    echo_success "Parameters were loaded with success" "$ok"
#	else
#	    echo -e "\nUnable to load all parameters in \"$FILE_CONF\""
#	    echo -e "\tINSTALL ABORT"
#	    exit 1
#	fi
#	install_modPython;
#	if [ "$?" -eq 0 ] ; then
#	    install_agent;
#	    install_module;
#       else
#	    echo_failure "Modules Python weren't installed with success" "$fail"
#	    echo -e "\tINSTALL ABORT"
#	    exit 1
#       fi
#    fi

if [ $UPDATE -eq 0 ] ; then
    echo_success "\nThe $LOG_VERSION update is finished" "$ok"
elif [ $UPDATE -eq 1 ] ; then
    echo_success "\nThe $LOG_VERSION install is finished" "$ok"
else
    echo_success "\nThe $LOG_VERSION uninstall is finished" "$ok"
fi

echo -e  "See README and the log file for more details.\n"

${CAT} << __EOT__
###############################################################################
#                                                                             #
#      Go to the URL : http://your-server/centreon/                           #
#                   	to finish the setup in clean install case             #
#                                                                             #
#  Report bugs at                                                             #
#    http://community.centreon.com/projects/centreon-discovery/issues/new     #
#                                                                             #
###############################################################################
__EOT__

exit 0
