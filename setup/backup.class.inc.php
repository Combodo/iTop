<?php
// Copyright (C) 2010-2018 Combodo SARL
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

require_once('tar.php');

interface BackupArchive
{
	/**
	 * @param string $sFile
	 *
	 * @return bool <b>TRUE</b> if the file is present, <b>FALSE</b> otherwise.
	 */
	public function hasFile($sFile);

	/**
	 * @param string $sDirectory
	 *
	 * @return bool <b>TRUE</b> if the directory is present, <b>FALSE</b> otherwise.
	 */
	public function hasDir($sDirectory);

	/**
	 * @param string $sDestinationDir
	 * @param string $sArchiveFile
	 *
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function extractFileTo($sDestinationDir, $sArchiveFile);

	/**
	 * Extract a whole directory from the archive.
	 * Usage: $oArchive->extractDirTo('/var/www/html/itop/data', '/production-modules/')
	 *
	 * @param string $sDestinationDir
	 * @param string $sArchiveDir Note: must start and end with a slash !!
	 *
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function extractDirTo($sDestinationDir, $sArchiveDir);

	/**
	 * Returns the entry contents using its name
	 *
	 * @param string $name Name of the entry
	 * @param int $length [optional] The length to be read from the entry. If 0, then the entire entry is read.
	 * @param int $flags [optional] The flags to use to open the archive. the following values may be ORed to it.
	 *     <b>ZipArchive::FL_UNCHANGED</b>
	 *
	 * @return string the contents of the entry on success or <b>FALSE</b> on failure.
	 */
	public function getFromName($name, $length = 0, $flags = null);
}

if (class_exists('ZipArchive')) // The setup must be able to start even if the "zip" extension is not loaded
{
	/**
	 * Handles adding directories into a Zip archive, and a unified API for archive read
	 * suggested enhancement: refactor the API for writing as well
	 */
	class ZipArchiveEx extends ZipArchive implements BackupArchive
	{
		public function addDir($sDir, $sZipDir = '')
		{
			if (is_dir($sDir))
			{
				if ($dh = opendir($sDir))
				{
					// Add the directory
					if (!empty($sZipDir))
					{
						$this->addEmptyDir($sZipDir);
					}

					// Loop through all the files
					while (($sFile = readdir($dh)) !== false)
					{
						// If it's a folder, run the function again!
						if (!is_file($sDir.$sFile))
						{
							// Skip parent and root directories
							if (($sFile !== ".") && ($sFile !== ".."))
							{
								$this->addDir($sDir.$sFile."/", $sZipDir.$sFile."/");
							}
						}
						else
						{
							// Add the files
							$this->addFile($sDir.$sFile, $sZipDir.$sFile);
						}
					}
				}
			}
		}

		/**
		 * @param string $sFile
		 *
		 * @return bool <b>TRUE</b> if the file is present, <b>FALSE</b> otherwise.
		 */
		public function hasFile($sFile)
		{
			return ($this->locateName($sFile) !== false);
		}

		/**
		 * @param string $sDirectory
		 *
		 * @return bool <b>TRUE</b> if the directory is present, <b>FALSE</b> otherwise.
		 */
		public function hasDir($sDirectory)
		{
			return ($this->locateName($sDirectory) !== false);
		}

		/**
		 * @param string $sDestinationDir
		 * @param string $sArchiveFile
		 *
		 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
		 */
		public function extractFileTo($sDestinationDir, $sArchiveFile)
		{
			return $this->extractTo($sDestinationDir, $sArchiveFile);
		}

		/**
		 * Extract a whole directory from the archive.
		 * Usage: $oZip->extractDirTo('/var/www/html/itop/data', '/production-modules/')
		 *
		 * @param string $sDestinationDir
		 * @param string $sZipDir Must start and end with a slash !!
		 *
		 * @return boolean
		 */
		public function extractDirTo($sDestinationDir, $sZipDir)
		{
			$aFiles = array();
			for ($i = 0; $i < $this->numFiles; $i++)
			{
				$sEntry = $this->getNameIndex($i);
				//Use strpos() to check if the entry name contains the directory we want to extract
				if (strpos($sEntry, $sZipDir) === 0)
				{
					//Add the entry to our array if it in in our desired directory
					$aFiles[] = $sEntry;
				}
			}
			// Extract only the selected files
			if ((count($aFiles) > 0) && ($this->extractTo($sDestinationDir, $aFiles) === true))
			{
				return true;
			}

			return false;
		}
	} // class ZipArchiveEx

	class BackupException extends Exception
	{
	}

	class DBBackup
	{
		/**
		 * utf8mb4 was added in MySQL 5.5.3 but works with programs like mysqldump only since MySQL 5.5.33
		 *
		 * @since 2.5 see #1001
		 */
		const MYSQL_VERSION_WITH_UTF8MB4_IN_PROGRAMS = '5.5.33';

		// To be overriden depending on the expected usages
		protected function LogInfo($sMsg)
		{
		}

		protected function LogError($sMsg)
		{
		}

		/** @var Config */
		protected $oConfig;

		// shortcuts used for log purposes
		/** @var string */
		protected $sDBHost;
		/** @var int */
		protected $iDBPort;
		/** @var string */
		protected $sDBName;
		/** @var string */
		protected $sDBSubName;

		/**
		 * Connects to the database to backup
		 *
		 * @param Config $oConfig object containing the database configuration.<br>
		 * If null then uses the default configuration ({@see MetaModel::GetConfig})
		 *
		 * @since 2.5 uses a Config object instead of passing each attribute (there were far too many with the addition of MySQL TLS parameters !)
		 */
		public function __construct($oConfig = null)
		{
			if (is_null($oConfig))
			{
				// Defaulting to the current config
				$oConfig = MetaModel::GetConfig();
			}

			$this->oConfig = $oConfig;

			// init log variables
			CMDBSource::InitServerAndPort($oConfig->Get('db_host'), $this->sDBHost, $this->iDBPort);
			$this->sDBName = $oConfig->get('db_name');
			$this->sDBSubName = $oConfig->get('db_subname');
		}

		protected $sMySQLBinDir = '';

		/**
		 * Create a normalized backup name, depending on the current date/time and Database
		 *
		 * @param sNameSpec string Name and path, eventually containing itop placeholders + time formatting specs
		 */
		public function SetMySQLBinDir($sMySQLBinDir)
		{
			$this->sMySQLBinDir = $sMySQLBinDir;
		}

		/**
		 * Create a normalized backup name, depending on the current date/time and Database
		 *
		 * @param string sNameSpec Name and path, eventually containing itop placeholders + time formatting specs
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

		/**
		 * @deprecated 2.4.0 Zip files are limited to 4 Gb, use CreateCompressedBackup to create tar.gz files
		 *
		 * @param string $sZipFile
		 * @param string|null $sSourceConfigFile
		 *
		 * @throws \BackupException
		 */
		public function CreateZip($sZipFile, $sSourceConfigFile = null)
		{
			$aContents = array();

			// Note: the file is created by tempnam and might not be writeable by another process (Windows/IIS)
			// (delete it before spawning a process)
			$sDataFile = tempnam(SetupUtils::GetTmpDir(), 'itop-');
			$this->LogInfo("Data file: '$sDataFile'");
			$this->DoBackup($sDataFile);
			$aContents[] = array(
				'source' => $sDataFile,
				'dest' => 'itop-dump.sql',
			);

			foreach ($this->GetAdditionalFiles($sSourceConfigFile) as $sArchiveFile => $sSourceFile)
			{
				$aContents[] = array(
					'source' => $sSourceFile,
					'dest' => $sArchiveFile,
				);
			}

			$this->DoZip($aContents, $sZipFile);

			// Windows/IIS: the data file has been created by the spawned process...
			//   trying to delete it will issue a warning, itself stopping the setup abruptely
			@unlink($sDataFile);
		}

		/**
		 * @param string $sTargetFile Path and name, without the extension
		 * @param string|null $sSourceConfigFile Configuration file to embed into the backup, if not the current one
		 *
		 * @throws \Exception
		 */
		public function CreateCompressedBackup($sTargetFile, $sSourceConfigFile = null)
		{
			$this->LogInfo("Creating backup: '$sTargetFile.tar.gz'");

			$oArchive = new ArchiveTar($sTargetFile.'.tar.gz');

			$sTmpFolder = APPROOT.'data/tmp-backup-'.rand(10000, getrandmax());
			$aFiles = $this->PrepareFilesToBackup($sSourceConfigFile, $sTmpFolder);

			$oArchive->createModify($aFiles, '', $sTmpFolder);

			SetupUtils::rrmdir($sTmpFolder);
		}

		/**
		 * Copy files to store into the temporary folder, in addition to the SQL dump
		 *
		 * @param string $sSourceConfigFile
		 * @param string $sTmpFolder
		 *
		 * @return array list of files to archive
		 * @throws \Exception
		 */
		protected function PrepareFilesToBackup($sSourceConfigFile, $sTmpFolder)
		{
			$aRet = array();
			if (is_dir($sTmpFolder))
			{
				SetupUtils::rrmdir($sTmpFolder);
			}
			@mkdir($sTmpFolder, 0777, true);
			if (is_null($sSourceConfigFile))
			{
				$sSourceConfigFile = MetaModel::GetConfig()->GetLoadedFile();
			}
			if (!empty($sSourceConfigFile))
			{
				$sFile = $sTmpFolder.'/config-itop.php';
				copy($sSourceConfigFile, $sFile);
				$aRet[] = $sFile;
			}

			$sDeltaFile = APPROOT.'data/'.utils::GetCurrentEnvironment().'.delta.xml';
			if (file_exists($sDeltaFile))
			{
				$sFile = $sTmpFolder.'/delta.xml';
				copy($sDeltaFile, $sFile);
				$aRet[] = $sFile;
			}
			$sExtraDir = APPROOT.'data/'.utils::GetCurrentEnvironment().'-modules/';
			if (is_dir($sExtraDir))
			{
				$sModules = utils::GetCurrentEnvironment().'-modules';
				$sFile = $sTmpFolder.'/'.$sModules;
				SetupUtils::copydir($sExtraDir, $sFile);
				$aRet[] = $sFile;
			}
			$sDataFile = $sTmpFolder.'/itop-dump.sql';
			$this->DoBackup($sDataFile);
			$aRet[] = $sDataFile;

			return $aRet;
		}

		protected static function EscapeShellArg($sValue)
		{
			// Note: See comment from the 23-Apr-2004 03:30 in the PHP documentation
			//    It suggests to rely on pctnl_* function instead of using escapeshellargs
			return escapeshellarg($sValue);
		}

		/**
		 * Create a backup file
		 *
		 * @param string $sBackupFileName
		 *
		 * @throws \BackupException
		 */
		public function DoBackup($sBackupFileName)
		{
			$sHost = self::EscapeShellArg($this->sDBHost);
			$sUser = self::EscapeShellArg($this->oConfig->Get('db_user'));
			$sPwd = self::EscapeShellArg($this->oConfig->Get('db_pwd'));
			$sDBName = self::EscapeShellArg($this->sDBName);

			// Just to check the connection to the DB (better than getting the retcode of mysqldump = 1)
			$this->DBConnect();

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
				foreach ($aTables as $sTable)
				{
					$aEscapedTables[] = self::EscapeShellArg($sTable);
				}
				$sTables = implode(' ', $aEscapedTables);
			}

			$this->LogInfo("Starting backup of $this->sDBHost/$this->sDBName(suffix:'$this->sDBSubName')");

			$sMySQLDump = $this->GetMysqldumpCommand();

			// Store the results in a temporary file
			$sTmpFileName = self::EscapeShellArg($sBackupFileName);

			$sPortOption = self::GetMysqliCliSingleOption('port', $this->iDBPort);
			$sTlsOptions = self::GetMysqlCliTlsOptions($this->oConfig);

			$sMysqldumpVersion = self::GetMysqldumpVersion($sMySQLDump);
			$bIsMysqldumpSupportUtf8mb4 = (version_compare($sMysqldumpVersion,
					self::MYSQL_VERSION_WITH_UTF8MB4_IN_PROGRAMS) == -1);
			$sMysqldumpCharset = $bIsMysqldumpSupportUtf8mb4 ? 'utf8' : DEFAULT_CHARACTER_SET;

			// Delete the file created by tempnam() so that the spawned process can write into it (Windows/IIS)
			@unlink($sBackupFileName);
			// Note: opt implicitely sets lock-tables... which cancels the benefit of single-transaction!
			//       skip-lock-tables compensates and allows for writes during a backup
			$sCommand = "$sMySQLDump --opt --skip-lock-tables --default-character-set=".$sMysqldumpCharset." --add-drop-database --single-transaction --host=$sHost $sPortOption --user=$sUser --password=$sPwd $sTlsOptions --result-file=$sTmpFileName $sDBName $sTables 2>&1";
			$sCommandDisplay = "$sMySQLDump --opt --skip-lock-tables --default-character-set=".$sMysqldumpCharset." --add-drop-database --single-transaction --host=$sHost $sPortOption --user=xxxxx --password=xxxxx $sTlsOptions --result-file=$sTmpFileName $sDBName $sTables";

			// Now run the command for real
			$this->LogInfo("Executing command: $sCommandDisplay");
			$aOutput = array();
			$iRetCode = 0;
			exec($sCommand, $aOutput, $iRetCode);
			foreach ($aOutput as $sLine)
			{
				$this->LogInfo("mysqldump said: $sLine");
			}
			if ($iRetCode != 0)
			{
				// Cleanup residual output (Happens with Error 2020: Got packet bigger than 'maxallowedpacket' bytes...)
				if (file_exists($sBackupFileName))
				{
					unlink($sBackupFileName);
				}

				$this->LogError("Failed to execute: $sCommandDisplay. The command returned:$iRetCode");
				foreach ($aOutput as $sLine)
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
		 * Helper to create a ZIP out of several files
		 *
		 * @param array $aFiles
		 * @param string $sZipArchiveFile
		 *
		 * @throws \BackupException
		 */
		protected function DoZip($aFiles, $sZipArchiveFile)
		{
			foreach ($aFiles as $aFile)
			{
				$sFile = $aFile['source'];
				if (!is_file($sFile) && !is_dir($sFile))
				{
					throw new BackupException("File '$sFile' does not exist or could not be read");
				}
			}
			// Make sure the target path exists
			$sZipDir = dirname($sZipArchiveFile);
			SetupUtils::builddir($sZipDir);

			$oZip = new ZipArchiveEx();
			$res = $oZip->open($sZipArchiveFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);
			if ($res === true)
			{
				foreach ($aFiles as $aFile)
				{
					if (is_dir($aFile['source']))
					{
						$oZip->addDir($aFile['source'], $aFile['dest']);
					}
					else
					{
						$oZip->addFile($aFile['source'], $aFile['dest']);
					}
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
		 *
		 * @param string $sFile
		 */
		public function DownloadBackup($sFile)
		{
			header('Content-Description: File Transfer');
			header('Content-Type: multipart/x-zip');
			header('Content-Disposition: inline; filename="'.basename($sFile).'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: '.filesize($sFile));
			readfile($sFile);
		}

		/**
		 * Helper to open a Database connection
		 *
		 * @return \mysqli
		 * @throws \BackupException
		 * @uses CMDBSource
		 */
		protected function DBConnect()
		{
			$oConfig = $this->oConfig;
			$sServer = $oConfig->Get('db_host');
			$sUser = $oConfig->Get('db_user');
			$sPwd = $oConfig->Get('db_pwd');
			$sSource = $oConfig->Get('db_name');
			$sTlsEnabled = $oConfig->Get('db_tls.enabled');
			$sTlsCA = $oConfig->Get('db_tls.ca');

			try
			{
				$oMysqli = CMDBSource::GetMysqliInstance($sServer, $sUser, $sPwd, $sSource, $sTlsEnabled, $sTlsCA,
					false);

				if ($oMysqli->connect_errno)
				{
					$sHost = is_null($this->iDBPort) ? $this->sDBHost : $this->sDBHost.' on port '.$this->iDBPort;
					throw new BackupException("Cannot connect to the MySQL server '$sHost' (".$oMysqli->connect_errno.") ".$oMysqli->connect_error);
				}
				if (!$oMysqli->select_db($this->sDBName))
				{
					throw new BackupException("The database '$this->sDBName' does not seem to exist");
				}

				return $oMysqli;
			}
			catch (MySQLException $e)
			{
				throw new BackupException($e->getMessage());
			}
		}

		/**
		 * Helper to enumerate the tables of the database
		 *
		 * @throws \BackupException
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


		/**
		 * @param Config $oConfig
		 *
		 * @return string TLS arguments for CLI programs such as mysqldump. Empty string if the config does not use TLS.
		 *
		 * @see https://dev.mysql.com/doc/refman/5.6/en/encrypted-connection-options.html
		 * @since 2.5
		 */
		public static function GetMysqlCliTlsOptions($oConfig)
		{
			$bDbTlsEnabled = $oConfig->Get('db_tls.enabled');
			if (!$bDbTlsEnabled)
			{
				return '';
			}

			$sTlsOptions = '';
			$sTlsOptions .= ' --ssl';

			// ssl-key parameter : not implemented
			// ssl-cert parameter : not implemented

			$sTlsOptions .= self::GetMysqliCliSingleOption('ssl-ca', $oConfig->Get('db_tls.ca'));

			// ssl-cipher parameter : not implemented
			// ssl-capath parameter : not implemented

			return $sTlsOptions;
		}

		/**
		 * @param string $sCliArgName
		 * @param string $sData
		 *
		 * @return string empty if data is empty, else argument in form of ' --cliargname=data'
		 */
		private static function GetMysqliCliSingleOption($sCliArgName, $sData)
		{
			if (empty($sData))
			{
				return;
			}

			return ' --'.$sCliArgName.'='.self::EscapeShellArg($sData);
		}

		/**
		 * @return string the command to launch mysqldump (without its params)
		 */
		private function GetMysqldumpCommand()
		{
			$sMySQLBinDir = utils::ReadParam('mysql_bindir', $this->sMySQLBinDir, true);
			if (empty($sMySQLBinDir))
			{
				$sMysqldumpCommand = 'mysqldump';
			}
			else
			{
				$sMysqldumpCommand = '"'.$sMySQLBinDir.'/mysqldump"';
			}

			return $sMysqldumpCommand;
		}

		/**
		 * @param string $sMysqldumpCommand
		 *
		 * @return string version of the mysqldump program, as parsed from program return
		 *
		 * @uses mysqldump -V Sample return value : mysqldump  Ver 10.13 Distrib 5.7.19, for Win64 (x86_64)
		 * @since 2.5 needed to check compatibility with utf8mb4 (N°1001)
		 * @throws \BackupException
		 */
		private static function GetMysqldumpVersion($sMysqldumpCommand)
		{
			$sCommand = $sMysqldumpCommand.' -V';
			$aOutput = array();
			exec($sCommand, $aOutput, $iRetCode);

			if ($iRetCode != 0)
			{
				throw new BackupException("mysqldump could not be executed (retcode=$iRetCode): Please make sure it is installed and located at : $sMysqldumpCommand");
			}

			$sMysqldumpOutput = $aOutput[0];
			$aDumpVersionMatchResults = array();
			preg_match('/Distrib (\d+\.\d+\.\d+)/', $sMysqldumpOutput, $aDumpVersionMatchResults);

			return $aDumpVersionMatchResults[1];
		}
	}
}

class TarGzArchive implements BackupArchive
{
	/*
	 * @var ArchiveTar
	 */
	protected $oArchive;
	/*
	 * string[]
	 */
	protected $aFiles = null;

	public function __construct($sFile)
	{
		$this->oArchive = new ArchiveTar($sFile);
	}

	/**
	 * @param string $sFile
	 *
	 * @return bool <b>TRUE</b> if the file is present, <b>FALSE</b> otherwise.
	 */
	public function hasFile($sFile)
	{
		// remove leading and tailing /
		$sFile = trim($sFile, "/ \t\n\r\0\x0B");
		if ($this->aFiles === null)
		{
			// Initial load
			$this->buildFileList();
		}
		foreach ($this->aFiles as $aArchFile)
		{
			if ($aArchFile['filename'] == $sFile)
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * @param string $sDirectory
	 *
	 * @return bool <b>TRUE</b> if the directory is present, <b>FALSE</b> otherwise.
	 */
	public function hasDir($sDirectory)
	{
		// remove leading and tailing /
		$sDirectory = trim($sDirectory, "/ \t\n\r\0\x0B");
		if ($this->aFiles === null)
		{
			// Initial load
			$this->buildFileList();
		}
		foreach ($this->aFiles as $aArchFile)
		{
			if (($aArchFile['typeflag'] == 5) && ($aArchFile['filename'] == $sDirectory))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * @param string $p_path
	 * @param null $aEntries
	 *
	 * @return bool
	 */
	public function extractTo($p_path = '', $aEntries = null)
	{
		return $this->oArchive->extract($p_path);
	}

	/**
	 * @param string $sDestinationDir
	 * @param string $sArchiveFile
	 *
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function extractFileTo($sDestinationDir, $sArchiveFile)
	{
		return $this->oArchive->extractList($sArchiveFile, $sDestinationDir);
	}

	/**
	 * Extract a whole directory from the archive.
	 * Usage: $oArchive->extractDirTo('/var/www/html/itop/data', '/production-modules/')
	 *
	 * @param string $sDestinationDir
	 * @param string $sArchiveDir
	 *
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function extractDirTo($sDestinationDir, $sArchiveDir)
	{
		return $this->oArchive->extractList($sArchiveDir, $sDestinationDir);
	}

	/**
	 * Returns the entry contents using its name
	 *
	 * @param string $name Name of the entry
	 * @param int $length unused.
	 * @param int $flags unused.
	 *
	 * @return string the contents of the entry on success or <b>FALSE</b> on failure.
	 */
	public function getFromName($name, $length = 0, $flags = null)
	{
		return $this->oArchive->extractInString($name);
	}

	/**
	 */
	protected function buildFileList()
	{
		$this->aFiles = $this->oArchive->listContent();
	}
}

