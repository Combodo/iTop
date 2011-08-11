<?php
// Copyright (C) 2010 Combodo SARL
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
 * Static class utils
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once(APPROOT.'/core/config.class.inc.php');
require_once(APPROOT.'/application/transaction.class.inc.php');

define('ITOP_CONFIG_FILE', APPROOT.'/config-itop.php');
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
	private static $m_sConfigFile = ITOP_CONFIG_FILE;
	private static $m_oConfig = null;
	private static $m_bCASClient = false;

	// Parameters loaded from a file, parameters of the page/command line still have precedence
	private static $m_aParamsFromFile = null;

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
			}
		}
	}

	public static function UseParamFile($sParamFileArgName = 'param_file', $bAllowCLI = true)
	{
		$sFileSpec = self::ReadParam($sParamFileArgName, '', $bAllowCLI);
		foreach(explode(',', $sFileSpec) as $sFile)
		{
			$sFile = trim($sFile);
			if (!empty($sFile))
			{
				self::LoadParamFile($sFile);
			}
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
		$retValue = self::Sanitize_Internal($value, $sSanitizationFilter);
		if ($retValue === false)
		{
			$retValue = $defaultValue;
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
			
			case 'parameter':
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
				$retValue = filter_var($value, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>'/^[ A-Za-z0-9_=-]*$/'))); // the '=' equal character is used in serialized filters
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

    /**
     * Returns the absolute URL to the server's root path
     * @param $sCurrentRelativePath string NO MORE USED, kept for backward compatibility only !
     * @param $bForceHTTPS bool True to force HTTPS, false otherwise
     * @return string The absolute URL to the server's root, without the first slash
     */                   
	static public function GetAbsoluteUrlAppRoot()
	{
		$sUrl = MetaModel::GetConfig()->Get('app_root_url');
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
		$sProtocol = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']!="off")) ? 'https' : 'http';
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
		$sCASIncludePath =  MetaModel::GetConfig()->Get('cas_include_path');
		include_once($sCASIncludePath.'/CAS.php');
		
		$bCASDebug = MetaModel::GetConfig()->Get('cas_debug');
		if ($bCASDebug)
		{
			phpCAS::setDebug(APPROOT.'/error.log');
		}
		
		if (!self::$m_bCASClient)
		{
			// Initialize phpCAS
			$sCASVersion = MetaModel::GetConfig()->Get('cas_version');
			$sCASHost = MetaModel::GetConfig()->Get('cas_host');
			$iCASPort = MetaModel::GetConfig()->Get('cas_port');
			$sCASContext = MetaModel::GetConfig()->Get('cas_context');
			phpCAS::client($sCASVersion, $sCASHost, $iCASPort, $sCASContext, false /* session already started */);
			self::$m_bCASClient = true;
			$sCASCACertPath = MetaModel::GetConfig()->Get('cas_server_ca_cert_path');
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
}
?>
