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

require_once('../core/config.class.inc.php');

define('ITOP_CONFIG_FILE', '../config-itop.php');

class FileUploadException extends Exception
{
}

class utils
{
	private static $m_oConfig = null;

	public static function ReadParam($sName, $defaultValue = "")
	{
		return isset($_REQUEST[$sName]) ? $_REQUEST[$sName] : $defaultValue;
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
				throw new FileUploadException(Dict::Format('UI:Error:UploadedFileTooBig'), ini_get('upload_max_filesize'));
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
		// TO DO implement the real mechanism here
		return sprintf("%08x", rand(0,2000000000));
	}
	
	public static function IsTransactionValid($sId)
	{
		// TO DO implement the real mechanism here
		return true;
	}
	
	public static function ReadFromFile($sFileName)
	{
		if (!file_exists($sFileName)) return false;
		return file_get_contents($sFileName);
	}

	/**
	 * Get access to the application config file
	 * @param none
	 * @return Config The Config object initialized from the application config file
	 */	 	 	 	
	public static function GetConfig()
	{
		if (self::$m_oConfig == null)
		{
			self::$m_oConfig = new Config(ITOP_CONFIG_FILE);
		}
		return self::$m_oConfig;
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
     * Returns an absolute URL to the current page
     * @param $bQueryString bool True to also get the query string, false otherwise
     * @return string The absolute URL to the current page
     */                   
	static public function GetAbsoluteUrl($bQueryString = true, $bForceHTTPS = false)
	{
		// Build an absolute URL to this page on this server/port
		$sServerName = $_SERVER['SERVER_NAME'];
		if ($bForceHTTPS)
		{
			$sProtocol = 'https';
			$sPort = '';
		}
		else
		{
			$sProtocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
			if ($sProtocol == 'http')
			{
				$sPort = ($_SERVER['SERVER_PORT'] == 80) ? '' : ':'.$_SERVER['SERVER_PORT'];
			}
			else
			{
				$sPort = ($_SERVER['SERVER_PORT'] == 443) ? '' : ':'.$_SERVER['SERVER_PORT'];
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
}
?>
