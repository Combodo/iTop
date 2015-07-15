iTop - version 2.2.0 Beta - 16-July-2015
Readme file

1.   ABOUT THIS RELEASE
2.   INSTALLATION
2.1. Requirements
2.2. Install procedure
2.3. CRON
2.4. Upgrading from 2.0.x
2.5. Migration from 1.x versions
3.   FEATURES
3.1. Changes since 2.1.0
3.2. Known limitations
3.3. Known issues

1. ABOUT THIS RELEASE
   ==================
Thank you for downloading the 21st packaged release of iTop.
This version is a major release, with quite a few bug fixes and significative enhancements.

The documentation about iTop is available as a Wiki: https://wiki.openitop.org/

iTop is released under the AGPL (v3) license. (Check license.txt in this directory).
The source code of iTop can be found on SourceForge: https://sourceforge.net/p/itop/code/

1.1 What's new?
    ---------------------------
This version brings a number of expected enhancements, namely:

- An new engine to compute and display impact analysis (requires Graphviz on the server, but no longer depends on Flash)
- A complete rework of the exports
- A lock to prevent the concurrent modification of the same object by different agents
- A few performance optimizations (APC/APCu required on the server to benefit from them)
- Enhancements to customizations that can be performed in XML

... and about 25 bug fixes

1.2 Should I upgrade to 2.2.0 beta?
    -------------------------------
This version is a beta quality version, and thus is NOT recommended for production.
If you want to test drive the new features, we recommend that you install it in a "staging" environment.
Anyhow, prior to taking that decision, we encourage you to have a look at the migration notes:
https://wiki.openitop.org/doku.php?id=2_1_0:admin:210_to_220_migration_notes

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

2. INSTALLATION
   ============

2.1. Requirements
     ------------
Server configuration:
iTop is based on the AMP (Apache / MySQL / PHP) platform and requires PHP 5.3 and
MySQL 5. The installation of iTop does not require any command line access to the
server. The only operations required to install iTop are: copying the files to the
server and browsing web pages. iTop can be installed on any web server supporting
PHP 5.3: Apache, IIS, nginx...

End-user configuration:
Although iTop should work with most modern web browsers, the application has been
tested mostly with Firefox 36+, IE9+, Safari 5 and Chrome. iTop was designed for
at least a 1024x768 screen resolution. For the graphical view of the impact analysis,
Flash version 8 or higher is required for some charts.

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

More information into the Wiki: https://wiki.openitop.org/doku.php?id=2_0_3:admin:cron

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
The version 2.2.0 if fully compatible with 2.0.0, 2.0.1, 2.0.2, 2.0.3 and 2.1.0. Due to few database changes,
and new modules/files that have to be installed, you must run the setup when upgrading (whatever the original
version).

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

3. FEATURES
   ========

3.1. Changes since 2.1.0
     -------------------

Modernizations
--------------------
New look: a little bit "flatter" and more modern, but still quite similar to previous versions of iTop for a smooth migration 
The 'zip' extension is now mandatory to install iTop, since the code relies on the ZipArchive class for the Excel export and the scheduled backup.
iTop now requires PH 5.3.0 or higher (instead of PHP 5.2).
For the display of the impact analysis, Graphviz is required on the server.


Impact analysis
-----------------
Takes the redundancy into account (On "Power Sources" and on "Farms")
An new "Impact analysis" tab is now available on tickets, to show the exact impact of a given ticket (can be exported in PDF and attached to the ticket)
The graphical view no longer depends on Flash, takes into account the active tickets and is exportable in PDF
The display has been improved and better supports high volumes of data by automatically grouping similar objects
The impact analysis can now be customized in XML, but remains backwards compatible with definitions made by the mean of PHP methods


Exports
-------------
The bulk export has been completely redesigned:
- interactive choice of the columns to export (and their order) as well as all the format specific options
- support for high volumes of data for the interactive export
- the same export engine" is used for interactive or scripted exports
- new PDF format
The following enhancements/bugs were addressed:
#1071 Bulk Read access rights
#1034 List of fields for Excel export
#772 Some attributes not exportedvia export.php

Locking
-------------
A new locking mechanism has been introduced to prevent the concurrent interactive modification of the same object (for example a User Request ticket)
by two agents (or by the same agent in two different tabs of her/his browser). In case of troubles, an administrator can however bypass this lock.

Note: The locking mechanism can be completely disabled to go back to the previous behavior. (via the configuration parameter: concurrent_lock_enabled)

OQL syntax
--------------------
1) The OQL language now supports UNION statements:
SELECT Server WHERE cpu = '...' UNION SELECT PC
Unions support polymorphism: you can use UNION on as many OQL queries as needed as long as the selected classes have a common ancestor.
Unions  can be used anywhere in the application where an OQL query is expected.

2) JOIN ... ON objkey = id
Allow JOIN on a objclass/objkey pair of attributes
Enables queries on the synchronized objects (SynchroReplica::dest_id was changed into an attribute of type AttributeObjectKey), or with change tracking logs.


Scalability / Performance
-------------------------
Optimization: improvement to the OQL cache:
- take benefit of the APC cache (if present)
- memory indexation may have failed in case of long queries (query id based on a md5)
- added a kpi measure on the OQL parsing
Optimization: when displaying an object details, do not check data synchro for each and every attribute (the cache did exist but was inoperant)
Performance optimization: cache the result of the disk scan looking for icons for dashboards (speeds up the welcome page !)
Optimization of DisplayBlock::FromObjectSet, load only the needed column(s)!


Miscellaneous fixes
-------------------
#714  Localization of the date picker calendar. Get rid of the old jquery.datepicker.js file since iTop now relies on the built-in jQuery UI date picker widget.
#257  Dashlet label hardcoded to "Search for objects of type Server"
#759  Ticket lists in CI: show only active tickets (exclude tickets in states rejected/resolved/closed) and display one list per leaf class so that the status column will be visible. It it not possible anymore to edit the ticket list from the CI.
#1062 bumped the version number of the REST/JSON API to 1.3 to be aligned with the documentation !
#963  For security reasons, "Portal users" are no longer allowed to use the REST/JSON API.
#1078 Properly record the history of LinkedSet(Indirect)
#1079 DBWriteLinks deleting related objects
Bug fix: don't accept attachments (like images) via Chrome's copy/paste since it may duplicate the text content of a normal copy/paste and moreover causes troubles because there is no file name associated with the pasted content.
#788  Whenever a timeout is detected by an ajax request, a popup dialog warns the user to log-in again.
Small enhancement to the display of the meta model: in the list of transitions, display the code of the event as a tooltip.
JSON/REST: When specifying a case log entry (or the whole), it was not possible to set the user name without knowing a valid user id
Bug fix: prevent a crash of the web services when trying to log a non scalar paramater value...
#1092 Caller not preset when creating a ticket from a contact
#1082 Dashlet badge: do not display search results everytime.
#1088 Support of HTMLEditor in the PortalWebPage, for example if the description of a ticket is in HTML.
Bug fix: properly compute the URLs/URIs for the soap server (and its extensions)
#1083 HTML export: show a scroll bar when needed.
#1059 fix for the Spanish localization first_name and last_name were swaped.
#1054 increase max_execution_time during the setup.
#1052 Fix for the German localization.
#1050 Properly support the 'list' display style for external keys - as stated in the documentation!
#1047 Fix for the FindTab method.
#1045 Fix in the German localization.
#594  Properly display attachments inside "properties" by closing the span and the fieldset in non-edit mode.


Extending the data model
------------------------
New lifecycle action SetCurrentPerson. Also improved the existing lifecycle action SetCurrentUser to prevent from calling it on an external key that is not pointing to users (!= contact), and if the target attribute is a string, then store the friendlyname there.
#1069 Fix to add a new hierarchical key when there are already some records in the DB
Modules implementing a lifecycle written in PHP (and having actions executed on transitions) do not work until 2.1.0. The compatibility patch had been implemented but it was not working.
XML Enhancement: support injection of new modules treated as data.
XML 1.2: handle the XML transformation. Added APIs to report the functionality loss when downgrading (snippets, portal, module parameters, relations and object key)
XML Enhancement: PHP snippets inside the XML.
XML Enhancement: the default value for a module's parameter can now be specified (and altered) via the XML and will no longer reside in the configuration file.
API Enhancement: allow the API to create Case Log entries with a specified user_login.
Modularization of the portal. The entry points for portals is now defined in XML, and thus can be altered by an extension.
#1053 XML comments breaking the setup with message "Notice: Undefined property: DOMComment::$wholeText in ...modelfactory.class.inc.php on line 1280". Now, the XML comments are allowed.


Internals
----------------------
Code cleanup: deprecated the unused (and empty) class CMDBSearchFilter, replaced by DBSearch or DBObjectSearch depending on the usage.
Added an alternate implementation for storing "transaction" identifiers on disk instead of inside the $_SESSION variable.
Mutex instrumentation for troubleshooting...
Make sure that the SQL mutexes are specific to the current iTop instance, but still preserving the capability for the setup to detect an already running cron job with or without a valid config file.
Integrated the lexer/parser build tools (Lexer=0.4.0, Parser=0.1.7)
Implemented GetForJSON and FromJSONToValue for AttributeLinkedSet (though this is not used for the Rest/JSON services which are doing much more) -retrofit from branch 2.1.0
Make it possible to overload RestUtils (static methods called with static:: instead of self::) - iTop NOW REQUIRES PHP 5.3: we have verified, there are very installations of iTop made on PHP 5.2. It is worth to note that PHP 5.3 is already end of life (5.4 will become end of life in 8 months)
Improved the symptom when an error occurs in the "apply stimulus form". The symptom used to be: Object could not be written; unknown error. Now it will give the error message (e.g. Missing query arguments) so as to help in determining what's going on.
ormStopWatch::GetElapsedTime not working in case of queries containing :this-> parameters (the prototype of GetElapsedTime has changed and is NOT compatible with the previous one)
Fixed a typo on the default document mimetype: application/x-octet-stream
Meta information on lifecycle actions arguments: added type restrictions, and added the method ResetStopWatch
Additional markup for JQuery scripts...
Forms Enhancement: do not retrieve disabled fields.
Forms : Support several sets of forbidden values (with a specific "reason" message) per field.
- Read-only "long text" fields no longer appear as editable
- Combo and FormSelector fields are now sorted by default (but sorting can be disabled if needed)
Protect against JS errors when the form is in read-only mode.
Properly handle property_sheets with nested selector fields...
#803 template placeholders are now built on demand.
#1060 Internal: improved the symptoms when calling MetaModel::GetAttributeDef with an invalid attribute code (feedback on the class name and no more FATAL errors)
Internal: fixed the caching of DBObject::ToArgs()
1) Wasn't reset when the object was written the DB (thus having its ID set)
2) Wasn't taking the argument name into account (the list of placeholders was defined by the first caller)
Change of the QueryReflection API to support DesignTime.
ModelFactory: Re-creating a class into another location in the class hierarchy it equivalent to moving that class => the delta must be a "redefine" for the class (improved the comment from the previous commit)
ModelFactory: Re-creating a class into another location in the class hierarchy it equivalent to moving that class => the delta must be a "redefine" for the class



3.2. Known limitations (https://sourceforge.net/apps/trac/itop/report/3)
     -----------------
#71   The same MySQL credentials are used during the setup and for running the application.

Suhosin can interfere with iTop. More information can be found here: http://www.combodo.com/wiki/doku.php?id=admin:suhosin
Internet Explorer 6 is not supported (neither IE7 nor IE8 in compatibility mode)
Tested with IE8 and IE9, Firefox 3.6 up to Firefox 24 and Chrome. Be aware that there are certain limitations when using IE8 in "security mode" (when running IE on a Windows 2008 Server for example)


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
#399 	Copy/paste from iTop's CaseLog looses tabs
#343 	CKEditor (HTML Editor) not compatible with direct object creation on ExtKeys
#350 	Object edition form: validation does not tell which field has a problem
#730 	Leaving temporary files when performing a backup of the data during installation
