<?php
/**
 *  @copyright   Copyright (C) 2010-2019 Combodo SARL
 *  @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\CoreUpdate;

use Combodo\iTop\CoreUpdate\Controller\AjaxController;
use ContextTag;

require_once(APPROOT.'application/startup.inc.php');
new ContextTag('Setup');

$oUpdateController = new AjaxController();
$oUpdateController->DisableInDemoMode();
$oUpdateController->AllowOnlyAdmin();

// Allow parallel execution of ajax requests
session_write_close();
$oUpdateController->HandleOperation();
