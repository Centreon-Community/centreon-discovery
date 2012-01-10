#!/usr/bin/python
# -*- coding: iso-8859-15 -*-

import MySQLdb
import socket
import sys
import re

ID = 0
IP = 1
PLAGE = 2
CIDR = 3
CONF_PATH = "/etc/centreon/centreon.conf.php"

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
#	c.execute("""SELECT R.id, S.ns_ip_address, R.plage, R.cidr\
#			 FROM nagios_server S, mod_discovery_rangeip R\
#			 WHERE S.id=R.nagios_server_id AND R.poller_status=1 AND R.done=1\
#			 GROUP BY R.nagios_server_id;""")
	c.execute("""SELECT R.id, S.ns_ip_address, R.plage, R.cidr\
			 FROM nagios_server S, mod_discovery_rangeip R\
			 WHERE S.id=R.nagios_server_id AND R.poller_status=1 AND R.done=1;""")
	pollers = c.fetchall()
	for poller in pollers:
		print "ScanRangeIP : " + str(poller[ID]) + " pour la plage : " + str(poller[PLAGE])
		getHostsFromPoller(poller)

# Check the status of the pollers needed to scan range of IP
def statusPoller(db, idpoller=""):
	c=db.cursor()
	if idpoller is "":
#		c.execute("""SELECT R.id, S.ns_ip_address\
#			FROM nagios_server S, mod_discovery_rangeip R\
#			WHERE S.id=R.nagios_server_id\
#			GROUP BY R.nagios_server_id;""")
		c.execute("""SELECT R.id, S.ns_ip_address\
			FROM nagios_server S, mod_discovery_rangeip R\
			WHERE S.id=R.nagios_server_id;""")
	else:
		c.execute("""SELECT R.id, S.ns_ip_address\
			FROM nagios_server S, mod_discovery_rangeip R\
			WHERE S.id=R.nagios_server_id\
			AND R.nagios_server_id=%s
			GROUP BY R.nagios_server_id;""",(idpoller))
	pollers = c.fetchall()
	for poller in pollers:
		print "checkStatusPoller : " + str(poller[ID])
		checkStatusPoller(poller)

# Check the status of the poller in argument
def checkStatusPoller(poller):
	host = poller[IP]
	c=db.cursor()
	try:
		s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
		s.settimeout(3)
		s.connect((host, 1080))
		data = "#echo#"
		print host + " : " + data
		s.send(data)
		rcv = s.recv(512)
		if rcv.startswith("#reply#"):
			req = "UPDATE mod_discovery_rangeip SET poller_status = %d WHERE id=%d" % (1, poller[ID])
			print "DiscoveryAgent_central.py : STATUS_POLLER=1 pour Poller[ID]=" + str(poller[ID])
			c.execute(req)
		s.close()
	except socket.error:
		req = "UPDATE mod_discovery_rangeip SET poller_status = %d WHERE id=%d" % (2, poller[ID])
		print "DiscoveryAgent_central.py : STATUS_POLLER=2 pour Poller[ID]=" + str(poller[ID])
		c.execute(req)
	except KeyboardInterrupt:
		print "Connection aborted"
		s.close()

# 
def getHostsFromPoller(poller):
	c=db.cursor()
	try:
		s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
		s.connect((poller[IP], 1080))
		data = "#scanip#%s/%s"%(poller[PLAGE],poller[CIDR])
		s.send(data)
		rcv = ""
		rc = re.compile('[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+')
		req = "DELETE FROM mod_discovery_results WHERE plage_id=%d" % (poller[ID])
		c.execute(req)
		while not rcv.rstrip().endswith("#scanip#done"):
			rcv = s.recv(25)
			print "SCAN_RANGEIP : " +rcv.lstrip()
			if rcv.lstrip().startswith("#state#"):
				adr_ip = rc.findall(rcv)[0]
				print adr_ip
				req = "INSERT INTO mod_discovery_results(ip, plage_id) VALUES ('%s', %d)" % (adr_ip, poller[ID])
				c.execute(req)
		req = "UPDATE mod_discovery_rangeip SET done = %d WHERE id=%d" % (2, poller[ID])
		c.execute(req)
		s.close()
	except socket.error:
		print "error req connection"
	except KeyboardInterrupt:
		print "Connection aborted"
		s.close()

if __name__ == '__main__':
	'''
	Two parameters available :
	SCAN_RANGEIP  : Scan different range IP registered in DB
	STATUS_POLLER : Check poller status
	'''
	print "   --- Appel DiscoveryAgent_Central ---"
	# Bad number of args to call the script
	if len(sys.argv) not in [2,3] :
		print "You have to precise which action you want to do by using args"
		sys.exit()
	# Check the status of the pollers
	if sys.argv[1] == "STATUS_POLLER":
		print "STATUS_POLLER -->"
		db=connectToDB()
		if len(sys.argv) == 2:		
			statusPoller(db)
		else:
			statusPoller(db, sys.argv[2])
	# Scan a range of IP
	elif sys.argv[1] == "SCAN_RANGEIP":
		print "SCAN_RANGEIP -->"
		db=connectToDB()
		scanRangeIP(db)
	#  The argument given is not correct
	else:
		print "Bad argument : SCAN_RANGEIP / STATUS_POLLER"
		sys.exit()
	print "---------> End"
