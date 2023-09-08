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
	 * return a map linked to *.dict.php files that are generated after setup
	 * each entry key is lang code (example 'en')
	 * each value is an array with lang code (again) and dict file path
	 * @return array
	 */
	private function GetDictFiles() : array {
		$aDictFiles = [];

		foreach (glob(APPROOT.'env-'.\utils::GetCurrentEnvironment().'/dictionaries/*.dict.php') as $sDictFile){
			if (preg_match('/.*\\/(.*).dict.php/', $sDictFile, $aMatches)){
				$sLangCode = $aMatches[1];
				$aDictFiles[$sLangCode] = [
					'lang' => $sLangCode,
					'file' => $sDictFile
				];
			}
		}
		return $aDictFiles;
	}

	/**
	 * return a map generated with all *dict.php files content
	 * each entry key is the lang code (example 'en)
	 * each value is an array with localization code (ex. 'EN US') and a map of label key/values
	 * map is sorted by keys: en is first, then fr and then other lang code
	 * @return array
	 */
	private function ReadAllDictKeys() : array{
		clearstatcache();
		$aDictFiles =  $this->GetDictFiles();
		$aDictEntries = [];
		$aTmpValue=[];
		foreach ($aDictFiles as $sCode => $aData){
			$sContent = file_get_contents($aData['file']);
			$sReplacedContent = str_replace('Dict::SetEntries(', "\$aTmpValue['$sCode'] = array(", $sContent);
			$sTempFilePath = tempnam(sys_get_temp_dir(), 'tmp_dict').'.php';
			file_put_contents($sTempFilePath, $sReplacedContent);
			require_once($sTempFilePath);
			unlink($sTempFilePath);

			$aDictEntries[$sCode] = $aTmpValue[$sCode];
		}

		uksort($aDictEntries, function (string $sLangCode1, string $sLangCode2) {
			$sEnUsCode = "en-us";
			$sFrCode = "fr-fr";

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

		return $aDictEntries;
	}

	/**
	 * @group beforeSetup
	 * this test checks that there are the exact same count of labels between 'en'
	 * it checks also that label keys are the same as well (we could have the same count with 'toto' label key on 'en' side and 'titi' on another dict side)
	 */
	public function testDictEntryKeys()
	{
		$aDictEntries =  $this->ReadAllDictKeys();
		$this->assertNotEquals([], $aDictEntries, "No entries found from *.dict.php");

		$sPreviousCode = null;
		$sPreviousSize = null;
		$sPreviousKeys = null;
		foreach ($aDictEntries as $sCode => $aData){
			$aLabelEntries = $aData[1];
			$iCurrentSize = sizeof($aLabelEntries);
			$aCurrentKeys = array_keys($aLabelEntries);
			sort($aCurrentKeys);

			if ($sPreviousCode===null){
				$sPreviousCode = $sCode;
				$sPreviousSize = $iCurrentSize;
				$aPreviousKeys = $aCurrentKeys;
			} else {
				$this->assertEquals($sPreviousSize, $iCurrentSize, "$sPreviousCode and $sCode  dictionnaries dont have the same amount of labels ($iCurrentSize vs $sPreviousSize)");
				$this->assertEquals($aPreviousKeys, $aCurrentKeys, "$sPreviousCode and $sCode dictionnaries dont have the same label keys");
			}
		}
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
	private function GetKeyArgCountMap($aDictEntry) : array{
		$aKeyArgsCount = [];
		$aLabelEntries = $aDictEntry[1];
		foreach ($aLabelEntries as $sKey => $sValue){
			$iMaxIndex = 0;
			if (preg_match_all("/%(\d+)/", $sValue, $aMatches)){
				$aSubMatches = $aMatches[1];
				if (is_array($aSubMatches)){
					foreach ($aSubMatches as $aCurrentMatch){
						$iIndex = $aCurrentMatch;
						$iMaxIndex = ($iMaxIndex < $iIndex) ? $iIndex : $iMaxIndex;
					}
				}
			} else if ((false !== strpos($sValue, "%s"))
				|| (false !== strpos($sValue, "%d"))
			){
				$iMaxIndex = 1;
			}

			$aKeyArgsCount[$sKey] = $iMaxIndex;
		}
		return $aKeyArgsCount;
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
		foreach ($aKeyArgsCountMap[$sFirstEntryCode] as $sKey => $iCount){
			if (array_key_exists($sKey, $aDictEntry[1])){
				$aPlaceHolders = [];
				for ($i=0; $i<$iCount; $i++){
					$aPlaceHolders[]=$i;
				}

				$sLabelTemplate = $aDictEntry[1][$sKey];
				try{
					if (is_null(vsprintf($sLabelTemplate, $aPlaceHolders))){
						$aMismatchedKeys['null label'] = $sKey;
					}
				} catch(\Exception $e){
					if (array_key_exists($e->getMessage(), $aMismatchedKeys)){
						$aMismatchedKeys[$e->getMessage()][] = $sKey;
					} else {
						$aMismatchedKeys[$e->getMessage()] = [$sKey];
					}
				}
			}
		}

		foreach ($aMismatchedKeys as $sError => $aKeys){
			var_dump($sError);
			foreach ($aKeys as $sKey) {
				if ($sFirstEntryCode === $sCode) {
					var_dump([
						'label key' => $sKey,
						'expected nb of args' => $iCount,
						$sCode => $aDictEntry[1][$sKey],
					]);
				} else {
					var_dump([
						'label key' => $sKey,
						'expected nb of args' => $iCount,
						$sCode => $aDictEntry[1][$sKey],
						"label value in $sFirstEntryCode" => $aFirstDictEntry[1][$sKey],
					]);
				}
			}
		}

		$sErrorMsg = "$sFirstEntryCode and $sCode dictionnaries dont have the proper args provided to Dict::Format method and UI could explode without try/catch N°5491 dirty fix!";
		$this->assertEquals([], $aMismatchedKeys, $sErrorMsg);
	}
}
