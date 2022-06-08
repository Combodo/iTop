<?php

use Combodo\iTop\Controller\OAuth\OAuthWizardController;

require_once('../approot.inc.php');
require_once(APPROOT.'application/startup.inc.php');

$sTemplates = APPROOT.'templates/pages/backoffice/oauth';

$oUpdateController = new OAuthWizardController($sTemplates, 'core');
$oUpdateController->AllowOnlyAdmin();
$oUpdateController->SetDefaultOperation('Wizard');
$oUpdateController->HandleOperation();

