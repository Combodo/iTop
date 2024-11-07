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

use Combodo\iTop\Application\WebPage\iTopWebPage;

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/applicationcontext.class.inc.php');

require_once(APPROOT.'/application/startup.inc.php');

require_once(APPROOT.'/application/loginwebpage.class.inc.php');
IssueLog::Trace('----- Request: '.utils::GetRequestUri(), LogChannels::WEB_REQUEST);
LoginWebPage::DoLogin(); // Check user rights and prompt if needed
ApplicationMenu::CheckMenuIdEnabled('UniversalSearchMenu');

$oAppContext = new ApplicationContext();

$oP = new iTopWebPage(Dict::S('UI:UniversalSearchTitle'));
$oP->LinkScriptFromAppRoot("js/forms-json-utils.js");
$oP->LinkScriptFromAppRoot("js/wizardhelper.js");
$oP->LinkScriptFromAppRoot("js/wizard.utils.js");
$oP->LinkScriptFromAppRoot("js/extkeywidget.js");
$oP->LinkScriptFromAppRoot("js/jquery.blockUI.js");
		
// From now on the context is limited to the the selected organization ??

// Now render the content of the page
$sBaseClass = utils::ReadParam('baseClass', 'Organization', false, 'class');
$sClass = utils::ReadParam('class', $sBaseClass, false, 'class');
$sOQLClause = utils::ReadParam('oql_clause', '', false, 'raw_data');
$sFilter = utils::ReadParam('filter', '', false, 'raw_data');
$sOperation = utils::ReadParam('operation', '');

$oP->SetBreadCrumbEntry('ui-tool-universalsearch', Dict::S('Menu:UniversalSearchMenu'), Dict::S('Menu:UniversalSearchMenu+'), '', 'fas fa-search', iTopWebPage::ENUM_BREADCRUMB_ENTRY_ICON_TYPE_CSS_CLASSES);



//$sSearchHeaderForceDropdown
$sSearchHeaderForceDropdown = '<select  id="select_class" name="baseClass" onChange="this.form.submit();">';
$aClassLabels = array();
foreach(MetaModel::GetClasses('bizmodel') as $sCurrentClass)
{
	$aClassLabels[$sCurrentClass] = MetaModel::GetName($sCurrentClass);
}
asort($aClassLabels);
foreach($aClassLabels as $sCurrentClass => $sLabel)
{
	$sDescription = MetaModel::GetClassDescription($sCurrentClass);
	$sSelected = ($sCurrentClass == $sBaseClass) ? " SELECTED" : "";
	$sSearchHeaderForceDropdown .= "<option value=\"$sCurrentClass\" title=\"$sDescription\"$sSelected>$sLabel</option>";
}
$sSearchHeaderForceDropdown .= "</select>\n";
//end of $sSearchHeaderForceDropdown


try 
{
	if ($sOperation == 'search_form')
	{
			$sOQL = "SELECT $sClass $sOQLClause";
			$oFilter = DBObjectSearch::FromOQL($sOQL);
	}
	else
	{
		// Second part: advanced search form:
		if (!empty($sFilter))
		{
			$oFilter = DBSearch::unserialize($sFilter);
		}
		else if (!empty($sClass))
		{
			$oFilter = new DBObjectSearch($sClass);
		}
	}
}
catch (CoreException $e)
{
	$oFilter = new DBObjectSearch($sClass);
	$oP->P("<b>".Dict::Format('UI:UniversalSearch:Error', $e->getHtmlDesc())."</b>");
}

if ($oFilter != null)
{
	$oSet = new CMDBObjectSet($oFilter);
	$oBlock = new DisplayBlock($oFilter, 'search', false);
	$aExtraParams = $oAppContext->GetAsHash();
	$aExtraParams['open'] = true;
	$aExtraParams['baseClass'] = $sBaseClass;
	$aExtraParams['action'] = utils::GetAbsoluteUrlAppRoot().'pages/UniversalSearch.php';
	$aExtraParams['table_id'] = '1';
	$aExtraParams['search_header_force_dropdown'] = $sSearchHeaderForceDropdown;
	$aExtraParams['submit_on_load'] = false;
	$oBlock->Display($oP, 0, $aExtraParams);

	// Search results	
	$oResultBlock = new DisplayBlock($oFilter, 'list', false);
	$oResultBlock->Display($oP, 1);

	// Breadcrumb
	//$iCount = $oBlock->GetDisplayedCount();
	$sPageId = "ui-search-".$oFilter->GetClass();
	$sLabel = MetaModel::GetName($oFilter->GetClass());
	$oP->SetBreadCrumbEntry($sPageId, $sLabel, '', '', 'fas fa-search', iTopWebPage::ENUM_BREADCRUMB_ENTRY_ICON_TYPE_CSS_CLASSES);

	// Menu node
	$sFilter = $oFilter->ToOQL();
	$oP->add("\n<!-- $sFilter -->\n");
}
$oP->add("</div>\n");
$oP->output();
