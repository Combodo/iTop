<?php
// Copyright (C) 2010-2024 Combodo SAS
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


define('DICT_ERR_STRING', 1); // when a string is missing, return the identifier
define('DICT_ERR_EXCEPTION', 2); // when a string is missing, throw an exception
//define('DICT_ERR_LOG', 3); // when a string is missing, log an error


/**
 * Class Dict
 * Management of localizable strings
 */
class Dict
{
	protected static $m_iErrorMode = DICT_ERR_STRING;
	protected static $m_sDefaultLanguage = 'EN US';
	protected static $m_sCurrentLanguage = null; // No language selected by default

	protected static $m_aLanguages = array(); // array( code => array( 'description' => '...', 'localized_description' => '...') ...)
	protected static $m_aData = array();
	protected static $m_sApplicationPrefix = null;
	/** @var \ApcService $m_oApcService  */
	protected static $m_oApcService = null;

	/**
	 * @param $sLanguageCode
	 *
	 * @throws \DictExceptionUnknownLanguage
	 */
	public static function SetDefaultLanguage($sLanguageCode)
	{
		if (!array_key_exists($sLanguageCode, self::$m_aLanguages))
		{
			throw new DictExceptionUnknownLanguage($sLanguageCode);
		}
		self::$m_sDefaultLanguage = $sLanguageCode;
	}

	/**
	 * @param $sLanguageCode
	 *
	 * @throws \DictExceptionUnknownLanguage
	 * @since 3.0.4 3.1.1 3.2.0 Param $sLanguageCode becomes nullable
	 */
	public static function SetUserLanguage($sLanguageCode = null)
	{
		if (!is_null($sLanguageCode) && !array_key_exists($sLanguageCode, self::$m_aLanguages))
		{
			throw new DictExceptionUnknownLanguage($sLanguageCode);
		}
		self::$m_sCurrentLanguage = $sLanguageCode;
	}


	public static function GetUserLanguage()
	{
		if (self::$m_sCurrentLanguage == null) // May happen when no user is logged in (i.e. login screen, non-authenticated page)
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

	/**
	 * Check if a dictionary entry exists or not
	 * @param $sStringCode
	 *
	 * @return bool
	 */
	public static function Exists($sStringCode)
	{
		$sImpossibleString = 'aVlHYKEI3TZuDV5o0pghv7fvhYNYuzYkTk7WL0Zoqw8rggE7aq';
		if (static::S($sStringCode, $sImpossibleString) === $sImpossibleString)
		{
			return false;
		}
		return true;
	}

	/**
	 * Returns a localised string from the dictionary
	 *
	 * @param string $sStringCode The code identifying the dictionary entry
	 * @param string $sDefault Default value if there is no match in the dictionary, if no default provided, returns $sStringCode unchanged
	 * @param bool $bUserLanguageOnly False to allow the use of the default language as a fallback, true otherwise
	 *
	 * @return string
	 */
	public static function S($sStringCode, $sDefault = null, $bUserLanguageOnly = false)
	{
		$aInfo = self::GetLabelAndLangCode($sStringCode, $sDefault, $bUserLanguageOnly);
		return $aInfo['label'];
	}

	/**
	 * Returns a localised string from the dictionary with its associated lang code
	 *
	 * @param string $sStringCode The code identifying the dictionary entry
	 * @param string $sDefault Default value if there is no match in the dictionary
	 * @param bool $bUserLanguageOnly True to allow the use of the default language as a fallback, false otherwise
	 *
	 * @return array{
	 *     lang: string, label: string
	 * } with localized label string and used lang code
	 */
	private static function GetLabelAndLangCode($sStringCode, $sDefault = null, $bUserLanguageOnly = false)
	{
		// Attempt to find the string in the user language
		//
		$sLangCode = self::GetUserLanguage();
		self::InitLangIfNeeded($sLangCode);

		if (! array_key_exists($sLangCode, self::$m_aData))
		{
			IssueLog::Warning("Cannot find $sLangCode in all registered dictionaries.");
			// It may happen, when something happens before the dictionaries get loaded
			return [ 'label' => $sStringCode, 'lang' => $sLangCode ];
		}
		$aCurrentDictionary = self::$m_aData[$sLangCode];
		if (is_array($aCurrentDictionary) && array_key_exists($sStringCode, $aCurrentDictionary))
		{
			return [ 'label' => $aCurrentDictionary[$sStringCode], 'lang' => $sLangCode ];
		}
		if (!$bUserLanguageOnly)
		{
			// Attempt to find the string in the default language
			//
			self::InitLangIfNeeded(self::$m_sDefaultLanguage);

			$aDefaultDictionary = self::$m_aData[self::$m_sDefaultLanguage];
			if (is_array($aDefaultDictionary) && array_key_exists($sStringCode, $aDefaultDictionary))
			{
				return [ 'label' => $aDefaultDictionary[$sStringCode], 'lang' => self::$m_sDefaultLanguage ];
			}
			// Attempt to find the string in english
			//
			self::InitLangIfNeeded('EN US');

			$aDefaultDictionary = self::$m_aData['EN US'];
			if (is_array($aDefaultDictionary) && array_key_exists($sStringCode, $aDefaultDictionary))
			{
				return [ 'label' => $aDefaultDictionary[$sStringCode], 'lang' => 'EN US' ];
			}
		}
		// Could not find the string...
		//
		if (is_null($sDefault))
		{
			return [ 'label' => $sStringCode, 'lang' => null ];
		}

		return [ 'label' => $sDefault, 'lang' => null ];
	}


	/**
	 * Formats a localized string with numbered placeholders (%1$s...) for the additional arguments
	 * See vsprintf for more information about the syntax of the placeholders
	 *
	 * @see \TemplateString to use placeholders
	 *
	 * @param string $sFormatCode
	 *
	 * @return string
	 */
	public static function Format($sFormatCode /*, ... arguments ... */)
	{
		['label' => $sLocalizedFormat, 'lang' => $sLangCode] = self::GetLabelAndLangCode($sFormatCode);

		$aArguments = func_get_args();
		array_shift($aArguments);

		if ($sLocalizedFormat == $sFormatCode)
		{
			// Make sure the information will be displayed (ex: an error occurring before the dictionary gets loaded)
			return $sFormatCode.' - '.implode(', ', $aArguments);
		}

		try{
			return vsprintf($sLocalizedFormat, $aArguments);
		} catch(\Throwable $e){
			\IssueLog::Error("Cannot format dict key", null, ["sFormatCode" => $sFormatCode, "sLangCode" => $sLangCode, 'exception_msg' => $e->getMessage() ]);
			return $sFormatCode.' - '.implode(', ', $aArguments);
		}
	}

	/**
	 * Initialize the entries for a given language (replaces the former Add() method)
	 * @param string $sLanguageCode Code identifying the language i.e. 'FR-FR', 'EN-US'
	 * @param array $aEntries Hash array of dictionary entries
	 */
	public static function SetEntries($sLanguageCode, $aEntries)
	{
		self::$m_aData[$sLanguageCode] = $aEntries;
	}

	/**
	 * Set the list of available languages
	 * @param hash $aLanguagesList
	 */
	public static function SetLanguagesList($aLanguagesList)
	{
		self::$m_aLanguages = $aLanguagesList;
	}

	/**
	 * @since 2.7.6 N°4125
	 * @return \ApcService
	 */
	public static function GetApcService() {
		if (self::$m_oApcService === null){
			self::$m_oApcService = new ApcService();
		}
		return self::$m_oApcService;
	}

	/**
	 * @since 2.7.6 N°4125
	 * @param \ApcService $m_oApcService
	 */
	public static function SetApcService($oApcService) {
		self::$m_oApcService = $oApcService;
	}

	/**
	 * Load a language from the language dictionary, if not already loaded
	 * @param string $sLangCode Language code
	 * @return boolean
	 */
	public static function InitLangIfNeeded($sLangCode)
	{
		if (array_key_exists($sLangCode, self::$m_aData)) return true;

		$bResult = false;

		if (self::GetApcService()->function_exists('apc_fetch')
			&& (self::$m_sApplicationPrefix !== null))
		{
			// Note: For versions of APC older than 3.0.17, fetch() accepts only one parameter
			//
			self::$m_aData[$sLangCode] = self::GetApcService()->apc_fetch(self::$m_sApplicationPrefix.'-dict-'.$sLangCode);
			if (self::$m_aData[$sLangCode] === false) {
				unset(self::$m_aData[$sLangCode]);
			} else if (! is_array(self::$m_aData[$sLangCode])) {
				// N°4125: we don't fix dictionary corrupted cache (on iTop side).
				// but we log an error in a dedicated channel to let itop administrator be aware of a potential APCu issue to fix.
				IssueLog::Error("APCu corrupted data (with $sLangCode dictionary). APCu configuration and running version should be troubleshooted...", LogChannels::APC);
				$bResult = true;
			} else {
				$bResult = true;
			}
		}
		if (!$bResult)
		{
			$sDictFile = APPROOT.'env-'.utils::GetCurrentEnvironment().'/dictionaries/'.str_replace(' ', '-', strtolower($sLangCode)).'.dict.php';
			require_once($sDictFile);

			if (self::GetApcService()->function_exists('apc_store')
				&& (self::$m_sApplicationPrefix !== null))
			{
				self::GetApcService()->apc_store(self::$m_sApplicationPrefix.'-dict-'.$sLangCode, self::$m_aData[$sLangCode]);
			}
			$bResult = true;
		}
		return $bResult;
	}

	/**
	 * Enable caching (cached using APC)
	 * @param string $sApplicationPrefix The prefix for uniquely identifying this iTop instance
	 */
	public static function EnableCache($sApplicationPrefix)
	{
		self::$m_sApplicationPrefix = $sApplicationPrefix;
	}

	/**
	 * Reset the cached entries (cached using APC)
	 * @param string $sApplicationPrefix The prefix for uniquely identifying this iTop instance
	 */
	public static function ResetCache($sApplicationPrefix)
	{
		if (function_exists('apc_delete'))
		{
			foreach(self::$m_aLanguages as $sLang => $void)
			{
				apc_delete($sApplicationPrefix.'-dict-'.$sLang);
			}
		}
	}

	/////////////////////////////////////////////////////////////////////////


	/**
	 * Clone a string in every language (if it exists in that language)
	 *
	 * @param $sSourceCode
	 * @param $sDestCode
	 * @since 3.0.1 Not clone sSourceCode entry if sDestCode entry already exist
	 */
	public static function CloneString($sSourceCode, $sDestCode)
	{
		foreach(self::$m_aLanguages as $sLanguageCode => $foo) {
			if (isset(self::$m_aData[$sLanguageCode][$sSourceCode]) && !isset(self::$m_aData[$sLanguageCode][$sDestCode] ))	{
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

	// Only used by the setup to determine the list of languages to display in the initial setup screen
	// otherwise replaced by LoadModule by its own handler
	// sLanguageCode: Code identifying the language i.e. FR-FR
	// sEnglishLanguageDesc: Description of the language code, in English. i.e. French (France)
	// sLocalizedLanguageDesc: Description of the language code, in its own language. i.e. Français (France)
	// aEntries: Hash array of dictionary entries
	// ~~ or ~* can be used to indicate entries still to be translated.
	public static function Add($sLanguageCode, $sEnglishLanguageDesc, $sLocalizedLanguageDesc, $aEntries)
	{
		if (!array_key_exists($sLanguageCode, self::$m_aLanguages))
		{
			self::$m_aLanguages[$sLanguageCode] = array('description' => $sEnglishLanguageDesc, 'localized_description' => $sLocalizedLanguageDesc);
			self::$m_aData[$sLanguageCode] = array();
		}
		// No need to actually load the strings since it's only used to know the list of languages
		// at setup time !!
	}

	/**
	 * Export all the dictionary entries - of the given language - whose code matches the given prefix
	 * missing entries in the current language will be replaced by entries in the default language
	 * @param string $sStartingWith
	 * @return string[]
	 */
	public static function ExportEntries($sStartingWith)
	{
		self::InitLangIfNeeded(self::GetUserLanguage());
		self::InitLangIfNeeded(self::$m_sDefaultLanguage);
		$aEntries = array();
		$iLength = strlen($sStartingWith);

		// First prefill the array with entries from the default language
		foreach(self::$m_aData[self::$m_sDefaultLanguage] as $sCode => $sEntry)
		{
			if (substr($sCode, 0, $iLength) == $sStartingWith)
			{
				$aEntries[$sCode] = $sEntry;
			}
		}

		// Now put (overwrite) the entries for the user language
		foreach(self::$m_aData[self::GetUserLanguage()] as $sCode => $sEntry)
		{
			if (substr($sCode, 0, $iLength) == $sStartingWith)
			{
				$aEntries[$sCode] = $sEntry;
			}
		}
		return $aEntries;
	}
}
?>
