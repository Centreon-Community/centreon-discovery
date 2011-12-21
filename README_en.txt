#	Description:

Name: Centreon-Discovery
Version: 0.1

User Apache : www-data
Group apache : www-data
Path www : /var/www
Path centreon : /usr/local/centreon/

#	Installation:

1. Get last version of Discovery module here http://community.centreon.com/projects/centreon-discovery

Go to the page http://community.centreon.com/projects/centreon-discovery

2. Extract the archive

$# tar xzf centreon-discovery-x.x.tar.gz

3. Launch install of modules Python 

$# cd centreon-discovery-x.x/modPython
$# chmod 755 install_python_modules.sh
$# ./install_python_modules.sh

4. Copy files

$# cd .../Centreon-Discovery
$# cp -R www/modules/Centreon-Discovery /usr/local/centreon/www/modules
$# cd /usr/local/centreon/www/modules/
$# chown -R www-data:www-data Centreon-Discovery
$# chmod 744 Centreon-Discovery/include/agent/*

5. Go to interface of Centreon

Reach  menu 'Administration > Modules > Centreon-Discovery
The Discovery module must be in modules list but not installed.
Clic on the icon at right ""Install Module" then on the button "install module" to launch install.


#	FAQ

Q:	An error appears when the Python MySQLdb module needs to create a file in the root folder of web server. It hasn't rights (more details below).

R:	The Apache user must become owner of www folder
	$#chown www-data:www-data /var/www


#	Useful links :

	Centreon-Discovery
	
Module:			http://community.centreon.com/projects/centreon-discovery
Documentation:	http://community.centreon.com/projects/centreon-discovery/documents
SVN:			http://svn.modules.centreon.com/centreon-discovery/
Forum:			In progress

	Centreon
	
Wiki:			http://doc.centreon.com/
SVN:			http://svn.centreon.com/
Forum:			http://forum.centreon.com/


#	ERROR

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


2. Start installation with install.sh script on root directory "centreon-discovery-x.x"

$# chmod +x install.sh
$# ./install.sh -u /etc/centreon

3. Go on Centreon web interface

Go on menu 'Administration > Modules > Setup'.
SAD module must be present on modules list but not installed.
Click on right icon to start installation



#	FAQ

Q:

R:



#	Links:

	Centreon-discovery
	
Documentation:	*** TO DO ***
SVN:	*** TO DO ***
Trac:	*** TO DO ***
Forum:	*** TO DO ***

	Centreon
	
Wiki:	http://doc.centreon.com/
SVN:	http://svn.centreon.com/
Trac:	http://forge.centreon.com/projects/show/centreon
Forum:	http://forum.centreon.com/