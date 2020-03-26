<?php
/**
 *  @copyright   Copyright (C) 2010-2019 Combodo SARL
 *  @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\CoreUpdate;

use Combodo\iTop\CoreUpdate\Controller\AjaxController;
use ContextTag;
use MetaModel;
use utils;

if (!defined('MODULESROOT'))
{
	define('MODULESROOT', APPROOT.'env-production/');
}

require_once(MODULESROOT.'itop-core-update/src/Service/RunTimeEnvironmentCoreUpdater.php');
require_once(MODULESROOT.'itop-core-update/src/Service/CoreUpdater.php');
require_once(MODULESROOT.'itop-core-update/src/Controller/AjaxController.php');


MetaModel::LoadConfig(utils::GetConfig());

new ContextTag(ContextTag::TAG_SETUP);

$oUpdateController = new AjaxController(MODULESROOT.'itop-core-update/view', 'itop-core-update');
$oUpdateController->DisableInDemoMode();
$oUpdateController->AllowOnlyAdmin();

// Allow parallel execution of ajax requests
session_write_close();
$oUpdateController->HandleAjaxOperation();
