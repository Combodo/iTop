SET WEBROOT=http://localhost:81/trunk
SET EXPORT=%WEBROOT%/webservices/export.php

SET USER=admin
SET PWD=admin

REM The order (numbering) of the files is important since
REM it dictates the order to import them back
wget --output-document=01.organizations.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT bizOrganization&format=xml"
wget --output-document=02.locations.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT bizLocation&format=xml"
wget --output-document=03.persons.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT bizPerson WHERE id !%%3D 1&format=xml"
wget --output-document=04.teams.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT bizTeam&format=xml"
wget --output-document=05.pcs.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT bizPC&format=xml"
wget --output-document=06.servers.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT bizServer&format=xml"
wget --output-document=07.applications.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT bizApplication&format=xml"
wget --output-document=08.nw-devices.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT bizNetworkDevice&format=xml"
wget --output-document=09.links_contacts.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT lnkContactRealObject&format=xml"
wget --output-document=09.1.interfaces.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT bizInterface&format=xml"
wget --output-document=09.2.circuits.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT bizCircuit&format=xml"
wget --output-document=10.workgroups.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT bizWorkgroup&format=xml"
wget --output-document=11.incidents.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT bizIncidentTicket&format=xml"
wget --output-document=11.1.servicecall.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT bizServiceCall&format=xml"
wget --output-document=12.relatedtickets.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT lnkRelatedTicket&format=xml"
wget --output-document=13.infratickets.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT lnkInfraTicket&format=xml"
wget --output-document=14.contacttickets.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT lnkContactTicket&format=xml"
wget --output-document=15.changetickets.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT bizChangeTicket&format=xml"
wget --output-document=16.infrachangetickets.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT lnkInfraChangeTicket&format=xml"
wget --output-document=17.contactchangetickets.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT lnkContactChange&format=xml"
wget --output-document=18.contracts.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT bizContract&format=xml"
wget --output-document=18.1.services.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT bizService&format=xml"
wget --output-document=19.infracontracts.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT lnkInfraContract&format=xml"
wget --output-document=20.contactcontracts.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT lnkContactContract&format=xml"

REM ----------- to be moved (or move the links at the end)
wget --output-document=21.subnets.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT bizSubnet&format=xml"

wget --output-document=50.knownerror.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT bizKnownError&format=xml"

REM ----------- what about hierarchies?
wget --output-document=60.group.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT bizInfraGroup&format=xml"

wget --output-document=99.contactteam.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT lnkContactTeam&format=xml"
wget --output-document=100.contactobject.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT lnkContactRealObject&format=xml"
wget --output-document=101.infragroup.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT lnkInfraGrouping&format=xml"

pause
