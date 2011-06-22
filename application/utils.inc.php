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


	public static function ReadParam($sName, $defaultValue = "", $bAllowCLI = false)
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
		return $retValue;
	}
	
	public static function ReadPostedParam($sName, $defaultValue = "")
	{
		return isset($_POST[$sName]) ? $_POST[$sName] : $defaultValue;
	}
	
	/**
	 * Reads an uploaded file and turns it into an ormDocument object - Triggers an exception in case of error
	 * @param string $sName Name of the input used from uploading the file	 
	 * @return ormDocument The uploaded file (can be 'empty' if nothing was uploaded)
	 */	 	 
	public static function  ReadPostedDocument($sName)
	{
		$oDocument = new ormDocument(); // an empty document
		if(isset($_FILES[$sName]))
		{
			switch($_FILES[$sName]['error'])
			{
				case UPLOAD_ERR_OK:
				$doc_content = file_get_contents($_FILES[$sName]['tmp_name']);
				$sMimeType = $_FILES[$sName]['type'];
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
				$oDocument = new ormDocument($doc_content, $sMimeType, $_FILES[$sName]['name']);
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
				throw new FileUploadException(Dict::Format('UI:Error:UploadStoppedByExtension_FileName', $_FILES[$sName]['name']));
				break;
				
				default:
				throw new FileUploadException(Dict::Format('UI:Error:UploadFailedUnknownCause_Code', $_FILES[$sName]['error']));
				break;

			}
		}
		return $oDocument;
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
	 * Returns an absolute URL to the current page
	 * @param $bQueryString bool True to also get the query string, false otherwise
	 * @param $bForceHTTPS bool True to force HTTPS, false otherwise
	 * @return string The absolute URL to the current page
	 */                   
	static public function GetAbsoluteUrl($bQueryString = true, $bForceHTTPS = false)
	{
		// Build an absolute URL to this page on this server/port
		$sServerName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '';
		if (MetaModel::GetConfig()->GetSecureConnectionRequired() || MetaModel::GetConfig()->GetHttpsHyperlinks())
		{
			// If a secure connection is required, or if the URL is requested to start with HTTPS
			// then any URL must start with https !
			$bForceHTTPS = true;
		}
		if ($bForceHTTPS)
		{
			$sProtocol = 'https';
			$sPort = '';
		}
		else
		{
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
		if (!$bQueryString)
		{
			// remove all the parameters from the query string
			$iQuestionMarkPos = strpos($sPath, '?');
			if ($iQuestionMarkPos !== false)
			{
				$sPath = substr($sPath, 0, $iQuestionMarkPos);
			}
		} 
		$sUrl = "$sProtocol://{$sServerName}{$sPort}{$sPath}";
		
		return $sUrl;
	}
	
    /**
     * Returns the absolute URL PATH of the current page
     * @param $bForceHTTPS bool True to force HTTPS, false otherwise
     * @return string The absolute URL to the current page
     */                   
	static public function GetAbsoluteUrlPath($bForceHTTPS = false)
	{
		$sAbsoluteUrl = self::GetAbsoluteUrl(false, $bForceHTTPS); // False => Don't get the query string
		$sAbsoluteUrl = substr($sAbsoluteUrl, 0, 1+strrpos($sAbsoluteUrl, '/')); // remove the current page, keep just the path, up to the last /
		return $sAbsoluteUrl;
	}

    /**
     * Returns the absolute URL to the server's root path
     * @param $sCurrentRelativePath string NO MORE USED, kept for backward compatibility only !
     * @param $bForceHTTPS bool True to force HTTPS, false otherwise
     * @return string The absolute URL to the server's root, without the first slash
     */                   
	static public function GetAbsoluteUrlAppRoot($sCurrentRelativePathUNUSED = '', $bForceHTTPS = false)
	{
		$sAbsoluteUrl = self::GetAbsoluteUrl(false, $bForceHTTPS); // False => Don't get the query string
		$sCurrentScript = realpath($_SERVER['SCRIPT_FILENAME']);
		$sCurrentScript = str_replace('\\', '/', $sCurrentScript); // canonical path
		$sAppRoot = str_replace('\\', '/', APPROOT); // canonical path
		$sCurrentRelativePath = str_replace($sAppRoot, '', $sCurrentScript);
	
		$sAppRootPos = strpos($sAbsoluteUrl, $sCurrentRelativePath);
		if ($sAppRootPos !== false)
		{
			$sAbsoluteUrl = substr($sAbsoluteUrl, 0, $sAppRootPos); // remove the current page and path
		}
		else
		{
			throw new Exception("Failed to determine application root path $sAbsoluteUrl ($sCurrentRelativePath) APPROOT:'$sAppRoot'");
		}
		return $sAbsoluteUrl;
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
}
?>
