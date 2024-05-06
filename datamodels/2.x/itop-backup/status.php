<?php
/**
 * Copyright (C) 2010-2024 Combodo SAS
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
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\FieldSet\FieldSet;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Title\TitleUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Application\UI\Base\UIBlock;
use Combodo\iTop\Application\WebPage\iTopWebPage;
use Combodo\iTop\Renderer\BlockRenderer;

if (!defined('APPROOT')) {
	require_once(__DIR__.'/../../approot.inc.php');
}
require_once(APPROOT.'application/application.inc.php');

require_once(APPROOT.'application/startup.inc.php');

require_once(APPROOT.'application/loginwebpage.class.inc.php');

function GenerateBackupsList(string $sListTitleDictKey, string $sNoRecordDictKey, $aListConfig, $aListData, $sTableId): UIBlock
{
	$oBlockForList = new UIContentBlock();
	$oBlockForList->AddSubBlock(TitleUIBlockFactory::MakeNeutral(Dict::S($sListTitleDictKey), 2));
	if (count($aListData) > 0) {
		$oTable = DataTableUIBlockFactory::MakeForStaticData('', $aListConfig, array_reverse($aListData), $sTableId);

		$oTablePanel = PanelUIBlockFactory::MakeForInformation('');
		$oTablePanel->AddSubBlock($oTable);
		$oTablePanel->AddCSSClass('ibo-datatable-panel');
		$oBlockForList->AddSubBlock($oTablePanel);
	} else {
		$oBlockForList->AddSubBlock(
			AlertUIBlockFactory::MakeNeutral('', Dict::S($sNoRecordDictKey))
				->SetIsClosable(false)
				->SetIsCollapsible(false)
		);
	}

	return $oBlockForList;
}


/////////////////////////////////////////////////////////////////////
// Main program
//
LoginWebPage::DoLogin(); // Check user rights and prompt if needed
ApplicationMenu::CheckMenuIdEnabled('BackupStatus');

try {
	$sTransactionId = utils::GetNewTransactionId();
	$oP = new iTopWebPage(Dict::S('bkp-status-title'));
	$oP->set_base(utils::GetAbsoluteUrlAppRoot().'pages/');

	$oBackupTitle = TitleUIBlockFactory::MakeForPage(Dict::S('bkp-status-title'), 1);
	$oP->AddUiBlock($oBackupTitle);

	if (MetaModel::GetConfig()->Get('demo_mode')) {
		$oBackupDisabledCauseDemoMode = AlertUIBlockFactory::MakeForFailure(
			'The feature is disabled.',
			'iTop is in <b>demonstration mode</b>'
		);
		$oBackupDisabledCauseDemoMode
			->SetIsCollapsible(false)
			->SetIsClosable(false);
		$oP->AddUiBlock($oBackupDisabledCauseDemoMode);
	}

	//--- Settings and checks
	$oBlockForChecks = new UIContentBlock();
	$oBlockForChecks->AddSubBlock(TitleUIBlockFactory::MakeNeutral(Dict::S('bkp-status-checks'), 2));

	$oP->AddUiBlock($oBlockForChecks);

	// Availability of mysqldump
	//
	$sMySQLBinDir = MetaModel::GetConfig()->GetModuleSetting('itop-backup', 'mysql_bindir', '');
	$sMySQLBinDir = utils::ReadParam('mysql_bindir', $sMySQLBinDir, true);
	if (empty($sMySQLBinDir)) {
		$sMySQLDump = 'mysqldump';
	} else {
		//echo 'Info - Found mysql_bindir: '.$sMySQLBinDir;
		$sMySQLDump = '"'.$sMySQLBinDir.'/mysqldump"';
	}
	$sCommand = "$sMySQLDump -V 2>&1";

	$aOutput = array();
	$iRetCode = 0;
	exec($sCommand, $aOutput, $iRetCode);
	if ($iRetCode == 0) {
		$oBlockForChecks->AddSubBlock(
			AlertUIBlockFactory::MakeForSuccess('', Dict::Format("bkp-mysqldump-ok", $aOutput[0]))
				->SetIsClosable(false)
				->SetIsCollapsible(false)
		);
	} else {
		if ($iRetCode == 1) {
			$sMySqlDump = Dict::Format("bkp-mysqldump-notfound", implode(' ', $aOutput));
		} else {
			$sMySqlDump = Dict::Format("bkp-mysqldump-issue", $iRetCode);
		}
		$oBlockForChecks->AddSubBlock(
			AlertUIBlockFactory::MakeForWarning($sMySqlDump)
				->SetIsClosable(false)
				->SetIsCollapsible(false)
		);
	}
	foreach ($aOutput as $sLine) {
		IssueLog::Info("$sCommand said: $sLine");
	}

	// Destination directory
	//
	// Make sure the target directory exists and is writeable
	$sBackupDir = realpath(APPROOT.'data/backups/');
	SetupUtils::builddir($sBackupDir);
	if (!is_dir($sBackupDir)) {
		$oBlockForChecks->AddSubBlock(
			AlertUIBlockFactory::MakeForWarning('', Dict::Format('bkp-missing-dir', $sBackupDir))
				->SetIsClosable(false)
				->SetIsCollapsible(false)
		);
	} else {
		$sBackupDir = realpath($sBackupDir); // just for cosmetic purpose (dir separator, as APPROOT contains a hardcoded '/')
		$sDiskSpaceReadable = SetupUtils::HumanReadableSize(SetupUtils::CheckDiskSpace($sBackupDir));
		$oBlockForChecks->AddSubBlock(
			AlertUIBlockFactory::MakeForInformation('', Dict::Format('bkp-free-disk-space', $sDiskSpaceReadable, $sBackupDir))
				->SetIsClosable(false)
				->SetIsCollapsible(false)
		);
		if (!is_writable($sBackupDir)) {
			$oBlockForChecks->AddSubBlock(
				AlertUIBlockFactory::MakeForWarning(Dict::Format('bkp-dir-not-writeable', $sBackupDir))
					->SetIsClosable(false)
					->SetIsCollapsible(false)
			);
		}
	}
	$sBackupDirAuto = $sBackupDir.'/auto/';
	SetupUtils::builddir($sBackupDirAuto);
	$sBackupDirManual = $sBackupDir.'/manual/';
	SetupUtils::builddir($sBackupDirManual);

	// Wrong format
	//
	$sBackupFile = MetaModel::GetConfig()->GetModuleSetting('itop-backup', 'file_name_format', BACKUP_DEFAULT_FORMAT);
	$oBackup = new DBBackupScheduled();
	$sZipName = $oBackup->MakeName($sBackupFile);
	$sZipNameInfo = '';
	if ($sZipName == '') {
		$oBlockForChecks->AddSubBlock(
			AlertUIBlockFactory::MakeForWarning(Dict::Format('bkp-wrong-format-spec', $sBackupFile, BACKUP_DEFAULT_FORMAT))
				->SetIsClosable(false)
				->SetIsCollapsible(false)
		);
	} else {
		$sZipNameInfo = Dict::Format('bkp-name-sample', $sZipName);
	}

	// Week Days
	//
	$sScheduleInfo = empty($sZipNameInfo) ? '' : $sZipNameInfo.'<br>';
	$aWeekDayToString = array(
		1 => Dict::S('DayOfWeek-Monday'),
		2 => Dict::S('DayOfWeek-Tuesday'),
		3 => Dict::S('DayOfWeek-Wednesday'),
		4 => Dict::S('DayOfWeek-Thursday'),
		5 => Dict::S('DayOfWeek-Friday'),
		6 => Dict::S('DayOfWeek-Saturday'),
		7 => Dict::S('DayOfWeek-Sunday'),
	);
	$aDayLabels = array();
	$oBackupExec = new BackupExec();
	foreach ($oBackupExec->InterpretWeekDays() as $iDay) {
		$aDayLabels[] = $aWeekDayToString[$iDay];
	}
	$sDays = implode(', ', $aDayLabels);
	$sBackupTime = MetaModel::GetConfig()->GetModuleSetting('itop-backup', 'time', '23:30');
	$sScheduleInfo .= Dict::Format('bkp-week-days', $sDays, $sBackupTime);

	$iRetention = MetaModel::GetConfig()->GetModuleSetting('itop-backup', 'retention_count', 5);
	$sScheduleInfo .= '<br>'.Dict::Format('bkp-retention', $iRetention);

	$oBlockForChecks->AddSubBlock(
		AlertUIBlockFactory::MakeForInformation('', $sScheduleInfo)
			->SetIsClosable(false)
			->SetIsCollapsible(false)
	);


	//--- List of backups
	//
	$aFiles = $oBackup->ListFiles($sBackupDirAuto);
	$aFilesToDelete = array();
	while (count($aFiles) > $iRetention - 1) {
		$aFilesToDelete[] = array_shift($aFiles);
	}

	$oRestoreMutex = new iTopMutex('restore.'.utils::GetCurrentEnvironment());
	if ($oRestoreMutex->IsLocked()) {
		$sDisableRestore = 'disabled="disabled"';
	} else {
		$sDisableRestore = '';
	}
	$sRestore= Dict::S('bkp-button-restore-now');
	//--- 1st table: list the backups made in the background
	//
	$aDetails = array();
	$sButtonOnClickJS = '';
	foreach ($oBackup->ListFiles($sBackupDirAuto) as $sBackupFile) {
		$sFileName = basename($sBackupFile);
		$sFilePath = 'auto/'.$sFileName;
		if (MetaModel::GetConfig()->Get('demo_mode')) {
			$sName = $sFileName;
		} else {
			$sAjax = utils::GetAbsoluteUrlModulePage('itop-backup', 'ajax.backup.php',
				array(
					'operation' => 'download',
					'file' => $sFilePath,
					'transaction_id' => $sTransactionId,
				)
			);
			$sName = "<a href=\"$sAjax\">".$sFileName.'</a>';
		}
		$sSize = SetupUtils::HumanReadableSize(filesize($sBackupFile));
		$sConfirmRestore = addslashes(Dict::Format('bkp-confirm-restore', $sFileName));
		$sFileEscaped = addslashes($sFilePath);
		$oButton = ButtonUIBlockFactory::MakeNeutral($sRestore);
		$oButton->SetIsDisabled($oRestoreMutex->IsLocked());
		if (in_array($sBackupFile, $aFilesToDelete)) {
			$aDetails[] = array(
				'file' => $sName.' <span class="next_to_delete" title="'.Dict::S('bkp-next-to-delete').'">*</span>',
				'size' => $sSize,
				'actions' => BlockRenderer::RenderBlockTemplates($oButton),
			);
		} else {
			$aDetails[] = array('file' => $sName, 'size' => $sSize, 'actions' => BlockRenderer::RenderBlockTemplates($oButton));
		}
		$sButtonOnClickJS .= '$("#'.$oButton->GetId().'").off("click").on("click", function () {LaunchRestoreNow("'.$sFileEscaped.'", "'.$sConfirmRestore.'");});';
	}
	$aConfig = array(
		'file' => array('label' => Dict::S('bkp-table-file'), 'description' => Dict::S('bkp-table-file+')),
		'size' => array('label' => Dict::S('bkp-table-size'), 'description' => Dict::S('bkp-table-size+')),
		'actions' => array('label' => Dict::S('bkp-table-actions'), 'description' => Dict::S('bkp-table-actions+')),
	);
	$sTableId = 'datatable_background_backups';
	$oP->AddUiBlock(
		GenerateBackupsList(
			'bkp-status-backups-auto',
			'bkp-status-backups-none',
			$aConfig,
			$aDetails,
			$sTableId
		)
	);
	$oP->add_ready_script(
		<<<JS
$('#$sTableId').on('init.dt draw.dt', function(){
	$sButtonOnClickJS
});
JS
	);


	//--- 2nd table: list the backups made manually
	//
	$aDetails = array();
	$sButtonOnClickJS = '';
	foreach ($oBackup->ListFiles($sBackupDirManual) as $sBackupFile) {
		$sFileName = basename($sBackupFile);
		$sFilePath = 'manual/'.$sFileName;
		if (MetaModel::GetConfig()->Get('demo_mode')) {
			$sName = $sFileName;
		} else {
			$sAjax = utils::GetAbsoluteUrlModulePage('itop-backup', 'ajax.backup.php',
				array(
					'operation' => 'download',
					'file' => $sFilePath,
					'transaction_id' => $sTransactionId,
				)
			);
			$sName = "<a href=\"$sAjax\">".$sFileName.'</a>';
		}
		$sSize = SetupUtils::HumanReadableSize(filesize($sBackupFile));
		$sConfirmRestore = addslashes(Dict::Format('bkp-confirm-restore', $sFileName));
		$sFileEscaped = addslashes($sFilePath);
		$oButton = ButtonUIBlockFactory::MakeNeutral("$sRestore");
		$oButton->SetIsDisabled($oRestoreMutex->IsLocked());
		$aDetails[] = array('file' => $sName, 'size' => $sSize, 'actions' => BlockRenderer::RenderBlockTemplates($oButton));
		$sButtonOnClickJS .= '$("#'.$oButton->GetId().'").off("click").on("click", function () {LaunchRestoreNow("'.$sFileEscaped.'", "'.$sConfirmRestore.'");});';
	}
	$aConfig = array(
		'file' => array('label' => Dict::S('bkp-table-file'), 'description' => Dict::S('bkp-table-file+')),
		'size' => array('label' => Dict::S('bkp-table-size'), 'description' => Dict::S('bkp-table-size+')),
		'actions' => array('label' => Dict::S('bkp-table-actions'), 'description' => Dict::S('bkp-table-actions+')),
	);
	$sTableId = 'datatable_manual_backups';
	$oP->AddUiBlock(
		GenerateBackupsList(
			'bkp-status-backups-manual',
			'bkp-status-backups-none',
			$aConfig,
			$aDetails,
			$sTableId
		)
	);
	$oP->add_ready_script(
		<<<JS
$('#$sTableId').on('init.dt draw.dt', function(){
	$sButtonOnClickJS
});
JS
	);


	//--- Backup now
	$oBlockForBackupNow = new UIContentBlock();
	$oBlockForBackupNow->AddSubBlock(TitleUIBlockFactory::MakeNeutral(Dict::S('bkp-button-backup-now'), 2));

	$oP->AddUiBlock($oBlockForBackupNow);


	// Ongoing operation ?
	//
	$oBackupMutex = new iTopMutex('backup.'.utils::GetCurrentEnvironment());
	if ($oBackupMutex->IsLocked()) {
		$oBlockForBackupNow->AddSubBlock(
			AlertUIBlockFactory::MakeForFailure(Dict::S('bkp-backup-running'))
				->SetIsClosable(false)
				->SetIsCollapsible(false)
		);
	}
	$oRestoreMutex = new iTopMutex('restore.'.utils::GetCurrentEnvironment());
	if ($oRestoreMutex->IsLocked()) {
		$oBlockForBackupNow->AddSubBlock(
			AlertUIBlockFactory::MakeForFailure(Dict::S('bkp-restore-running'))
				->SetIsClosable(false)
				->SetIsCollapsible(false)
		);
	}

	// Next occurrence
	//
	/** @var \BackgroundTask $oTask */
	$oTask = MetaModel::GetObjectByName(BackgroundTask::class, BackupExec::class, false);
	if ($oTask)
	{
		$oTimezone = new DateTimeZone(MetaModel::GetConfig()->Get('timezone'));
		$oNext = new DateTime($oTask->Get('next_run_date'), $oTimezone);
		$sNextOccurrence = Dict::Format('bkp-next-backup', $aWeekDayToString[$oNext->Format('N')], $oNext->Format('Y-m-d'),
			$oNext->Format('H:i'));
	}
	else
	{
		$sNextOccurrence = Dict::S('bkp-next-backup-unknown');
	}
	$oBlockForBackupNow->AddSubBlock(
		AlertUIBlockFactory::MakeForInformation('', $sNextOccurrence)
			->SetIsClosable(false)
			->SetIsCollapsible(false)
	);

	// Do backup now
	//
	$sBackUpNow= Dict::S('bkp-button-backup-now');
	$oLaunchBackupButton = ButtonUIBlockFactory::MakeForPrimaryAction($sBackUpNow);
	$oLaunchBackupButton->SetOnClickJsCode('LaunchBackupNow();');
	$oBlockForBackupNow->AddSubBlock($oLaunchBackupButton);

	// restoration panels / hidden info
	$oRestoreSuccess = AlertUIBlockFactory::MakeForSuccess('', '', 'backup_success')
		->AddCSSClass('ibo-is-hidden')
		->SetIsCollapsible(false)
		->SetIsClosable(true);
	$oBlockForBackupNow->AddSubBlock($oRestoreSuccess);
	$oRestoreFailure = AlertUIBlockFactory::MakeForFailure('', '', 'backup_errors')
		->AddCSSClass('ibo-is-hidden')
		->SetIsCollapsible(false)
		->SetIsClosable(true);
	$oBlockForBackupNow->AddSubBlock($oRestoreFailure);
	$oBlockForBackupNow->AddHtml('<input type="hidden" name="restore_token" id="restore_token">');

	$sConfirmBackup = addslashes(Dict::S('bkp-confirm-backup'));
	$sPleaseWaitBackup = addslashes(Dict::S('bkp-wait-backup'));
	$sPleaseWaitRestore = addslashes(Dict::S('bkp-wait-restore'));
	$sRestoreDone = addslashes(Dict::S('bkp-success-restore'));


	$sMySQLBinDir = addslashes(MetaModel::GetConfig()->GetModuleSetting('itop-backup', 'mysql_bindir', ''));
	$sDBHost = addslashes(MetaModel::GetConfig()->Get('db_host'));
	$sDBUser = addslashes(MetaModel::GetConfig()->Get('db_user'));
	$sDBPwd = addslashes(MetaModel::GetConfig()->Get('db_pwd'));
	$sDBName = addslashes(MetaModel::GetConfig()->Get('db_name'));
	$sDBSubName = addslashes(MetaModel::GetConfig()->Get('db_subname'));

	$sEnvironment = addslashes(utils::GetCurrentEnvironment());

	$oP->add_script(
		<<<JS
function LaunchBackupNow()
{
	$('#backup_success').addClass('ibo-is-hidden');
	$('#backup_errors').addClass('ibo-is-hidden');

	if (confirm('$sConfirmBackup'))
	{
		const oModal = CombodoModal.OpenModal({
				title: '$sBackUpNow',
				content: '<i class="ajax-spin fas fa-sync-alt fa-spin"></i> $sPleaseWaitBackup'
		});

		var oParams = {};
		oParams.operation = 'backup';
		oParams.transaction_id = "$sTransactionId";
		$.post(GetAbsoluteUrlModulePage('itop-backup', 'ajax.backup.php'), oParams, function(data){
			if (data.search(/error|exceptio|notice|warning/i) != -1)
			{
				$('#backup_errors').html(data);
				$('#backup_errors').removeClass('ibo-is-hidden');
			}
			else
			{
				window.location.reload();
			}
			oModal.dialog('close');
		});
	}
}
function LaunchRestoreNow(sBackupFile, sConfirmationMessage)
{
	if (!confirm(sConfirmationMessage))
	{
		return;
	}

	const oModal = CombodoModal.OpenModal({
		title: '$sRestore',
		content: '<i class="ajax-spin fas fa-sync-alt fa-spin"></i> $sPleaseWaitRestore'
	});

	$('#backup_success').addClass('ibo-is-hidden');
	$('#backup_errors').addClass('ibo-is-hidden');

	var oParams = {};
	oParams.operation = 'restore_get_token';
	oParams.file = sBackupFile;
	oParams.transaction_id = "$sTransactionId";
	$.post(GetAbsoluteUrlModulePage('itop-backup', 'ajax.backup.php'), oParams, function(data){

		// Get the value of restore_token
		$('#restore_token').val(data.token);

		var oParams = {};
		oParams.operation = 'restore_exec';
		oParams.token = $("#restore_token").val(); // token to check auth + rights without loading MetaModel
		oParams.environment = '$sEnvironment'; // needed to load the config
		oParams.transaction_id = "$sTransactionId";
		if (oParams.token.length > 0) {
			$.post(GetAbsoluteUrlModulePage('itop-backup', 'ajax.backup.php'), oParams, function(data){
				if (data.search(/error|exceptio|notice|warning/i) != -1) {
					$('#backup_success').addClass('ibo-is-hidden');
					$('#backup_errors').html(data);
					$('#backup_errors').removeClass('ibo-is-hidden');
				} else {
					$('#backup_errors').addClass('ibo-is-hidden');
					$('#backup_success').html('$sRestoreDone');
					$('#backup_success').removeClass('ibo-is-hidden');
				}
				oModal.dialog('close');
			});
		} else {
			$('button.restore').prop('disabled', true);
			oModal.dialog('close');
		}
	});
}
JS
	);

	if (MetaModel::GetConfig()->Get('demo_mode'))
	{
		$oP->add_ready_script("$('button').prop('disabled', true).attr('title', 'Disabled in demonstration mode')");
	}
}
catch(Exception $e)
{
	$oP = new iTopWebPage(Dict::S('bkp-status-title'));
	$oP->p('<b>'.$e->getMessage().'</b>');
}

$oP->output();
