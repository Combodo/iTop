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

$sTransactionId = utils::ReadParam('transaction_id');
if ((empty($sTransactionId)) || (false === privUITransaction::IsTransactionValid($sTransactionId))) {
	echo 'Invalid user';
	exit(3);
}

$sConfigFile = APPCONF.'production/config-itop.php';
@chmod($sConfigFile, 0770); // Allow overwriting the file

header('Location: wizard.php');