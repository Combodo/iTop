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
	protected $sDBHost;
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
			$this->sDBHost = MetaModel::GetConfig()->GetDBHost();
			$this->sDBUser = MetaModel::GetConfig()->GetDBUser();
			$this->sDBPwd = MetaModel::GetConfig()->GetDBPwd();
			$this->sDBName = MetaModel::GetConfig()->GetDBName();
			$this->sDBSubName = MetaModel::GetConfig()->GetDBSubName();
		}
		else
		{
			$this->sDBHost = $sDBHost;
			$this->sDBUser = $sDBUser;
			$this->sDBPwd = $sDBPwd;
			$this->sDBName = $sDBName;
			$this->sDBSubName = $sDBSubName;
		}
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
		$sDataFile = tempnam(SetupUtils::GetTmpDir(), 'itop-');
		SetupPage::log("Info - Data file: '$sDataFile'");

		if (is_null($sSourceConfigFile))
		{
			$sSourceConfigFile = MetaModel::GetConfig()->GetLoadedFile();
		}

		$this->DoBackup($sDataFile);

		$this->DoZip($sDataFile, $sSourceConfigFile, $sZipFile);
		unlink($sDataFile);
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

		$sMySQLBinDir = utils::ReadParam('mysql_bindir', '', true);
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
		$sCommand = "$sMySQLDump --opt --default-character-set=utf8 --add-drop-database --single-transaction --host=$sHost --user=$sUser --password=$sPwd --result-file=$sTmpFileName $sDBName $sTables 2>&1";
		$sCommandDisplay = "$sMySQLDump --opt --default-character-set=utf8 --add-drop-database --single-transaction --host=$sHost --user=xxxxx --password=xxxxx --result-file=$sTmpFileName $sDBName $sTables";

		// Now run the command for real
		SetupPage::log("Info - Executing command: $sCommandDisplay");
		$aOutput = array();
		$iRetCode = 0;
		exec($sCommand, $aOutput, $iRetCode);
		if ($iRetCode != 0)
		{
			SetupPage::log("Error - retcode=".$iRetCode."\n");
			throw new BackupException("Failed to execute mysqldump. Return code: $iRetCode");
		}
		foreach($aOutput as $sLine)
		{
			SetupPage::log("Info - mysqldump said: $sLine");
		}
	}

	/**
	 * Helper to create a ZIP out of a data file and the configuration file
	 */	 	
	protected function DoZip($sDataFile, $sConfigFile, $sZipArchiveFile)
	{
		if (!is_file($sConfigFile))
		{
			throw new BackupException("Configuration file '$sConfigFile' does not exist or could not be read");
		}
		// Make sure the target path exists
		$sZipDir = dirname($sZipArchiveFile);
		SetupUtils::builddir($sZipDir);
	
		$oZip = new ZipArchive();
		$res = $oZip->open($sZipArchiveFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);
		if ($res === TRUE)
		{
			$oZip->addFile($sDataFile, 'itop-dump.sql');
			$oZip->addFile($sConfigFile, 'config-itop.php');
		
			if ($oZip->close())
			{
				SetupPage::log("Info - Archive: $sZipArchiveFile created");
			}
			else
			{
				SetupPage::log("Error - Failed to save zip archive: $sZipArchiveFile");
				throw new BackupException("Failed to save zip archive: $sZipArchiveFile");
			}
		}
		else
		{
			SetupPage::log("Error - Failed to create zip archive: $sZipArchiveFile.");
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
		$oMysqli = new mysqli($this->sDBHost, $this->sDBUser, $this->sDBPwd);
		if ($oMysqli->connect_errno)
		{
			throw new BackupException("Cannot connect to the MySQL server '$this->sDBHost' (".$mysqli->connect_errno . ") ".$mysqli->connect_error);
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
