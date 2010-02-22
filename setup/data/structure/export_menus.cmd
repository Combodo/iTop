SET WEBROOT=http://localhost:81/trunk
SET EXPORT=%WEBROOT%/webservices/export.php

SET USER=admin
SET PWD=admin

REM The order (numbering) of the files is important since
REM it dictates the order to import them back
wget --output-document=1.menus.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT menuNode WHERE type%%3D%%27application%%27 OR type%%3D%%27administrator%%27&format=xml"
pause