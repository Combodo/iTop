<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Config\Controller\ConfigEditorController;
use Combodo\iTop\Config\Validator\iTopConfigValidator;

require_once(APPROOT.'application/startup.inc.php');

/////////////////////////////////////////////////////////////////////
// Main program
//
LoginWebPage::DoLogin(); // Check user rights and prompt if needed
ApplicationMenu::CheckMenuIdEnabled('ConfigEditor');


$oConfigEditorController = new ConfigEditorController();
$oConfigEditorController->SetDefaultOperation('Edit');
$oConfigEditorController->HandleOperation();

