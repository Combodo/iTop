iTop - version 1.2.0 - 06-Sep-2011
Readme file

1.   ABOUT THIS RELEASE
2.   INSTALLATION
2.1. Requirements
2.2. Install procedure
2.3. Migration from previous version
3.   FEATURES
3.1. Changes since 1.1
3.2. Known limitations
3.3. Known issues

1. ABOUT THIS RELEASE
   ==================
Thank you for downloading the tenth packaged release of iTop.
This version comes with a few new features and bug fixes.

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
- Japanese localization is now part of iTop
- Paginated display: when a list contains lots of data it is displayed page per page
- Quite a few performance improvements to make iTop behave properly with huge data sets
- Hierarchical keys: parent/child relationships can now be described using a special type of key,
  and then queried efficiently in the database (Used by Organization, Location and Group)
- CAS authentication: iTop now supports single-sign-on with JA-SIG CAS

1.2 Should I upgrade to 1.2.0?
    ---------------------------
- If you are manipulating big sets of data (several thousands of objects in one go)
- If you care about organizations or locations hierarchy
- If you speak/read Japanese
- If you already use JA-SIG CAS (www.jasig.org/cas) for example with a Liferay portal

then you'll benefit from iTop 1.2 and it's probably worth upgrading.


1.3 Special Thanks To:
    -----------------
Bruno Bonfils for his guidance about LDAP and authentication.
Randall Badilla Castro for the Spanish translation.
Jonathan Lucas, Stephan Rosenke and David Gümbel from ITOMIG GmbH, for the German translation.
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
Antoine Coetsier for the CAS support and tests
Vincenzo Todisco for his contribution to the enhancement of the webservices

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

3.1. Changes since 1.1
     -------------------

Version 1.2.0 brings a few major changes.

Major changes
-------------
- Paginated display
- Management of hierarchy of objects: implemented for Organizations, Groups and Locations and taken into account by the profiles/user rights.
- CAS integration: added support of JA-SIG Central Authentication Service (CAS) with log-off support, using phpCAS API.

Localization
------------
The Japanese translation was added, thanks to Tadashi Kaneda.
The German translation was updated by Stephan Rosenke

More information on the localization (completion progress, how to contribute) here:
http://www.combodo.com/itop-localization/

Minor changes
-------------
Improved import.php and synchro_import.php: added 'date_format' (example: %d/%m/%Y %H:%i:%s)
When needed the drop-down list of organizations is replaced by an autocomplete
Templates: new type of block = sqlblock, allows for displaying tables/charts based in SQL queries (much quicker for some 'Group By' operations)
Added support of 'drill-down' (i.e on_click) on bar and pie charts
Added SQL blocks
New feature: online help on search inputs (date format and operators) a tooltip appears when the user clicks a date/search field
Better handling of object deletion issues during a data synchro...
#130: keywords to narrow the scope of the global search (e.g. server:webserver searches "webserver" only in the "server" objects)
Added a new web service to create UserRequest tickets (similarly to Incidents tickets). Based on code from Vincenzo Todisco.
Bug fix: when changing the currently selected organization, go back to the initial (Welcome) menu instead of trying to stay on the same menu... which caused troubles (e.g. "New Contact" => assertion failed)
Improved error handling when loading linkedset as attributes in one go in CSV import
Don't make the Ticket's case log hidden in the 'New' state, since it's not hidden in the portal !
Better error message if the configuration file exists but is not readable
In CLI mode, do not depend on the current directory for synchro/import.php and synchro/synchro.php: the scripts can now be run from anywhere.
New module to easily manage attachments in one click instead of creating a separate 'Document' object. If this module is installed, portal users will create attachments instead of linked documents when uploading files with their ticket
Added a new type of 'Trigger': TriggerOnPortalUpdate, called when the end-user updates a ticket via the portal.
The deadlines display format can be configured.
Lists can be displayed either as combo boxes (default and original behavior) or radio buttons, either horizontal or vertical.
In the templates or menus, the sort order can be specified.
Developers: DisplayBareProperties now called both in read and edition modes.
#446: XSS vulnerabilities
Security: protected bulk modify against HTTP/Post piracy
Added the display of the total count of objects in overviews.
Different display for 'Date' fields: shorter field than DateTime since there is no "time" part.
A mandatory case log field is now considered as 'filled' if it contains a previous entry
#148 Allow overloading attribute/enum labels in the dictionary
Warning (popup) message when navigating away from an edition form.
#452 Export.php - field list can be specified also for HTML output
German localization update, thanks to Stephan Rosenke.
Shortcut actions (parameter in the config file)
Notifications: case log in plain text (this->case_log) or the latest entry (this->head(case_log))
Allow creation of an ticket in a different initial state via the new 'initial_state_path' attribute.
Support update of CaseLog fields in bulk_modify mode.
Detection of the Suhosin extension during the installation and tell the user if the get_max_value is too small.
#284: Improved verification to the PHP file upload settings to avoid troubles later


Bugs fixed
----------
The complete list of active tickets can be reviewed at http://sourceforge.net/apps/trac/itop/report/1

#122 Optimized the load of data set (do not load unused columns, that can cause some tmp tables to get too big for memory)
#403 Partial installation not working (error on ticket form)
#404: context lost when doing certain actions. What was fixed:
  - Run Query
  - Display Data Model Schema
  - Drill-down in charts (OQL & SQL)
  - Paginated lists (actually a regression)
  What remains:
  - Global search...
  - Drill-down in Flash "impacts / depends on"
#405 Could not install without the module 'User Request Management'
#408 Case log not working with PHP < 5.3 - the fix preserves the compatibility with installed version (but the dates are lost)
#410 Added translation for ticket status (and other enum fields) when displaying the History tab.
#415 Could not limit user on some organization (symptom: wrong queries... org_id does not exist...)
#420 Data synchro logs: increased the size of the attribute last_error
#422 (detection of magic_quotes_runtime)
#423 Fixed issues with application root URL = f(mode CLI, modules, web server techno, etc.)
#427 Unable to remove all items from a linkset when editing an object.
#424 Error when updating the Data Synchro statistics
#429: web browser can crash when a text field contains several times the same URL !!!
#433: Database triggers creation was incorrect when iTop was installed with a 'prefix' for the DB tables.
Dashboard templates: fixed issue with asynchronous mode (still some cosmetic issues) with itopblock and the table format
n:n wizard, context was lost when searching for objects of a derived class to be added.
'Apply stimulus multiple" was saying: "Please select at least one object"
Make sure that the flash object respects the z-order otherwise the hierarchy/organization picker appears behind the Flash in Chrome and IE.
Fixed issues when adding/removing modules during the setup:
 - When adding modules: the data model was not refreshed in the cache before attempting to load "structure" (or "sample") data
 - When removing a module: remaining (invalid) triggers were still used.
A title was missing for the menu 'All Opened Changes' at the top of the page
Fixed the parsing of OQL error messages: should be able to report the line number (usually 1) and the character where the error happened
Don't display an error (assertion failed) if the user selects nothing (i.e -- select one --) in the "CSV template" tab.
Display/download links on documents that were both doing exactly the same thing
Fixed the display of 'Used IP Addresses' (i.e. Network interfaces) in the details of a Subnet object.
Fixed the computation of IPs in a subnet that failed (returned negative numbers) on some versions of PHP compiled in 32-bit.
Enhanced interface for complex SLA computations...
#447: interfaces not showing up on the details of a server when an org is selected: there were collisions in the internal query parameters names ! This is now fixed.
The default value for Date fields is different than for DateTime fields: no 'time' part at the end (use the attribute's own format)
#458: back button was asking to fill the mandatory fields !
Support the selection (via an autocomplete) in a list that contains duplicates
#457: crash when deleting two organizations.
Accented characters not displayed within autocomplete selection controls
Email test: under IIS it was not detecting Windows correctly, and the help message was therefore completely wrong.
Dictionary: English was proposed twice in the list of available languages!
#465: incorrect logic when resetting the 'ConnectedToInterface'
Upgrade: fails to recreate a view when is has become invalid (missing attribute)
#363 Charts not displaying with IE8 + IIS + HTTPS
#373 Error when deleting two network devices connected to each other
#258 Context automatically set when specifying an organization in a search form
#444 Sort order not visible / lost on refresh

Productivity Enhancements
-------------------------
Quicker path to create or modify objects: buttons directly accessible next to the Actions popup menu.
Limit the drop down menu to your favorite organizations: use the user preferences menu next to the Logoff button.
For tickets, create/modify and apply an action in one step; E.g. Create and assign in one click. 
Form validation buttons (Ok/Cancel) showed on top and bottom. Can be tuned (top, bottom or both).
The paginated presentation allows a quicker navigation for really large data sets.


Performance Enhancements
------------------------
Do not load the full set of items when it comes to displaying an autocomplete!
Displaying 1000 object would take real long if many organizations are loaded into iTop (querying all the orgs for each object)
Cache the Count of items in an object set
Autocomplete = do not load every object when determining the list of matches


3.2. Known limitations (https://sourceforge.net/apps/trac/itop/report/3)
     -----------------
#71   The same MySQL credentials are used during the setup and for running the application.
#265  Add reconciliations keys into CSV template

Suhosin can interfere with iTop. More information can be found here: https://sourceforge.net/apps/mediawiki/itop/index.php?title=ITop_and_Suhosin
Internet Explorer 6 is not supported (neither IE7 nor IE8 in compatibility mode)
Tested with IE8 and IE9. Be aware that there are certain limitations when using IE8 in "security mode" (when running IE on a Windows 2008 Server for example)


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