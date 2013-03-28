#!/usr/bin/python
# -*- coding: iso-8859-15 -*-

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
# Developped by: Baptiste G.
#
# WEBSITE: http://community.centreon.com/projects/centreon-discovery
# SVN: http://svn.modules.centreon.com/centreon-discovery

import os
import socket
import sys
import re
import time
import threading
from Crypto.Cipher import AES

# Fix the problem of PYTHON_EGG_CACHE
os.environ['PYTHON_EGG_CACHE'] = '/tmp'

import MySQLdb

# For debug only, 0:log // 1:console
LOG_FILE = "@AGENT_DIR@" + "/log/"+time.strftime('%Y%m',time.localtime())  +"_Central_DiscoveryAgent.log"
#LOG_FILE = "log/"+time.strftime('%Y%m',time.localtime())  +"_Central_DiscoveryAgent.log"
MODE_DEBUG = 0

ID = 0
IP = 1
PLAGE = 2
CIDR = 3
CONF_PATH = "@CENTREON_ETC@" + "/centreon.conf.php"
#CONF_PATH = "/etc/centreon/centreon.conf.php"

KEY = "@KEY@"
#KEY = ""

# Encryption/Decryption function using key
def enc_dec(msg, encrypt):
	obj_crypt = AES.new(KEY, AES.MODE_CFB)
	if encrypt == 1:
		return obj_crypt.encrypt(msg)
	else:
		return obj_crypt.decrypt(msg)

# Connection to the database
def connectToDB():
	fd = open(CONF_PATH, "r")
	for ligne in fd:
		if ligne.lstrip().startswith("$conf_centreon['hostCentreon']"):
			conf_host = ligne.replace("$conf_centreon['hostCentreon'] = \"","").replace("\";","").rstrip()
		if ligne.lstrip().startswith("$conf_centreon['user']"):
			conf_user = ligne.replace("$conf_centreon['user'] = \"","").replace("\";","").rstrip()
		if ligne.lstrip().startswith("$conf_centreon['password']"):
			conf_password = ligne.replace("$conf_centreon['password'] = \"","").replace("\";","").rstrip()
		if ligne.lstrip().startswith("$conf_centreon['db']"):
			conf_db = ligne.replace("$conf_centreon['db'] = \"","").replace("\";","").rstrip()
	fd.close()	
	db=MySQLdb.connect(host=conf_host,user=conf_user,passwd=conf_password,db=conf_db)
	return db

# Scan a range of IP taken in the database
def scanRangeIP(db):
	c=db.cursor()
	# Set done = 1 while checking pollers
	c.execute("""UPDATE mod_discovery_rangeip SET done = 1 WHERE id=0""")
	c.execute("""SELECT R.id, S.ns_ip_address, R.plage, R.cidr, R.nmap_profil, R.nmap_max_retries, R.nmap_host_timeout, R.nmap_max_rtt_timeout, R.oid_hostname, R.oid_os, R.snmp_version, R.snmp_port, R.snmp_community, R.snmp_v3login, R.snmp_v3level, R.snmp_v3authtype, R.snmp_v3authpass, R.snmp_v3privtype, R.snmp_v3privpass, R.snmp_timeout, R.snmp_retries\
			 FROM nagios_server S, mod_discovery_rangeip R\
			 WHERE S.id=R.nagios_server_id AND R.poller_status=1 AND R.done=1;""")
	pollers = c.fetchall()
	thread_scan_list = []
	for poller in pollers:
		print "Scan the range IP " + str(poller[PLAGE]) + " (range #" + str(poller[ID])  +")"
		thread_scan = threading.Thread(None, getHostsFromPoller, None, (poller, ))
		thread_scan.start()
		thread_scan_list.append(thread_scan)
	for thread in thread_scan_list:
		thread.join()
	# Set done = 2 when scan is done
	c.execute("""UPDATE mod_discovery_rangeip SET done = 2 WHERE id=0""")

# Check the status of the pollers needed to scan range of IP
def statusPoller(db, idpoller=""):
	c=db.cursor()
	if idpoller is "":
		c.execute("""SELECT R.id, S.ns_ip_address, R.nagios_server_id\
			FROM nagios_server S, mod_discovery_rangeip R\
			WHERE S.id=R.nagios_server_id\
			GROUP BY R.nagios_server_id;""")
	else:
		c.execute("""SELECT R.id, S.ns_ip_address, R.nagios_server_id\
			FROM nagios_server S, mod_discovery_rangeip R\
			WHERE S.id=R.nagios_server_id\
			AND R.nagios_server_id=%s
			GROUP BY R.nagios_server_id;""",(idpoller))
	pollers = c.fetchall()
	thread_status_list = []
	for poller in pollers:
		thread_status = threading.Thread(None, checkStatusPoller, None, (poller,))
		thread_status.start()
		thread_status_list.append(thread_status)
	for thread in thread_status_list:
		thread.join()

# Check the status of the poller in argument
def checkStatusPoller(poller):
	host = poller[IP]
	db2 = connectToDB()
	c=db2.cursor()
	try:
		s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
		s.settimeout(3)
		s.connect((host, 1080))
		data = "#echo#"
		if KEY != "":
			data = enc_dec(data, 1)
		s.send(data)
		rcv = s.recv(512)
		if KEY != "":
			rcv = enc_dec(rcv, 0)
		if rcv.startswith("#reply#"):
			req = "UPDATE mod_discovery_rangeip SET poller_status = %d WHERE nagios_server_id=%d" % (1, poller[2])
			print "Poller #%d (%s) STATUS_POLLER=1 (OK)" % (poller[2], host)
			c.execute(req)
		s.close()
		time.sleep(0.01)
	except socket.error:
		req = "UPDATE mod_discovery_rangeip SET poller_status = %d WHERE nagios_server_id=%d" % (2, poller[2])
		print "Poller #%d (%s) STATUS_POLLER=2 (NOK)" % (poller[2], host)
		c.execute(req)
		s.close()
		time.sleep(0.01)
	except KeyboardInterrupt:
		print "Connection aborted"
		s.close()
	return

# Scan IP range 
def getHostsFromPoller(poller):
	db2 = connectToDB()
	c=db2.cursor()
	try:
		s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
		s.connect((poller[IP], 1080))
		data = "#scanip#$#%s/%s#$#%s#$#%s#$#%s#$#%s#$#%s#$#%s#$#%s#$#%s#$#%s#$#%s#$#%s#$#%s#$#%s#$#%s#$#%s#$#%s#$#%s"%(poller[PLAGE],poller[CIDR],poller[4],poller[5],poller[6],poller[7],poller[8],poller[9],poller[10],poller[11],poller[12],poller[13],poller[14],poller[15],poller[16],poller[17],poller[18],poller[19],poller[20])
		if KEY != "":
			data = enc_dec(data, 1)
		s.send(data)
		rcv = ""
		rc = re.compile('[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+')
		req = "DELETE FROM mod_discovery_results WHERE plage_id=%d" % (poller[ID])
		c.execute(req)
		while not rcv.rstrip().endswith("#scanip#done"):
			rcv = s.recv(475)
			if KEY != "":
				rcv = enc_dec(rcv, 0)
			if rcv.strip() == "":
				print "Error while receiving data.\nCheck connection of poller.\nEND"
				req = "UPDATE mod_discovery_rangeip SET done = %d WHERE done<>2 AND nagios_server_id = (SELECT nagios_server_id FROM  (SELECT * FROM mod_discovery_rangeip) AS d1 WHERE d1.id=%d)" % (3, poller[ID])
				c.execute(req)
				return
			print "SCAN_RANGEIP : " +rcv.lstrip()
			if rcv.lstrip().startswith("#state#"):
				strSplit = rcv.split("#$#", 5)
				#adr_ip = rc.findall(rcv)[0]
				adr_ip = strSplit[1]
				hostname = strSplit[3]
				os_name = strSplit[4]
				community = strSplit[5]
				req = "INSERT INTO mod_discovery_results(ip, plage_id,hostname,os,snmp_community) VALUES ('%s', %d, '%s', '%s', '%s')" % (adr_ip, poller[ID], hostname, os_name,community)
				c.execute(req)
		req = "UPDATE mod_discovery_rangeip SET done = %d WHERE id=%d" % (2, poller[ID])
		c.execute(req)
		s.close()
		time.sleep(0.01)
	except socket.error:
		print "error req connection"
		s.close()
		time.sleep(0.01)
	except KeyboardInterrupt:
		print "Connection aborted"
		s.close()
	return

if __name__ == '__main__':
	'''
	Two parameters available :
	SCAN_RANGEIP  : Scan different range IP registered in DB
	STATUS_POLLER : Check poller status
	'''
	# Check the debug mode (O:log // 1:console)
	if MODE_DEBUG ==  0:
		saveout = sys.stdout
		saveerr = sys.stderr
		flog = open(LOG_FILE, 'a')
		sys.stdout = flog	
		sys.stderr = flog
		print "##############################################"
		print "### Central log : " + time.strftime('%Y/%m/%d %H:%M:%S', time.localtime()) + "###"
	print "   --- Call DiscoveryAgent_Central ---"
	# Bad number of args to call the script
	if len(sys.argv) not in [2,3] :
		print "You have to precise which action you want to do by using args\n"
		print " - STATUS_POLLER to check the pollers"
		print " - SCAN_RANGEIP to scan a range IP"			
	# Check the status of the pollers
	else:
		if sys.argv[1] == "STATUS_POLLER":
			print "Check the status of poller"
			db=connectToDB()
			if len(sys.argv) == 2:		
				statusPoller(db)
			else:
				statusPoller(db, sys.argv[2])
		# Scan a range of IP
		elif sys.argv[1] == "SCAN_RANGEIP":
			print "Scan range IP"
			db=connectToDB()
			scanRangeIP(db)
		#  The argument given is not correct
		else:
			print "Bad argument : SCAN_RANGEIP / STATUS_POLLER"
		print "   --- End DiscoveryAgent_Central ---"
	# Exit properly
	if MODE_DEBUG ==  0:
		print "\n"
		sys.stdout = saveout
		sys.stderr = saveerr
		flog.close()	
