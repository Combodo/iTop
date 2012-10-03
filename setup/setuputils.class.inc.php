<?php
// Copyright (C) 2012 Combodo SARL
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

/**
 * The standardized result of any pass/fail check performed by the setup
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html GPL
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
 * Namespace for storing all the functions/utilities needed by both
 * the setup wizard and the installation process
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html GPL
 */

class SetupUtils
{
	const PHP_MIN_VERSION = '5.2.0';
	const MYSQL_MIN_VERSION = '5.0.0';
	const MIN_MEMORY_LIMIT = 33554432; // = 32*1024*1024 Beware: Computations are not allowed in defining constants
	const SUHOSIN_GET_MAX_VALUE_LENGTH = 2048;

	/**
	 * Check the version of PHP, the needed PHP extension and a number
	 * of configuration parameters (memory_limit, max_upload_file_size, etc...)
	 * @param SetupPage $oP The page used only for its 'log' method
	 * @return array An array of CheckResults objects
	 */
	static function CheckPHPVersion()
	{
		$aResult = array();

		SetupPage::log('Info - CheckPHPVersion');
		if (version_compare(phpversion(), self::PHP_MIN_VERSION, '>='))
		{
			$aResult[] = new CheckResult(CheckResult::INFO, "The current PHP Version (".phpversion().") is greater than the minimum version required to run ".ITOP_APPLICATION.", which is (".self::PHP_MIN_VERSION.")");
		}
		else
		{
			$aResult[] = new CheckResult(CheckResult::ERROR, "Error: The current PHP Version (".phpversion().") is lower than the minimum version required to run ".ITOP_APPLICATION.", which is (".self::PHP_MIN_VERSION.")");
		}
		$aMandatoryExtensions = array('mysqli', 'iconv', 'simplexml', 'soap', 'hash', 'json', 'session', 'pcre', 'dom');
		$aOptionalExtensions = array('mcrypt' => 'Strong encryption will not be used.',
									 'ldap' => 'LDAP authentication will be disabled.');
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
			if (extension_loaded($sExtension))
			{
				$aExtensionsOk[] = $sExtension;
			}
			else
			{
				$aMissingExtensions[$sExtension] = $sMessage;
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
		else
		{
			$sPhpIniFile = 'php.ini';
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
			$aResult[] = new CheckResult(CheckResult::WARNING, "post_max_size (".ini_get('post_max_size').") in php.ini should be bigger than upload_max_filesize (".ini_get('upload_max_filesize').") otherwise you cannot upload files of the maximun size.");
		}


		SetupPage::log("Info - upload_max_filesize: ".ini_get('upload_max_filesize'));
		SetupPage::log("Info - post_max_size: ".ini_get('post_max_size'));
		SetupPage::log("Info - max_file_uploads: ".ini_get('max_file_uploads'));

		// Check some more ini settings here, needed for file upload
		if (function_exists('get_magic_quotes_gpc'))
		{
			if (@get_magic_quotes_gpc())
			{
				$aResult[] = new CheckResult(CheckResult::ERROR, "'magic_quotes_gpc' is set to On. Please turn it Off in php.ini before continuing.");
			}
		}
		if (function_exists('magic_quotes_runtime'))
		{
			if (@magic_quotes_runtime())
			{
				$aResult[] = new CheckResult(CheckResult::ERROR, "'magic_quotes_runtime' is set to On. Please turn it Off in php.ini before continuing.");
			}
		}


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
			if ($iMemoryLimit < self::MIN_MEMORY_LIMIT)
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
		return $aResult;
	}

	/**
	 * Check that the backup could be executed
	 * @param Page $oP The page used only for its 'log' method
	 * @return array An array of CheckResults objects
	 */
	static function CheckBackupPrerequisites($sDestDir)
	{
		$aResult = array();
		SetupPage::log('Info - CheckBackupPrerequisites');

		// zip extension
		//
		if (!extension_loaded('zip'))
		{
			$sMissingExtensionLink = "<a href=\"http://www.php.net/manual/en/book.zip.php\" target=\"_blank\">zip</a>";
			$aResult[] = new CheckResult(CheckResult::ERROR, "Missing PHP extension: zip", $sMissingExtensionLink);
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
		$sMySQLBinDir = utils::ReadParam('mysql_bindir', '', true);
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
			$aResult[] = new CheckResult(CheckResult::INFO, "mysqldump is present: ".$aOutput[0]);
		}
		elseif ($iRetCode == 1)
		{
			$aResult[] = new CheckResult(CheckResult::ERROR, "mysqldump could not be found: ".implode(' ', $aOutput)." - Please make sure it is installed and in the path.");
		}
		else
		{
			$aResult[] = new CheckResult(CheckResult::ERROR, "mysqldump could not be executed (retcode=$iRetCode): Please make sure it is installed and in the path");
		}
		foreach($aOutput as $sLine)
		{
			SetupPage::log('Info - mysqldump -V said: '.$sLine);
		}

		// check disk space
		// to do... evaluate how we can correlate the DB size with the size of the dump (and the zip!)
		// E.g. 2,28 Mb after a full install, giving a zip of 26 Kb (data = 26 Kb)
		// Example of query (DB without a suffix)
		//$sDBSize = "SELECT SUM(ROUND(DATA_LENGTH/1024/1024, 2)) AS size_mb FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = `$sDBName`";

		return $aResult;
	}

	/**
	 * Helper function to retrieve the system's temporary directory
	 * Emulates sys_get_temp_dir if neeed (PHP < 5.2.1)
	 * @return string Path to the system's temp directory
	 */
	static function GetTmpDir()
	{
		// try to figure out what is the temporary directory
		// prior to PHP 5.2.1 the function sys_get_temp_dir
		// did not exist
		if ( !function_exists('sys_get_temp_dir'))
		{
			if( $temp=getenv('TMP') ) return realpath($temp);
			if( $temp=getenv('TEMP') ) return realpath($temp);
			if( $temp=getenv('TMPDIR') ) return realpath($temp);
			$temp=tempnam(__FILE__,'');
			if (file_exists($temp))
			{
				unlink($temp);
				return realpath(dirname($temp));
			}
			return null;
		}
		else
		{
			return realpath(sys_get_temp_dir());
		}
	}

	/**
	 * Helper function to retrieve the directory where files are to be uploaded
	 * @return string Path to the temp directory used for uploading files
	 */
	static function GetUploadTmpDir()
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
	 */
	public static function rrmdir($dir)
	{
		if ((strlen(trim($dir)) == 0) || ($dir == '/') || ($dir == '\\'))
		{
			throw new Exception("Attempting to delete directory: '$dir'");
		}
		self::tidydir($dir);
		rmdir($dir);
	}

	/**
	 * Helper to recursively cleanup a directory
	 */
	public static function tidydir($dir)
	{
		if ((strlen(trim($dir)) == 0) || ($dir == '/') || ($dir == '\\'))
		{
			throw new Exception("Attempting to delete directory: '$dir'");
		}

		foreach(glob($dir . '/*') as $file)
		{
			if(is_dir($file))
			{
				self::tidydir($file);
				rmdir($file);
			}
			else
			{
				unlink($file);
			}
		}
	}

	/**
	 * Helper to build the full path of a new directory
	 */
	public static function builddir($dir)
	{
		$parent = dirname($dir);
		if(!is_dir($parent))
		{
			self::builddir($parent);
		}
		if (!is_dir($dir))
		{
			mkdir($dir);
		}
	}

	/**
	 * Helper to copy a directory to a target directory, skipping .SVN files (for developer's comfort!)
	 * Returns true if successfull
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
					if ($sFile == '.' || $sFile == '..' || $sFile == '.svn')
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
								symlink($sSource.'/'.$sFile, $sDest.'/'.$sFile);
							}
							else
							{
								throw(new Exception("Error, cannot *copy* '$sSource/$sFile' to '$sDest/$sFile' using symbolic links, 'symlink' is not supported on this system."));
							}
						}
						else
						{
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
	static function GetPreviousInstance($sDir)
	{
		$bFound = false;
		$sSourceDir = '';
		$sSourceEnvironement = '';
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
				'db_server' => $oPrevConf->GetDBHost(),
				'db_user' => $oPrevConf->GetDBUser(),
				'db_pwd' => $oPrevConf->GetDBPwd(),
				'db_name' => $oPrevConf->GetDBName(),
				'db_prefix' => $oPrevConf->GetDBSubname(),
			);
		}
		
		return $aResult;
	}
	
	static function CheckDiskSpace($sDir)
	{
		while(($f = @disk_free_space($sDir)) == false)
		{
			if ($sDir == dirname($sDir)) break;
			if ($sDir == '.') break;
			$sDir = dirname($sDir);
		}
		
		return $f;
	}
	
	static function HumanReadableSize($fBytes)
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
	
	static function DisplayDBParameters($oPage, $bAllowDBCreation, $sDBServer, $sDBUser, $sDBPwd, $sDBName, $sDBPrefix, $sNewDBName = '')
	{
		$oPage->add('<tr><td colspan="2">');
		$oPage->add('<fieldset><legend>Database Server Connection</legend>');
		$oPage->add('<table>');
		$oPage->add('<tr><td>Server Name:</td><td><input id="db_server" type="text" name="db_server" value="'.htmlentities($sDBServer, ENT_QUOTES, 'UTF-8').'" size="15"/></td><td>E.g. "localhost", "dbserver.mycompany.com" or "192.142.10.23"</td></tr>');
		$oPage->add('<tr><td>Login:</td><td><input id="db_user" type="text" name="db_user" value="'.htmlentities($sDBUser, ENT_QUOTES, 'UTF-8').'" size="15"/></td><td rowspan="2" style="vertical-align:top">The account must have the following privileges on the database: SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, CREATE VIEW, SUPER, TRIGGER</td></tr>');
		$oPage->add('<tr><td>Password:</td><td><input id="db_pwd" type="password" name="db_pwd" value="'.htmlentities($sDBPwd, ENT_QUOTES, 'UTF-8').'" size="15"/></td></tr>');
		$oPage->add('</table>');
		$oPage->add('</fieldset>');
		$oPage->add('</td></tr>');
		
		$oPage->add('<tr><td colspan="2"><span id="db_info"></span></td></tr>');
		
		$oPage->add('<tr><td colspan="2">');
		$oPage->add('<fieldset><legend>Database</legend>');
		$oPage->add('<table>');
		if ($bAllowDBCreation)
		{
			$oPage->add('<tr><td><input type="radio" id="existing_db" name="create_db" value="no"/><label for="existing_db">&nbsp;Use the existing database:</label></td>');
			$oPage->add('<td id="db_name_container"><input id="db_name" name="db_name" size="15" maxlen="32" value="'.htmlentities($sDBName, ENT_QUOTES, 'UTF-8').'"/><span style="width:20px;" id="v_db_name"></span></td></tr>');
			$oPage->add('<tr><td><input type="radio" id="create_db" name="create_db" value="yes"/><label for="create_db">&nbsp;Create a new database:</label></td>');
			$oPage->add('<td><input id="db_new_name" type="text" name="db_new_name" value="'.htmlentities($sNewDBName, ENT_QUOTES, 'UTF-8').'" size="15" maxlength="32"/><span style="width:20px;" id="v_db_new_name"></span></td></tr>');
			$oPage->add('<tr><td>Use a prefix for the tables:</td><td><input id="db_prefix" type="text" name="db_prefix" value="'.htmlentities($sDBPrefix, ENT_QUOTES, 'UTF-8').'" size="15" maxlength="32"/><span style="width:20px;" id="v_db_prefix"></span></td></tr>');
		}
		else
		{
			$oPage->add('<tr><td>Database Name:</td><td id="db_name_container"><input id="db_name" name="db_name" size="15" maxlen="32" value="'.htmlentities($sDBName, ENT_QUOTES, 'UTF-8').'"/><span style="width:20px;" id="v_db_name"></span></td></tr>');
			$oPage->add('<tr><td>Use a prefix for the tables:</td><td><input id="db_prefix" type="text" name="db_prefix" value="'.htmlentities($sDBPrefix, ENT_QUOTES, 'UTF-8').'" size="15"/><span style="width:20px;" id="v_db_prefix"></span></td></tr>');
		}
		$oPage->add('</table>');
		$oPage->add('</fieldset>');
		$oPage->add('<tr><td colspan="2"><span id="table_info">&nbsp;</span></td></tr>');
		$oPage->add('</td></tr>');
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
		'db_name': $("#db_name").val()
	}
	if (oXHRCheckDB !== null)
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
			bUsed = ($("#existing_db").attr("checked") == "checked");
			bMandatory = true;
		}
		if (sFieldId == 'db_new_name')
		{
			bUsed = ($("#create_db").attr("checked") == "checked");
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
			if (sValue.match(/^[A-Za-z][A-Za-z0-9_]*$/))
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

$("#db_server").bind("keyup change", function() { CheckDBConnection(); });
$("#db_user").bind("keyup change", function() { CheckDBConnection(); });
$("#db_pwd").bind("keyup change", function() { CheckDBConnection(); });
$("#db_new_name").bind("click keyup change", function() { $("#create_db").attr("checked", "checked"); WizardUpdateButtons(); });
$("#db_name").bind("click keyup change", function() {  $("#existing_db").attr("checked", "checked"); WizardUpdateButtons(); });
$("#db_prefix").bind("keyup change", function() { WizardUpdateButtons(); });
$("#existing_db").bind("click change", function() { WizardUpdateButtons(); });
$("#create_db").bind("click change", function() { WizardUpdateButtons(); });
EOF
		);
		
	}

	/**
	 * Helper function check the connection to the database, verify a few conditions (minimum version, etc...) and (if connected)
	 * enumerate the existing databases (if possible)
	 * @return mixed false if the connection failed or array('checks' => Array of CheckResult, 'databases' => Array of database names (as strings) or null if not allowed)
	 */
	static function CheckServerConnection($sDBServer, $sDBUser, $sDBPwd)
	{
		$aResult = array('checks' => array(), 'databases' => null);
		try
		{
			$oDBSource = new CMDBSource;
			$oDBSource->Init($sDBServer, $sDBUser, $sDBPwd);
			$aResult['checks'][] = new CheckResult(CheckResult::INFO, "Connection to '$sDBServer' as '$sDBUser' successful.");
			$aResult['checks'][] = new CheckResult(CheckResult::INFO, "Info - User privileges: ".($oDBSource->GetRawPrivileges()));

			$sDBVersion = $oDBSource->GetDBVersion();
			if (version_compare($sDBVersion, self::MYSQL_MIN_VERSION, '>='))
			{
				$aResult['checks'][] = new CheckResult(CheckResult::INFO, "Current MySQL version ($sDBVersion), greater than minimum required version (".self::MYSQL_MIN_VERSION.")");
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
			}
			else
			{
				$aResult['checks'][] = new CheckResult(CheckResult::ERROR, "Error: Current MySQL version is ($sDBVersion), minimum required version (".self::MYSQL_MIN_VERSION.")");
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
	
	static public function AsyncCheckDB($oPage, $aParameters)
	{
		$sDBServer = $aParameters['db_server'];
		$sDBUser = $aParameters['db_user'];
		$sDBPwd = $aParameters['db_pwd'];
		$sDBName = $aParameters['db_name'];

		$oPage->add_ready_script('oXHRCheckDB = null;'); 	

		$checks = SetupUtils::CheckServerConnection($sDBServer, $sDBUser, $sDBPwd);
		if ($checks === false)
		{
			// Connection failed, disable the "Next" button
			$oPage->add_ready_script('$("#wiz_form").data("db_connection", "error");');
			$oPage->add_ready_script('$("#db_info").html("No connection to the database...");');
		}
		else
		{
			$aErrors = array();
			$aWarnings = array();
			foreach($checks['checks'] as $oCheck)
			{
				if ($oCheck->iSeverity == CheckResult::ERROR)
				{
					$aErrors[] = $oCheck->sLabel;
				}
				else if ($oCheck->iSeverity == CheckResult::WARNING)
				{
					$aWarnings[] = $oCheck->sLabel;
				}
								}
			if (count($aErrors) > 0)
			{
				$oPage->add_ready_script('$("#wiz_form").data("db_connection", "error");');
				$oPage->add_ready_script('$("#db_info").html(\'<img src="../images/validation_error.png"/>&nbsp;<b>Error:</b> '.htmlentities(implode('<br/>', $aErrors), ENT_QUOTES, 'UTF-8').'\');');
			}
			else if (count($aWarnings) > 0)
			{
				$oPage->add_ready_script('$("#wiz_form").data("db_connection", "");');
				$oPage->add_ready_script('$("#db_info").html(\'<img src="../images/error.png"/>&nbsp;<b>Warning:</b> '.htmlentities(implode('<br/>', $aWarnings), ENT_QUOTES, 'UTF-8').'\');');
			}
			else
			{
				$oPage->add_ready_script('$("#wiz_form").data("db_connection", "");');
				$oPage->add_ready_script('$("#db_info").html(\'<img src="../images/validation_ok.png"/>&nbsp;Database server connection Ok.\');');
			}
			
			if ($checks['databases'] == null)
			{
				$sDBNameInput = '<input id="db_name" name="db_name" size="15" maxlen="32" value="'.htmlentities($sDBName, ENT_QUOTES, 'UTF-8').'"/><span style="width:20px;" id="v_db_name"></span>';
				$oPage->add_ready_script('$("#table_info").html(\'<img src="../images/error.png"/>&nbsp;Not enough rights to enumerate the databases\');');
			}
			else
			{
				$sDBNameInput = '<select id="db_name" name="db_name">';				
				foreach($checks['databases'] as $sDatabaseName)
				{
					if ($sDatabaseName != 'information_schema')
					{
						$sEncodedName = htmlentities($sDatabaseName, ENT_QUOTES, 'UTF-8');
						$sSelected = ($sDatabaseName == $sDBName) ? ' selected ' : '';
						$sDBNameInput .= '<option value="'.$sEncodedName.'"'.$sSelected.'>'.$sEncodedName.'</option>';
					}
				}
				$sDBNameInput .= '</select>';
			}
			$oPage->add_ready_script('$("#db_name_container").html("'.addslashes($sDBNameInput).'");');
			$oPage->add_ready_script('$("#db_name").bind("click keyup change", function() { $("#existing_db").attr("checked", "checked"); WizardUpdateButtons(); });');
			
		}
		$oPage->add_ready_script('WizardUpdateButtons();');
	}
	
	/**
	 * Helper function to get the available languages from the given directory
	 * @param $sDir Path to the dictionary
	 * @return an array of language code => description
	 */    
	static public function GetAvailableLanguages($sDir)
	{
		require_once(APPROOT.'/core/coreexception.class.inc.php');
		require_once(APPROOT.'/core/dict.class.inc.php');
	
		$aFiles = scandir($sDir);
		foreach($aFiles as $sFile)
		{
			if ($sFile == '.' || $sFile == '..' || $sFile == '.svn')
			{
				// Skip
				continue;
			}
	
			$sFilePath = $sDir.'/'.$sFile;
			if (is_file($sFilePath) && preg_match('/^.+\.dict.*\.php$/i', $sFilePath, $aMatches))
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
			$sSelected = ($sCode == $sDefaultLanguageCode) ? ' selected ' : '';
			$sHtml .= '<option value="'.$sCode.'"'.$sSelected.'>'.htmlentities($aInfo['description'], ENT_QUOTES, 'UTF-8').' ('.htmlentities($aInfo['localized_description'], ENT_QUOTES, 'UTF-8').')</option>';
		}
		$sHtml .= '</select></td></tr>';
		
		return $sHtml;
	}
	
	public static function AnalyzeInstallation($oWizard)
	{
		require_once(APPROOT.'/setup/moduleinstaller.class.inc.php');
		$oConfig = new Config();
		
		$aParamValues = array(
			'db_server' => $oWizard->GetParameter('db_server', ''),
			'db_user' => $oWizard->GetParameter('db_user', ''),
			'db_pwd' => $oWizard->GetParameter('db_pwd', ''),
			'db_name' => $oWizard->GetParameter('db_name', ''),
			'db_prefix' => $oWizard->GetParameter('db_prefix', ''),
			'source_dir' => APPROOT.'datamodel',
		);
		$oConfig->UpdateFromParams($aParamValues, 'datamodel');

		$oProductionEnv = new RunTimeEnvironment();
		$aAvailableModules = $oProductionEnv->AnalyzeInstallation($oConfig, 'datamodel');

		return $aAvailableModules;
	}

	/**
	 * Checks if the content of a directory matches the given manifest
	 * @param string $sBaseDir Path to the root directory of iTop
	 * @param string $sSourceDir Relative path to the directory to check under $sBaseDir
	 * @param Array $aDOMManifest Array of array('path' => relative_path 'size'=> iSize, 'md5' => sHexMD5)
	 * @param Hash $aResult Used for recursion 
	 * @return hash Hash array ('added' => array(), 'removed' => array(), 'modified' => array()) 
	 */
	public static function CheckDirAgainstManifest($sBaseDir, $sSourceDir, $aManifest, $aExcludeNames = array('.svn'), $aResult = null)
	{
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

		// Read the content of the directory
		foreach(glob($sBaseDir.'/'.$sSourceDir .'/*') as $sFilePath)
		{
			$sFile = basename($sFilePath);
			
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
		
		$aResults = self::CheckDirAgainstManifest($sBaseDir, 'modules', $aManifest);
		
//		echo "<pre>Comparison of ".dirname($sBaseDir)."/modules:\n".print_r($aResults, true)."</pre>";
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
			$aTmp = self::CheckDirAgainstManifest($sBaseDir, 'portal', $aManifest);
			$aResults['added'] = array_merge($aResults['added'], $aTmp['added']);
			$aResults['modified'] = array_merge($aResults['modified'], $aTmp['modified']);
			$aResults['removed'] = array_merge($aResults['removed'], $aTmp['removed']);
		}
		
//		echo "<pre>Comparison of ".dirname($sBaseDir)."/portal:\n".print_r($aResults, true)."</pre>";
		return $aResults;
	}
	
}