<?php
/**
 * Dict
 * Handles Localizable data 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
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
	protected static $m_sCurrentLanguage = 'EN US';

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
		$aCurrentDictionary = self::$m_aData[self::$m_sCurrentLanguage];
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
			self::$m_aLanguages[$sLanguageCode] = array($sEnglishLanguageDesc, $sLocalizedLanguageDesc);
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

/*
Dans les templates, les chaines localizables seront remplacées par un tag <itopstring>code_de_la_chaine</itopstring>
Modifier les profils utilisateurs pour stocker le langage de l'utilisateur.
*/


?>
