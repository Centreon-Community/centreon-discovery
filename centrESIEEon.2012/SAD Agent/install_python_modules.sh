#!/bin/bash
echo "Installation des modules python"
echo "-------------------------------"
echo "Module NMAP"
cd python-nmap-0.1.4
python2.6 setup.py install
echo "-------------------------------"
echo "Module SetupTools"
cd ../setuptools-0.6c11
python2.6 setup.py install
echo "-------------------------------"
echo "Module MySQLdb"
cd ../MySQL-python-1.2.3
python2.6 setup.py build
python2.6 setup.py install
echo "-------------------------------"
