<?php


use Combodo\iTop\Controller\OAuth\OAuthLandingController;

require_once('../approot.inc.php');
require_once(APPROOT.'/application/startup.inc.php');

$sTemplates = APPROOT.'templates/pages/backoffice/oauth';

$oUpdateController = new OAuthLandingController($sTemplates, 'core');
$oUpdateController->AllowOnlyAdmin();
$oUpdateController->SetDefaultOperation('Landing');
$oUpdateController->HandleOperation();
