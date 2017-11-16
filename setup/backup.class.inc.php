<?php
// Copyright (C) 2010-2017 Combodo SARL
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

interface BackupArchive
{
	/**
	 * @param string $sFile
	 * @return bool <b>TRUE</b> if the file is present, <b>FALSE</b> otherwise.
	 */
	public function hasFile($sFile);

	/**
	 * @param string $sDirectory
	 * @return bool <b>TRUE</b> if the directory is present, <b>FALSE</b> otherwise.
	 */
	public function hasDir($sDirectory);

	/**
	 * @param string $sDestinationDir
	 * @param string $sArchiveFile
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function extractFileTo($sDestinationDir, $sArchiveFile);

	/**
	 * Extract a whole directory from the archive.
	 * Usage: $oArchive->extractDirTo('/var/www/html/itop/data', '/production-modules/')
	 * @param string $sDestinationDir
	 * @param string $sArchiveDir Note: must start and end with a slash !!
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function extractDirTo($sDestinationDir, $sArchiveDir);

	/**
	 * Returns the entry contents using its name
	 * @param string $name Name of the entry
	 * @param int $length [optional] The length to be read from the entry. If 0, then the entire entry is read.
	 * @param int $flags [optional] The flags to use to open the archive. the following values may be ORed to it. <b>ZipArchive::FL_UNCHANGED</b>
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
					if (!empty($sZipDir)) $this->addEmptyDir($sZipDir);
						
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
		 * @return bool <b>TRUE</b> if the file is present, <b>FALSE</b> otherwise.
		 */
		public function hasFile($sFile)
		{
			return ($this->locateName($sFile) !== false);
		}

		/**
		 * @param string $sDirectory
		 * @return bool <b>TRUE</b> if the directory is present, <b>FALSE</b> otherwise.
		 */
		public function hasDir($sDirectory)
		{
			return ($this->locateName($sDirectory) !== false);
		}

		/**
		 * @param string $sDestinationDir
		 * @param string $sArchiveFile
		 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
		 */
		public function extractFileTo($sDestinationDir, $sArchiveFile)
		{
			return $this->extractTo($sDestinationDir, $sArchiveFile);
		}

		/**
		 * Extract a whole directory from the archive.
		 * Usage: $oZip->extractDirTo('/var/www/html/itop/data', '/production-modules/')
		 * @param string $sDestinationDir
		 * @param string $sZipDir Must start and end with a slash !!
		 * @return boolean
		 */
		public function extractDirTo($sDestinationDir, $sZipDir)
		{
			$aFiles = array();
			for($i = 0; $i < $this->numFiles; $i++)
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
			if ((count($aFiles)  > 0) && ($this->extractTo($sDestinationDir, $aFiles) === true)) 
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

		/**
		 * @deprecated 2.4.0 Zip files are limited to 4 Gb, use CreateCompressedBackup to create tar.gz files
		 * @param $sZipFile
		 * @param null $sSourceConfigFile
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
		 */
		public function CreateCompressedBackup($sTargetFile, $sSourceConfigFile = null)
		{
			$this->LogInfo("Creating backup: '$sTargetFile.tar.gz'");

			// Note: PharData::compress strips everything after the first dot found in the name of the tar, then it adds .tar.gz
			//       Hence, we have to create our own file in the target directory, and rename it when the process is complete
            $sTarFile = dirname($sTargetFile) . '/' . str_replace('.', '_', basename($sTargetFile)) . '.tar';
            $this->LogInfo("Tar file: '$sTarFile'");
			$oArchive = new PharData($sTarFile);

			foreach ($this->GetAdditionalFiles($sSourceConfigFile) as $sArchiveFile => $sSourceFile)
			{
				if (is_dir($sSourceFile))
				{
					$this->LogInfo("Adding directory into tar file: '$sSourceFile', recorded as '$sArchiveFile'");
					// Note: Phar::buildFromDirectory does not allow to specify a destination subdirectory
					//       Hence we have to add all files one by one
					$sSourceDir = realpath($sSourceFile);
					$sArchiveDir = trim($sArchiveFile, '/');

					$oDirectoryIterator = new RecursiveDirectoryIterator($sSourceDir, RecursiveDirectoryIterator::SKIP_DOTS);
					$oAllFiles = new RecursiveIteratorIterator($oDirectoryIterator);
					foreach ($oAllFiles as $oSomeFile)
					{
						if ($oSomeFile->isDir()) continue;

						// Replace the local path by the archive path - the resulting string starts with a '/'
						$sRelativePathName = substr($oSomeFile->getRealPath(), strlen($sSourceDir));
						// Under Windows realpath gives a mix of backslashes and slashes
						$sRelativePathName = str_replace('\\', '/', $sRelativePathName);
						$sArchiveFile = $sArchiveDir.$sRelativePathName;
						$oArchive->addFile($oSomeFile->getPathName(), $sArchiveFile);
					}
				}
				else
				{
					$this->LogInfo("Adding file into tar file: '$sSourceFile', recorded as '$sArchiveFile'");
					$oArchive->addFile($sSourceFile, $sArchiveFile);
				};
			}

			// Note: the file is created by tempnam and might not be writeable by another process (Windows/IIS)
			// (delete it before spawning a process)
			// Note: the file is created by tempnam and might not be writeable by another process (Windows/IIS)
			// (delete it before spawning a process)
			$sDataFile = tempnam(SetupUtils::GetTmpDir(), 'itop-');
			$this->LogInfo("Data file: '$sDataFile'");
			$this->DoBackup($sDataFile);

			$oArchive->addFile($sDataFile, 'itop-dump.sql');
			// todo: reduce disk space needed by the operation by piping the output of mysqldump directly into the tar
			// tip1 : this syntax works fine (did not work with addFile)
			//$oArchive->buildFromIterator(
			//	new ArrayIterator(
			//		array('production.delta.xml' => fopen(ROOTDIR.'production.delta.xml', 'rb'))
			//	)
			//);
			// tip2 : use the phar stream by redirecting the output of mysqldump into
			//        phar://var/www/itop/data/backups/manual/trunk_pro-2017-07-05_15_10.tar.gz/itop-dump.sql
			//
			//	new ArrayIterator(
			//		array('production.delta.xml' => fopen(ROOTDIR.'production.delta.xml', 'rb'))
			//	)
			//);

			// Windows/IIS: the data file has been created by the spawned process...
			//   trying to delete it will issue a warning, itself stopping the setup abruptely
			@unlink($sDataFile);

			if (file_exists($sTarFile.'.gz'))
			{
				// Prevent the gzip compression from failing -> the whole operation is an overwrite
				$this->LogInfo("Overwriting tar.gz: '$sTarFile'");
				unlink($sTarFile.'.gz');
			}
			// zlib is a must!
			$oArchive->compress(Phar::GZ);

			// Cleanup
			unset($oArchive);
			unlink($sTarFile);

			if ($sTargetFile != $sTarFile)
			{
				// Give the file the expected name
				if (file_exists($sTargetFile.'.gz'))
				{
					// Remove it -> the whole operation is an overwrite
					$this->LogInfo("Overwriting tar.gz: '$sTargetFile'");
					unlink($sTargetFile.'.gz');
				}
				rename($sTarFile.'.gz', $sTargetFile.'.tar.gz');
			}
		}

		/**
		 * List files to store into the archive, in addition to the SQL dump
		 * @return array of sArchiveName => sFilePath
		 */
		protected function GetAdditionalFiles($sSourceConfigFile)
		{
			$aRet = array();
			if (is_null($sSourceConfigFile))
			{
				$sSourceConfigFile = MetaModel::GetConfig()->GetLoadedFile();
			}
			if (!empty($sSourceConfigFile))
			{
				$aRet['config-itop.php'] = $sSourceConfigFile;
			}

			$sDeltaFile = APPROOT.'data/'.utils::GetCurrentEnvironment().'.delta.xml';
			if (file_exists($sDeltaFile))
			{
				$aRet['delta.xml'] = $sDeltaFile;
			}
			$sExtraDir = APPROOT.'data/'.utils::GetCurrentEnvironment().'-modules/';
			if (is_dir($sExtraDir))
			{
				$sModules = utils::GetCurrentEnvironment().'-modules/';
				$aRet[$sModules] = $sExtraDir;
			}
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
			// Note: opt implicitely sets lock-tables... which cancels the benefit of single-transaction!
			//       skip-lock-tables compensates and allows for writes during a backup
			$sCommand = "$sMySQLDump --opt --skip-lock-tables --default-character-set=utf8 --add-drop-database --single-transaction --host=$sHost $sPortOption --user=$sUser --password=$sPwd --result-file=$sTmpFileName $sDBName $sTables 2>&1";
			$sCommandDisplay = "$sMySQLDump --opt --skip-lock-tables --default-character-set=utf8 --add-drop-database --single-transaction --host=$sHost $sPortOption --user=xxxxx --password=xxxxx --result-file=$sTmpFileName $sDBName $sTables";
	
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
				// Cleanup residual output (Happens with Error 2020: Got packet bigger than 'maxallowedpacket' bytes...)
				if (file_exists($sBackupFileName))
				{
					unlink($sBackupFileName);
				}
	
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
		 * Helper to create a ZIP out of several files
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
			if ($res === TRUE)
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
}

class TarGzArchive implements BackupArchive
{
	/*
	 * @var PharData
	 */
	protected $oPharArchive;
	/*
	 * string[]
	 */
	protected $aFiles = null;

	public function __construct($sFile)
	{
		$this->oPharArchive = new PharData($sFile);
	}

	/**
	 * @param string $sFile
	 * @return bool <b>TRUE</b> if the file is present, <b>FALSE</b> otherwise.
	 */
	public function hasFile($sFile)
	{
		return $this->oPharArchive->offsetExists($sFile);
	}

	/**
	 * @param string $sDirectory
	 * @return bool <b>TRUE</b> if the directory is present, <b>FALSE</b> otherwise.
	 */
	public function hasDir($sDirectory)
	{
		return $this->oPharArchive->offsetExists($sDirectory);
	}

	/**
	 * @param string $sDestinationDir
	 * @param string $sArchiveFile
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function extractFileTo($sDestinationDir, $sArchiveFile)
	{
		return $this->oPharArchive->extractTo($sDestinationDir, $sArchiveFile, true);
	}

	/**
	 * Extract a whole directory from the archive.
	 * Usage: $oArchive->extractDirTo('/var/www/html/itop/data', '/production-modules/')
	 * @param string $sDestinationDir
	 * @param string $sArchiveDir Note: must start and end with a slash !!
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function extractDirTo($sDestinationDir, $sArchiveDir)
	{
		$aFiles = array();
		foreach ($this->getFiles($sArchiveDir) as $oFileInfo)
		{
			$aFiles[] = $oFileInfo->getRelativePath();
		}
		if ((count($aFiles) > 0) && ($this->oPharArchive->extractTo($sDestinationDir, $aFiles, true) === true))
		{
			return true;
		}
		return false;
	}

	/**
	 * Returns the entry contents using its name
	 * @param string $name Name of the entry
	 * @param int $length [optional] The length to be read from the entry. If 0, then the entire entry is read.
	 * @param int $flags [optional] The flags to use to open the archive. the following values may be ORed to it. <b>ZipArchive::FL_UNCHANGED</b>
	 * @return string the contents of the entry on success or <b>FALSE</b> on failure.
	 */
	public function getFromName($name, $length = 0, $flags = null)
	{
		$oFileInfo = $this->oPharArchive->offsetGet($name);
		$sFile = $oFileInfo->getPathname();
		$sRet = file_get_contents($sFile);
		return $sRet;
	}

	/**
	 * @param string|null $sArchivePath Path to search for
	 * @return null
	 */
	public function getFiles($sArchivePath = null)
	{
		if ($this->aFiles === null)
		{
			// Initial load
			$this->buildFileList();
		}
		if ($sArchivePath === null)
		{
			// Take them all
			$aRet = $this->aFiles;
		}
		else
		{
			// Filter out files not in the given path
			$aRet = array();
			foreach ($this->aFiles as $oFileInfo)
			{
				if ($oFileInfo->isUnder($sArchivePath))
				{
					$aRet[] = $oFileInfo;
				}
			}
		}
		return $aRet;
	}

	/**
	 * @param PharData|null $oPharData
	 * @param string $sArchivePath Path relatively to the archive root
	 */
	protected function buildFileList($oPharData = null, $sArchivePath = '/')
	{
		if ($oPharData === null)
		{
			$oPharData = $this->oPharArchive;
		}
		foreach($oPharData as $oPharFileInfo)
		{
			if($oPharFileInfo->isDir())
			{
				$oSubDirectory = new PharData($oPharFileInfo->getPathname());
				// Recurse
				$this->buildFileList($oSubDirectory, $sArchivePath.'/'.$oPharFileInfo->getFileName());
			}
			else
			{
				$this->aFiles[] = new TarGzFileInfo($oPharFileInfo, $sArchivePath);
			}
		}
	}
}

class TarGzFileInfo
{
	public function __construct(PharFileInfo $oFileInfo, $sArchivePath)
	{
		$this->oPharFileInfo = $oFileInfo;
		$this->sArchivePath = trim($sArchivePath, '/');
	}

	protected $sArchivePath;
	protected $oPharFileInfo;

	public function getPathname()
	{
		return $this->oPharFileInfo->getPathname();
	}

	public function getRelativePath()
	{
		return $this->sArchivePath.'/'.$this->oPharFileInfo->getFilename();
	}

	public function isUnder($sArchivePath)
	{
		$sTestedPath = trim($sArchivePath, '/');
		return (strpos($this->sArchivePath, $sTestedPath) === 0);
	}
}

