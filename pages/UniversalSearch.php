<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * Analytical search
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/itopwebpage.class.inc.php');
require_once(APPROOT.'/application/applicationcontext.class.inc.php');

require_once(APPROOT.'/application/startup.inc.php');

require_once(APPROOT.'/application/loginwebpage.class.inc.php');
LoginWebPage::DoLogin(true); // Check user rights and prompt if needed (must be admin)

$oAppContext = new ApplicationContext();

$oP = new iTopWebPage(Dict::S('UI:UniversalSearchTitle'));
$oP->add_linked_script("../js/json.js");
$oP->add_linked_script("../js/forms-json-utils.js");
$oP->add_linked_script("../js/wizardhelper.js");
$oP->add_linked_script("../js/wizard.utils.js");
$oP->add_linked_script("../js/linkswidget.js");
$oP->add_linked_script("../js/extkeywidget.js");
$oP->add_linked_script("../js/jquery.blockUI.js");
		
// From now on the context is limited to the the selected organization ??

// Now render the content of the page
$sBaseClass = utils::ReadParam('baseClass', 'Organization', false, 'class');
$sClass = utils::ReadParam('class', $sBaseClass, false, 'class');
$sOQLClause = utils::ReadParam('oql_clause', '', false, 'raw_data');
$sFilter = utils::ReadParam('filter', '', false, 'raw_data');
$sOperation = utils::ReadParam('operation', '');

// First part: select the class to search for
$oP->add("<form>");
$oP->add(Dict::S('UI:UniversalSearch:LabelSelectTheClass')."<select style=\"width: 150px;\" id=\"select_class\" name=\"baseClass\" onChange=\"this.form.submit();\">");
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
	$oP->add("<option value=\"$sCurrentClass\" title=\"$sDescription\"$sSelected>$sLabel</option>");
}
$oP->add("</select>\n");
$oP->add($oAppContext->GetForForm());
$oP->add("</form>\n");

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
			$oFilter = CMDBSearchFilter::unserialize($sFilter);
		}
		else if (!empty($sClass))
		{
			$oFilter = new CMDBSearchFilter($sClass);
		}
	}
}
catch (CoreException $e)
{
	$oFilter = new CMDBSearchFilter($sClass);
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
	//$aExtraParams['class'] = $sClassName;
	$oBlock->Display($oP, 0, $aExtraParams);

	// Search results	
	$oResultBlock = new DisplayBlock($oFilter, 'list', false);
	$oResultBlock->Display($oP, 1);
	
	// Menu node
	$sFilter = $oFilter->ToOQL();
	$oP->add("\n<!-- $sFilter -->\n");
}
$oP->add("</div>\n");
$oP->output();
?>
