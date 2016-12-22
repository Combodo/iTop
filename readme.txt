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
Thank you for downloading the 27th packaged release of iTop.
This maintenance release fixes regressions and functions which were supposed to be part of the 2.3.x feature set.
Most of the regressions are related to the introduction of HTML formatted Case Logs and the Enhanced Portal.

The documentation about iTop is available as a Wiki at: https://wiki.openitop.org/

iTop is released under the AGPL (v3) license. (Check license.txt in this directory).
The source code of iTop can be found on SourceForge: https://sourceforge.net/p/itop/code/

1.1 What's new?
    -----------

Changes since iTop 2.3.1:

  Enhanced Portal
  ---------------
- Align behavior to legacy Portal: the enhanced portal now works fine for users having "Allowed Organizations" defined in their user account.
- #1299 "Oops, could not load data" when creating request in Full ITIL instance when running PHP7, has been fixed.
- Added possibility to specify a controller action for a brick tile. This allows to use some logic in order to make a specific render relying for example on DB dataobjects.
- Activate multi-objects sorting based on DataModel default ordering in BrowseBrick. For example the Services catalog which is made of 3 objects: ServiceFamily/Service/ServiceSubcategory, used to be ordered only on Service Family is now sorted on Service Family, then on Service and last on Service Sub-category.
- Fix bug in edition form with multiple LinkedSets.
- Templates not working with OQL "list" fields (requires the Request Templates extension). This only happened when the field had too many items and was trying to render them as an autocomplete.
- Support display of HTML fields in lists in the new portal.
- Fix Deadline attributes which were not displayed properly in ManageBrick.
- Optimized column load in ManageBrick and BrowseBrick to improve performance.
- Fixed a regression which caused some characters (like < >) to be displayed as their corresponding HTML entities (&gt;)
- Fixed the quick search on enumerated values and finalclass field. The search was performed against the "code" instead of the displayed (localized) value.
- Fixed the display of enums and html images in lists.
- Fixed the display of friendlyname in lists, which was not behaving well on abstract class when the name was composed of several fields in the child classes.
- Fixed the list of resolved tickets for power users: the list was restricted to their own tickets.
- A read-only AttributeDuration in the portal ticket edit form was preventing attachment on that form. It’s been fixed. AttributeDuration field are still read-only in the portal.
- AttributeBlob was not working in the portal. It is now available but in read-only mode only.
- #1281: Fixed a few hardcoded strings to dictionary: Service catalog brick had 2 hardcoded headers ("Service" and "Sous-Service")
- The Spanish translation of the new Portal has been added.
- The German translation has been improved.
- When a Ticket is opened in a new tab, the caselog entry was not emptied after submission, leading to frequent duplicate entries in the Public log, if user was submitting again. This is fixed.
- Fix display of Wiki text which was pointing to the console object, instead of the portal one.
- #1284: Fixed issue when trying to re-open a ticket as a portal user. Cause was that the destination state had "must prompt" attributes that were all "read only" for the current user, making the entire form "read only" and therefore removing "submit" button. The user was the not able to complete the transition. Fix consists of skipping the form when all attributes are "read only" for the user.
- Refactored a portion of TWIG (Loader is now in an helper TWIG)
- Placed transition buttons to the right with the 'submit' one as it was confusing.
- Fixed a bug on the default configuration that was displaying only UserRequest in the Closed requests brick instead of both UserRequest and Incident objects.
- Fixed a bug with external key as radio button in forms

  Support of Internet Explorer 9 in the new portal
  ------------------------------------------------
- Removed console.log to prevent a javascript error on IE9 which was stopiing the processing
- Cosmetic adjustments for IE 9: zoom-in/zoom-out cursors do not exist in IE9: use the hand cursor instead
- Fix Autocomplete bug with IE9 in forms.
- Remove in IE9 the placeholder "Type your text here" in the Public log of a Ticket as it was wrongly logged as a real user entry
- Fix for the upload of attachments with IE9.

  Legacy Portal
  -------------
- Uploading an inline image in the Case Log was not working, image was loaded but not permanently stored, it’s been fixed.
- Uploading an inline image in an HTML text has been enabled.

  Embedded HTML editor
  --------------------
- #1321 Table formatting (border, cellpadding, width) was lost when editing a table inside the HTML editor
- Fix regression introduced by HTML sanitizer, which was preventing 'ftp' and 'file' protocols in <a href= > tag, thanks to configuration parameter: 'url_validation_pattern'
- Fixed CKEditor which was missing in the Console: Text justification, Fonts and Size selection.
- The maximize icon for the rich text editor was not showing when iTop was installed at a location which path contained a space.

  Console
  -------
- Cosmetics: Enlarge DateTime fields which were too narrow (the end of the time was not visible when editing).
- Creation of a Ticket in a status different than the default initial value was not working very well.
- Case log copy from a Parent ticket to its child tickets was not handling properly the HTML formatting.
- The creation of an object B from an object A edition form (using the + icon), was failing if object B was having a mandatory HTML field, as the data in the HTML field was ignored/dropped.
- Massive modification of objects having an HTML field, followed by at least one required field was failing.
- #1305 Issue with date/time inputs on Chrome: losing focus as soon as the date has been correctly typed, preventing the user from typing the time.

  Core
  ----
- CSV import failing with final class (localized value not taken into account)
- #1279: CSV export of audit results: pass the parameters as a POST since they may be too long to fit in the query string of the URL.
- #1297: timezone configuration setting was inoperant (regression from iTop 2.2.x).
- Resize on AttributeImage used to crash when "gd" extension was not installed. Now it just does not resize.
- Added protection against time differences between the MySQL server and the PHP server, when running 'synchro_import.php'
- Optimization of database queries which was impacting Portal performance. We found one case where the query execution was never ending and takes now less than a second.
- Fix corner case situation where UNIONS and INTERSECTIONS were not handled correctly.
- Fix: a character "à" in a case log was causing the REST/JSON API to fail if mbstring was not enabled.
- Fix the pollution of "error.log" with the contents of each email sent (transport = PHPMail)
- Within some customized DataModel where users could create Ticket directly in Resolved state, if the date format was not the default, it was displaying a Fatal Error.
- Backup on Sundays was not working due to wrong query.
- Properly integrate .htaccess files (in /data and in /log) into the iTop zip package (those files were ignored by the build process)
- Fix one OQL query in DataModel which could become ambiguous after tricky Datamodel customization.
- Setup enhancement: protect the method RenameValueInDB() from non-existent attributes.
- When editing an object in the console, external fields (i.e. fields depending on an external key) were not automatically refreshed when changing the value of the external key (regression from iTop 2.2.x)
- Security fixes to prevent XSS injections in the page setup/email.test.php


1.2 Should I upgrade to 2.3.3?
    --------------------------
Yes, if you are running iTop 2.3.1 we recommend you to upgrade. This version fixes quite a number of bugs from the previous version and is suitable for running in production.


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
The version 2.3.3 if fully compatible with 2.0.0, 2.0.1, 2.0.2, 2.0.3, 2.1.0, 2.2.0, 2.2.1 and 2.3.1.
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