------------------------------------------------
#	Description:
------------------------------------------------
Name: Centreon-Discovery
Version: 2.3
Distribution: Debian/CentOS

------------------------------------------------
#	Requirements
------------------------------------------------
* Nmap (version 5.00 or highter) must be installed on each poller. In the case of a centralized architecture, nmap must be installed on the central.
* Python (version 2.4) must be installed too on the central and on every pollers.
* The package python-dev (or python-devel depending on linux distribution) must be installed too on the central and on every pollers.
* The package mysql-devel (on CentOS) must be installed on the central.

------------------------------------------------
#	Setup:
------------------------------------------------
1. Get last version of Discovery module here http://community.centreon.com/projects/centreon-discovery

Go to the page http://community.centreon.com/projects/centreon-discovery

2. Extract files from the archive

	# tar xzf centreon-discovery-x.x.tar.gz

3. Launch setup script

	# cd centreon-discovery-x.x/
	# chmod 755 install.sh

Choose the setup type :
 - as poller : "-t poller"
 - as central : "-t central"
 - as both : "-t both"
 
Example for poller : # ./install.sh -i -t poller
The "-i-" option allows you to specify the setup mode

4. Go to Centreon interface

Reach menu 'Administration > Modules > Centreon-Discovery'
The Discovery module must be listed but not installed.
Click on the icon to the right "Install Module" then on button "install module" to launch install.

5. Execute Poller agent (installed in /etc/centreon-discovery by default) in root

	# /etc/centreon-discovery/DiscoveryAgent_poller.py &
(or $# python26 /etc/centreon-discovery/DiscoveryAgent_poller.py &)

------------------------------------------------
#	UPDATE
------------------------------------------------
1. Go to Centreon interface

Reach menu 'Administration > Modules > Centreon-Discovery'
The Discovery module must be listed and installed.
Click on the icon to the right "Uninstall Module" then on button "OK" to confirm.

2. Delete agent(s)

Check that poller agent is stopped.

Delete agent(s)
	# cd /etc/centreon-discovery
	# rm -f *.py

Change directory "/etc/centreon-discovery" depending on your configuration.

3. Delete module

Delete module
	# cd /usr/local/centreon/www/modules
	# rm -rf Discovery

Change directory "/usr/local/centreon/www/modules" depending on your configuration.

4. Go to "#	Installation:"

------------------------------------------------
#	UNINSTALL (version 2.3 minimum)
------------------------------------------------
1. Get last version of Discovery module here http://community.centreon.com/projects/centreon-discovery

Go to the page http://community.centreon.com/projects/centreon-discovery

2. Extract files from the archive

	# tar xzf centreon-discovery-x.x.tar.gz
	
3. Uninstall module

Launch uninstall script
	# ./install.sh -r /usr/share/centreon-discovery

Once uninstall process done without error, the module has been entirely deleted from Centreon and your system.

------------------------------------------------
#	FAQ
------------------------------------------------
Q: Which install type I am into: poller? central? ou both ?

A1 : poller : if you have just Nagios installed on your server and this last sends to Central the collected informations. Look the list in Centreon interface : "Configuration" --> "Centreon"  --> column localhost = NO)
A2 : central : if you have just Nagios installed on your server and this last recieves from poller(s) the collected informations.
A3 : both : if you have at the same time Nagios and Centreon installed on your server. Look the list in Centreon interface : "Configuration" --> "Centreon" --> column localhost = YES)

Q:	An error appears when the Python MySQLdb module needs to create a file in the root folder of web server. Some rights are missing (more details below).

A:	The Apache user must become owner of www folder
	$#chown www-data:www-data /var/www

Q : How to install the lastest nmap release on CentOS ?

R : rpm -vhU http://nmap.org/dist/nmap-5.51-1.i386.rpm
------------------------------------------------
#	Useful links :
------------------------------------------------
	Centreon-Discovery
	
Module:			http://community.centreon.com/projects/centreon-discovery
Documentation:	http://community.centreon.com/projects/centreon-discovery/documents
SVN:			http://svn.modules.centreon.com/centreon-discovery/
Forum:			http://forum.centreon.com/forumdisplay.php/36-Centreon-discovery-s-modules

	Centreon
	
Wiki:			http://doc.centreon.com/
SVN:			http://svn.centreon.com/
Forum:			http://forum.centreon.com/

------------------------------------------------
#	ERROR
------------------------------------------------
Traceback (most recent call last):
  File "./modules/Centreon-Discovery/DiscoveryAgent_central.py", line 4, in <module>
    import MySQLdb
  File "/usr/lib/python2.5/site-packages/PIL/__init__.py", line 19, in <module>

  File "build/bdist.linux-i686/egg/_mysql.py", line 7, in <module>
  File "build/bdist.linux-i686/egg/_mysql.py", line 4, in __bootstrap__
  File "build/bdist.linux-i686/egg/pkg_resources.py", line 882, in resource_filename
  File "build/bdist.linux-i686/egg/pkg_resources.py", line 1351, in get_resource_filename
  File "build/bdist.linux-i686/egg/pkg_resources.py", line 1373, in _extract_resource
  File "build/bdist.linux-i686/egg/pkg_resources.py", line 962, in get_cache_path
  File "build/bdist.linux-i686/egg/pkg_resources.py", line 928, in extraction_error
pkg_resources.ExtractionError: Can't extract file(s) to egg cache

The following error occurred while trying to extract file(s) to the Python egg
cache:

  [Errno 13] Permission denied: '/var/www/.python-eggs'

The Python egg cache directory is currently set to:

  /var/www/.python-eggs

Perhaps your account does not have write access to this directory?  You can
change the cache directory by setting the PYTHON_EGG_CACHE environment
variable to point to an accessible directory.
