<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * Class Dict
 * Management of localizable strings 
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class DictException extends CoreException
{
}

class DictExceptionUnknownLanguage extends DictException
{
	public function __construct($sLanguageCode)
	{
		$aContext = array();
		$aContext['language_code'] = $sLanguageCode;
		parent::__construct('Unknown localization language', $aContext);
	}
}

class DictExceptionMissingString extends DictException
{
	public function __construct($sLanguageCode, $sStringCode)
	{
		$aContext = array();
		$aContext['language_code'] = $sLanguageCode;
		$aContext['string_code'] = $sStringCode;
		parent::__construct('Missing localized string', $aContext);
	}
}


define('DICT_ERR_STRING', 1); // when a string is missing, return the identifier
define('DICT_ERR_EXCEPTION', 2); // when a string is missing, throw an exception
//define('DICT_ERR_LOG', 3); // when a string is missing, log an error


class Dict
{
	protected static $m_bTraceFiles = false;
	protected static $m_aEntryFiles = array();

	protected static $m_iErrorMode = DICT_ERR_STRING;
	protected static $m_sDefaultLanguage = 'EN US';
	protected static $m_sCurrentLanguage = null; // No language selected by default

	protected static $m_aLanguages = array(); // array( code => array( 'description' => '...', 'localized_description' => '...') ...)
	protected static $m_aData = array();


	public static function EnableTraceFiles()
	{
		self::$m_bTraceFiles = true;
	}

	public static function GetEntryFiles()
	{
		return self::$m_aEntryFiles;
	}

	public static function SetDefaultLanguage($sLanguageCode)
	{
		if (!array_key_exists($sLanguageCode, self::$m_aLanguages))
		{
			throw new DictExceptionUnknownLanguage($sLanguageCode);
		}
		self::$m_sDefaultLanguage = $sLanguageCode;
	}

	public static function SetUserLanguage($sLanguageCode)
	{
		if (!array_key_exists($sLanguageCode, self::$m_aLanguages))
		{
			throw new DictExceptionUnknownLanguage($sLanguageCode);
		}
		self::$m_sCurrentLanguage = $sLanguageCode;
	}


	public static function GetUserLanguage()
	{
		if (self::$m_sCurrentLanguage == null) // May happen when no user is logged in (i.e login screen, non authentifed page)
		{
			// In which case let's use the default language
			return self::$m_sDefaultLanguage;
		}
		return self::$m_sCurrentLanguage;
	}

	//returns a hash array( code => array( 'description' => '...', 'localized_description' => '...') ...)
	public static function GetLanguages()
	{
		return self::$m_aLanguages;
	}

	// iErrorMode from {DICT_ERR_STRING, DICT_ERR_EXCEPTION}
	public static function SetErrorMode($iErrorMode)
	{
		self::$m_iErrorMode = $iErrorMode;
	}


	public static function S($sStringCode, $sDefault = null, $bUserLanguageOnly = false)
	{
		// Attempt to find the string in the user language
		//
		if (!array_key_exists(self::GetUserLanguage(), self::$m_aData))
		{
			// It may happen, when something happens before the dictionnaries get loaded
			return $sStringCode;
		}
		$aCurrentDictionary = self::$m_aData[self::GetUserLanguage()];
		if (array_key_exists($sStringCode, $aCurrentDictionary))
		{
			return $aCurrentDictionary[$sStringCode];
		}
		if (!$bUserLanguageOnly)
		{
			// Attempt to find the string in the default language
			//
			$aDefaultDictionary = self::$m_aData[self::$m_sDefaultLanguage];
			if (array_key_exists($sStringCode, $aDefaultDictionary))
			{
				return $aDefaultDictionary[$sStringCode];
			}
			// Attempt to find the string in english
			//
			$aDefaultDictionary = self::$m_aData['EN US'];
			if (array_key_exists($sStringCode, $aDefaultDictionary))
			{
				return $aDefaultDictionary[$sStringCode];
			}
		}
		// Could not find the string...
		//
		switch (self::$m_iErrorMode)
		{
			case DICT_ERR_STRING:
				if (is_null($sDefault))
				{
					return $sStringCode;
				}
				else
				{
					return $sDefault;
				}
				break;

			case DICT_ERR_EXCEPTION:
			default:
				throw new DictExceptionMissingString(self::$m_sCurrentLanguage, $sStringCode);
				break;
		}
		return 'bug!';
	}


	public static function Format($sFormatCode /*, ... arguments ....*/)
	{
		$sLocalizedFormat = self::S($sFormatCode);
		$aArguments = func_get_args();
		array_shift($aArguments);
		
		if ($sLocalizedFormat == $sFormatCode)
		{
			// Make sure the information will be displayed (ex: an error occuring before the dictionary gets loaded)
			return $sFormatCode.' - '.implode(', ', $aArguments);
		}

		return vsprintf($sLocalizedFormat, $aArguments);
	}


	// sLanguageCode: Code identifying the language i.e. FR-FR
	// sEnglishLanguageDesc: Description of the language code, in English. i.e. French (France)
	// sLocalizedLanguageDesc: Description of the language code, in its own language. i.e. Français (France)
	// aEntries: Hash array of dictionnary entries
	// ~~ or ~* can be used to indicate entries still to be translated. 
	public static function Add($sLanguageCode, $sEnglishLanguageDesc, $sLocalizedLanguageDesc, $aEntries)
	{
		if (self::$m_bTraceFiles)
		{
			$aBacktrace = debug_backtrace();
			$sFile = $aBacktrace[0]["file"];

			foreach($aEntries as $sKey => $sValue)
			{
				self::$m_aEntryFiles[$sLanguageCode][$sKey] = array(
					'file' => $sFile,
					'value' => $sValue
				);
			}
		}

		if (!array_key_exists($sLanguageCode, self::$m_aLanguages))
		{
			self::$m_aLanguages[$sLanguageCode] = array('description' => $sEnglishLanguageDesc, 'localized_description' => $sLocalizedLanguageDesc);
			self::$m_aData[$sLanguageCode] = array();
		}
		foreach($aEntries as $sCode => $sValue)
		{
			self::$m_aData[$sLanguageCode][$sCode] = self::FilterString($sValue);
		}
	}

	/**
	 * Clone a string in every language (if it exists in that language)
	 */	 	
	public static function CloneString($sSourceCode, $sDestCode)
	{
		foreach(self::$m_aLanguages as $sLanguageCode => $foo)
		{
			if (isset(self::$m_aData[$sLanguageCode][$sSourceCode]))
			{
				self::$m_aData[$sLanguageCode][$sDestCode] = self::$m_aData[$sLanguageCode][$sSourceCode];
			}
		}
	}

	public static function MakeStats($sLanguageCode, $sLanguageRef = 'EN US')
	{
		$aMissing = array(); // Strings missing for the target language
		$aUnexpected = array(); // Strings defined for the target language, but not found in the reference dictionary
		$aNotTranslated = array(); // Strings having the same value in both dictionaries
		$aOK = array(); // Strings having different values in both dictionaries

		foreach (self::$m_aData[$sLanguageRef] as $sStringCode => $sValue)
		{
			if (!array_key_exists($sStringCode, self::$m_aData[$sLanguageCode]))
			{
				$aMissing[$sStringCode] = $sValue;
			}
		}

		foreach (self::$m_aData[$sLanguageCode] as $sStringCode => $sValue)
		{
			if (!array_key_exists($sStringCode, self::$m_aData[$sLanguageRef]))
			{
				$aUnexpected[$sStringCode] = $sValue;
			}
			else
			{
				// The value exists in the reference
				$sRefValue = self::$m_aData[$sLanguageRef][$sStringCode];
				if ($sValue == $sRefValue)
				{
					$aNotTranslated[$sStringCode] = $sValue;
				}
				else
				{
					$aOK[$sStringCode] = $sValue;
				}
			}
		}
		return array($aMissing, $aUnexpected, $aNotTranslated, $aOK);
	}
	
	public static function Dump()
	{
		MyHelpers::var_dump_html(self::$m_aData);
	}
	
	public static function InCache($sApplicationPrefix)
	{
		if (function_exists('apc_fetch'))
		{
			$bResult = false;
			// Note: For versions of APC older than 3.0.17, fetch() accepts only one parameter
			//
			self::$m_aData = apc_fetch($sApplicationPrefix.'-dict');
			if (is_bool(self::$m_aData) && (self::$m_aData === false))
			{
				self::$m_aData = array();
			}
			else
			{
				self::$m_aLanguages = apc_fetch($sApplicationPrefix.'-languages');
				if (is_bool(self::$m_aLanguages) && (self::$m_aLanguages === false))
				{
					self::$m_aLanguages = array();
				}
				else
				{
					$bResult = true;
				}
			}
			return $bResult;
		}
		return false;
	}
	
	public static function InitCache($sApplicationPrefix)
	{
		if (function_exists('apc_store'))
		{
			apc_store($sApplicationPrefix.'-languages', self::$m_aLanguages);
			apc_store($sApplicationPrefix.'-dict', self::$m_aData);
		}
	}

	public static function ResetCache($sApplicationPrefix)
	{
		if (function_exists('apc_delete'))
		{
			apc_delete($sApplicationPrefix.'-languages');
			apc_delete($sApplicationPrefix.'-dict');
		}
	}
	
	protected static function FilterString($s)
	{
		return str_replace(array('~~', '~*'), '', $s);
	}
}
?>
