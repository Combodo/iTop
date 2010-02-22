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
$sOQLClass = utils::ReadParam('oql_class', 'bizOrganization');
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
	$sMenuNodeContent = <<<EOF
<div id="TopPane">
<itopblock BlockClass="DisplayBlock" type="search" asynchronous="false" encoding="text/oql">$sFilter</itopblock>
</div>
<div id="BottomPane">
<p></p>
<itopblock BlockClass="DisplayBlock" type="list" asynchronous="false" encoding="text/oql">$sFilter</itopblock>
</div>
EOF;


	if ($sOperation == "add_menu")
	{
		$oMenuNode = MetaModel::NewObject('menuNode');
		$sClass = utils::ReadPostedParam('class', '');
		$sLabel = utils::ReadPostedParam('label', '');
		$sDescription = utils::ReadPostedParam('description', '');
		$iPreviousNodeId = utils::ReadPostedParam('previous_node_id', 1);
		$bChildItem = utils::ReadPostedParam('child_item', false);
		$oMenuNode->Set('label', $sDescription);
		$oMenuNode->Set('name', $sLabel);
		$oMenuNode->Set('icon_path', '../images/std_view.gif');
		$oMenuNode->Set('template', $sMenuNodeContent);
		$oMenuNode->Set('hyperlink', 'UI.php');
		$oMenuNode->Set('type', 'user');
		$oMenuNode->Set('user_id', UserRights::GetUserId());
		$oPreviousNode = MetaModel::GetObject('menuNode', $iPreviousNodeId);
		if ($bChildItem)
		{
			// Insert the new item as a child of the previous one
			$oMenuNode->Set('parent_id', $iPreviousNodeId);
			$oMenuNode->Set('rank', 1); // A new child item is the first one, so let's start the numbering at 1
			// If there are already child nodes, shift their rank by one
			// to make room for the newly inserted child node
			$oNextNodeSet = $oPreviousNode->GetChildNodesSet(null); // null => don't limit ourselves to the user context
																	// since we need to update all children in order to keep
																	// the database consistent 
			while($oNextNode = $oNextNodeSet->Fetch())
			{
				$oNextNode->Set('rank', 1 + $oNextNode->Get('rank'));
				$oNextNode->DBUpdate();
			}
		}
		else
		{
			// Insert the new item as the next sibling of the previous one
			$oMenuNode->Set('parent_id', $oPreviousNode->Get('parent_id'));
			$oMenuNode->Set('rank', 1 +  $oPreviousNode->Get('rank')); // the new item comes immediatly after the selected one
			// Add 1 to the rank of all the nodes currently following the 'selected' one
			// to make room for the newly inserted node
			$oNextNodeSet = $oPreviousNode->GetNextNodesSet(null);	// null => don't limit ourselves to the user context
																	// since we need to update all children in order to keep
																	// the database consistent 
			while($oNextNode = $oNextNodeSet->Fetch())
			{
				$oNextNode->Set('rank', 1 + $oNextNode->Get('rank'));
				$oNextNode->DBUpdate();
			}

		}
		list($bRes, $aIssues) = $oMenuNode->CheckToInsert();
		if ($bRes)
		{
			$oMenuNode->DBInsert();
			$oP->add("<form method=\"get\">");
			$oP->add("<p>Menu item created !</p>");
			$oP->add("<input type=\"hidden\" name=\"filter\" value=\"$sFilter\">");
			$oP->add("<input type=\"hidden\" name=\"class\" value=\"$sClassName\">");
			$oP->add("<input type=\"submit\" name=\"\" value=\"Reload Page\">");
			$oP->add("<form>");
		}
	}
	
	$oP->add("</div>\n");
}
else
{
	$oP->add("</div>\n");
}

$oP->output();
?>
