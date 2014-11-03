<?php
// Copyright (C) 2014 Combodo SARL
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
 * Monitor the backup
 *
 * @copyright   Copyright (C) 2013 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

if (!defined('__DIR__')) define('__DIR__', dirname(__FILE__));
require_once(__DIR__.'/../../approot.inc.php');
require_once(APPROOT.'application/application.inc.php');
require_once(APPROOT.'application/itopwebpage.class.inc.php');

require_once(APPROOT.'application/startup.inc.php');

require_once(APPROOT.'application/loginwebpage.class.inc.php');


/////////////////////////////////////////////////////////////////////
// Main program
//
LoginWebPage::DoLogin(true); // Check user rights and prompt if needed (must be admin)

//$sOperation = utils::ReadParam('operation', 'menu');
//$oAppContext = new ApplicationContext();

$oP = new iTopWebPage(Dict::S('bkp-status-title'));
$oP->set_base(utils::GetAbsoluteUrlAppRoot().'pages/');


try
{
	$oP->add("<h1>".Dict::S('bkp-status-title')."</h1>");

	if (MetaModel::GetConfig()->Get('demo_mode'))
	{
		$oP->add("<div class=\"header_message message_info\">iTop is in <b>demonstration mode</b>: the feature is disabled.</div>");
	}

	$sImgOk = '<img src="../images/validation_ok.png"> ';
	$sImgError = '<img src="../images/validation_error.png"> ';

	$oP->add("<fieldset>");
	$oP->add("<legend>".Dict::S('bkp-status-checks')."</legend>");

	// Availability of mysqldump
	//
	$sMySQLBinDir = MetaModel::GetConfig()->GetModuleSetting('itop-backup', 'mysql_bindir', '');
	$sMySQLBinDir = utils::ReadParam('mysql_bindir', $sMySQLBinDir, true);
	if (empty($sMySQLBinDir))
	{
		$sMySQLDump = 'mysqldump';
	}
	else
	{
		//echo 'Info - Found mysql_bindir: '.$sMySQLBinDir;
		$sMySQLDump = '"'.$sMySQLBinDir.'/mysqldump"';
	}
	$sCommand = "$sMySQLDump -V 2>&1";

	$aOutput = array();
	$iRetCode = 0;
	exec($sCommand, $aOutput, $iRetCode);
	if ($iRetCode == 0)
	{
		$sMySqlDump = $sImgOk.Dict::Format("bkp-mysqldump-ok", $aOutput[0]);
	}
	elseif ($iRetCode == 1)
	{
		$sMySqlDump = $sImgError.Dict::Format("bkp-mysqldump-notfound", implode(' ', $aOutput));
	}
	else
	{
		$sMySqlDump = $sImgError.Dict::Format("bkp-mysqldump-issue", $iRetCode);
	}
	foreach($aOutput as $sLine)
	{
		//echo 'Info - mysqldump -V said: '.$sLine;
	}
	$oP->p($sMySqlDump);

	// Destination directory
	//
	// Make sure the target directory exists and is writeable
	$sBackupDir = APPROOT.'data/backups/';
	SetupUtils::builddir($sBackupDir);
	if (!is_dir($sBackupDir))
	{
		$oP->p($sImgError.Dict::Format('bkp-missing-dir', $sBackupDir));
	}
	else
	{
		$oP->p(Dict::Format('bkp-free-disk-space', SetupUtils::HumanReadableSize(SetupUtils::CheckDiskSpace($sBackupDir)), $sBackupDir));
		if (!is_writable($sBackupDir))
		{
			$oP->p($sImgError.Dict::Format('bkp-dir-not-writeable', $sBackupDir));
		}
	}
	$sBackupDirAuto = $sBackupDir.'auto/';
	SetupUtils::builddir($sBackupDirAuto);
	$sBackupDirManual = $sBackupDir.'manual/';
	SetupUtils::builddir($sBackupDirManual);

	// Wrong format
	//
	$sBackupFile = MetaModel::GetConfig()->GetModuleSetting('itop-backup', 'file_name_format', BACKUP_DEFAULT_FORMAT);
	$oBackup = new DBBackupScheduled();
	$sZipName = $oBackup->MakeName($sBackupFile);
	if ($sZipName == '')
	{
		$oP->p($sImgError.Dict::Format('bkp-wrong-format-spec', $sBackupFile, BACKUP_DEFAULT_FORMAT));
	}
	else
	{
		$oP->p(Dict::Format('bkp-name-sample', $sZipName));
	}

	// Week Days
	//
	$aWeekDayToString = array(
		1 => Dict::S('DayOfWeek-Monday'),
		2 => Dict::S('DayOfWeek-Tuesday'),
		3 => Dict::S('DayOfWeek-Wednesday'),
		4 => Dict::S('DayOfWeek-Thursday'),
		5 => Dict::S('DayOfWeek-Friday'),
		6 => Dict::S('DayOfWeek-Saturday'),
		7 => Dict::S('DayOfWeek-Sunday')
	);
	$aDayLabels = array();
	$oBackupExec = new BackupExec();
	foreach ($oBackupExec->InterpretWeekDays() as $iDay)
	{
		$aDayLabels[] = $aWeekDayToString[$iDay];
	}
	$sDays = implode(', ', $aDayLabels);
	$sBackupTime = MetaModel::GetConfig()->GetModuleSetting('itop-backup', 'time', '23:30');
	$oP->p(Dict::Format('bkp-week-days', $sDays, $sBackupTime));

	$iRetention = MetaModel::GetConfig()->GetModuleSetting('itop-backup', 'retention_count', 5);
	$oP->p(Dict::Format('bkp-retention', $iRetention));

	$oP->add("</fieldset>");

	// List of backups
	//
	$aFiles = $oBackup->ListFiles($sBackupDirAuto);
	$aFilesToDelete = array();
	while (count($aFiles) > $iRetention - 1)
	{
		$aFilesToDelete[] = array_shift($aFiles);
	}

	$oRestoreMutex = new iTopMutex('restore.'.utils::GetCurrentEnvironment());
	if ($oRestoreMutex->TryLock())
	{
		$oRestoreMutex->Unlock();
		$sDisableRestore = '';
	}
	else
	{
		$sDisableRestore = 'disabled="disabled"';
	}
	
	// 1st table: list the backups made in the background
	//
	$aDetails = array();
	foreach ($oBackup->ListFiles($sBackupDirAuto) as $sBackupFile)
	{
		$sFileName = basename($sBackupFile);
		$sFilePath = 'auto/'.$sFileName;
		if (MetaModel::GetConfig()->Get('demo_mode'))
		{
			$sName = $sFileName;
		}
		else
		{
			$sAjax = utils::GetAbsoluteUrlModulePage('itop-backup', 'ajax.backup.php', array('operation' => 'download', 'file' => $sFilePath));
			$sName = "<a href=\"$sAjax\">".$sFileName.'</a>';
		}
		$sSize = SetupUtils::HumanReadableSize(filesize($sBackupFile));
		$sConfirmRestore = addslashes(Dict::Format('bkp-confirm-restore', $sFileName));
		$sFileEscaped = addslashes($sFilePath);
		$sRestoreBtn = '<button class="restore" onclick="LaunchRestoreNow(\''.$sFileEscaped.'\', \''.$sConfirmRestore.'\');" '.$sDisableRestore.'>'.Dict::S('bkp-button-restore-now').'</button>';
		if (in_array($sBackupFile, $aFilesToDelete))
		{
			$aDetails[] = array('file' => $sName.' <span class="next_to_delete" title="'.Dict::S('bkp-next-to-delete').'">*</span>', 'size' => $sSize, 'actions' => $sRestoreBtn);
		}
		else
		{
			$aDetails[] = array('file' => $sName, 'size' => $sSize, 'actions' => $sRestoreBtn);
		}
	}
	$aConfig = array(
		'file' => array('label' => Dict::S('bkp-table-file'), 'description' => Dict::S('bkp-table-file+')),
		'size' => array('label' => Dict::S('bkp-table-size'), 'description' => Dict::S('bkp-table-size+')),
		'actions' => array('label' => Dict::S('bkp-table-actions'), 'description' => Dict::S('bkp-table-actions+')),
	);
	$oP->add("<fieldset>");
	$oP->add("<legend>".Dict::S('bkp-status-backups-auto')."</legend>");
	if (count($aDetails) > 0)
	{
		$oP->add('<div style="max-height:400px; overflow: auto;">');
		$oP->table($aConfig, array_reverse($aDetails));
		$oP->add('</div>');
	}
	else
	{
		$oP->p(Dict::S('bkp-status-backups-none'));
	}
	$oP->add("</fieldset>");

	// 2nd table: list the backups made manually
	//
	$aDetails = array();
	foreach ($oBackup->ListFiles($sBackupDirManual) as $sBackupFile)
	{
		$sFileName = basename($sBackupFile);
		$sFilePath = 'manual/'.$sFileName;
		if (MetaModel::GetConfig()->Get('demo_mode'))
		{
			$sName = $sFileName;
		}
		else
		{
			$sAjax = utils::GetAbsoluteUrlModulePage('itop-backup', 'ajax.backup.php', array('operation' => 'download', 'file' => $sFilePath));
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
	$oP->add("<fieldset>");
	$oP->add("<legend>".Dict::S('bkp-status-backups-manual')."</legend>");
	if (count($aDetails) > 0)
	{
		$oP->add('<div style="max-height:400px; overflow: auto;">');
		$oP->table($aConfig, array_reverse($aDetails));
		$oP->add('</div>');
	}
	else
	{
		$oP->p(Dict::S('bkp-status-backups-none'));
	}
	$oP->add("</fieldset>");

	// Ongoing operation ?
	//
	$oBackupMutex = new iTopMutex('backup.'.utils::GetCurrentEnvironment());
	if ($oBackupMutex->TryLock())
	{
		$oBackupMutex->Unlock();
	}
	else
	{
		$oP->p(Dict::S('bkp-backup-running'));
	}
	$oRestoreMutex = new iTopMutex('restore.'.utils::GetCurrentEnvironment());
	if ($oRestoreMutex->TryLock())
	{
		$oRestoreMutex->Unlock();
	}
	else
	{
		$oP->p(Dict::S('bkp-restore-running'));
	}

	// Do backup now
	//
	$oBackupExec = new BackupExec();
	$oNext = $oBackupExec->GetNextOccurrence();
	$oP->p(Dict::Format('bkp-next-backup', $aWeekDayToString[$oNext->Format('N')], $oNext->Format('Y-m-d'), $oNext->Format('H:i')));
	$oP->p('<button onclick="LaunchBackupNow();">'.Dict::S('bkp-button-backup-now').'</button>');
	$oP->add('<div id="backup_success" class="header_message message_ok" style="display: none;"></div>');
	$oP->add('<div id="backup_errors" class="header_message message_error" style="display: none;"></div>');
	$oP->add('<input type="hidden" name="restore_token" id="restore_token"/>');
	
	$sConfirmBackup = addslashes(Dict::S('bkp-confirm-backup'));
	$sPleaseWaitBackup = addslashes(Dict::S('bkp-wait-backup'));
	$sPleaseWaitRestore = addslashes(Dict::S('bkp-wait-restore'));
	$sRestoreDone = addslashes(Dict::S('bkp-success-restore'));

	$sMySQLBinDir = addslashes(MetaModel::GetConfig()->GetModuleSetting('itop-backup', 'mysql_bindir', ''));
	$sDBHost = addslashes(MetaModel::GetConfig()->GetDBHost());
	$sDBUser = addslashes(MetaModel::GetConfig()->GetDBUser());
	$sDBPwd = addslashes(MetaModel::GetConfig()->GetDBPwd());
	$sDBName = addslashes(MetaModel::GetConfig()->GetDBName());
	$sDBSubName = addslashes(MetaModel::GetConfig()->GetDBSubName());

	$sEnvironment = addslashes(utils::GetCurrentEnvironment());
	
	$oP->add_script(
<<<EOF
function LaunchBackupNow()
{
	$('#backup_success').hide();
	$('#backup_errors').hide();

	if (confirm('$sConfirmBackup'))
	{
		$.blockUI({ message: '<h1><img src="../images/indicator.gif" /> $sPleaseWaitBackup</h1>' });

		var oParams = {};
		oParams.operation = 'backup';
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
	if (confirm(sConfirmationMessage))
	{
		$.blockUI({ message: '<h1><img src="../images/indicator.gif" /> $sPleaseWaitRestore</h1>' });

		$('#backup_success').hide();
		$('#backup_errors').hide();

		var oParams = {};
		oParams.operation = 'restore_get_token';
		oParams.file = sBackupFile;
		$.post(GetAbsoluteUrlModulePage('itop-backup', 'ajax.backup.php'), oParams, function(data){

			// Get the value of restore_token
			$('#backup_errors').append(data);

			var oParams = {};
			oParams.operation = 'restore_exec';
			oParams.token = $("#restore_token").val();
			oParams.mysql_bindir = '$sMySQLBinDir';
			oParams.db_host = '$sDBHost';
			oParams.db_user = '$sDBUser';
			oParams.db_pwd = '$sDBPwd';
			oParams.db_name = '$sDBName';
			oParams.db_subname = '$sDBSubName';
			oParams.environment = '$sEnvironment';
			if (oParams.token.length > 0)
			{
				$.post(GetAbsoluteUrlModulePage('itop-backup', 'ajax.backup.php'), oParams, function(data){
					if (data.search(/error|exceptio|notice|warning/i) != -1)
					{
						$('#backup_success').hide();
						$('#backup_errors').html(data);
						$('#backup_errors').show();
					}
					else
					{
						$('#backup_errors').hide();
						$('#backup_success').html('$sRestoreDone');
						$('#backup_success').show();
					}
					$.unblockUI();
				});
			}
			else
			{
				$('button.restore').attr('disabled', 'disabled');
				$.unblockUI();
			}
		});
	}
}
EOF
	);

	if (MetaModel::GetConfig()->Get('demo_mode'))
	{
		$oP->add_ready_script("$('button').attr('disabled', 'disabled').attr('title', 'Disabled in demonstration mode')");
	}
}
catch(Exception $e)
{
	$oP->p('<b>'.$e->getMessage().'</b>');
}

$oP->output();
?>
