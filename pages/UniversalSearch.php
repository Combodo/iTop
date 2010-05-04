<?php
require_once('../application/application.inc.php');
require_once('../application/itopwebpage.class.inc.php');
require_once('../application/applicationcontext.class.inc.php');

require_once('../application/startup.inc.php');

require_once('../application/loginwebpage.class.inc.php');
LoginWebPage::DoLogin(); // Check user rights and prompt if needed

$oContext = new UserContext();
$oAppContext = new ApplicationContext();
$iActiveNodeId = utils::ReadParam('menu', -1);
$currentOrganization = utils::ReadParam('org_id', '');

$oP = new iTopWebPage(Dict::S('UI:UniversalSearchTitle'), $currentOrganization);

// From now on the context is limited to the the selected organization ??

// Now render the content of the page
$sBaseClass = utils::ReadParam('baseClass', 'bizOrganization');
$sClass = utils::ReadParam('class', $sBaseClass);
$sOQLClause = utils::ReadParam('oql_clause', '');
$sFilter = utils::ReadParam('filter', '');
$sOperation = utils::ReadParam('operation', '');

// First part: select the class to search for
$oP->add("<form>");
$oP->add("<input type=\"hidden\" name=\"org_id\" value=\"$currentOrganization\" />");
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
$oP->add("</select></form>");

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
