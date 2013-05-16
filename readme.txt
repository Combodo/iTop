iTop - version 2.0.1-beta - 03-April-2012
Readme file

1.   ABOUT THIS RELEASE
2.   INSTALLATION
2.1. Requirements
2.2. Install procedure
2.3. CRON
2.4. Upgrading from 2.0.0
2.5. Migration from 1.x versions
3.   FEATURES
3.1. Changes since 2.0.0
3.2. Known limitations
3.3. Known issues

1. ABOUT THIS RELEASE
   ==================
Thank you for downloading the 15th packaged release of iTop.
This version is a maintenance release, with quite a few bug fixes and two enhancements.

The documentation about iTop is available as a Wiki: http://www.combodo.com/wiki

iTop is released under the AGPL (v3) license. (Check license.txt in this directory).
The source code of iTop can be found on SourceForge: https://sourceforge.net/p/itop/code/

1.1 What's new?
    ---------------------------
This version brings a number of bug fixes since 2.0.0 and two enhancements:
- CRON jobs are now scheduled according to their "periodicity"
- A new REST/JSON web service API is available (documented at: http://www.combodo.com/wiki/doku.php?id=advancedtopics:rest_json)

... and about 40 bug fixes.

1.2 Should I upgrade to 2.0.1?
    -------------------------------
Considering that iTop 2.0.1 is fully compatible with iTop 2.0.0 and the number of bugs fixed, we recommend you to upgrade.

1.3 Special Thanks To:
    -----------------
Bruno Bonfils for his guidance about LDAP and authentication.
Randall Badilla Castro and Miguel Turrubiates for the Spanish translation.
Jonathan Lucas, Stephan Rosenke and David Gümbel from ITOMIG GmbH, for the German translation.
Christian Lempereur and Olivier Fouquet for their feedbacks.
Everaldo Coelho and the Oxygen Team for their wonderful icons.
The JQuery team and all the jQuery plugins authors for developing such a powerful library.
Phil Eddies for the numerous feedbacks provided, and the first implementation of CKEdit
Marco Tulio and Bruno Cassaro for the Portuguese (Brazilian) translation
Vladimir Shilov and Shamil Khamit for the Russian translation
Izzet Sirin for the Turkish translation
Deng Lixin for the Chinese translation
Marialaura Colantoni for the Italian translation
Schlobinux for the fix of the setup temporary file verification.
Gabor Kiss for the Hungarian translation
Tadashi Kaneda, Shoji Seki and Hirofumi Kosaka for the Japanese translation
Antoine Coetsier for the CAS support and tests
Vincenzo Todisco for his contribution to the enhancement of the webservices
Stephan Rickauer, Tobias Glemser and Sabri Saleh for their consulting about iTop security
Claudio Cesar Sanchez Tejeda for his contribution to bug fixes on the export and data synchronization

2. INSTALLATION
   ============

2.1. Requirements
     ------------
Server configuration:
iTop is based on the AMP (Apache / MySQL / PHP) platform and requires PHP 5.2 and
MySQL 5. The installation of iTop does not require any command line access to the
server. The only operations required to install iTop are: copying the files to the
server and browsing web pages. iTop can be installed on any web server supporting
PHP 5.2: Apache, IIS, nginx...

End-user configuration:
Although iTop should work with most modern web browsers, the application has been
tested mostly with Firefox 3+, IE8, IE9, Safari 5 and Chrome. iTop was designed for
at least a 1024x768 screen resolution. For the graphical view of the impact analysis,
Flash version 8 or higher is required.

2.2. Install procedure
     -----------------
1) Make sure that you have a properly configured instance of Apache/PHP/MySQL running
2) Unpack the files contained in the zipped package, and copy the content of the "web"
   directory in a directory served by your web server.
3) Check the access rights on the files/folders: the setup needs to have write access
   either to the directory where iTop is installed or to the following subdirectories
   (create them if needed)
     - conf
     - data
     - env-production
     - log
3) Point your web browser to the URL corresponding to the directory where the files
   have been unzipped and follow the indications on the screen.
  
If you wish to re-launch the installation process (for example in order to install
more modules), just make sure that the configuration file (located at <itop>/conf/production/config-itop.php)
is writable by the web server (on Windows: remove the "read-only" flag, on Linux
adjust the rights of the file) and point your browser to <itop>/setup/.

2.3. cron.php
     --------
The following features will require the activation of CRON.PHP:
 - asynchronous emails. By default, this option is disabled. To enable it, set 'email_asynchronous' to 1 in the configuration file.
 - check ticket SLA. Tickets reaching the limits will be passed into Escalation TTO/TTR states.

More information into the Wiki: https://sourceforge.net/apps/mediawiki/itop/index.php?title=Cron.php

New in 2.0.1: you can get a status of the cron "tasks" with the command:

php cron.php --auth_user=admin_login --auth_pwd=admin_pwd --status_only=1

The output will look as shown below:
+---------------------------+---------+---------------------+---------------------+--------+-----------+
| Task Class                | Status  | Last Run            | Next Run            | Nb Run | Avg. Dur. |
+---------------------------+---------+---------------------+---------------------+--------+-----------+
| CheckStopWatchThresholds  | active  | 2013-03-28 10:32:27 | 2013-03-28 10:32:37 |     51 |   0.317 s |
| EmailBackgroundProcess    | active  | 2013-03-28 10:32:27 | 2013-03-28 10:32:57 |     12 |   7.089 s |
| ExecAsyncTask             | active  | 2013-03-28 10:32:27 | 2013-03-28 10:32:29 |     51 |   0.032 s |
+---------------------------+---------+---------------------+---------------------+--------+-----------+

2.4. Upgrading from 2.0.0
     --------------------
The version 2.0.1 if fully compatible with 2.0.0. Due to the new database table used for storing the
definition of cron jobs, you must run the setup when upgrading.

If the location of mysql binaries is in the "path", the setup proposes to perform a full backup
of iTop (database + configuration file) using mysqldump.

Here is how to upgrade, step by step, a 2.0.0 instance of iTop:

1) Do NOT overwrite the files from the previous version. Expand the content of the "web" directory of
   the new package into a new directory on the web server.
2) Check the access rights on the files/folders: the setup needs to have write access either to the
   whole directory where iTop is installed or to the following subdirectories (create them if needed)
    - conf
    - data
    - env-production
    - log

3) Point your web browser to the URL corresponding to the new location. You should see the setup screen.
4) When prompted (At step 2 of the installation), choose "Upgrade an existing instance"
5) Either enter the path (on the disk) to the previous instance, or supply the needed credentials.
6) Run the setup to completion. Once this is done you can connect to your upgraded iTop.
7) To replace the old instance of iTop with the newly installed one:
   Rename the directories to switch the locations
   Edit the new configuration file (now located at <itop>/conf/production/config-itop.php) and change the
   value of the "application_url" parameter.

2.5. Migrating from 1.x versions
     ---------------------------
The setup is designed to upgrade existing 1.x instances of iTop automatically. In case the instance was
customized (for example by altering its data model), the installation process will detect the modifications
(by scanning the source files and comparing them with the manifest) and will prompt either to keep the
modifications or to discard them.

If the location of mysql binaries is in the "path", the installation proposes to perform a full backup
of iTop (database + configuration file) using mysqldump.

Here is how to upgrade, step by step, a 1.x instance of iTop.

1) Do NOT overwrite the files from the previous version. Expand the content of the "web" directory of
   the new package into a new directory on the web server.
2) Check the access rights on the files/folders: the setup needs to have write access either to the
   directory where iTop is installed or to the following subdirectories (create them if needed)
    - conf
    - data
    - env-production
    - log

3) Point your web browser to the URL corresponding to the new location. You should see the setup screen.
4) When prompted (At step 2 of the installation), choose "Upgrade an existing instance"
5) Enter the needed credentials. It is not mandatory to supply the location (on the disk of the server)
   where the previous instance was installed but, by doing so, you let the installation find the credentials
   (by looking at the previous configuration file) and also perform additional checks, for instance, to
   detect any customization that you may have made to iTop.
6) Run the setup to completion. Once this is done you can connect to your upgraded iTop.
7) To replace the old instance of iTop with the newly installed one:
   Rename the directories to switch the locations
   Edit the new configuration file (now located at <itop>/conf/production/config-itop.php) and change the
   value of the "application_url" parameter.

That's it.

3. FEATURES
   ========

3.1. Changes since 2.0.0
     -------------------

This maintenance version consists mostly in bug fixes. There are only two enhancements:
- CRON jobs are now scheduled according to their "periodicity"
- A new REST/JSON web service API is available (documented at: http://www.combodo.com/wiki/doku.php?id=advancedtopics:rest_json)

Bug fixes from Trac:
--------------------
#698 SeparatorPopupMenuItem was not working.
#697 Properly export NULL dates in "spreadsheet" format.
#696 The message "Please fill all mandatory fields" can now be localized (done in English, French and German).
#694 A module can now add a menu under a menu from another module
#691 Notifications were not sent at all if some recicipients had an empty address
#690 XML export broken
#688 When the autocomplete is activated, and the allowed values depend on another value, then it is possible to set a wrong value
#687 Label for attribute Person: "name" was always shown in English (Last Name)
#684 CSV import / reconciliation using an enum does not take the translation into account
#683 Allow installation on a DB which names begins with numbers
#682 Order notifications (last first).
#680 Setup failing to display the check report when DOM extension not enabled (php-xml not installed on redhat distributions)
#679 Improved the reporting in case of an error while loading a module: 1) the list of already loaded modules is given, 2) the full path of the searched node is given
#677 Cosmetics in the german localization (and a few other languages): first header of the config mgmt overview
#675 Error when drilling down on graph/pie/table with group by on a field that can be null (this case has been excluded)
#674 request_type:servicerequest changed into service_request - added the DB update to allow an upgrade
#674 Fix bug  TTO / TTR computation for Service request
#670 Fix for an XSS vulnerability issue.
#666 Add reconciliation key for Software
#664 Could not login after upgrade of an iTop 1.x with a DB prefix
#661 and #662 Could not create a user request (or ?) as soon as the autocomplete feature gets active
#660 Warning raised with ZendServer (with APC cache enabled) causing the setup to fail
#659 Exception handling producing notices, hence causing confusion
#657 JavaScript error when modifying UserLDAP object with Sync
#634 Detection of HTTPS not working with nginx (iTop always considering the current connection as being secure)
#626 Fixed missing translation in dictionaries (Tickets: "relations", and Contacts overview / count)

Setup/installation fixes
------------------------
Compiler: typo preventing from setting the property 'min_autocomplete_chars' on an external key
Better error reporting when loading a module fails.
Sort the modules before processing them for dependencies, in order to obtain a predictable result independent from the order of the modules in the file system... hopefully... (should fix Trac#679)
Data model alternate options were not properly checked when upgrading (especially when selecting ITIL tickets)
Enable support of databases which name either is a reserved word or contains non-alphanumeric characters (i.e. itop-production).
Added support for creating symbolic links via the toolkit
Added more debug info in the setup.log about the detection of the previously installed modules

Data model fixes
----------------
Correction to display IP address field for:
  Physical Device
  Network Device
  Server
  Storage System
  NAS
  Tape Library
  SAN switch
Fix an issue that prevented the creation of Logical Interfaces
Add reconciliation keys for SLT in order to allow import for SLT having the same name
Remove wrong dependency to service_id on parent_request_id (for ITIL tickets)

Miscellaneous fixes
-------------------
Preserve POSted parameters on the login web page (useful when the session expires)
More readable edition for AttributeDuration
Properly record history of Hierarchical Keys
Fix for supporting the CSV export of big audit results.
Fix for making iUIPageExtension usable !
Fixed transparent background issues on the icons at the top-right of the main iTop page...

3.2. Known limitations (https://sourceforge.net/apps/trac/itop/report/3)
     -----------------
#71   The same MySQL credentials are used during the setup and for running the application.

Suhosin can interfere with iTop. More information can be found here: http://www.combodo.com/wiki/doku.php?id=admin:suhosin
Internet Explorer 6 is not supported (neither IE7 nor IE8 in compatibility mode)
Tested with IE8 and IE9, Firefox 3.6 up to Firefox 16 and Chrome. Be aware that there are certain limitations when using IE8 in "security mode" (when running IE on a Windows 2008 Server for example)


3.3. Known issues (https://sourceforge.net/apps/trac/itop/report/3)
     ------------
#259	Not instantaneously logged off when the administrator deletes a user account
#273	The administrator can delete his/her own user account
#372	APC Cache not efficient (multi org usage, global search)
#382	Search form / base class lost after a search
#377	Case log: exclude the index from the views
#388	IE9: edition fields not resizable
#443 	Objects remain in the database after de-installing some modules
#442 	Useless profiles installed (1.x legacy data model only)
#438 	The selection of Organizations using the hierarchy does not work on IE8 in security mode
#436 	Cannot type "All Organizations" 	
#381 	Deletion of dependencies could fail in a multi-org environment
#241 	"status" is a free-text field when configuring a Trigger
#358 	Multi-column queries sometimes returning an empty set
#383 	OQL: negative integers not allowed (workaround: 0 - 1)
#399 	Copy/paste from iTop's CaseLog looses tabs
#343 	CKEditor (HTML Editor) not compatible with direct object creation on ExtKeys
#350 	Object edition form: validation does not tell which field has a problem
#317 	Edition of a Document - opens the second tab
#730  Leaving temporary files when performing a backup of the data during installation