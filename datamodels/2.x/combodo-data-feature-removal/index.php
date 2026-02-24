<?php

/**
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DataFeatureRemoval;

use Combodo\iTop\DataFeatureRemoval\Controller\DataFeatureRemovalController;
use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalHelper;
use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalLog;

require_once(APPROOT.'application/startup.inc.php');

DataFeatureRemovalLog::Enable();

$oController = new DataFeatureRemovalController(MODULESROOT.DataFeatureRemovalHelper::MODULE_NAME.'/templates', DataFeatureRemovalHelper::MODULE_NAME);
$oController->SetDefaultOperation('Main');
$oController->HandleOperation();
