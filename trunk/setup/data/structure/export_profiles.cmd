SET WEBROOT=http://localhost:81/trunk
SET EXPORT="%WEBROOT%/webservices/export.php"

SET USER=admin
SET PWD=admin

REM The order (numbering) of the files is important since
REM it dictates the order to import them back
wget --output-document=10.dimensions.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT URP_Dimensions&format=xml"
wget --output-document=11.profiles.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT URP_Profiles&format=xml"
wget --output-document=12.classprojection.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT URP_ClassProjection&format=xml"
wget --output-document=13.profileprojection.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT URP_ProfileProjection&format=xml"
wget --output-document=14.actiongrant.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT URP_ActionGrant&format=xml"
wget --output-document=15.attributegrant.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT URP_AttributeGrant&format=xml"
wget --output-document=16.stimulusgrant.xml --post-data="auth_user=%USER%&auth_pwd=%PWD%&operation=login" "%EXPORT%?expression=SELECT URP_StimulusGrant&format=xml"
pause