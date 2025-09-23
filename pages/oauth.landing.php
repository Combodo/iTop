<?php


use Combodo\iTop\Controller\OAuth\OAuthLandingController;

require_once('../approot.inc.php');
require_once(APPROOT.'/application/startup.inc.php');
IssueLog::Trace('----- Request: '.utils::GetRequestUri(), LogChannels::WEB_REQUEST);

$sTemplates = APPROOT.'templates/pages/backoffice/oauth';

$oUpdateController = new OAuthLandingController($sTemplates, 'core');
$oUpdateController->SetDefaultOperation('Landing');
$oUpdateController->HandleOperation();
