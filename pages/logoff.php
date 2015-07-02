<?php
// Copyright (C) 2010-2013 Combodo SARL
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

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/itopwebpage.class.inc.php');
require_once(APPROOT.'/application/wizardhelper.class.inc.php');

require_once(APPROOT.'/application/startup.inc.php');
$oAppContext = new ApplicationContext();
$currentOrganization = utils::ReadParam('org_id', '');
$operation = utils::ReadParam('operation', '');
require_once(APPROOT.'/application/loginwebpage.class.inc.php');
require_once(APPROOT.'/application/ajaxwebpage.class.inc.php');
$bPortal = utils::ReadParam('portal', false);
$sUrl = utils::GetAbsoluteUrlAppRoot();

if ($operation == 'do_logoff')
{
	// Reload the same dummy page to let the "calling" page execute its 'onunload' method before performing the actual logoff.
	// Note the redirection MUST NOT be made via an HTTP "header" since onunload is called only when the actual content of the DOM
	// is replaced by some other content. So the "bouncing" page must provide some content (in our case a script making the redirection).
	$oPage = new ajax_page('');
	$oPage->add_script("window.location.href='{$sUrl}pages/logoff.php?portal=$bPortal'");
	$oPage->output();
	exit;
}

if ($bPortal)
{
	$sUrl .= 'portal/';
}
else
{
	$sUrl .= 'pages/UI.php';
}
if (isset($_SESSION['auth_user']))
{
	$sAuthUser = $_SESSION['auth_user'];
	UserRights::Login($sAuthUser); // Set the user's language
}

$sLoginMode = isset($_SESSION['login_mode']) ? $_SESSION['login_mode'] : '';
LoginWebPage::ResetSession();
switch($sLoginMode)
{
	case 'cas':
	$sCASLogoutUrl = MetaModel::GetConfig()->Get('cas_logout_redirect_service');
	if (empty($sCASLogoutUrl))
	{
		$sCASLogoutUrl = $sUrl;
	}
	utils::InitCASClient();					
	phpCAS::logoutWithRedirectService($sCASLogoutUrl); // Redirects to the CAS logout page
	break;
}
$oPage = LoginWebPage::NewLoginWebPage();
$oPage->no_cache();
$oPage->DisplayLoginHeader();
$oPage->add("<div id=\"login\">\n");
$oPage->add("<h1>".Dict::S('UI:LogOff:ThankYou')."</h1>\n");

$oPage->add("<p><a href=\"$sUrl\">".Dict::S('UI:LogOff:ClickHereToLoginAgain')."</a></p>");
$oPage->add("</div>\n");
$oPage->output();
?>
