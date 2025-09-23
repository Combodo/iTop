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

use Combodo\iTop\Portal\Kernel;
use Symfony\Component\HttpFoundation\Request;

require_once MODULESROOT.'itop-portal-base/portal/config/bootstrap.php';

// Stacking context tag so it knows we are in the portal
$oContext = new ContextTag(ContextTag::TAG_PORTAL);
$oContext2 = new ContextTag('Portal:'.$_ENV['PORTAL_ID']);


$oKPI = new ExecutionKPI();

// Note: Manually refactored ternary condition to be PHP 5.x compatible
if ($trustedProxies = isset($_SERVER['TRUSTED_PROXIES']) ? $_SERVER['TRUSTED_PROXIES'] : (isset($_ENV['TRUSTED_PROXIES']) ? $_ENV['TRUSTED_PROXIES'] : false)) {
	Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST);
}

// Note: Manually refactored ternary condition to be PHP 5.x compatible
if ($trustedHosts = isset($_SERVER['TRUSTED_HOSTS']) ? $_SERVER['TRUSTED_HOSTS'] : (isset($_ENV['TRUSTED_HOSTS']) ? $_ENV['TRUSTED_HOSTS'] : false)) {
	Request::setTrustedHosts([$trustedHosts]);
}

$oKernel = new Kernel($_SERVER['APP_ENV'], (bool)$_SERVER['APP_DEBUG']);
$oKPI->ComputeAndReport('Symfony kernel init');

$oKPI = new ExecutionKPI();
$oRequest = Request::createFromGlobals();
$oKPI->ComputeAndReport('Symfony request parsing/creation');

$oKPI = new ExecutionKPI();
/** @noinspection PhpUnhandledExceptionInspection */
$oResponse = $oKernel->handle($oRequest);
$oResponse->send();
$oKPI->ComputeAndReport('Page execution and rendering');


$oKPI = new ExecutionKPI();
$oKernel->terminate($oRequest, $oResponse);
$oKPI->ComputeAndReport('Symfony kernel termination');


ExecutionKPI::ReportStats();