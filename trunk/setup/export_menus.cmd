SET WEBROOT=http://localhost:81
SET USER=Erwan
SET PWD=Taloc
REM The order (numbering) of the files is important since
REM it dictates the order to import them back
wget --output-document=1.menus.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%WEBROOT%/pages/export.php?expression=SELECT menuNode WHERE type%%3D%%27application%%27&format=xml"
