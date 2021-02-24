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

/**
 * The standardized result of any pass/fail check performed by the setup
 *
 * @copyright   Copyright (C) 2010-2018 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
class CheckResult
{
	// Severity levels
	const ERROR = 0;
	const WARNING = 1;
	const INFO = 2;

	public $iSeverity;
	public $sLabel;
	public $sDescription;

	public function __construct($iSeverity, $sLabel, $sDescription = '')
	{
		$this->iSeverity = $iSeverity;
		$this->sLabel = $sLabel;
		$this->sDescription = $sDescription;
	}
}

/**
 * All of the functions/utilities needed by both the setup wizard and the installation process
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
class SetupUtils
{
	// -- Minimum versions (requirements : forbids installation if not met)
	const PHP_MIN_VERSION = '5.6.0'; // 5.6 will be supported until the end of 2018 (see http://php.net/supported-versions.php)
	const MYSQL_MIN_VERSION = '5.6.0'; // 5.6 to have fulltext on InnoDB for Tags fields (N°931)
	const MYSQL_NOT_VALIDATED_VERSION = ''; // MySQL 8 is now OK (N°2010 in 2.7.0) but has no query cache so mind the perf on large volumes !

	// -- versions that will be the minimum in next iTop major release (warning if not met)
	const PHP_NEXT_MIN_VERSION = '7.1.3'; // we are aiming on switching to Symfony 4 in iTop 2.8
	const MYSQL_NEXT_MIN_VERSION = ''; // no new MySQL requirement for next iTop version
	// -- First recent version that is not yet validated by Combodo (warning)
	const PHP_NOT_VALIDATED_VERSION = '7.5.0';

	const MIN_MEMORY_LIMIT = 33554432; // 32 * 1024 * 1024 - we can use expressions in const since PHP 5.6 but we are in the setup !
	const SUHOSIN_GET_MAX_VALUE_LENGTH = 2048;

	/**
	 * Check configuration parameters, for example :
	 * <ul>
	 * <li>PHP version
	 * <li>needed PHP extensions
	 * <li>memory_limit
	 * <li>max_upload_file_size
	 * <li>...
	 * </ul>
	 *
	 * @internal SetupPage $oP The page used only for its 'log' method
	 * @return CheckResult[]
	 */
	public static function CheckPhpAndExtensions()
	{
		$aResult = array();

		// For log file(s)
		if (!is_dir(APPROOT.'log'))
		{
			@mkdir(APPROOT.'log');
		}

		self::CheckPhpVersion($aResult);

		// Check the common directories
		$aWritableDirsErrors = self::CheckWritableDirs(array('log', 'env-production', 'env-production-build', 'conf', 'data'));
		$aResult = array_merge($aResult, $aWritableDirsErrors);

		$aMandatoryExtensions = array(
			'mysqli',
			'iconv',
			'simplexml',
			'soap',
			'hash',
			'json',
			'session',
			'pcre',
			'dom',
			'zlib',
			'gd', // test image type (always returns false if not installed), image resizing, PDF export
		);
		$aOptionalExtensions = array(
			'mcrypt, sodium or openssl' =>
				array(
					'mcrypt' => 'Strong encryption will not be used.',
					'sodium' => 'Strong encryption will not be used.',
					'openssl' => 'Strong encryption will not be used.',
				),
			'ldap' => 'LDAP authentication will be disabled.',
			'mbstring' => 'For CryptEngine implementations, trace in Mail to ticket automation', // N°2891
		);

		asort($aMandatoryExtensions); // Sort the list to look clean !
		ksort($aOptionalExtensions); // Sort the list to look clean !
		$aExtensionsOk = array();
		$aMissingExtensions = array();
		$aMissingExtensionsLinks = array();
		// First check the mandatory extensions
		foreach($aMandatoryExtensions as $sExtension)
		{
			if (extension_loaded($sExtension))
			{
				$aExtensionsOk[] = $sExtension;
			}
			else
			{
				$aMissingExtensions[] = $sExtension;
				$aMissingExtensionsLinks[] = "<a href=\"http://www.php.net/manual/en/book.$sExtension.php\" target=\"_blank\">$sExtension</a>";
			}
		}
		if (count($aExtensionsOk) > 0)
		{
			$aResult[] = new CheckResult(CheckResult::INFO, "Required PHP extension(s): ".implode(', ', $aExtensionsOk).".");
		}
		if (count($aMissingExtensions) > 0)
		{
			$aResult[] = new CheckResult(CheckResult::ERROR, "Missing PHP extension(s): ".implode(', ', $aMissingExtensionsLinks).".");
		}
		// Next check the optional extensions
		$aExtensionsOk = array();
		$aMissingExtensions = array();
		foreach($aOptionalExtensions as $sExtension => $sMessage)
		{
			//if sMessage is an array, extensions in it are conditional between them
			if (is_array($sMessage))
			{
				$bIsAtLeastOneLoaded = false;
				$sConditionalMissingMessage = '';
				foreach($sMessage as $sConditionalExtension => $sConditionalMessage)
				{
					if (extension_loaded($sConditionalExtension))
					{
						$bIsAtLeastOneLoaded = true;
						$aExtensionsOk[] = $sConditionalExtension;
					}
					else
					{
						$sConditionalMissingMessage = $sConditionalMessage;
					}
				}
				if(!$bIsAtLeastOneLoaded)
				{
					$aMissingExtensions[$sExtension] = $sConditionalMissingMessage;
				}
			}
			else
			{
				if (extension_loaded($sExtension))
				{
					$aExtensionsOk[] = $sExtension;
				}
				else
				{
					$aMissingExtensions[$sExtension] = $sMessage;
				}
			}
		}
		if (count($aExtensionsOk) > 0)
		{
			$aResult[] = new CheckResult(CheckResult::INFO, "Optional PHP extension(s): ".implode(', ', $aExtensionsOk).".");
		}
		if (count($aMissingExtensions) > 0)
		{
			foreach($aMissingExtensions as $sExtension => $sMessage)
			{
				$aResult[] = new CheckResult(CheckResult::WARNING, "Missing optional PHP extension: $sExtension. ".$sMessage);
			}
		}
		// Check some ini settings here
		if (function_exists('php_ini_loaded_file')) // PHP >= 5.2.4
		{
			$sPhpIniFile = php_ini_loaded_file();
			// Other included/scanned files
			if ($sFileList = php_ini_scanned_files())
			{
				if (strlen($sFileList) > 0)
				{
					$aFiles = explode(',', $sFileList);

					foreach ($aFiles as $sFile)
					{
						$sPhpIniFile .= ', '.trim($sFile);
					}
				}
			}
			SetupPage::log("Info - php.ini file(s): '$sPhpIniFile'");
		}

		if (!ini_get('file_uploads'))
		{
			$aResult[] = new CheckResult(CheckResult::ERROR, "Files upload is not allowed on this server (file_uploads = ".ini_get('file_uploads').").");
		}

		$sUploadTmpDir = self::GetUploadTmpDir();
		if (empty($sUploadTmpDir))
		{
			$sUploadTmpDir = '/tmp';
			$aResult[] = new CheckResult(CheckResult::WARNING, "Temporary directory for files upload is not defined (upload_tmp_dir), assuming that $sUploadTmpDir is used.");
		}
		// check that the upload directory is indeed writable from PHP
		if (!empty($sUploadTmpDir))
		{
			if (!file_exists($sUploadTmpDir))
			{
				$aResult[] = new CheckResult(CheckResult::ERROR, "Temporary directory for files upload ($sUploadTmpDir) does not exist or cannot be read by PHP.");
			}
			else if (!is_writable($sUploadTmpDir))
			{
				$aResult[] = new CheckResult(CheckResult::ERROR, "Temporary directory for files upload ($sUploadTmpDir) is not writable.");
			}
			else
			{
				SetupPage::log("Info - Temporary directory for files upload ($sUploadTmpDir) is writable.");
			}
		}


		if (!ini_get('upload_max_filesize'))
		{
			$aResult[] = new CheckResult(CheckResult::ERROR, "File upload is not allowed on this server (upload_max_filesize = ".ini_get('upload_max_filesize').").");
		}

		$iMaxFileUploads = ini_get('max_file_uploads');
		if (!empty($iMaxFileUploads) && ($iMaxFileUploads < 1))
		{
			$aResult[] = new CheckResult(CheckResult::ERROR, "File upload is not allowed on this server (max_file_uploads = ".ini_get('max_file_uploads').").");
		}

		$iMaxUploadSize = utils::ConvertToBytes(ini_get('upload_max_filesize'));
		$iMaxPostSize = utils::ConvertToBytes(ini_get('post_max_size'));

		if ($iMaxPostSize <= $iMaxUploadSize)
		{
			$aResult[] = new CheckResult(CheckResult::WARNING, "post_max_size (".ini_get('post_max_size').") in php.ini should be strictly greater than upload_max_filesize (".ini_get('upload_max_filesize').") otherwise you cannot upload files of the maximum size.");
		}


		SetupPage::log("Info - upload_max_filesize: ".ini_get('upload_max_filesize'));
		SetupPage::log("Info - post_max_size: ".ini_get('post_max_size'));
		SetupPage::log("Info - max_file_uploads: ".ini_get('max_file_uploads'));

		// Check some more ini settings here, needed for file upload
		$sMemoryLimit = trim(ini_get('memory_limit'));
		if (empty($sMemoryLimit))
		{
			// On some PHP installations, memory_limit does not exist as a PHP setting!
			// (encountered on a 5.2.0 under Windows)
			// In that case, ini_set will not work, let's keep track of this and proceed anyway
			$aResult[] = new CheckResult(CheckResult::WARNING, "No memory limit has been defined in this instance of PHP");
		}
		else
		{
			// Check that the limit will allow us to load the data
			//
			$iMemoryLimit = utils::ConvertToBytes($sMemoryLimit);
			if (!utils::IsMemoryLimitOk($iMemoryLimit, self::MIN_MEMORY_LIMIT))
			{
				$aResult[] = new CheckResult(CheckResult::ERROR, "memory_limit ($iMemoryLimit) is too small, the minimum value to run the application is ".self::MIN_MEMORY_LIMIT.".");
			}
			else
			{
				SetupPage::log("Info - memory_limit is $iMemoryLimit, ok.");
			}
		}

		// Special case for APC
		if (extension_loaded('apc'))
		{
			$sAPCVersion = phpversion('apc');
			$aResult[] = new CheckResult(CheckResult::INFO, "APC detected (version $sAPCVersion). The APC cache will be used to speed-up ".ITOP_APPLICATION.".");
		}

		// Special case Suhosin extension
		if (extension_loaded('suhosin'))
		{
			$sSuhosinVersion = phpversion('suhosin');
			$aOk[] = "Suhosin extension detected (version $sSuhosinVersion).";

			$iGetMaxValueLength = ini_get('suhosin.get.max_value_length');
			if ($iGetMaxValueLength < self::SUHOSIN_GET_MAX_VALUE_LENGTH)
			{
				$aResult[] = new CheckResult(CheckResult::WARNING,  "suhosin.get.max_value_length ($iGetMaxValueLength) is too small, the minimum value recommended to run the application is ".self::SUHOSIN_GET_MAX_VALUE_LENGTH.".");
			}
			else
			{
				SetupPage::log("Info - suhosin.get.max_value_length = $iGetMaxValueLength, ok.");
			}
		}

		if (function_exists('php_ini_loaded_file')) // PHP >= 5.2.4
		{
			$sPhpIniFile = php_ini_loaded_file();
			// Other included/scanned files
			if ($sFileList = php_ini_scanned_files())
			{
				if (strlen($sFileList) > 0)
				{
					$aFiles = explode(',', $sFileList);

					foreach ($aFiles as $sFile)
					{
						$sPhpIniFile .= ', '.trim($sFile);
					}
				}
			}
			$aResult[] = new CheckResult(CheckResult::INFO,  "Loaded php.ini files: $sPhpIniFile");
		}

		// Check the configuration of the sessions persistence, since this is critical for the authentication
		if (ini_get('session.save_handler') == 'files')
		{
			$sSavePath = ini_get('session.save_path');
			SetupPage::log("Info - session.save_path is: '$sSavePath'.");

			// According to the PHP documentation, the format can be /path/where/to_save_sessions or "N;/path/where/to_save_sessions" or "N;MODE;/path/where/to_save_sessions"
			$sSavePath = ltrim(rtrim($sSavePath, '"'), '"'); // remove surrounding quotes (if any)

			if (!empty($sSavePath))
			{
				if (($iPos = strrpos($sSavePath, ';', 0)) !== false)
				{
					// The actual path is after the last semicolon
					$sSavePath = substr($sSavePath, $iPos+1);
				}
				if (!is_writable($sSavePath))
				{
					$aResult[] = new CheckResult(CheckResult::ERROR, "The value for session.save_path ($sSavePath) is not writable for the web server. Make sure that PHP can actually save session variables. (Refer to the PHP documentation: http://php.net/manual/en/session.configuration.php#ini.session.save-path)");
				}
				else
				{
					$aResult[] = new CheckResult(CheckResult::INFO, "The value for session.save_path ($sSavePath) is writable for the web server.");
				}
			}
			else
			{
				$aResult[] = new CheckResult(CheckResult::WARNING, "Empty path for session.save_path. Make sure that PHP can actually save session variables. (Refer to the PHP documentation: http://php.net/manual/en/session.configuration.php#ini.session.save-path)");
			}
		}
		else
		{
			$aResult[] = new CheckResult(CheckResult::INFO, "session.save_handler is: '".ini_get('session.save_handler')."' (different from 'files').");
		}

		return $aResult;
	}

	/**
	 * @param CheckResult[] $aResult checks log
	 */
	private static function CheckPhpVersion(&$aResult)
	{
		SetupPage::log('Info - CheckPHPVersion');
		$sPhpVersion = phpversion();

		if (version_compare($sPhpVersion, self::PHP_MIN_VERSION, '>='))
		{
			$aResult[] = new CheckResult(CheckResult::INFO,
				"The current PHP Version (".$sPhpVersion.") is greater than the minimum version required to run ".ITOP_APPLICATION.", which is (".self::PHP_MIN_VERSION.")");


			$sPhpNextMinVersion = self::PHP_NEXT_MIN_VERSION; // mandatory before PHP 5.5 (arbitrary expressions), keeping compat because we're in the setup !
			if (!empty($sPhpNextMinVersion))
			{
				if (version_compare($sPhpVersion, self::PHP_NEXT_MIN_VERSION, '>='))
				{
					$aResult[] = new CheckResult(CheckResult::INFO,
						"The current PHP Version (".$sPhpVersion.") is greater than the minimum version required to run next ".ITOP_APPLICATION." release, which is (".self::PHP_NEXT_MIN_VERSION.")");
				}
				else
				{
					$aResult[] = new CheckResult(CheckResult::WARNING,
						"The current PHP Version (".$sPhpVersion.") is lower than the minimum version required to run next ".ITOP_APPLICATION." release, which is (".self::PHP_NEXT_MIN_VERSION.")");
				}
			}

			if (version_compare($sPhpVersion, self::PHP_NOT_VALIDATED_VERSION, '>='))
			{
				$aResult[] = new CheckResult(CheckResult::WARNING,
					"The current PHP Version (".$sPhpVersion.") is not yet validated by Combodo. You may experience some incompatibility issues.");
			}
		}
		else
		{
			$aResult[] = new CheckResult(CheckResult::ERROR,
				"Error: The current PHP Version (".$sPhpVersion.") is lower than the minimum version required to run ".ITOP_APPLICATION.", which is (".self::PHP_MIN_VERSION.")");
		}
	}

	/**
	 * Check that the selected modules meet their dependencies
	 * @param $sSourceDir
	 * @param $sExtensionDir
	 * @param $aSelectedModules
	 * @return array
	 */
	public static function CheckSelectedModules($sSourceDir, $sExtensionDir, $aSelectedModules)
	{
		$aResult = array();
		SetupPage::log('Info - CheckSelectedModules');

		$aDirsToScan = array(APPROOT.$sSourceDir);
		$sExtensionsPath = APPROOT.$sExtensionDir;
		if (is_dir($sExtensionsPath))
		{
			// if the extensions dir exists, scan it for additional modules as well
			$aDirsToScan[] = $sExtensionsPath;
		}
		require_once(APPROOT.'setup/modulediscovery.class.inc.php');
		try
		{
			ModuleDiscovery::GetAvailableModules($aDirsToScan, true, $aSelectedModules);
		}
		catch(Exception $e)
		{
			$aResult[] = new CheckResult(CheckResult::ERROR, $e->getMessage());
		}
		return $aResult;
	}

	/**
	 * Check that the backup could be executed
	 * @param $sDBBackupPath
	 * @param $sMySQLBinDir
	 * @return array An array of CheckResults objects
	 * @internal param Page $oP The page used only for its 'log' method
	 */
	public static function CheckBackupPrerequisites($sDBBackupPath, $sMySQLBinDir = null)
	{
		$aResult = array();
		SetupPage::log('Info - CheckBackupPrerequisites');

		// zip extension
		//
		if (!extension_loaded('phar'))
		{
			$sMissingExtensionLink = "<a href=\"http://www.php.net/manual/en/book.phar.php\" target=\"_blank\">zip</a>";
			$aResult[] = new CheckResult(CheckResult::ERROR, "Missing PHP extension: phar", $sMissingExtensionLink);
		}
		if (!extension_loaded('zlib'))
		{
			$sMissingExtensionLink = "<a href=\"http://www.php.net/manual/en/book.zlib.php\" target=\"_blank\">zip</a>";
			$aResult[] = new CheckResult(CheckResult::ERROR, "Missing PHP extension: zlib", $sMissingExtensionLink);
		}

		// availability of exec()
		//
		$aDisabled = explode(', ', ini_get('disable_functions'));
		SetupPage::log('Info - PHP functions disabled: '.implode(', ', $aDisabled));
		if (in_array('exec', $aDisabled))
		{
			$aResult[] = new CheckResult(CheckResult::ERROR, "The PHP exec() function has been disabled on this server");
		}

		// availability of mysqldump
		if (empty($sMySQLBinDir) && null != MetaModel::GetConfig())
		{
			$sMySQLBinDir = MetaModel::GetConfig()->GetModuleSetting('itop-backup', 'mysql_bindir', '');
		}

		if (empty($sMySQLBinDir))
		{
			$sMySQLDump = 'mysqldump';
		}
		else
		{
			SetupPage::log('Info - Found mysql_bindir: '.$sMySQLBinDir);
			$sMySQLDump = '"'.$sMySQLBinDir.'/mysqldump"';
		}
		$sCommand = "$sMySQLDump -V 2>&1";

		$aOutput = array();
		$iRetCode = 0;
		exec($sCommand, $aOutput, $iRetCode);
		if ($iRetCode == 0)
		{
			$aResult[] = new CheckResult(CheckResult::INFO, "mysqldump is present: Ok.");
		}
		elseif ($iRetCode == 1)
		{
			// Unfortunately $aOutput is not really usable since we don't know its encoding (character set)
			$aResult[] = new CheckResult(CheckResult::ERROR, "mysqldump could not be found. Please make sure it is installed and in the path.");
		}
		else
		{
			// Unfortunately $aOutput is not really usable since we don't know its encoding (character set)
			$aResult[] = new CheckResult(CheckResult::ERROR, "mysqldump could not be executed (retcode=$iRetCode): Please make sure it is installed and " . (empty($sMySQLBinDir) ? "in the path" :  "located at : $sMySQLDump"));
		}
		foreach($aOutput as $sLine)
		{
			SetupPage::log('Info - mysqldump -V said: '.$sLine);
		}
		
		// create and test destination location
		//
		$sDestDir = dirname($sDBBackupPath);
		setuputils::builddir($sDestDir);
		if (!is_dir($sDestDir))
		{
			$aResult[] = new CheckResult(CheckResult::ERROR, "$sDestDir does not exist and could not be created.");
		}

		// check disk space
		// to do... evaluate how we can correlate the DB size with the size of the dump (and the zip!)
		// E.g. 2,28 Mb after a full install, giving a zip of 26 Kb (data = 26 Kb)
		// Example of query (DB without a suffix)
		//$sDBSize = "SELECT SUM(ROUND(DATA_LENGTH/1024/1024, 2)) AS size_mb FROM information_schema.TABLES WHERE TABLE_SCHEMA = `$sDBName`";

		return $aResult;
	}

	/**
	 * Check that graphviz can be launched
	 * @param $sGraphvizPath
	 * @return CheckResult The result of the check
	 * @internal param string $GraphvizPath The path where graphviz' dot program is installed
	 */
	public static function CheckGraphviz($sGraphvizPath)
	{
		$oResult = null;
		SetupPage::log('Info - CheckGraphviz');

		// availability of exec()
		//
		$aDisabled = explode(', ', ini_get('disable_functions'));
		SetupPage::log('Info - PHP functions disabled: '.implode(', ', $aDisabled));
		if (in_array('exec', $aDisabled))
		{
			return new CheckResult(CheckResult::ERROR, "The PHP exec() function has been disabled on this server");
		}

		// availability of dot / dot.exe
		if (empty($sGraphvizPath))
		{
			$sGraphvizPath = 'dot';
		} else {
			clearstatcache();
			if (!is_file($sGraphvizPath) || !is_executable($sGraphvizPath)) {
				//N°3412 avoid shell injection
				return new CheckResult(CheckResult::WARNING, "$sGraphvizPath could not be executed: Please make sure it is installed and in the path");
			}

			if (!utils::IsWindowsEnvironment()){
				$sGraphvizPath = escapeshellcmd($sGraphvizPath);
			}
		}

		$sCommand = "\"$sGraphvizPath\" -V 2>&1";

		$aOutput = array();
		$iRetCode = 0;
		exec($sCommand, $aOutput, $iRetCode);
		if ($iRetCode == 0)
		{
			$oResult = new CheckResult(CheckResult::INFO, "dot is present: ".$aOutput[0]);
		}
		else
		{
			$oResult = new CheckResult(CheckResult::WARNING, "dot could not be executed (retcode=$iRetCode): Please make sure it is installed and in the path");
		}
		foreach($aOutput as $sLine)
		{
			SetupPage::log('Info - '.$sGraphvizPath.' -V said: '.$sLine);
		}

		return $oResult;
	}

	/**
	 * Helper function to retrieve the system's temporary directory
	 * Emulates sys_get_temp_dir if needed (PHP < 5.2.1)
	 * @return string Path to the system's temp directory
	 */
	public static function GetTmpDir()
	{
		return realpath(sys_get_temp_dir());
	}

	/**
	 * Helper function to retrieve the directory where files are to be uploaded
	 * @return string Path to the temp directory used for uploading files
	 */
	public static function GetUploadTmpDir()
	{
		$sPath = ini_get('upload_tmp_dir');
		if (empty($sPath))
		{
			$sPath = self::GetTmpDir();
		}
		return $sPath;
	}

	/**
	 * Helper to recursively remove a directory
	 * @param $dir
	 * @throws Exception
	 */
	public static function rrmdir($dir)
	{
		if ((strlen(trim($dir)) == 0) || ($dir == '/') || ($dir == '\\'))
		{
			throw new Exception("Attempting to delete directory: '$dir'");
		}
		self::tidydir($dir);
		self::rmdir_safe($dir);
	}

	/**
	 * Helper to recursively cleanup a directory
	 * @param $dir
	 * @throws Exception
	 */
	public static function tidydir($dir)
	{
		if ((strlen(trim($dir)) == 0) || ($dir == '/') || ($dir == '\\'))
		{
			throw new Exception("Attempting to delete directory: '$dir'");
		}

		$aFiles = scandir($dir); // Warning glob('.*') does not seem to return the broken symbolic links, thus leaving a non-empty directory
		if ($aFiles !== false)
		{
			foreach($aFiles as $file)
			{
				if (($file != '.') && ($file != '..'))
				{
					if(is_dir($dir.'/'.$file))
					{
						self::tidydir($dir.'/'.$file);
						self::rmdir_safe($dir.'/'.$file);
					}
					else
					{
						if (!unlink($dir.'/'.$file))
						{
							SetupPage::log("Warning - FAILED to remove file '$dir/$file'");
						}
						else if (file_exists($dir.'/'.$file))
						{
							SetupPage::log("Warning - FAILED to remove file '$dir/.$file'");
						}
					}
				}
			}
		}
	}

	/**
	 * Helper to build the full path of a new directory
	 * @param $dir
	 */
	public static function builddir($dir)
	{
		if (empty($dir))
		{
			// avoid infinite loops :/
			return;
		}
		if (!is_dir($dir))
		{
			$parent = dirname($dir);
			self::builddir($parent);
			mkdir($dir);
		}
	}

	public static function rmdir_safe($dir)
	{
		// avoid unnecessary warning
		// Try 100 times...
		$i = 100;
		while ((@rmdir($dir) === false) && $i > 0)
		{
			// Magic trick for windows
			// sometimes the folder is empty but rmdir fails
			closedir(opendir($dir));
			$i--;
		}
		if ($i == 0)
		{
			rmdir($dir);
		}
	}

	/**
	 * Helper to copy a directory to a target directory, skipping .SVN files (for developer's comfort!)
	 * Returns true if successful
	 * @param $sSource
	 * @param $sDest
	 * @param bool $bUseSymbolicLinks
	 * @return bool
	 * @throws Exception
	 */
	public static function copydir($sSource, $sDest, $bUseSymbolicLinks = false)
	{
		if (is_dir($sSource))
		{
			if (!is_dir($sDest))
			{
				mkdir($sDest);
			}
			$aFiles = scandir($sSource);
			if(sizeof($aFiles) > 0 )
			{
				foreach($aFiles as $sFile)
				{
					if ($sFile == '.' || $sFile == '..' || $sFile == '.svn' || $sFile == '.git')
					{
						// Skip
						continue;
					}

					if (is_dir($sSource.'/'.$sFile))
					{
						// Recurse
						self::copydir($sSource.'/'.$sFile, $sDest.'/'.$sFile, $bUseSymbolicLinks);
					}
					else
					{
						if ($bUseSymbolicLinks)
						{
							if (function_exists('symlink'))
							{
								if (file_exists($sDest.'/'.$sFile))
								{
									unlink($sDest.'/'.$sFile);
								}
								symlink($sSource.'/'.$sFile, $sDest.'/'.$sFile);
							}
							else
							{
								throw(new Exception("Error, cannot *copy* '$sSource/$sFile' to '$sDest/$sFile' using symbolic links, 'symlink' is not supported on this system."));
							}
						}
						else
						{
							if (is_link($sDest.'/'.$sFile))
							{
								unlink($sDest.'/'.$sFile);
							}
							copy($sSource.'/'.$sFile, $sDest.'/'.$sFile);
						}
					}
				}
			}
			return true;
		}
		elseif (is_file($sSource))
		{
			if ($bUseSymbolicLinks)
			{
				if (function_exists('symlink'))
				{
					return symlink($sSource, $sDest);
				}
				else
				{
					throw(new Exception("Error, cannot *copy* '$sSource' to '$sDest' using symbolic links, 'symlink' is not supported on this system."));
				}
			}
			else
			{
				return copy($sSource, $sDest);
			}
		}
		else
		{
			return false;
		}
	}

	/**
	 * Helper to move a directory when the parent directory of the target dir cannot be written
	 * To be used as alternative to rename()
	 * Files/Subdirs of the source directory are moved one by one
	 * Returns void
	 *
	 * @param string $sSource
	 * @param string $sDest
	 * @param boolean $bRemoveSource If true $sSource will be removed, otherwise $sSource will just be emptied
	 * @throws Exception
	 */
	public static function movedir($sSource, $sDest, $bRemoveSource = true)
	{
		if (!is_dir($sSource))
		{
			throw new Exception("movedir: the source directory '$sSource' is not a valid directory or cannot be read");
		}
		if (!is_dir($sDest))
		{
			self::builddir($sDest);
		}
		else
		{
			self::tidydir($sDest);
		}

		self::copydir($sSource, $sDest);
		self::tidydir($sSource);
		if($bRemoveSource === true)
		{
			self::rmdir_safe($sSource);
		}
	}

	public static function GetPreviousInstance($sDir)
	{
		$sSourceDir = '';
		$sSourceEnvironment = '';
		$sConfigFile = '';
		$aResult = array(
			'found' => false,
		);

		if (file_exists($sDir.'/config-itop.php'))
		{
			$sSourceDir = $sDir;
			$sSourceEnvironment = '';
			$sConfigFile = $sDir.'/config-itop.php';
			$aResult['found'] = true;
		}
		else if (file_exists($sDir.'/conf/production/config-itop.php'))
		{
			$sSourceDir = $sDir;
			$sSourceEnvironment = 'production';
			$sConfigFile = $sDir.'/conf/production/config-itop.php';
			$aResult['found'] = true;
		}

		if ($aResult['found'])
		{
			$oPrevConf = new Config($sConfigFile);

			$aResult = array(
				'found' => true,
				'source_dir' => $sSourceDir,
				'source_environment' => $sSourceEnvironment,
				'configuration_file' => $sConfigFile,
				'db_server' => $oPrevConf->Get('db_host'),
				'db_user' => $oPrevConf->Get('db_user'),
				'db_pwd' => $oPrevConf->Get('db_pwd'),
				'db_name' => $oPrevConf->Get('db_name'),
				'db_prefix' => $oPrevConf->Get('db_subname'),
				'db_tls_enabled' => $oPrevConf->Get('db_tls.enabled'),
				'db_tls_ca' => $oPrevConf->Get('db_tls.ca'),
				'graphviz_path' => $oPrevConf->Get('graphviz_path'),
				'mysql_bindir' => $oPrevConf->GetModuleSetting('itop-backup', 'mysql_bindir', ''),
			);
		}

		return $aResult;
	}

	/**
	 * @param string $sDir
	 *
	 * @return bool|float false if failure
	 * @uses \disk_free_space()
	 */
	public static function CheckDiskSpace($sDir)
	{
		while(($f = @disk_free_space($sDir)) == false)
		{
			if ($sDir == dirname($sDir)) break;
			if ($sDir == '.') break;
			$sDir = dirname($sDir);
		}

		return $f;
	}

	public static function HumanReadableSize($fBytes)
	{
		$aSizes = array('bytes', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb', 'Hb');
		$index = 0;
		while (($fBytes > 1000) && ($index < count($aSizes)))
		{
			$index++;
			$fBytes = $fBytes / 1000;
		}

		return sprintf('%.2f %s', $fBytes, $aSizes[$index]);
	}

	/**
	 * @param \WebPage $oPage
	 * @param boolean $bIsItopInstall true if we are installing, false if we're upgrading
	 * @param string $sDBServer
	 * @param string $sDBUser
	 * @param string $sDBPwd
	 * @param string $sDBName
	 * @param string $sDBPrefix
	 * @param string $bTlsEnabled
	 * @param string $sTlsCA
	 * @param string $sNewDBName
	 */
	public static function DisplayDBParameters(
		$oPage, $bIsItopInstall, $sDBServer, $sDBUser, $sDBPwd, $sDBName, $sDBPrefix, $bTlsEnabled, $sTlsCA,
		$sNewDBName = ''
	) {
		$sWikiVersion =  utils::GetItopVersionWikiSyntax(); //eg : '2_7_0';
		$sMysqlTlsWikiPageUrl = 'https://wiki.openitop.org/doku.php?id='.$sWikiVersion.':install:php_and_mysql_tls';

		$oPage->add('<tr><td colspan="2">');
		$oPage->add('<fieldset><legend>Database Server Connection</legend>');
		$oPage->add('<table id="table_db_options">');

		//-- DB connection params
		$oPage->add('<tbody>');
		$oPage->add('<tr><td>Server Name:</td><td><input id="db_server" type="text" name="db_server" value="'.htmlentities($sDBServer, ENT_QUOTES, 'UTF-8').'" size="15"/></td><td>E.g. "localhost", "dbserver.mycompany.com" or "192.142.10.23"</td></tr>');
		$oPage->add('<tr><td>Login:</td><td><input id="db_user" type="text" name="db_user" value="'.htmlentities($sDBUser, ENT_QUOTES, 'UTF-8').'" size="15"/></td><td rowspan="2" style="vertical-align:top">The account must have the following privileges on the database: SELECT, INSERT, UPDATE, DELETE, DROP, CREATE, ALTER, CREATE VIEW, SHOW VIEW, LOCK TABLE, SUPER, TRIGGER</td></tr>');
		$oPage->add('<tr><td>Password:</td><td><input id="db_pwd" autocomplete="off" type="password" name="db_pwd" value="'.htmlentities($sDBPwd, ENT_QUOTES, 'UTF-8').'" size="15"/></td></tr>');
		$oPage->add('</tbody>');

		//-- TLS params (N°1260)
		$sTlsEnabledChecked = $bTlsEnabled ? ' checked' : '';
		$sTlsCaDisabled = $bTlsEnabled ? '' : ' disabled';
		$oPage->add('<tbody id="tls_options" class="collapsable-options">');
		$oPage->add('<tr><th colspan="3" style="text-align: left; background-color: transparent"><label style="margin: 6em; font-weight: normal; color: #696969"><img style="vertical-align:bottom" id="db_tls_img">Use TLS encrypted connection</label></th></tr>');
		$oPage->add('<tr style="display:none"><td colspan="3" class="message message-error">Before configuring MySQL with TLS encryption, read the documentation <a href="'.$sMysqlTlsWikiPageUrl.'" target="_blank">on Combodo\'s Wiki</a></td></tr>');
		$oPage->add('<tr style="display:none"><td colspan="3"><label><input id="db_tls_enabled" type="checkbox"'.$sTlsEnabledChecked.' name="db_tls_enabled" value="1"> Encrypted connection enabled</label></td></tr>');
		$oPage->add('<tr style="display:none"><td>SSL CA:</td>');
		$oPage->add('<td><input id="db_tls_ca" autocomplete="off" type="text" name="db_tls_ca" value="'.htmlentities($sTlsCA,
				ENT_QUOTES, 'UTF-8').'" size="15"'.$sTlsCaDisabled.'></td>');
		$oPage->add('<td>Path to certificate authority file for SSL</td></tr>');
		$oPage->add('</tbody>');

		$oPage->add('</table>');
		$oPage->add('</fieldset>');
		$oPage->add('</td></tr>');

		$oPage->add('<tr><td colspan="2"><div id="db_info"></div></td></tr>');

		$oPage->add('<tr><td colspan="2">');
		$oPage->add('<fieldset><legend>Database</legend>');
		$oPage->add('<table>');
		if ($bIsItopInstall)
		{
			$oPage->add('<tr><td><input type="radio" id="create_db" name="create_db" value="yes"/><label for="create_db">&nbsp;Create a new database:</label></td>');
			$oPage->add('<td><input id="db_new_name" type="text" name="db_new_name" value="'.htmlentities($sNewDBName, ENT_QUOTES, 'UTF-8').'" size="15" maxlength="32"/><span style="width:20px;" id="v_db_new_name"></span></td></tr>');
			$oPage->add('<tr><td><input type="radio" id="existing_db" name="create_db" value="no"/><label for="existing_db">&nbsp;Use the existing database:</label></td>');
			$oPage->add('<td id="db_name_container"><input id="db_name" name="db_name" size="15" maxlen="32" value="'.htmlentities($sDBName, ENT_QUOTES, 'UTF-8').'"/><span style="width:20px;" id="v_db_name"></span></td></tr>');
			$oPage->add('</tbody>');
			$oPage->add('<tbody id="prefix_option" class="collapsable-options">');
			$oPage->add('<tr><th colspan="3" style="text-align: left; background-color: transparent"><label style="margin: 0.4em; font-weight: normal; color: #696969"><img style="vertical-align:bottom">Use shared database</label></th></tr>');
			$oPage->add('<tr style="display:none"><td>Use a prefix for the tables:</td><td><input id="db_prefix" type="text" name="db_prefix" value="'.htmlentities($sDBPrefix,
					ENT_QUOTES, 'UTF-8').'" size="15"/><span style="width:20px;" id="v_db_prefix"></span></td></tr>');
			$oPage->add('</tbody>');
		}
		else
		{
			$oPage->add('<tr><td>Database Name:</td><td id="db_name_container"><input id="db_name" name="db_name" size="15" maxlen="32" value="'.htmlentities($sDBName, ENT_QUOTES, 'UTF-8').'"/><span style="width:20px;" id="v_db_name"></span></td></tr>');
			$oPage->add('</tbody>');
			$oPage->add('<tbody id="prefix_option" class="collapsable-options">');
			$oPage->add('<tr><th colspan="3" style="text-align: left; background-color: transparent"><label style="margin: 0.4em; font-weight: normal; color: #696969"><img style="vertical-align:bottom">Use shared database</label></th></tr>');
			$oPage->add('<tr style="display:none"><td>Use a prefix for the tables:</td><td><input id="db_prefix" type="text" name="db_prefix" value="'.htmlentities($sDBPrefix,
					ENT_QUOTES, 'UTF-8').'" size="15"/><span style="width:20px;" id="v_db_prefix"></span></td></tr>');
			$oPage->add('</tbody>');
		}
		$oPage->add('</table>');
		$oPage->add('</fieldset>');
		$oPage->add('<tr><td colspan="2"><span id="table_info">&nbsp;</span></td></tr>');
		$oPage->add('</td></tr>');

		// Sub options toggle (TLS, prefix)
		$oPage->add_script(<<<'JS'
function toggleCollapsableOptions($tbody) {
	$tbody.find("tr").not("tr:first-child").toggle();
	updateCollapsableImage($tbody);
}
function updateCollapsableImage($tbody) {
	$collapsableImg = $tbody.find("tr:first-child>th>label>img");
	console.debug("img", $collapsableImg, $tbody);
	imgPath = "../images/";
	imgUrl = ($tbody.find("tr:nth-child(2)>td:visible").length > 0) 
		? "minus.gif"
		: "plus.gif";
	$collapsableImg.attr("src", imgPath+imgUrl);
}
JS
		);
		if ($bTlsEnabled)
		{
			$oPage->add_ready_script('toggleCollapsableOptions($("tbody#tls_options"));');
		}
		$oPage->add_ready_script(
			<<<'JS'
$("tbody.collapsable-options>tr>th>label").click(function() {
	var $tbody = $(this).closest("tbody");
	toggleCollapsableOptions($tbody);
});
$("#db_tls_enabled").click(function() {
	var bTlsEnabled = $("#db_tls_enabled").is(":checked");
	$("#db_tls_ca").prop("disabled", !bTlsEnabled);
});
$("tbody.collapsable-options").each(function() {
	updateCollapsableImage($(this));
})
JS
		);

		$oPage->add_script(
			<<<EOF
var iCheckDBTimer = null;
var oXHRCheckDB = null;

function CheckDBConnection()
{
	// Don't call the server too often...
	if (iCheckDBTimer !== null)
	{
		clearTimeout(iCheckDBTimer);
		iCheckDBTimer = null;
	}
	iCheckDBTimer = setTimeout(DoCheckDBConnection, 500);
}

function DoCheckDBConnection()
{
	iCheckDBTimer = null;
	var oParams = {
		'db_server': $("#db_server").val(),
		'db_user': $("#db_user").val(),
		'db_pwd': $("#db_pwd").val(),
		'db_name': $("#db_name").val(),
		'db_tls_enabled': $("input#db_tls_enabled").prop('checked') ? 1 : 0,
		'db_tls_ca': $("input#db_tls_ca").val(),
	}
	if ((oXHRCheckDB != null) && (oXHRCheckDB != undefined))
	{
		oXHRCheckDB.abort();
		oXHRCheckDB = null;
	}
	oXHRCheckDB = WizardAsyncAction("check_db", oParams);
}

function ValidateField(sFieldId, bUsed)
{
	var sValue = new String($("#"+sFieldId).val());
	var bMandatory = false;

	if (bUsed)
	{
		if (sFieldId == 'db_name')
		{
			bUsed = ($("#existing_db").prop("checked"));
			bMandatory = true;
		}
		if (sFieldId == 'db_new_name')
		{
			bUsed = ($("#create_db").prop("checked"));
			bMandatory = true;
		}
	}
		
	if (!bUsed)
	{
		$("#v_"+sFieldId).html("");
		return true;
	}
	else
	{
		if (sValue != "")
		{
			if (sValue.match(/^[A-Za-z0-9_]*$/))
			{
				var bCollision = false;
				if (sFieldId == 'db_new_name')
				{
					// check that the "new name" does not correspond to an existing database
					var sNewName = $('#db_new_name').val();
					$('#db_name option').each( function() {
						if ($(this).attr('value') == sNewName)
						{
							bCollision = true;
						}
					});
				}
				
				if (bCollision)
				{
					$("#v_"+sFieldId).html('<img src="../images/validation_error.png" title="A database with the same name already exists"/>');
					return false;
				}
				else
				{
					$("#v_"+sFieldId).html("");
					return true;
				}
			}
			else
			{
				$("#v_"+sFieldId).html('<img src="../images/validation_error.png" title="Only the characters [A-Za-z0-9_] are allowed"/>');
				return false;
			}
		}
		else if (bMandatory)
		{
			$("#v_"+sFieldId).html('<img src="../images/validation_error.png" title="This field cannot be empty"/>');
			return false;
		}
		else
		{
			$("#v_"+sFieldId).html("");
			return true;
		}
	}
}
EOF
		);
		$oPage->add_ready_script(
			<<<EOF
DoCheckDBConnection(); // Validate the initial values immediately

$("table#table_db_options").on("keyup change", "tr>td input", function() { CheckDBConnection(); });

$("#db_new_name").on("click keyup change", function() { $("#create_db").prop("checked", true); WizardUpdateButtons(); });
$("#db_name").on("click keyup change", function() {  $("#existing_db").prop("checked", true); WizardUpdateButtons(); });
$("#db_prefix").on("keyup change", function() { WizardUpdateButtons(); });
$("#existing_db").on("click change", function() { WizardUpdateButtons(); });
$("#create_db").on("click change", function() { WizardUpdateButtons(); });
EOF
		);

	}

	/**
	 * Helper function : check the connection to the database, verify a few conditions (minimum version, etc...) and
	 * (if connected) enumerate the existing databases (if possible)
	 *
	 * @param string $sDBServer
	 * @param string $sDBUser
	 * @param string $sDBPwd
	 * @param bool $bTlsEnabled
	 * @param string $sTlsCA
	 *
	 * @return bool|array false if the connection failed or array('checks' => Array of CheckResult, 'databases' =>
	 *     Array of database names (as strings) or null if not allowed)
	 */
	public static function CheckDbServer(
		$sDBServer, $sDBUser, $sDBPwd, $bTlsEnabled = false, $sTlsCA = null
	)
	{
		$aResult = array('checks' => array(), 'databases' => null);

		if ($bTlsEnabled)
		{
			if (!empty($sTlsCA) && !self::CheckFileExists($sTlsCA, $aResult, 'Can\'t open SSL CA file'))
			{
				return $aResult;
			}
		}

		try
		{
			$oDBSource = new CMDBSource;
			$oDBSource->Init($sDBServer, $sDBUser, $sDBPwd, '', $bTlsEnabled, $sTlsCA);
			$aResult['checks'][] = new CheckResult(CheckResult::INFO, "Connection to '$sDBServer' as '$sDBUser' successful.");
			$aResult['checks'][] = new CheckResult(CheckResult::INFO, "Info - User privileges: ".($oDBSource->GetRawPrivileges()));

			$bHasDbVersionRequired = self::CheckDbServerVersion($aResult, $oDBSource);
			if (!$bHasDbVersionRequired)
			{
				return $aResult;
			}

			// Check some server variables
			$iMaxAllowedPacket = $oDBSource->GetServerVariable('max_allowed_packet');
			$iMaxUploadSize = utils::ConvertToBytes(ini_get('upload_max_filesize'));
			if ($iMaxAllowedPacket >= (500 + $iMaxUploadSize)) // Allow some space for the query + the file to upload
			{
				$aResult['checks'][] = new CheckResult(CheckResult::INFO, "MySQL server's max_allowed_packet ($iMaxAllowedPacket) is big enough compared to upload_max_filesize ($iMaxUploadSize).");
			}
			else if($iMaxAllowedPacket < $iMaxUploadSize)
			{
				$aResult['checks'][] = new CheckResult(CheckResult::WARNING, "MySQL server's max_allowed_packet ($iMaxAllowedPacket) is not big enough. Please, consider setting it to at least ".(500 + $iMaxUploadSize).".");
			}

			$iMaxConnections = $oDBSource->GetServerVariable('max_connections');
			if ($iMaxConnections < 5)
			{
				$aResult['checks'][] = new CheckResult(CheckResult::WARNING, "MySQL server's max_connections ($iMaxConnections) is not enough. Please, consider setting it to at least 5.");
			}
			else
			{
				$aResult['checks'][] = new CheckResult(CheckResult::INFO, "MySQL server's max_connections is set to $iMaxConnections.");
			}

			try
			{
				$aResult['databases'] = $oDBSource->ListDB();
			}
			catch(Exception $e)
			{
				$aResult['databases'] = null;
			}
		}
		catch(Exception $e)
		{
			return false;
		}

		return $aResult;
	}

	/**
	 * Use to test access to MySQL SSL files (key, cert, ca)
	 *
	 * @param string $sPath
	 * @param array $aResult passed by reference, will by updated in case of error
	 * @param $sErrorMessage
	 *
	 * @return bool false if file doesn't exist
	 * @used-by CheckDbServer
	 */
	private static function CheckFileExists($sPath, &$aResult, $sErrorMessage)
	{
		if (!is_readable($sPath))
		{
			$aResult['checks'][] = new CheckResult(CheckResult::ERROR, $sErrorMessage);

			return false;
		}

		return true;
	}

	/**
	 * @param array $aResult two keys : 'checks' with CheckResult array, 'databases' with list of databases available
	 * @param CMDBSource $oDBSource
	 *
	 * @return boolean false if DB doesn't meet the minimum version requirement
	 */
	static private function CheckDbServerVersion(&$aResult, $oDBSource)
	{
		$sDBVendor= $oDBSource->GetDBVendor();
		$sDBVersion = $oDBSource->GetDBVersion();

		if (
			!empty(self::MYSQL_NOT_VALIDATED_VERSION)
			&& ($sDBVendor === CMDBSource::ENUM_DB_VENDOR_MYSQL)
			&& version_compare($sDBVersion, self::MYSQL_NOT_VALIDATED_VERSION, '>=')
		)
		{
			$aResult['checks'][] = new CheckResult(CheckResult::ERROR,
				"Error: Current MySQL version is $sDBVersion. iTop doesn't yet support MySQL ".self::MYSQL_NOT_VALIDATED_VERSION." and above.");

			return false;
		}

		$bRet = false;
		if (version_compare($sDBVersion, self::MYSQL_MIN_VERSION, '>='))
		{
			$aResult['checks'][] = new CheckResult(CheckResult::INFO,
				"Current MySQL version ($sDBVersion), greater than minimum required version (".self::MYSQL_MIN_VERSION.")");

			$sMySqlNextMinVersion = self::MYSQL_NEXT_MIN_VERSION; // mandatory before PHP 5.5 (arbitrary expressions), keeping compat because we're in the setup !
			if (!empty($sMySqlNextMinVersion))
			{
				if (version_compare($sDBVersion, self::MYSQL_NEXT_MIN_VERSION, '>='))
				{
					$aResult['checks'][] = new CheckResult(CheckResult::INFO,
						"Current MySQL version ($sDBVersion), greater than minimum required version for next ".ITOP_APPLICATION." release (".self::MYSQL_NEXT_MIN_VERSION.")");
				}
				else
				{
					$aResult['checks'][] = new CheckResult(CheckResult::WARNING,
						"Warning : Current MySQL version is $sDBVersion, minimum required version for next ".ITOP_APPLICATION." release will be ".self::MYSQL_NEXT_MIN_VERSION);
				}
			}

			$bRet = true;
		}
		else
		{
			$aResult['checks'][] = new CheckResult(CheckResult::ERROR,
				"Error: Current MySQL version is $sDBVersion, minimum required version is ".self::MYSQL_MIN_VERSION);
			$bRet = false;
		}

		return $bRet;
	}

	/**
	 * @param string $sDBServer
	 * @param string $sDBUser
	 * @param string $sDBPwd
	 * @param bool $bTlsEnabled
	 * @param string $sTlsCa
	 *
	 * @return string
	 * @throws \MySQLException
	 */
	static public function GetMySQLVersion(
		$sDBServer, $sDBUser, $sDBPwd, $bTlsEnabled = false, $sTlsCa = null
	)
	{
		$oDBSource = new CMDBSource;
		$oDBSource->Init($sDBServer, $sDBUser, $sDBPwd, '', $bTlsEnabled, $sTlsCa);
		$sDBVersion = $oDBSource->GetDBVersion();
		return $sDBVersion;
	}

	static public function AsyncCheckDB($oPage, $aParameters)
	{
		$sDBServer = $aParameters['db_server'];
		$sDBUser = $aParameters['db_user'];
		$sDBPwd = $aParameters['db_pwd'];
		$sDBName = $aParameters['db_name'];

		$bIsWindows = (array_key_exists('WINDIR', $_SERVER) || array_key_exists('windir', $_SERVER));
		if ($bIsWindows && (preg_match('@([%!"])@',$sDBPwd) > 0))
		{
			// Unsupported Password, warn the user
			$oPage->add_ready_script(
<<<JS
$("#db_info").html('<div class="message message-error"><span class="message-title">Error:</span>On Windows, the backup won\'t work because database password contains %, ! or &quot; character</div>');
JS
			);
		}
		else
		{
			$sTlsEnabled = (isset($aParameters['db_tls_enabled'])) ? $aParameters['db_tls_enabled'] : null;
			$sTlsCA = (isset($aParameters['db_tls_ca'])) ? $aParameters['db_tls_ca'] : null;

			$oPage->add_ready_script('oXHRCheckDB = null;');

			$checks = SetupUtils::CheckDbServer($sDBServer, $sDBUser, $sDBPwd, $sTlsEnabled, $sTlsCA);

			if ($checks === false)
			{
				// Connection failed, disable the "Next" button
				$oPage->add_ready_script('$("#wiz_form").data("db_connection", "error");');
				$oPage->add_ready_script(
					<<<JS
$("#db_info").html('<div class="message message-error"><span class="message-title">Error:</span>No connection to the database</div>');
JS
			);
			}
			else
			{
				$aErrors = array();
				$aWarnings = array();
				foreach ($checks['checks'] as $oCheck)
				{
					if ($oCheck->iSeverity == CheckResult::ERROR)
					{
						$aErrors[] = $oCheck->sLabel;
					}
					else
					{
						if ($oCheck->iSeverity == CheckResult::WARNING)
						{
							$aWarnings[] = $oCheck->sLabel;
						}
					}
				}
				if (count($aErrors) > 0)
				{
					$sErrorsToDisplay = utils::HtmlEntities(implode('<br/>', $aErrors));
					$oPage->add_ready_script('$("#wiz_form").data("db_connection", "error");');
					$oPage->add_ready_script(
<<<JS
$("#db_info").html('<div class="message message-error"><span class="message-title">Error:</span>$sErrorsToDisplay</div>');
JS
					);
				}
				else
				{
					if (count($aWarnings) > 0)
					{
						$sWarningsToDisplay = utils::HtmlEntities(implode('<br/>', $aWarnings));
						$oPage->add_ready_script('$("#wiz_form").data("db_connection", "");');
						$oPage->add_ready_script(
							<<<JS
$("#db_info").html('<div class="message message-warning"><span class="message-title">Warning:</span>$sWarningsToDisplay</div>');
JS
						);
					}
					else
					{
						$oPage->add_ready_script('$("#wiz_form").data("db_connection", "");');
						$oPage->add_ready_script(
							<<<JS
$("#db_info").html('<div class="message message-valid"><span class="message-title">Success:</span>Database server connection ok.</div>');
JS
						);
					}
				}

				if ($checks['databases'] == null)
				{
					$sDBNameInput = '<input id="db_name" name="db_name" size="15" maxlen="32" value="'.htmlentities($sDBName, ENT_QUOTES, 'UTF-8').'"/><span style="width:20px;" id="v_db_name"></span>';
					$oPage->add_ready_script(
<<<JS
$("#table_info").html('<div class="message message-error"><span class="message-title">Error:</span>Not enough rights to enumerate the databases</div>');
JS
					);
				}
				else
				{
					$sDBNameInput = '<select id="db_name" name="db_name">';
					foreach ($checks['databases'] as $sDatabaseName)
					{
						if ($sDatabaseName != 'information_schema')
						{
							$sEncodedName = htmlentities($sDatabaseName, ENT_QUOTES, 'UTF-8');
							$sSelected = ($sDatabaseName == $sDBName) ? ' selected ' : '';
							$sDBNameInput .= '<option value="'.$sEncodedName.'" '.$sSelected.'>'.$sEncodedName.'</option>';
						}
					}
					$sDBNameInput .= '</select>';
				}
				$oPage->add_ready_script('$("#db_name_container").html("'.addslashes($sDBNameInput).'");');
				$oPage->add_ready_script('$("#db_name").bind("click keyup change", function() { $("#existing_db").prop("checked", true); WizardUpdateButtons(); });');

			}
		}
		$oPage->add_ready_script('WizardUpdateButtons();');
	}

	/**
	 * Helper function to get the available languages from the given directory
	 * @param $sDir String Path to the dictionary
	 * @return array of language code => description
	 */
	static public function GetAvailableLanguages($sDir)
	{
		require_once(APPROOT.'/core/coreexception.class.inc.php');
		require_once(APPROOT.'/core/dict.class.inc.php');

		$aFiles = scandir($sDir);
		foreach($aFiles as $sFile)
		{
			if ($sFile == '.' || $sFile == '..' || $sFile == '.svn' || $sFile == '.git')
			{
				// Skip
				continue;
			}

			$sFilePath = $sDir.'/'.$sFile;
			if (is_file($sFilePath) && preg_match('/^.*dict.*\.php$/i', $sFilePath, $aMatches))
			{
				require_once($sFilePath);
			}
		}

		return Dict::GetLanguages();
	}

	static public function GetLanguageSelect($sSourceDir, $sInputName, $sDefaultLanguageCode)
	{
		$sHtml = '<select  id="'.$sInputName.'" name="'.$sInputName.'">';
		$sSourceDir = APPROOT.'dictionaries/';
		$aLanguages = SetupUtils::GetAvailableLanguages($sSourceDir);
		foreach($aLanguages as $sCode => $aInfo)
		{
			$sSelected = ($sCode == $sDefaultLanguageCode) ? 'selected ' : '';
			$sHtml .= '<option value="'.$sCode.'" '.$sSelected.'>'.htmlentities($aInfo['description'], ENT_QUOTES, 'UTF-8').' ('.htmlentities($aInfo['localized_description'], ENT_QUOTES, 'UTF-8').')</option>';
		}
		$sHtml .= '</select></td></tr>';

		return $sHtml;
	}

	/**
	 *
	 * @param $oWizard
	 * @param bool $bAbortOnMissingDependency ...
	 * @param array $aModulesToLoad List of modules to search for, defaults to all if ommitted
	 *
	 * @return array
	 * @throws Exception
	 */
	public static function AnalyzeInstallation($oWizard, $bAbortOnMissingDependency = false, $aModulesToLoad = null)
	{
		require_once(APPROOT.'/setup/moduleinstaller.class.inc.php');
		$oConfig = new Config();
		$sSourceDir = $oWizard->GetParameter('source_dir', '');

		if (strpos($sSourceDir, APPROOT) !== false)
		{
			$sRelativeSourceDir = str_replace(APPROOT, '', $sSourceDir);
		}
		else if (strpos($sSourceDir, $oWizard->GetParameter('previous_version_dir')) !== false)
		{
			$sRelativeSourceDir = str_replace($oWizard->GetParameter('previous_version_dir'), '', $sSourceDir);
		}
		else
		{
			throw(new Exception('Internal error: AnalyzeInstallation: source_dir is neither under APPROOT nor under previous_installation_dir ???'));
		}


		$aParamValues = array(
			'db_server' => $oWizard->GetParameter('db_server', ''),
			'db_user' => $oWizard->GetParameter('db_user', ''),
			'db_pwd' => $oWizard->GetParameter('db_pwd', ''),
			'db_name' => $oWizard->GetParameter('db_name', ''),
			'db_prefix' => $oWizard->GetParameter('db_prefix', ''),
			'db_tls_enabled' => $oWizard->GetParameter('db_tls_enabled', false),
			'db_tls_ca' => $oWizard->GetParameter('db_tls_ca', ''),
			'source_dir' => $sRelativeSourceDir,
		);
		$oConfig->UpdateFromParams($aParamValues, null);
		$aDirsToScan = array($sSourceDir);

		if (is_dir(APPROOT.'extensions'))
		{
			$aDirsToScan[] = APPROOT.'extensions';
		}
		if (is_dir($oWizard->GetParameter('copy_extensions_from')))
		{
			$aDirsToScan[] = $oWizard->GetParameter('copy_extensions_from');
		}
		$sExtraDir = APPROOT.'data/production-modules/';
		if (is_dir($sExtraDir))
		{
			$aDirsToScan[] = $sExtraDir;
		}
		$oProductionEnv = new RunTimeEnvironment();
		$aAvailableModules = $oProductionEnv->AnalyzeInstallation($oConfig, $aDirsToScan, $bAbortOnMissingDependency, $aModulesToLoad);

		foreach($aAvailableModules as $key => $aModule)
		{
			$bIsExtra = (array_key_exists('root_dir', $aModule) && (strpos($aModule['root_dir'], $sExtraDir) !== false)); // Some modules (root, datamodel) have no 'root_dir'
			if ($bIsExtra)
			{
				// Modules in data/production-modules/ are considered as mandatory and always installed
				$aAvailableModules[$key]['visible'] = false;
			}
		}

		return $aAvailableModules;
	}

	/**
	 * @param WizardController $oWizard
	 *
	 * @return array|bool
	 */
	public static function GetApplicationVersion($oWizard)
	{
		require_once(APPROOT.'/setup/moduleinstaller.class.inc.php');
		$oConfig = new Config();

		$aParamValues = array(
			'db_server' => $oWizard->GetParameter('db_server', ''),
			'db_user' => $oWizard->GetParameter('db_user', ''),
			'db_pwd' => $oWizard->GetParameter('db_pwd', ''),
			'db_name' => $oWizard->GetParameter('db_name', ''),
			'db_prefix' => $oWizard->GetParameter('db_prefix', ''),
			'db_tls_enabled' => $oWizard->GetParameter('db_tls_enabled', false),
			'db_tls_ca' => $oWizard->GetParameter('db_tls_ca', ''),
			'source_dir' => '',
		);
		$oConfig->UpdateFromParams($aParamValues, null);

		$oProductionEnv = new RunTimeEnvironment();
		return $oProductionEnv->GetApplicationVersion($oConfig);
	}

	/**
	 * @param array $aModules List of available module codes
	 *
	 * @return bool true if we are in a iTop product package (professional, essential, ...)
	 * @since 2.7.0 N°2533
	 */
	public static function IsProductVersion($aModules)
	{
		return array_key_exists('itsm-designer-connector', $aModules);
	}

	/**
	 * @param array $aModules Available modules with code as key and metadata array as values
	 *    Same structure as the one returned by {@link \RunTimeEnvironment::AnalyzeInstallation}
	 * @param string $sExtensionsDir In the setup, get value with the 'extensions_dir' parameter
	 *
	 * @return string Error message if has manually installed modules, empty string otherwise
	 *
	 * @since 2.7.0 N°2533
	 */
	public static function CheckManualInstallDirEmpty($aModules, $sExtensionsDir = 'extensions')
	{
		if (!static::IsProductVersion($aModules))
		{
			return '';
		}

		$sManualInstallModulesFullPath = APPROOT.$sExtensionsDir.DIRECTORY_SEPARATOR;
		//simple test in order to prevent install iTop pro with module in extension folder
		$aFileInfo = scandir($sManualInstallModulesFullPath);
		foreach ($aFileInfo as $sFolder)
		{
			if ($sFolder != "." && $sFolder != ".." && is_dir($sManualInstallModulesFullPath.$sFolder) === true)
			{
				return "Some modules are present in the '$sExtensionsDir' directory, this is not allowed when using ".ITOP_APPLICATION;
			}
		}
		return '';
	}

	/**
	 * Checks if the content of a directory matches the given manifest
	 * @param string $sBaseDir Path to the root directory of iTop
	 * @param string $sSourceDir Relative path to the directory to check under $sBaseDir
	 * @param $aManifest
	 * @param array $aExcludeNames
	 * @param Hash $aResult Used for recursion
	 * @return hash Hash array ('added' => array(), 'removed' => array(), 'modified' => array())
	 * @internal param array $aDOMManifest Array of array('path' => relative_path 'size'=> iSize, 'md5' => sHexMD5)
	 */
	public static function CheckDirAgainstManifest($sBaseDir, $sSourceDir, $aManifest, $aExcludeNames = array('.svn', '.git'), $aResult = null)
	{
//echo "CheckDirAgainstManifest($sBaseDir, $sSourceDir ...)\n"; 
		if ($aResult === null)
		{
			$aResult = array('added' => array(), 'removed' => array(), 'modified' => array());
		}

		if (substr($sSourceDir, 0, 1) == '/')
		{
			$sSourceDir = substr($sSourceDir, 1);
		}

		// Manifest limited to all the files supposed to be located in this directory
		$aDirManifest = array();
		foreach($aManifest as $aFileInfo)
		{
			$sDir = dirname($aFileInfo['path']);
			if ($sDir == '.')
			{
				// Hmm... the file seems located at the root of iTop
				$sDir = '';
			}
			if ($sDir == $sSourceDir)
			{
				$aDirManifest[basename($aFileInfo['path'])] = $aFileInfo;
			}
		}

//echo "The manifest contains ".count($aDirManifest)." files for the directory '$sSourceDir' (and below)\n"; 

		// Read the content of the directory
		foreach(glob($sBaseDir.'/'.$sSourceDir .'/*') as $sFilePath)
		{
			$sFile = basename($sFilePath);
//echo "Checking $sFile ($sFilePath)\n"; 

			if (in_array(basename($sFile), $aExcludeNames)) continue;

			if(is_dir($sFilePath))
			{
				$aResult = self::CheckDirAgainstManifest($sBaseDir, $sSourceDir.'/'.$sFile, $aManifest, $aExcludeNames, $aResult);
			}
			else
			{
				if (!array_key_exists($sFile, $aDirManifest))
				{
//echo "New file ".$sFile." in $sSourceDir\n"; 
					$aResult['added'][$sSourceDir.'/'.$sFile] = true;
				}
				else
				{
					$aStats = stat($sFilePath);
					if ($aStats['size'] != $aDirManifest[$sFile]['size'])
					{
						// Different sizes
						$aResult['modified'][$sSourceDir.'/'.$sFile] = 'Different sizes. Original size: '.$aDirManifest[$sFile]['size'].' bytes, actual file size on disk: '.$aStats['size'].' bytes.';
					}
					else
					{
						// Same size, compare the md5 signature
						$sMD5 = md5_file($sFilePath);
						if ($sMD5 != $aDirManifest[$sFile]['md5'])
						{
							$aResult['modified'][$sSourceDir.'/'.$sFile] = 'Content modified (MD5 checksums differ).';
//echo $sSourceDir.'/'.$sFile." modified ($sMD5 == {$aDirManifest[$sFile]['md5']})\n";
						}
//else
//{
//	echo $sSourceDir.'/'.$sFile." unmodified ($sMD5 == {$aDirManifest[$sFile]['md5']})\n";
//}
					}
//echo "Removing ".$sFile." from aDirManifest\n"; 
					unset($aDirManifest[$sFile]);
				}
			}
		}
		// What remains in the array are files that were deleted
		foreach($aDirManifest as $sDeletedFile => $void)
		{
			$aResult['removed'][$sSourceDir.'/'.$sDeletedFile] = true;
		}
		return $aResult;
	}

	public static function CheckDataModelFiles($sManifestFile, $sBaseDir)
	{
		$oXML = simplexml_load_file($sManifestFile);
		$aManifest = array();
		foreach($oXML as $oFileInfo)
		{
			$aManifest[] = array('path' => (string)$oFileInfo->path, 'size' => (int)$oFileInfo->size, 'md5' => (string)$oFileInfo->md5);
		}

		$sBaseDir = preg_replace('|modules/?$|', '', $sBaseDir);
		$aResults = self::CheckDirAgainstManifest($sBaseDir, 'modules', $aManifest);

//		echo "<pre>Comparison of ".dirname($sBaseDir)."/modules against $sManifestFile:\n".print_r($aResults, true)."</pre>";
		return $aResults;
	}

	public static function CheckPortalFiles($sManifestFile, $sBaseDir)
	{
		$oXML = simplexml_load_file($sManifestFile);
		$aManifest = array();
		foreach($oXML as $oFileInfo)
		{
			$aManifest[] = array('path' => (string)$oFileInfo->path, 'size' => (int)$oFileInfo->size, 'md5' => (string)$oFileInfo->md5);
		}

		$aResults = self::CheckDirAgainstManifest($sBaseDir, 'portal', $aManifest);

//		echo "<pre>Comparison of ".dirname($sBaseDir)."/portal:\n".print_r($aResults, true)."</pre>";
		return $aResults;
	}

	public static function CheckApplicationFiles($sManifestFile, $sBaseDir)
	{
		$oXML = simplexml_load_file($sManifestFile);
		$aManifest = array();
		foreach($oXML as $oFileInfo)
		{
			$aManifest[] = array('path' => (string)$oFileInfo->path, 'size' => (int)$oFileInfo->size, 'md5' => (string)$oFileInfo->md5);
		}

		$aResults = array('added' => array(), 'removed' => array(), 'modified' => array());
		foreach(array('addons', 'core', 'dictionaries', 'js', 'application', 'css', 'pages', 'synchro', 'webservices') as $sDir)
		{
			$aTmp = self::CheckDirAgainstManifest($sBaseDir, $sDir, $aManifest);
			$aResults['added'] = array_merge($aResults['added'], $aTmp['added']);
			$aResults['modified'] = array_merge($aResults['modified'], $aTmp['modified']);
			$aResults['removed'] = array_merge($aResults['removed'], $aTmp['removed']);
		}

//		echo "<pre>Comparison of ".dirname($sBaseDir)."/portal:\n".print_r($aResults, true)."</pre>";
		return $aResults;
	}

	/**
	 * @param string $sInstalledVersion
	 * @param string $sSourceDir
	 * @return bool|hash
	 * @throws Exception
	 */
	public static function CheckVersion($sInstalledVersion, $sSourceDir)
	{
		$sManifestFilePath = self::GetVersionManifest($sInstalledVersion);
		if ($sSourceDir != '')
		{
			if (file_exists($sManifestFilePath))
			{
				$aDMchanges = self::CheckDataModelFiles($sManifestFilePath, $sSourceDir);
				//$aPortalChanges = self::CheckPortalFiles($sManifestFilePath, $sSourceDir);
				//$aCodeChanges = self::CheckApplicationFiles($sManifestFilePath, $sSourceDir);

				//echo("Changes detected compared to $sInstalledVersion:<br/>DataModel:<br/><pre>".print_r($aDMchanges, true)."</pre>");
				//echo("Changes detected compared to $sInstalledVersion:<br/>DataModel:<br/><pre>".print_r($aDMchanges, true)."</pre><br/>Portal:<br/><pre>".print_r($aPortalChanges, true)."</pre><br/>Code:<br/><pre>".print_r($aCodeChanges, true)."</pre>");
				return $aDMchanges;
			}
			else
			{
				return false;
			}
		}
		else
		{
			throw(new Exception("Cannot check version '$sInstalledVersion', no source directory provided to check the files."));
		}
	}

	public static function GetVersionManifest($sInstalledVersion)
	{
		if (preg_match('/^([0-9]+)\./', $sInstalledVersion, $aMatches))
		{
			return APPROOT.'datamodels/'.$aMatches[1].'.x/manifest-'.$sInstalledVersion.'.xml';
		}
		return false;
	}

	public static function CheckWritableDirs($aWritableDirs)
	{
		$aNonWritableDirs = array();
		foreach($aWritableDirs as $sDir)
		{
			$sFullPath = APPROOT.$sDir;
			if (is_dir($sFullPath) && !is_writable($sFullPath))
			{
				$aNonWritableDirs[APPROOT.$sDir] = new CheckResult(CheckResult::ERROR, "The directory <b>'".APPROOT.$sDir."'</b> exists but is not writable for the application.");
			}
			else if (file_exists($sFullPath) && !is_dir($sFullPath))
			{
				$aNonWritableDirs[APPROOT.$sDir] = new CheckResult(CheckResult::ERROR, ITOP_APPLICATION." needs the directory <b>'".APPROOT.$sDir."'</b> to be writable. However <i>file</i> named <b>'".APPROOT.$sDir."'</b> already exists.");
			}
			else if (!is_dir($sFullPath) && !is_writable(APPROOT))
			{
				$aNonWritableDirs[APPROOT.$sDir] = new CheckResult(CheckResult::ERROR, ITOP_APPLICATION." needs the directory <b>'".APPROOT.$sDir."'</b> to be writable. The directory <b>'".APPROOT.$sDir."'</b> does not exist and '".APPROOT."' is not writable, the application cannot create the directory '$sDir' inside it.");
			}
		}
		return $aNonWritableDirs;
	}

	public static function GetLatestDataModelDir()
	{
		$sBaseDir = APPROOT.'datamodels';

		$aDirs = glob($sBaseDir.'/*', GLOB_MARK | GLOB_ONLYDIR);
		if ($aDirs !== false)
		{
			sort($aDirs);
			// Windows: there is a backslash at the end (though the path is made of slashes!!!)
			$sDir = basename(array_pop($aDirs));
			$sRes = $sBaseDir.'/'.$sDir.'/';
			return $sRes;
		}
		return false;
	}

	public static function GetCompatibleDataModelDir($sInstalledVersion)
	{
		if (preg_match('/^([0-9]+)\./', $sInstalledVersion, $aMatches))
		{
			$sMajorVersion = $aMatches[1];
			$sDir = APPROOT.'datamodels/'.$sMajorVersion.'.x/';
			if (is_dir($sDir))
			{
				return $sDir;
			}
		}
		return false;
	}

	static public function GetDataModelVersion($sDatamodelDir)
	{
		$sVersionFile = $sDatamodelDir.'version.xml';
		if (file_exists($sVersionFile))
		{
			$oParams = new XMLParameters($sVersionFile);
			return $oParams->Get('version');
		}
		return false;
	}

	/**
	 * Returns an array of xml nodes describing the licences.
	 * @param $sEnv string|null Execution environment. If present loads licenses only for installed modules else loads all licenses available.
	 * @return array Licenses list.
	 */
	static public function GetLicenses($sEnv = null)
	{
		$aLicenses = array();
		$aLicenceFiles = glob(APPROOT.'setup/licenses/*.xml');
		if (empty($sEnv))
		{
			$aLicenceFiles = array_merge($aLicenceFiles, glob(APPROOT.'datamodels/*/*/license.*.xml'));
			$aLicenceFiles = array_merge($aLicenceFiles, glob(APPROOT.'extensions/*/license.*.xml'));
			$aLicenceFiles = array_merge($aLicenceFiles, glob(APPROOT.'data/*-modules/*/license.*.xml'));
		}
		else
		{
			$aLicenceFiles = array_merge($aLicenceFiles, glob(APPROOT.'env-'.$sEnv.'/*/license.*.xml'));
		}
		foreach ($aLicenceFiles as $sFile)
		{
			$oXml = simplexml_load_file($sFile);
			if (!empty($oXml->license))
			{
				foreach ($oXml->license as $oLicense)
				{
					$aLicenses[(string)$oLicense->product] = $oLicense;
				}
			}
		}
		return $aLicenses;
	}

	/**
	 * @return string path to the log file where the create and/or alter queries are written
	 */
	static public function GetSetupQueriesFilePath()
	{
		return APPROOT.'log/setup-queries-'.strftime('%Y-%m-%d_%H_%M').'.sql';
	}

	public static function EnterMaintenanceMode($oConfig)
	{
		@touch(MAINTENANCE_MODE_FILE);
		self::Log("----> Entering maintenance mode");
		self::WaitCronTermination($oConfig, "maintenance");
	}

	public static function ExitMaintenanceMode($bLog = true)
	{
		@unlink(MAINTENANCE_MODE_FILE);
		if ($bLog)
		{
			self::Log("<---- Exiting maintenance mode");
		}
	}

	public static function IsInMaintenanceMode()
	{
		return file_exists(MAINTENANCE_MODE_FILE);
	}

	public static function EnterReadOnlyMode($oConfig)
	{
		@touch(READONLY_MODE_FILE);
		self::Log("----> Entering read only mode");
		self::WaitCronTermination($oConfig, "read only");
	}

	public static function ExitReadOnlyMode($bLog = true)
	{
		@unlink(READONLY_MODE_FILE);
		if ($bLog)
		{
			self::Log("<---- Exiting read only mode");
		}
	}

	public static function IsInReadOnlyMode()
	{
		return file_exists(READONLY_MODE_FILE);
	}

	/**
	 * @param Config $oConfig
	 * @param string $sMode
	 */
	private static function WaitCronTermination($oConfig, $sMode)
	{
		try
		{
			// Wait for cron to stop
			if (is_null($oConfig))
			{
				return;
			}
			// Use mutex to check if cron is running
			$oMutex = new iTopMutex(
				'cron'.$oConfig->Get('db_name').$oConfig->Get('db_subname'),
				$oConfig->Get('db_host'),
				$oConfig->Get('db_user'),
				$oConfig->Get('db_pwd'),
				$oConfig->Get('db_tls.enabled'),
				$oConfig->Get('db_tls.ca')
			);
			$iCount = 1;
			$iStarted = time();
			$iMaxDuration = $oConfig->Get('cron_max_execution_time');
			$iTimeLimit = $iStarted + $iMaxDuration;
			while ($oMutex->IsLocked())
			{
				self::Log("Waiting for cron to stop ($iCount)");
				$iCount++;
				sleep(1);
				if (time() > $iTimeLimit)
				{
					throw new Exception("Cannot enter $sMode mode, consider stopping the cron temporarily");
				}
			}
		} catch (Exception $e)
		{
			// Ignore errors
		}
	}

	/**
	 * Create and store Setup authentication token
	 *
	 * @return string token
	 */
	public final static function CreateSetupToken()
	{
		if (!is_dir(APPROOT.'data'))
		{
			mkdir(APPROOT.'data');
		}
		if (!is_dir(APPROOT.'data/setup'))
		{
			mkdir(APPROOT.'data/setup');
		}
		$sUID = hash('sha256', rand());
		file_put_contents(APPROOT.'data/setup/authent', $sUID);
		return $sUID;
	}

	/**
	 * Verify Setup authentication token (from the request parameter 'authent')
	 *
	 * @param bool $bRemoveToken
	 *
	 * @throws \SecurityException
	 */
	public final static function CheckSetupToken($bRemoveToken = false)
	{
		$sAuthent = utils::ReadParam('authent', '', false, 'raw_data');
		$sTokenFile = APPROOT.'data/setup/authent';
		if (!file_exists($sTokenFile) || $sAuthent !== file_get_contents($sTokenFile))
		{
			throw new SecurityException('Setup operations are not allowed outside of the setup');
		}
		if ($bRemoveToken)
		{
			@unlink($sTokenFile);
		}
	}

	private final static function Log($sText)
	{
		if (class_exists('SetupPage'))
		{
			SetupPage::log($sText);
		}
		else
		{
			IssueLog::Info($sText);
		}
	}
}

/**
 * Helper class to write rules (as PHP expressions) in the 'auto_select' field of the 'module'
 */
class SetupInfo
{
	public static $aSelectedModules = array();

	/**
	 * Called by the setup process to initializes the list of selected modules. Do not call this method
	 * from an 'auto_select' rule
	 * @param hash $aModules
	 * @return void
	 */
	public static function SetSelectedModules($aModules)
	{
		self::$aSelectedModules = $aModules;
	}

	/**
	 * Returns true if a module is selected (as a consequence of the end-user's choices,
	 * or because the module is hidden, or mandatory, or because of a previous auto_select rule)
	 * @param string $sModuleId The identifier of the module (without the version number. Example: itop-config-mgmt)
	 * @return boolean True if the module is already selected, false otherwise
	 */
	public static function ModuleIsSelected($sModuleId)
	{
		return (array_key_exists($sModuleId, self::$aSelectedModules));
	}
}
