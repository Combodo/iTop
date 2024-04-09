#! /bin/bash

CLI_NAME=$(basename $0)
DIR=$(dirname $0)
ITOP_DIR="$DIR/../.."

HELP="Syntax: $CLI_NAME XML_SETUP [INSTALLATION_XML]"

function HELP {
	echo $HELP
	exit 1
}

if [ $# -lt 1 ]
then
	echo "Missing parameters passed."
	HELP
fi

if [ $# -gt 2 ]
then
	echo "Too much parameters passed ($#) : $*."
	HELP
fi

XML_SETUP=$1
if [ ! -f $XML_SETUP ]
then
  echo "XML_SETUP file ($XML_SETUP) not found."
	HELP
fi

if [ $# -eq 2 ]
then
	INSTALLATION_XML=$2
	if [ ! -f $INSTALLATION_XML ]
	then
		echo "INSTALLATION_XML file ($INSTALLATION_XML) not found."
	  HELP
	fi
else
  INSTALLATION_XML="$ITOP_DIR/datamodels/2.x/installation.xml"
fi

echo "$CLI_NAME: Using XML_SETUP ($XML_SETUP) and INSTALLATION_XML ($INSTALLATION_XML) files during unattended itop installation."

rm -rf $ITOP_DIR/data/.maintenance;
echo php $DIR/unattended-install.php --use_itop_config --installation_xml="$INSTALLATION_XML" --param-file="$XML_SETUP"

php $DIR/unattended-install.php --use_itop_config --installation_xml="$INSTALLATION_XML" --param-file="$XML_SETUP"
