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
	static function CheckPHPVersion(SetupPage $oP)
	{
		$aResult = array();
		$bResult = true;
		$aErrors = array();
		$aWarnings = array();
		$aOk = array();
		
		$oP->log('Info - CheckPHPVersion');
		if (version_compare(phpversion(), self::PHP_MIN_VERSION, '>='))
		{
			$aResult[] = new CheckResult(CheckResult::INFO, "The current PHP Version (".phpversion().") is greater than the minimum required version (".self::PHP_MIN_VERSION.")");
		}
		else
		{
			$aResult[] = new CheckResult(CheckResult::ERROR, "Error: The current PHP Version (".phpversion().") is lower than the minimum required version (".self::PHP_MIN_VERSION.")");
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
			$oP->log("Info - php.ini file(s): '$sPhpIniFile'");
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
				$oP->log("Info - Temporary directory for files upload ($sUploadTmpDir) is writable.");
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
			$aResult[] = new CheckResult(CheckResult::WARNING, "post_max_size (".ini_get('post_max_size').") must be bigger than upload_max_filesize (".ini_get('upload_max_filesize')."). You may want to check the PHP configuration file(s): '$sPhpIniFile'. Be aware that this setting can also be overridden in the apache configuration.");
		}
	
	
		$oP->log("Info - upload_max_filesize: ".ini_get('upload_max_filesize'));
		$oP->log("Info - post_max_size: ".ini_get('post_max_size'));
		$oP->log("Info - max_file_uploads: ".ini_get('max_file_uploads'));
	
		// Check some more ini settings here, needed for file upload
		if (function_exists('get_magic_quotes_gpc'))
		{
		  	if (@get_magic_quotes_gpc())
		  	{
				$aResult[] = new CheckResult(CheckResult::ERROR, "'magic_quotes_gpc' is set to On. Please turn it Off before continuing. You may want to check the PHP configuration file(s): '$sPhpIniFile'. Be aware that this setting can also be overridden in the apache configuration.");
			}
		}
		if (function_exists('magic_quotes_runtime'))
		{
		  	if (@magic_quotes_runtime())
		  	{
				$aResult[] = new CheckResult(CheckResult::ERROR, "'magic_quotes_runtime' is set to On. Please turn it Off before continuing. You may want to check the PHP configuration file(s): '$sPhpIniFile'. Be aware that this setting can also be overridden in the apache configuration.");
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
				$oP->log_info("memory_limit is $iMemoryLimit, ok.");		
			}
		}
		
		// Special case for APC
		if (extension_loaded('apc'))
		{
			$sAPCVersion = phpversion('apc');
			$aResult[] = new CheckResult(CheckResult::INFO, "APC detected (version $sAPCVersion). The APC cache will be used to speed-up the application.");
		}
	
		// Special case Suhosin extension
		if (extension_loaded('suhosin'))
		{
			$sSuhosinVersion = phpversion('suhosin');
			$aOk[] = "Suhosin extension detected (version $sSuhosinVersion).";
			
			$iGetMaxValueLength = ini_get('suhosin.get.max_value_length');
			if ($iGetMaxValueLength < self::SUHOSIN_GET_MAX_VALUE_LENGTH)
			{	
				$aResult[] = new CheckResult(CheckResult::INFO,  "suhosin.get.max_value_length ($iGetMaxValueLength) is too small, the minimum value to run the application is ".self::SUHOSIN_GET_MAX_VALUE_LENGTH.". This value is set by the PHP configuration file(s): '$sPhpIniFile'. Be aware that this setting can also be overridden in the apache configuration.");
			}
			else
			{
				$oP->log_info("suhosin.get.max_value_length = $iGetMaxValueLength, ok.");		
			}
		}

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
}