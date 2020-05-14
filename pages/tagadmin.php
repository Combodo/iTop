<?php
/**
 * Copyright (C) 2013-2019 Combodo SARL
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

require_once('../approot.inc.php');
require_once(APPROOT.'application/application.inc.php');
require_once(APPROOT.'application/itopwebpage.class.inc.php');
require_once(APPROOT.'application/startup.inc.php');
require_once(APPROOT.'application/loginwebpage.class.inc.php');

try
{
	LoginWebPage::DoLogin();
	// Check user rights and prompt if needed
	ApplicationMenu::CheckMenuIdEnabled("TagAdminMenu");

	$oAppContext = new ApplicationContext();

	// Main program
	//
	$oP = new iTopWebPage(Dict::S('Menu:TagAdminMenu+'));
	$oP->add_linked_script("../js/json.js");
	$oP->add_linked_script("../js/forms-json-utils.js");
	$oP->add_linked_script("../js/wizardhelper.js");
	$oP->add_linked_script("../js/wizard.utils.js");
	$oP->add_linked_script("../js/linkswidget.js");
	$oP->add_linked_script("../js/extkeywidget.js");
	$oP->add_linked_script("../js/jquery.blockUI.js");

	$sBaseClass = 'TagSetFieldData';
	$sClass = utils::ReadParam('class', '', false, 'class');
	$sOQLClause = utils::ReadParam('oql_clause', '', false, 'raw_data');
	$sFilter = utils::ReadParam('filter', '', false, 'raw_data');
	$sOperation = utils::ReadParam('operation', '');

	$oP->add('<div class="page_header" style="padding:0.5em;">');
	$oP->add('<h1>'.dict::S('UI:TagAdminMenu:Title').'</h1>');
	$oP->add('</div>');

	$oP->SetBreadCrumbEntry('ui-tool-tag-admin', Dict::S('Menu:TagAdminMenu'), Dict::S('Menu:TagAdminMenu+'), '', utils::GetAbsoluteUrlAppRoot().'images/wrench.png');

	$sSearchHeaderForceDropdown = '<select  id="select_class" name="class" onChange="this.form.submit();">';
	$aClassLabels = array();
	foreach(MetaModel::EnumChildClasses($sBaseClass, ENUM_CHILD_CLASSES_EXCLUDETOP) as $sCurrentClass)
	{
		$aClassLabels[$sCurrentClass] = MetaModel::GetName($sCurrentClass);
	}
	asort($aClassLabels);
	foreach($aClassLabels as $sCurrentClass => $sLabel)
	{
		if (empty($sClass))
		{
			$sClass = $sCurrentClass;
		}
		$sSelected = ($sCurrentClass == $sClass) ? " SELECTED" : "";
		$sSearchHeaderForceDropdown .= "<option value=\"$sCurrentClass\" title=\"$sLabel\" $sSelected>$sLabel</option>";
	}
	$sSearchHeaderForceDropdown .= "</select>\n";

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
		$oP->P("<b>".Dict::Format('UI:TagSetFieldData:Error', $e->getHtmlDesc())."</b>");
	}

	if (!empty($oFilter))
	{
		$oSet = new CMDBObjectSet($oFilter);
		$oBlock = new DisplayBlock($oFilter, 'search', false);
		$aExtraParams = $oAppContext->GetAsHash();
		$aExtraParams['open'] = true;
		$aExtraParams['class'] = $sClass;
		$aExtraParams['action'] = utils::GetAbsoluteUrlAppRoot().'pages/tagadmin.php';
		$aExtraParams['table_id'] = '1';
		$aExtraParams['search_header_force_dropdown'] = $sSearchHeaderForceDropdown;
		$oBlock->Display($oP, 0, $aExtraParams);

		// Search results
		$oResultBlock = new DisplayBlock($oFilter, 'list', false);
		$oResultBlock->Display($oP, 1);

		// Menu node
		$sFilter = $oFilter->ToOQL();
		$oP->add("\n<!-- $sFilter -->\n");
	}
	else
	{
		$oP->add("<p>");
		$oP->add(Dict::S('UI:TagAdminMenu:NoTags'));
		$oP->add("</p>");
	}
	$oP->add("</div>\n");

	$oP->output();
}
catch (Exception $e)
{
	require_once(APPROOT.'setup/setuppage.class.inc.php');

	$oP = new ErrorPage(Dict::S('UI:PageTitle:FatalError'));
	$oP->add("<h1>".Dict::S('UI:FatalErrorMessage')."</h1>\n");
	//$oP->error(Dict::Format('UI:Error_Details', $e->getMessage()));
	$oP->output();

	IssueLog::Error($e->getMessage());
}
