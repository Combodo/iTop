iTop - version 1.2.1 - 01-Feb-2012
Readme file

1.   ABOUT THIS RELEASE
2.   INSTALLATION
2.1. Requirements
2.2. Install procedure
2.3. Migration from previous version
3.   FEATURES
3.1. Changes since 1.2
3.2. Known limitations
3.3. Known issues

1. ABOUT THIS RELEASE
   ==================
Thank you for downloading the eleventh packaged release of iTop.
This version is mostly a maintenance release that fixes a few bugs of iTop 1.2.

A wiki is available: https://sourceforge.net/apps/mediawiki/itop/index.php?title=ITop_Documentation
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
This version mostly consists in bug fixes for iTop 1.2, however there are a few new features:

- Better display of the impact analysis view: Allows to filter the classes of objects displayed in the
  graphical view. The view now resizes to fit the window of your browser.

- Query phrase book: create, test and store your OQL queries in the "Query Phrasebook" for an easy export
  via the "Export" menu, without the limitation of the URL length in Excel web queries ! A new export format
  "spreadsheet" has been added, specifically for running as Excel web queries (dates are split between date and time)

- Enhanced data synchronization:
  - For performing huge synchronizations with little memory (despite PHP's memory leaks), the execution of the data
    synchronization (in CLI mode only) can be run by chunk of x elements by specifying --max_chunk_size=x on the
    command line to synchro_import.php or synchro_exec.php. Try to set this value to 1000 to run with 128 MB of memory.
    As of now, there is no easy mean to check wether the execution has been split or not, and to evaluate the real benefit of this feature.
	 To do this, you will have to look into the database, in table priv_sync_log. The column memory_usage_peak gives you the maximum amount of memory used throughout the whole execution.  
  - When an (optional) external key cannot be reconciled, log a warning on the replica. the replicas containing a
    warning are then processed everytime in case the ext key changes.
  - Also improved the search/display of replicas for an easier troubleshooting of the synchronization.


1.2 Should I upgrade to 1.2.1?
    ---------------------------
Considering that there more than 30 bug fixes and very few new features, it's probably quite safe to upgrade to
this new version. If you are using the data synchronization with big sets of data, you can benefit from the new
"chunk" mode by just adding a parameter to the command line !
The interactive audit is now faster and uses less memory when processing big numbers of elements.


1.3 Special Thanks To:
    -----------------
Bruno Bonfils for his guidance about LDAP and authentication.
Randall Badilla Castro for the Spanish translation.
Jonathan Lucas, Stephan Rosenke and David Gümbel from ITOMIG GmbH, for the German translation.
Christian Lempereur and Olivier Fouquet for their feedbacks.
Everaldo Coelho and the Oxygen Team for their wonderful icons.
The JQuery team and the all the jQuery plugins authors for developing such a powerful library.
Phil Eddies for the numerous feedbacks provided, and the first implementation of CKEdit
Marco Túlio and Bruno Cassaro for the Portuguese (Brazilian) translation
Vladimir Shilov for the Russian translation
Izzet Sirin for the Turkish translation
Deng Lixin for the Chinese translation
Marialaura Colantoni for the Italian translation
Schlobinux for the fix of the setup temporary file verification.
Gabor Kiss for the Hungarian translation
Tadashi Kaneda for the Japanese translation
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

2.4. Migrating from 1.0, 1.0.1, 1.0.2, 1.1 or 1.2
     --------------------------------------------
You can simply overwrite the files from the previous version with the new ones but we recommend that you copy the files of the new version to new directory.
After installing the files, you MUST run the setup by
1) Marking the file config-itop.php as read-write for the web server
2) Poiting you web browser to http://<your_itop>/setup
The updgrade will modify the database schema. Be aware that this new schema is not compatible with the previous versions of iTop.

If you are executing the upgrade on a production instance of iTop, it is a good practice to make a backup of the database and the configuration file (config-itop.php) prior to running the upgrade.

Step by step instructions:
1) Unpack the files contained in the zipped package, and copy the content of the "web"
directory in a directory served by your web server.
2) Point your web browser to the URL corresponding to the directory where the files
have been unpackaged.
3) Select "Upgrade an existing iTop instance"
4) Follow the instructions.

5) If you were using tickets: CheckSLAForTickets.php (from 1.0 up to 1.0.2) has been deprecated in favour of cron.php - see section 2.3.


2.5. Migrating from 0.9
     ------------------
Depending on your current situation, there are several possible migration paths.
Please refer to the migration guide available at http://www.combodo.com/itopdocumentation.

3. FEATURES
   ========

3.1. Changes since 1.2
     -------------------

Version 1.2.1 brings a few major changes.

Major changes
-------------
- Better display of the impact analysis view: Allows to filter the classes of objects displayed in the
  graphical view. The view now resizes to fit your browser's window.

- Query phrase book: create, test and store your OQL queries in the "Query Phrasebook" for an easy export
  via the "Export" menu, without the limitation of the URL length in Excel web queries ! A new export format
  "spreadsheet" was aded, specifically for running as Excel web queries (dates are split between date and time)

- Enhanced data synchronization:
  - For performing huge synchronizations with little memory (despite PHP's memory leaks), the execution of the data
    synchronization (in CLI mode only) can be run by chunk of x elements by specifying --max_chunk_size=x on the
    command line to synchro_import.php or synchro_exec.php. Try to set this value to 1000 to run with 128 MB of memory.
  - When an (optional) external key cannot be reconciled, log a warning on the replica. the replicas containing a
    warning are then processed everytime in case the ext key changes.
  - Also improved the search/display of replicas for an easier troubleshooting of the synchronization.

Localization
------------
This version contains some enhancements to the German and Brazilian translations thanks to David Gümbel and Marco Túlio

More information on the localization (completion progress, how to contribute) here:
http://www.combodo.com/itop-localization/

Minor changes
-------------
Automatic synchro of CAS/LDAP users: it is possible to have iTop automatically create the user record when an authorized user connects through CAS
 - Use the default language when creating a new user from CAS
 - Support patterns for casMemberof

Audit:
Better error handling in case of OQL error in the audit page, now the error is properly trapped and indicates which query is the cause of the error.
Optimized memory usage when auditing large volumes of CIs (10'000 items was requiring 200 Mb, it now runs with 32 Mb -including the 30Mb overhead!)

Added a link to a favicon (icon in the browser's bar and tab)

Allow a module to restrict the access to a given menu/group by redeclaring the menu with restricted rights.
All rights are combined with the AND operator.

Added the "search form" on top of the list of user accounts, useful to find a user in a huge list !

Added the ability to display a custom welcome/disclaimer message at the bottom of the login form.
Just put a non empty string (can contain HTML tags) in the dictionary entry 'UI:Login:About'

In the Toolkit: Improved the check on data model consistency: detection of SQL columns used by two attributes

Ticket's case log can now be bigger than 64 Kilobytes...


Bugs fixed
----------
The complete list of active tickets can be reviewed at http://sourceforge.net/apps/trac/itop/report/1

#522 issue with non-ASCII characters in notifications subject.
#519 Change password bug: it was not possible to for a user to change their own passord, if the (new) password contained non-alphanumeric characters !
#518 Properly pass the context (i.e. currently selected org) to the auto-refresh lists
#516 and #517 Improved the export (specify fields for multi-column queries) and web queries (default field list)
#512 Command line mode (CLI) is now supported for the 'export' page. With either the --auth_user and --auth_pwd parameters or --param_file
#494 It seems that PHPSoap does not understand the <wsdl:documentation> tag, let's put them as comments
#493 Incorrect display of Users' Grant Matrix
#487 Resizable text areas disappeared when located on the second tab !
#486 Fixed SQL dashboards limitations
#485 Export.php improved for integration into Excel / web queries (bug with IIS/HTTPS, limitation on the size of the OQL)
     Export for MS Excel web queries: format=spreadsheet Improved the end-user experience with Excel and the web queries (added a phrasebook)
     + link to test the OQL attributes (query phrasebook or email actions, etc.) including the handlink of query arguments)
     + fixed wrong prototypes for a few implementations of GetBareProperties()
#484 Fixed issue with IIS ("Wrong password" at first prompt)
#482 OpenSearch (integration with your browser's search bar) was broken.
#482 The setting 'min_autocomplete_chars' was not taken into account
#481 localized characters in Service / Service Category name and description were not properly displayed.
#480 The 'min_autocomplete_chars' settings was not taken into account.
#478 Fixed issue in the audit: the results are wrong whenever an organization is selected
#477 Could not specify more than one reconciliation key (regression) + took the opportunity to enhance protection against XSS injection (using column names in the data)
#473 Could not load NW interfaces (reconciliation issue) - merged from trunk

Other bugs not listed in Trac:
Security issue: the attachments were visible by anybody (by forming URLs manually), whatever the allowed organizations. The change requires the execution of the setup/migration procedure.
Apply the AllowedValues constraints(as default values)  when selecting elements via the "magnifier" button or creating an new element via the "plus" button.
Paginated lists were broken in the Impact Analysis "List" tab
Incorrectly appending a parameter ?version= to linked scripts already containing a parameter in their URL, also changed the parameter name to 'itopversion' to avoid collisions
Always apply the AllowedValues constraints(as default values)  when selecting elements via the "magnifier" button or creating an new element via the "plus" button... also make sure that allowed values is enforced
When searching objects to add to the current object (when managing n:n relationships), set the default search params in order to stay in the current silo.
Fixed issue: nobody in the list of persons to notify for portal users (security takes precedence)
In the setup: increased Suhosin minimum value for get_max_value to 2048 due to a bug seen on some installations
Fix to have the proper use of GetEditValue... thanks to C. Naud
SQL Block with parameters were always displayed as table, whatever their type...
Removed a (useless) hardcoded reference to FunctionalCI that may break inthe display of the Impact Analysis
(Tried to) improve the display of the Synchronization Tooltip that "sometimes" does not work on IE 8...
Put some default reconciliation keys on Actions and Triggers to ease the use of CSV import
Protect against an empty list of reconciliation keys in the interactive CSV Import
Export for spreadsheets: transform keys (id of the queried object or external keys) into the corresponding friendly name

3.2. Known limitations (https://sourceforge.net/apps/trac/itop/report/3)
     -----------------
#71   The same MySQL credentials are used during the setup and for running the application.
#265  Add reconciliations keys into CSV template

Suhosin can interfere with iTop. More information can be found here: https://sourceforge.net/apps/mediawiki/itop/index.php?title=ITop_and_Suhosin
Internet Explorer 6 is not supported (neither IE7 nor IE8 in compatibility mode)
Tested with IE8 and IE9, Firefox 3.6 up to Firefox 8 and Chrome. Be aware that there are certain limitations when using IE8 in "security mode" (when running IE on a Windows 2008 Server for example)


3.3. Known issues (https://sourceforge.net/apps/trac/itop/report/3)
     ------------
#259	Not instantaneously logged off when the administrator deletes a user account
#175	When moving backward in the CSV import wizard, some settings may be reset (e.g column mapping)
#174	CSV import not displaying the labels of enums
#273	The administrator can delete his/her own user account
#372	APC Cache not efficient (multi org usage, global search)
#382	Search form / base class lost after a search
#377	Case log: exclude the index from the views
#388	IE9: edition fields not resizable
#443 	Objects remain in the database after de-installing some modules
#442 	Useless profiles installed
#441 	/doc redirects to Apache documentation!
#439 	Display the modifications to a linkedset in the main object's history
#438 	The selection of Organizations using the hierarchy does not work on IE8 in security mode
#436 	Cannot type "All Organizations" 	
#398 	Import CSV: Unchanged attributes marked as "modified"
#381 	Deletion of dependencies could fail in a multi-org environment
#241 	"status" is a free-text field when configuring a Trigger
#358 	Multi-column queries sometimes returning an empty set
#383 	OQL: negative integers not allowed
#399 	Copy/paste from iTop's CaseLog looses tabs
#343 	CKEditor (HTML Editor) not compatible with direct object creation on ExtKeys
#350 	Object edition form: validation does not tell which field has a problem
#317 	Edition of a Document - opens the second tab
