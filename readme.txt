iTop - version 0.9.1 - 06-Apr-2010
Readme file

Content Of This File:

1.   ABOUT THIS RELEASE
2.   INSTALLATION
2.1. Requirements
2.2. Install procedure
2.3. Migration from version 0.8.1
3.   LIMITATIONS OF THE CURRENT VERSION
3.1. Changes since 0.8.1
4.   HOW TO
4.1. How to export data out of iTop
4.3. How to import data into iTop

1. ABOUT THIS RELEASE
   ==================
Thank you for downloading the sixth packaged release of iTop. This version is a maintenance
release providing a few bug fixes.
Keep in mind that this version (0.9.1) is still not a final version (see the section "Limitations" below).

With this release we also provide two user guides. These documents are available
on our web site http://www.combodo.com, under the "Support" topic.

iTop is released under the GPL (v3) license. (Check license.txt in this directory).
The source code of iTop can be found on SourceForge: http://itop.sourceforge.net

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
The 0.9.1 data model IS FULLY COMPATIBLE with the former one (0.9).
In order to upgrade from vesion 0.9, just overwrite the files in your iTop directory
with the ones provided in the current version.

In order to MIGRATE FROM AN OLDER VERSION, you have two options:
	- keep your current data model and give up all the benefits from the
new data model (subnets, known errors, etc.).
	- export your data into CSV files and import them back after the installation
of the new release.

Preserving your previous data model
-----------------------------------
In case you want to keep a previous data model (i.e. from 0.8.1) just make sure
that you don't overwrite the content of the "business" directory and its "template"
subdirectory.

Exporting & re-importing data
-----------------------------
If you choose this option, you have to use the export function for each type of
object, in order to save your data in csv files.
Once done, you can install new release 0.9 using a new database.
Then, you can re-import you object using "csv import" functionality. 

Caution: you need to make sure that attributes for your objects are still valid,
as per the new data model constraints.
In particular, attributes defined as enumerated lists may have to be changed
into the data files, to comply with to the new list of allowed values.
Please refer to the chapter "viewing data model" chapter in the administrator
guide.

In case you encounter issues, do not hesitate to contact the support team:
support@combodo.com



3. LIMITATIONS OF THE CURRENT VERSION
   ==================================

Release 0.9.1 is not supporting:

	- Creation of new user profile from the iTop user interface.
	- "Delete All" action for a list of objects.
	- Cloning an existing device. This feature has been disabled for the moment
          as it was not working properly.
	- "Update All" for n/n relationships.
	- A lifecycle is not defined for all types of CIs. Only for incident tickets and change tickets.
	- Localized characters (like accentuated letters) are not supported for the moment in eMail notifications


3.1. Changes since 0.9
     -------------------
Only bug fixes haven bee made since version 0.9, hence the increment of the minor revision number.

Bugs fixed
----------

All our bugs are tracked on sourceforge: 
http://sourceforge.net/apps/trac/itop/report/1.
This release is closing 3 bugs or enhancement requests.

#86 major	defect	Fixed bug on CSV import (was not working fine when exporting/importing fields with multiple lines)
#87 major 	defect	Strings containing only digits were treated as numbers instead of string (i.e leading zeroes were lost)
#93 major	defect	Fixed issue within the setup data load (related to memory_limit)

Comprehensive list of all other changes...
------------------------------------------

- Fixed bug in DisplayBlock (group by - visible on the page "contacts overview")
- Finalized the demo of impact computation (removed an ugly test message) and added few comments
- Related objects computation:
	moved to OQL
	added capacity to set a default value based on the related objects (during the creation wizard)
- Fixed issues with the consultant toolkit: upgrade an existing DB (add new class/attribute)
- Developed core services to allow for demonstrating impact computation capability
- Deprecated option operation=direct on page UI.php (not used anyway ?)

4. HOW TO
   ======

4.1. How to export data out of iTop
     ------------------------------
A set of objects can be exported by the mean of a web service (could be scripted)
Simply call /pages/export.php?format=xml&expression=OQL
(format=csv is also available)

Using wget, this would give the following command line:
wget --header="Content-Type:application/x-www-form-urlencoded" --post-file=./login.txt -O "export.txt" http://<server>/webservices/export.php?format=csv&expression=...

Use the wget option -O to store the result in the specified file, in our example: export.txt

The format for the file login.txt should be:

operation=login&auth_user=<your user>&auth_pwd=<your password>&foo=1

The set of objects to be exported is defined by an OQL query.
OQL stands for Object Query Language. The OQL syntax is very close to the SQL.
The main differences between SQL and OQL are:

 * No FROM clause: an OQL query always return a set of objects of a given class
   and the user will never specify the expected columns, because the OQL
   interpreter retreives this information from the Data Model.
   
 * JOINS: simply specify "JOIN" and the interpreter will determine for you if an
   INNER JOIN or an OUTER JOIN should be performed, based on the definitions of
   the Data Model.

OQL Examples:

Get all the contacts
SELECT bizContact

Get all the persons (note that a person is contact also, but it has more
attributes to be exported: first_name and employee_number)

SELECT bizPerson

Get the WAN circuits provided by "Foo Telecom"

SELECT bizCircuit JOIN bizOrganization ON bizCircuit.provider_id = bizOrganization.id
WHERE bizOrganization.name = 'Foo Telecom'

Get the WAN circuits providers

SELECT bizOrganization JOIN bizCircuit
ON bizCircuit.provider_id = bizOrganization.id

(In this example we have just inverted bizCircuit and bizOrganization ; yes the order matters, the first class specified is the expected class)


4.2. How to import data
     ------------------
SOAP web service:

This new SOAP web service enables the creation of incident tickets from an external application.
Look at the iTop WSDL file (http://<your_server_and_port/webservices/itop.wsdl.php) for the full
description of the API.

Check the PHP client example, available with this release in /webservices/itop.soap.examples.php


"CSV import" web service:

This is the "POST web service" that was already existing in the previous version.
A dedicated page allows you to write a script to enter new data, or refresh existing
data. This can be helpful for the initial load or to schedule a daily synchronization
of the data coming from an external data source - could be another application,
an automated data collector, etc.

/webservices/import.php?class=bizOrganization&csvdata=<multine-csv>[&separator=<char>]

Note that this service emulates the functionality provided by the interactive
bulk load: /pages/import.php

csvdata must be posted, the first line will contain the codes of the attributes
to load, the first column is always used as the reconciliation key
- should be unique, as it determines if the object needs to be updated or created)
If not specified, the separator defaults to ';'

The answer is given in a simple html format, explaining what has been done for each row of data.

Example:
A script that creates a company called "Food and Drug Administration" (code FDA).

wget --header="Content-Type:application/x-www-form-urlencoded" --post-file=data.txt http://<yourserver:port>/webservices/import.php?class=bizOrganization

with: data.txt containing the following text

auth_user=<username>&auth_pwd=<pwd>&loginop=login&csvdata=name;code
Food and Drug Administration;FDA
Combodo;CBD
