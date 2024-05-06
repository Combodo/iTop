<?php
// Copyright (C) 2014-2024 Combodo SAS
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


require_once(APPROOT.'setup/setuputils.class.inc.php');
require_once(APPROOT.'setup/backup.class.inc.php');
require_once(APPROOT.'core/mutex.class.inc.php');


define('BACKUP_DEFAULT_FORMAT', '__DB__-%Y-%m-%d_%H_%M');

class BackupHandler extends ModuleHandlerAPI
{
	public static function OnMetaModelStarted()
	{
		try
		{
			$oRestoreMutex = new iTopMutex('restore.'.utils::GetCurrentEnvironment());
			if ($oRestoreMutex->IsLocked())
			{
				IssueLog::Info(__class__.'::'.__function__.' A user is trying to use iTop while a restore is running. The requested page is in read-only mode.');
				MetaModel::GetConfig()->Set('access_mode', ACCESS_READONLY, 'itop-backup');
				MetaModel::GetConfig()->Set('access_message', ' - '.dict::S('bkp-restore-running'), 'itop-backup');
			}
		}
		catch(Exception $e)
		{
			IssueLog::Error(__class__.'::'.__function__.' Failed to check if a backup/restore is running: '.$e->getMessage());
		}
	}
}

class DBBackupScheduled extends DBBackup
{
	protected function LogInfo($sMsg)
	{
		echo $sMsg."\n";
		IssueLog::Info($sMsg);
	}

	protected function LogError($sMsg)
	{
		IssueLog::Error($sMsg);
		echo 'Error: '.$sMsg."\n";
	}

	/**
	 * List and order by date the backups in the given directory
	 * Note: the algorithm is currently based on the file modification date... because there is no "creation date" in general
	 * @param string $sBackupDir
	 * @return array
	 */
	public function ListFiles($sBackupDir)
	{
		$aFiles = array();
		$aTimes = array();
		// Legacy format -limited to 4 Gb
		foreach(glob($sBackupDir.'*.zip') as $sFilePath)
		{
			$aFiles[] = $sFilePath;
			$aTimes[] = filemtime($sFilePath); // unix time
		}
		// Modern format
		foreach(glob($sBackupDir.'*.tar.gz') as $sFilePath)
		{
			$aFiles[] = $sFilePath;
			$aTimes[] = filemtime($sFilePath); // unix time
		}
		array_multisort($aTimes, $aFiles);
	
		return $aFiles;
	}
}

class BackupExec extends AbstractWeeklyScheduledProcess
{
	protected $sBackupDir;
	protected $iRetentionCount;

	protected function GetModuleName()
	{
		return 'itop-backup';
	}

	protected function GetDefaultModuleSettingTime()
	{
		return '23:30';
	}

	/**
	 * @param string $sBackupDir Target directory, defaults to APPROOT/data/backups/auto
	 * @param int $iRetentionCount default to the value given in the configuration file 'retentation_count'<br>
	 *      set to 0 to disable this feature
	 */
	public function __construct($sBackupDir = null, $iRetentionCount = null)
	{
		if (is_null($sBackupDir))
		{
			$this->sBackupDir = APPROOT.'data/backups/auto/';
		}
		else
		{
			$this->sBackupDir = $sBackupDir;
		}
		if (is_null($iRetentionCount))
		{
			$this->iRetentionCount = MetaModel::GetConfig()->GetModuleSetting($this->GetModuleName(), 'retention_count', 5);
		}
		else
		{
			$this->iRetentionCount = $iRetentionCount;
		}
	}

	/**
	 * @param int $iUnixTimeLimit
	 * @return string
	 * @throws Exception
	 */
	public function Process($iUnixTimeLimit)
	{
		$oMutex = new iTopMutex('backup.'.utils::GetCurrentEnvironment());
		$oMutex->Lock();

		try
		{
			// Make sure the target directory exists
			SetupUtils::builddir($this->sBackupDir);
	
			$oBackup = new DBBackupScheduled();

			// Eliminate files exceeding the retention setting
			//
			if ($this->iRetentionCount > 0)
			{
				$aFiles = $oBackup->ListFiles($this->sBackupDir);
				while (count($aFiles) >= $this->iRetentionCount)
				{
					$sFileToDelete = array_shift($aFiles);
					unlink($sFileToDelete);
					if (file_exists($sFileToDelete))
					{
						// Ok, do not loop indefinitely on this
						break;
					}
				}
			}
	
			// Do execute the backup
			//
			$oBackup->SetMySQLBinDir(MetaModel::GetConfig()->GetModuleSetting($this->GetModuleName(), 'mysql_bindir', ''));

			$sBackupFileFormat = MetaModel::GetConfig()->GetModuleSetting($this->GetModuleName(), 'file_name_format', '__DB__-%Y-%m-%d_%H_%M');
			$sName = $oBackup->MakeName($sBackupFileFormat);
			if ($sName == '')
			{
				$sName = $oBackup->MakeName(BACKUP_DEFAULT_FORMAT);
			}
			$sBackupFile = $this->sBackupDir.$sName;
			$sSourceConfigFile = APPCONF.utils::GetCurrentEnvironment().'/'.ITOP_CONFIG_FILE;
			try
			{
				$oBackup->CreateCompressedBackup($sBackupFile, $sSourceConfigFile);
			}
			catch (BackupException $e)
			{
				throw new ProcessFatalException($e->getMessage());
			}
		}
		catch (Exception $e)
		{
			$oMutex->Unlock();
			throw $e;
		}
		$oMutex->Unlock();
		return "Created the backup: $sBackupFile";
	}

}
