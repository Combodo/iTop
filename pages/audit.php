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
 * Execute and shows the data quality audit
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
/**
 * Adds the context parameters to the audit query
 */
function FilterByContext(DBObjectSearch &$oFilter, ApplicationContext $oAppContext)
{
	$sObjClass = $oFilter->GetClass();		
	$aContextParams = $oAppContext->GetNames();
	$aCallSpec = array($sObjClass, 'MapContextParam');
	if (is_callable($aCallSpec))
	{
		foreach($aContextParams as $sParamName)
		{
			$sValue = $oAppContext->GetCurrentValue($sParamName, null);
			if ($sValue != null)
			{
				$sAttCode = call_user_func($aCallSpec, $sParamName); // Returns null when there is no mapping for this parameter
				if ( ($sAttCode != null) && MetaModel::IsValidAttCode($sObjClass, $sAttCode))
				{
					// Check if the condition points to a hierarchical key
					if ($sAttCode == 'id')
					{
						// Filtering on the objects themselves
						$sHierarchicalKeyCode = MetaModel::IsHierarchicalClass($sObjClass);
						
						if ($sHierarchicalKeyCode !== false)
						{
							$oRootFilter = new DBObjectSearch($sObjClass);
							$oRootFilter->AddCondition($sAttCode, $sValue);
							$oFilter->AddCondition_PointingTo($oRootFilter, $sHierarchicalKeyCode, TREE_OPERATOR_BELOW); // Use the 'below' operator by default
							$bConditionAdded = true;
						}
					}
					else
					{
						$oAttDef = MetaModel::GetAttributeDef($sObjClass, $sAttCode);
						$bConditionAdded = false;
						if ($oAttDef->IsExternalKey())
						{
							$sHierarchicalKeyCode = MetaModel::IsHierarchicalClass($oAttDef->GetTargetClass());
							
							if ($sHierarchicalKeyCode !== false)
							{
								$oRootFilter = new DBObjectSearch($oAttDef->GetTargetClass());
								$oRootFilter->AddCondition('id', $sValue);
								$oHKFilter = new DBObjectSearch($oAttDef->GetTargetClass());
								$oHKFilter->AddCondition_PointingTo($oRootFilter, $sHierarchicalKeyCode, TREE_OPERATOR_BELOW); // Use the 'below' operator by default
								$oFilter->AddCondition_PointingTo($oHKFilter, $sAttCode);
								$bConditionAdded = true;
							}
						}
					}
					if (!$bConditionAdded)
					{
						$oFilter->AddCondition($sAttCode, $sValue);
					}
				}
			}
		}
	}
}

function GetRuleResultFilter($iRuleId, $oDefinitionFilter, $oAppContext)
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
	}
	else
	{
		// The query returns only the valid elements, all the others are invalid
		$aValidRows = $oRuleFilter->ToDataArray(array('id'));
		$aValidIds = array();
		foreach($aValidRows as $aRow)
		{
			$aValidIds[] = $aRow['id'];
		}
		$oFilter = $oDefinitionFilter->DeepClone();
		if (count($aValidIds) > 0)
		{
			$aInDefSet = array();
			foreach($oDefinitionFilter->ToDataArray(array('id')) as $aRow)
			{
				$aInDefSet[] = $aRow['id'];
			}
			$aInvalids = array_diff($aInDefSet, $aValidIds);
			if (count($aInvalids) > 0)
			{
				$oFilter->AddCondition('id', $aInvalids, 'IN');
			}
			else
			{
				$oFilter->AddCondition('id', 0, '=');
			}
		}
	}
	return $oFilter;
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

try
{
	require_once('../approot.inc.php');
	require_once(APPROOT.'/application/application.inc.php');
	require_once(APPROOT.'/application/itopwebpage.class.inc.php');
	require_once(APPROOT.'/application/csvpage.class.inc.php');

	
	require_once(APPROOT.'/application/startup.inc.php');
	$operation = utils::ReadParam('operation', '');
	$oAppContext = new ApplicationContext();
	
	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	LoginWebPage::DoLogin(); // Check user rights and prompt if needed
	
	$oP = new iTopWebPage(Dict::S('UI:Audit:Title'));
	
	switch($operation)
	{
		case 'csv':
		// Big result sets cause long OQL that cannot be passed (serialized) as a GET parameter
		// Therefore we don't use the standard "search_oql" operation of UI.php to display the CSV
		$iCategory = utils::ReadParam('category', '');
		$iRuleIndex = utils::ReadParam('rule', 0);
	
		$oAuditCategory = MetaModel::GetObject('AuditCategory', $iCategory);
		$oDefinitionFilter = DBObjectSearch::FromOQL($oAuditCategory->Get('definition_set'));
		FilterByContext($oDefinitionFilter, $oAppContext);
		$oDefinitionSet = new CMDBObjectSet($oDefinitionFilter);
		$oFilter = GetRuleResultFilter($iRuleIndex, $oDefinitionFilter, $oAppContext);
		$oErrorObjectSet = new CMDBObjectSet($oFilter);
		$oAuditRule = MetaModel::GetObject('AuditRule', $iRuleIndex);
		$sFileName = utils::ReadParam('filename', null, true, 'string');
		$bAdvanced = utils::ReadParam('advanced', false);
		$sAdvanced = $bAdvanced ? '&advanced=1' : '';
		
		if ($sFileName != null)
		{
			$oP = new CSVPage("iTop - Export");
			$sCharset = MetaModel::GetConfig()->Get('csv_file_default_charset');
			$sCSVData = cmdbAbstractObject::GetSetAsCSV($oErrorObjectSet, array('localize_values' => true, 'fields_advanced' => $bAdvanced), $sCharset);
			if ($sCharset == 'UTF-8')
			{
				$sOutputData = UTF8_BOM.$sCSVData;
			}
			else
			{
				$sOutputData = $sCSVData;
			}
			if ($sFileName == '')
			{
				// Plain text => Firefox will NOT propose to download the file
				$oP->add_header("Content-type: text/plain; charset=$sCharset");
			}
			else
			{
				$oP->add_header("Content-type: text/csv; charset=$sCharset");
			}
			$oP->add($sOutputData);
			$oP->TrashUnexpectedOutput();
			$oP->output();
			exit;
		}
		else
		{
			$oP->add('<div class="page_header"><h1>Audit Errors: <span class="hilite">'.$oAuditRule->Get('description').'</span></h1><img style="margin-top: -20px; margin-right: 10px; float: right;" src="../images/stop.png"/></div>');
			$oP->p('<a href="./audit.php?'.$oAppContext->GetForLink().'">[Back to audit results]</a>');
		    $sBlockId = 'audit_errors';
			$oP->p("<div id=\"$sBlockId\" style=\"clear:both\">\n");
			$oBlock = DisplayBlock::FromObjectSet($oErrorObjectSet, 'csv');    
			$oBlock->Display($oP, 1);
			$oP->p("</div>\n");    
			// Adjust the size of the Textarea containing the CSV to fit almost all the remaining space
			$oP->add_ready_script(" $('#1>textarea').height(400);"); // adjust the size of the block			
			$sExportUrl = utils::GetAbsoluteUrlAppRoot()."pages/audit.php?operation=csv&category=".$oAuditCategory->GetKey()."&rule=".$oAuditRule->GetKey();
			$oP->add_ready_script("$('a[href*=\"webservices/export.php?expression=\"]').attr('href', '".$sExportUrl."&filename=audit.csv".$sAdvanced."');");
			$oP->add_ready_script("$('#1 :checkbox').removeAttr('onclick').click( function() { var sAdvanced = ''; if (this.checked) sAdvanced = '&advanced=1'; window.location.href='$sExportUrl'+sAdvanced; } );");
		}
		break;
						
		case 'errors':
		$iCategory = utils::ReadParam('category', '');
		$iRuleIndex = utils::ReadParam('rule', 0);
	
		$oAuditCategory = MetaModel::GetObject('AuditCategory', $iCategory);
		$oDefinitionFilter = DBObjectSearch::FromOQL($oAuditCategory->Get('definition_set'));
		FilterByContext($oDefinitionFilter, $oAppContext);
		$oDefinitionSet = new CMDBObjectSet($oDefinitionFilter);
		$oFilter = GetRuleResultFilter($iRuleIndex, $oDefinitionFilter, $oAppContext);
		$oErrorObjectSet = new CMDBObjectSet($oFilter);
		$oAuditRule = MetaModel::GetObject('AuditRule', $iRuleIndex);
		$oP->add('<div class="page_header"><h1>Audit Errors: <span class="hilite">'.$oAuditRule->Get('description').'</span></h1><img style="margin-top: -20px; margin-right: 10px; float: right;" src="../images/stop.png"/></div>');
		$oP->p('<a href="./audit.php?'.$oAppContext->GetForLink().'">[Back to audit results]</a>');
	    $sBlockId = 'audit_errors';
		$oP->p("<div id=\"$sBlockId\" style=\"clear:both\">\n");
		$oBlock = DisplayBlock::FromObjectSet($oErrorObjectSet, 'list');    
		$oBlock->Display($oP, 1);
		$oP->p("</div>\n");
		$sExportUrl = utils::GetAbsoluteUrlAppRoot()."pages/audit.php?operation=csv&category=".$oAuditCategory->GetKey()."&rule=".$oAuditRule->GetKey();
		$oP->add_ready_script("$('a[href*=\"pages/UI.php?operation=search\"]').attr('href', '".$sExportUrl."')");
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
			try
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
					$aRow['description'] = $oAuditRule->GetName();
					if ($iCount == 0)
					{
						// nothing to check, really !
						$aRow['nb_errors'] = "<a href=\"audit.php?operation=errors&category=".$oAuditCategory->GetKey()."&rule=".$oAuditRule->GetKey()."\">0</a>"; 
						$aRow['percent_ok'] = '100.00';
						$aRow['class'] = GetReportColor($iCount, 0);
					}
					else
					{
						try
						{
							$oFilter = GetRuleResultFilter($oAuditRule->GetKey(), $oDefinitionFilter, $oAppContext);
							$aErrors = $oFilter->ToDataArray(array('id'));
							$iErrorsCount = count($aErrors);
							foreach($aErrors as $aErrorRow)
							{
								$aObjectsWithErrors[$aErrorRow['id']] = true;
							}
							$aRow['nb_errors'] = ($iErrorsCount == 0) ? '0' : "<a href=\"?operation=errors&category=".$oAuditCategory->GetKey()."&rule=".$oAuditRule->GetKey()."&".$oAppContext->GetForLink()."\">$iErrorsCount</a> <a href=\"?operation=csv&category=".$oAuditCategory->GetKey()."&rule=".$oAuditRule->GetKey()."&".$oAppContext->GetForLink()."\">(CSV)</a>"; 
							$aRow['percent_ok'] = sprintf('%.2f', 100.0 * (($iCount - $iErrorsCount) / $iCount));
							$aRow['class'] = GetReportColor($iCount, $iErrorsCount);							
						}
						catch(Exception $e)
						{
							$aRow['nb_errors'] = "OQL Error"; 
							$aRow['percent_ok'] = 'n/a';
							$aRow['class'] = 'red';
							$sMessage = Dict::Format('UI:Audit:ErrorIn_Rule_Reason', $oAuditRule->GetHyperlink(), $e->getMessage());
							$oP->p("<img style=\"vertical-align:middle\" src=\"../images/stop-mid.png\"/>&nbsp;".$sMessage);
						}
					}
					$aResults[] = $aRow;
					$iTotalErrors = count($aObjectsWithErrors);
					$sOverallPercentOk = ($iCount == 0) ? '100.00' : sprintf('%.2f', 100.0 * (($iCount - $iTotalErrors) / $iCount));
					$sClass = GetReportColor($iCount, $iTotalErrors);
		
				}
			}
			catch(Exception $e)
			{
				$aRow = array();
				$aRow['description'] = "OQL error";
				$aRow['nb_errors'] = "n/a"; 
				$aRow['percent_ok'] = '';
				$aRow['class'] = 'red';				
				$sMessage = Dict::Format('UI:Audit:ErrorIn_Category_Reason', $oAuditCategory->GetHyperlink(), $e->getMessage());
				$oP->p("<img style=\"vertical-align:middle\" src=\"../images/stop-mid.png\"/>&nbsp;".$sMessage);
				$aResults[] = $aRow;					
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
	$oP = new SetupPage(Dict::S('UI:PageTitle:FatalError'));
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
	$oP = new SetupPage(Dict::S('UI:PageTitle:FatalError'));
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
