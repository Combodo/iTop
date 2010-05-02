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
$currentOrganization = utils::ReadParam('org_id', 1);

$oP = new iTopWebPage("iTop - Universal search", $currentOrganization);

// From now on the context is limited to the the selected organization ??

// Now render the content of the page
$sClass = utils::ReadParam('class', 'bizOrganization');
$sOQLClass = utils::ReadParam('oql_class', $sClass);
$sOQLClause = utils::ReadParam('oql_clause', '');
$sClassName = $sOQLClass; //utils::ReadParam('class', $sOQLClass);
$sFilter = utils::ReadParam('filter', '');
$sOperation = utils::ReadParam('operation', '');

// First part: select the class to search for
$oP->add("<form>");
$oP->add("<input type=\"hidden\" name=\"org_id\" value=\"$currentOrganization\" />");
$oP->add("Select the class to search: <select style=\"width: 150px;\" id=\"select_class\" name=\"oql_class\" onChange=\"this.form.submit();\">");
$aClassLabels = array();
foreach(MetaModel::GetClasses('bizmodel') as $sClass)
{
	$aClassLabels[$sClass] = MetaModel::GetName($sClass);
}
asort($aClassLabels);
foreach($aClassLabels as $sClass => $sLabel)
{
	$sDescription = MetaModel::GetClassDescription($sClass);
	$sSelected = ($sClass == $sClassName) ? " SELECTED" : "";
	$oP->add("<option value=\"$sClass\" title=\"$sDescription\"$sSelected>$sLabel</option>");
}
$oP->add("</select></form>");

try 
{
	if ($sOperation == 'search_form')
	{
			$sOQL = "SELECT $sOQLClass $sOQLClause";
			$oFilter = DBObjectSearch::FromOQL($sOQL);
	}
	else
	{
		// Second part: advanced search form:
		if (!empty($sFilter))
		{
			$oFilter = CMDBSearchFilter::unserialize($sFilter);
		}
		else if (!empty($sClassName))
		{
			$oFilter = new CMDBSearchFilter($sClassName);
		}
	}
}
catch (CoreException $e)
{
	$oFilter = new CMDBSearchFilter($sClassName);
	$oP->P("<b>Error:</b>");
	$oP->P($e->getHtmlDesc());
}

if ($oFilter != null)
{
	$oSet = new CMDBObjectSet($oFilter);
	$oBlock = new DisplayBlock($oFilter, 'search', false);
	$aExtraParams = $oAppContext->GetAsHash();
	$aExtraParams['open'] = true;
	$aExtraParams['oql_class'] = $sOQLClass;
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
