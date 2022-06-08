<?php

use Combodo\iTop\Controller\OAuth\OAuthAjaxController;

require_once('../approot.inc.php');
require_once(APPROOT.'/application/startup.inc.php');

$sTemplates = APPROOT.'templates/pages/backoffice/oauth';

$oUpdateController = new OAuthAjaxController($sTemplates, 'core');
$oUpdateController->AllowOnlyAdmin();
$oUpdateController->SetDefaultOperation('Default');
$oUpdateController->HandleOperation();
