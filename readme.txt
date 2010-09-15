iTop - version 1.0.0 - 16-Sep-2010
Readme file

1.   ABOUT THIS RELEASE
2.   INSTALLATION
2.1. Requirements
2.2. Install procedure
2.3. Migration from previous version
3.   FEATURES
3.1. Changes since 0.9.1
3.2. Known limitations
3.3. Known issues

1. ABOUT THIS RELEASE
   ==================
Thank you for downloading the seventh packaged release of iTop. This version is
the first complete version of iTop: it aims at being really used professionaly.

Additional documentation can be dowloaded from http://www.combodo.com,
under the "Support" topic:
 - User guide
 - Admin guide
 - How to upgrade from previous versions

iTop is released under the GPL (v3) license. (Check license.txt in this directory).
The source code of iTop can be found on SourceForge: http://itop.sourceforge.net

1.1 Special Thanks To:
    -----------------
Bruno Bonfils for his guidance about LDAP and authentication.
Randall Badilla Castro, for the Spanish translation.
Jonathan Lucas and David Gumbel from ITOMIG BmBh, for the german translation.
Christian Lempereur, for his feedbacks.
Olivier Fouquet, for his feedbacks.



2. INSTALLATION
   ============

2.1. Requirements
     ------------
iTop is based on the AMP (Apache / MySQL / PHP) platform and requires PHP 5.2 and
MySQL 5. The installation of iTop does not require any command line access to the
server. The only operations required to install iTop are: copying the files to the
server and browsing some web pages.
Although iTop should work with most modern web browsers the application has been
tested mostly on Firefox 3 and IE7/IE8.

2.2. Install procedure
     -----------------
1) Make sure that you have a properly configured instance of Apache/PHP running
2) Unpack the files contained in the zipped package in a directory served by your
web server.
3) Point your web browser to the URL corresponding to the directory were the files
have been unpackaged and follow the indications on the screen.

Note:
iTop uses MySQL with the InnoDB engine. If you are running on Linux and if the setup is
very slow with the hard drive spinning a lot, try to set the following value in the my.cnf
configuration file (usually located at /etc/mysql/my.cnf):

innodb_flush_method = O_DSYNC

On some systems you'll see a 5 to 10 times performance improvement for writing data into
the MySQL database !

2.3. Migration from a previous version
     ---------------------------------
Please refer to the updgrade guide available at www.combodo.com/itop-support.


3. FEATURES
   ========

3.1. Changes since 0.9.1
     -------------------

Version 1.0 is a major release.

Localization
------------
- iTop is localized: English, French, Spanish and German are available.

User portal
-----------
- User Portal: let customers submit their request directly

SLA Management
--------------
- SLAs can be defined in the service management module. iTop application uses them to change behavior of Incident,
  Request and Change tickets when SLAs are not respected.

Modular setup
-------------
- Modules: It is possible to select ITIL modules you would like to use


Major changes
-------------
- A brand new data model has been designed to make iTop ITIL compliant.
- Graphical views have been developed to represent releation between CIS. Two views are available today: "impact"
  in order to view what CIs are impacted by a given one, and "depends on" to view what CIs impact a given one.
- Relations for computing impacts can be customized.
- Those relations are used as well to compute impacted CIs and contacts to notify when an incident occurs.
- UI look and feel has been reviewed to make the application more professional.
- The CSV import tool has been improved to make it easier to use.
- A Web service has been developed to allow tickets to be created automatically from emails. This feature simplifies
  ticket creation for end-users.
- User management: Finalized the UI to create new users and manage their profiles
- Authentication: added the possibility to rely on an LDAP authentication, or
  and external authentication (e.g. Web Server single sign-on, relying on a .htaccess file)


Minor changes
-------------
- User welcome splash screen: message displayed to new users, the first time
  they logon to iTop
- Implemented validation of attributes entered in forms
- import.php has been finalized, and is the preferred way to load/synchronize
  data in a non-interactive way
- New menu to edit the Audit Category and Rules


Security improvements
---------------------
#157 Data Admin menu allowed only to admins and configuration managers
#260 Prevent normal users from accessing data admin menu
Don't display the admin menu for non-admin users
#188 - ForceHttps = SecureConnectionRequired
Strong encryption of passwords
Redirect from /pages/index.php to /index.php (to prevent users from listing the directory)


Bugs fixed
----------

Bugs can be reviewed on http://sourceforge.net/apps/trac/itop/report/1

#182	Setup fails with mysql error 1046 or 1146
#144 	Could not create a workgroup
#97 	Issue when removing an organization
#105 	Issue in exporting a given class of object
#106 	Importing data using import CSV
#116 	When modifying a user, the link with the profile(s) is lost.
#98 	Computation of free IPs in a subnet is wrong
#126 	'magic_quotes_gpc' test issue during setup
#128 	Issue when using AttributeBlob not mandatory
#136 	Context menus
#102  Allow users to change their password.
#210  Error message when trying to uploading a big file
#139  mysSQL error: "truncated column", or truncated string
#223  Trim spaces in CSV imports
#215  Support several characters encoding for the CSV imports
#239  Issue with character set (impacting searches with accents)
#234  PHP Strict Standards warnings
#140  Check that user logins are unique

3.2. Known limitations
     -----------------
#71   The same MySQL credentials are used for setup and the application. They have to
      be changed manually into the configuration file to achieve maximum security
#246  Massive data load requiring to setup specific HTTP sessions with higher
      timeouts and memory limits
#257  Could not delete more than 997 items when SUHOSIN is installed with its
      default settings (See TRAC)
#265  Add reconciliations keys into CSV template

3.3. Known issues
     ------------
#259  Not instantaneously logged off when the administrator deletes a user entry
#245  Search form gets too specialized
#175  When moving backward in the CSV import wizard, some settings may be lost
      (e.g column selection)
#174  CSV import not displaying the labels of enums

#258	org_id search -> silo
