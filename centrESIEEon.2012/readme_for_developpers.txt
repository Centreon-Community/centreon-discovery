TRUNK?
C'est le répertoire principal, celui dans lequel on va travailler pour faire évoluer le projet.
TAGS?
Dans ce répertoire on va placer les versions figées du projet, des snapshots de version stable. Par exemple la version 1.0, puis 1.1,… Il faut considérer ce répertoire comme étant en lecture seule. Une sorte d'historique des différents versions.
BRANCHES?
On va retrouver ici, une zone de travail différente du TRUNK, qui permettra de faire évoluer des versions en parallèles du TRUNK. Par exemple, lorsque l'on doit effectuer une correction sur la version 0.6, alors que l'on travaille déjà sur la version 0.7, on pourra placer une copie de TAGS/0.6 dans BRANCHES/0.6.x; une fois le travail terminé on pourra créer un TAGS/0.6.1 basé sur BRANCHES/0.6.x.


Source : http://blog.geturl.net/post/2009/04/20/%5Bsvn%5D-Trunk-Tags-Branches-!