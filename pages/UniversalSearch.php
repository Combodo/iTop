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

/**
 * Analytical search
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
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
$sBaseClass = utils::ReadParam('baseClass', 'Organization');
$sClass = utils::ReadParam('class', $sBaseClass);
$sOQLClause = utils::ReadParam('oql_clause', '');
$sFilter = utils::ReadParam('filter', '');
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
