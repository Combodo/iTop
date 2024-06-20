<?php

/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

// Disable PhpUnhandledExceptionInspection as the exception handling is made by the file including this one
/** @noinspection PhpUnhandledExceptionInspection */

use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\Dotenv\Dotenv;

// Global autoloader (portal autoloader is already required through module.itop-portal-base.php)
require_once APPROOT.'/lib/autoload.php';

// Load current environment if necessary (typically from CLI as the app is not started yet)
if (!defined('MODULESROOT'))
{
	if (file_exists(__DIR__.'/../../../../approot.inc.php'))
	{
		require_once __DIR__.'/../../../../approot.inc.php';   // When in env-xxxx folder
	}
	else
	{
		require_once __DIR__.'/../../../../../approot.inc.php';   // When in datamodels/x.x folder
	}
	require_once APPROOT.'/application/startup.inc.php';
}

// Load cached env vars if the .env.local file exists
if (!class_exists(Dotenv::class)) {
	throw new RuntimeException('Please run "composer require symfony/dotenv" to load the ".env" files configuring the application.');
} else {
	$sPath = file_exists(dirname(__DIR__).'/.env.local') ? dirname(__DIR__).'/.env.local' : dirname(__DIR__).'/.env';
	$oDotenv = new Dotenv();
	$oDotenv->usePutenv();

	// load all the .env files
	if (method_exists($oDotenv, 'loadEnv')) {
		$oDotenv->loadEnv($sPath, null, 'prod');
	} else {
		// fallback code in case your Dotenv component is not 4.2 or higher (when loadEnv() was added)

		if (file_exists($sPath) || !file_exists($sPathDist = "$sPath.dist")) {
			$oDotenv->load($sPath);
		} else {
			$oDotenv->load($sPathDist);
		}

		if (null === $sEnv = (isset($_SERVER['APP_ENV']) ? $_SERVER['APP_ENV'] : (isset($_ENV['APP_ENV']) ? $_ENV['APP_ENV'] : null))) {
			$oDotenv->populate(array('APP_ENV' => $sEnv = 'prod'));
		}

		if ('test' !== $sEnv && file_exists($sPathDist = "$sPath.local")) {
			$oDotenv->load($sPathDist);
			$sEnv = isset($_SERVER['APP_ENV']) ? $_SERVER['APP_ENV'] : (isset($_ENV['APP_ENV']) ? $_ENV['APP_ENV'] : $sEnv);
		}

		if (file_exists($sPathDist = "$sPath.$sEnv")) {
			$oDotenv->load($sPathDist);
		}

		if (file_exists($sPathDist = "$sPath.$sEnv.local")) {
			$oDotenv->load($sPathDist);
		}
	}
}

$_SERVER += $_ENV;
$_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = (isset($_SERVER['APP_ENV']) ? $_SERVER['APP_ENV'] : (isset($_ENV['APP_ENV']) ? $_ENV['APP_ENV'] : null)) ?: 'prod';
$_SERVER['APP_DEBUG'] = isset($_SERVER['APP_DEBUG']) ? $_SERVER['APP_DEBUG'] : (isset($_ENV['APP_DEBUG']) ? $_ENV['APP_DEBUG'] : ('prod' !== $_SERVER['APP_ENV']));
$_SERVER['APP_DEBUG'] = $_ENV['APP_DEBUG'] = (int)$_SERVER['APP_DEBUG'] || filter_var($_SERVER['APP_DEBUG'],
	FILTER_VALIDATE_BOOLEAN) ? '1' : '0';

if ($_SERVER['APP_DEBUG'])
{
	umask(0000);

	if (class_exists(Debug::class))
	{
		Debug::enable();
	}
}

if (isset($_ENV['PORTAL_ID']))
{
	// Nothing to do
}
// Note: Default value is set to "false" to differentiate an empty value from a non given parameter
elseif ($sPortalId = utils::ReadParam('portal_id', false, true))
{

	$_ENV['PORTAL_ID'] = $sPortalId;
}
elseif (defined('PORTAL_ID'))
{
	$_ENV['PORTAL_ID'] = PORTAL_ID;
	@trigger_error(
		sprintf(
			'Usage of legacy "PORTAL_ID" constant ("%s") is deprecated. You should pass "portal_id" in the URL as GET parameter.',
			PORTAL_ID
		),
		E_USER_DEPRECATED
	);
}

if (empty($_ENV['PORTAL_ID']))
{
	echo "Missing argument 'portal_id'";
	exit;
}

// Make sure that the PORTAL_ID constant is also defined
// Note: This is widely used in extensions, snippets and all
if (!defined('PORTAL_ID'))
{
	define('PORTAL_ID', $_ENV['PORTAL_ID']);
}

// Env. vars to be used in templates and others
$_ENV['COMBODO_CURRENT_ENVIRONMENT'] = utils::GetCurrentEnvironment();
$_ENV['COMBODO_ABSOLUTE_URL'] = utils::GetAbsoluteUrlAppRoot();
$_ENV['COMBODO_CONF_APP_ICON_URL'] = MetaModel::GetConfig()->Get('app_icon_url');
$_ENV['COMBODO_MODULES_ABSOLUTE_URL'] = utils::GetAbsoluteUrlModulesRoot();
$_ENV['COMBODO_PORTAL_BASE_ABSOLUTE_URL'] = utils::GetAbsoluteUrlModulesRoot().'itop-portal-base/portal/public/';
$_ENV['COMBODO_PORTAL_BASE_ABSOLUTE_PATH'] = MODULESROOT.'/itop-portal-base/portal/public/';
$_ENV['COMBODO_PORTAL_INSTANCE_ABSOLUTE_URL'] = utils::GetAbsoluteUrlModulesRoot().$_ENV['PORTAL_ID'].'/';