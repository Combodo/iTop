iTop - version 1.0.? - ??-??-201?
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
a maintenance release. It aims at upgrading seemlessly an existing 1.0 installation.

Additional documentation can be downloaded from http://www.combodo.com/itopdocumentation
 - User guide
 - Administrator guide
 - Customization guide
 - Implementation guide

iTop is released under the GPL (v3) license. (Check license.txt in this directory).
The source code of iTop can be found on SourceForge: http://itop.sourceforge.net

1.1 Should I upgrade to 1.0.1 ?
    ---------------------------
This maintenance release fixes a number of usability issues of iTop 1.0:
- Better handling of forms: fields validation and default values handling have been improved
- Support of IE8 and Safari
- Support of IIS
- Support of localized texts in the User Portal

If any of the above items is important for you, then you should upgrade your version of iTop.
If you are Brazilian and want to run iTop on IIS with IE8, then this release is for you !


1.2 Special Thanks To:
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

2.3. Migrating from 1.0
     ------------------
Overwrite your current installation files with the new ones.
Configuration file, Data model files, and the database made by iTop 1.0 are
fully compatible with iTop 1.0.1.

2.4. Migrating from 0.9
     ------------------
Depending on your current situation, there are several possible migration paths.
Please refer to the migration guide available at http://www.combodo.com/itopdocumentation.

3. FEATURES
   ========

3.1. Changes since 1.0
     -----------------

Version 1.0.1 is a maintenance release.

Localization
------------
Portuguese (Brazil) has been added.
German localization reviewed.

Major changes
-------------
None: this is a maintenance release!

Minor changes
-------------
#246  The page import.php can be used in CLI mode, allowing for massive data load
#311	Improved the reporting in the bulk import GUI (reconciliation of external keys, how to specify "undefined")
#293	Show the IP address against the device in the IP usage table for a subnet
#276	Show mandatory fields during CSV import
#285	Email addresses displayed as Mailto hyperlinks
Nicer display of the CSV import results...
Special passthrough mode for big XML pages output.
Allow n:n links to link several times to the same remote object (if "duplicates)=> true in the linkedset definition)
#284 Improved the behavior and reporting when attempting to create a document after a huge file
#111 Improved the data loader, and added a REST service to load data from a file.
   This is particularly interesting to facilitate the migration from an older installation.

Browser compatibility
---------------------
Tested successfully with IE8 and Chrome.
Fixed the "Relationships" Flash navigator so that it works also on Safari. (tested with Safari 5.0.2 on Windows) (Trac #310)
- Fixed the search form, and also fixed the search/selection of objects to link (n:n links) that was broken on IE8.
Fix to prevent IE 8 from running in IE7 compatibility mode... to be tested...


Security improvements
---------------------
#300	When logged onto an iTop instance, you are allowed on any other instance 

Bugs fixed
----------
The complete list can be reviewed on http://sourceforge.net/apps/trac/itop/report/1

#286	GetAbsoluteUrl creates broken links on IIS
#278	Missing PHP5 modules not detected properly
#289	Misleading errors when apache not authorized to write files in "setup" directory
#295	Unable to update or insert data
#313	Provider Contracts are not filtered by Allowed Organizations
#309	some of php-ofc-library files are missing
#315	Default organization not handled properly when there is just one organization allowed for the user
#308	Subnet / Free IPs: the subnet address is reserved (e.g. x.x.x.0)
#312	Exclamation sign not displayed for mandatory fields
#307	Auto-complete not reporting wrong selection
#302	Error: Unknown variable sIcon
#298	CSV template file opened in the browser instead of "downloaded"
#245	Search form gets too specialized
#306	Password gets corrupted if the admin forgets to select a profile
#297	Fixed a reporting issue on the SOAP service CreateIncidentTicket
#296	Incorrect display of Service/Subcategory localized characters in the portal
#292	Could not leave "User Satisfaction" field undefined
#258	Context automatically selected when searching on organization
#282	OQL Error when using functions
#288	Some multi objects OQL queries do not work
Fixed a bug in the XML encoding function
Fixed the issue "Object already modified". The mechanism that prevents a user from submitting the same form twice has been redesigned.
#283 Fixed issue with the default value of Enum attributes
Fixed limitation: tickets named automatically even if a name is specified (attribute : ref) ; this is stopper when importing tickets from an existing workflow tool


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

