============================================
WARNING - THIS IS NOT AN OFFICIAL RELEASE!!!
TO BE USED FOR INTERNAL PURPOSES ONLY!
============================================
iTop - version 2.1.0 - 16-Dec-2014
Readme file

1.   ABOUT THIS RELEASE
2.   INSTALLATION
2.1. Requirements
2.2. Install procedure
2.3. CRON
2.4. Upgrading from 2.0.x
2.5. Migration from 1.x versions
3.   FEATURES
3.1. Changes since 2.0.3
3.2. Known limitations
3.3. Known issues

1. ABOUT THIS RELEASE
   ==================
Thank you for downloading the 20th packaged release of iTop.
This version is a maintenance release, with quite a few bug fixes and a few enhancements.

The documentation about iTop is available as a Wiki: https://wiki.openitop.org/

iTop is released under the AGPL (v3) license. (Check license.txt in this directory).
The source code of iTop can be found on SourceForge: https://sourceforge.net/p/itop/code/

1.1 What's new?
    ---------------------------
This version brings a number of bug fixes since 2.0.3 and a few enhancements, namely:

- A rudimentary configuration file editor (for administrators)
- Automated data backups, and manual backup/restore
- Excel exports
- Dutch translation contributed by Remie Malik from Linprofs (www.linprofs.com)

... and about 80 bug fixes!

1.2 Should I upgrade to 2.1.0?
    -------------------------------
Considering that iTop 2.1.0 is fully compatible with iTop 2.0.x and the number of bugs fixed, we recommend you to upgrade.
Anyhow, prior to taking that decision, we encourage you to have a look at the migration notes:
https://wiki.openitop.org/doku.php?id=2_1_0:admin:203_to_210_migration_notes

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

2.4. Upgrading from 2.0.x
     --------------------
The version 2.1.0 if fully compatible with 2.0.0, 2.0.1, 2.0.2 and 2.0.3. Due to few database changes,
and new modules that have to be installed, you must run the setup when upgrading (whatever the original
version).

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

3.1. Changes since 2.0.3
     -------------------

This version consists in three new features and bug fixes.


Configuration editor
--------------------
This is a way for administrators to edit the configuration file from the iTop UI, with their iTop credentials.


Automated backups
-----------------
Performs automated backups. The scheduling can be configured, and it handles a rotation.
A dedicated page provide administrators with a status report and allows for performing manual backups
and restores.


Excel exports
-------------
In addition to the existing CSV export features, it is now possible to generate an Excel file directly,
from any list of objects.


Usability
--------------------
#1011 Proper resizing of the dialog box for managing 1:n links.
#974  Prevent multiple javascript refresh when reloading the "initial state" of a ticket.
#985  Preserve the displayed sort order when refreshing a table.
#1016 Record the displayed value of the database_table_name field in the database. This happens:
 - when creating a new Synchro Data Source
 - when modifying an "old" Synchro Data Source for which the field was empty.


Scalability / Performance
-------------------------
#965  Since 2.0.3, for each synchronized object, around 100 queries are performed (2 are required), and this is multiplied by the number of duplicate replicas (then resulting in a significant slowdown).
#1020 Restrict dashboard/shortcut refresh interval (new parameter: min_reload_interval)
#1028 Speed up the display of history and object creation (regression since 2.0.3). Beware, conversion time at setup can be long if the history table is big.


Notifications
-------------
#998 Accurately check the configured mail transport and report information accordingly in the email.test page.
Add two "debug" transports for Swift mailer: Null and LogFile which are useful for staging environments where one does not want to send real emails.
#978 Email test utility always reporting "default SMTP port"


Miscellaneous fixes
-------------------
Portal: handle mandatory attributes in Reopen/Close dialogs
#1000 Implemented the behavior for the flag OPT_ATT_MUSTCHANGE, and took the opportunity to add a feedback when a field is mandatory OR when the format is wrong
#1012 Losing half of the connection when changing a port (connections between network devices). I took the opportunity to simplify the connection management as it was initiated in change [3388].
#1008 Error when deleting a Network Device connected to another Network Device (does not happen if the other end is another type of "ConnectedDevice")
#1007 Unexpected change of the case log when doing massive update of a User Request (+ hide the checkbox for the status because it makes no sense)
#979 Data synchro: recover the DB triggers (backup/restore)
Fixed regression introduced in iTop 2.0.3, in the data model view: could not see the OQL constraints on external keys
#995 Make sure that tto/ttr passed gets set even if the CRON has not been run (and as soon as some overrun has been counted)
#983 Sortering not possible on multi-column queries
#969 XML: the menu option enable_admin_only hides the menu for everyone
#970 and #650 Corrupted attachements. Reworked the cleanup of undesired output, to protect it against the case when the output buffer is unfortunately closed. On the other hand, I found out that several output buffer can be stacked. Thus the protection could be further improved (difficulty: that can be web server dependent).
#993 The about box does not show up when the directory extensions is missing
Fixed the support of a non-default port for MySQL, thanks to theBigOne!
#968 Interactive CSV Export truncated or missing characters (since 2.0.3)
#991 CSV export truncated (system dependent, since 2.0) due to a bug in iconv, the workaround is to do little by little
Dehardcoded the size of the attachments preview
#988 Could not change the case of a login
#778 Issue on list sort order when editing an element (N-N link tabs)
#986 Search form: handle indirect external keys
#987 Usage login prevents from user deletion
#932 Search form should be prefilled when running a search "shortcut" - very little progress: fixed the case when several criteria are given
#985 Shortcut auto refresh degrading table cosmetics
#984 Dashboard auto refresh degrading table functionalities like sorting
#976: make sure that we do not bypass the method that computes the reference for newly created tickets.
Protect dashboards against invalid queries in "grouped by" dashlets.
Legacy user rights management: allow the deletion of a profile in one step (it was nearly impossible because of the numerous related records, mainly of type URP_ActionGrant, for which iTop was requesting a manual deletion)
#1026 CSV import of tickets with impact = '', issuing a Notice
#1021 Better alignment of multiple header dashlets in the same cell.
#1027 CSV import failing abruptely in case of ambiguous reconciliation on an external key. Regression introduced in 2.0.3.
#1030 Missing values in the history tab (TTO/TTR) (since 2.0.2) There is no data loss: changes were correctly made and all the changes already recorded will be correctly displayed with the current fix.
#975  Modified the enumerated list for model type in order to manage Phone CIs
Allow linkage of organization to a Delivery model, directly from the tab "Customers"
More meta information about the interfaces.
Replaced provider_name by provider_id in the search form of service-subcategories
Reviewed the french translation
Added a tab to link a problem to incidents
Missing translation for the tab "related requests"
#1022 Do cascade the resolution of an incident to its child requests
Prevent the JS validation (on focus) to create multiple entries for the same field, since it breaks the validation.
#1039 Prevent concurrent executions of either synchro_import.php or synchro_exec.php for a given data source, since it would lead to unpredictable results.
#1037 Refresh "priority" when either "impact" or "urgency" changes.


Extending the data model
------------------------
#972 Incomprehensible error message during setup, with a sample extension provided by Combodo! (empty user rights tag)
#971 XML: could not specify an icon as a path to a file
User rights: deny on a parent class must give DENY even if the class is explicitely ALLOW on the same profile (that was already working if the rules are given on several profiles). Added a config flag to force the legacy algorithm (user_rights_legacy, defaulting to false)
#1029 Got rid of tags <format> that were not used at all and that were really misleading extension developers
#1032 When adding a case log, existing objects could not be displayed anymore!

Improved the XML format, changing from 1.0 to 1.1
- The change is ascendant compatible (automatically converted into 1.1 by ModelFactory) and thus sould be transparent: could may leave your extensions unchanged if you do not need to benefit from the new format
- Added <inherit_flags_from> to inherit the flags from another state
- Added an id on the user rights profile/actions to allow a finer granularity for the deltas.
- New concept: HighlightScale to avoid overloading methods GetIcon and GetHilightClass...
- Added an id on the transitions to allow a finer granularity for the deltas.
- Rework of the lifecycle/actions to ease the extensibility (Generic handlers replacing the specific ones: Rest, Copy, SetCurrentDate, SetCurrentUser, SetElapsedTime)


Internals
----------------------
Protected the property fields against the collision of ids within the same page (even if that is a bug, make it work not too bad!)
Forms: drop-down box default value label could be changed (or this entry could be entirely removed)
Form fields: added callbacks ('equals' and 'get_field_value') to allow the implementation of enhanced form fields
The FormSelectorField now has its own widget to properly cope with its "subfields" in "property sheet" mode.
Support of more sophisticated forms layout...
Proper handling of the validation of subforms...
Read-only mode for icon selector widget: display at least the icon.
lnkVirtualDeviceToVolume and lnkTriggerAction are link classes and should be declared as such
Transmission of user rights along N-N links: must work both with DEL_AUTO and DEL_SILENT external keys (found with a code review, DEL_SILENT is still rarely used)
Rework of the ModelFactory API to make it simpler and safer.
Instrumented Model Factory with means to keep track of touched nodes
#989 Developper issue: query arguments having a null value are dismissed
Bug fix: FetchAssoc was broken when dealing with in-memory sets.
Improved the processing of background task to enable more advanced functionalities like queuing
Declaration of generic methods which can be run on tickets.
Enhanced reporting during the setup: all the queries (create table / alter table) are now logged into "setup.log" along with their execution time.
Instrumented the code to ease the troubleshooting of the computing of working hours
New function: ormStopWatch::GetElapsedTime to compute the cumulated elapsed time on a stop watch still running -not used yet (but tested!)
Predefined objects are now handled by RuntimeEnvironment
Support for some (optional) feedback during submit.
Support for some (optional) feedback during uploads.
Rework of the user rights data model, while strictly preserving the current functionality (checked using the tool dump_profiles.php, with simple to full ITIL configurations). Class groups have been renamed/merged/removed. This is documented in the migration notes (wiki).
Rework of the dictionaries: moved menu related entries to the module itop-welcome-itil (which does create most of those menus), while preserving the original copy of the entries so as to be compatible with customizations made with a copy of an older version of itop-welcome-itil
Cosmetics on the module names (consistency)
Demo mode: prevent the deletion of Users...

Packaging
-----------------------
#960 [RPM Packaging] Adjust line endings in READ and LICENSE files
#962 [RPM Packaging] Added the use of logrotate for cron.log and error.log
#959 Fixing licensing mismatches for compatibility with the Fedora licensing policy (the modification only affects comments) .



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
#1034	Excel export on the command-line ignoring the list of fields
