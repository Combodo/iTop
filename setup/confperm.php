<?php
require_once '../approot.inc.php';
require_once APPROOT.'setup/setuputils.class.inc.php';


if (false === utils::IsDevelopmentEnvironment()) {
	echo 'Action forbidden';
	exit(1);
}

$aPreviousInstance = SetupUtils::GetPreviousInstance(APPROOT);
if (false === $aPreviousInstance['found']) {
	echo 'Action forbidden';
	exit(2);
}

try {
	SetupUtils::CheckSetupToken(true);
}
catch (SecurityException $e) {
	echo 'Invalid user';
	exit(3);
}

$sConfigFile = APPCONF.'production/config-itop.php';
@chmod($sConfigFile, 0770); // Allow overwriting the file

header('Location: wizard.php');