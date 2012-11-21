iTop - version 2.0.0 Beta - 30-Oct-2012
Readme file

1.   ABOUT THIS RELEASE
2.   INSTALLATION
2.1. Requirements
2.2. Install procedure
2.3. CRON
2.4. Migration from previous version
3.   FEATURES
3.1. Changes since 1.2.1
3.2. Known limitations
3.3. Known issues

1. ABOUT THIS RELEASE
   ==================
Thank you for downloading the twelfth packaged release of iTop.
This version is a major release, with a new datamodel, editable dashboards and user customizable lists.

A wiki is available: https://sourceforge.net/apps/mediawiki/itop/index.php?title=ITop_Documentation
Additional documentation can be downloaded from the link above:
 - User guide
 - Administrator guide
 - Customization guide
 - Implementation guide


iTop is released under the AGPL (v3) license. (Check license.txt in this directory).
The source code of iTop can be found on SourceForge: http://itop.sourceforge.net

1.1 What's new?
    ---------------------------
This version comes with an enhanced data model (but you can keep the existing one,
see the upgrade section hereafter).
- Virtualization, Data Centers, and Storage are now optional modules of the CMDB
- You can choose between fully ITIL-compliant tickets or a simpler model for
  managing user requests, incidents and changes

End users can customize the GUI:
- Dashboards become editable
- List of objects are configurable per user (list of columns and sort order)
- Some application settings can be overriden: language, favorite organizations, ... 

1.2 Should I upgrade to 2.0.0 beta?
    -------------------------------
Despite all the care taken to prepare this release, it is still considered as "beta" quality
and therefore is NOT recommended for a production use. However, feel free to use it and experiment.     
    
Note that upgrading an installation of iTop 1.x will preserve your original data model and data.
Would you like to benefit from the new modelization of the data, then you have to install 2.0
from scratch and migrate your data between the two applications by exporting and importing them back.

This version does fix a significant number of issues (sse the list below).
It also comes with significant improvements in the end-user experience:
- editable dashboards
- customize lists
- user preferences

1.3 Special Thanks To:
    -----------------
Bruno Bonfils for his guidance about LDAP and authentication.
Randall Badilla Castro and Miguel Turrubiates for the Spanish translation.
Jonathan Lucas, Stephan Rosenke and David Gümbel from ITOMIG GmbH, for the German translation.
Christian Lempereur and Olivier Fouquet for their feedbacks.
Everaldo Coelho and the Oxygen Team for their wonderful icons.
The JQuery team and the all the jQuery plugins authors for developing such a powerful library.
Phil Eddies for the numerous feedbacks provided, and the first implementation of CKEdit
Marco Tulio and Bruno Cassaro for the Portuguese (Brazilian) translation
Vladimir Shilov for the Russian translation
Izzet Sirin for the Turkish translation
Deng Lixin for the Chinese translation
Marialaura Colantoni for the Italian translation
Schlobinux for the fix of the setup temporary file verification.
Gabor Kiss for the Hungarian translation
Tadashi Kaneda, Shoji Seki and Hirofumi Kosaka for the Japanese translation
Antoine Coetsier for the CAS support and tests
Vincenzo Todisco for his contribution to the enhancement of the webservices
Tobias Glemser and Sabri Saleh for their consulting about iTop security
Claudio Cesar Sanchez Tejeda for his contribution to bug fixes on the export and data synchronization

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

2.3. CRON.PHP
     --------
The following features will require the activation of CRON.PHP:
 - asynchronous emails. By default, this option is disabled. To enable it, set 'email_asynchronous' to 1 in the configuration file.
 - check ticket SLA. Tickets reaching the limits will be passed into Escalation TTO/TTR states.

More information into the Wiki: https://sourceforge.net/apps/mediawiki/itop/index.php?title=Cron.php

2.4. Migrating from 1.x versions
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

3.1. Changes since 1.2.1
     -------------------

Version 2.0.0 brings a few major improvements.

Major changes
-------------
- Editable dashboards: end-users can edit a dashboard by the mean of a WYSIWYG GUI. They can share
  their tuned dashboards with other users by the mean of an export/import capability. They can also
  leave their custom dashboard and get back to the original dashboard.

The "Preference" page now allows a user to:
- change her/his favorite language
- set a global default for the length of all lists, overriding the system-wide configuration.
- change her/his favorite organizations

- The list of objects have been improved:
 - the end-user can change list of displayed columns, and the sort order
 - sorting issues have been fixed
 - almost every list can be exported

Localization
------------
No big changes in localization for this release.

More information on the localization (completion progress, how to contribute) here:
http://www.combodo.com/itop-localization/

Minor changes
-------------
The license has been changed to AGPL (replacing GPL/LPGL)
#421 Sort IP addresses on INET_ATON (API only, see #520 to have this as the default sort order for NW Interfaces)
#520 Capability to define a default sort order (PHP/XML)
#439 Record and display changes in the link sets (ex: Members of a team)
Implemented the "multiple choices" in search forms for Enums and External keys.
Added a refresh button (and creation /modification messages) on the details of an object
Friendly names: improved the behavior. Now fully compliant with end users expectations (e.g. a list of contacts shows the friendly name of the persons and team, not only the attribute 'name', the search can be performed on the friendly name as well)
The date picker fills the "time" part of the field with 00:00:00 when picking a DateTime instead of just a Date.
Allow more than 64K for the email content (including attachments)
Distinguish between creation and modification user rights
Updated schema.php to add web link to link class on linked set attributes
Reload the impact/depends on graph only on demand for better performance, via the new Refresh button
Move the "favorites" organization at the bottom of the page.
Do NOT grab cursor hotkeys (CTRL + left arrow) to hide/show the menu pane.
Enhancement: prevent reloading a list while the configuration dialog is open.
Pretty print of the configuration file (parameters ordered alphabetically + comments added)
Allow utilization of place holder in from and reply_to fields for action emails
Config: use app_icon_url to change the hyperlink used when clicking on the main icon
Added a new favicon
Cosmetic enhancements to ease the search for a class in the schema.
Integration of the latest version of CKEditor: version 3.6.4, released on 17 July 2012


CSV import/export
-----------------
#283  Import.php localized by default, option no_localize to disable
#175  When moving backward in the CSV import wizard, some settings may be reset (e.g column mapping)
#174  CSV import not displaying the labels of enums
#585  Error in CSV export (from a search result)
#265  Add reconciliations keys into CSV template
#554  Export.php localized by default, option no_localize to disable
#555  Friendlyname abusively used as a reconciliation key
Default charset is ISO-8859-1 to be compatible with Excel (See config parameter csv_file_default_charset)
CSV export in UTF-8 with BOM to help Excel in getting it right (not all versions)
Fixed reporting issues (wrong class, exceptions, changed external key)
Fixed settings lost when navigating in the import wizard
Fixed issues when some html entities were found in the data (reporting + export)
Added a link to download the CSV export.php
CSV import: added flag 'csv_import_history_display' to disable the history tab (too long to display, when the feature is heavily used)
CSV Import: when using cut&paste, the character set is de facto utf-8 (no user choice)
Do not allow changing read-only attributes by CSV import.

Data Synchronization
--------------------
#540  Data synchro: the option "write if empty" was not implemented
#582  "stable name" for synchro_data_xxx tables.
Make sure that the creation of the data_synchro_xxx tables uses the utf8 charset and collation and the same DB Engine as the rest of the database.
Added detecting of missing columns in the synchro_data_xxx tables (in case of duplicate SQL column names in the orignal data model). See Trac #503.
Bug fix: to do not try to access a DataSource while it's being deleted
Enhancement: added a new (hidden) configuration setting 'synchro_prevent_delete_all' (default to true) to deactivate the "safety belt" and allow the deletion of all replicas of a synchro task in one go.

CAS integration
---------------
- regression fix: support patterns for the MemberOf groups filtering
- activate/de-activate the profiles synchronization using the 'cas_update_profiles' configuration flag
- provide default profile(s) when creating a new user from CAS, only if no match is found for assigning profiles from the CAS MemberOf group(s).
- properly log-off (and report the issue in the log) in case we fail to create a user during the CAS Synchro

Bugs fixed
----------
The complete list of active tickets can be reviewed at http://sourceforge.net/apps/trac/itop/report/1

#583 Losing attachments when performing massive change
#528 Typo: criticALity
#527 Typo: license get an S in the US
#467 Friendly names not up to date when sending notifications
#411, #421, #520 Sorting of lists: sort is now always executed server-side.
#541 Fixed bug in the export for spreadsheet (time format)
#556 Reworked the caching of user rights data
#558 properly parse OQL strings containing hexadecimal sequences (i.e. 'QWERTY0xCUIOP'). Note that for now hexadecimal numbers are parsed but not interpreted properly...
#559 ldap user can login with blank password
#439 Make sure that changes made by a plugin get recorded
#565 Fixed security issues (XSS)


Other bug fixes not listed in Trac:
Do not perform time consuming computations for building the menus if there are too many objects in a list (limit is configurable).
Portal fixes (relative URLs and parameter validation)...
Restore the previous URLMaker in case the sending of a notification is not the last action of a page... (e.g. if the page displays the details of an object after sending the notifications...)
Protect against a non-existent "MapContextParams" function
Protects against too long string when logging web services events
XML Export: do not export "unimplemented" link sets, so that the resulting output can be used as sample data in the setup
Bug fix: properly export boolean attributes to XML (a value of false was creating an empty XML tag)
- HTML attributes > 64 Kb
- Log of notification displayed as HTML
Bug fix for queries where the selected class is not the first one in the list
Some changes to the application layout: logs now go to the ./log folder ./data should be used to store application's data.
Fixed an issue revealed by fix [2201], occurring when filtering on organization (context) - the fix is not complete (see Trac #588)
Bug fix: prevent 'assertion failed' when a block auto reloads: '0' is indeed a valid ID for a display block !!
Properly parse accentuated characters inside the "autocomplete" widget.
Protects the dialog resizing against some JS errors
Fixed issues with accentuated characters in the graphs (bars or pie)
Fixed issue in the portal: the list of opened requests and closed request where messed up when pagination was activated on both lists
Bug fix: preserve the previous settings in the configuration file in case of upgrade.
Fixed the "Reset(APC)Cache" at the end of the installation.
Fixed two bugs revealed with specific constraints (query expression like 'SELECT b FROM a JOIN b', AND the organization context is set)
Bug of month: make sure that GetFilter returns a usable filter (i.e. with the parameters)


Extension capabilities
----------------------
When there is still no dictionary available, the menus / classes / attributes have a default label based on their raw names (replacing _ by a blank)
Named tab containers instead of non-unique numbering !
Make GetConfig independent of the MetaModel
In the 'context', pass menus by ID and no longer by index.
Use the 'style' of the MenuBlock (inherited from DisplayBlock) to distinguish between a list of one object and the details of the same object.
Datamodel/Menus/Dashboards/Profiles are now defined in XML
Protect the download of documents against spurious blank lines coming from nowhere !!
Implementation of a new extension "iPopupMenuExtension" to allow a module to add menu items almost anywhere inside iTop.
Handling of "pure PHP" classes inside the data model
Don't perform computations inside GetAsHTML because this may cause an infinite recursion since GetAsHTML is called by ToArgs
Simplified the change tracking. Simply call DBObject::DBInsert (resp. Update and Delete) and the change will be recorded for the current page. This is compatible with the old (not mandatory anymore) way that was requiring DBInsertTracked APIs (resp. Update, Delete).
"extensions" is now the offical place for storing extension modules
Portal: enable adding dependent attributes in the request creation form
Objects always recorded before the notifications are sent
Capability to add 'attachments' => array of ormDocument to the context of a trigger, the attachments will be added to the email sent
Added the ability to Find then Remove a tab inside a page
Support edition of the "latest modified" entry of a case log
The hierarchical key in Organizations is not always named 'parent_id'


3.2. Known limitations (https://sourceforge.net/apps/trac/itop/report/3)
     -----------------
#71   The same MySQL credentials are used during the setup and for running the application.

Suhosin can interfere with iTop. More information can be found here: https://sourceforge.net/apps/mediawiki/itop/index.php?title=ITop_and_Suhosin
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
#597 	IE9: Black border around icons
#443 	Objects remain in the database after de-installing some modules
#442 	Useless profiles installed
#441 	/doc redirects to Apache documentation!
#438 	The selection of Organizations using the hierarchy does not work on IE8 in security mode
#436 	Cannot type "All Organizations" 	
#398 	Import CSV: Unchanged attributes marked as "modified"
#381 	Deletion of dependencies could fail in a multi-org environment
#241 	"status" is a free-text field when configuring a Trigger
#358 	Multi-column queries sometimes returning an empty set
#383 	OQL: negative integers not allowed (workaround: 0 - 1)
#399 	Copy/paste from iTop's CaseLog looses tabs
#343 	CKEditor (HTML Editor) not compatible with direct object creation on ExtKeys
#350 	Object edition form: validation does not tell which field has a problem
#317 	Edition of a Document - opens the second tab
