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
 * Class Dict
 * Management of localizable strings 
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
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
	protected static $m_iErrorMode = DICT_ERR_STRING;
	protected static $m_sDefaultLanguage = 'EN US';
	protected static $m_sCurrentLanguage = null; // No language selected by default

	protected static $m_aLanguages = array(); // array( code => array( 'description' => '...', 'localized_description' => '...') ...)
	protected static $m_aData = array();


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


	public static function GetCurrentLanguage()
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


	public static function S($sStringCode, $sDefault = null)
	{
		// Attempt to find the string in the user language
		//
		if (!array_key_exists(self::GetCurrentLanguage(), self::$m_aData))
		{
			// It may happen, when something happens before the dictionnaries get loaded
			return $sStringCode;
		}
		$aCurrentDictionary = self::$m_aData[self::GetCurrentLanguage()];
		if (array_key_exists($sStringCode, $aCurrentDictionary))
		{
			return $aCurrentDictionary[$sStringCode];
		}
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
		
		if ($sLocalizedFormat == $sFormatCode)
		{
			// Make sure the information will be displayed (ex: an error occuring before the dictionary gets loaded)
			return $sFormatCode.' - '.implode(', ', $aArguments);
		}

		array_shift($aArguments);
		return vsprintf($sLocalizedFormat, $aArguments);
	}


	// sLanguageCode: Code identifying the language i.e. FR-FR
	// sEnglishLanguageDesc: Description of the language code, in English. i.e. French (France)
	// sLocalizedLanguageDesc: Description of the language code, in its own language. i.e. Français (France)
	// aEntries: Hash array of dictionnary entries
	public static function Add($sLanguageCode, $sEnglishLanguageDesc, $sLocalizedLanguageDesc, $aEntries)
	{
		if (!array_key_exists($sLanguageCode, self::$m_aLanguages))
		{
			self::$m_aLanguages[$sLanguageCode] = array('description' => $sEnglishLanguageDesc, 'localized_description' => $sLocalizedLanguageDesc);
			self::$m_aData[$sLanguageCode] = array();
		}
		self::$m_aData[$sLanguageCode] = array_merge(self::$m_aData[$sLanguageCode], $aEntries);
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
}
?>
