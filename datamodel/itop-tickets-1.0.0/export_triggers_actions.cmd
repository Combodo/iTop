SET WEBROOT=http://localhost:81/
SET EXPORT=%WEBROOT%/webservices/export.php

SET USER=admin
SET PWD=test

REM The order (numbering) of the files is important since
REM it dictates the order to import them back
wget --output-document=sample.data.ta-triggers.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT Trigger&format=xml"
wget --output-document=sample.data.ta-actions.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT ActionEmail&format=xml"
wget --output-document=sample.data.ta-links.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT lnkTriggerAction&format=xml"
pause
