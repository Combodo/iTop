<?php
// Copyright (C) 2010-2012 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

/**
 * Wizard to configure and initialize the iTop application
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once('../approot.inc.php');
require_once(APPROOT.'/application/utils.inc.php');
require_once(APPROOT.'/core/config.class.inc.php');
require_once(APPROOT.'/setup/setuppage.class.inc.php');
require_once(APPROOT.'/setup/wizardcontroller.class.inc.php');
require_once(APPROOT.'/setup/wizardsteps.class.inc.php');

clearstatcache(); // Make sure we know what we are doing !
// Set a long (at least 4 minutes) execution time for the setup to avoid timeouts during this phase
ini_set('max_execution_time', max(240, ini_get('max_execution_time')));
// While running the setup it is desirable to see any error that may happen
ini_set('display_errors', true);
ini_set('display_startup_errors', true);

/////////////////////////////////////////////////////////////////////
// Fake functions to protect the first run of the installer
// in case the PHP JSON module is not installed...
if (!function_exists('json_encode'))
{
	function json_encode($value, $options = null)
	{
		return '[]';
	}
}
if (!function_exists('json_decode'))
{
	function json_decode($json, $assoc=null)
	{
		return array();
	}
}
/////////////////////////////////////////////////////////////////////

$oWizard = new WizardController('WizStepWelcome');
$oWizard->Run();
