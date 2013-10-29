iTop - version 2.0.2-beta - 29-Oct-2013
Readme file

1.   ABOUT THIS RELEASE
2.   INSTALLATION
2.1. Requirements
2.2. Install procedure
2.3. CRON
2.4. Upgrading from 2.0.x
2.5. Migration from 1.x versions
3.   FEATURES
3.1. Changes since 2.0.1
3.2. Known limitations
3.3. Known issues

1. ABOUT THIS RELEASE
   ==================
Thank you for downloading the 17th packaged release of iTop.
This version is a maintenance release, with quite a few bug fixes and a few enhancements.

The documentation about iTop is available as a Wiki: http://www.combodo.com/wiki

iTop is released under the AGPL (v3) license. (Check license.txt in this directory).
The source code of iTop can be found on SourceForge: https://sourceforge.net/p/itop/code/

1.1 What's new?
    ---------------------------
This version brings a number of bug fixes since 2.0.1 and a few enhancements, namely:

- Modelization of VLANs on Subnet and Physical Interfaces
- Brand new User Portal look and feel (no change in the behavior)
- Forgot your password? Regain access to iTop without bothering an administrator
- Automatic refresh of the lists in the dashboards, and for the shortcuts
- Scalability: better support of large volumes of tickets, and in general with large volumes of data

... and about 50 bug fixes!

1.2 Should I upgrade to 2.0.2?
    -------------------------------
Considering that iTop 2.0.1 is fully compatible with iTop 2.0.0 and the number of bugs fixed, we recommend you to upgrade.

Be aware that the User Portal appearance has significantly been changed.

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

2.4. Upgrading from 2.0.x
     --------------------
The version 2.0.2 if fully compatible with 2.0.0 and 2.0.1. Due to few database changes,
you must run the setup when upgrading (whatever the original version).

If the location of mysql binaries is in the "path", the setup proposes to perform a full backup
of iTop (database + configuration file) using mysqldump.

Here is how to upgrade, step by step, a 2.0.x instance of iTop:

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

3.1. Changes since 2.0.1
     -------------------

This maintenance version consists in a mix of bug fixes and enhancements.

Enhancements
--------------------
- Modelization of VLANs on Subnet and Physical Interfaces
- User Portal:
    * Brand new look and feel (no change in the behavior)
    * Capability to manage both User Request and Incidents by setting portal_tickets to 'UserRequest,Incident' -see Portal/readme.txt
- Forgot your password? Regain access to iTop without bothering an administrator
- Automatic refresh of the lists in the dashboards, and for the shortcuts

Usability
--------------------
Added buttons to check/uncheck all options at once in multi selects inside search forms.
Better UI to manage direct linksets: added the ability to provide the "reverse query" by specifying a '<filter>' tag on AttributeLinkedSet.

Scalability
--------------------
No time limit for long operations like: Bulk delete, CSV import (interactive) and Bulk modify
Speedup the display of lists of tickets (large volumes)
Speedup the display of object details (long history track) 
For technical details: see tickets #783, #233 and #466

JSON/REST API (new version: 1.1)
--------------------------------
#767 Reconciliations must be made on strict criteria
#758 Key given in clear in the returned objects (incremented the API verion to 1.1)
+ core/create and core/update, allow to reset an external key (0)

Data model fixes/changes
------------------------
Added VLANs on Subnet and Physical Interfaces
Added a version for Documents
#800 No need to track that last update has changed each time the ticket gets updated (common to all types of tickets)
Prevent Support agent to create ticket for obsolete Services and Service sub categories
Remove duplicate display of attribute service provider
#792 Duplicate entries in the parent/child tickets when updating the case log and applying a stimulus (e.g. Close the WO) at the same time.
#805 Make sure tickets are named on their id (concurrent access was not taken into account)
#804 tickets' highlighting is now based on the computation performed by the stopwatch, in order to support non 24x7 working hours
#754 Prevent UserRequests to have themselves set as Parent Request (and same for Incidents)
Modify Sample data for Service categories to set them to status "production" by default
#768 Avoid to select obsolete service and service sub categories in the portal
#789 Add up to 12 Digit for a IPInterface
#755 Prevent modification of CIs and Contacts list for UserRequest and Incidents
#742 Allow portal user to modify a ticket when it is pending
#739 Prevent Support Agents from settings a UserRequest to status "Pending"
#751 Check that class Logical Volume exists when checking dependencies of a Server + Add attribute Subnet name on Subnet element
Moved definition of the delivery model of an organization from itop-config-mgmt to itop-service-mgmt module.
New pattern accepting the new global Top Level Domains (gTLD)
Allow "Support Agents" to put an Incident in "Pending" state.
#747: protects against the non-existence of the UserRequest class (which is not always installed).


Miscellaneous fixes
-------------------
Localization: French and german (#562 and #760) have been reviewed.
Compiler 
- added "constants"
- added brand management
- safe compilation (works in a temporary directory, on success then move it into env-production)
- possibility to introduce a delta (not in a module) at compile time
- allow to set the flags enable_class/enable_action etc. for a TemplateMenuNode (already taken into account at runtime)
- added indexes
Added a demo mode (config: demo_mode = true). In that mode, logins get read-only (even for admins)
#785 Share the results of a query phrase (preview of the results in the query details page -iif it has NO parameter)
#783 Added the placeholder $this->xxx_list$ for emailing (names separated by a new line, truncated to 100 items)
Reviewed the instrumentation to help in tuning the performance (added a message in the admin banner when logging is active + measure the impact of object reloads)
#771: better display for "edit in place".
#795 Issue when using the actual (id) value of an external key as a reconciliation field
#741 Complete localization of the CSV import confirmation dialog
#790 Only report as installed the modules from the previous installation, not all previous installations.
#738 Adding a space at the end of the mailto: URL to make it better recognized by mail clients (namely Outlook)
#791 Protect against single quotes in localized strings...
#777 mandatory fields that are external keys are now displayed with a star before the arrow: ExtkeyName*->ReconciliationField. In import the old syntax is supported as well.
#769 Title of pies and charts are not consistent with the title of other dashlets
#794 Could not export the field friendlyname in format 'spreadsheet'
#793 provide the default '=' and '!=' operators for all types of Computed Fields.
#773 Display boolean values from the stop watches as yes/no (localized, like enums) + took the opportunity to enable the export in spreadsheet format
#762 Remove wrong fields approval_date approval_comments  for  a Routine change
Retrofit the useful DoPostRequest function which was used (and defined) in several extensions.
Added support of different (sub)classes of notifications in the "Notifications" tab on an object.
Fix for properly computing the default choices in case of upgrade...
#745 Default menu is not computed correctly (depends on the customizations made to the menu -> order of declaration)
The login web page must NOT be cached by the web browsers
#774 Sort the enums in the selection drop-down box (search forms) -initially based on the declaration order
#782: preview (as a tooltip) for image attachments.
#784 Data sync: display the attribute code (as well as its label in the user language)
#781 Plain text dashlet shown on one single line
#779 It is possible to record a wrong OQL in the phrase book, but then it cannot be edited anymore!
Internal: failed authentication to return error 401 instead of prompting the end-user (to be exploited by the ajax calls)
Logoff: display the message in the user language (used to be 100% english)
Generalized the option tracking_level to any kind of attributes. Defaults to 'all', can be set to 'none' to disable the change tracking on a single attribute (LinkSets still have the same allowed values: none, list, details and all).
Protect the deletion of objects with very long friendly names
Cosmetics on the login web page
Allow for comparisons of the module's versions in the expression of dependencies. For example one can now say "itop-config-mgmt/>=2.0.2" for a dependency.
Internal- ModelFactory: needed / define_if_not_exists were not equivalent
#763 Could not use "configure this list" once a stop watch has been added to the list, which is a pitty because such attributes are not aimed at being displayed in lists!
Fixed bug (wrong DB charset after invoking AnalyzeInstallation!)
Load structural data for all selected modules indepently of:
- the load of sample data
- first install or upgrade
Management of environments: the banner must be injected by the mean of iPageUIExtension
Module installation information always loaded within the meta model
Make the logo transparent (background removal)
CRON:
- report that CRON is already running BEFORE saying that the DB is read-only (re-entrance during an operation done in the background)
- protection against re-entrance now relies on a bullet-proof mutex. Also added the option 'debug=1' to output the call stack in case an exception occurs (not always because of passwords being shown in the call stack)
- reschedule at startup IIF the task is inactive or it is planned in the future
- exit gracefully if iTop not yet installed
- handle tasks scheduled at given date/time (as opposed to a task being executed more or less continuously).
New mechanism: a module page can be accessed by the mean of a canonical URL (utils::GetAbsoluteUrlModulePage to build the proper URL)
#752 Notifications sent several times (or too late) when MySQL is hosted on another server
Setup: Source dir recorded with a trailing backslash under windows
Restored the original behavior of itop-sla-computation (if present, then it becomes the default working hour computer)
Improved the error reporting for the backup (in case mysqldump fails with a single error, then the error is displayed directly)
New verb "AfterDatabaseSetup" for performing installation tasks after the completion of the DB creation (+predefined objects & admin account)
#746 allow adding an AttributeBlob with is_null_allowed = true to an existing Data Model. (same issue fixed also for AttributeOneWayPassword).
Properly handle nested forms in "PropertySheet" and "read-only" mode
Bug fix: validation was broken when the first fields were not Ok.
Export the content of the CaseLogs in "spreadsheet" format, with some tricks to preserve the formatting in Excel.
Forms enhancements:
- The current value of a field is automatically excluded from the forbidden values
- Several levels of subforms can be nested, even when displaying as a property sheet
- Sortables fields re-implemented based on a widget.
- Specify forbidden values + message to explain the issue(toolip) (fiwed a bug on the previous implementation, causing a javascript error, hence a stopper regression due to missing event binds)
- Dialog: specify an introduction message
Protect against non existing reconciliation keys...
Completed the move of dashboards from separate definition files (e.g. overview.xml) into data model files (8 dashboards were concerned on the model 2.x, 6 for the model 1.x)
Re-position the popup menu each time the button is clicked, in case the button was moved...
Make sure that tabs (and tab panels) are properly identified
Removed the use of the obsolete $.browser property, since we don't care about IE 7 anyway.
Upgrade to jQuery 1.10 and jQuery UI 1.10
OQL normalization and dashlets have been made independent from the class MetaModel (adjusted the API)
Added OQL normalization unit tests (to be run on a standard installation)
#736 Could not delete objects unless you are authorized to bulk delete
#734 Fixed a regression on reconciliation keys during CSV import.


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
#730  Leaving temporary files when performing a backup of the data during installation
