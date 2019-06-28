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
 * Executes a portal without having a dedicated module.
 * This allows to make a portal directly from the ITSM Designer.
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

// If PORTAL_ID is not already defined, we look for it in a parameter
if(!defined('PORTAL_ID'))
{
    // Retrieving portal id from request params
    $sPortalId = utils::ReadParam('portal_id', '');
    if ($sPortalId == '')
    {
        echo "Missing argument 'portal_id'";
        exit;
    }

    // Defining portal constants
    define('PORTAL_ID', $sPortalId);
}

// Set debug mode only when necessary
if (utils::ReadParam('debug', 'false') === 'true')
{
	$_SERVER['APP_DEBUG'] = true;
}

define('PORTAL_CACHE_PATH', utils::GetCachePath() . '/portals/' . PORTAL_ID . '/');

// Constants to be used in templates and others
define('COMBODO_CURRENT_ENVIRONMENT', utils::GetCurrentEnvironment());
define('COMBODO_ABSOLUTE_URL', utils::GetAbsoluteUrlAppRoot());
define('COMBODO_MODULES_ABSOLUTE_URL',  utils::GetAbsoluteUrlAppRoot() . 'env-' . utils::GetCurrentEnvironment());
define('COMBODO_PORTAL_BASE_ABSOLUTE_URL', utils::GetAbsoluteUrlAppRoot() . 'env-' . utils::GetCurrentEnvironment() . '/itop-portal-base/portal/public/');
define('COMBODO_PORTAL_BASE_ABSOLUTE_PATH',  MODULESROOT . '/itop-portal-base/portal/public/');
define('COMBODO_PORTAL_INSTANCE_ABSOLUTE_URL', utils::GetAbsoluteUrlAppRoot() . 'env-' . utils::GetCurrentEnvironment() . '/' . PORTAL_ID . '/');

require_once APPROOT . '/env-' . utils::GetCurrentEnvironment() . '/itop-portal-base/portal/public/index.php';
