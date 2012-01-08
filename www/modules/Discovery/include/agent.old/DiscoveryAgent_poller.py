#!/usr/bin/python
# -*- coding: iso-8859-15 -*-

import socket
import sys
import nmap

def connectToCentral():
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
				data="#reply#"
				conn.send(data)
			elif data.startswith("#scanip#"):
				plage = data.replace("#scanip#","")
				scanRangeIP(plage, conn, s)
				data = "#scanip#done"
				conn.send(data)	
	except KeyboardInterrupt:
		print "Connection aborted"
		s.close()
		conn.close()


def scanRangeIP(rangeIP, conn, s):
	try:
		nm = nmap.PortScanner()         # instantiate nmap.PortScanner object
	except nmap.PortScannerError:
		print('Nmap not found', sys.exc_info()[0])
		return
	except:
		print("Unexpected error:", sys.exc_info()[0])
		return

	# Scan NMAP en ARP sur un réseau spécifié
#	nm.scan(hosts=rangeIP, arguments='-n -sP -PR ') # ARP
	nm.scan(hosts=rangeIP, arguments='-n -sU -p161 -T5 ') # Port 161

	hosts_list = [(x, nm[x]['status']['state']) for x in nm.all_hosts()]
	for host, status in hosts_list:
		if status=='up' :
#			print('{0}:{1}'.format(host, status))
			state = "#state#%s#%s"%(host,status)
			state = "%25s"%state
			conn.send(state)
			print "Envoi : ",state.lstrip()
	print "#scanip#done"		

if __name__ == '__main__':
	connectToCentral();
