iTop - version 2.3.3 - 22-Dec-2016
Readme file

1.   ABOUT THIS RELEASE
2.   INSTALLATION
2.1. Requirements
2.2. Install procedure
2.3. CRON
2.4. Upgrading from 2.x.x
2.5. Migration from 1.x versions
3.   FEATURES
3.1. Known limitations
3.2. Known issues

1. ABOUT THIS RELEASE
   ==================
Thank you for downloading the 28th packaged release of iTop.
This maintenance release fixes regressions and functions which were supposed to be part of the 2.3.x feature set.
Most of the regressions are related to the introduction of HTML formatted Case Logs and the Enhanced Portal.

The documentation about iTop is available as a Wiki at: https://wiki.openitop.org/

iTop is released under the AGPL (v3) license. (Check license.txt in this directory).
The source code of iTop can be found on SourceForge: https://sourceforge.net/p/itop/code/

1.1 What's new?
    -----------

Changes since iTop 2.3.3

User Interface
--------------
Enable browser spell checking in the rich text editor, use: Ctrl + right click to get it
#1125 Friendly name format ignored if only one attribute was used.
Dependent fields fail to reload when creating an object from another one, with mandatory date using format different from MySQL one.
Adding an InlineImage while adding at the same time an object in a IndirectLinkedSet would attach the InlineImage to the linked object instead of the host one. If their organizations were different, it could result in denying the display of the InlineImage.
Ugly labels when hovering bar or pie charts (grouped on an external key or an enum)
Object with a &, < ou > in its name was not displayed correctly in external key field when created or retrieved through a pop-up search.

Impact analyses
---------------
Messing up with redundancy settings (could either lead to wrong results or a fatal error if a relation is configured downstream).
Missing edges (and redundancy) when two classes impact a given class and both relations use the same neighbour id (and if redundancy is enabled over both relations).
Role "Do not notify" on contact was ignored when recomputing the ticket impact (and log flood with PHP Notices)
Impact analysis graph does not refresh when unchecking some items (clicking on the blue drawer shows the graph unchanged).

Portals
-------
>> New: add_to_list() can now be used in portal action rules.
#1396 $this->hyperlink(portal)$ used in 'notifications' was broken since iTop 2.3.3 (since r4519) 
Portal: log_kpi_duration / log_kpi_memory are now supported by the portal
Portal: Fix invalid URL in LinkedSet searchbox when editing an object (eg. Adding a Contact to an UserRequest)
Portal: Object display crashed when a linkedset attribute has corrupted data (eg. an external key to 0)
Portal: Wrong form used in some inheritance cases.
Legacy portal: Since iTop 2.3, plain text caselog entries can no longer be toggled due to a bad jQuery selector. Only HTML entries were working.

Administration tasks
--------------------
#1413 Data synchro: a line break or '<' in the 'description' of the DataSource object, brook the display of synchronized objects edition form.
Data synchro: allow setting 'undefined' value for a date when an empty string is provided. Known issue: Integer and Decimal cannot be set to 'undefined' value.
OQL: Multi-objects OQL queries with UNION, could fail with various symptoms such as "Class 'IT Department' not found" or "An object id must be an integer value".
Audit: failing with message "Attempting to merge a filter of class A with a filter of class B" (regression introduced in 1.3.2)
Configuration: 'log_queries' setting has been deprecated, use 'log_kpi_duration' instead.
Remove Fatal Errors when disabling logging in the configuration file or when developing specific pages
Fixed XSS vulnerability
Improve API/REST JSON to enable adding entry to HTML caselog using non-HTML text (handling 'new line').
Setup: failing (during database creation) with MetaEnum attribute having no mapping for the class they are declared in.


1.2 Should I upgrade to 2.3.4?
    --------------------------
Yes, we recommend you to upgrade. This version fixes quite a number of bugs from the previous version and is suitable for running in production.


1.3 Special Thanks To
    -----------------
Bruno Bonfils for his guidance about LDAP and authentication.
Randall Badilla Castro and Miguel Turrubiates for the Spanish translation.
Jonathan Lucas, Stephan Rosenke and David Gümbel from ITOMIG GmbH, for the German translation.
Christian Lempereur and Olivier Fouquet for their feedbacks.
Everaldo Coelho and the Oxygen Team for their wonderful icons.
The JQuery team and all the jQuery plugins authors for developing such a powerful library.
Phil Eddies for the numerous feedbacks provided, and the first implementation of CKEdit
Marco Tulio and Bruno Cassaro for the Portuguese (Brazilian) translation
Vladimir Kunin, Vladimir Shilov and Shamil Khamit for the Russian translation
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
Bruno Cornec for his support and contribution to the Linux packaging of iTop
Jean-François Bilger for providing a fix for an unsuspected SQL bug
Remie Malik from Linprofs for the Dutch translation
Erik Bøg for the Danish translation
Lukáš Dvořák and Daniel Rokos for the Czech translation

2. INSTALLATION
   ============

2.1. Requirements
     ------------
Server configuration:
iTop is based on the AMP (Apache / MySQL / PHP) platform and requires PHP 5.3.6 and
MySQL 5. The installation of iTop does not require any command line access to the
server. The only operations required to install iTop are: copying the files to the
server and browsing web pages. iTop can be installed on any web server supporting
PHP 5.3.6: Apache, IIS, nginx...

End-user configuration:
Although iTop should work with most modern web browsers, the application has been
tested mostly with Firefox 36+, IE9+, Safari 5 and Chrome. iTop was designed for
at least a 1024x768 screen resolution.

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
4) Point your web browser to the URL corresponding to the directory where the files
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

More information into the Wiki: https://wiki.openitop.org/doku.php?id=latest:admin:cron

You can get a status of the cron "tasks" with the command:

php cron.php --auth_user=admin_login --auth_pwd=admin_pwd --status_only=1

The output will look as shown below:
+---------------------------+---------+---------------------+---------------------+--------+-----------+
| Task Class                | Status  | Last Run            | Next Run            | Nb Run | Avg. Dur. |
+---------------------------+---------+---------------------+---------------------+--------+-----------+
| CheckStopWatchThresholds  | active  | 2013-03-28 10:32:27 | 2013-03-28 10:32:37 |     51 |   0.317 s |
| EmailBackgroundProcess    | active  | 2013-03-28 10:32:27 | 2013-03-28 10:32:57 |     12 |   7.089 s |
| ExecAsyncTask             | active  | 2013-03-28 10:32:27 | 2013-03-28 10:32:29 |     51 |   0.032 s |
+---------------------------+---------+---------------------+---------------------+--------+-----------+

2.4. Upgrading from 2.x.x
     --------------------
The version 2.3.4 if fully compatible with 2.0.0, 2.0.1, 2.0.2, 2.0.3, 2.1.0, 2.2.0, 2.2.1, 2.3.1 and 2.3.3.
Due to few database changes and new modules/files that have to be installed, you
MUST run the setup when upgrading (whatever the original version).

If the location of mysql binaries is in the "path", the setup proposes to perform a full backup
of iTop (database + configuration file) using mysqldump.

Here is how to upgrade, step by step, a 2.x.x instance of iTop:

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



3.1. Known limitations (https://sourceforge.net/apps/trac/itop/report/3)
     -----------------
#71   The same MySQL credentials are used during the setup and for running the application.

Some types of attributes (AttributeDuration, AttributeBlob) are always displayed as read-only in the Enhanced Portal.
Suhosin can interfere with iTop. More information can be found here: http://www.combodo.com/wiki/doku.php?id=admin:suhosin
Internet Explorer 6 is not supported (neither IE7 nor IE8 in compatibility mode)
Tested with IE9, Firefox 3.6 up to Firefox 50 and Chrome.


3.2. Known issues (https://sourceforge.net/apps/trac/itop/report/3)
     ------------
#259    Not instantaneously logged off when the administrator deletes a user account
#273    The administrator can delete his/her own user account
#372    APC Cache not efficient (multi org usage, global search)
#382    Search form / base class lost after a search
#377    Case log: exclude the index from the views
#388    IE9: edition fields not resizable
#443    Objects remain in the database after de-installing some modules
#442    Useless profiles installed (1.x legacy data model only)
#436    Cannot type "All Organizations"
#381    Deletion of dependencies could fail in a multi-org environment
#241    "status" is a free-text field when configuring a Trigger
#358    Multi-column queries sometimes returning an empty set
#350    Object edition form: validation does not tell which field has a problem
#730    Leaving temporary files when performing a backup of the data during installation
#1145   Two connections between a connectable CI and a network device must have different ports
#1146   History not reflecting a modification of the connection between a connectable CI and a network device
#1147   Identical links not always modified as expected