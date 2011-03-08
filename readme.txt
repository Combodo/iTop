iTop - version 1.0.2 - 19-01-2011
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
Thank you for downloading the eigth packaged release of iTop. This version is
a maintenance release. It aims at upgrading seamlessly an existing 1.0 or 1.0.1 installation.

Additional documentation can be downloaded from http://www.combodo.com/itopdocumentation
 - User guide
 - Administrator guide
 - Customization guide
 - Implementation guide

iTop is released under the GPL (v3) license. (Check license.txt in this directory).
The source code of iTop can be found on SourceForge: http://itop.sourceforge.net

1.1 What's new?
    ---------------------------
- Three new localizations were added: Chinese, Russian and Turkish
- User Interface enhancements: quick search and create within a form when dealing with external keys
- Improved support of IE8: a few cosmetic enhancements
- Enhanced CSV import (with a special CSV "synchro" mode, confirmation dialogs for "risky operations",
  better history tracking of imports) and command line support for importing objects


1.2 Should I upgrade to 1.0.2?
    ---------------------------
This maintenance release fixes brings the following improvements:
- Added localization for Turkish, Russian and Chinese
- Fixed some usability issues with Internet Explorer
- Improved the usability of the CSV import feature, both in interactive and command-line modes

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
Schlobinux for the fix of the setup temporary file verification.

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
tested mostly Firefox 3, IE8, Safari 5 and Chrome. iTop was designed for at least a
1024x768 screen resolution. For the graphical view of the impact analysis, Flash
version 8 or higher is required.

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

2.3. Migrating from 1.0 or 1.0.1
     ---------------------------
Overwrite your current installation files with the new ones.
Configuration file, Data model files, and the database made by iTop 1.0 are
fully compatible with iTop 1.0.1.

2.4. Migrating from 0.9
     ------------------
Depending on your current situation, there are several possible migration paths.
Please refer to the migration guide available at http://www.combodo.com/itopdocumentation.

3. FEATURES
   ========

3.1. Changes since 1.0.1
     -------------------

Version 1.0.2 is a maintenance release.

Localization
------------
Turkish, Russian and Chinese were added.
German localization reviewed.

Major changes
-------------
None: this is a maintenance release!
#320 Integrated an HTML Editor, though none of the fields of the standard iTop data model uses it!

Minor changes
-------------
Added the ability to attach files to a user request from the "portal" page.
Use XMLPage passthrough mode to speed up and consume less memory for big XML exports.
- Improved feedback while searching and reloading added objects. (N-N links)
REVIEWED THE FILE INCLUSION POLICY -> the application can be moved !!!
Read-only mode relying successively on a DB property, and an application setting
Improved change tracking: user login replaced by the full name if available

Improved implementation of the 'autocomplete' input and fix of quite a few related issue with aysnchronous inputs. Autocompletes are now restricted to external keys only.
Some details:
- Autocomplete now matches on 'contains' instead of 'begins with'
- The minimum size of this match is configurable in the config file and per attribute ('min_autocomplete_chars').
- The maximum size that turns a drop-down list into an autocomplete is configurable in the config-file and per attribute ('max_combo_length').
- Better feedback when expanding/collapsing search results lists.
- 'Pointer' cursor on the link to Expand/Collapse results lists.
- The 'mandatory' state of an attribute is no longer lost when some part of a form is reloaded asynchronously
- added the ability to create objects pointed by ExtKeys even when the edit mode is a drop-down list and not an autocomplete
- made this behavior configurable globally or per external key, using the config-flag/option: allow_target_creation.
Renamed 'autocompleteWidget' to 'extkeyWidget' since it's not always an autocomplete...
Make sure that the "+" (Create) button is never displayed for an abstract class.

Support resizable elements inside tabs.
Allow DBObjects to be deleted by the standard UI 'Delete', which may be useful in case a DBObject has to be deleted as a dependent object of a CMDBObject.
Force a dummy timezone to prevent a warning during the setup...
Cosmetic on the iTop logo (under IE8). Removed an unneeded size=100% that bothers IE.
XML data loader now requests credentials
The configuration file now contains "relative paths" only. This means that if you installed iTop 1.0.2, you can move the directory containing the iTop installation.

* iTop Customization

Added the capability to enable/disable menus based on the rights to apply a given stimulus.
Allow a module to provide a handler to override application settings: OnMetaModelStarted()
Menus created via a handler, at runtime
Patch for supporting a data model without any Organization.
Patch for supporting a data model without any Person.
The hyperlink to the online-help file is now configurable
Modularity: allow a module to execute some specific installation procedures (customize the config file, do something in the database)
User profiles: created in dedicated module itop-profiles-itil
Welcome page moved out the application, into a dedicated module: itop-welcome-itil
Moved the standards menus into the "welcome" module
Added the capability to enable/disable menus based on the rights to apply a given stimulus.
Fixed the processing of hierarchical ZLists to keep the display order when plain fields and fieldsets are mixed at the same level.
Added support for hierarchical ZLists when checking the data model consistency



* Browser compatibility

iTop was tested successfully ON FF 3.6, IE8, Chrome and Safari 5 (Windows).
Fixed the "Relationships" Flash navigator so that it works also on Safari. (tested with Safari 5.0.2 on Windows) (Trac #310)
- Fixed the search form, and also fixed the search/selection of objects to link (n:n links) that was broken on IE8.
- Fix to prevent IE 8 from running in IE7 compatibility mode
- Cosmetics: The login and change password forms now look the same on all browsers (FF, IE8, Safari 5, Chrome)

* CSV Import

- Added the new "synchro" mode to the CSV load page.
- Ask for confirmation when doing a CSV import/synchro that is considered as "risky" (based on thresholds from the config file)
- Added a "Restart" button to quickly start over a CSV import/synchro
Added a tab into the CSV import: browse the CSV imports history


Bugs fixed
----------
The complete list of active tickets can be reviewed at http://sourceforge.net/apps/trac/itop/report/1

#299 "Show all" should provide some feedback (progress)
#314 Set a longer timeout during setup
#318 (and #335) added the check of the mandatory DOM extension.
#321 Display PHP errors during setup instead of hiding them!
#331 Import.php could not be run in  HTTP mode (when PHP running in CGI mode)
#332 Improved usability of the CSV import wizard with IE8. 
#333 Organizations' drop-down list is truncated on IE when the name of an organization is too long.
#334 Proper handling of the "remove objects" button (was working only for the first linkset in the object).
#337 email validation. Use a simpler regular expression that is much faster to execute.
#338 Service Element not updated if service is autofilled
#339 Fixed a typo in German translation thanks to ulmerspatz and Jonathan Lucas
#345 Impossible to reassign to another workgroup
#346 CSV Import prompts to enter the mapping when pressing 'Restart'

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

