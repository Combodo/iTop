<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
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

use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\CollapsibleSection\CollapsibleSectionUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\FieldSet\FieldSetUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Form\FormUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\HtmlFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Title\TitleUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Toolbar\Separator\ToolbarSeparatorUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlockUIBlockFactory;
use Combodo\iTop\Application\WebPage\iTopWebPage;
use Combodo\iTop\DBTools\Service\DBAnalyzerUtils;

@include_once('../../approot.inc.php');
require_once(APPROOT.'application/startup.inc.php');

require_once('db_analyzer.class.inc.php');

const MAX_RESULTS = 10;

/**
 * @param iTopWebPage $oP
 * @param ApplicationContext $oAppContext
 *
 * @return iTopWebPage
 * @throws \CoreException
 * @throws \DictExceptionMissingString
 * @throws \MySQLException
 */
function DisplayDBInconsistencies(iTopWebPage &$oP, ApplicationContext &$oAppContext)
{
	$iShowId = intval(utils::ReadParam('show_id', '0'));
	$sClassSelection = utils::ReadParam('class_selection', '');
	$bVerbose = utils::ReadParam('verbose', 0);
	if (!empty($sClassSelection)) {
		$aClassSelection = explode(",", $sClassSelection);
	} else {
		$aClassSelection = array();
	}

	$oP->SetCurrentTab('DBTools:Inconsistencies');

	$bRunAnalysis = intval(utils::ReadParam('run_analysis', '0'));
	if ($bRunAnalysis) {
		$oDBAnalyzer = new DatabaseAnalyzer(0);
		$aResults = $oDBAnalyzer->CheckIntegrity($aClassSelection);
		if (empty($aResults)) {
			$oAlert = AlertUIBlockFactory::MakeForSuccess(Dict::S('DBTools:NoError'));
			$oP->AddUiBlock($oAlert);
		}
	}

	$oFieldSet = FieldSetUIBlockFactory::MakeStandard(Dict::S('DBTools:SelectAnalysisType'));
	$oP->AddUiBlock($oFieldSet);
	$oForm = FormUIBlockFactory::MakeStandard();
	$oFieldSet->AddSubBlock($oForm);

	$oToolbar = ToolbarUIBlockFactory::MakeStandard();
	$oForm->AddSubBlock($oToolbar);

	$oInput = InputUIBlockFactory::MakeForInputWithLabel(Dict::S('DBTools:HideIds'), 'show_id');
	$oToolbar->AddSubBlock($oInput);
	$oInput->GetInput()->SetType('radio');
	$oInput->GetInput()->SetValue('0');
	$oInput->GetInput()->SetIsChecked($iShowId == 0);
	$oInput->GetInput()->AddCSSClasses(['ibo-input-checkbox', 'ibo-input--label-left']);

	$oToolbar->AddSubBlock(ToolbarSeparatorUIBlockFactory::MakeVertical());

	$oInput = InputUIBlockFactory::MakeForInputWithLabel(Dict::S('DBTools:ShowIds'), 'show_id');
	$oToolbar->AddSubBlock($oInput);
	$oInput->GetInput()->SetType('radio');
	$oInput->GetInput()->SetValue('1');
	$oInput->GetInput()->SetIsChecked($iShowId == 1);
	$oInput->GetInput()->AddCSSClasses(['ibo-input-checkbox', 'ibo-input--label-left']);

	$oToolbar->AddSubBlock(ToolbarSeparatorUIBlockFactory::MakeVertical());

	$oInput = InputUIBlockFactory::MakeForInputWithLabel(Dict::S('DBTools:ShowReport'), 'show_id');
	$oToolbar->AddSubBlock($oInput);
	$oInput->GetInput()->SetType('radio');
	$oInput->GetInput()->SetValue('3');
	$oInput->GetInput()->SetIsChecked($iShowId == 3);
	$oInput->GetInput()->AddCSSClasses(['ibo-input-checkbox', 'ibo-input--label-left']);

	$oButton = ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('DBTools:Analyze'), null, null, true);
	// TODO 3.0 Spacing ?
	$oButton->AddCSSClasses(['mt-5', 'mb-5']);
	$oForm->AddSubBlock($oButton);

	$oInput = InputUIBlockFactory::MakeForHidden('class_selection', $sClassSelection);
	$oForm->AddSubBlock($oInput);
	$oInput = InputUIBlockFactory::MakeForHidden('run_analysis', 1);
	$oForm->AddSubBlock($oInput);
	$oInput = InputUIBlockFactory::MakeForHidden('exec_module', 'combodo-db-tools');
	$oForm->AddSubBlock($oInput);
	$oInput = InputUIBlockFactory::MakeForHidden('exec_page', 'dbtools.php');
	$oForm->AddSubBlock($oInput);
	$oForm->AddSubBlock($oAppContext->GetForFormBlock());


	if (!empty($sClassSelection)) {
		$oForm = FormUIBlockFactory::MakeStandard();
		$oP->AddUiBlock($oForm);
		$oInput = InputUIBlockFactory::MakeForHidden('show_id', '0');
		$oForm->AddSubBlock($oInput);
		$oInput = InputUIBlockFactory::MakeForHidden('class_selection', '');
		$oForm->AddSubBlock($oInput);
		$oInput = InputUIBlockFactory::MakeForHidden('error_selection', '');
		$oForm->AddSubBlock($oInput);
		$oInput = InputUIBlockFactory::MakeForHidden('exec_module', 'combodo-db-tools');
		$oForm->AddSubBlock($oInput);
		$oInput = InputUIBlockFactory::MakeForHidden('exec_page', 'dbtools.php');
		$oForm->AddSubBlock($oInput);
		$oButton = ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('DBTools:ShowAll'), null, null, true);
		$oForm->AddSubBlock($oButton);
	}

	if (!empty($aResults)) {
		if ($iShowId == 3) {
			// Report
			DisplayInconsistenciesReport($aResults, $bVerbose);
		}

		if ($iShowId == 0) {
			// Error List
			$oPanel = PanelUIBlockFactory::MakeForWarning(Dict::S('DBTools:ErrorsFound'));
			$oPanel->AddCSSClass('ibo-datatable-panel');
			$oP->AddUiBlock($oPanel);
			$oPanel->AddSubBlock(DisplayErrorList($aResults));
		} else {
			// Detail List
			$oFieldSet = FieldSetUIBlockFactory::MakeStandard(Dict::S('DBTools:ErrorsFound'));
			$oP->AddUiBlock($oFieldSet);
			$oFieldSet->AddSubBlock(DisplayErrorDetails($aResults, $bVerbose));
		}
	}
	return $oP;
}

/**
 * @param $aResults
 * @param bool $bVerbose
 *
 * @return mixed
 * @throws \CoreException
 * @throws \DictExceptionMissingString
 */
function DisplayInconsistenciesReport($aResults, $bVerbose = false)
{
	$sReportFile = DBAnalyzerUtils::GenerateReport($aResults, $bVerbose);

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
 * @param $aResults
 *
 * @return \Combodo\iTop\Application\UI\Base\UIBlock
 * @throws \CoreException
 * @throws \DictExceptionMissingString
 */
function DisplayErrorList($aResults)
{
	$aColumns = [
		'class' => ['label' => Dict::S('DBTools:Class')],
		'count' => ['label' => Dict::S('DBTools:Count')],
		'error' => ['label' => Dict::S('DBTools:Error')],
	];
	$aRows = [];

	foreach ($aResults as $sClass => $aErrorList) {
		foreach ($aErrorList as $sErrorLabel => $aError) {
			$iCount = $aError['count'];
			if ($iCount === DatabaseAnalyzer::LIMIT) {
				$iCount = "$iCount(+)";
			}
			$aRows[] = [
				'class' => MetaModel::GetName($sClass).' ('.$sClass.')',
				'count' => $iCount,
				'error' => $sErrorLabel,
			];
		}
	}

	return DataTableUIBlockFactory::MakeForForm('', $aColumns, $aRows);
}

function DisplayErrorDetails($aResults, $bVerbose)
{
	$oBlock = UIContentBlockUIBlockFactory::MakeStandard();

	$oBlock->AddSubBlock(HtmlFactory::MakeParagraph(Dict::S('DBTools:Disclaimer')));
	$oBlock->AddSubBlock(HtmlFactory::MakeParagraph(Dict::S('DBTools:Indication')));

	foreach ($aResults as $sClass => $aErrorList) {
		foreach ($aErrorList as $sErrorLabel => $aError) {
			$iCount = $aError['count'];
			if ($iCount === DatabaseAnalyzer::LIMIT) {
				$iCount = "$iCount(+)";
			}
			$sErrorTitle = Dict::Format('DBTools:DetailedErrorTitle', MetaModel::GetName($sClass).' ('.$sClass.')',	$iCount, $sErrorLabel);
			$oCollapsible = CollapsibleSectionUIBlockFactory::MakeStandard($sErrorTitle);
			$oBlock->AddSubBlock($oCollapsible);

			if ($aError['count'] === DatabaseAnalyzer::LIMIT) {
				$oHTML = new Combodo\iTop\Application\UI\Base\Component\Html\Html('<p>'.Dict::format('DBTools:DetailedErrorLimit', DatabaseAnalyzer::LIMIT).'</p>');
				$oCollapsible->AddSubBlock($oHTML);
			}

			$oFieldSet = FieldSetUIBlockFactory::MakeStandard(Dict::S('DBTools:SQLquery'));
			$oCollapsible->AddSubBlock($oFieldSet);

			if (array_key_exists('query', $aError)) {
				$oCode = UIContentBlockUIBlockFactory::MakeForPreformatted($aError['query']);
				$oFieldSet->AddSubBlock($oCode);

				if (isset($aError['fixit'])) {
					$oFieldSet = FieldSetUIBlockFactory::MakeStandard(Dict::S('DBTools:FixitSQLquery'));
					$oCollapsible->AddSubBlock($oFieldSet);

					$aQueries = $aError['fixit'];
					foreach ($aQueries as $sFixQuery) {
						$oCode = UIContentBlockUIBlockFactory::MakeForPreformatted($sFixQuery);
						$oFieldSet->AddSubBlock($oCode);
					}
				}
			}

			if ($bVerbose) {
				$oFieldSet = FieldSetUIBlockFactory::MakeStandard(Dict::S('DBTools:SQLresult'));
				$oCollapsible->AddSubBlock($oFieldSet);

				$sQueryResult = '';
				$iCount = count($aError['res']);
				$iMaxCount = MAX_RESULTS;
				foreach ($aError['res'] as $aRes) {
					$iMaxCount--;
					if ($iMaxCount < 0) {
						$sQueryResult .= 'Displayed '.MAX_RESULTS."/$iCount results.<br>";
						break;
					}
					foreach ($aRes as $sKey => $sValue) {
						$sQueryResult .= "'$sKey'='$sValue'&nbsp;";
					}
					$sQueryResult .= '<br>';
				}
				$oCode = UIContentBlockUIBlockFactory::MakeForPreformatted($sQueryResult);
				$oFieldSet->AddSubBlock($oCode);
			}
		}
	}

	return $oBlock;
}

/**
 * @param iTopWebPage $oP
 * @param ApplicationContext $oAppContext
 *
 * @return iTopWebPage
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

	$oLostAttachmentsBlock = UIContentBlockUIBlockFactory::MakeStandard(null, ['ibo-dbt-lostattachments']);
	$oP->AddUiBlock($oLostAttachmentsBlock);

	$oForm = FormUIBlockFactory::MakeStandard();
	$oLostAttachmentsBlock->AddSubBlock($oForm);

	$oInput = InputUIBlockFactory::MakeForHidden('exec_module', 'combodo-db-tools');
	$oForm->AddSubBlock($oInput);
	$oInput = InputUIBlockFactory::MakeForHidden('exec_page', 'dbtools.php');
	$oForm->AddSubBlock($oInput);

	// Step 1: Analyze DB
	if (!$bDoAnalyze) {
		$oAlert = AlertUIBlockFactory::MakeForInformation(Dict::S('DBTools:LostAttachments:Disclaimer'));
		$oForm->AddSubBlock($oAlert);

		$oPanel = FieldSetUIBlockFactory::MakeStandard(Dict::S('DBTools:LostAttachments:Step:Analyze'));
		$oForm->AddSubBlock($oPanel);
		$oButton = ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('DBTools:LostAttachments:Button:Analyze'), 'step_name', 'analyze', true);
		// TODO 3.0 Spacing ?
		$oButton->AddCSSClasses(['mt-5', 'mb-5']);
		$oPanel->AddSubBlock($oButton);
	}

	// Step 2: Display results
	if ($bDoAnalyze) {
		// Check if we have to restore some items first
		if ($bDoRestore) {
			foreach ($aRecordsToClean as $sRecordToClean) {
				utils::PushArchiveMode(false);  // For iTop < 2.5, the application can be wrongly set to archive mode true when it fails from retrieving an object. See r5340.
				try {
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
					// CMDBChangeOp.change will be automatically filled
					$oChangeOp->Set('objclass', $sTargetClass);
					$oChangeOp->Set('objkey', $sTargetId);
					$oChangeOp->Set('description', $sHistoryEntry);
					$oChangeOp->DBInsert();

					// Remove origin object (should only be done for InlineImage)
					$oOriginObject->DBDelete();

					$iRestoredItemsCount++;
				}
				catch (Exception $e) {
					$aErrorsReport[] = 'Could not restore attachment from '.$sRecordToClean.', cause: '.$e->getMessage();
				}
				utils::PopArchiveMode();
			}
		}

		// Search attachments stored as inline images
		$sInlineImageDBTable = MetaModel::DBGetTable('InlineImage');
		$sSelWrongRecs = 'SELECT id, secret, "InlineImage" AS current_class, id AS current_id, item_class AS target_class, item_id AS target_id, contents_filename AS filename FROM '.$sInlineImageDBTable.' WHERE contents_mimetype NOT LIKE "image/%"';
		$aWrongRecords = CMDBSource::QueryToArray($sSelWrongRecs);


		if (empty($aWrongRecords)) {
			$oAlert = AlertUIBlockFactory::MakeForSuccess(Dict::S('DBTools:LostAttachments:Step:AnalyzeResults:None'));
			$oForm->AddSubBlock($oAlert);
		} else {
			// Errors found
			$oAlert = AlertUIBlockFactory::MakeForFailure(Dict::Format('DBTools:LostAttachments:Step:AnalyzeResults:Some', count($aWrongRecords)));
			// TODO 3.0 Spacing ?
			$oAlert->AddCSSClass('mb-5');
			$oForm->AddSubBlock($oAlert);

			$oPanel = PanelUIBlockFactory::MakeForWarning(Dict::S('DBTools:LostAttachments:Step:AnalyzeResults'));
			$oPanel->AddCSSClass('ibo-datatable-panel');
			$oForm->AddSubBlock($oPanel);

			// Display errors as table
			$aColumns = [
				'select' => ['label' => '<input type="checkbox" class="dbt-toggler-cbx" />'],
				'filename' => ['label' => Dict::S('DBTools:LostAttachments:Step:AnalyzeResults:Item:Filename')],
				'location' => ['label' => Dict::S('DBTools:LostAttachments:Step:AnalyzeResults:Item:CurrentLocation')],
				'target' => ['label' => Dict::S('DBTools:LostAttachments:Step:AnalyzeResults:Item:TargetLocation')],
			];
			$aRows = [];
			foreach ($aWrongRecords as $iIndex => $aWrongRecord) {

				$sCurrentClass = $aWrongRecord['current_class'];
				$sCurrentId = $aWrongRecord['current_id'];
				$sRecordToClean = Dict::S('DBTools:LostAttachments:StoredAsInlineImage');

				$sTargetClass = $aWrongRecord['target_class'];
				$sTargetId = $aWrongRecord['target_id'];
				$sTargetLocation = '<a href="'.ApplicationContext::MakeObjectUrl($sTargetClass, $sTargetId).'" target="_blank">'.$sTargetClass.'::'.$sTargetId.'</a>';

				$sFilename = '<a href="'.utils::GetAbsoluteUrlAppRoot().INLINEIMAGE_DOWNLOAD_URL.$aWrongRecord['id'].'&s='.$aWrongRecord['secret'].'" target="_blank">'.$aWrongRecord['filename'].'</a>';

				$aRows[] = [
					'select' => '<input type="checkbox" class="dbt-cbx" name="dbt-cbx[]" value="'.$sCurrentClass.'::'.$sCurrentId.'" />',
					'filename' => $sFilename,
					'location' => $sRecordToClean,
					'target' => $sTargetLocation,
				];
				// $oP->add('<tr class="'.$sRowClass.'"><td><input type="checkbox" class="dbt-cbx" name="dbt-cbx[]" value="'.$sCurrentClass.'::'.$sCurrentId.'" /></td><td>'.$sFilename.'</td><td>'.$sRecordToClean.'</td><td>'.$sTargetLocation.'</td></tr>');
			}

			$oTable = DataTableUIBlockFactory::MakeForForm('results', $aColumns, $aRows);
			$oPanel->AddSubBlock($oTable);
			/** @var \Combodo\iTop\Application\UI\Base\Component\Button\ButtonJS $oButton */
			$oButton = ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('DBTools:LostAttachments:Button:Restore'), 'step_name', 'restore', true);
			// TODO 3.0 Spacing ?
			$oButton->AddCSSClasses(['mt-5', 'ml-5']);
			$oButton->SetIsDisabled(true);
			$oPanel->AddSubBlock($oButton);


			// JS to handle checkboxes and button
			$oP->add_ready_script(
				<<<EOF
	// Check all / none checkboxes
	$('.ibo-dbt-lostattachments .dbt-toggler-cbx').on('click', function(){
		$('.ibo-dbt-lostattachments .dbt-cbx').prop('checked', $(this).prop('checked'));
		
		// Disable restore button if at lest one checkbox clicked
		var bDisableButton = ($('.ibo-dbt-lostattachments .dbt-cbx:checked').length === 0)
		$('.ibo-dbt-lostattachments button[name="step_name"][value="restore"]').prop('disabled', bDisableButton);
	});

	// Click on a checkbox
	$('.ibo-dbt-lostattachments .dbt-cbx').on('click', function(){
		// Disable restore button if at lest one checkbox clicked
		var bDisableButton = ($('.ibo-dbt-lostattachments .dbt-cbx:checked').length === 0)
		$('.ibo-dbt-lostattachments button[name="step_name"][value="restore"]').prop('disabled', bDisableButton);
		
		// Uncheck global checkbox
		if( $('.ibo-dbt-lostattachments .dbt-cbx:not(:checked)').length > 0 )
		{
			$('.ibo-dbt-lostattachments .dbt-toggler-cbx').prop('checked', false);
		}
	});
EOF
			);
		}

	}

	// Step 3: Restore results
	if ($bDoRestore) {
		$oPanel = FieldSetUIBlockFactory::MakeStandard(Dict::S('DBTools:LostAttachments:Step:RestoreResults'));
		$oForm->AddSubBlock($oPanel);

		$oAlert = AlertUIBlockFactory::MakeForSuccess(Dict::Format('DBTools:LostAttachments:Step:RestoreResults:Results', $iRestoredItemsCount, $iRecordsToCleanCount));
		$oPanel->AddSubBlock($oAlert);

		if (!empty($aErrorsReport)) {
			foreach ($aErrorsReport as $sErrorReport) {

				$oAlert = AlertUIBlockFactory::MakeForFailure($sErrorReport);
				$oPanel->AddSubBlock($oAlert);
			}
		}
	}
	$oForm->AddSubBlock($oAppContext->GetForFormBlock());

	// Buttons disabling on click
	$sConfirmText = Dict::S('DBTools:LostAttachments:Button:Restore:Confirm');
	$sButtonBusyText = Dict::S('DBTools:LostAttachments:Button:Busy');
	$oP->add_ready_script(
		<<<EOF
	$('.ibo-dbt-lostattachments button[name="step_name"]').on('click', function(){
	
		if($(this).val() === 'restore')
		{
			if(!confirm('{$sConfirmText}'))
			{
				return false;
			}		
		}
		$(this).text('{$sButtonBusyText}');
	});
EOF
	);

	return $oP;
}

/////////////////////////////////////////////////////////////////////
// Main program
//
try {
	if (method_exists('ApplicationMenu', 'CheckMenuIdEnabled')) {
		LoginWebPage::DoLogin(); // Check user rights and prompt if needed
		ApplicationMenu::CheckMenuIdEnabled('DBToolsMenu');
	} else {
		LoginWebPage::DoLogin(true); // Check user rights and prompt if needed
	}

	$oAppContext = new ApplicationContext();


	$sPageTitle = Dict::S('DBTools:Title');
	$sPageId = 'db-tools';

	$oP = new iTopWebPage($sPageTitle);
	$oP->add_saas('env-'.utils::GetCurrentEnvironment().'/combodo-db-tools/default.scss');

	$oTitle = TitleUIBlockFactory::MakeForPage($sPageTitle);
	$oP->AddUiBlock($oTitle);

	$oP->AddTabContainer('db-tools');
	$oP->SetCurrentTabContainer('db-tools');

	// DB Inconsistences
	$oP = DisplayDBInconsistencies($oP, $oAppContext);

	// Lost attachments
	$oP = DisplayLostAttachments($oP, $oAppContext);
}
catch (Exception $e) {
	$oP->p('<b>'.$e->getMessage().'</b>');
}

if (isset($oP)) {
	$oP->output();
}
