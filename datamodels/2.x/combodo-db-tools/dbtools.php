<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
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

use Combodo\iTop\DBTools\Service\DBAnalyzerUtils;

@include_once('../../approot.inc.php');
require_once(APPROOT.'application/startup.inc.php');

require_once('db_analyzer.class.inc.php');

const MAX_RESULTS = 10;

/**
 * @param iTopWebPage $oP
 * @param ApplicationContext $oAppContext
 *
 * @return \iTopWebPage
 * @throws CoreException
 * @throws DictExceptionMissingString
 * @throws MySQLException
 */
function DisplayDBInconsistencies(iTopWebPage &$oP, ApplicationContext &$oAppContext)
{
	$iShowId = intval(utils::ReadParam('show_id', '0'));
	$sErrorLabelSelection = utils::ReadParam('error_selection', '');
	$sClassSelection = utils::ReadParam('class_selection', '');
	if (!empty($sClassSelection))
	{
		$aClassSelection = explode(",", $sClassSelection);
	}
	else
	{
		$aClassSelection = array();
	}
	$sClassSelection = utils::ReadParam('class_selection', '');

	$oP->SetCurrentTab('DBTools:Inconsistencies');

	$bRunAnalysis = intval(utils::ReadParam('run_analysis', '0'));
	if ($bRunAnalysis)
	{
		$oDBAnalyzer = new DatabaseAnalyzer(0);
		$aResults = $oDBAnalyzer->CheckIntegrity($aClassSelection);
		if (empty($aResults))
		{
			$oP->p('<div class="header_message message_ok">'.Dict::S('DBTools:NoError').'</div>');
		}
	}

	$oP->add('<div style="padding: 15px; background: #ddd;">');
	$oP->add("<form>");
	$oP->add('<table style="border=0;">');

	$oP->add("<tr><td>");
	$sChecked = ($iShowId == 0) ? 'checked' : '';
	$oP->add("<label><input type=\"radio\" $sChecked name=\"show_id\" value=\"0\">".Dict::S('DBTools:HideIds').'</label>');
	$oP->add("</td><td>");
	$sChecked = ($iShowId == 1) ? 'checked' : '';
	$oP->add("<label><input type=\"radio\" $sChecked name=\"show_id\" value=\"1\">".Dict::S('DBTools:ShowIds').'</label>');
	$oP->add("</td><td>");
	$sChecked = ($iShowId == 3) ? 'checked' : '';
	$oP->add("<label><input type=\"radio\" $sChecked name=\"show_id\" value=\"3\">".Dict::S('DBTools:ShowReport').'</label>');
	$oP->add("</td></tr>\n");

	$oP->add("</table><br>\n");

	$oP->add("<input type=\"submit\" value=\"".Dict::S('DBTools:Analyze')."\">\n");
	$oP->add('<input type="hidden" name="class_selection" value="'.$sClassSelection.'"/>');
	$oP->add('<input type="hidden" name="error_selection" value="'.$sErrorLabelSelection.'"/>');
	$oP->add('<input type="hidden" name="run_analysis" value="1"/>');
	$oP->add('<input type="hidden" name="exec_module" value="combodo-db-tools"/>');
	$oP->add('<input type="hidden" name="exec_page" value="dbtools.php"/>');
	$oP->add($oAppContext->GetForForm());
	$oP->add("</form>\n");
	$oP->add('</div>');


	if (!empty($sErrorLabelSelection) || !empty($sClassSelection))
	{
		$oP->add("<br>");
		$oP->add("<form>");
		$oP->add('<input type="hidden" name="show_id" value="0"/>');
		$oP->add('<input type="hidden" name="class_selection" value=""/>');
		$oP->add('<input type="hidden" name="error_selection" value=""/>');
		$oP->add('<input type="hidden" name="exec_module" value="combodo-db-tools"/>');
		$oP->add('<input type="hidden" name="exec_page" value="dbtools.php"/>');
		$oP->add("<input type=\"submit\" value=\"".Dict::S('DBTools:ShowAll')."\">\n");
		$oP->add("</form>\n");
	}

	if (!empty($aResults))
	{

		if ($iShowId == 3)
		{
			DisplayInconsistenciesReport($aResults);
		}

		$oP->p(Dict::S('DBTools:ErrorsFound'));

		$oP->add('<table class="listResults"><tr><th>'.Dict::S('DBTools:Class').'</th><th>'.Dict::S('DBTools:Count').'</th><th>'.Dict::S('DBTools:Error').'</th></tr>');
		$bTable = true;
		foreach($aResults as $sClass => $aErrorList)
		{
			foreach($aErrorList as $sErrorLabel => $aError)
			{
				if (!empty($sErrorLabelSelection) && ($sErrorLabel != $sErrorLabelSelection))
				{
					continue;
				}

				if (!$bTable)
				{
					$oP->add('<br>');
					$oP->add('<table class="listResults"><tr><th></th><th>Class</th><th>Count</th><th>Error</th></tr>');
					$bTable = true;
				}

				$oP->add('<tr>');


				$oP->add('<td>'.MetaModel::GetName($sClass).' ('.$sClass.')</td>');
				$iCount = $aError['count'];
				$oP->add('<td>'.$iCount.'</td>');
				$oP->add('<td>'.$sErrorLabel.'</td>');
				$oP->add('</tr>');

				if ($iShowId > 0)
				{
					$oP->add('</table>');
					$bTable = false;
					$oP->p(Dict::S('DBTools:SQLquery'));
					$sQuery = $aError['query'];
					$oP->add('<div style="padding: 15px; background: #f1f1f1;">');
					$oP->add('<code>'.$sQuery.'</code>');
					$oP->add('</div>');

					if (isset($aError['fixit']))
					{
						$oP->p(Dict::S('DBTools:FixitSQLquery'));
						$aQueries = $aError['fixit'];
						$oP->add('<div style="padding: 15px; background: #f1f1f1;">');
						foreach($aQueries as $sFixQuery)
						{
							$oP->add('<pre>'.$sFixQuery.'</pre>');
						}
						$oP->add('<br></div>');
					}

					$oP->p(Dict::S('DBTools:SQLresult'));
					$sQueryResult = '';
					$iCount = count($aError['res']);
					$iMaxCount = MAX_RESULTS;
					foreach($aError['res'] as $aRes)
					{
						$iMaxCount--;
						if ($iMaxCount < 0)
						{
							$sQueryResult .= 'Displayed '.MAX_RESULTS."/$iCount results.<br>";
							break;
						}
						foreach($aRes as $sKey => $sValue)
						{
							$sQueryResult .= "'$sKey'='$sValue'&nbsp;";
						}
						$sQueryResult .= '<br>';
					}
					$oP->add('<div style="padding: 15px; background: #f1f1f1;">');
					$oP->add('<code>'.$sQueryResult.'</code>');
					$oP->add('</div>');
				}
			}
		}
		$oP->add('</table>');
	}
	return $oP;
}

/**
 * @param $aResults
 *
 * @return mixed
 * @throws CoreException
 * @throws DictExceptionMissingString
 */
function DisplayInconsistenciesReport($aResults)
{
	$sReportFile = DBAnalyzerUtils::GenerateReport($aResults);

	$sZipReport = "{$sReportFile}.zip";
	$oArchive = new ZipArchive();
	$oArchive->open($sZipReport, ZipArchive::CREATE);
	$oArchive->addFile($sReportFile.'.log', basename($sReportFile.'.log'));
	$oArchive->close();

	header('Content-Description: File Transfer');
	header('Content-Type: multipart/x-zip');
	header('Content-Disposition: inline; filename="'.basename($sZipReport).'"');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Expires: 0');
	header('Content-Length: '.filesize($sZipReport));
	readfile($sZipReport);
	unlink($sZipReport);
	exit(0);
}

/**
 * @param iTopWebPage $oP
 * @param ApplicationContext $oAppContext
 *
 * @return \iTopWebPage
 * @throws CoreException
 * @throws MySQLException
 * @throws \Exception
 */
function DisplayLostAttachments(iTopWebPage &$oP, ApplicationContext &$oAppContext)
{
	// Retrieve parameters
	$sStepName = utils::ReadParam('step_name');
	$aRecordsToClean = utils::ReadParam('dbt-cbx', array(), false, 'raw_data');

	$iRestoredItemsCount = 0;
	$iRecordsToCleanCount = count($aRecordsToClean);
	$aErrorsReport = array();

	$bDoAnalyze = in_array($sStepName, array('analyze', 'restore'));
	$bDoRestore = in_array($sStepName, array('restore'));

	// Build HTML
	$oP->SetCurrentTab('DBTools:LostAttachments');

	$oP->add('<div class="db-tools-tab-content">');
	$oP->add('<div class="dbt-lostattachments">');

	$oP->add('<div class="header_message message_info">'.Dict::S('DBTools:LostAttachments:Disclaimer').'</div>');
	$oP->add('<div class="dbt-steps">');
	$oP->add('<form>');
	$oP->add('<input type="hidden" name="exec_module" value="combodo-db-tools"/>');
	$oP->add('<input type="hidden" name="exec_page" value="dbtools.php"/>');

	// Step 1: Analyze DB
	$oP->add('<div class="dbt-step"><p class="dbt-step-description"><span class="dbt-step-number">1.</span><span>'.Dict::S('DBTools:LostAttachments:Step:Analyze').'</span></p><button type="submit" name="step_name" value="analyze">'.Dict::S('DBTools:LostAttachments:Button:Analyze') .'</button></div>');

	// Step 2: Display results
	if($bDoAnalyze)
	{
		// Check if we have to restore some items first
		if($bDoRestore)
		{
			foreach($aRecordsToClean as $sRecordToClean)
			{
				utils::PushArchiveMode(false);  // For iTop < 2.5, the application can be wrongly set to archive mode true when it fails from retrieving an object. See r5340.
				try
				{
					// Retrieve attachment
					$aLocationParts = explode('::', $sRecordToClean);
					/** @var \DBObject $oOriginObject */
					$oOriginObject = MetaModel::GetObject($aLocationParts[0], $aLocationParts[1], true, true);
					/** @var \ormDocument $oOrmDocument */
					$oOrmDocument = $oOriginObject->Get('contents');

					// Retrieve target object
					$sTargetClass = $oOriginObject->Get('item_class');
					$sTargetId = $oOriginObject->Get('item_id');
					/** @var \DBObject $oTargetObject */
					$oTargetObject = MetaModel::GetObject($sTargetClass, $sTargetId, true, true);

					// Put it on the target object
					/** @var \Attachment $oAttachment */
					$oAttachment = MetaModel::NewObject('Attachment');
					$oAttachment->Set('item_class', $sTargetClass);
					$oAttachment->Set('item_id', $sTargetId);
					$oAttachment->Set('item_org_id', $oTargetObject->Get('org_id'));
					$oAttachment->Set('contents', $oOrmDocument);
					$oAttachment->DBInsert();

					// Put history entry
					$sHistoryEntry = Dict::Format('DBTools:LostAttachments:History', $oOrmDocument->GetFileName());
					CMDBObject::SetTrackInfo(UserRights::GetUserFriendlyName());
					$oChangeOp = MetaModel::NewObject('CMDBChangeOpPlugin');
					/** @var \Change $oChange */
					$oChange = CMDBObject::GetCurrentChange();
					$oChangeOp->Set('change', $oChange->GetKey());
					$oChangeOp->Set('objclass', $sTargetClass);
					$oChangeOp->Set('objkey', $sTargetId);
					$oChangeOp->Set('description', $sHistoryEntry);
					$oChangeOp->DBInsert();

					// Remove origin object (should only be done for InlineImage)
					$oOriginObject->DBDelete();

					$iRestoredItemsCount++;
				}
				catch(Exception $e)
				{
					$aErrorsReport[] = 'Could not restore attachment from '.$sRecordToClean.', cause: '.$e->getMessage();
				}
				utils::PopArchiveMode();
			}
		}

		// Search attachments stored as inline images
		$sInlineImageDBTable = MetaModel::DBGetTable('InlineImage');
		$sSelWrongRecs = 'SELECT id, secret, "InlineImage" AS current_class, id AS current_id, item_class AS target_class, item_id AS target_id, contents_filename AS filename FROM '.$sInlineImageDBTable.' WHERE contents_mimetype NOT LIKE "image/%"';
		$aWrongRecords = CMDBSource::QueryToArray($sSelWrongRecs);

		$oP->add('<div class="dbt-step">');
		$oP->add('<p class="dbt-step-description"><span class="dbt-step-number">2.</span><span>'.Dict::S('DBTools:LostAttachments:Step:AnalyzeResults').'</span></p>');

		if(empty($aWrongRecords))
		{
			$oP->add('<div class="header_message message_ok">'.Dict::S('DBTools:LostAttachments:Step:AnalyzeResults:None').'</div>');
		}
		else
		{
			$oP->add('<div class="header_message message_error">'.Dict::Format('DBTools:LostAttachments:Step:AnalyzeResults:Some', count($aWrongRecords)).'</div>');

			// Display errors as table
			$oP->add('<table class="listResults">');
			$oP->add('<tr><th><input type="checkbox" class="dbt-toggler-cbx" /></th><th>'.Dict::S('DBTools:LostAttachments:Step:AnalyzeResults:Item:Filename').'</th><th>'.Dict::S('DBTools:LostAttachments:Step:AnalyzeResults:Item:CurrentLocation').'</th><th>'.Dict::S('DBTools:LostAttachments:Step:AnalyzeResults:Item:TargetLocation').'</th></tr>');

			foreach($aWrongRecords as $iIndex => $aWrongRecord)
			{

				$sCurrentClass = $aWrongRecord['current_class'];
				$sCurrentId = $aWrongRecord['current_id'];
				$sRecordToClean = Dict::S('DBTools:LostAttachments:StoredAsInlineImage');

				$sTargetClass = $aWrongRecord['target_class'];
				$sTargetId = $aWrongRecord['target_id'];
				$sTargetLocation = '<a href="'.ApplicationContext::MakeObjectUrl($sTargetClass, $sTargetId).'" target="_blank">'.$sTargetClass.'::'.$sTargetId.'</a>';

				$sFilename = '<a href="'.utils::GetAbsoluteUrlAppRoot().INLINEIMAGE_DOWNLOAD_URL.$aWrongRecord['id'].'&s='.$aWrongRecord['secret'].'" target="_blank">'.$aWrongRecord['filename'].'</a>';

				$sRowClass = ($iIndex % 2 === 0) ? 'odd' : 'even'; // (Starts at 0, not 1)
				$oP->add('<tr class="'.$sRowClass.'"><td><input type="checkbox" class="dbt-cbx" name="dbt-cbx[]" value="'.$sCurrentClass.'::'.$sCurrentId.'" /></td><td>'.$sFilename.'</td><td>'.$sRecordToClean.'</td><td>'.$sTargetLocation.'</td></tr>');
			}

			$oP->add('</table>');
			$oP->add('<div><button type="submit" name="step_name" value="restore" disabled>'.Dict::S('DBTools:LostAttachments:Button:Restore').'</button></div>');

			// JS to handle checkboxes and button
			$oP->add_ready_script(
<<<EOF
	// Check all / none checkboxes
	$('.dbt-lostattachments .dbt-toggler-cbx').on('click', function(){
		$('.dbt-lostattachments .dbt-cbx').prop('checked', $(this).prop('checked'));
		
		// Disable restore button if at lest one checkbox clicked
		var bDisableButton = ($('.dbt-lostattachments .dbt-cbx:checked').length === 0)
		$('.dbt-lostattachments button[name="step_name"][value="restore"]').prop('disabled', bDisableButton);
	});

	// Click on a checkbox
	$('.dbt-lostattachments .dbt-cbx').on('click', function(){
		// Disable restore button if at lest one checkbox clicked
		var bDisableButton = ($('.dbt-lostattachments .dbt-cbx:checked').length === 0)
		$('.dbt-lostattachments button[name="step_name"][value="restore"]').prop('disabled', bDisableButton);
		
		// Uncheck global checkbox
		if( $('.dbt-lostattachments .dbt-cbx:not(:checked)').length > 0 )
		{
			$('.dbt-lostattachments .dbt-toggler-cbx').prop('checked', false);
		}
	});
EOF
			);
		}

		$oP->add('</div>');
	}

	// Step 3: Restore results
	if($bDoRestore)
	{
		$oP->add('<div class="dbt-step">');
		$oP->add('<p class="dbt-step-description"><span class="dbt-step-number">3.</span><span>'.Dict::S('DBTools:LostAttachments:Step:RestoreResults').'</span></p>');

		$oP->add('<div class="header_message message_info">'.Dict::Format('DBTools:LostAttachments:Step:RestoreResults:Results', $iRestoredItemsCount, $iRecordsToCleanCount).'</div>');

		if(!empty($aErrorsReport))
		{
			foreach($aErrorsReport as $sErrorReport)
			{
				$oP->add('<div class="header_message message_error">'.$sErrorReport.'</div>');
			}
		}

		$oP->add('</div>');
	}

	$oP->add($oAppContext->GetForForm());
	$oP->add('</form>');
	$oP->add('</div>');

	$oP->add('</div>');
	$oP->add('</div>');

	// Buttons disabling on click
	$sConfirmText = Dict::S('DBTools:LostAttachments:Button:Restore:Confirm');
	$sButtonBusyText = Dict::S('DBTools:LostAttachments:Button:Busy');
	$oP->add_ready_script(
<<<EOF
	$('.dbt-lostattachments button[name="step_name"]').on('click', function(){
	
		if($(this).val() === 'restore')
		{
			if(!confirm('{$sConfirmText}'))
			{
				return false;
			}		
		}
		//$(this).prop('disabled', true);
		$(this).text('{$sButtonBusyText}');
	});
EOF
	);

	return $oP;
}

/////////////////////////////////////////////////////////////////////
// Main program
//
try
{
	if (method_exists('ApplicationMenu', 'CheckMenuIdEnabled'))
	{
		LoginWebPage::DoLogin(); // Check user rights and prompt if needed
		ApplicationMenu::CheckMenuIdEnabled('DBToolsMenu');
	}
	else
	{
		LoginWebPage::DoLogin(true); // Check user rights and prompt if needed
	}

	$oAppContext = new ApplicationContext();


	$sPageTitle = Dict::S('DBTools:Title');
	$sPageId = 'db-tools';

	$oP = new iTopWebPage($sPageTitle);
	$oP->add_saas('env-'.utils::GetCurrentEnvironment().'/combodo-db-tools/default.scss');

	$oP->add(
<<<EOF
<div class="page_header">
  	<h1>$sPageTitle</h1>
</div>
EOF
	);
	$oP->AddTabContainer('db-tools');
	$oP->SetCurrentTabContainer('db-tools');

	// DB Inconsistences
	$oP = DisplayDBInconsistencies($oP, $oAppContext);

	// Lost attachments
	$oP = DisplayLostAttachments($oP, $oAppContext);
}
catch (Exception $e)
{
	$oP->p('<b>'.$e->getMessage().'</b>');
}

if (isset($oP))
{
	$oP->output();
}
