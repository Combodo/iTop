iTop - version 2.3.0 - 5-Jul-2016
Readme file

1.   ABOUT THIS RELEASE
2.   INSTALLATION
2.1. Requirements
2.2. Install procedure
2.3. CRON
2.4. Upgrading from 2.x.x
2.5. Migration from 1.x versions
3.   FEATURES
3.1. Changes since 2.2.1
3.2. Known limitations
3.3. Known issues

1. ABOUT THIS RELEASE
   ==================
Thank you for downloading the 25th packaged release of iTop.
This version is a major release, with quite a few bug fixes.

The documentation about iTop is available as a Wiki: https://wiki.openitop.org/

iTop is released under the AGPL (v3) license. (Check license.txt in this directory).
The source code of iTop can be found on SourceForge: https://sourceforge.net/p/itop/code/

1.1 What's new?
    ---------------------------
This is a major release.

It brings the following new features (details in chapter 3.1):
- Enhanced customer portal
- Navigation breadcrumb
- Rich text formatting
- Date and time formats


1.2 Should I upgrade to 2.3.0?
    --------------------------
This version is a beta quality version and, as such, is NOT suitable for running in production.


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
Lukáš Dvořák and Daniel Rokos for the Czech translation

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
The version 2.3.0 if fully compatible with 2.0.0, 2.0.1, 2.0.2, 2.0.3, 2.1.0, 2.2.0 and 2.2.1.
Due to few database changes and new modules/files that have to be installed, you
must run the setup when upgrading (whatever the original version).

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

3.1. New features
     ------------

1) Enhanced customer portal
Completely new and responsive user interface: support of mobile phone, tablets, etc.
Highly customizable via XML
FAQs integrated by default 

2) Navigation breadcrumb
Based on Most Recently visited pages
New shorcuts buttons when the navigation menu is hidden 

3) Rich text fields
Case log and ticket description can now be formatted
Fullscreen edition
Copy/Paste and Drag-and-Drop of images

4) Date and time format
Configurable per language (new setting date_and_time_format in the configuration file)
Custom formats are supported for import/export  
For backward compatibility, the default setting is the MySQL format


3.2. Changes since 2.2.1
     -------------------

1) Browser compatibility
IE8 is not supported anymore: the minimum version for Internet Explorer is 9
No need for Flash players anymore


2) Data model (2.x)
Added attribute Ticket::operational_status: depending on the status of the ticket, this attribute will take on of the following values: ongoing, resolved or closed
Added Person/picture: optionally add the picture and visualize it in the details or in the enhanced portal
User Request (all-in-one): the end-user can leave the request type undefined, in such a case, she can select any type of services and the request type gets computed when the requests is written to the DB. Still, this is possible to select a request type and the list of services is filled with the corresponding services. This behavior was necessary for the new user portal to work fine.
Tickets description and case logs are now in HTML
New field on the User class to enable/disable user accounts (this attribute is R/O in demo mode).


3) Data corruption
#1213 Losing SLA data when changing any attribute of an SLA.


4) Security
#1202: Fix for a security vulnerability in the Configuration Editor.
Fix for potential XSS vulnerability on uploaded file names.
XSS: Correctly escape the name of an object when it is displayed within an hyperlink
#1206: "Forgotten password" - the temporary token could be hacked by the mean of a hand-made HTTP request
#1162 .htaccess and web.config files to prevent users from accessing the contents of data/log directories (support of apache 2.4)
Prevent grouping on password fields since it may lead to disclosure of the encrypted version of the password.
Properly sanitize the "switch_env" parameter and take it into account only if it contains a valid value.


4) Customizations (via XML deltas)
Switching to XML version 1.3.
- new attribute MetaEnum
- new attribute AttributeCustomFields (experimental!)
- new attribute AttributeImage (experimental!)
- new flag _delta="if_exists". Use this flag to ignore a branch if the corresponding node does not exist in the data model being hacked. This is to reduce the burden of developping separate modules depending on the installation options.
- new flag to open/collapse the search form at the top of a page in an OQLMenuNode: search_form_open
ResetStopWatch could not be used as a lifecycle action: the symptom is "The action has failed".
Label of the final class attribute could only be defined on the root class (overriding it in derived classes had no effect)
Improved the error reporting when assembling data model XML files (full path and line number of the faulty node)
A module can have its own design defined in XML (/itop_design/modules_designs/module_design) and accessed at run time via the class ModuleDesign.
The images specified in the branding or in module_designs can be given as a fileref or a path relative to the env-production directory
#1188 Allow to define a new constant or a brand new class as part of a delta that is not in a module
#1223 Custom lifecycle actions: improved the reporting when an action returns false (class/function/id logged into error.log)+ the framework now considers that no return value is equivalent to 'true'

5) Module development (PHP API)
No need for bridge (auto-select) modules to be listed as installed modules in the about box. Still, they are listed in the "support information".
Improved the module ordering algorithm. If a module has several dependencies (inclusive OR), it must be installed after each and every of its dependency that has been selected for installation.
Support for objects to go "out of the silo" during a transition by making sure that we can reload an object we've just saved.
If you have developped specific pages, and want them to appear in the breadcrumb, call iTopWebPage::AddBreadCrumbEntry.
Added verbs to the User Rights management API:
- HasProfile
- ListProfiles
- GetAllowedPortals
Added a mean to cache data that will be reset upon compilation. To be used in conjunction with ModuleDesign.
It is possible to implement several portals and still use placeholders to point to the relevant portal (use DBObject::RegisterURLMakerClass(<my-portal>, <mu-url-maker>), then $this-hyperlink(<my-portal>)$)
Context tags to identify the context of the execution. Usage: ContextTag::Check('Portal:itop-portal'). Known tags: 'GUI:console', 'GUI:Portal', 'Portal:itop-portal', 'CRON'... see ContextTag::GetStack()


6) Queries (OQL)
Magic query arguments:
- In addition to current_contact_id, the following arguments can be used in any OQL query (provided that the page running the query requires a  login): current_contact->attcode and current_user->attcode
- The "Run queries" page is now taking into account those magic arguments (do not prompt the end-user with these arguments!)
Hierarchies can now be expressed both ways. Example of a query that now works fine: SELECT Organization AS root JOIN Organization AS child ON child.parent_id BELOW root.id WHERE child.name LIKE 'Combodo'. In the previous implementation, the operator was interpreted as '='.


7) Optimizations
Do not load all columns when checking if a CI is part of  the "context" of a given ticket.
Optimization/bug (!): Never use the whole object as a placeholder in ApplyParams !!
Cleanup and optimization of the handling/loading of the dictionary files.
Optimization: load "pdftage" (and thus tcpdf) only when needed.
Adding an extra index to speed-up data synchronization for large volumes of data.
Improved the User Rights management API:
Doing less queries for user rights: caching the user profiles into the SESSION cookie


8) Data synchro
Enhanced display/edition of the "Reconciliation Key" column when defining the reconciliation using the attributes.
Prevent timeouts, since the synchro may be launched from the web (as a "web service", especially by the "collectors").
#1253 Properly parse dates in synchro import. Thanks to Karl aka karkoff1212 for reporting the issue.


9) Other fixes
#1210 Dependant field not reset (servicesubcategory not reset when service is reset)
Modified the "List" tab of the Impact Analysis to display only the actually impacted objects. The content of this tab is now refreshed every time the graph is rebuilt to take into account the "context" changes which causes the actual impact to change, or the filtering.
Initial feedback while loading the 'list' tab of the impact analysis, useful when this tab is displayed first.
Fixed a typo in German translation files ("Deails für Benutzeranfrage" => "Details für Benutzeranfrage")
When a date/time format is specified, don't try to process columns named 'id' since obviously these are neither date/times nor a genuine attribute code.
#1209 Setup or Backup failing with french error message 'Effacement du fichier ...' Regression introduced in iTop 2.2.1. Occurs when a backup fails and prevents users from seeing the mysql error report.
Attachments : Delete button's label of an attachment was hard-coded. Putted dictionnary entry instead.
Wiki syntax: allow white spaces in the specification of a link to an object (form: [[<class>:<friendlyname>]])
#1215: URL fields can now store up to 2048 characters
#1214: concurrent access lock not properly released when CheckToWrite() reports an error during a transition from one state to another.
Styles fine tuning and nicer display of the main menu (no more animation on initial load).
Suppress "Notice" messages when iconv detects invalid UTF-8 characters, since it breaks the JSON output if display_errors in On...
#1167 Error while upgrading db model from v 2.1 to 2.2 with orphan attachments.
File or image upload is not supported (and thus disabled) when using the [+] button to create a new object inside a popup dialog.
#1169 Broken link to iTop Wiki in itop-tickets.htm
Impact analysis display: cosmetics on tooltips: widen a bit the tooltips and prevent the text from overflowing horizontally.
CSV Imports:
- Make sure that the CSV Parser has enough time to run on big amount of data.
- Speedup the display of the CSV Import interactive wizard by parsing only the needed lines of the CSV data (in the first steps of the wizard).
#1199 Properly handle the icon of attachments without any extension.
#1205 Positioning of dropdown list of "Popup Menus" on Chrome (and IE 11) when the content has been scrolled
#1233 Spanish translation: InterfaCe + Solución Aplicativa
#1251 Disabling log notification in config causes a fatal error
Export: cannot export an object with a property named "length"
"Search Drawer" is closed by default, unless the configuration parameter "legacy_search_drawer" is set to "true".


9) Setup
Setup: Automatically remove duplicated modules (by keeping only the most recent one) when loading modules, independently of the loading order.
Setup: Make sure that the setup can be launched even if the 'php-zip' module is not installed.
#1252 Setup: make the project compatible with Ansible deployment (the file "database exi.png" was in fact not used at all!)
#1254 Setup: iTop 2.3.0 requires PHP 5.3.6 (HTML sanitizer using the API DOMDocument::saveHTML with an argument)


10) Internal
Exclude magic parameters when listing query parameters (refactoring from run_query) This enables the use of magic parameters in the exports. The issue was less exposed in iTop 2.2.0 because only one single magic parameter was available.
DBSearch : Allow join between DBUnionSearch by adding the DBUnionSearch::Join verb
#1221 Exclude git folder from the copied folders, during the compilation process
Fixed typo in the reporting of page spurious chars
Installation
- Better handling of  'auto_select' modules
- New way of implementing the "includes" of modules, now completely out of the configuration file !
Implemented DBObject::ExecActions, enables scripting object preset/modifications
Added verb ormCaseLog::GetAsArray()
Query arguments: when the value of a query argument is null, it must be considered as being a valid argument (was reported as missing). Improved the error reporting when the argument is in the form :this->attcode and the attcode is not valid for the class of 'this'.
Query arguments could be array values, making it easier to build dynamic IN() clauses
When uploading documents, get the mimetype from the file itself (if feasible) rather than relying on the mimetype of the HTTP header. This was already implemented but it was buggy and fell anytime into the fallback method.
Make the login page more mobile friendly.
Add the "filter" attribute into the details form of the TriggerOnThresholdReached class.
Prevent infinite cross-ticket recursion when propagating parent->child resolution in tickets.
The result of CheckToWrite() was not taken into account (action failed silently) when creating an object using the [+] button inside a form.
Programmatically allow to write on any object - if needed - independently of the profiles.
PHP warning issued when the CSS is rebuilt (SASS lib)
Core API: added DBSearch:SetSelectedClasses
#1173 Error during setup on a development system (XML containing unwanted text)
Core : Added CloneWithAlias function to DBSearch class. It creates a new DBObjectSearch from a DBSearch with a new alias.
Compiler: Model alterations not flattened prior to compilation (when using the setup UI)
Model Factory: factorized duplicate code from ApplyChanges + fixed an issue in the error reporting
Fixed the verb DBObjectSearch::IsAny
Read-only fields are no longer stored in the form as hidden fields.
Code refactoring: fix of #876 implemented in 2.0.3 as [r3161], moved to a place where it will fix other implementations of the setup
Limitation: DBSearch::Intersect to throw an exception whenever any of the merged queries have a queried class that does not correspond to the first joined class. This is a limitation of the current implementation of Intersect. Allowing such use cases would require quite a rework of that API.
Replacing the SCSS->CSS conversion library by a newer one made by Leaf Corcoran: http://leafo.github.io/scssphp, tweaked to work on PHP 5.3
Extending action classes (notifications): objects listed twice (in the base classes and leaf classes) in the notification page (actions tab).
Email generation - No need to force "Content-Transfer-Encoding: 8bit". The default is "quoted-printable" and works fine if the content is made of plain text. Leaving the 8bit encoding could work but in such a case, the statement should be:
$oEncoder = new Swift_Mime_ContentEncoder_PlainContentEncoder('8bit', true /*canonicalize*/);... otherwise the lines get truncated at random places (CRLF is assumed while PHP EOL is made of CR only!) -This has an impact on plain text email only.
#1235 DBObject API - external fields not up to date after changing the external key (though they seem to be in sync when inspecting the internal values, Get() does not return the expected value).
Demo mode: to not allow deleting neither changing the org of persons attached to a user account (this to make sure that the portal users will still have access to the customer portal)



3.2. Known limitations (https://sourceforge.net/apps/trac/itop/report/3)
     -----------------
#71   The same MySQL credentials are used during the setup and for running the application.

Suhosin can interfere with iTop. More information can be found here: http://www.combodo.com/wiki/doku.php?id=admin:suhosin
Internet Explorer 6 is not supported (neither IE7 nor IE8 in compatibility mode)
Tested with IE9, Firefox 3.6 up to Firefox 24 and Chrome. Be aware that there are certain limitations when using IE8 in "security mode" (when running IE on a Windows 2008 Server for example)


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
#1145   Two connections between a connectable CI and a network device must have different ports
#1146   History not reflecting a modification of the connection between a connectable CI and a network device
#1147   Identical links not always modified as expected
