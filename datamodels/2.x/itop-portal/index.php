<?php

// Copyright (C) 2010-2017 Combodo SARL
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
 * Backward Compatibility file for default portal.
 * Needed when:
 * - PortalDispatcher uses the old url "pages/exec.php?exec_module=itop-portal&amp;exec_page=index.php"
 * - Portal xml has no //properties/urlmaker_class tag
 * - Checks are necessary (eg. UserRequest/Incident class detection)
 *
 * NOT needed when:
 * - PortalDispatcher uses the new url "pages/exec.php?exec_module=itop-portal-base&amp;exec_page=index.php&amp;portal_id=itop-portal"
 * - Portal xml has a //properties/urlmaker_class tag (or doesn't need to register a UrlMakerClass)
 */

if (file_exists(__DIR__ . '/../../approot.inc.php'))
{
	require_once __DIR__ . '/../../approot.inc.php';   // When in env-xxxx folder
}
else
{
	require_once __DIR__ . '/../../../approot.inc.php';   // When in datamodels/x.x folder
}
require_once APPROOT . '/application/startup.inc.php';

// Protection against setup in the following configuration : ITIL Ticket with Enhanced Portal selected but neither UserRequest or Incident. Which would crash the portal.
if (!class_exists('UserRequest') && !class_exists('Incident'))
{
	die('iTop has neither been installed with User Request nor Incident tickets. Please contact your administrator.');
}

// Defining portal constants
$sDir = basename(__DIR__);
define('PORTAL_MODULE_ID', $sDir);
define('PORTAL_ID', $sDir);

require_once APPROOT . '/env-' . utils::GetCurrentEnvironment() . '/itop-portal-base/index.php';
