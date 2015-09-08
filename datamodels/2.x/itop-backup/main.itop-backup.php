<?php
// Copyright (C) 2014-2015 Combodo SARL
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
			$oBackupMutex = new iTopMutex('backup.'.utils::GetCurrentEnvironment());
			if ($oBackupMutex->TryLock())
			{
				$oBackupMutex->Unlock();
			}
			else
			{
				// Not needed: the DB dump is done in a single transaction
				//MetaModel::GetConfig()->Set('access_mode', ACCESS_READONLY, 'itop-backup');
				//MetaModel::GetConfig()->Set('access_message', ' - '.dict::S('bkp-backup-running'), 'itop-backup');
			}
	
			$oRestoreMutex = new iTopMutex('restore.'.utils::GetCurrentEnvironment());
			if ($oRestoreMutex->TryLock())
			{
				$oRestoreMutex->Unlock();
			}
			else
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
		static $bDebug = null;
		if ($bDebug == null)
		{
			$bDebug = MetaModel::GetConfig()->GetModuleSetting('itop-backup', 'debug', false);
		}

		if ($bDebug)
		{
			echo $sMsg."\n";
		}
	}

	protected function LogError($sMsg)
	{
		static $bDebug = null;
		if ($bDebug == null)
		{
			$bDebug = MetaModel::GetConfig()->GetModuleSetting('itop-backup', 'debug', false);
		}

		IssueLog::Error($sMsg);
		if ($bDebug)
		{
			echo 'Error: '.$sMsg."\n";
		}
	}

	/**
	 * List and order by date the backups in the given directory 	
	 * Note: the algorithm is currently based on the file modification date... because there is no "creation date" in general
	 */	
	public function ListFiles($sBackupDir)
	{
		$aFiles = array();
		$aTimes = array();
		foreach(glob($sBackupDir.'*.zip') as $sFilePath)
		{
			$aFiles[] = $sFilePath;
			$aTimes[] = filemtime($sFilePath); // unix time
		}
		array_multisort($aTimes, $aFiles);
	
		return $aFiles;
	}
}

class BackupExec implements iScheduledProcess
{
	protected $sBackupDir;
	protected $iRetentionCount;

	/**
	 * Constructor
	 * @param sBackupDir string Target directory, defaults to APPROOT/data/backups/auto
	 * @param iRetentionCount int Rotation (default to the value given in the configuration file 'retentation_count') set to 0 to disable this feature	 	 
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
			$this->iRetentionCount = MetaModel::GetConfig()->GetModuleSetting('itop-backup', 'retention_count', 5);
		}
		else
		{
			$this->iRetentionCount = $iRetentionCount;
		}
	}

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
			$oBackup->SetMySQLBinDir(MetaModel::GetConfig()->GetModuleSetting('itop-backup', 'mysql_bindir', ''));
	
			$sBackupFile = MetaModel::GetConfig()->GetModuleSetting('itop-backup', 'file_name_format', '__DB__-%Y-%m-%d_%H_%M');
			$sName = $oBackup->MakeName($sBackupFile);
			if ($sName == '')
			{
				$sName = $oBackup->MakeName(BACKUP_DEFAULT_FORMAT);
			}
			$sZipFile = $this->sBackupDir.$sName.'.zip';
			$sSourceConfigFile = APPCONF.utils::GetCurrentEnvironment().'/'.ITOP_CONFIG_FILE;
			$oBackup->CreateZip($sZipFile, $sSourceConfigFile);
		}
		catch (Exception $e)
		{
			$oMutex->Unlock();
			throw $e;
		}
		$oMutex->Unlock();
		return "Created the backup: $sZipFile";
	}

	/*
		Interpret current setting for the week days
		@returns array of int (monday = 1)
	*/
	public function InterpretWeekDays()
	{
		static $aWEEKDAYTON = array('monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6, 'sunday' => 7);
		$aDays = array();
		$sWeekDays = MetaModel::GetConfig()->GetModuleSetting('itop-backup', 'week_days', 'monday, tuesday, wednesday, thursday, friday');
		if ($sWeekDays != '')
		{
			$aWeekDaysRaw = explode(',', $sWeekDays);
			foreach ($aWeekDaysRaw as $sWeekDay)
			{
				$sWeekDay = strtolower(trim($sWeekDay));
				if (array_key_exists($sWeekDay, $aWEEKDAYTON))
				{
					$aDays[] = $aWEEKDAYTON[$sWeekDay];
				}
				else
				{
					throw new Exception("'itop-backup: wrong format for setting 'week_days' (found '$sWeekDay')");
				}
			}
		}
		if (count($aDays) == 0)
		{
			throw new Exception("'itop-backup: missing setting 'week_days'");
		}
		$aDays = array_unique($aDays);   
		sort($aDays);
		return $aDays;
	}

	/*
		Gives the exact time at which the process must be run next time
		@returns DateTime
	*/
	public function GetNextOccurrence()
	{
		$bEnabled = MetaModel::GetConfig()->GetModuleSetting('itop-backup', 'enabled', true);
		if (!$bEnabled)
		{
			$oRet = new DateTime('3000-01-01');
		}
		else
		{
			// 1st - Interpret the list of days as ordered numbers (monday = 1)
			// 
			$aDays = $this->InterpretWeekDays();	
	
			// 2nd - Find the next active week day
			//
			$sBackupTime = MetaModel::GetConfig()->GetModuleSetting('itop-backup', 'time', '23:30');
			if (!preg_match('/[0-2][0-9]:[0-5][0-9]/', $sBackupTime))
			{
				throw new Exception("'itop-backup: wrong format for setting 'time' (found '$sBackupTime')");
			}
			$oNow = new DateTime();
			$iNextPos = false;
			for ($iDay = $oNow->format('N') ; $iDay <= 7 ; $iDay++)
			{
				$iNextPos = array_search($iDay, $aDays);
				if ($iNextPos !== false)
				{
					if (($iDay > $oNow->format('N')) || ($oNow->format('H:i') < $sBackupTime))
					{
						break;
					}
				}
			}
	
			// 3rd - Compute the result
			//
			if ($iNextPos === false)
			{
				// Jump to the first day within the next week
				$iFirstDayOfWeek = $aDays[0];
				$iDayMove = $oNow->format('N') - $iFirstDayOfWeek;
				$oRet = clone $oNow;
				$oRet->modify('-'.$iDayMove.' days');
				$oRet->modify('+1 weeks');
			}
			else
			{
				$iNextDayOfWeek = $aDays[$iNextPos];
				$iMove = $iNextDayOfWeek - $oNow->format('N');
				$oRet = clone $oNow;
				$oRet->modify('+'.$iMove.' days');
			}
			list($sHours, $sMinutes) = explode(':', $sBackupTime);
			$oRet->setTime((int)$sHours, (int) $sMinutes);
		}
		return $oRet;
	}
}

class ItopBackup extends ModuleHandlerAPI
{
	public static function OnMenuCreation()
	{
		if (UserRights::IsAdministrator())
		{
			$oAdminMenu = new MenuGroup('AdminTools', 80 /* fRank */);
			new WebPageMenuNode('BackupStatus', utils::GetAbsoluteUrlModulePage('itop-backup', 'status.php'), $oAdminMenu->GetIndex(), 15 /* fRank */);
		}
	}
}

