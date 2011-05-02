iTop - version 1.1.0 - 04-05-2011
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
Thank you for downloading the ninth packaged release of iTop.
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
- Data synchronization. As a consultant, use advanced tools to integrate tightly iTop
  with an external application. Documentation is available on the brand new iTop wiki:
  https://sourceforge.net/apps/mediawiki/itop/index.php?title=Data_Synchronization
- Modify all utility: select a set of objects and force a value on all of them, or close a set of tickets in a few steps.
- Tickets: the form have been redesigned with some focus on the case log.
- iTop takes advantage of the APC cache.
- Italian and Hungarian localizations are now part of iTop



1.2 Should I upgrade to 1.1.0?
    ---------------------------
You are manipulating huge data sets, and need to perform bulk changes but you are still not familiar with the powerful CSV import feature.
You are using the helpdesk modules (Incident and User Request Management), and want to improve the productivity of agents.
You need to integrate with an inventory tool. iTop 1.1 comes with synchronization tools.
You are running iTop on a slow system: iTop now takes advantage of the APC cache. The response time could be divided by 5!

If any of the above items is important for you, then you should upgrade your version of iTop.


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

2.4. Migrating from 1.0, 1.0.1 or 1.0.2
     ----------------------------------
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

3.1. Changes since 1.0.2
     -------------------

Version 1.1.0 brings a few major changes.

Major changes
-------------
Ticket forms: simple changes for a better productivity:
- The case log is no more a simple text area: it is now a real log, making it more readable in case of very long discussions.
- Enhanced layout: attributes are grouped, and the case log can take 100% of the width of the window.

Bulk modify and bulk actions on tickets
A new wizard to update a given set of objects. E.g.: close a set of incident tickets, or move a set of Servers from a location to another.

Synchronization with external application: an integrated utility to help in developing an integration between iTop and an external (master database). E.g.: integration with inventory tools like OCS-NG.


Localization
------------
Added Italian translation, thanks to Marialaura Colantoni.
English dictionary completion.
Improvements to the French translation.

More information on the localization (completion progress, how to contribute) here:
http://www.combodo.com/itop-localization/


Minor changes
-------------
#347 Let customers update the tickets they have submitted via the portal
#149 Friendly names: objects such as persons/DB instance/interfaces have a name made of several attributes and formatted in the dictionary
Plugin API - alpha version - the basis for extending iTop seamlessly... to bo continued...
#365 Give the user some feedback when the password was successfully changed/set. Note that iTop does not check that the new password is different from the old one.
OQL: IS_NULL() and REGEXP have been added.
Adjusted the default ITIL profiles definitions
Revised styles for a nicer/cleaner display of the details and forms.
Changed the default character collation to be consistent with the DB definition.
#362 New capability for attributes derived from AttributeString: specify a validation_pattern (regexp)
#328 Added the capability to import/export link sets in CSV format
Search forms enhancement: when a search criteria is an external (foreign) key, use an autocomplete instead of of drop-down list, when the number of different values is too big, as in other forms.
#355 CSV Import (non interactive) now supporting localized column headers, making it possible to import directly data generated by the interactive export. NOTE: to achieve this, the default separator is now the coma (whereas the default separator in XCel sheets is the semicolumn)
#271 Internal - Removed a workaround made unnecessary with fix [1108]
#156 make sure the hierarchical ZLists are supported everywhere.
#344 default search behavior for enumerated attributes (and similar types: 'Class', 'Language' and 'FinalClass') is now a strict '=' instead of 'contains'.
#352 Web Service CreateTicket: Search the service subcategory given the found service_id (if not already specified)
Automatic deletion of links lnkSolutionToCI
Keep track of the application's usage: an entry in the log is added each time a user connects to the application. (This feature is disabled by default)
Check SLA for tickets moved to a new page: cron.php. See dedicated section.
Email sent in asynchronous mode (relying on cron.php - see dedicated section)
Aligned the display of the case log (and properties) of a ticket in the User Portal to what is done in the normal UI: better look (multi-column, fieldsets, wider case log at the bottom).
New option group_by_expr for "group_by' display blocks (to be used in templates) to specify a PHP expression to use for the group by. This allows to build dashboards where dates are grouped by the day of the month, for example.
#370 standard argument for CLI/REST services: param_file

Wiki formatting. The attributes of type 'AttributeText' or derived, allow some special formatting:
- links to objects: [[Server:db1.my-company.com]] will be seen as a Url to the server named 'db1.my-company.com' in iTop.
- url are detected and displayed as hyperlinks

Optimizations:
- Delayed startup for all non-important javascript effects to speed-up the display of the pages.
- Implementation of the APC cache. Settings: apc_cache.query_ttl (defaults to 3600s) and apc_cache.enabled = true by default

New implementation of the setup:
- support of upgrade or reinstallation
- optimizations (3 times faster)
- dictionary files (<Lang>.dict.<ModuleName>.php) loaded automatically without the need to specify them explicitely in the module definition file.


Bugs fixed
----------
The complete list of active tickets can be reviewed at http://sourceforge.net/apps/trac/itop/report/1

#375: Display scroll bars appropriately when dealing with big CSV load jobs.
Protect against javascript js files being kept in the browser's cache when upgrading an iTop instance.
Deletion: the message "object deleted" was displayed twice since the last review of the deletion
Cosmetic: changed error message when dependencies cannot be solved
#366 Global search case sensitive or not working at all (issue with COLLATION)
#360 Wrong COUNT when using JOINS (+ other conditions)
#340 Fixed OQL parsing issue - parameters in IN()/NOT IN() clauses
#313 Provider contracts are filtered on the 'provider_id' - for filtering in the UI via the drop-down list of Organizations and for the security profiles ("Allowed Organizations").  The mapping for 'org_id', if any, is now taken into account by the security.
#336 verification of the directory of the temporary config file was wrong... however the script still assumes that the temporary config file and the final one are stored in the same place... at the root of the iTop installation.
#348 Multi-column queries not working fine with open joins and if null values to be displayed
#356 Audit results filtered by context
#357 Audit results list not expandable
#353 no menu for DBServerInstance objects.
#305 Specified the charset in any call to htmlentities()
In read-only mode, stimulus must not be allowed
Display of relationships: Removed a "assertion failed" error message, and fixed an incorrect detection of the maximum recursion level
#351 undefined variable sClass...
CSV import web service - cosmetics on the reporting in case the data set is empty
#388 Browser compatibility: Fixed issues with IE9 (Removed the 'bgiframe' javascript , which was designed for fixing IE6 issues only and causes troubles with IE9.)
#385 Issue with auto-complete in search forms
#394 Web service in CLI mode failing with error "could not find approot.inc.php"
#395 Web service in CLI mode failing with no message
#301 Logoff/Logon from the portal: removed the forced redirection to the portal -this was very confusing when logging again as administrator 

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
