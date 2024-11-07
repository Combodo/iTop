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

use Combodo\iTop\Application\Helper\Session;
use Combodo\iTop\Application\WebPage\AjaxPage;

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/wizardhelper.class.inc.php');

require_once(APPROOT.'/application/startup.inc.php');
IssueLog::Trace('----- Request: '.utils::GetRequestUri(), LogChannels::WEB_REQUEST);
$oAppContext = new ApplicationContext();
$currentOrganization = utils::ReadParam('org_id', '');
$operation = utils::ReadParam('operation', '');
require_once(APPROOT.'/application/loginwebpage.class.inc.php');
$bPortal = utils::ReadParam('portal', false);
$sUrl = utils::GetAbsoluteUrlAppRoot();

if ($operation == 'do_logoff')
{
	// Reload the same dummy page to let the "calling" page execute its 'onunload' method before performing the actual logoff.
	// Note the redirection MUST NOT be made via an HTTP "header" since onunload is called only when the actual content of the DOM
	// is replaced by some other content. So the "bouncing" page must provide some content (in our case a script making the redirection).
	$oPage = new AjaxPage('');
	$oPage->add_script("window.location.href='{$sUrl}pages/logoff.php?portal=$bPortal'");
	$oPage->output();
	exit;
}

if (Session::IsSet('auth_user'))
{
	$sAuthUser = Session::Get('auth_user');
	UserRights::Login($sAuthUser); // Set the user's language
}

LoginWebPage::ResetSession();

$bLoginDebug = MetaModel::GetConfig()->Get('login_debug');
if ($bLoginDebug)
{
	IssueLog::Info("---------------------------------");
	if (isset($sAuthUser))
	{
		IssueLog::Info("--> Logout user: [$sAuthUser]");
	}
	else
	{
		IssueLog::Info("--> Logout");
	}
	$sSessionLog = session_id().' '.utils::GetSessionLog();
	IssueLog::Info("SESSION: $sSessionLog");
}

$aPluginList = LoginWebPage::GetLoginPluginList('iLogoutExtension');

/** @var iLogoutExtension $oLogoutExtension */
foreach ($aPluginList as $oLogoutExtension)
{
	if ($bLoginDebug)
	{
		$sCurrSessionLog = session_id().' '.utils::GetSessionLog();
		if ($sCurrSessionLog != $sSessionLog)
		{
			$sSessionLog = $sCurrSessionLog;
			IssueLog::Info("SESSION: $sSessionLog");
		}
		IssueLog::Info("Logout call: ".get_class($oLogoutExtension));
	}

	$oLogoutExtension->LogoutAction();
}

if ($bLoginDebug)
{
	$sCurrSessionLog = session_id().' '.utils::GetSessionLog();
	if ($sCurrSessionLog != $sSessionLog)
	{
		$sSessionLog = $sCurrSessionLog;
		IssueLog::Info("SESSION: $sSessionLog");
	}
	IssueLog::Info("--> Display logout page");
}

LoginWebPage::ResetSession(true);
if ($bLoginDebug) {
    $sSessionLog = session_id().' '.utils::GetSessionLog();
    IssueLog::Info("SESSION: $sSessionLog");
}

$oPage = LoginWebPage::NewLoginWebPage();
$oPage->DisplayLogoutPage($bPortal);
