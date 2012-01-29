#!/bin/bash
echo "Installation des modules python"
echo "-------------------------------"
echo "Module NMAP"
cd python-nmap-0.1.4
python setup.py install
echo "-------------------------------"
echo "Module SetupTools"
cd ../setuptools-0.6c11
python setup.py install
echo "-------------------------------"
echo "Module MySQLdb"
cd ../MySQL-python-1.2.3
python setup.py build
python setup.py install
echo "-------------------------------"
