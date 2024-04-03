#! /bin/bash

DIR=$(dirname $0)
ITOP_DIR="$DIR/../.."

XML_SETUP="$DIR/fresh-install.xml"
INSTALLATION_XML="$ITOP_DIR/datamodels/2.x/installation.xml"
HELP="Syntax: $0 [XML_SETUP] [INSTALLATION_XML]"

if [ $# -gt 2 ]
then
	echo "Too much parameters passed to $0."
	echo $HELP
	exit 1
fi

if [ $# -gt 0 ]
then
	XML_SETUP=$1
	if [ ! -f $XML_SETUP ]
	then
		echo "Xml setup file ($XML_SETUP) not found."
		echo $HELP
		exit 1
	fi
else
	echo "Using default XML_SETUP ($XML_SETUP) and INSTALLATION_XML ($INSTALLATION_XML) files during unattended itop installation."	
fi

if [ $# -gt 1 ]
then
	INSTALLATION_XML=$2
	if [ ! -f $INSTALLATION_XML ]
	then
		echo "installation.xml file ($INSTALLATION_XML) not found."
		echo $HELP
		exit 1
	fi
	echo "Using XML_SETUP ($XML_SETUP) and INSTALLATION_XML ($INSTALLATION_XML) files during unattended itop installation."
else
	echo "Using XML_SETUP ($XML_SETUP) and default INSTALLATION_XML ($INSTALLATION_XML) files during unattended itop installation."
fi



rm -rf $ITOP_DIR/data/.maintenance; 

php $DIR/unattended-install.php --use-itop-config=1 --use_installation_xml="$INSTALLATION_XML" --response_file="$XML_SETUP"