#!/bin/sh

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
# Developped by: Sub2.13
#
# WEBSITE: http://community.centreon.com/projects/centreon-discovery
# SVN: http://svn.modules.centreon.com/centreon-discovery

### BEGIN INIT INFO
# Provides:           discovery-agent.sh
# Required-Start:     $all
# Required-Stop:      $all
# Default-Start:      2 3 4 5
# Default-Stop:       0 1 6
# Short-Description:  Discovery Agent poller
# Description:        Poller agent for centreon-discovery module
### END INIT INFO

DIR=@AGENT_DIR@
#DIR=/usr/share

case "$1" in
    start)
        echo -n "Starting agent poller :"
	pid=`ps ax | grep "DiscoveryAgent_poller.py" | grep -v grep | awk '{print $1}'`
	if [ -z $pid ] ; then
	    /usr/bin/python $DIR/DiscoveryAgent_poller.py &
	    echo " done."
	    exit 0			
	else
	    echo " failed. DiscoveryAgent_poller.py is stopping ?"
	    exit 1
	fi
        ;;
    stop)
	echo -n "Stopping agent poller :"
	pid=`ps ax | grep "DiscoveryAgent_poller.py" | grep -v grep | awk '{print $1}'`
	if [ -z $pid ] ; then
	    echo " failed. DiscoveryAgent_poller.py is running ?"
	    exit 1
	else
	    kill $pid
	    echo " done."
	    exit 0
	fi
        ;;
    status)
	echo -n "Status agent poller :"
	if [ `ps ax | grep "DiscoveryAgent_poller.py" | grep -v grep | wc -l` -eq 1 ] ; then
            echo " started. (PID : `ps ax | grep "DiscoveryAgent_poller.py" | grep -v grep | awk '{print $1}'`)"		
	    exit 0
        else
            echo " stopped."
            exit 1
        fi
        ;;				
    restart)
	$0 stop
	$0 start
	;;
    *)
        echo "Usage: discovery-agent {start|stop|restart|status}"
        exit 1
        ;;
esac
