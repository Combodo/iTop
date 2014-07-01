iTop - version 2.0.3-beta - 13-Jun-2014
Readme file

1.   ABOUT THIS RELEASE
2.   INSTALLATION
2.1. Requirements
2.2. Install procedure
2.3. CRON
2.4. Upgrading from 2.0.x
2.5. Migration from 1.x versions
3.   FEATURES
3.1. Changes since 2.0.2
3.2. Known limitations
3.3. Known issues

1. ABOUT THIS RELEASE
   ==================
Thank you for downloading the 18th packaged release of iTop.
This version is a maintenance release, with quite a few bug fixes and a few enhancements.

The documentation about iTop is available as a Wiki: http://www.combodo.com/wiki

iTop is released under the AGPL (v3) license. (Check license.txt in this directory).
The source code of iTop can be found on SourceForge: https://sourceforge.net/p/itop/code/

1.1 What's new?
    ---------------------------
This version brings a number of bug fixes since 2.0.2 and a few enhancements, namely:

- Scalability: better support of large volumes of objects, with much less memory usage
- Cleanup of the REST/JSON API, a few rough corners have been rounded
- Conditional notifications
- Usability: faster display of an object's details

... and about 50 bug fixes!

1.2 Should I upgrade to 2.0.3?
    -------------------------------
Considering that iTop 2.0.3 is fully compatible with iTop 2.0.x and the number of bugs fixed, we recommend you to upgrade.

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
Bruno Cornec for his support and contribution to the Linux packaging of iTop
Jean-François Bilger for providing a fix for an unsuspected SQL bug

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

More information into the Wiki: https://wiki.openitop.org/doku.php?id=2_0_2:admin:cron

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
The version 2.0.3 if fully compatible with 2.0.0, 2.0.1 and 2.0.2. Due to few database changes,
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

3.1. Changes since 2.0.2
     -------------------

This maintenance version consists in a mix of bug fixes and enhancements.

Enhancements
--------------------
Memory consumption by the application has been drastically reduced.

Usability
--------------------
#934 Support the <display_style> option for ExternalKeys: horizontal and vertical radio buttons groups are now supported
#933 Search form for Query Phrasebook items. If you implement your own menus (equivalent to itop-welcome-itil module), make sure that you update the menu definition to show the search form at the top.
#929 Speed up the full text search (mostly from the end user perspective, requires a custom configuration)
#930 AttributeExternalFields displayed in a form are automatically refreshed when their "parent" field is modified...
#909 Faster display for the "details" of an object:
     - object's history is only loaded when the "History" tab is clicked
     - by default the history display is truncated to the 'max_history_length' (= 50) latest modifications
#878 Missing scrollbar in "linkset-direct" edition popup dialog
#862 Popup menu misplaced when the window scrolls (e.g. when displaying large lists of results)
#861 and #636 Set the focus on User Name in iTop Login Form
Ticket's attachments can now be added by drag and drop (on browsers supporting HTML5 drag and drop). 


Scalability / Performance
-------------------------
#867 (and #907 as a dup') De-harcode set_time_limit (per loop) in lengthy operations. Default value is 30 seconds (per loop), configurable via the new parameter "max_execution_time_per_loop", instead of 5 seconds previously.
Compatibility with APCu (For PHP 5.5+), since it is slightly different from APC.
Two experimental perf. enhancements:
- maintain list the attributes (potentially) modified to speed-up ListChanges() by avoiding a systematic comparison between the content of linkedsets.
- cache the list of SynchroDataSources and use this in InSyncScope() to avoid searching in the SynchroReplicas when it's not needed...
Depending on the configuration, these optimizations may speed-up the CSV import by up to 40% !!
Experimental perf. enhancement: cache the foreign keys to use when importing object to avoid searching for the same object several times during a given import. Seem to speed up the imports by 7 to 10%.
CSV export (from the toolkit menu) now displays an asynchronous page, to better cope with a huge number of objects (> 10000)
- Memory optimization: no longer store all DBObjects in memory while browsng through a Set, but pull them one by one from the MySQL client buffer as needed.
- Also renamed Merge to Append since it's really what it does (seems to be used only in the tests)
Code cleanup to implement the tabs handling (inside web pages) in one place. Added the ability to provide asynchronously loaded tabs (content must come from the same server).
Use the object oriented verison of the MySQLi API which seems  free of memory leaks (compared to the procedural version of the same API).

JSON/REST API (new version: 1.2)
--------------------------------
#926 Proper "report" data when performing a Delete operation
#925 Added an option to output all the fields of the object found (not only the fields of the queried class), using "*+" for the list of queried fields
#897 Improved the error reporting when an external key is specified with a final class that is not a subclass of the class of the external key
#891 Better error reporting when either the parameter auth_user or auth_pwd are missing.
#877 More flexibility on case log updates (in particular, it is now possible to write the entire case log), remains compatible with the previous API
#869 API was not outputing case log attributes (not in a structured way)
Properly handle external and basic authentication methods for REST web services.
Proper output of boolean values in JSON.
Bug fix: the JSON value for an enum should be the raw value, not its translated label.

Data model fixes/changes
------------------------
#854 Flag Is null allowed not working on attributes Date and DateTime + the default value is now taken into account
Fixed issue with 1.x datamodels: dashlets of type "badge" not working (preventing from editing an existing dashboard), since 2.0.2
Aligned the authentication module with the one of 2.x, to enable the feature "Forgot password" for legacy data models
Added the "outage" field to simple Change tickets, since it's already present in ITIL Changes.


Notifications
-------------
#901 Added the attribute "filter" to the triggers, to define conditional notifications
#872 Support notifications for the creation of a new user. Also fix the translation of the "Additional values" in ValueSetEnumClasses.
#856 allow asynchronous emails to have an empty 'to' recipient... (not used anyway)
#483 Added placeholders for the notifications: html(caselog), head_html(caselog), html(linkset). The HTML can be customized. Fixes the issue about lines being wrapped in a curious way (root cause: swift mailer).


Miscellaneous fixes
-------------------
#943 Fix for supporting drop-down lists/auto-completes based on a parametrized query in the portal.
#936 Tune the default (i.e. implicit) tracking level on link sets (and disable tracking on 1-N links, for fresh installations)
#935 Better support of CheckToWrite() in object's transitions, improved by checking the data sooner for a consistent workflow.
#931 Management of n:n links can be broken in case of insufficient user rights. Side effect: attribute_linkedset with the flag OPT_ATT_HIDDEN are now completely hidden (the tab is not displayed at all).
#928 Setup crashing if async_retries is configured
#923 prevent XSS injection in forgot password page.
#919 Circular references between tickets (parent/child). Protect the framework against infinite recursions on cascaded updates (done at the DBUpdate level). 
#918 TTO/TTR status "passed" gets reset when the stop watch is stopped (using the status "triggered" instead)
#913 Error when searching for child requests and no organization is specified. Still, I could not figure out WHY IT WAS WORKING WHEN AN ORG IS SELECTED as a search filter!
#905 The toolkit menu was visible in the portal for Administrators (but it was not usable). It is now hidden in any case.
#896 XSS injection on the portal (any search form)
#890 Dispatch the defines in the proper modules to make sure that the portal works with all possible combinations of tickets.
#888 Security on the portal incompatible with customizations (regression introduced in 2.0.2), now requires to define PORTAL_USERREQUEST_DISPLAY_QUERY and PORTAL_USERREQUEST_DISPLAY_POWERUSER_QUERY
#887 Short term fix for preventing ToArgs to alter the content of an object...
#886 Delete change history so that if an ID is reused the history starts from scratch (and cleanup most of the data as soon as the object is deleted)
#881 Paginated list in popup dialog is broken
     - Missing scrollbar in the popup when using the [+] button
#876 Upgrade finishes with error "Cannot reload object id = -1" (root cause: DB in read-only mode, see config/access_mode)
#875 Could not use OQL queries with a double quote in the condition
#873 Allow the character % in the path of an URL (requires the edition of the config file when upgrading)
#871 eMail validation pattern was too strict: now fully configurable (globally and per attribute).
#870 When a user deletes all her/his shortcuts at once, this was deleting all the shortcuts for all users.
#859 About box: also list the modules installed from the extensions folder
#731 Full text search requires a string of at least three characters (configurable: full_text_needle_min)
Completed the Portuguese translation (Brazil), provided in december... (by Marco Tulio?) - modules updated: attachments, change, incident, request and request/ITIL, service for providers
Portal + templates: Bug fix = when the user selects a template, then go back to select a service for which no template applies, he still gets the tempate fields in the final form.
Added a helper function to get an icon stored as an ormDocument: ormDocument::GetDownloadURL
Full text search shortcuts: allow the use of class names containing underscores and numbers (e.g. Processus métier: écarissage)
Properly optimize the columns to load, when subitems are requested.
Allow the use of any character into the help text on an attribute (usefull to explain a constraint implemented as a regular expression for instance.) Reminder: the text is given as a dictionary entry named like "Class:<class>/Attribute:<attcode>?"
Fixed a compiler error message (wrong syntax when using a PHP class to implement the class methods)
Limit the display of the status to the latest 100 runs of the synchro data source.
Implement the iDisplay interface on any class derived from DBObject, but also limit the possible actions on such objects (disable edition)
Code cleanup to implement the tabs handling (inside web pages) in one place. Added the ability to provide asynchronously loaded tabs (content must come from the same server).
Run Query enhancements
- Properly catch *all* exceptions and redisplay the entered OQL statement every time
- Post the form to force its refresh (i.e. running the query again) even if the query did not change
Better handling of the default choices in the setup, in case of upgrade (for some specific configurations of the installation wizard).
Object's edition: keep track of what was typed in the case log fields when reloading the form (for example with a different "initial state")
Protect Bulk Modify against XSS injection!
Bug fix : missing semicolons were causing an error with IE9.
Finalized the French translation for some types of "Triggers"
Templates processing aligned with "templates-base" 2.1.1: allow template fields with the same name the attribute code of the curent object.
Make the Basic Authentication (login_mode=basic) work with non-ASCII characters (in the username as well as in the password), though this may depend on the browser...
Add a new flag "debug" (false by default)  to turn off the debug traces of the 'authent-ldap' module since the traces contain potentially sensitive information in clear text.
Demo mode: disable the pin button on the left pane (and keeps it open and resizable)
Fix for Plugins: if a page uses set_base then JS popup menu items were reloading the page. Still, set_base should not be used!
Enabled KPI tracing for the export page
Optimization: map the extended attribute code to the corresponding external field when this if possible (ex: org_id->name to org_name); this reduces the number of queries, in particular when using the "export CSV" menu on a list.
Optimize the queries for the export page
Resetting the stop watch...do clean the first start date when it is not running!
Allow to reset a running stop watch (without stopping it!)
Preserve "hidden" template fields.
Dictionary string for the portal should not depend on a module
- Put back support of templates
- Make sure that unwanted parameters cannot be set when creating the ticket
Record the very same installation time for all modules.
Asynchronous emails: added a retry mechanism useful in case your SMTP server restricts the number of emails that can be sent over a period of time (usage: broadcasting a newsletter). The mechanism is not specific to sending email as it is implemented at the AsyncTask level.


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
