#!/bin/bash
# Linux installation script to be used inside packages (deb, rmp)
# or launched manually with the appropriate variables set...
#
# $Id$
#
#set -v

if [ "_$_ITOP_SYSCONFDIR_" = "_" ]; then
	_ITOP_SYSCONFDIR_="/etc"
fi
if [ "_$_ITOP_VARDIR_" = "_" ]; then
	_ITOP_VARDIR_="/var"
fi
if [ "_$_ITOP_NAME_" = "_" ]; then
	_ITOP_NAME_="itop-itsm"
fi

if [ "_$PREFIX" != "_" ]; then
	local=${HEAD}$PREFIX
	sublocal=$PREFIX
	conf=${HEAD}$_ITOP_SYSCONFDIR_/$_ITOP_NAME_
	subconf=$_ITOP_SYSCONFDIR_/$_ITOP_NAME_
	var=${HEAD}$_ITOP_VARDIR_
	subvar=$_ITOP_VARDIR_
	webconf=${HEAD}$_ITOP_WEBCONFDIR_
	subwebconf=$_ITOP_WEBCONFDIR_
else
	local=/usr/local
	sublocal=$local
	conf=$local/$_ITOP_SYSCONFDIR_
	subconf=$conf
	var=$local/$_ITOP_VARDIR_
	subvar=$var
	webconf=$local/$_ITOP_WEBCONFDIR_ 
	subwebconf=$_ITOP_WEBCONFDIR_
fi

if [ "_$_ITOP_WEBCONFDIR_" = "_" ]; then
	_ITOP_WEBCONFDIR_="$conf/../httpd"
	if [ ! -d $_ITOP_WEBCONFDIR_ ]; then
		exit "Please define a valid _ITOP_WEBCONFDIR_ variable"
	fi
fi

# Define additional dirs
if [ _"$_ITOP_LOGDIR_" = _"" ]; then
        _ITOP_LOGDIR_="$var/log/$_ITOP_NAME_"
else
        _ITOP_LOGDIR_="${HEAD}$_ITOP_LOGDIR_"
fi

if [ _"$_ITOP_VARLIBDIR_" = _"" ]; then
        _ITOP_VARLIBDIR_="$var/lib/$_ITOP_NAME_"
else
        _ITOP_VARLIBDIR_="${HEAD}$_ITOP_VARLIBDIR_"
fi

if [ _"$_ITOP_DATADIR_" = _"" ]; then
        _ITOP_DATADIR_="$local/share/$_ITOP_NAME_"
else
        _ITOP_DATADIR_="${HEAD}$_ITOP_DATADIR_"
fi

# From now on Variables are correctly setup, just use them
#
echo "$_ITOP_NAME_ will be installed under $_ITOP_DATADIR_"

echo "Creating target directories ..."
for d in production test toolkit; do
	install -m 755 -d $conf/$d $_ITOP_VARLIBDIR_/env-$d 
done
install -m 755 -d $_ITOP_DATADIR_ $_ITOP_LOGDIR_ "$_ITOP_VARLIBDIR_/data"

echo "Copying files ..."
cp -a ./web/* $_ITOP_DATADIR_

echo "Fixing line endings in LICENSE and README files"
sed -i -e "s/\r$//g" ./LICENSE ./README

echo "Creating symlinks..."
(cd $_ITOP_DATADIR_ ; \
ln -s $subconf conf ;\
ln -s $subvar/log/$_ITOP_NAME_ log ;\
ln -s $subvar/lib/$_ITOP_NAME_/env-production env-production ;\
ln -s $subvar/lib/$_ITOP_NAME_/env-test env-test ;\
ln -s $subvar/lib/$_ITOP_NAME_/data data ;\
)
(cd  $_ITOP_VARLIBDIR_ ; ln -s $sublocal/share/$_ITOP_NAME_/approot.inc.php approot.inc.php)


if [ _"$HEAD" != _"" ]; then
	echo Creating $webconf/conf.d, $conf/../cron.d and $conf/../logrotate.d directories
	install -m 755 -d $webconf/conf.d $conf/../cron.d $conf/../logrotate.d
fi

# Substitute variables for templates
sed -e "s~_ITOP_NAME_~$_ITOP_NAME_~g" -e "s~_ITOP_SYSCONFDIR_~$subconf~g" -e "s~_ITOP_DATADIR_~$sublocal/share~g" -e "s~_ITOP_LOGDIR_~$subvar/log~g" ./web/setup/install/apache.conf.tpl > $webconf/conf.d/$_ITOP_NAME_.conf
sed -e "s~_ITOP_NAME_~$_ITOP_NAME_~g" -e "s~_ITOP_SYSCONFDIR_~$subconf~g" -e "s~_ITOP_DATADIR_~$sublocal/share~g" -e "s~_ITOP_LOGDIR_~$subvar/log~g" ./web/setup/install/cron.tpl > $conf/../cron.d/$_ITOP_NAME_
sed -e "s~_ITOP_NAME_~$_ITOP_NAME_~g" -e "s~_ITOP_SYSCONFDIR_~$subconf~g" -e "s~_ITOP_DATADIR_~$sublocal/share~g" -e "s~_ITOP_LOGDIR_~$subvar/log~g" ./web/setup/install/logrotate.tpl > $conf/../logrotate.d/$_ITOP_NAME_
chmod 644 $webconf/conf.d/$_ITOP_NAME_.conf $conf/../cron.d/$_ITOP_NAME_ $conf/../logrotate.d/$_ITOP_NAME_

exit 0
