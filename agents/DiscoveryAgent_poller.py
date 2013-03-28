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

import socket
import sys
import nmap
import time
import commands
import threading
import re
from Crypto.Cipher import AES

# For debug only, 0:log // 1:console
LOG_FILE = "@AGENT_DIR@" + "/log/"+time.strftime('%Y%m',time.localtime())  +"_Poller_DiscoveryAgent.log"
#LOG_FILE = "log/"+time.strftime('%Y%m',time.localtime())  +"_Poller_DiscoveryAgent.log"
MODE_DEBUG = 0

KEY = "@KEY@"
#KEY = ""

# Encryption/Decryption function using key
def enc_dec(msg, encrypt):
	obj_crypt = AES.new(KEY, AES.MODE_CFB)
	if encrypt == 1:
		return obj_crypt.encrypt(msg)
	else:
		return obj_crypt.decrypt(msg)

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
				if KEY != "":
					data = enc_dec(data, 0)
				if data.startswith("#echo#"):
					print 'DiscoveryAgent_poller.py : STATUS_POLLER'
					data="#reply#"
					if KEY != "":
						data = enc_dec(data, 1)
					conn.send(data)
				elif data.startswith("#scanip#"):
					strSplit = data.lstrip().split("#$#")
					plage = strSplit[1]
					args = [strSplit[2], strSplit[3], strSplit[4], strSplit[5], strSplit[6], strSplit[7], strSplit[8], strSplit[9], strSplit[10], strSplit[11], strSplit[12], strSplit[13], strSplit[14], strSplit[15], strSplit[16], strSplit[17], strSplit[18]]
					print 'DiscoveryAgent_poller.py : SCAN_RANGEIP for ' + plage
					scanRangeIP(plage, conn, s, args)
					data = "#scanip#done"		
					if KEY != "":
						data = enc_dec(data, 1)
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


def scanRangeIP(rangeIP, conn, s, args):
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
		rc = re.compile('T[0-5]')
		nmap_args = "-n -sU -p%s -%s --max_retries=%s --host_timeout=%sms --max_rtt_timeout=%sms" % (args[7], rc.findall(args[0])[0], args[1], args[2], args[3])
		print(nmap_args)
		
		#nmap_args = "-n -sU -p%s -%s --max_retries=%s --max_rtt_timeout=%s" % (args[7], rc.findall(args[0])[0], args[1],args[3])
		nm.scan(hosts=rangeIP, arguments=nmap_args)

		hosts_list = [(x, nm[x]['status']['state']) for x in nm.all_hosts()]
		thread_host_list = []
		for host, status in hosts_list:
			if status=='up' :
				thread_host = threading.Thread(None, getHostOS, None, (host,args,status,conn,))
				thread_host.start()
				thread_host_list.append(thread_host)
		for thread in thread_host_list:
			thread.join()
		print "#scanip#done"		
	except nmap.PortScannerError:
		print "Error using nmap. Scan stopped"
		return

def getHostOS(host,args,status,conn):
	complete_snmp=" -v %s -t %s -r %s -O nq %s:%s %s 2>&1 | grep %s" % (args[6], args[15], args[16], host, args[7], args[4], args[4])
	hostname=""
	i=0
	communities=[]
	#SNMP v1-v2c
	if args[6] != '3':
		communities=args[8].lstrip().split("||")
		while hostname == '' and i < len(communities):
			req_snmp="snmpget -c %s" % (communities[i])
			req_snmp=req_snmp+complete_snmp
			print req_snmp
			hostname = commands.getoutput(req_snmp)	
			i+=1
		save_community=communities[i-1]
	#SNMP v3
	else:
		if args[10] == 'NoAuthNoPriv':
			req_snmp = "snmpget -u %s -l %s" % (args[9], args[10])
		elif args[10] == 'AuthNoPriv' :
			req_snmp = "snmpget -u %s -l %s -a %s -A %s" % (args[9], args[10], args[11], args[12])
		elif args[10] == 'AuthPriv' :
			req_snmp = "snmpget -u %s -l %s -a %s -A %s -x %s -X %s" % (args[9], args[10], args[11], args[12], args[13], args[14])
		req_snmp=req_snmp+complete_snmp
		print req_snmp
		hostname = commands.getoutput(req_snmp)
		save_community="nocomm"
	if hostname == "":
		hostname = "* TimeOut SNMP *"
		os_name = "* TimeOut SNMP *"
		save_community="nocomm"
	else:
		hostname = hostname.split(' ',1)[1]
		#hostname = hostname.replace(hostname, hostname[hostname.find("STRING")+len("STRING: "):])
		complete_snmp=" -v %s -t %s -r %s -O nq %s:%s %s 2>&1 | grep %s" % (args[6], args[15], args[16], host, args[7], args[5], args[5])
		os_name=""		
		i=0
		#SNMP v1-v2c
		if args[6] != '3':
			communities=args[8].lstrip().split("||")
			while os_name == '' and i < len(communities):
				req_snmp="snmpget -c %s" % (communities[i])
				req_snmp=req_snmp+complete_snmp
				print req_snmp
				os_name = commands.getoutput(req_snmp)	
				i+=1
		#SNMP v3
		else:
			if args[10] == 'NoAuthNoPriv':
				req_snmp = "snmpget -u %s -l %s" % (args[9], args[10])
			elif args[10] == 'AuthNoPriv' :
				req_snmp = "snmpget -u %s -l %s -a %s -A %s" % (args[9], args[10], args[11], args[12])
			elif args[10] == 'AuthPriv' :
				req_snmp = "snmpget -u %s -l %s -a %s -A %s -x %s -X %s" % (args[9], args[10], args[11], args[12], args[13], args[14])
			req_snmp=req_snmp+complete_snmp
		
		os_name = commands.getoutput(req_snmp)
		os_name = os_name.split(' ',1)[1]
	state = "#state#$#%s#$#%s#$#%s#$#%s#$#%s"%(host,status,hostname,os_name,save_community)
	state = "%475s"%state
	print "Send : ",state.lstrip()
	if KEY != "":
		state = enc_dec(state, 1)
	conn.send(state)


if __name__ == '__main__':
	# Connection to central	
	connectToCentral();
