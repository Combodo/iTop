<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
 * This file is part of iTop.
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Test\UnitTest\Integration;

use Combodo\iTop\Test\UnitTest\ItopTestCase;

class DictionariesConsistencyAfterSetupTest extends ItopTestCase
{
	public function FormatProvider(){
		return [
			'key does not exist in dictionnary' => [
				'sTemplate' => null,
				'sExpectedTraduction' => 'ITOP::DICT:FORMAT:BROKEN:KEY - 1',
			],
			'traduction that breaks expected nb of arguments' => [
				'sTemplate' => 'toto %1$s titi %2$s',
				'sExpectedTraduction' => 'ITOP::DICT:FORMAT:BROKEN:KEY - 1',
			],
			'traduction ok' => [
				'sTemplate' => 'toto %1$s titi',
				'sExpectedTraduction' => 'toto 1 titi',
			],
		];
	}

	/**
	 * @since 2.7.10 N°5491 - Inconsistent dictionary entries regarding arguments to pass to Dict::Format
	 * Dict::Format
	 * @dataProvider FormatProvider
	 */
	public function testFormat($sTemplate, $sExpectedTraduction){
		$sLangCode = \Dict::GetUserLanguage();
		$aDictByLang = $this->GetNonPublicStaticProperty(\Dict::class, 'm_aData');
		$sDictKey = 'ITOP::DICT:FORMAT:BROKEN:KEY';

		if (! is_null($sTemplate)){
			if (array_key_exists($sLangCode, $aDictByLang)){
				$aDictByLang[$sLangCode][$sDictKey] = $sTemplate;
			} else {
				$aDictByLang[$sLangCode] = [$sDictKey => $sTemplate];
			}
		}

		$this->SetNonPublicStaticProperty(\Dict::class, 'm_aData', $aDictByLang);

		$this->assertEquals($sExpectedTraduction, \Dict::Format($sDictKey, "1"));
	}

	/**
	 * return a map generated with all *dict.php files content
	 * each entry key is the lang code (example 'en)
	 * each value is an array with localization code (ex. 'EN US') and a map of label key/values
	 * map is sorted by keys: en is first, then fr and then other lang code
	 * @return array
	 */
	private function ReadAllDictKeys() : array{
		$this->setUp();

		$aPrefixToLanguageData = array(
			'cs' => array('CS CZ', 'Czech', 'Čeština'),
			'da' => array('DA DA', 'Danish', 'Dansk'),
			'de' => array('DE DE', 'German', 'Deutsch'),
			'en' => array('EN US', 'English', 'English'),
			'es_cr' => array('ES CR', 'Spanish', array(
				'Español, Castellaño', // old value
				'Español, Castellano', // new value since N°3635
			)),
			'fr' => array('FR FR', 'French', 'Français'),
			'hu' => array('HU HU', 'Hungarian', 'Magyar'),
			'it' => array('IT IT', 'Italian', 'Italiano'),
			'ja' => array('JA JP', 'Japanese', '日本語'),
			'nl' => array('NL NL', 'Dutch', 'Nederlands'),
			'pl' => array('PL PL', 'Polish', 'Polski'),
			'pt_br' => array('PT BR', 'Brazilian', 'Brazilian'),
			'ru' => array('RU RU', 'Russian', 'Русский'),
			'sk' => array('SK SK', 'Slovak', 'Slovenčina'),
			'tr' => array('TR TR', 'Turkish', 'Türkçe'),
			'zh_cn' => array('ZH CN', 'Chinese', '简体中文'),
		);

		foreach($aPrefixToLanguageData as $sLang => $aLangInfo){
			$sLangCode = $aLangInfo[0];
			\Dict::InitLangIfNeeded($sLangCode);
		}
		$aDictEntries = $this->GetNonPublicStaticProperty(\Dict::class, 'm_aData');

		uksort($aDictEntries, function (string $sLangCode1, string $sLangCode2) {
			$sEnUsCode = "EN US";
			$sFrCode = "FR FR";

			if ($sLangCode1 === $sEnUsCode) {
				return -1;
			}
			if ($sLangCode2 === $sEnUsCode) {
				return 1;
			}
			if ($sLangCode1 === $sFrCode) {
				return -1;
			}
			if ($sLangCode2 === $sFrCode) {
				return 1;
			}
			return ($sLangCode1 < $sLangCode2) ? 1 : -1;
		});

		$this->tearDown();
		return $aDictEntries;
	}

	public function DictEntryValuesProvider(){
		//first entry should be linked to 'en' dictionnary
		//it is linked to sorting order used on ReadAllDictKeys
		$aFirstDictEntry = [];
		$sFirstEntryCode = null;

		$aUseCases = [];

		foreach ($this->ReadAllDictKeys() as $sCode => $aDictEntry){
			if (null === $sFirstEntryCode){
				$sFirstEntryCode = $sCode;
				$aFirstDictEntry = $aDictEntry;
			}
			$aUseCases[$sCode] = [
				'firstDict' => $aFirstDictEntry,
				'firstCode' => $sFirstEntryCode,
				'currentCode' => $sCode,
				'currentDict' => $aDictEntry,
			];
		}

		return $aUseCases;
	}

	/**
	 * foreach dictionnary label map (key/value) it counts the number argument that should be passed to use Dict::Format
	 * examples:
	 *  for "gabu zomeu" label there are no args
	 *  for "shadok %1 %2 %3" there are 3 args
	 *
	 * limitation: there is no validation check for "%3 itop %2 combodo" which seems unconsistent
	 * @param $aDictEntry
	 *
	 * @return array
	 */
	private function GetKeyArgCountMap($aDictEntry) {
		$aKeyArgsCount = [];
		foreach ($aDictEntry as $sKey => $sValue){
			$aKeyArgsCount[$sKey] = $this->countArg($sValue);
		}
		return $aKeyArgsCount;
	}

	private function countArg($sLabel) {
		$iMaxIndex = 0;
		if (preg_match_all("/%(\d+)/", $sLabel, $aMatches)){
			$aSubMatches = $aMatches[1];
			if (is_array($aSubMatches)){
				foreach ($aSubMatches as $aCurrentMatch){
					$iIndex = $aCurrentMatch;
					$iMaxIndex = ($iMaxIndex < $iIndex) ? $iIndex : $iMaxIndex;
				}
			}
		} else if ((false !== strpos($sLabel, "%s"))
			|| (false !== strpos($sLabel, "%d"))
		){
			$iMaxIndex = 1;
		}

		return $iMaxIndex;
	}

	/**
	 * @group beforeSetup
	 * compare en and other dictionnaries and check that for all labels there it the same number of arguments
	 * if not Dict::Format could raise an exception for some languages. translation should be done again...
	 * @dataProvider DictEntryValuesProvider
	 */
	public function testDictEntryValues($aFirstDictEntry, $sFirstEntryCode, $sCode, $aDictEntry)
	{
		$aKeyArgsCountMap = [];
		$aKeyArgsCountMap[$sFirstEntryCode] = $this->GetKeyArgCountMap($aFirstDictEntry);
		//$aKeyArgsCountMap[$sCode] = $this->GetKeyArgCountMap($aDictEntry);

		//set user language
		$this->SetNonPublicStaticProperty(\Dict::class, 'm_sCurrentLanguage', $sCode);

		$aMismatchedKeys = [];
		foreach ($aKeyArgsCountMap[$sFirstEntryCode] as $sKey => $iExpectedNbOfArgs){
			if (array_key_exists($sKey, $aDictEntry)){
				$aPlaceHolders = [];
				for ($i=0; $i<$iExpectedNbOfArgs; $i++){
					$aPlaceHolders[]=$i;
				}

				$sLabelTemplate = $aDictEntry[$sKey];
				try{
					if (is_null(vsprintf($sLabelTemplate, $aPlaceHolders))){
						$aMismatchedKeys['null label'] = $sKey;
					}
				} catch(\Exception $e){
					if (array_key_exists($e->getMessage(), $aMismatchedKeys)){
						$aMismatchedKeys[$e->getMessage()][$sKey] = $iExpectedNbOfArgs;
					} else {
						$aMismatchedKeys[$e->getMessage()] = [$sKey => $iExpectedNbOfArgs];
					}
				}
			}
		}

		$iCount = 0;
		foreach ($aMismatchedKeys as $sError => $aKeys){
			//var_dump($sError);
			foreach ($aKeys as $sKey => $iExpectedNbOfArgs) {
				$iCount++;
				if ($sFirstEntryCode === $sCode) {
					var_dump([
						'label key' => $sKey,
						'expected nb of args' => $iExpectedNbOfArgs,
						$sCode => $aDictEntry[$sKey],
					]);
				} else {
					var_dump([
						'label key' => $sKey,
						'expected nb of args' => $iExpectedNbOfArgs,
						$sCode => $aDictEntry[$sKey],
						"label value in $sFirstEntryCode" => $aFirstDictEntry[$sKey],
					]);
				}
			}
		}

		$sErrorMsg = sprintf("%s broken propertie(s) on $sCode dictionaries!", $iCount);
		$this->assertEquals([], $aMismatchedKeys, $sErrorMsg);
	}
}
