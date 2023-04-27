<?php
$sConfigFile = 'conf/production/config-itop.php';
$sStartPage = './pages/UI.php';
$sSetupPage = './setup/index.php';

require_once('approot.inc.php');

/**
 * Check that the configuration file exists and has the appropriate access rights
 * If the file does not exist, launch the configuration wizard to create it
 */  
if (file_exists(APPROOT.$sConfigFile))
{
	if (!is_readable($sConfigFile))
	{
		echo "<p><b>Error</b>: Unable to read the configuration file: '$sConfigFile'. Please check the access rights on this file.</p>";
	}
	else if (is_writable($sConfigFile))
	{
		require_once (APPROOT.'setup/setuputils.class.inc.php');
		if (SetupUtils::IsInReadOnlyMode())
		{
			echo "<p><b>Warning</b>: the application is currently in maintenance, please wait.</p>";
			echo "<p>Click <a href=\"$sStartPage\">here</a> to ignore this warning and continue to run iTop in read-only mode.</p>";
		}
		else
		{
			echo <<<HTML
<p><b>Security Warning</b>: the configuration file '{$sConfigFile}' should be read-only.</p>
<p>Please modify the access rights to this file.</p>
<p>Click <a href="{$sStartPage}">here</a> to ignore this warning and continue to run iTop.</p>
HTML;
		}
	}
	else
	{
		header("Location: $sStartPage");
	}
}
else
{
	// Config file does not exist, need to run the setup wizard to create it
	header("Location: $sSetupPage");
}
