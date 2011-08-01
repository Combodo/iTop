iTop - version 1.2.0-alpha - 02-Aug-2011
Readme file

1.   ABOUT THIS RELEASE
2.   INSTALLATION
2.1. Requirements
2.2. Install procedure
2.3. Migration from previous version
3.   FEATURES
3.1. Changes since 1.0
3.2. Known limitations
3.3. Known issues

1. ABOUT THIS RELEASE
   ==================
Thank you for downloading the tenth packaged release of iTop.
This version comes with a few new features and bug fixes.

A wiki is now available: https://sourceforge.net/apps/mediawiki/itop/index.php?title=ITop_Documentation
Additional documentation can be downloaded from there:
 - User guide
 - Administrator guide
 - Customization guide
 - Implementation guide
Wiki articles complete the documentation for advanced/specific concerns.


iTop is released under the GPL (v3) license. (Check license.txt in this directory).
The source code of iTop can be found on SourceForge: http://itop.sourceforge.net

1.1 What's new?
    ---------------------------
- Japanese localization is now part of iTop
- Paginated display: when a list contains lots of data it is displayed page per page
- Quite a few performance improvements to make iTop behave properly with huge data sets
- Hierarchical keys: parent/child relationships can now be described using a special type of key, and then queried efficiently in the database


1.2 Should I upgrade to 1.2.0?
    ---------------------------
- If you are manipulating big sets of data (several thousands of objects in one go)
- If you care about organizations or locations hierarchy
- If you speak/read Japanese

Then you'll benefit from iTop 1.2 and it's probably worth upgrading. 


1.3 Special Thanks To:
    -----------------
Bruno Bonfils for his guidance about LDAP and authentication.
Randall Badilla Castro for the Spanish translation.
Jonathan Lucas and David Gumbel from ITOMIG Gmbh, for the German translation.
Christian Lempereur and Olivier Fouquet for their feedbacks.
Everaldo Coelho and the Oxygen Team for their wonderful icons.
The JQuery team and the all the jQuery plugins authors for developing such a powerful library.
Phil Eddies for the numerous feedbacks provided, and the first implementation of CKEdit
Marco Tulio for the Portuguese (Brazilian) translation
Vladimir Shilov for the Russian translation
Izzet Sirin for the Turkish translation
Deng Lixin for the Chinese translation
Marialaura Colantoni for the Italian translation
Schlobinux for the fix of the setup temporary file verification.
Gabor Kiss for the Hungarian translation
Tadashi Kaneda for the Japanese translation

2. INSTALLATION
   ============

2.1. Requirements
     ------------
Server configuration:
iTop is based on the AMP (Apache / MySQL / PHP) platform and requires PHP 5.2 and
MySQL 5. The installation of iTop does not require any command line access to the
server. The only operations required to install iTop are: copying the files to the
server and browsing web pages. iTop can be installed on Apache and IIS.

End-user configuration:
Although iTop should work with most modern web browsers, the application has been
tested mostly with Firefox 3, IE8, IE9, Safari 5 and Chrome. iTop was designed for
at least a 1024x768 screen resolution. For the graphical view of the impact analysis,
Flash version 8 or higher is required.

2.2. Install procedure
     -----------------
1) Make sure that you have a properly configured instance of Apache/PHP running
2) Unpack the files contained in the zipped package, and copy the content of the "web"
directory in a directory served by your web server.
3) Point your web browser to the URL corresponding to the directory where the files
have been unpackaged and follow the indications on the screen.

Note:
iTop uses MySQL with the InnoDB engine. If you are running on Linux and if the setup is
very slow with the hard drive spinning a lot, try to set the following value in the my.cnf
configuration file (usually located at /etc/mysql/my.cnf):

innodb_flush_method = O_DSYNC

On some systems you'll see a 5 to 10 times performance boost for writing data into
the MySQL database !

2.3. CRON.PHP
     --------
The following features will require the activation of CRON.PHP:
 - asynchronous emails. By default, this option is disabled. To enable it, set 'email_asynchronous' to 1 in the configuration file.
 - check ticket SLA. Tickets reaching the limits will be passed into Escalation TTO/TTR states.

More information into the Wiki: https://sourceforge.net/apps/mediawiki/itop/index.php?title=Cron.php

2.4. Migrating from 1.0, 1.0.1, 1.0.2 or 1.1
     ---------------------------------------
The upgrade procedure has changed. We recommend you to copy the files of the new version must be copied to a
new directory. The updgrade will modify the database schema. This new schema is not compatible with the previous versions of iTop.

If you are executing the upgrade on a production instance of iTop, it is a good practice to make a backup of the database and the configuration file (config-itop.php) prior to running the upgrade.

1) Unpack the files contained in the zipped package, and copy the content of the "web"
directory in a directory served by your web server.
2) Point your web browser to the URL corresponding to the directory where the files
have been unpackaged.
3) Select "Upgrade an existing iTop instance"
4) Follow the instructions.

5) If you were using tickets: CheckSLAForTickets.php has been deprecated in favour of cron.php - see section 2.3.


2.5. Migrating from 0.9
     ------------------
Depending on your current situation, there are several possible migration paths.
Please refer to the migration guide available at http://www.combodo.com/itopdocumentation.

3. FEATURES
   ========

3.1. Changes since 1.1
     -------------------

Version 1.2.0 brings a few major changes.

Major changes
-------------
- Paginated display
- Management of hierarchy of objects

Localization
------------
Added Japanese translation, thanks to Tadashi Kaneda.

More information on the localization (completion progress, how to contribute) here:
http://www.combodo.com/itop-localization/


Minor changes
-------------
- Keywords in global search (Trac#130): you can type "server:dbserver" (without the quotes) and the global search will search for the text "dbserver" only on the class "server"

Bugs fixed
----------
The complete list of active tickets can be reviewed at http://sourceforge.net/apps/trac/itop/report/1


3.2. Known limitations (https://sourceforge.net/apps/trac/itop/report/3)
     -----------------
#71   The same MySQL credentials are used during the setup and for running the application.
#257  Could not delete more than 997 items when SUHOSIN is installed with its default settings (See TRAC)
#265  Add reconciliations keys into CSV template
Internet Explorer 7 is not supported (neither IE7 nor IE8 in compatibility mode)


3.3. Known issues (https://sourceforge.net/apps/trac/itop/report/3)
     ------------
#259	Not instantaneously logged off when the administrator deletes a user account
#245	Search form gets too specialized: after searching on a subclass it not possible to select the base class again
#175	When moving backward in the CSV import wizard, some settings may be reset (e.g column mapping)
#174	CSV import not displaying the labels of enums
#258	Context automatically set when specifying an organization in a search form
#273	The administrator can delete his/her own user account
#363	Flash charts and IE8
#372	APC Cache not efficient (multi org usage, global search)
#373	Error when deleting two network devices connected to each other
#382	Search form / base class lost after a search
#377	Case log: exclude the index from the views
#388	IE9: edition fields not resizable
