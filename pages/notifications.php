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


use Combodo\iTop\Application\UI\Base\Component\CollapsibleSection\CollapsibleSection;
use Combodo\iTop\Application\UI\Base\Component\Html\HtmlFactory;
use Combodo\iTop\Application\UI\Base\Layout\PageContent\PageContentFactory;
use Combodo\iTop\Application\WebPage\iTopWebPage;

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');

require_once(APPROOT.'/application/startup.inc.php');
require_once(APPROOT.'/application/loginwebpage.class.inc.php');
IssueLog::Trace('----- Request: '.utils::GetRequestUri(), LogChannels::WEB_REQUEST);
LoginWebPage::DoLogin(); // Check user rights and prompt if needed
ApplicationMenu::CheckMenuIdEnabled("NotificationsMenu");

/**
 * @param iTopWebPage $oP
 * @param string $sClassToDisplay
 * @param array $aClassesToExclude
 *
 * @throws \ApplicationException
 * @throws \CoreException
 * @throws \DictExceptionMissingString
 * @since 3.0.0
 */
function DisplayActionsTab(iTopWebPage &$oP, string $sClassToDisplay, array $aClassesToExclude = []): void
{
	// Check if class exists
	if (! MetaModel::IsValidClass($sClassToDisplay)) {
		return;
	}

	$aActionClasses = array();
	foreach(MetaModel::EnumChildClasses($sClassToDisplay, ENUM_CHILD_CLASSES_ALL, true) as $sActionClass) {
		// Ignore abstract classes
		if (MetaModel::IsAbstract($sActionClass)) {
			continue;
		}

		// Ignore specific classes
		foreach ($aClassesToExclude as $sClassToExclude) {
			if (is_a($sActionClass, $sClassToExclude, true)) {
				continue 2;
			}
		}

		$aActionClasses[] = $sActionClass;
	}

	// Don't display tab if no action class
	if (count($aActionClasses) === 0) {
		return;
	}

	$oP->SetCurrentTab('UI:NotificationsMenu:Actions:'.$sClassToDisplay);

	$iBlock = 0;
	foreach($aActionClasses as $sActionClass)
	{
		$oFilter = new DBObjectSearch($sActionClass);
		$oFilter->AddCondition('finalclass', $sActionClass); // derived classes will be further processed

		$aParams = array('panel_title' => MetaModel::GetName($sActionClass));

		$sBlockId = 'block_'.utils::Sanitize($sClassToDisplay, '', utils::ENUM_SANITIZATION_FILTER_ELEMENT_IDENTIFIER).'_'.$iBlock;
		$oBlock = new DisplayBlock($oFilter, 'list', false, $aParams);
		$oBlock->Display($oP, $sBlockId, $aParams);
		$iBlock++;
	}
}

// Main program
//
$oP = new iTopWebPage(Dict::S('Menu:NotificationsMenu+'));
$oP->SetBreadCrumbEntry('ui-tool-notifications', Dict::S('Menu:NotificationsMenu'), Dict::S('Menu:NotificationsMenu+'), '', 'fas fa-bell',
	iTopWebPage::ENUM_BREADCRUMB_ENTRY_ICON_TYPE_CSS_CLASSES);

$oPageContentLayout = PageContentFactory::MakeStandardEmpty();
$oP->SetContentLayout($oPageContentLayout);

$sAlertTitle = Dict::S('UI:NotificationsMenu:Title');
$sAlertContent = Dict::S('UI:NotificationsMenu:HelpContent');
$oConfigurationHelp = new CollapsibleSection($sAlertTitle, [HtmlFactory::MakeHtmlContent($sAlertContent)]);
$oConfigurationHelp
	->SetOpenedByDefault(true)
	->EnableSaveCollapsibleState('notifications__home');
$oPageContentLayout->AddMainBlock($oConfigurationHelp);

/*************************************
 *           Triggers tab
 ************************************/

$oP->AddTabContainer('Tabs_0');
$oP->SetCurrentTabContainer('Tabs_0');

$oP->SetCurrentTab('UI:NotificationsMenu:Triggers');

$oFilter = new DBObjectSearch('Trigger');
$aParams = array('panel_title' => Dict::S('UI:NotificationsMenu:AvailableTriggers'));
$oBlock = new DisplayBlock($oFilter, 'list', false, $aParams);
$oBlock->Display($oP, 'block_0', $aParams);

/*************************************
 *           Actions tabs
 ************************************/

DisplayActionsTab($oP, 'ActionEmail');
DisplayActionsTab($oP, 'ActionWebhook');
DisplayActionsTab($oP, 'Action', ['ActionEmail', 'ActionWebhook']);

/*************************************
 *           End reset
 ************************************/

$oP->SetCurrentTab('');
$oP->SetCurrentTabContainer('');

$oP->output();
