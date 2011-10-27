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
 * Execute and shows the data quality audit
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */
try
{
	require_once('../approot.inc.php');
	require_once(APPROOT.'/application/application.inc.php');
	require_once(APPROOT.'/application/itopwebpage.class.inc.php');
	
	require_once(APPROOT.'/application/startup.inc.php');
	$operation = utils::ReadParam('operation', '');
	$oAppContext = new ApplicationContext();
	
	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	LoginWebPage::DoLogin(); // Check user rights and prompt if needed
	
	$oP = new iTopWebPage(Dict::S('UI:Audit:Title'));
	
	/**
	 * Adds the context parameters to the audit query
	 */
	function FilterByContext(DBObjectSearch &$oFilter, ApplicationContext $oAppContext)
	{
		$sObjClass = $oFilter->GetClass();		
		$aContextParams = $oAppContext->GetNames();
		if (is_callable("$sObjClass::MapContextParam"))
		{
			foreach($aContextParams as $sParamName)
			{
				$sValue = $oAppContext->GetCurrentValue($sParamName, null);
				if ($sValue != null)
				{
					$sAttCode = eval("return $sObjClass::MapContextParam('$sParamName');"); // Returns null when there is no mapping for this parameter
					if ($sAttCode != null)
					{
						$oFilter->AddCondition($sAttCode, $sValue);
					}
				}
			}
		}
	}

	function GetRuleResultSet($iRuleId, $oDefinitionFilter, $oAppContext)
	{
		$oRule = MetaModel::GetObject('AuditRule', $iRuleId);
		$sOql = $oRule->Get('query');
		$oRuleFilter = DBObjectSearch::FromOQL($sOql);
		FilterByContext($oRuleFilter, $oAppContext); // Not needed since this filter is a subset of the definition filter, but may speedup things

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
			$oFilter = clone $oDefinitionFilter;
			$oFilter->AddCondition('id', $aValidIds, 'NOTIN');
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
	
		$oAuditCategory = MetaModel::GetObject('AuditCategory', $iCategory);
		$oDefinitionFilter = DBObjectSearch::FromOQL($oAuditCategory->Get('definition_set'));
		FilterByContext($oDefinitionFilter, $oAppContext);
		$oDefinitionSet = new CMDBObjectSet($oDefinitionFilter);
		$oErrorObjectSet = GetRuleResultSet($iRuleIndex, $oDefinitionFilter, $oAppContext);
		$oAuditRule = MetaModel::GetObject('AuditRule', $iRuleIndex);
		$oP->add('<div class="page_header"><h1>Audit Errors: <span class="hilite">'.$oAuditRule->Get('description').'</span></h1><img style="margin-top: -20px; margin-right: 10px; float: right;" src="../images/stop.png"/></div>');
		$oP->p('<a href="./audit.php?'.$oAppContext->GetForLink().'">[Back to audit results]</a>');
	    $sBlockId = 'audit_errors';
		$oP->p("<div id=\"$sBlockId\" style=\"clear:both\">\n");
		$oBlock = DisplayBlock::FromObjectSet($oErrorObjectSet, 'list');    
		$oBlock->Display($oP, 1);
		$oP->p("</div>\n");    
		break;
		
		case 'audit':
		default:
		$oP->add('<div class="page_header"><h1>'.Dict::S('UI:Audit:InteractiveAudit').'</h1><img style="margin-top: -20px; margin-right: 10px; float: right;" src="../images/clean.png"/></div>');
		$oAuditFilter = new CMDBSearchFilter('AuditCategory');
		$oCategoriesSet = new DBObjectSet($oAuditFilter);
		$oP->add("<table style=\"margin-top: 1em; padding: 0px; border-top: 3px solid #f6f6f1; border-left: 3px solid #f6f6f1; border-bottom: 3px solid #e6e6e1;	border-right: 3px solid #e6e6e1;\">\n");
		$oP->add("<tr><td>\n");
		$oP->add("<table>\n");
		$oP->add("<tr>\n");
		$oP->add("<th><img src=\"../images/minus.gif\"></th><th class=\"alignLeft\">".Dict::S('UI:Audit:HeaderAuditRule')."</th><th>".Dict::S('UI:Audit:HeaderNbObjects')."</th><th>".Dict::S('UI:Audit:HeaderNbErrors')."</th><th>".Dict::S('UI:Audit:PercentageOk')."</th>\n");
		$oP->add("</tr>\n");
		while($oAuditCategory = $oCategoriesSet->fetch())
		{
			$oDefinitionFilter = DBObjectSearch::FromOQL($oAuditCategory->Get('definition_set'));
			FilterByContext($oDefinitionFilter, $oAppContext);
			
			$aObjectsWithErrors = array();
			if (!empty($currentOrganization))
			{
				if (MetaModel::IsValidFilterCode($oDefinitionFilter->GetClass(), 'org_id'))
				{
					$oDefinitionFilter->AddCondition('org_id', $currentOrganization, '=');
				}
			}
			$aResults = array();
			$oDefinitionSet = new CMDBObjectSet($oDefinitionFilter);
			$iCount = $oDefinitionSet->Count();
			$oRulesFilter = new CMDBSearchFilter('AuditRule');
			$oRulesFilter->AddCondition('category_id', $oAuditCategory->GetKey(), '=');
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
					$oRuleFilter = DBObjectSearch::FromOQL($oAuditRule->Get('query'));
					$oErrorObjectSet = GetRuleResultSet($oAuditRule->GetKey(), $oDefinitionFilter, $oAppContext);
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
}
catch(CoreException $e)
{
	require_once(APPROOT.'/setup/setuppage.class.inc.php');
	$oP = new SetupWebPage(Dict::S('UI:PageTitle:FatalError'));
	$oP->add("<h1>".Dict::S('UI:FatalErrorMessage')."</h1>\n");	
	$oP->error(Dict::Format('UI:Error_Details', $e->getHtmlDesc()));	
	$oP->output();

	if (MetaModel::IsLogEnabledIssue())
	{
		if (MetaModel::IsValidClass('EventIssue'))
		{
			$oLog = new EventIssue();

			$oLog->Set('message', $e->getMessage());
			$oLog->Set('userinfo', '');
			$oLog->Set('issue', $e->GetIssue());
			$oLog->Set('impact', 'Page could not be displayed');
			$oLog->Set('callstack', $e->getTrace());
			$oLog->Set('data', $e->getContextData());
			$oLog->DBInsertNoReload();
		}

		IssueLog::Error($e->getMessage());
	}

	// For debugging only
	//throw $e;
}
catch(Exception $e)
{
	require_once(APPROOT.'/setup/setuppage.class.inc.php');
	$oP = new SetupWebPage(Dict::S('UI:PageTitle:FatalError'));
	$oP->add("<h1>".Dict::S('UI:FatalErrorMessage')."</h1>\n");	
	$oP->error(Dict::Format('UI:Error_Details', $e->getMessage()));	
	$oP->output();

	if (MetaModel::IsLogEnabledIssue())
	{
		if (MetaModel::IsValidClass('EventIssue'))
		{
			$oLog = new EventIssue();

			$oLog->Set('message', $e->getMessage());
			$oLog->Set('userinfo', '');
			$oLog->Set('issue', 'PHP Exception');
			$oLog->Set('impact', 'Page could not be displayed');
			$oLog->Set('callstack', $e->getTrace());
			$oLog->Set('data', array());
			$oLog->DBInsertNoReload();
		}

		IssueLog::Error($e->getMessage());
	}
}
?>
