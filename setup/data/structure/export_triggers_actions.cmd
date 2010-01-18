SET WEBROOT=http://localhost:81/
SET EXPORT=%WEBROOT%/webservices/export.php

SET USER=admin
SET PWD=test

REM The order (numbering) of the files is important since
REM it dictates the order to import them back
wget --output-document=23.triggers.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT Trigger&format=xml"
wget --output-document=24.actions.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT ActionEmail&format=xml"
wget --output-document=25.trigger-actions.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT lnkTriggerAction&format=xml"
pause
