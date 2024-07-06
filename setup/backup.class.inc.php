<?php
// Copyright (C) 2010-2024 Combodo SAS
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

use Combodo\iTop\Service\InterfaceDiscovery\InterfaceDiscovery;

require_once(APPROOT.'core/tar-itop.class.inc.php');

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
	 * Note: both params $length and $flags are unused in the current archive format (TarGzArchive)... But this is a
	 * public interface and there might be some other implementations out there so: DON'T REMOVE THEM!
	 *
	 * @param string $name Name of the entry
	 * @param int $length [optional] The length to be read from the entry. If 0, then the entire entry is read.
	 * @param int $flags [optional] The flags to use to open the archive.<br>
	 *      Was used with Zip archives, example : <code>ZipArchive::FL_UNCHANGED</code>
	 *
	 * @return string the contents of the entry on success or <b>FALSE</b> on failure.
	 */
	public function getFromName($name, $length = 0, $flags = null);
}


class BackupException extends Exception
{
}


class DBBackup
{
	/**
	 * utf8mb4 was added in MySQL 5.5.3 but works with programs like mysqldump only since MySQL 5.5.33
	 *
	 * @since 2.5.0 see N°1001
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
	 * @since 2.5.0 uses a Config object instead of passing each attribute (there were far too many with the addition of MySQL TLS parameters
	 *     !)
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
	 * @param string sMySQLBinDir  Name and path, eventually containing itop placeholders + time formatting specs
	 */
	public function SetMySQLBinDir($sMySQLBinDir)
	{
		$this->sMySQLBinDir = $sMySQLBinDir;
	}

	/**
	 * Create a normalized backup name, depending on the current date/time and Database
	 *
	 * @param string $sNameSpec Name and path, eventually containing itop placeholders + time formatting following the strftime() format {@link https://www.php.net/manual/fr/function.strftime.php}
	 * @param \DateTime|null $oDateTime Date time to use for the name
	 *
	 * @return string Name of the backup file WITHOUT the file extension (eg. `.tar.gz`)
	 * @since 3.1.0 N°5279 Add $oDateTime parameter
	 */
	public function MakeName(string $sNameSpec = "__DB__-%Y-%m-%d", DateTime $oDateTime = null)
	{
		if ($oDateTime === null) {
			$oDateTime = new DateTime();
		}

		$sFileName = $sNameSpec;
		$sFileName = str_replace('__HOST__', $this->sDBHost, $sFileName);
		$sFileName = str_replace('__DB__', $this->sDBName, $sFileName);
		$sFileName = str_replace('__SUBNAME__', $this->sDBSubName, $sFileName);

		// Transform date/time placeholders (%Y, %m, etc)
		// N°5279 - As of PHP 8.1 strftime() is deprecated so we use \DateTime::format() instead
		//
		// IMPORTANT: We can't use \DateTime::format() directly on the whole filename as it would also format characters that are not supposed to be. eg. "__DB__-Y-m-d-production" would become "itopdb-2023-02-09-+01:00Thu, 09 Feb 2023 11:34:01 +0100202309"
		$sFileName = preg_replace_callback(
			'/(%[a-zA-Z])/',
			function ($aMatches) use ($oDateTime) {
				$sDateTimeFormatPlaceholder = utils::StrftimeFormatToDateTimeFormat($aMatches[0]);
				return $oDateTime->format($sDateTimeFormatPlaceholder);
			},
			$sFileName,
		);

		return $sFileName;
	}

	/**
	 * @param string $sTargetFile Path and name, without the extension
	 * @param string|null $sSourceConfigFile Configuration file to embed into the backup, if not the current one
	 *
	 * @throws \CoreException if CMDBSource not initialized
	 * @throws \BackupException if archive cannot be created
	 * @throws \Exception
	 */
	public function CreateCompressedBackup($sTargetFile, $sSourceConfigFile = null)
	{
		//safe zone for db backup => cron is stopped/ itop in readonly
		$bIsCmdbSourceInitialized = CMDBSource::GetMysqli() instanceof mysqli;
		if (!$bIsCmdbSourceInitialized) {
			$sErrorMsg = 'Cannot backup : CMDBSource not initialized !';
			$this->LogError($sErrorMsg);
			throw new CoreException($sErrorMsg);
		}

		$this->LogInfo("Creating backup: '$sTargetFile.tar.gz'");

		$oArchive = new ITopArchiveTar($sTargetFile.'.tar.gz');

		$sTmpFolder = APPROOT.'data/tmp-backup-'.rand(10000, getrandmax());
		$aFiles = $this->PrepareFilesToBackup($sSourceConfigFile, $sTmpFolder);

		$sFilesList = var_export($aFiles, true);
		$this->LogInfo("backup: adding to archive files '$sFilesList'");
		$bArchiveCreationResult = $oArchive->createModify($aFiles, '', $sTmpFolder);
		if (!$bArchiveCreationResult) {
			$sErrorMsg = 'Cannot backup : unable to create archive';
			$this->LogError($sErrorMsg);
			throw new BackupException($sErrorMsg);
		}

		$this->LogInfo("backup: removing tmp folder '$sTmpFolder'");
		SetupUtils::rrmdir($sTmpFolder);
	}

	/**
	 * Copy files to store into the temporary folder, in addition to the SQL dump
	 *
	 * @param string $sSourceConfigFile
	 * @param string $sTmpFolder
	 * @param bool $bSkipSQLDumpForTesting 
	 *
	 * @return array list of files to archive
	 * @throws \Exception
	 */
	protected function PrepareFilesToBackup($sSourceConfigFile, $sTmpFolder, $bSkipSQLDumpForTesting = false)
	{
		$aRet = array();
		if (is_dir($sTmpFolder))
		{
			SetupUtils::rrmdir($sTmpFolder);
		}
		$this->LogInfo("backup: creating tmp dir '$sTmpFolder'");
		@mkdir($sTmpFolder, 0777, true);
		if (is_null($sSourceConfigFile))
		{
			$sSourceConfigFile = MetaModel::GetConfig()->GetLoadedFile();
		}
		if (!empty($sSourceConfigFile))
		{
			$sFile = $sTmpFolder.'/config-itop.php';
			$this->LogInfo("backup: adding resource '$sSourceConfigFile'");
			@copy($sSourceConfigFile, $sFile); // During unattended install config file may be absent
			$aRet[] = $sFile;
		}

		$sDeltaFile = APPROOT.'data/'.utils::GetCurrentEnvironment().'.delta.xml';
		if (file_exists($sDeltaFile))
		{
			$sFile = $sTmpFolder.'/delta.xml';
			$this->LogInfo("backup: adding resource '$sDeltaFile'");
			copy($sDeltaFile, $sFile);
			$aRet[] = $sFile;
		}
		$sExtraDir = APPROOT.'data/'.utils::GetCurrentEnvironment().'-modules/';
		if (is_dir($sExtraDir))
		{
			$sModules = utils::GetCurrentEnvironment().'-modules';
			$sFile = $sTmpFolder.'/'.$sModules;
			$this->LogInfo("backup: adding resource '$sExtraDir'");
			SetupUtils::copydir($sExtraDir, $sFile);
			$aRet[] = $sFile;
		}

		$aExtraFiles = [];
		if (MetaModel::GetConfig() !== null) // During unattended install config file may be absent
		{
			$aExtraFiles = MetaModel::GetModuleSetting('itop-backup', 'extra_files', []);
		}

		foreach (InterfaceDiscovery::GetInstance()->FindItopClasses(iBackupExtraFilesExtension::class) as $sExtensionClass)
		{
			/** @var iBackupExtraFilesExtension $oExtensionInstance */
			$oExtensionInstance = new $sExtensionClass();
			$aExtraFiles = array_merge($aExtraFiles, $oExtensionInstance->GetExtraFilesRelPaths());
		}

		foreach($aExtraFiles as $sExtraFileOrDir)
		{
			if(!file_exists(APPROOT.'/'.$sExtraFileOrDir)) {
				continue; // Ignore non-existing files
			}

			$sExtraFullPath = utils::RealPath(APPROOT.'/'.$sExtraFileOrDir, APPROOT);
			if ($sExtraFullPath === false)
			{
				throw new Exception("Backup: Aborting, resource '$sExtraFileOrDir'. Considered as UNSAFE because not inside the iTop directory.");
			}
			if (is_dir($sExtraFullPath))
			{
				$sFile = $sTmpFolder.'/'.$sExtraFileOrDir;
				$this->LogInfo("backup: adding directory '$sExtraFileOrDir'");
				SetupUtils::copydir($sExtraFullPath, $sFile);
				$aRet[] = $sFile;
			}
			elseif (file_exists($sExtraFullPath))
			{
				$sFile = $sTmpFolder.'/'.$sExtraFileOrDir;
				$this->LogInfo("backup: adding file '$sExtraFileOrDir'");
				@mkdir(dirname($sFile), 0755, true);
				copy($sExtraFullPath, $sFile);
				$aRet[] = $sFile;
			}
		}
		if (!$bSkipSQLDumpForTesting)
		{
			$sDataFile = $sTmpFolder.'/itop-dump.sql';
			$this->DoBackup($sDataFile);
			$aRet[] = $sDataFile;
		}

		return $aRet;
	}

	public static function EscapeShellArg($sValue)
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

		$sPortAndTransportOptions = self::GetMysqlCliPortAndTransportOptions($this->sDBHost, $this->iDBPort);
		$sTlsOptions = self::GetMysqlCliTlsOptions($this->oConfig);

		$sMysqlVersion = CMDBSource::GetDBVersion();
		$bIsMysqlSupportUtf8mb4 = (version_compare($sMysqlVersion, self::MYSQL_VERSION_WITH_UTF8MB4_IN_PROGRAMS) === -1);
		$sMysqldumpCharset = $bIsMysqlSupportUtf8mb4 ? 'utf8' : DEFAULT_CHARACTER_SET;

		// Delete the file created by tempnam() so that the spawned process can write into it (Windows/IIS)
		@unlink($sBackupFileName);

		// Store the password into a temporary file to avoid mysql complaint
		$sMySQLDumpCnfFile = tempnam(SetupUtils::GetTmpDir(), 'itop-mysqldump-');
		$sMySQLDumpCnf = <<<EOF
[mysqldump]
password=$sPwd
EOF;
		touch($sMySQLDumpCnfFile);
		chmod($sMySQLDumpCnfFile, 0600);
		file_put_contents($sMySQLDumpCnfFile, $sMySQLDumpCnf, LOCK_EX);

		// Note: opt implicitly sets lock-tables... which cancels the benefit of single-transaction!
			//       skip-lock-tables compensates and allows for writes during a backup
		$sCommand = "$sMySQLDump --defaults-extra-file=\"$sMySQLDumpCnfFile\" --opt --skip-lock-tables --default-character-set=" . $sMysqldumpCharset . " --add-drop-database --single-transaction --host=$sHost $sPortAndTransportOptions --user=$sUser $sTlsOptions --result-file=$sTmpFileName $sDBName $sTables 2>&1";
		$sCommandDisplay = "$sMySQLDump --defaults-extra-file=\"$sMySQLDumpCnfFile\" --opt --skip-lock-tables --default-character-set=" . $sMysqldumpCharset . " --add-drop-database --single-transaction --host=$sHost $sPortAndTransportOptions --user=xxxxx $sTlsOptions --result-file=$sTmpFileName $sDBName $sTables";

		// Now run the command for real
		$this->LogInfo("backup: generate data file with command: $sCommandDisplay");
		$aOutput = array();
		$iRetCode = 0;
		exec($sCommand, $aOutput, $iRetCode);
		@unlink($sMySQLDumpCnfFile);
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
				$sMoreInfo = "Check the log files 'log/setup.log' or 'log/error.log' for more information.";
			}
			throw new BackupException("Failed to execute mysqldump: ".$sMoreInfo);
		}
	}

	/**
	 * Helper to download the file directly from the browser
	 *
	 * @param string $sFile full file path
	 *
	 * @throws \InvalidParameterException if the file doesn't exists
	 */
	public function DownloadBackup($sFile)
	{
		if (!file_exists($sFile))
		{
			throw new InvalidParameterException('Invalid file path');
		}

		$sMimeType = utils::GetFileMimeType($sFile);

		header('Content-Description: File Transfer');
		header('Content-Type: '.$sMimeType);
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
                throw new MySQLException('Could not connect to the DB server '.$oMysqli->connect_errno.' (mysql errno: '.$oMysqli->connect_error, array('host' => $sHost, 'user' => $sUser));
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
	 * @throws \MySQLException
	 *
	 * @uses \CMDBSource::IsSslModeDBVersion() so needs a connection opened !
	 *
	 * @since 2.5.0 N°1260
	 * @since 2.6.2 2.7.0 N°2336 Call DB to get vendor and version (so CMDBSource must be init before calling this method)
	 * @link https://dev.mysql.com/doc/refman/5.7/en/connection-options.html#encrypted-connection-options Command Options for Encrypted Connections
	 */
	public static function GetMysqlCliTlsOptions($oConfig)
	{
		$bDbTlsEnabled = $oConfig->Get('db_tls.enabled');
		if (!$bDbTlsEnabled)
		{
			return '';
		}
		$sTlsOptions = '';
		// Mysql 5.7.11 and upper deprecated --ssl and uses --ssl-mode instead
		if (CMDBSource::IsSslModeDBVersion())
		{
			if(empty($oConfig->Get('db_tls.ca')))
			{
				$sTlsOptions .= ' --ssl-mode=REQUIRED';
			}
			else
			{
				$sTlsOptions .= ' --ssl-mode=VERIFY_CA';
			}
		}
		else
		{
			$sTlsOptions .= ' --ssl';
		}

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
			return '';
		}

		return ' --'.$sCliArgName.'='.self::EscapeShellArg($sData);
	}

	/**
	 * @return string CLI options for port and protocol
	 *
	 * @since 2.7.9 3.0.4 3.1.1 N°6123 method creation
	 * @since 2.7.10 3.0.4 3.1.2 3.2.0 N°6889 rename method to return both port and transport options. Keep default socket connexion if we are on localhost with no port
	 *
	 * @link https://bugs.mysql.com/bug.php?id=55796 MySQL CLI tools will ignore `--port` option on localhost
	 * @link https://jira.mariadb.org/browse/MDEV-14974 Since 10.6.1 the MariaDB CLI tools will use the `--port` option on host=localhost
	 */
	private static function GetMysqlCliPortAndTransportOptions(string $sHost, ?int $iPort): string
	{
		if (strtolower($sHost) === 'localhost') {
			/**
			 * Since MariaDB 10.6.1 if we have host=localhost, and only the --port option we will get a warning
			 * To avoid this warning if we want to set --port option we must set --protocol=tcp
			 **/
			if (is_null($iPort)) {
				// no port specified => no option to return, this will mean using socket protocol (unix socket)
				return '';
			}

			$sPortOption = self::GetMysqliCliSingleOption('port', $iPort);
			$sTransportOptions = ' --protocol=tcp';
			return $sPortOption . $sTransportOptions;
		}

		if (is_null($iPort)) {
			$iPort = CMDBSource::MYSQL_DEFAULT_PORT;
		}
		$sPortOption = self::GetMysqliCliSingleOption('port', $iPort);

		return $sPortOption;
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
}

class TarGzArchive implements BackupArchive
{
	/** @var \ITopArchiveTar $oArchive */
	protected $oArchive;
	/** @var string[] $aFiles */
	protected $aFiles = null;

	public function __construct($sFile)
	{
		$this->oArchive = new ITopArchiveTar($sFile);
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

