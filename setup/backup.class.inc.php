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


class BackupException extends Exception
{
}

class DBBackup
{
	// To be overriden depending on the expected usages
	protected function LogInfo($sMsg)
	{
	}
	protected function LogError($sMsg)
	{
	}

	protected $sDBHost;
	protected $iDBPort;
	protected $sDBUser;
	protected $sDBPwd;
	protected $sDBName;
	protected $sDBSubName;

	/**
	 * Connects to the database to backup
	 * By default, connects to the current MetaModel (must be loaded)
	 * 	 	 
	 * @param sDBHost string Database host server
	 * @param $sDBUser string User login
	 * @param $sDBPwd string User password
	 * @param $sDBName string Database name
	 * @param $sDBSubName string Prefix to the tables of itop in the database
	 */
	public function __construct($sDBHost = null, $sDBUser = null, $sDBPwd = null, $sDBName = null, $sDBSubName = null)
	{
		if (is_null($sDBHost))
		{
			// Defaulting to the current config
			$sDBHost = MetaModel::GetConfig()->GetDBHost();
			$sDBUser = MetaModel::GetConfig()->GetDBUser();
			$sDBPwd = MetaModel::GetConfig()->GetDBPwd();
			$sDBName = MetaModel::GetConfig()->GetDBName();
			$sDBSubName = MetaModel::GetConfig()->GetDBSubName();
		}

		// Compute the port (if present in the host name)
		$aConnectInfo = explode(':', $sDBHost);
		$sDBHostName = $aConnectInfo[0];
		if (count($aConnectInfo) > 1)
		{
			$iDBPort = $aConnectInfo[1];
		}
		else
		{
			$iDBPort = null;
		}

		$this->sDBHost = $sDBHostName;
		$this->iDBPort = $iDBPort;
		$this->sDBUser = $sDBUser;
		$this->sDBPwd = $sDBPwd;
		$this->sDBName = $sDBName;
		$this->sDBSubName = $sDBSubName;
	}

	protected $sMySQLBinDir = '';
	/**
	 * Create a normalized backup name, depending on the current date/time and Database
	 * @param sNameSpec string Name and path, eventually containing itop placeholders + time formatting specs
	 */	 	
	public function SetMySQLBinDir($sMySQLBinDir)
	{
		$this->sMySQLBinDir = $sMySQLBinDir;
	}	

	/**
	 * Create a normalized backup name, depending on the current date/time and Database
	 * @param sNameSpec string Name and path, eventually containing itop placeholders + time formatting specs
	 */	 	
	public function MakeName($sNameSpec = "__DB__-%Y-%m-%d")
	{
		$sFileName = $sNameSpec;
		$sFileName = str_replace('__HOST__', $this->sDBHost, $sFileName);
		$sFileName = str_replace('__DB__', $this->sDBName, $sFileName);
		$sFileName = str_replace('__SUBNAME__', $this->sDBSubName, $sFileName);
		// Transform %Y, etc.
		$sFileName = strftime($sFileName);
		return $sFileName;
	}

	public function CreateZip($sZipFile, $sSourceConfigFile = null)
	{
		// Note: the file is created by tempnam and might not be writeable by another process (Windows/IIS)
		// (delete it before spawning a process)
		$sDataFile = tempnam(SetupUtils::GetTmpDir(), 'itop-');
		$this->LogInfo("Data file: '$sDataFile'");

		$aContents = array();
		$aContents[] = array(
			'source' => $sDataFile,
			'dest' => 'itop-dump.sql',
		);

		if (is_null($sSourceConfigFile))
		{
			$sSourceConfigFile = MetaModel::GetConfig()->GetLoadedFile();
		}
		if (!empty($sSourceConfigFile))
		{
			$aContents[] = array(
				'source' => $sSourceConfigFile,
				'dest' => 'config-itop.php',
			);
		}

		$this->DoBackup($sDataFile);

		$sDeltaFile = APPROOT.'data/'.utils::GetCurrentEnvironment().'.delta.xml';
		if (file_exists($sDeltaFile))
		{
			$aContents[] = array(
				'source' => $sDeltaFile,
				'dest' => 'delta.xml',
			);
		}
		$this->DoZip($aContents, $sZipFile);
		// Windows/IIS: the data file has been created by the spawned process...
		//   trying to delete it will issue a warning, itself stopping the setup abruptely
		@unlink($sDataFile);
	}


	protected static function EscapeShellArg($sValue)
	{
		// Note: See comment from the 23-Apr-2004 03:30 in the PHP documentation
		//    It suggests to rely on pctnl_* function instead of using escapeshellargs
		return escapeshellarg($sValue);
	}

	/**
	 * Create a backup file	
	 */	
	public function DoBackup($sBackupFileName)
	{
		$sHost = self::EscapeShellArg($this->sDBHost);
		$sUser = self::EscapeShellArg($this->sDBUser);
		$sPwd = self::EscapeShellArg($this->sDBPwd);
		$sDBName = self::EscapeShellArg($this->sDBName);

		// Just to check the connection to the DB (better than getting the retcode of mysqldump = 1)
		$oMysqli = $this->DBConnect();

		$sTables = '';
		if ($this->sDBSubName != '')
		{
			// This instance of iTop uses a prefix for the tables, so there may be other tables in the database
			// Let's explicitely list all the tables and views to dump
			$aTables = $this->EnumerateTables();
			if (count($aTables) == 0)
			{
				// No table has been found with the given prefix
				throw new BackupException("No table has been found with the given prefix");
			}
			$aEscapedTables = array();
			foreach($aTables as $sTable)
			{
				$aEscapedTables[] = self::EscapeShellArg($sTable);
			}
			$sTables = implode(' ', $aEscapedTables);
		}

		$this->LogInfo("Starting backup of $this->sDBHost/$this->sDBName(suffix:'$this->sDBSubName')");

		$sMySQLBinDir = utils::ReadParam('mysql_bindir', $this->sMySQLBinDir, true);
		if (empty($sMySQLBinDir))
		{
			$sMySQLDump = 'mysqldump';
		}
		else
		{
			$sMySQLDump = '"'.$sMySQLBinDir.'/mysqldump"';
		}

		// Store the results in a temporary file
		$sTmpFileName = self::EscapeShellArg($sBackupFileName);
		if (is_null($this->iDBPort))
		{
			$sPortOption = '';
		}
		else
		{
			$sPortOption = '--port='.$this->iDBPort.' ';
		}
		// Delete the file created by tempnam() so that the spawned process can write into it (Windows/IIS)
		unlink($sBackupFileName);
		$sCommand = "$sMySQLDump --opt --default-character-set=utf8 --add-drop-database --single-transaction --host=$sHost $sPortOption --user=$sUser --password=$sPwd --result-file=$sTmpFileName $sDBName $sTables 2>&1";
		$sCommandDisplay = "$sMySQLDump --opt --default-character-set=utf8 --add-drop-database --single-transaction --host=$sHost $sPortOption --user=xxxxx --password=xxxxx --result-file=$sTmpFileName $sDBName $sTables";

		// Now run the command for real
		$this->LogInfo("Executing command: $sCommandDisplay");
		$aOutput = array();
		$iRetCode = 0;
		exec($sCommand, $aOutput, $iRetCode);
		foreach($aOutput as $sLine)
		{
			$this->LogInfo("mysqldump said: $sLine");
		}
		if ($iRetCode != 0)
		{
			$this->LogError("Failed to execute: $sCommandDisplay. The command returned:$iRetCode");
			foreach($aOutput as $sLine)
			{
				$this->LogError("mysqldump said: $sLine");
			}
			if (count($aOutput) == 1) 
			{
				$sMoreInfo = trim($aOutput[0]); 
			}
			else
			{
				$sMoreInfo = "Check the log files '".realpath(APPROOT.'/log/setup.log or error.log')."' for more information.";
			}
			throw new BackupException("Failed to execute mysqldump: ".$sMoreInfo);
		}
	}

	/**
	 * Helper to create a ZIP out of a data file and the configuration file
	 */	 	
	protected function DoZip($aFiles, $sZipArchiveFile)
	{
		foreach ($aFiles as $aFile)
		{
			$sFile = $aFile['source'];
			if (!is_file($sFile))
			{
				throw new BackupException("File '$sFile' does not exist or could not be read");
			}
		}
		// Make sure the target path exists
		$sZipDir = dirname($sZipArchiveFile);
		SetupUtils::builddir($sZipDir);
	
		$oZip = new ZipArchive();
		$res = $oZip->open($sZipArchiveFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);
		if ($res === TRUE)
		{
			foreach ($aFiles as $aFile)
			{
				$oZip->addFile($aFile['source'], $aFile['dest']);
			}
			if ($oZip->close())
			{
				$this->LogInfo("Archive: $sZipArchiveFile created");
			}
			else
			{
				$this->LogError("Failed to save zip archive: $sZipArchiveFile");
				throw new BackupException("Failed to save zip archive: $sZipArchiveFile");
			}
		}
		else
		{
			$this->LogError("Failed to create zip archive: $sZipArchiveFile.");
			throw new BackupException("Failed to create zip archive: $sZipArchiveFile.");
		}
	}

	/**
	 * Helper to download the file directly from the browser	
	 */	
	public function DownloadBackup($sFile)
	{
		$oP = new ajax_page('backup');
		$oP->SetContentType("multipart/x-zip");
		$oP->SetContentDisposition('inline', basename($sFile));
		$oP->add(file_get_contents($sFile));
		$oP->output();
	}

	/**
	 * Helper to open a Database connection
	 */
	protected function DBConnect()
	{
		if (is_null($this->iDBPort))
		{
			$oMysqli = new mysqli($this->sDBHost, $this->sDBUser, $this->sDBPwd);
		}
		else
		{
			$oMysqli = new mysqli($this->sDBHost, $this->sDBUser, $this->sDBPwd, '', $this->iDBPort);
		}
		if ($oMysqli->connect_errno)
		{
			$sHost = is_null($this->iDBPort) ? $this->sDBHost : $this->sDBHost.' on port '.$this->iDBPort;
			throw new BackupException("Cannot connect to the MySQL server '$this->sDBHost' (".$oMysqli->connect_errno . ") ".$oMysqli->connect_error);
		}
		if (!$oMysqli->select_db($this->sDBName))
		{
			throw new BackupException("The database '$this->sDBName' does not seem to exist");
		}
		return $oMysqli;
	}

	/**
	 * Helper to enumerate the tables of the database
	 */	 	
	protected function EnumerateTables()
	{
		$oMysqli = $this->DBConnect();
		if ($this->sDBSubName != '')
		{
			$oResult = $oMysqli->query("SHOW TABLES LIKE '{$this->sDBSubName}%'");
		}
		else
		{
			$oResult = $oMysqli->query("SHOW TABLES");
		}
		if (!$oResult)
		{
			throw new BackupException("Failed to execute the SHOW TABLES query: ".$oMysqli->error);
		}
		$aTables = array();
		while ($aRow = $oResult->fetch_row())
		{
			$aTables[] = $aRow[0];
		}
		return $aTables;
	}
}

?>
