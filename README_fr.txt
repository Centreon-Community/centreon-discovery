------------------------------------------------
#	Description:
------------------------------------------------
Nom: Centreon-Discovery
Version: 2.3
Distribution : Debian/CentOS

------------------------------------------------
#	Prerequis
------------------------------------------------
* Python (version 2.4) doit être installé à la fois sur le ou les pollers ainsi que sur le central.
* Le logiciel nmap (version 5.00 ou plus) doit être installé sur chaque poller. Dans le cas d'une architecture centralisée, nmap doit être installé sur le central.
* Le paquet python-dev (python-devel en fonction des distributions) doit être installé à la fois sur le ou les pollers ainsi que sur le central.
* Le paquet mysql-devel (sur CentOS) doit être installé sur le central.

------------------------------------------------
#	Installation:
------------------------------------------------
1. Télécharger la dernière version du module depuis la page de téléchargement

Se rendre sur la page http://community.centreon.com/projects/centreon-discovery

2. Extraire le contenu de l'archive

	# tar xzf centreon-discovery-x.x.tar.gz

3. Lancer l'installation automatique

	# cd centreon-discovery-x.x
	# chmod 755 install.sh

Choississez votre type d'installation :
 - en tant que poller : "-t poller"
 - en tant que central : "-t central"
 - en tant que les deux : "-t both"
 
Exemple pour poller : # ./install.sh -i -t poller

L'option "-i" permet de spécifier que l'on désire installer le module.

4. Dans l'interface de Centreon

Se rendre ensuite dans le menu 'Administration > Modules > Centreon-Discovery
Le module Discovery doit figurer dans la liste des modules mais non installé.
Cliquer sur l'icone à droite "Install Module" puis sur le bouton "install module" pour lancer l'installation.

5. Exécuter l'agent Poller en fond de tâche (installé par défaut dans /etc/centreon-discovery) en root

	# python /etc/centreon-discovery/DiscoveryAgent_poller.py &
	(ou $# python26 /etc/centreon-discovery/DiscoveryAgent_poller.py &)

------------------------------------------------
#	MISE A JOUR
------------------------------------------------
1. Dans l'interface de Centreon

Se rendre ensuite dans le menu 'Administration > Modules > Centreon-Discovery
Le module Discovery doit figurer dans la liste des modules.
Cliquer sur l'icone à droite "Uninstall Module" puis sur le bouton "OK" pour confirmer.

2. Suppression de(s) agent(s)

Vérifier que l'agent poller est bien arrêté

Supprimer le(s) agent(s)
	# cd /etc/centreon-discovery
	# rm -f *.py

Le chemin "/etc/centreon-discovery" est à adapter en fonction de votre configuration.

3. Suppression du module

Supprimer le module
	# cd /usr/local/centreon/www/modules
	# rm -rf Discovery

Le chemin "/usr/local/centreon/www/modules" est à adapter en fonction de votre configuration.

4. Aller à "#	Installation:"

------------------------------------------------
#	DESINSTALLATION (version 2.3 minimum)
------------------------------------------------
1. Télécharger la version du module installée depuis la page de téléchargement

Se rendre sur la page http://community.centreon.com/projects/centreon-discovery

2. Extraire le contenu de l'archive

	# tar xzf centreon-discovery-x.x.tar.gz

3. Désinstallation du module

Lancer la désinstallation automatique
	# ./install.sh -r /usr/share/centreon-discovery

Une fois le processus terminé sans erreur, le module a été entièrement supprimé de Centreon et de votre système.

------------------------------------------------
#	FAQ
------------------------------------------------
Q : Dans quel type d'installation je me trouve : poller? central? ou both ?

R1 : poller : si vous n'avez que Nagios d'installé sur cette machine et que ce dernier renvoit les infos collectées vers un serveur central. Voir la liste dans l'interface Centreon : "Configuration" --> "Centreon"  --> colonne localhost = NO)
R2 : central : si vous n'avez que Centreon d'installé sur cette machine et que ce dernier collecte toutes les infos renvoyées par le(s) poller(s).
R3 : both : si vous avez à la fois Nagios et Centreon d'installés sur la machine. Voir la liste dans l'interface Centreon : "Configuration" --> "Centreon" --> colonne localhost = YES)

Q :	Une erreur apparaît lorsque le module Python MySQLdb a besoin de créer un fichier à la racine du serveur web et qu'il n'a pas les droits (plus de détails de l'erreur plus bas).

R :	La solution consiste a rendre l'utilisateur Apache propriétaire de dossier www
	$#chown www-data:www-data /var/www
	
Q : Comment installer la dernière version de nmap sur CentOS ?

R : rpm -vhU http://nmap.org/dist/nmap-5.51-1.i386.rpm

------------------------------------------------
#	Liens utiles:
------------------------------------------------
	Centreon-Discovery
	
Documentation:	http://community.centreon.com/projects/centreon-discovery/documents
SVN:			http://svn.modules.centreon.com/centreon-discovery/
Forum:			http://forum.centreon.com/forumdisplay.php/36-Centreon-discovery-s-modules

	Centreon
	
Wiki:			http://doc.centreon.com/
SVN:			http://svn.centreon.com/
Forum:			http://forum.centreon.com/

------------------------------------------------
#	ERREUR
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
