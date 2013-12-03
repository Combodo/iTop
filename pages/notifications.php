<?php
// Copyright (C) 2013 Combodo SARL
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
 * Page to configuration the notifications (triggers and actions)
 *
 * @copyright   Copyright (C) 2013 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/itopwebpage.class.inc.php');

require_once(APPROOT.'/application/startup.inc.php');

require_once(APPROOT.'/application/loginwebpage.class.inc.php');
LoginWebPage::DoLogin(true); // Check user rights and prompt if needed (must be admin)



// Main program
//
$oP = new iTopWebPage(Dict::S('Menu:NotificationsMenu+'));

$oP->add('<div class="page_header" style="padding:0.5em;">');
$oP->add('<h1>'.dict::S('UI:NotificationsMenu:Title').'</h1>');
$oP->add('</div>');

$oP->StartCollapsibleSection(Dict::S('UI:NotificationsMenu:Help'), true);
$oP->add('<div style="padding: 1em; font-size:10pt;background:#E8F3CF;margin-top: 0.25em;">');
$oP->add('<img src="../images/bell.png" style="margin-top: -60px; margin-right: 10px; float: right;">');
$oP->add(Dict::S('UI:NotificationsMenu:HelpContent'));
$oP->add('</div>');
$oP->add('');
$oP->add('');
$oP->EndCollapsibleSection();

$oP->add('<p>&nbsp;</p>');


$oP->AddTabContainer('Tabs_0');
$oP->SetCurrentTabContainer('Tabs_0');

$oP->SetCurrentTab(Dict::S('UI:NotificationsMenu:Triggers'));
$oP->add('<h2>'.Dict::S('UI:NotificationsMenu:AvailableTriggers').'</h2>');
$oFilter = new DBObjectSearch('Trigger');
$aParams = array();
$oBlock = new DisplayBlock($oFilter, 'list', false, $aParams);
$oBlock->Display($oP, 'block_0', $aParams);


$aActionClasses = array();
foreach(MetaModel::EnumChildClasses('Action', ENUM_CHILD_CLASSES_EXCLUDETOP) as $sActionClass)
{
	if (!MetaModel::IsAbstract($sActionClass))
	{
		$aActionClasses[] = $sActionClass;
	}
}

$oP->SetCurrentTab(Dict::S('UI:NotificationsMenu:Actions'));

if (count($aActionClasses) == 1)
{
	// Preserve old style
	$oP->add('<h2>'.Dict::S('UI:NotificationsMenu:AvailableActions').'</h2>');
}

$iBlock = 0;
foreach($aActionClasses as $sActionClass)
{
	if (count($aActionClasses) > 1)
	{
		// New style
		$oP->add('<h2>'.MetaModel::GetName($sActionClass).'</h2>');
	}
	$oFilter = new DBObjectSearch($sActionClass);
	$aParams = array();
	$oBlock = new DisplayBlock($oFilter, 'list', false, $aParams);
	$oBlock->Display($oP, 'block_action_'.$iBlock, $aParams);
	$iBlock++;
}

$oP->SetCurrentTab('');
$oP->SetCurrentTabContainer('');

$oP->output();