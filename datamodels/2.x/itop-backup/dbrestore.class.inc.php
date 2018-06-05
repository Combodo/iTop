<?php
// Copyright (C) 2014-2017 Combodo SARL
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


class DBRestore extends DBBackup
{
	protected function LogInfo($sMsg)
	{
		//IssueLog::Info('non juste info: '.$sMsg);
	}
	protected function LogError($sMsg)
	{
		IssueLog::Error($sMsg);
	}

	protected function LoadDatabase($sDataFile)
	{
		$this->LogInfo("Loading data onto $this->sDBHost/$this->sDBName(suffix:'$this->sDBSubName')");

		// Just to check the connection to the DB (more accurate than getting the retcode of mysql)
		$oMysqli = $this->DBConnect();

		$sHost = self::EscapeShellArg($this->sDBHost);
		$sUser = self::EscapeShellArg($this->sDBUser);
		$sPwd = self::EscapeShellArg($this->sDBPwd);
		$sDBName = self::EscapeShellArg($this->sDBName);
		if (empty($this->sMySQLBinDir))
		{
			$sMySQLExe = 'mysql';
		}
		else
		{
			$sMySQLExe = '"'.$this->sMySQLBinDir.'/mysql"';
		}
		if (is_null($this->iDBPort))
		{
			$sPortOption = '';
		}
		else
		{
			$sPortOption = '--port='.$this->iDBPort.' ';
		}

		$sDataFileEscaped = self::EscapeShellArg($sDataFile);
		$sCommand = "$sMySQLExe --default-character-set=utf8 --host=$sHost $sPortOption --user=$sUser --password=$sPwd $sDBName <$sDataFileEscaped 2>&1";
		$sCommandDisplay = "$sMySQLExe --default-character-set=utf8 --host=$sHost $sPortOption --user=xxxx --password=xxxx $sDBName <$sDataFileEscaped 2>&1";

		// Now run the command for real
		$this->LogInfo("Executing command: $sCommandDisplay");
		$aOutput = array();
		$iRetCode = 0;
		exec($sCommand, $aOutput, $iRetCode);
		foreach($aOutput as $sLine)
		{
			$this->LogInfo("mysql said: $sLine");
		}
		if ($iRetCode != 0)
		{
			$this->LogError("Failed to execute: $sCommandDisplay. The command returned:$iRetCode");
			foreach($aOutput as $sLine)
			{
				$this->LogError("mysql said: $sLine");
			}
			if (count($aOutput) == 1) 
			{
				$sMoreInfo = trim($aOutput[0]); 
			}
			else
			{
				$sMoreInfo = "Check the log file '".realpath(APPROOT.'/log/error.log')."' for more information.";
			}
			throw new BackupException("Failed to execute mysql: ".$sMoreInfo);
		}
	}

	/**
	 * @deprecated Use RestoreFromCompressedBackup instead
	 * @param $sZipFile
	 * @param string $sEnvironment
	 */
	public function RestoreFromZip($sZipFile, $sEnvironment = 'production')
	{
		$this->RestoreFromCompressedBackup($sZipFile, $sEnvironment);
	}

	/**
	 * @param string $sFile A file with the extension .zip or .tar.gz
	 * @param string $sEnvironment Target environment
	 *
	 * @throws \Exception
	 */
	public function RestoreFromCompressedBackup($sFile, $sEnvironment = 'production')
	{
		$this->LogInfo("Starting restore of ".basename($sFile));

		$sNormalizedFile = strtolower(basename($sFile));
		if (substr($sNormalizedFile, -4) == '.zip')
		{
			$this->LogInfo('zip file detected');
			$oArchive = new ZipArchiveEx();
			$oArchive->open($sFile);
		}
		elseif (substr($sNormalizedFile, -7) == '.tar.gz')
		{
			$this->LogInfo('tar.gz file detected');
			$oArchive = new TarGzArchive($sFile);
		}
		else
		{
			throw new Exception('Unsupported format for a backup file: '.$sFile);
		}

		// Load the database
		//
		$sDataDir = APPROOT.'data/tmp-backup-'.rand(10000, getrandmax());

		SetupUtils::builddir($sDataDir); // Here is the directory
		$oArchive->extractTo($sDataDir);

		$sDataFile = $sDataDir.'/itop-dump.sql';
		$this->LoadDatabase($sDataFile);

		// Update the code
		//
		$sDeltaFile = APPROOT.'data/'.$sEnvironment.'.delta.xml';

		if (is_file($sDataDir.'/delta.xml'))
		{
			// Extract and rename delta.xml => <env>.delta.xml;
			rename($sDataDir.'/delta.xml', $sDeltaFile);
		}
		else
		{
			@unlink($sDeltaFile);
		}
		if (is_dir(APPROOT.'data/production-modules/'))
		{
			SetupUtils::rrmdir(APPROOT.'data/production-modules/');
		}
		if (is_dir($sDataDir.'/production-modules'))
		{
			rename($sDataDir.'/production-modules', APPROOT.'data/production-modules/');
		}

		$sConfigFile = APPROOT.'conf/'.$sEnvironment.'/config-itop.php';
		@chmod($sConfigFile, 0770); // Allow overwriting the file
		rename($sDataDir.'/config-itop.php', $sConfigFile);
		@chmod($sConfigFile, 0444); // Read-only

		SetupUtils::rrmdir($sDataDir);

		$oEnvironment = new RunTimeEnvironment($sEnvironment);
		$oEnvironment->CompileFrom($sEnvironment);
	}
}
