<?php
require_once('../application/application.inc.php');
require_once('../application/itopwebpage.class.inc.php');

require_once('../application/startup.inc.php');
$currentOrganization = utils::ReadParam('org_id', '');
$operation = utils::ReadParam('operation', '');
$oAppContext = new ApplicationContext();

require_once('../application/loginwebpage.class.inc.php');
LoginWebPage::DoLogin(); // Check user rights and prompt if needed

$oP = new iTopWebPage("iTop - CMDB Audit", $currentOrganization);

function GetRuleResultSet($iRuleId, $oDefinitionFilter)
{
	$oContext = new UserContext();
	
	$oRule = $oContext->GetObject('AuditRule', $iRuleId);
	$sSibusql = $oRule->Get('query');
	$oRuleFilter = DBObjectSearch::FromSibusQL($sSibusql);
	if ($oRule->Get('valid_flag') == 'false')
	{
		// The query returns directly the invalid elements
		$oFilter = $oRuleFilter; 
		$oFilter->MergeWith($oDefinitionFilter);
		$oErrorObjectSet = new CMDBObjectSet($oFilter);
	}
	else
	{
		// The query returns only the valid elements, all the others are invalid
		$oFilter = $oRuleFilter; 
		$oErrorObjectSet = new CMDBObjectSet($oFilter);
		$aValidIds = array(0); // Make sure that we have at least one value in the list
		while($oObj = $oErrorObjectSet->Fetch())
		{
			$aValidIds[] = $oObj->GetKey(); 
		}
		$oFilter = $oDefinitionFilter;
		$oFilter->AddCondition('pkey', $aValidIds, 'NOTIN');
		$oErrorObjectSet = new CMDBObjectSet($oFilter);
	}
	return $oErrorObjectSet;
}

function GetReportColor($iTotal, $iErrors)
{
	$sResult = 'red';
	if ( ($iTotal == 0) || ($iErrors / $iTotal) <= 0.05 )
	{
		$sResult = 'green';
	}
	else if ( ($iErrors / $iTotal) <= 0.25 )
	{
		$sResult = 'orange';
	}
	return $sResult;
}

switch($operation)
{
	case 'errors':
	$iCategory = utils::ReadParam('category', '');
	$iRuleIndex = utils::ReadParam('rule', 0);

	$oContext = new UserContext();
	$oAuditCategory = $oContext->GetObject('AuditCategory', $iCategory);
	$oDefinitionFilter = DBObjectSearch::FromSibusQL($oAuditCategory->Get('definition_set'));
	if (!empty($currentOrganization))
	{
		$oDefinitionFilter->AddCondition('org_id', $currentOrganization);
	}
	$oDefinitionSet = new CMDBObjectSet($oDefinitionFilter);
	$oErrorObjectSet = GetRuleResultSet($iRuleIndex, $oDefinitionFilter);
	$oAuditRule = $oContext->GetObject('AuditRule', $iRuleIndex);
	$oP->add('<div class="page_header"><h1>Audit Errors: <span class="hilite">'.$oAuditRule->Get('description').'</span></h1><img style="margin-top: -20px; margin-right: 10px; float: right;" src="../images/stop.png"/></div>');
	$oP->p('<a href="./audit.php?'.$oAppContext->GetForLink().'">[Back to audit results]</a>');
	cmdbAbstractObject::DisplaySet($oP, $oErrorObjectSet);
	break;
	
	case 'audit':
	default:
	$oP->add('<div class="page_header"><h1>Interactive Audit</h1><img style="margin-top: -20px; margin-right: 10px; float: right;" src="../images/clean.png"/></div>');
	$oAuditFilter = new CMDBSearchFilter('AuditCategory');
	$oCategoriesSet = new DBObjectSet($oAuditFilter);
	$oP->add("<table style=\"margin-top: 1em; padding: 0px; border-top: 3px solid #f6f6f1; border-left: 3px solid #f6f6f1; border-bottom: 3px solid #e6e6e1;	border-right: 3px solid #e6e6e1;\">\n");
	$oP->add("<tr><td>\n");
	$oP->add("<table>\n");
	$oP->add("<tr>\n");
	$oP->add("<th><img src=\"../images/minus.gif\"></th><th class=\"alignLeft\">Audit Rule</th><th># Objects</th><th># Errors</th><th>% Ok</th>\n");
	$oP->add("</tr>\n");
	while($oAuditCategory = $oCategoriesSet->fetch())
	{
		$oDefinitionFilter = DBObjectSearch::FromSibusQL($oAuditCategory->Get('definition_set'));
		$aObjectsWithErrors = array();
		if (!empty($currentOrganization))
		{
			$oDefinitionFilter->AddCondition('org_id', $currentOrganization);
		}
		$aResults = array();
		$oDefinitionSet = new CMDBObjectSet($oDefinitionFilter);
		$iCount = $oDefinitionSet->Count();
		$oRulesFilter = new CMDBSearchFilter('AuditRule');
		$oRulesFilter->AddCondition('category_id', $oAuditCategory->GetKey());
		$oRulesSet = new DBObjectSet($oRulesFilter);
		while($oAuditRule = $oRulesSet->fetch() )
		{
			$aRow = array();
			$aRow['description'] = $oAuditRule->Get('name');
			if ($iCount == 0)
			{
				// nothing to check, really !
				$aRow['nb_errors'] = "<a href=\"?operation=errors&category=".$oAuditCategory->GetKey()."&rule=".$oAuditRule->GetKey()."\">0</a>"; 
				$aRow['percent_ok'] = '100.00';
				$aRow['class'] = GetReportColor($iCount, 0);
			}
			else
			{
				$oRuleFilter = DBObjectSearch::FromSibusQL($oAuditRule->Get('query'));
				$oErrorObjectSet = GetRuleResultSet($oAuditRule->GetKey(), $oDefinitionFilter);
				$iErrorsCount = $oErrorObjectSet->Count();
				while($oObj = $oErrorObjectSet->Fetch())
				{
					$aObjectsWithErrors[$oObj->GetKey()] = true;
				}
				$aRow['nb_errors'] = ($iErrorsCount == 0) ? '0' : "<a href=\"?operation=errors&category=".$oAuditCategory->GetKey()."&rule=".$oAuditRule->GetKey()."&".$oAppContext->GetForLink()."\">$iErrorsCount</a>"; 
				$aRow['percent_ok'] = sprintf('%.2f', 100.0 * (($iCount - $iErrorsCount) / $iCount));
				$aRow['class'] = GetReportColor($iCount, $iErrorsCount);
			}
			$aResults[] = $aRow;
			$iTotalErrors = count($aObjectsWithErrors);
			$sOverallPercentOk = ($iCount == 0) ? '100.00' : sprintf('%.2f', 100.0 * (($iCount - $iTotalErrors) / $iCount));
			$sClass = GetReportColor($iCount, $iTotalErrors);

		}
		$oP->add("<tr>\n");
		$oP->add("<th><img src=\"../images/minus.gif\"></th><th class=\"alignLeft\">".$oAuditCategory->GetName()."</th><th class=\"alignRight\">$iCount</th><th class=\"alignRight\">$iTotalErrors</th><th class=\"alignRight $sClass\">$sOverallPercentOk %</th>\n");
		$oP->add("</tr>\n");
		foreach($aResults as $aRow)
		{
			$oP->add("<tr>\n");
			$oP->add("<td>&nbsp;</td><td colspan=\"2\">".$aRow['description']."</td><td class=\"alignRight\">".$aRow['nb_errors']."</td><td class=\"alignRight ".$aRow['class']."\">".$aRow['percent_ok']." %</td>\n");
			$oP->add("</tr>\n");
		}
	}
	$oP->add("</table>\n");
	$oP->add("</td></tr>\n");
	$oP->add("</table>\n");
}
	
$oP->output();
?>
