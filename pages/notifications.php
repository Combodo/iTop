<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
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


use Combodo\iTop\Application\UI\Base\Component\CollapsibleSection\CollapsibleSection;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Layout\PageContent\PageContentFactory;

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');

require_once(APPROOT.'/application/startup.inc.php');
require_once(APPROOT.'/application/loginwebpage.class.inc.php');
LoginWebPage::DoLogin(); // Check user rights and prompt if needed
ApplicationMenu::CheckMenuIdEnabled("NotificationsMenu");

// Main program
//
$oP = new iTopWebPage(Dict::S('Menu:NotificationsMenu+'));
$oP->SetBreadCrumbEntry('ui-tool-notifications', Dict::S('Menu:NotificationsMenu'), Dict::S('Menu:NotificationsMenu+'), '', 'fas fa-bell',
	iTopWebPage::ENUM_BREADCRUMB_ENTRY_ICON_TYPE_CSS_CLASSES);

$oPageContentLayout = PageContentFactory::MakeStandardEmpty();
$oP->SetContentLayout($oPageContentLayout);

$sAlertTitle = Dict::S('UI:NotificationsMenu:Title');
$sAlertContent = Dict::S('UI:NotificationsMenu:HelpContent');
$oConfigurationHelp = new CollapsibleSection($sAlertTitle, [new Html($sAlertContent)]);
$oConfigurationHelp
	->SetOpenedByDefault(true)
	->EnableSaveCollapsibleState('notifications__home');
$oPageContentLayout->AddMainBlock($oConfigurationHelp);

$oP->AddTabContainer('Tabs_0');
$oP->SetCurrentTabContainer('Tabs_0');

$oP->SetCurrentTab('UI:NotificationsMenu:Triggers');
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

$oP->SetCurrentTab('UI:NotificationsMenu:Actions');

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
	$oFilter->AddCondition('finalclass', $sActionClass); // derived classes will be further processed
	$aParams = array();
	$oBlock = new DisplayBlock($oFilter, 'list', false, $aParams);
	$oBlock->Display($oP, 'block_action_'.$iBlock, $aParams);
	$iBlock++;
}

$oP->SetCurrentTab('');
$oP->SetCurrentTabContainer('');

$oP->output();
