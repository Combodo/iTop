<?php

// Copyright (C) 2010-2015 Combodo SARL
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
 * Required constants :
 * - PORTAL_MODULE_ID : Name of the portal instance module
 * - PORTAL_ID : Name of the portal instance module design (Configuration)
 */
// Silex framework and components
require_once APPROOT . '/lib/silex/vendor/autoload.php';
// iTop application requirements
//require_once __DIR__.'/../../../../approot.inc.php';  // Required by the instanciation module
//require_once APPROOT.'/application/startup.inc.php';  // Required by the instanciation module
require_once APPROOT . '/core/moduledesign.class.inc.php';
require_once APPROOT . '/application/loginwebpage.class.inc.php';
require_once APPROOT . '/sources/autoload.php';
// Portal
require_once __DIR__ . '/../src/providers/urlgeneratorserviceprovider.class.inc.php';
require_once __DIR__ . '/../src/helpers/urlgeneratorhelper.class.inc.php';
require_once __DIR__ . '/../src/providers/contextmanipulatorserviceprovider.class.inc.php';
require_once __DIR__ . '/../src/helpers/contextmanipulatorhelper.class.inc.php';
require_once __DIR__ . '/../src/providers/scopevalidatorserviceprovider.class.inc.php';
require_once __DIR__ . '/../src/helpers/scopevalidatorhelper.class.inc.php';
require_once __DIR__ . '/../src/helpers/securityhelper.class.inc.php';
require_once __DIR__ . '/../src/helpers/applicationhelper.class.inc.php';

use \Combodo\iTop\Portal\Helper\ApplicationHelper;

// Checking user rights and prompt if needed
LoginWebPage::DoLoginEx(PORTAL_ID);
if (UserRights::GetContactId() == 0)
{
	die(Dict::S('Portal:ErrorNoContactForThisUser'));
}

// Stacking context tag so it knows we are in the portal
$oContex = new ContextTag('GUI:Portal');
$oContex2 = new ContextTag('Portal:' . PORTAL_MODULE_ID);

if (!defined('DISABLE_DATA_LOCALIZER_PORTAL'))
{
	ApplicationContext::SetPluginProperty('QueryLocalizerPlugin', 'language_code', UserRights::GetUserLanguage());
}

// Checking if debug param is on
$bDebug = (isset($_REQUEST['debug']) && ($_REQUEST['debug'] === 'true') );

// Initializing Silex framework
$oKPI = new ExecutionKPI();
$oApp = new Silex\Application();

// Registring optional silex components
$oApp->register(new Combodo\iTop\Portal\Provider\UrlGeneratorServiceProvider());
$oApp->register(new Combodo\iTop\Portal\Provider\ContextManipulatorServiceProvider());
$oApp->register(new Combodo\iTop\Portal\Provider\ScopeValidatorServiceProvider(), array(
	'scope_validator.scopes_path' => utils::GetCachePath(),
	'scope_validator.scopes_filename' => PORTAL_ID . '.scopes.php',
	'scope_validator.instance_name' => PORTAL_ID
));
$oApp->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => MODULESROOT
));
$oApp->register(new Silex\Provider\HttpFragmentServiceProvider());
$oKPI->ComputeAndReport('Initialization of the Silex application');

// Configuring Silex application
$oApp['debug'] = $bDebug;
$oApp['combodo.absolute_url'] = utils::GetAbsoluteUrlAppRoot();
$oApp['combodo.portal.base.absolute_url'] = utils::GetAbsoluteUrlAppRoot() . 'env-' . utils::GetCurrentEnvironment() . '/itop-portal-base/portal/web/';
$oApp['combodo.portal.base.absolute_path'] = MODULESROOT . '/itop-portal-base/portal/web/';
$oApp['combodo.portal.instance.absolute_url'] = utils::GetAbsoluteUrlAppRoot() . 'env-' . utils::GetCurrentEnvironment() . '/' . PORTAL_MODULE_ID . '/';
$oApp['combodo.portal.instance.id'] = PORTAL_MODULE_ID;
$oApp['combodo.portal.instance.conf'] = array();
$oApp['combodo.portal.instance.routes'] = array();

// Registering error/exception handler in order to transform php error to exception
ApplicationHelper::RegisterExceptionHandler($oApp);

// Preparing portal foundations (Can't use Silex autoload through composer as we don't follow PSR conventions -filenames, functions-)
$oKPI = new ExecutionKPI();
ApplicationHelper::LoadControllers();
ApplicationHelper::LoadRouters();
ApplicationHelper::RegisterRoutes($oApp);
ApplicationHelper::LoadBricks();
ApplicationHelper::LoadFormManagers();
ApplicationHelper::RegisterTwigExtensions($oApp);
$oKPI->ComputeAndReport('Loading portal files (routers, controllers, ...)');

// Loading portal configuration from the module design
$oKPI = new ExecutionKPI();
ApplicationHelper::LoadPortalConfiguration($oApp);
$oKPI->ComputeAndReport('Parsing portal configuration');
// Loading current user
ApplicationHelper::LoadCurrentUser($oApp);

// Running application
$oKPI = new ExecutionKPI();
$oApp->run();
$oKPI->ComputeAndReport('Page execution and rendering');

// Logging trace and stats
DBSearch::RecordQueryTrace();
ExecutionKPI::ReportStats();