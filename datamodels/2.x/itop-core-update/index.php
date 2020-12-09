<?php
/**
 *  @copyright   Copyright (C) 2010-2019 Combodo SARL
 *  @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\CoreUpdate;

use Combodo\iTop\CoreUpdate\Controller\UpdateController;
use ContextTag;

require_once(APPROOT.'application/startup.inc.php');
new ContextTag(ContextTag::TAG_SETUP);

$oUpdateController = new UpdateController(MODULESROOT.'itop-core-update/view', 'itop-core-update');
$oUpdateController->DisableInDemoMode();
$oUpdateController->AllowOnlyAdmin();
$oUpdateController->SetDefaultOperation('SelectUpdateFile');
$oUpdateController->HandleOperation();
