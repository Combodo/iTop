<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/itopwebpage.class.inc.php');
require_once(APPROOT.'/application/wizardhelper.class.inc.php');

require_once(APPROOT.'/application/startup.inc.php');
$oAppContext = new ApplicationContext();
$currentOrganization = utils::ReadParam('org_id', '');
$operation = utils::ReadParam('operation', '');

require_once(APPROOT.'/application/loginwebpage.class.inc.php');
session_name(MetaModel::GetConfig()->Get('session_name'));
session_start();
$bPortal = utils::ReadParam('portal', false);
$sUrl = utils::GetAbsoluteUrlAppRoot();
if ($bPortal)
{
	$sUrl .= 'portal/';
}
else
{
	$sUrl .= 'pages/UI.php';
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
$oPage = new LoginWebPage();
$sVersionShort = Dict::Format('UI:iTopVersion:Short', ITOP_VERSION);
$oPage->add("<div id=\"login-logo\"><a href=\"http://www.combodo.com/itop\"><img title=\"$sVersionShort\" src=\"../images/itop-logo-external.png\"></a></div>\n");
$oPage->add("<div id=\"login\">\n");
$oPage->add("<h1>".Dict::S('UI:LogOff:ThankYou')."</h1>\n");

$oPage->add("<p><a href=\"$sUrl\">".Dict::S('UI:LogOff:ClickHereToLoginAgain')."</a></p>");
$oPage->add("</div>\n");
$oPage->output();
?>
