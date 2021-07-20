<?php
/**
 * Copyright (C) 2010-2021 Combodo SARL
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
use Combodo\iTop\Application\UI\Base\Component\Title\TitleUIBlockFactory;
use Combodo\iTop\Application\UI\Base\UIBlock;

if (!defined('__DIR__')) {
	define('__DIR__', dirname(__FILE__));
}
if (!defined('APPROOT')) {
	require_once(__DIR__.'/../../approot.inc.php');
}
require_once(APPROOT.'application/application.inc.php');
require_once(APPROOT.'application/itopwebpage.class.inc.php');

require_once(APPROOT.'application/startup.inc.php');

require_once(APPROOT.'application/loginwebpage.class.inc.php');


function DecorateErrorMessages(string $sMessage)
{
	return '<b>'.$sMessage.'</b>';
}

function GenerateBackupsList(string $sListTitleDictKey, string $sNoRecordDictKey, $aListConfig, $aListData): UIBlock
{
	$oFieldsetForList = new FieldSet(Dict::S($sListTitleDictKey));

	if (count($aListData) > 0) {
		$oFieldsetForList->AddSubBlock(
			DataTableUIBlockFactory::MakeForForm(uniqid('form_', true), $aListConfig, array_reverse($aListData))
		);
	} else {
		$oFieldsetForList->AddSubBlock(
			AlertUIBlockFactory::MakeNeutral('', Dict::S($sNoRecordDictKey))
				->SetIsClosable(false)
				->SetIsCollapsible(false)
		);
	}

	return $oFieldsetForList;
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
	$oFieldsetChecks = new FieldSet(Dict::S('bkp-status-checks'));
	$oP->AddUiBlock($oFieldsetChecks);

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
		$oFieldsetChecks->AddSubBlock(
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
		$oFieldsetChecks->AddSubBlock(
			AlertUIBlockFactory::MakeForWarning('', DecorateErrorMessages($sMySqlDump))
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
		$oFieldsetChecks->AddSubBlock(
			AlertUIBlockFactory::MakeForWarning('', DecorateErrorMessages(Dict::Format('bkp-missing-dir', $sBackupDir)))
				->SetIsClosable(false)
				->SetIsCollapsible(false)
		);
	} else {
		$sBackupDir = realpath($sBackupDir); // just for cosmetic purpose (dir separator, as APPROOT contains a hardcoded '/')
		$sDiskSpaceReadable = SetupUtils::HumanReadableSize(SetupUtils::CheckDiskSpace($sBackupDir));
		$oFieldsetChecks->AddSubBlock(
			AlertUIBlockFactory::MakeForInformation('', Dict::Format('bkp-free-disk-space', $sDiskSpaceReadable, $sBackupDir))
				->SetIsClosable(false)
				->SetIsCollapsible(false)
		);
		if (!is_writable($sBackupDir)) {
			$oFieldsetChecks->AddSubBlock(
				AlertUIBlockFactory::MakeForWarning('', DecorateErrorMessages(Dict::Format('bkp-dir-not-writeable', $sBackupDir)))
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
		$oFieldsetChecks->AddSubBlock(
			AlertUIBlockFactory::MakeForWarning(
				'',
				DecorateErrorMessages(Dict::Format('bkp-wrong-format-spec', $sBackupFile, BACKUP_DEFAULT_FORMAT))
			)
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

	$oFieldsetChecks->AddSubBlock(
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

	//--- 1st table: list the backups made in the background
	//
	$aDetails = array();
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
		$sRestoreBtn = '<button class="restore" onclick="LaunchRestoreNow(\''.$sFileEscaped.'\', \''.$sConfirmRestore.'\');" '.$sDisableRestore.'>'.Dict::S('bkp-button-restore-now').'</button>';
		if (in_array($sBackupFile, $aFilesToDelete)) {
			$aDetails[] = array(
				'file' => $sName.' <span class="next_to_delete" title="'.Dict::S('bkp-next-to-delete').'">*</span>',
				'size' => $sSize,
				'actions' => $sRestoreBtn,
			);
		} else {
			$aDetails[] = array('file' => $sName, 'size' => $sSize, 'actions' => $sRestoreBtn);
		}
	}
	$aConfig = array(
		'file' => array('label' => Dict::S('bkp-table-file'), 'description' => Dict::S('bkp-table-file+')),
		'size' => array('label' => Dict::S('bkp-table-size'), 'description' => Dict::S('bkp-table-size+')),
		'actions' => array('label' => Dict::S('bkp-table-actions'), 'description' => Dict::S('bkp-table-actions+')),
	);

	$oP->AddUiBlock(
		GenerateBackupsList(
			'bkp-status-backups-auto',
			'bkp-status-backups-none',
			$aConfig,
			$aDetails
		)
	);


	//--- 2nd table: list the backups made manually
	//
	$aDetails = array();
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
		$sRestoreBtn = '<button class="restore" onclick="LaunchRestoreNow(\''.$sFileEscaped.'\', \''.$sConfirmRestore.'\');" '.$sDisableRestore.'>'.Dict::S('bkp-button-restore-now').'</button>';
		$aDetails[] = array('file' => $sName, 'size' => $sSize, 'actions' => $sRestoreBtn);
	}
	$aConfig = array(
		'file' => array('label' => Dict::S('bkp-table-file'), 'description' => Dict::S('bkp-table-file+')),
		'size' => array('label' => Dict::S('bkp-table-size'), 'description' => Dict::S('bkp-table-size+')),
		'actions' => array('label' => Dict::S('bkp-table-actions'), 'description' => Dict::S('bkp-table-actions+')),
	);

	$oP->AddUiBlock(
		GenerateBackupsList(
			'bkp-status-backups-manual',
			'bkp-status-backups-none',
			$aConfig,
			$aDetails
		)
	);


	//--- Backup now
	$oFieldsetBackupNow = new FieldSet(Dict::S('bkp-button-backup-now'));
	$oP->AddSubBlock($oFieldsetBackupNow);

	// Ongoing operation ?
	//
	$oBackupMutex = new iTopMutex('backup.'.utils::GetCurrentEnvironment());
	if ($oBackupMutex->IsLocked()) {
		$oFieldsetBackupNow->AddSubBlock(
			AlertUIBlockFactory::MakeForFailure('', DecorateErrorMessages(Dict::S('bkp-backup-running')))
				->SetIsClosable(false)
				->SetIsCollapsible(false)
		);
	}
	$oRestoreMutex = new iTopMutex('restore.'.utils::GetCurrentEnvironment());
	if ($oRestoreMutex->IsLocked()) {
		$oFieldsetBackupNow->AddSubBlock(
			AlertUIBlockFactory::MakeForFailure('', DecorateErrorMessages(Dict::S('bkp-restore-running')))
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
	$oFieldsetBackupNow->AddSubBlock(
		AlertUIBlockFactory::MakeForInformation('', $sNextOccurrence)
			->SetIsClosable(false)
			->SetIsCollapsible(false)
	);

	// Do backup now
	//
	$oLaunchBackupButton = ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('bkp-button-backup-now'));
	$oLaunchBackupButton->SetOnClickJsCode('LaunchBackupNow();');
	$oFieldsetBackupNow->AddSubBlock($oLaunchBackupButton);

	// restoration panels / hidden info
	$oRestoreSuccess = AlertUIBlockFactory::MakeForSuccess('', '', 'backup_success')
		->AddCSSClass('ibo-is-hidden')
		->SetIsCollapsible(false)
		->SetIsClosable(true);
	$oFieldsetBackupNow->AddSubBlock($oRestoreSuccess);
	$oRestoreFailure = AlertUIBlockFactory::MakeForFailure('', '', 'backup_errors')
		->AddCSSClass('ibo-is-hidden')
		->SetIsCollapsible(false)
		->SetIsClosable(true);
	$oFieldsetBackupNow->AddSubBlock($oRestoreFailure);
	$oFieldsetBackupNow->AddHtml('<input type="hidden" name="restore_token" id="restore_token">');

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
		$.blockUI({ message: '<h1><img src="../images/indicator.gif" /> $sPleaseWaitBackup</h1>' });

		var oParams = {};
		oParams.operation = 'backup';
		oParams.transaction_id = "$sTransactionId";
		$.post(GetAbsoluteUrlModulePage('itop-backup', 'ajax.backup.php'), oParams, function(data){
			if (data.search(/error|exceptio|notice|warning/i) != -1)
			{
				$('#backup_errors').html(data);
				$('#backup_errors').show();
			}
			else
			{
				window.location.reload();
			}
			$.unblockUI();
		});
	}
}
function LaunchRestoreNow(sBackupFile, sConfirmationMessage)
{
	if (!confirm(sConfirmationMessage))
	{
		return;
	}

	$.blockUI({ message: '<h1><img src="../images/indicator.gif" /> $sPleaseWaitRestore</h1>' });

	$('#backup_success').addClass('ibo-is-hidden');
	$('#backup_errors').addClass('ibo-is-hidden');

	var oParams = {};
	oParams.operation = 'restore_get_token';
	oParams.file = sBackupFile;
	oParams.transaction_id = "$sTransactionId";
	$.post(GetAbsoluteUrlModulePage('itop-backup', 'ajax.backup.php'), oParams, function(data){

		// Get the value of restore_token
		$('#backup_errors').append(data);

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
				$.unblockUI();
			});
		} else {
			$('button.restore').prop('disabled', true);
			$.unblockUI();
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
