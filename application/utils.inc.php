<?php
// Copyright (C) 2010-2013 Combodo SARL
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
 * Static class utils
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once(APPROOT.'/core/config.class.inc.php');
require_once(APPROOT.'/application/transaction.class.inc.php');

define('ITOP_CONFIG_FILE', 'config-itop.php');
define('ITOP_DEFAULT_CONFIG_FILE', APPCONF.ITOP_DEFAULT_ENV.'/'.ITOP_CONFIG_FILE);

define('SERVER_NAME_PLACEHOLDER', '$SERVER_NAME$');

class FileUploadException extends Exception
{
}


/**
 * Helper functions to interact with forms: read parameters, upload files...
 * @package     iTop
 */
class utils
{
	private static $oConfig = null;
	private static $m_bCASClient = false;

	// Parameters loaded from a file, parameters of the page/command line still have precedence
	private static $m_aParamsFromFile = null;
	private static $m_aParamSource = array();

	protected static function LoadParamFile($sParamFile)
	{
		if (!file_exists($sParamFile))
		{
			throw new Exception("Could not find the parameter file: '$sParamFile'");
		}
		if (!is_readable($sParamFile))
		{
			throw new Exception("Could not load parameter file: '$sParamFile'");
		}
		$sParams = file_get_contents($sParamFile);

		if (is_null(self::$m_aParamsFromFile))
		{
			self::$m_aParamsFromFile = array();
		}

		$aParamLines = explode("\n", $sParams);
		foreach ($aParamLines as $sLine)
		{
			$sLine = trim($sLine);

			// Ignore the line after a '#'
			if (($iCommentPos = strpos($sLine, '#')) !== false)
			{
				$sLine = substr($sLine, 0, $iCommentPos);
				$sLine = trim($sLine);
			}

			// Note: the line is supposed to be already trimmed
			if (preg_match('/^(\S*)\s*=(.*)$/', $sLine, $aMatches))
			{
				$sParam = $aMatches[1];
				$value = trim($aMatches[2]);
				self::$m_aParamsFromFile[$sParam] = $value;
				self::$m_aParamSource[$sParam] = $sParamFile;
			}
		}
	}

	public static function UseParamFile($sParamFileArgName = 'param_file', $bAllowCLI = true)
	{
		$sFileSpec = self::ReadParam($sParamFileArgName, '', $bAllowCLI, 'raw_data');
		foreach(explode(',', $sFileSpec) as $sFile)
		{
			$sFile = trim($sFile);
			if (!empty($sFile))
			{
				self::LoadParamFile($sFile);
			}
		}
	}

	/**
	 * Return the source file from which the parameter has been found,
	 * usefull when it comes to pass user credential to a process executed
	 * in the background	 
	 * @param $sName Parameter name
	 * @return The file name if any, or null
	 */
	public static function GetParamSourceFile($sName)
	{
		if (array_key_exists($sName, self::$m_aParamSource))
		{
			return self::$m_aParamSource[$sName];
		}
		else
		{
			return null;
		}
	}

	public static function IsModeCLI()
	{
		$sSAPIName = php_sapi_name();
		$sCleanName = strtolower(trim($sSAPIName));
		if ($sCleanName == 'cli')
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	public static function ReadParam($sName, $defaultValue = "", $bAllowCLI = false, $sSanitizationFilter = 'parameter')
	{
		global $argv;
		$retValue = $defaultValue;

		if (!is_null(self::$m_aParamsFromFile))
		{
			if (isset(self::$m_aParamsFromFile[$sName]))
			{
				$retValue = self::$m_aParamsFromFile[$sName];
			}
		}

		if (isset($_REQUEST[$sName]))
		{
			$retValue = $_REQUEST[$sName];
		}
		elseif ($bAllowCLI && isset($argv))
		{
			foreach($argv as $iArg => $sArg)
			{
				if (preg_match('/^--'.$sName.'=(.*)$/', $sArg, $aMatches))
				{
					$retValue = $aMatches[1];
				}
			}
		}
		return self::Sanitize($retValue, $defaultValue, $sSanitizationFilter);
	}
	
	public static function ReadPostedParam($sName, $defaultValue = '', $sSanitizationFilter = 'parameter')
	{
		$retValue = isset($_POST[$sName]) ? $_POST[$sName] : $defaultValue;
		return self::Sanitize($retValue, $defaultValue, $sSanitizationFilter);
	}
	
	public static function Sanitize($value, $defaultValue, $sSanitizationFilter)
	{
		if ($value === $defaultValue)
		{
			// Preserve the real default value (can be used to detect missing mandatory parameters)
			$retValue = $value;
		}
		else
		{
			$retValue = self::Sanitize_Internal($value, $sSanitizationFilter);
			if ($retValue === false)
			{
				$retValue = $defaultValue;
			}
		}
		return $retValue;		
	}
	
	protected static function Sanitize_Internal($value, $sSanitizationFilter)
	{
		switch($sSanitizationFilter)
		{
			case 'integer':
			$retValue = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
			break;
			
			case 'class':
			$retValue = $value;
			if (!MetaModel::IsValidClass($value))
			{
				$retValue = false;
			}
			break;

			case 'string':
			$retValue = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
			break;
			
			case 'context_param':
			case 'parameter':
			case 'field_name':
			if (is_array($value))
			{
				$retValue = array();
				foreach($value as $key => $val)
				{
					$retValue[$key] = self::Sanitize_Internal($val, $sSanitizationFilter); // recursively check arrays
					if ($retValue[$key] === false)
					{
						$retValue = false;
						break;
					}
				}
			}
			else
			{
				switch($sSanitizationFilter)
				{
					case 'parameter':
					$retValue = filter_var($value, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>'/^[ A-Za-z0-9_=-]*$/'))); // the '=' equal character is used in serialized filters
					break;
					
					case 'field_name':
					$retValue = filter_var($value, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>'/^[A-Za-z0-9_]+(->[A-Za-z0-9_]+)*$/'))); // att_code or att_code->name or AttCode->Name or AttCode->Key2->Name
					break;
					
					case 'context_param':
					$retValue = filter_var($value, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>'/^[ A-Za-z0-9_=%:+-]*$/')));
					break;
						
				}
			}
			break;
						
			default:
			case 'raw_data':
			$retValue = $value;
			// Do nothing
		}
		return $retValue;		
	}
	
	/**
	 * Reads an uploaded file and turns it into an ormDocument object - Triggers an exception in case of error
	 * @param string $sName Name of the input used from uploading the file	 
	 * @param string $sIndex If Name is an array of posted files, then the index must be used to point out the file	 
	 * @return ormDocument The uploaded file (can be 'empty' if nothing was uploaded)
	 */	 	 
	public static function  ReadPostedDocument($sName, $sIndex = null)
	{
		$oDocument = new ormDocument(); // an empty document
		if(isset($_FILES[$sName]))
		{
			$aFileInfo = $_FILES[$sName];

			$sError = is_null($sIndex) ? $aFileInfo['error'] : $aFileInfo['error'][$sIndex];
			switch($sError)
			{
				case UPLOAD_ERR_OK:
				$sTmpName = is_null($sIndex) ? $aFileInfo['tmp_name'] : $aFileInfo['tmp_name'][$sIndex];
				$sMimeType = is_null($sIndex) ? $aFileInfo['type'] : $aFileInfo['type'][$sIndex];
				$sName = is_null($sIndex) ? $aFileInfo['name'] : $aFileInfo['name'][$sIndex];

				$doc_content = file_get_contents($sTmpName);
				if (function_exists('finfo_file'))
				{
					// as of PHP 5.3 the fileinfo extension is bundled within PHP
					// in which case we don't trust the mime type provided by the browser
					$rInfo = @finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
					if ($rInfo !== false)
					{
					   $sType = @finfo_file($rInfo, $file);
					   if ( ($sType !== false)
					        && is_string($sType)
					        && (strlen($sType)>0))
					   {
					        $sMimeType = $sType;
					   }
					}
					@finfo_close($rInfo);
				}
				$oDocument = new ormDocument($doc_content, $sMimeType, $sName);
				break;
				
				case UPLOAD_ERR_NO_FILE:
				// no file to load, it's a normal case, just return an empty document
				break;
				
				case UPLOAD_ERR_FORM_SIZE:
				case UPLOAD_ERR_INI_SIZE:
				throw new FileUploadException(Dict::Format('UI:Error:UploadedFileTooBig', ini_get('upload_max_filesize')));
				break;

				case UPLOAD_ERR_PARTIAL:
				throw new FileUploadException(Dict::S('UI:Error:UploadedFileTruncated.'));
				break;
				
				case UPLOAD_ERR_NO_TMP_DIR:
				throw new FileUploadException(Dict::S('UI:Error:NoTmpDir'));
				break;

				case UPLOAD_ERR_CANT_WRITE:
				throw new FileUploadException(Dict::Format('UI:Error:CannotWriteToTmp_Dir', ini_get('upload_tmp_dir')));
				break;

				case UPLOAD_ERR_EXTENSION:
				$sName = is_null($sIndex) ? $aFileInfo['name'] : $aFileInfo['name'][$sIndex];
				throw new FileUploadException(Dict::Format('UI:Error:UploadStoppedByExtension_FileName', $sName));
				break;
				
				default:
				throw new FileUploadException(Dict::Format('UI:Error:UploadFailedUnknownCause_Code', $sError));
				break;

			}
		}
		return $oDocument;
	}
	
	/**
	 * Interprets the results posted by a normal or paginated list (in multiple selection mode)
	 * @param $oFullSetFilter DBObjectSearch The criteria defining the whole sets of objects being selected
	 * @return Array An arry of object IDs corresponding to the objects selected in the set
	 */	
	public static function ReadMultipleSelection($oFullSetFilter)
	{
		$aSelectedObj = utils::ReadParam('selectObject', array());
		$sSelectionMode = utils::ReadParam('selectionMode', '');
		if ($sSelectionMode != '')
		{
			// Paginated selection
			$aExceptions = utils::ReadParam('storedSelection', array());
			if ($sSelectionMode == 'positive')
			{
				// Only the explicitely listed items are selected
				$aSelectedObj = $aExceptions;
			}
			else
			{
				// All items of the set are selected, except the one explicitely listed
				$aSelectedObj = array();
				$oFullSet = new DBObjectSet($oFullSetFilter);
				$sClassAlias = $oFullSetFilter->GetClassAlias();
				$oFullSet->OptimizeColumnLoad(array($sClassAlias => array('friendlyname'))); // We really need only the IDs but it does not work since id is not a real field
				while($oObj = $oFullSet->Fetch())
				{
					if (!in_array($oObj->GetKey(), $aExceptions))
					{
						$aSelectedObj[] = $oObj->GetKey();
					}
				}
			}
		}
		return $aSelectedObj;
	}

	public static function GetNewTransactionId()
	{
		return privUITransaction::GetNewTransactionId();
	}
	
	public static function IsTransactionValid($sId, $bRemoveTransaction = true)
	{
		return privUITransaction::IsTransactionValid($sId, $bRemoveTransaction);
	}
	
	public static function RemoveTransaction($sId)
	{
		return privUITransaction::RemoveTransaction($sId);
	}
	
	public static function ReadFromFile($sFileName)
	{
		if (!file_exists($sFileName)) return false;
		return file_get_contents($sFileName);
	}

	/**
	 * Helper function to convert a value expressed in a 'user friendly format'
	 * as in php.ini, e.g. 256k, 2M, 1G etc. Into a number of bytes
	 * @param mixed $value The value as read from php.ini
	 * @return number
	 */	 	  
	public static function ConvertToBytes( $value )
	{
		$iReturn = $value;
	    if ( !is_numeric( $value ) )
		{
	        $iLength = strlen( $value );
	        $iReturn = substr( $value, 0, $iLength - 1 );
	        $sUnit = strtoupper( substr( $value, $iLength - 1 ) );
	        switch ( $sUnit )
			{
	            case 'G':
	                $iReturn *= 1024;
	            case 'M':
	                $iReturn *= 1024;
	            case 'K':
	                $iReturn *= 1024;
	        }
	    }
        return $iReturn;
    }

	/**
	 * Helper function to convert a string to a date, given a format specification. It replaces strtotime which does not allow for specifying a date in a french format (for instance)
	 * Example: StringToTime('01/05/11 12:03:45', '%d/%m/%y %H:%i:%s')
	 * @param string $sDate
	 * @param string $sFormat
	 * @return timestamp or false if the input format is not correct
	 */	 	  
	public static function StringToTime($sDate, $sFormat)
	{
	   // Source: http://php.net/manual/fr/function.strftime.php
		// (alternative: http://www.php.net/manual/fr/datetime.formats.date.php)
		static $aDateTokens = null;
		static $aDateRegexps = null;
		if (is_null($aDateTokens))
		{
		   $aSpec = array(
				'%d' =>'(?<day>[0-9]{2})',
				'%m' => '(?<month>[0-9]{2})',
				'%y' => '(?<year>[0-9]{2})',
				'%Y' => '(?<year>[0-9]{4})',
				'%H' => '(?<hour>[0-2][0-9])',
				'%i' => '(?<minute>[0-5][0-9])',
				'%s' => '(?<second>[0-5][0-9])',
				);
			$aDateTokens = array_keys($aSpec);
			$aDateRegexps = array_values($aSpec);
		}
	   
	   $sDateRegexp = str_replace($aDateTokens, $aDateRegexps, $sFormat);
	   
	   if (preg_match('!^(?<head>)'.$sDateRegexp.'(?<tail>)$!', $sDate, $aMatches))
	   {
			$sYear = isset($aMatches['year']) ? $aMatches['year'] : 0;
			$sMonth = isset($aMatches['month']) ? $aMatches['month'] : 1;
			$sDay = isset($aMatches['day']) ? $aMatches['day'] : 1;
			$sHour = isset($aMatches['hour']) ? $aMatches['hour'] : 0;
			$sMinute = isset($aMatches['minute']) ? $aMatches['minute'] : 0;
			$sSecond = isset($aMatches['second']) ? $aMatches['second'] : 0;
			return strtotime("$sYear-$sMonth-$sDay $sHour:$sMinute:$sSecond");
		}
	   else
	   {
	   	return false;
	   }
	   // http://www.spaweditor.com/scripts/regex/index.php
	}

	static public function GetConfig()
	{
		if (self::$oConfig == null)
		{
			$sConfigFile = self::GetConfigFilePath();
			if (file_exists($sConfigFile))
			{
				self::$oConfig = new Config($sConfigFile);
			}
			else
			{
				// When executing the setup, the config file may be still missing
				self::$oConfig = new Config();
			}
		}
		return self::$oConfig;
	}
    /**
     * Returns the absolute URL to the application root path
     * @return string The absolute URL to the application root, without the first slash
     */                   
	static public function GetAbsoluteUrlAppRoot()
	{
		$sUrl = self::GetConfig()->Get('app_root_url');
		if (strpos($sUrl, SERVER_NAME_PLACEHOLDER) > -1)
		{
			if (isset($_SERVER['SERVER_NAME']))
			{
				$sServerName = $_SERVER['SERVER_NAME'];
			}
			else
			{
				// CLI mode ?
				$sServerName = php_uname('n');
			}
			$sUrl = str_replace(SERVER_NAME_PLACEHOLDER, $sServerName, $sUrl);
		}
		return $sUrl;
	}

	static public function GetDefaultUrlAppRoot()
	{
		// Build an absolute URL to this page on this server/port
		$sServerName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '';
		$sProtocol = self::IsConnectionSecure() ? 'https' : 'http';
		$iPort = isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : 80;
		if ($sProtocol == 'http')
		{
			$sPort = ($iPort == 80) ? '' : ':'.$iPort;
		}
		else
		{
			$sPort = ($iPort == 443) ? '' : ':'.$iPort;
		}
		// $_SERVER['REQUEST_URI'] is empty when running on IIS
		// Let's use Ivan Tcholakov's fix (found on www.dokeos.com)
		if (!empty($_SERVER['REQUEST_URI']))
		{
			$sPath = $_SERVER['REQUEST_URI'];
		}
		else
		{
			$sPath = $_SERVER['SCRIPT_NAME'];
			if (!empty($_SERVER['QUERY_STRING']))
			{
				$sPath .= '?'.$_SERVER['QUERY_STRING'];
			}
			$_SERVER['REQUEST_URI'] = $sPath;
		}
		$sPath = $_SERVER['REQUEST_URI'];

		// remove all the parameters from the query string
		$iQuestionMarkPos = strpos($sPath, '?');
		if ($iQuestionMarkPos !== false)
		{
			$sPath = substr($sPath, 0, $iQuestionMarkPos);
		}
		$sAbsoluteUrl = "$sProtocol://{$sServerName}{$sPort}{$sPath}";

		$sCurrentScript = realpath($_SERVER['SCRIPT_FILENAME']);
		$sCurrentScript = str_replace('\\', '/', $sCurrentScript); // canonical path
		$sAppRoot = str_replace('\\', '/', APPROOT); // canonical path
		$sCurrentRelativePath = str_replace($sAppRoot, '', $sCurrentScript);
	
		$sAppRootPos = strpos($sAbsoluteUrl, $sCurrentRelativePath);
		if ($sAppRootPos !== false)
		{
			$sAppRootUrl = substr($sAbsoluteUrl, 0, $sAppRootPos); // remove the current page and path
		}
		else
		{
			// Second attempt without index.php at the end...
			$sCurrentRelativePath = str_replace('index.php', '', $sCurrentRelativePath);
			$sAppRootPos = strpos($sAbsoluteUrl, $sCurrentRelativePath);
			if ($sAppRootPos !== false)
			{
				$sAppRootUrl = substr($sAbsoluteUrl, 0, $sAppRootPos); // remove the current page and path
			}
			else
			{
				// No luck...
				throw new Exception("Failed to determine application root path $sAbsoluteUrl ($sCurrentRelativePath) APPROOT:'$sAppRoot'");
			}			
		}
		return $sAppRootUrl;
	}

	/**
	 * Helper to handle the variety of HTTP servers
	 * See #286 (fixed in [896]), and #634 (this fix)
	 * 	 
	 * Though the official specs says 'a non empty string', some servers like IIS do set it to 'off' !
	 * nginx set it to an empty string
	 * Others might leave it unset (no array entry)	 
	 */	 	
	static public function IsConnectionSecure()
	{
		$bSecured = false;

		if (!empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off'))
		{
			$bSecured = true;
		}
		return $bSecured;
	}

	/**
	 * Tells whether or not log off operation is supported.
	 * Actually in only one case:
	 * 1) iTop is using an internal authentication
	 * 2) the user did not log-in using the "basic" mode (i.e basic authentication) or by passing credentials in the URL
	 * @return boolean True if logoff is supported, false otherwise
	 */
	static function CanLogOff()
	{
		$bResult = false;
		if(isset($_SESSION['login_mode']))
		{
			$sLoginMode = $_SESSION['login_mode'];
			switch($sLoginMode)
			{
				case 'external':
				$bResult = false;
				break;
	
				case 'form':
				case 'basic':
				case 'url':
				case 'cas':
				default:
				$bResult = true;
				
			}			
		}
		return $bResult;
	}
	
	/**
	 * Initializes the CAS client
	 */
	 static function InitCASClient()
	 {
		$sCASIncludePath =  self::GetConfig()->Get('cas_include_path');
		include_once($sCASIncludePath.'/CAS.php');
		
		$bCASDebug = self::GetConfig()->Get('cas_debug');
		if ($bCASDebug)
		{
			phpCAS::setDebug(APPROOT.'log/error.log');
		}
		
		if (!self::$m_bCASClient)
		{
			// Initialize phpCAS
			$sCASVersion = self::GetConfig()->Get('cas_version');
			$sCASHost = self::GetConfig()->Get('cas_host');
			$iCASPort = self::GetConfig()->Get('cas_port');
			$sCASContext = self::GetConfig()->Get('cas_context');
			phpCAS::client($sCASVersion, $sCASHost, $iCASPort, $sCASContext, false /* session already started */);
			self::$m_bCASClient = true;
			$sCASCACertPath = self::GetConfig()->Get('cas_server_ca_cert_path');
			if (empty($sCASCACertPath))
			{
				// If no certificate authority is provided, do not attempt to validate
				// the server's certificate
				// THIS SETTING IS NOT RECOMMENDED FOR PRODUCTION. 
				// VALIDATING THE CAS SERVER IS CRUCIAL TO THE SECURITY OF THE CAS PROTOCOL! 
				phpCAS::setNoCasServerValidation();
			}
			else
			{
				phpCAS::setCasServerCACert($sCASCACertPath);
			}			
		}
	 }
	 
	 static function DebugBacktrace($iLimit = 5)
	 {
		$aFullTrace = debug_backtrace();
		$aLightTrace = array();
		for($i=1; ($i<=$iLimit && $i < count($aFullTrace)); $i++) // Skip the last function call... which is the call to this function !
		{
			$aLightTrace[$i] = $aFullTrace[$i]['function'].'(), called from line '.$aFullTrace[$i]['line'].' in '.$aFullTrace[$i]['file'];
		}
		echo "<p><pre>".print_r($aLightTrace, true)."</pre></p>\n";
	 }

	/**
	 * Execute the given iTop PHP script, passing it the current credentials
	 * Only CLI mode is supported, because of the need to hand the credentials over to the next process
	 * Throws an exception if the execution fails or could not be attempted (config issue)
	 * @param string $sScript Name and relative path to the file (relative to the iTop root dir)
	 * @param hash $aArguments Associative array of 'arg' => 'value'
	 * @return array(iCode, array(output lines))
	 */	 	  
	/**
	 */
	static function ExecITopScript($sScriptName, $aArguments)
	{
		$aDisabled = explode(', ', ini_get('disable_functions'));
		if (in_array('exec', $aDisabled))
		{
			throw new Exception("The PHP exec() function has been disabled on this server");
		}

		$sPHPExec = trim(self::GetConfig()->Get('php_path'));
		if (strlen($sPHPExec) == 0)
		{
			throw new Exception("The path to php must not be empty. Please set a value for 'php_path' in your configuration file.");
		}

		$sAuthUser = self::ReadParam('auth_user', '', 'raw_data');
		$sAuthPwd = self::ReadParam('auth_pwd', '', 'raw_data');
		$sParamFile = self::GetParamSourceFile('auth_user');
		if (is_null($sParamFile))
		{
			$aArguments['auth_user'] = $sAuthUser;
			$aArguments['auth_pwd'] = $sAuthPwd;
		}
		else
		{
			$aArguments['param_file'] = $sParamFile;
		}
		
		$aArgs = array();
		foreach($aArguments as $sName => $value)
		{
			// Note: See comment from the 23-Apr-2004 03:30 in the PHP documentation
			//    It suggests to rely on pctnl_* function instead of using escapeshellargs
			$aArgs[] = "--$sName=".escapeshellarg($value);
		}
		$sArgs = implode(' ', $aArgs);
		
		$sScript = realpath(APPROOT.$sScriptName);
		if (!file_exists($sScript))
		{
			throw new Exception("Could not find the script file '$sScriptName' from the directory '".APPROOT."'");
		}

		$sCommand = '"'.$sPHPExec.'" '.escapeshellarg($sScript).' -- '.$sArgs;

		if (version_compare(phpversion(), '5.3.0', '<'))
		{
			if (substr(PHP_OS,0,3) == 'WIN')
			{
				// Under Windows, and for PHP 5.2.x, the whole command has to be quoted
				// Cf PHP doc: http://php.net/manual/fr/function.exec.php, comment from the 27-Dec-2010
				$sCommand = '"'.$sCommand.'"';
			}
		}

		$sLastLine = exec($sCommand, $aOutput, $iRes);
		if ($iRes == 1)
		{
			throw new Exception(Dict::S('Core:ExecProcess:Code1')." - ".$sCommand);
		}
		elseif ($iRes == 255)
		{
			$sErrors = implode("\n", $aOutput);
			throw new Exception(Dict::S('Core:ExecProcess:Code255')." - ".$sCommand.":\n".$sErrors);
		}

		//$aOutput[] = $sCommand;
		return array($iRes, $aOutput);
	}

	/**
	 * Get the current environment
	 */
	public static function GetCurrentEnvironment()
	{
		if (isset($_SESSION['itop_env']))
		{
			return $_SESSION['itop_env'];
		}
		else
		{
			return ITOP_DEFAULT_ENV;
		}
	}
	
	/**
	 * Merge standard menu items with plugin provided menus items
	 */
	public static function GetPopupMenuItems($oPage, $iMenuId, $param, &$aActions, $sTableId = null, $sDataTableId = null)
	{
		// 1st - add standard built-in menu items
		// 
		switch($iMenuId)
		{
			case iPopupMenuExtension::MENU_OBJLIST_TOOLKIT:
			// $param is a DBObjectSet
			$oAppContext = new ApplicationContext();
			$sContext = $oAppContext->GetForLink();
			$sDataTableId = is_null($sDataTableId) ? '' : $sDataTableId;
			$sUIPage = cmdbAbstractObject::ComputeStandardUIPage($param->GetFilter()->GetClass());
			$sOQL = addslashes($param->GetFilter()->ToOQL(true));
			$sFilter = urlencode($param->GetFilter()->serialize());
			$sUrl = utils::GetAbsoluteUrlAppRoot()."pages/$sUIPage?operation=search&filter=".$sFilter."&{$sContext}";
			$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/xlsx-export.js');
			$sXlsxFilter = $param->GetFilter()->serialize();
			$sXlsxJSFilter = addslashes($sXlsxFilter);
			
			$aResult = array(
				new SeparatorPopupMenuItem(),
				// Static menus: Email this page, CSV Export & Add to Dashboard
				new URLPopupMenuItem('UI:Menu:EMail', Dict::S('UI:Menu:EMail'), "mailto:?body=".urlencode($sUrl).' '), // Add an extra space to make it work in Outlook
				new URLPopupMenuItem('UI:Menu:CSVExport', Dict::S('UI:Menu:CSVExport'), $sUrl."&format=csv"),
				new JSPopupMenuItem('xlsx-export', Dict::S('ExcelExporter:ExportMenu'), "XlsxExportDialog('$sXlsxJSFilter');", array()),
				new JSPopupMenuItem('UI:Menu:AddToDashboard', Dict::S('UI:Menu:AddToDashboard'), "DashletCreationDlg('$sOQL')"),
				new JSPopupMenuItem('UI:Menu:ShortcutList', Dict::S('UI:Menu:ShortcutList'), "ShortcutListDlg('$sOQL', '$sDataTableId', '$sContext')"),
			);
			break;

			case iPopupMenuExtension::MENU_OBJDETAILS_ACTIONS:
			// $param is a DBObject
			$oObj = $param;
			$oFilter = DBobjectSearch::FromOQL("SELECT ".get_class($oObj)." WHERE id=".$oObj->GetKey());
			$sFilter = $oFilter->serialize();
			$sUrl = ApplicationContext::MakeObjectUrl(get_class($oObj), $oObj->GetKey());
			$sUIPage = cmdbAbstractObject::ComputeStandardUIPage(get_class($oObj));
			$oAppContext = new ApplicationContext();
			$sContext = $oAppContext->GetForLink();
			$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/xlsx-export.js');
			$sXlsxJSFilter = addslashes($sFilter);
			$aResult = array(
				new SeparatorPopupMenuItem(),
				// Static menus: Email this page & CSV Export
				new URLPopupMenuItem('UI:Menu:EMail', Dict::S('UI:Menu:EMail'), "mailto:?subject=".urlencode($oObj->GetRawName())."&body=".urlencode($sUrl).' '), // Add an extra space to make it work in Outlook
				new URLPopupMenuItem('UI:Menu:CSVExport', Dict::S('UI:Menu:CSVExport'), utils::GetAbsoluteUrlAppRoot()."pages/$sUIPage?operation=search&filter=".urlencode($sFilter)."&format=csv&{$sContext}"),
				new JSPopupMenuItem('xlsx-export', Dict::S('ExcelExporter:ExportMenu'), "XlsxExportDialog('$sXlsxJSFilter');", array()),
			);
			break;

			case iPopupMenuExtension::MENU_DASHBOARD_ACTIONS:
			// $param is a Dashboard
			$oAppContext = new ApplicationContext();
			$aParams = $oAppContext->GetAsHash();
			$sMenuId = ApplicationMenu::GetActiveNodeId();
			$sDlgTitle = addslashes(Dict::S('UI:ImportDashboardTitle'));
			$sDlgText = addslashes(Dict::S('UI:ImportDashboardText'));
			$sCloseBtn = addslashes(Dict::S('UI:Button:Cancel'));
			$aResult = array(
				new SeparatorPopupMenuItem(),
				new URLPopupMenuItem('UI:ExportDashboard', Dict::S('UI:ExportDashBoard'), utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php?operation=export_dashboard&id='.$sMenuId),
				new JSPopupMenuItem('UI:ImportDashboard', Dict::S('UI:ImportDashBoard'), "UploadDashboard({dashboard_id: '$sMenuId', title: '$sDlgTitle', text: '$sDlgText', close_btn: '$sCloseBtn' })"),
			);
			break;

			default:
			// Unknown type of menu, do nothing
			$aResult = array();
		}
		foreach($aResult as $oMenuItem)
		{
			$aActions[$oMenuItem->GetUID()] = $oMenuItem->GetMenuItem();
		}

		// Invoke the plugins
		//
		foreach (MetaModel::EnumPlugins('iPopupMenuExtension') as $oExtensionInstance)
		{
			if (is_object($param) && !($param instanceof DBObject))
			{
				$tmpParam = clone $param; // In case the parameter is an DBObjectSet, clone it to prevent alterations
			}
			else
			{
				$tmpParam = $param;
			}
			foreach($oExtensionInstance->EnumItems($iMenuId, $tmpParam) as $oMenuItem)
			{
				if (is_object($oMenuItem))
				{
					$aActions[$oMenuItem->GetUID()] = $oMenuItem->GetMenuItem();
					
					foreach($oMenuItem->GetLinkedScripts() as $sLinkedScript)
					{
						$oPage->add_linked_script($sLinkedScript);
					}
				}
			}
		}
	}
	/**
	 * Get target configuration file name (including full path)
	 */
	public static function GetConfigFilePath($sEnvironment = null)
	{
		if (is_null($sEnvironment))
		{
			$sEnvironment = self::GetCurrentEnvironment();
		}
		return APPCONF.$sEnvironment.'/'.ITOP_CONFIG_FILE;
	}

    /**
     * Returns the absolute URL to the modules root path
     * @return string ...
     */                   
	static public function GetAbsoluteUrlModulesRoot()
	{
		$sUrl = self::GetAbsoluteUrlAppRoot().'env-'.self::GetCurrentEnvironment().'/';
		return $sUrl;
	}

    /**
     * Returns the URL to a page that will execute the requested module page
     *      
     * To be compatible with this mechanism, the called page must include approot
     * with an absolute path OR not include it at all (losing the direct access to the page)
     * if (!defined('__DIR__')) define('__DIR__', dirname(__FILE__));
     * require_once(__DIR__.'/../../approot.inc.php');
     *      
     * @return string ...
     */                   
	static public function GetAbsoluteUrlModulePage($sModule, $sPage, $aArguments = array(), $sEnvironment = null)
	{
		$sEnvironment = is_null($sEnvironment) ? self::GetCurrentEnvironment() : $sEnvironment;
		$aArgs = array();
		$aArgs[] = 'exec_module='.$sModule;
		$aArgs[] = 'exec_page='.$sPage;
		$aArgs[] = 'exec_env='.$sEnvironment;
		foreach($aArguments as $sName => $sValue)
		{
			if (($sName == 'exec_module')||($sName == 'exec_page')||($sName == 'exec_env'))
			{
				throw new Exception("Module page: $sName is a reserved page argument name");
			}
			$aArgs[] = $sName.'='.urlencode($sValue);
		}
		$sArgs = implode('&', $aArgs);
		return self::GetAbsoluteUrlAppRoot().'pages/exec.php?'.$sArgs;
	}

	/**
	 * Returns a name unique amongst the given list
	 * @param string $sProposed The default value
	 * @param array  $aExisting An array of existing values (strings)	 	 
	 */
	static public function MakeUniqueName($sProposed, $aExisting)
	{
		if (in_array($sProposed, $aExisting))
		{
			$i = 1;
			while (in_array($sProposed.$i, $aExisting) && ($i < 50))
			{
				$i++;
			}
			return $sProposed.$i;
		}
		else
		{
			return $sProposed;
		}
	}
	
	/**
	 * Some characters cause troubles with jQuery when used inside DOM IDs, so let's replace them by the safe _ (underscore)
	 * @param string $sId The ID to sanitize
	 * @return string The sanitized ID
	 */
	static public function GetSafeId($sId)
	{
		return str_replace(array(':', '[', ']', '+', '-'), '_', $sId);
	}
	
	/**
	 * Helper to execute an HTTP POST request
	 * Source: http://netevil.org/blog/2006/nov/http-post-from-php-without-curl
	 *         originaly named after do_post_request
	 * Does not require cUrl but requires openssl for performing https POSTs.
	 * 
	 * @param string $sUrl The URL to POST the data to
	 * @param hash $aData The data to POST as an array('param_name' => value)
	 * @param string $sOptionnalHeaders Additional HTTP headers as a string with newlines between headers
	 * @param hash	$aResponseHeaders An array to be filled with reponse headers: WARNING: the actual content of the array depends on the library used: cURL or fopen, test with both !! See: http://fr.php.net/manual/en/function.curl-getinfo.php
	 * @return string The result of the POST request
	 * @throws Exception
	 */ 
	static public function DoPostRequest($sUrl, $aData, $sOptionnalHeaders = null, &$aResponseHeaders = null)
	{
		// $sOptionnalHeaders is a string containing additional HTTP headers that you would like to send in your request.
	
		if (function_exists('curl_init'))
		{
			// If cURL is available, let's use it, since it provides a greater control over the various HTTP/SSL options
			// For instance fopen does not allow to work around the bug: http://stackoverflow.com/questions/18191672/php-curl-ssl-routinesssl23-get-server-helloreason1112
			// by setting the SSLVERSION to 3 as done below.
			$aHeaders = explode("\n", $sOptionnalHeaders);
			$aHTTPHeaders = array();
			foreach($aHeaders as $sHeaderString)
			{
				if(preg_match('/^([^:]): (.+)$/', $sHeaderString, $aMatches))
				{
					$aHTTPHeaders[$aMatches[1]] = $aMatches[2];
				}
			}
			$aOptions = array(
				CURLOPT_RETURNTRANSFER	=> true,     // return the content of the request
				CURLOPT_HEADER			=> false,    // don't return the headers in the output
				CURLOPT_FOLLOWLOCATION	=> true,     // follow redirects
				CURLOPT_ENCODING		=> "",       // handle all encodings
				CURLOPT_USERAGENT		=> "spider", // who am i
				CURLOPT_AUTOREFERER		=> true,     // set referer on redirect
				CURLOPT_CONNECTTIMEOUT	=> 120,      // timeout on connect
				CURLOPT_TIMEOUT			=> 120,      // timeout on response
				CURLOPT_MAXREDIRS		=> 10,       // stop after 10 redirects
				CURLOPT_SSL_VERIFYPEER	=> false,    // Disabled SSL Cert checks
				CURLOPT_SSLVERSION		=> 3,		 // MUST to prevent a strange SSL error: http://stackoverflow.com/questions/18191672/php-curl-ssl-routinesssl23-get-server-helloreason1112
				CURLOPT_POST			=> count($aData),
				CURLOPT_POSTFIELDS		=> http_build_query($aData),
				CURLOPT_HTTPHEADER		=> $aHTTPHeaders,
			);
			
			$ch = curl_init($sUrl);
			curl_setopt_array($ch, $aOptions);
			$response = curl_exec($ch);
			$iErr = curl_errno($ch);
			$sErrMsg = curl_error( $ch );
			$aHeaders = curl_getinfo( $ch );
			if ($iErr !== 0)
			{
				throw new Exception("Problem opening URL: $sUrl, $sErrMsg");
			}
			if (is_array($aResponseHeaders))
			{
				$aHeaders = curl_getinfo($ch);
				foreach($aHeaders as $sCode => $sValue)
				{
					$sName = str_replace(' ' , '-', ucwords(str_replace('_', ' ', $sCode))); // Transform "content_type" into "Content-Type"
					$aResponseHeaders[$sName] = $sValue;
				}
			}
			curl_close( $ch );
		}
		else
		{
			// cURL is not available let's try with streams and fopen...
			
			$sData = http_build_query($aData);
			$aParams = array('http' => array(
									'method' => 'POST',
									'content' => $sData,
									'header'=> "Content-type: application/x-www-form-urlencoded\r\nContent-Length: ".strlen($sData)."\r\n",
									));
			if ($sOptionnalHeaders !== null)
			{
				$aParams['http']['header'] .= $sOptionnalHeaders;
			}
			$ctx = stream_context_create($aParams);
		
			$fp = @fopen($sUrl, 'rb', false, $ctx);
			if (!$fp)
			{
				global $php_errormsg;
				if (isset($php_errormsg))
				{
					throw new Exception("Wrong URL: $sUrl, $php_errormsg");
				}
				elseif ((strtolower(substr($sUrl, 0, 5)) == 'https') && !extension_loaded('openssl'))
				{
					throw new Exception("Cannot connect to $sUrl: missing module 'openssl'");
				}
				else
				{
					throw new Exception("Wrong URL: $sUrl");
				}
			}
			$response = @stream_get_contents($fp);
			if ($response === false)
			{
				throw new Exception("Problem reading data from $sUrl, $php_errormsg");
			}
			if (is_array($aResponseHeaders))
			{
				$aMeta = stream_get_meta_data($fp);
				$aHeaders = $aMeta['wrapper_data'];
				foreach($aHeaders as $sHeaderString)
				{
					if(preg_match('/^([^:]+): (.+)$/', $sHeaderString, $aMatches))
					{
						$aResponseHeaders[$aMatches[1]] = trim($aMatches[2]);
					}
				}
			}
		}
		return $response;
	}
}
?>
