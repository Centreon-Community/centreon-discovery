#!/usr/bin/python
# -*- coding: iso-8859-15 -*-

import socket
import sys
import nmap
import time
import commands
import threading

# For debug only, 0:log // 1:console
LOG_FILE = "@AGENT_DIR@" + "/log/"+time.strftime('%Y%m',time.localtime())  +"_Poller_DiscoveryAgent.log"
#LOG_FILE = "log/"+time.strftime('%Y%m',time.localtime())  +"_Poller_DiscoveryAgent.log"
MODE_DEBUG = 0

def connectToCentral():
	# Check the debug mode (O:log // 1:console)
	if MODE_DEBUG ==  0:
		saveout = sys.stdout
		saveerr = sys.stderr
		flog = open(LOG_FILE, 'a')
		sys.stdout = flog	
		sys.stderr = flog
		print "##############################################"
		print "### Poller log : " + time.strftime('%Y/%m/%d %H:%M:%S', time.localtime()) + "###"
		flog.flush()
	try:
		HOST = ''                 	      # Symbolic name meaning all available interfaces
		PORT = 1080             	      # Arbitrary non-privileged port
		s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
		s.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
		s.bind((HOST, PORT))
		s.listen(1)
		while 1:
			conn, addr = s.accept()
			print 'Connected by', addr
		    	data = conn.recv(256)
			if data.startswith("#echo#"):
				print 'DiscoveryAgent_poller.py : STATUS_POLLER'
				data="#reply#"
				conn.send(data)
			elif data.startswith("#scanip#"):
				strSplit = data.lstrip().split("#")
				plage = strSplit[2]
				oid_hostname = strSplit[3]
				oid_os = strSplit[4]
				print 'DiscoveryAgent_poller.py : SCAN_RANGEIP for ' + plage
				scanRangeIP(plage, conn, s, oid_hostname, oid_os)
				data = "#scanip#done"
				conn.send(data)			
			if MODE_DEBUG==0:
				flog.flush()
	except KeyboardInterrupt:
		print "Connection aborted"
		conn.close()
		s.close()
	finally:
		# Exit properly
		if MODE_DEBUG ==  0:
			print "\n"
			sys.stdout = saveout
			sys.stderr = saveerr
			flog.close()	


def scanRangeIP(rangeIP, conn, s,  oid_hostname,  oid_os):
	try:
		nm = nmap.PortScanner()         # instantiate nmap.PortScanner object
	except nmap.PortScannerError:
		print('Nmap not found or Nmap version < 5.00', sys.exc_info()[0])
		return
	except:
		print("Unexpected error:", sys.exc_info()[0])
		return

	# Scan NMAP en ARP sur un réseau spécifié
#	nm.scan(hosts=rangeIP, arguments='-n -sP -PR ') # ARP
	try:
		nm.scan(hosts=rangeIP, arguments='-n -sU -p161 -T5 ') # Port 161

		hosts_list = [(x, nm[x]['status']['state']) for x in nm.all_hosts()]
		thread_host_list = []
		for host, status in hosts_list:
			if status=='up' :
				thread_host = threading.Thread(None, getHostOS, None, (host,oid_hostname,oid_os,status,conn,))
				thread_host.start()
				thread_host_list.append(thread_host)
		for thread in thread_host_list:
			thread.join()
		print "#scanip#done"		
	except nmap.PortScannerError:
		print "Error using connection. Scan stopped"
		return

def getHostOS(host,oid_hostname,oid_os,status,conn):
	req_snmp = "snmpget -c public -v 2c %s %s" % (host, oid_hostname)
	hostname = commands.getoutput(req_snmp)
	if hostname.startswith("Timeout"):
		hostname = "* TimeOut SNMP *"
		os_name = "* TimeOut SNMP *"
	else:
		hostname = hostname.replace(hostname, hostname[hostname.find("STRING")+len("STRING: "):])
		req_snmp = "snmpget -c public -v 2c %s %s" % (host, oid_os)
		os_name = commands.getoutput(req_snmp)
		os_name = os_name.replace("SNMPv2-MIB::sysDescr.0 = STRING: ","")
#			print('{0}:{1}'.format(host, status))
	state = "#state#%s#%s#%s#%s"%(host,status,hostname,os_name)
	state = "%475s"%state
	conn.send(state)
	print "Send : ",state.lstrip()


if __name__ == '__main__':
	# Connection to central	
	connectToCentral();
