<?php
/**
 *  @copyright   Copyright (C) 2010-2022 Combodo SARL
 *  @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\RemoteAuthentOAuth;

use Combodo\iTop\RemoteAuthentOAuth\Controller\RemoteAuthentOauthController;

require_once(APPROOT.'application/startup.inc.php');

if (version_compare(ITOP_DESIGN_LATEST_VERSION , '3.0') >= 0) {
	$sTemplates = MODULESROOT.'itop-remote-authent-oauth/templates';
} else {
	$sTemplates = MODULESROOT.'itop-remote-authent-oauth/templates/legacy';
}

$oUpdateController = new RemoteAuthentOauthController($sTemplates, 'itop-remote-authent-oauth');
$oUpdateController->AllowOnlyAdmin();
$oUpdateController->SetDefaultOperation('CreateMailbox');
$oUpdateController->HandleOperation();


