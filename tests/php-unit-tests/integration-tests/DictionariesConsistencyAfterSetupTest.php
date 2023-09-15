<?php

namespace Combodo\iTop\Test\UnitTest\Integration;

use Combodo\iTop\Test\UnitTest\ItopTestCase;

class DictionariesConsistencyAfterSetupTest extends ItopTestCase
{
	//used by testDictEntryValues
	//to filter false positive broken traductions
	private static $aLabelCodeNotToCheck = [
		//use of Dict::S not Format
		"UI:Audit:PercentageOk",

		//unused dead labels
		"Class:DatacenterDevice/Attribute:redundancy/count",
		"Class:DatacenterDevice/Attribute:redundancy/disabled",
		"Class:DatacenterDevice/Attribute:redundancy/percent",
		"Class:TriggerOnThresholdReached/Attribute:threshold_index+"
	];

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
	 * @param  $sTemplate : if null it will not create dict entry
	 * @since 2.7.10 N°5491 - Inconsistent dictionary entries regarding arguments to pass to Dict::Format
	 * @dataProvider FormatProvider
	 */
	public function testFormatWithOneArgumentAndCustomKey(?string $sTemplate, $sExpectedTranslation){
		//tricky way to mock GetLabelAndLangCode behavior via connected user language
		$sLangCode = \Dict::GetUserLanguage();
		$aDictByLang = $this->GetNonPublicStaticProperty(\Dict::class, 'm_aData');
		$sDictKey = 'ITOP::DICT:FORMAT:BROKEN:KEY';

		if (! is_null($sTemplate)){
			$aDictByLang[$sLangCode][$sDictKey] = $sTemplate;
		}

		$this->SetNonPublicStaticProperty(\Dict::class, 'm_aData', $aDictByLang);

		$this->assertEquals($sExpectedTranslation, \Dict::Format($sDictKey, "1"));
	}

	//test works after setup (no annotation @beforesetup)
	//even if it does not extend ItopDataTestCase
	private function ReadDictKeys($sLangCode) : array {
		\Dict::InitLangIfNeeded($sLangCode);

		$aDictEntries = $this->GetNonPublicStaticProperty(\Dict::class, 'm_aData');
		return $aDictEntries[$sLangCode];
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
		ksort($aKeyArgsCount);
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
	 * Warning: hardcoded list of languages
	 * It is hard to have it dynamically via Dict::GetLanguages as for each lang Dict::Init should be called first
	 **/
	public function LangCodeProvider(){
		return [
			'cs' => [ 'CS CZ' ],
			'da' => [ 'DA DA' ],
			'de' => [ 'DE DE' ],
			'en' => [ 'EN US' ],
			'es' => [ 'ES CR' ],
			'fr' => [ 'FR FR' ],
			'hu' => [ 'HU HU' ],
			'it' => [ 'IT IT' ],
			'ja' => [ 'JA JP' ],
			'nl' => [ 'NL NL' ],
			'pt' => [ 'PT BR' ],
			'ru' => [ 'RU RU' ],
			'sk' => [ 'SK SK' ],
			'tr' => [ 'TR TR' ],
			'zh' => [ 'ZH CN' ],
		];
	}

	/**
	 * compare en and other dictionaries and check that for all labels there is the same number of arguments
	 * if not Dict::Format could raise an exception for some languages. translation should be done again...
	 * @dataProvider LangCodeProvider
	 */
	public function testDictEntryValues($sLanguageCodeToTest)
	{
		$sReferenceLangCode = 'EN US';
		$aReferenceLangDictEntry = $this->ReadDictKeys($sReferenceLangCode);

		$aDictEntry = $this->ReadDictKeys($sLanguageCodeToTest);


		$aKeyArgsCountMap = [];
		$aKeyArgsCountMap[$sReferenceLangCode] = $this->GetKeyArgCountMap($aReferenceLangDictEntry);
		//$aKeyArgsCountMap[$sCode] = $this->GetKeyArgCountMap($aDictEntry);

		//set user language
		$this->SetNonPublicStaticProperty(\Dict::class, 'm_sCurrentLanguage', $sLanguageCodeToTest);

		$aMismatchedKeys = [];

		foreach ($aKeyArgsCountMap[$sReferenceLangCode] as $sKey => $iExpectedNbOfArgs){
			if (0 === $iExpectedNbOfArgs){
				//no arg needed in EN. 
				//let s assume job has been done correctly in EN to simplify
				continue;
			}

			if (in_array($sKey, self::$aLabelCodeNotToCheck)){
				//false positive: do not test
				continue;
			}

			if (array_key_exists($sKey, $aDictEntry)){
				$aPlaceHolders = [];
				for ($i=0; $i<$iExpectedNbOfArgs; $i++){
					$aPlaceHolders[]=$i;
				}

				$sLabelTemplate = $aDictEntry[$sKey];
				try{
					vsprintf($sLabelTemplate, $aPlaceHolders);
				} catch(\Throwable $e){
					$sError = $e->getMessage();
					if (array_key_exists($sError, $aMismatchedKeys)){
						$aMismatchedKeys[$sError][$sKey] = $iExpectedNbOfArgs;
					} else {
						$aMismatchedKeys[$sError] = [$sKey => $iExpectedNbOfArgs];
					}
				}
			}
		}

		$iCount = 0;
		foreach ($aMismatchedKeys as $sError => $aKeys){
			var_dump($sError);
			foreach ($aKeys as $sKey => $iExpectedNbOfArgs) {
				$iCount++;
				if ($sReferenceLangCode === $sLanguageCodeToTest) {
					var_dump([
						'key label' => $sKey,
						'expected nb of expected args' => $iExpectedNbOfArgs,
						"key value in $sLanguageCodeToTest" => $aDictEntry[$sKey],
					]);
				} else {
					var_dump([
						'key label' => $sKey,
						'expected nb of expected args' => $iExpectedNbOfArgs,
						"key value in $sLanguageCodeToTest" => $aDictEntry[$sKey],
						"key value in $sReferenceLangCode" => $aReferenceLangDictEntry[$sKey],
					]);
				}
			}
		}

		$sErrorMsg = sprintf("%s broken propertie(s) on $sLanguageCodeToTest dictionaries! either change the dict value in $sLanguageCodeToTest or add it in ignored label list (cf aLabelCodeNotToCheck)", $iCount);
		$this->assertEquals([], $aMismatchedKeys, $sErrorMsg);
	}

	public function VsprintfProvider(){
		return [
			'not enough args' => [
				"sLabelTemplate" => "$1%s",
				"aPlaceHolders" => [],
			],
			'exact nb of args' => [
				"sLabelTemplate" => "$1%s",
				"aPlaceHolders" => ["1"],
			],
			'too much args' => [
				"sLabelTemplate" => "$1%s",
				"aPlaceHolders" => ["1", "2"],
			],
			'\"% ok\" without args' => [
				"sLabelTemplate" => "% ok",
				"aPlaceHolders" => [],
			],
			'\"% ok $1%s\" without args' => [
				"sLabelTemplate" => "% ok",
				"aPlaceHolders" => ['1'],
			],
		];
	}

	/**
	 * @dataProvider VsprintfProvider
	public function testVsprintf($sLabelTemplate, $aPlaceHolders){
		try{
			$this->markTestSkipped("usefull to check a specific PHP version behavior");
			vsprintf($sLabelTemplate, $aPlaceHolders);
			$this->assertTrue(true);
		} catch(\Throwable $e) {
			$this->assertTrue(false, "label \'" .  $sLabelTemplate . " failed with " . var_export($aPlaceHolders, true)  );
		}
	}
	 */
}
