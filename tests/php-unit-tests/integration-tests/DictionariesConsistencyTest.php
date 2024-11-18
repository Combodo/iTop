<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
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
use Error;
use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use const ARRAY_FILTER_USE_BOTH;
use const DIRECTORY_SEPARATOR;


/**
 * Wrapper to load dictionary files without altering the main dictionary
 * Eval will be called within the current namespace (this is done by adding a "namespace" statement)
 *
 * @since 3.0.4 3.1.1 3.2.0 Optimize PHPUnit tests process time
 */
class Dict
{
	/**
	 * @var bool if true will keep entries in {@see m_aData}
	 */
	private static $bLoadEntries = false;

	private static $bSaveKeyDuplicates = false;

	/**
	 * @var array same as the real Dict class : language code as key, value containing array of dict key / label
	 */
	public static $m_aData = [];

	public static $aKeysDuplicate = [];

	public static $sLastAddedLanguageCode = null;

	public static function EnableLoadEntries(bool $bSaveKeyDuplicates = false) :void {
		self::$sLastAddedLanguageCode = null;
		self::$m_aData = [];
		self::$aKeysDuplicate = [];
		self::$bLoadEntries = true;
		self::$bSaveKeyDuplicates = $bSaveKeyDuplicates;
	}

	public static function Add($sLanguageCode, $sEnglishLanguageDesc, $sLocalizedLanguageDesc, $aEntries)
	{
		if (false === static::$bLoadEntries) {
			return;
		}

		static::$sLastAddedLanguageCode = $sLanguageCode;
		foreach ($aEntries as $sDictKey => $sDictLabel) {
			if (self::$bSaveKeyDuplicates) {
				if (isset(static::$m_aData[$sLanguageCode][$sDictKey])) {
					if (array_key_exists($sDictKey, self::$aKeysDuplicate)) {
						self::$aKeysDuplicate[$sDictKey]++;
					} else {
						self::$aKeysDuplicate[$sDictKey] = 1;
					}
				}
			}
			static::$m_aData[$sLanguageCode][$sDictKey] = $sDictLabel;
		}
	}

	public static function ResetFileDuplicate()
	{
		self::$aKeysDuplicate = [];
	}
}



/**
 * For tests on compiled dict files, see {@see CompiledDictionariesConsistencyTest}
 * @group beforeSetup
 */
class DictionariesConsistencyTest extends ItopTestCase
{
	/**
	 * Verify that language declarations match the file names (same language codes)
	 *
	 * @dataProvider DictionaryFileProvider
	 *
	 * @param $sDictFile
	 */
	public function testDictionariesLanguage($sDictFile): void
	{
		// In iTop the language available list is dynamically made during setup, depending on the dict files found
		// Here we are using a fixed list
		$aPrefixToLanguageData = $this->GetLanguagesData(false);

		if (!preg_match('/^(.*)\\.dict/', basename($sDictFile), $aMatches)) {
			static::fail("Dictionary file '$sDictFile' not matching the naming convention");
		}

		$sLangPrefix = $aMatches[1];
		if (!array_key_exists($sLangPrefix, $aPrefixToLanguageData)) {
			static::fail("Unknown prefix '$sLangPrefix' for dictionary file '$sDictFile:1'");
		}

		[$sExpectedLanguageCode, $sExpectedEnglishLanguageDesc, $aExpectedLocalizedLanguageDesc] = $aPrefixToLanguageData[$sLangPrefix];

		$sDictPHP = file_get_contents($sDictFile);
		$iCount = preg_match_all("@Dict::Add\('(.*)'\s*,\s*'(.*)'\s*,\s*'(.*)'@", $sDictPHP, $aMatches);
		if ($iCount === false) {
			static::fail("Pattern not working");
		}
		if ($iCount === 0) {
			// Empty dictionary, that's fine!
			static::assertTrue(true);
		}
		foreach ($aMatches[1] as $sLanguageCode) {
			static::assertSame($sExpectedLanguageCode, $sLanguageCode,
				"Unexpected language code for Dict::Add in dictionary $sDictFile");
		}
		foreach ($aMatches[2] as $sEnglishLanguageDesc) {
			static::assertSame($sExpectedEnglishLanguageDesc, $sEnglishLanguageDesc,
				"Unexpected language description (english) for Dict::Add in dictionary $sDictFile");
		}
		foreach ($aMatches[3] as $sLocalizedLanguageDesc)
		{
			if (false === is_array($aExpectedLocalizedLanguageDesc)) {
				$aExpectedLocalizedLanguageDesc = array($aExpectedLocalizedLanguageDesc);
			}
			static::assertContains($sLocalizedLanguageDesc,$aExpectedLocalizedLanguageDesc,
				"Unexpected language description for Dict::Add in dictionary $sDictFile");
		}
	}

	public function DictionaryFileProvider(): array
	{
		$this->setUp();
		$sAppRoot = static::GetAppRoot();


		$aDictFilesCore = [];
		$sCoreDictionariesPath = realpath($sAppRoot.'dictionaries');
		$sDictFilePattern = '/^.+\.dict.*\.php$/i';
		$oDirIterator = new RecursiveDirectoryIterator($sCoreDictionariesPath, RecursiveDirectoryIterator::SKIP_DOTS);
		$oIterator = new RecursiveIteratorIterator($oDirIterator, RecursiveIteratorIterator::SELF_FIRST);
		$oRegexIterator = new RegexIterator($oIterator, $sDictFilePattern, RegexIterator::GET_MATCH);
		foreach($oRegexIterator as $file) {
			$aDictFilesCore[] = $file[0];
		}


		$aDictFilesModules = array_merge(
			glob($sAppRoot.'datamodels/2.x/*/*.dict*.php'), // legacy form in modules
			glob($sAppRoot.'datamodels/2.x/*/dictionaries/*.dict*.php'), // modern form in modules

			//--- Following should not be present in packages, but are convenient for local debugging !
			glob($sAppRoot.'extensions/*/*.dict*.php'),
			glob($sAppRoot.'extensions/*/dictionaries/*.dict*.php')
		);
		$this->RemoveModulesWithout7246Fixes($aDictFilesModules);


		$aDictFiles = array_merge($aDictFilesCore, $aDictFilesModules);

		$aTestCases = array();
		foreach ($aDictFiles as $sDictFile) {
			preg_match('/^(.*)\\.dict/', basename($sDictFile), $aMatches);
			$sDictFileLangPrefix = $aMatches[1];

				$aTestCases[$sDictFile] = array('sDictFile' => $sDictFile, 'sLanguagePrefix' => $sDictFileLangPrefix);
		}

		return $aTestCases;
	}

	/**
	 * Most of our product packages uses tags for extensions modules, so they won't get the fixes. We are removing them, as we will test on newer packages anyway !
	 *
	 * @since 3.0.5 3.1.2 3.2.0 N°7246
	 */
	private function RemoveModulesWithout7246Fixes(array &$aDictFilesModules):void
	{
		require_once static::GetAppRoot() . 'approot.inc.php'; // mandatory for tearDownAfterClass to work, of not present will thow `Undefined constant "LINKSET_TRACKING_LIST"`
		$this->RequireOnceItopFile('core/config.class.inc.php'); // source of the ITOP_VERSION constant
		if (version_compare(ITOP_VERSION, '3.2.0', '>=')) { 
			return;
		}

		$aLegacyModulesList = [
			'authent-token',
			'combodo-approval-extended',
			'combodo-approval-light',
			'combodo-calendar-view',
			'combodo-oauth-email-synchro',
			'combodo-webhook-integration',
			'customer-survey',
			'itop-communications',
			'itop-fence',
			'itop-system-information',
			'itsm-designer-connector',
			'templates-base',
		];

		foreach ($aDictFilesModules as $key => $sDictFileFullPath) {
			$sDictFilePath = dirname($sDictFileFullPath);
			$sDictFileModuleName = basename($sDictFilePath);
			if (in_array($sDictFileModuleName, $aLegacyModulesList)) {
				unset($aDictFilesModules[$key]);
			}
		}
	}

	/**
	 * @dataProvider DictionaryFileProvider
	 *
	 * @param string $sDictFile
	 *
	 *
	 * @uses         CheckDictionarySyntax
	 */
	public function testStandardDictionariesPhpSyntax(string $sDictFile): void
	{
		$this->CheckDictionarySyntax($sDictFile);
	}

	/**
	 * Checks that {@see CheckDictionarySyntax} works as expected by passing 2 test dictionaries
	 *
	 * @uses CheckDictionarySyntax
	 */
	public function testPlaygroundDictionariesPhpSyntax(): void
	{
		$this->CheckDictionarySyntax(__DIR__.'/dictionaries-test/fr.dictionary.itop.core.KO.wrong_php', false);
		/** @noinspection PhpRedundantOptionalArgumentInspection */
		$this->CheckDictionarySyntax(__DIR__.'/dictionaries-test/fr.dictionary.itop.core.OK.php', true);
	}

	private function GetPhpCodeFromDictFile(string $sDictFile) : string {
		$sPHP = file_get_contents($sDictFile);
		// Strip php tag to allow "eval"
		$sPHP = substr(trim($sPHP), strlen('<?php'));
		// Make sure the Dict class is the one declared in the current file
		$sPHP = 'namespace '.__NAMESPACE__.";\n".$sPHP;

		// we are replacing instead of defining the constant so that if the constant is inside the string it will trigger an error
		// eg 	`'UI:Audit:Title' => 'ITOP_APPLICATION_SHORT - CMDB Audit',`
		// which should be `'UI:Audit:Title' => ITOP_APPLICATION_SHORT.' - CMDB Audit',`
		// Also we are replacing with - instead of _ as ITOP_APPLICATION_SHORT contains ITOP_APPLICATION and we don't want this replacement to occur
		$sPHP = str_replace(
			['ITOP_APPLICATION_SHORT', 'ITOP_APPLICATION', 'ITOP_VERSION_NAME'],
			['\'CONST__ITOP-APPLICATION-SHORT\'', '\'CONST__ITOP-APPLICATION\'', '\'CONST__ITOP-VERSION-NAME\''],
			$sPHP
		);

		return $sPHP;
	}

	/**
	 * @param string $sDictFile complete path for the file to check
	 * @param bool $bIsSyntaxValid expected assert value
	 */
	private function CheckDictionarySyntax(string $sDictFile, bool $bIsSyntaxValid = true): void
	{
		$sPHP = $this->GetPhpCodeFromDictFile($sDictFile);
		$iLineShift = 1; // Cope with the shift due to the namespace statement added in GetPhpCodeFromDictFile

		try {
			eval($sPHP);
			// Reaching this point => No syntax error
			if (!$bIsSyntaxValid) {
				$this->fail("Failed to detect syntax error in dictionary `{$sDictFile}` (which is known as being INCORRECT)");
			}
		}
		catch (Error $e) {
			if ($bIsSyntaxValid) {
				$iLine = $e->getLine() - $iLineShift;
				$this->fail("Invalid dictionary: {$e->getMessage()} in {$sDictFile}:{$iLine}");
			}
		}
		catch (Exception $e) {
			if ($bIsSyntaxValid) {
				$iLine = $e->getLine() - $iLineShift;
				$sExceptionClass = get_class($e);
				$this->fail("Exception thrown from dictionary: '$sExceptionClass: {$e->getMessage()}' in {$sDictFile}:{$iLine}");
			}
		}
		$this->assertTrue(true);
	}

	/**
	 * Since 3.0.0 and N°2969 it is possible to have a dictionaries directory in modules. We want to ensure that core modules use this functionality !
	 *
	 * @since 3.0.5 3.1.2 3.2.0 N°7143
	 */
	public function testNoDictFileInDatamodelsModuleRootDirectory():void {
		$sAppRoot = static::GetAppRoot();
		$aDictFilesInDatamodelsModuleRootDir = glob($sAppRoot.'datamodels/2.x/*/*.dict*.php');
		$this->assertNotFalse($aDictFilesInDatamodelsModuleRootDir, 'Searching for files returned an error');

		$aExcludedModulesList = $this->GetLtsCompatibleModulesList();
		$aDictFilesInDatamodelsModuleRootDir = array_filter(
			$aDictFilesInDatamodelsModuleRootDir,
			function($sDictFileFullPath) use ($aExcludedModulesList) {
				$sModuleFullPath = dirname($sDictFileFullPath);
				$sModuleDirectory = basename($sModuleFullPath);
				return !in_array($sModuleDirectory, $aExcludedModulesList);
			}
		);

		$sDictFilesInDatamodelsModuleRootDirList = var_export($aDictFilesInDatamodelsModuleRootDir, true);
		$this->assertCount(0, $aDictFilesInDatamodelsModuleRootDir,
			<<<EOF
There are some files in datamodels module root dirs ! You must either: 
- add the module in the GetLtsCompatibleModulesList method (if the module needs to keep compatibility with iTop 2.7)
- or move the dict files to the `dictionaries` module subfolder (if it can be set to iTop min 3.0.0)

List of directories: 
$sDictFilesInDatamodelsModuleRootDirList
EOF
		);
	}

	/**
	 * @return string[] List of modules that we want to ignore in {@link testNoDictFileInDatamodelsModuleRootDirectory}
	 *            Indeed multiple targets will add modules that must remain compatible with iTop 2.7 LTS, though with dict files in their root dir
	 *            The dictionaries directory in modules was added in 3.0.0 with N°2969
	 */
	private function GetLtsCompatibleModulesList(): array {
		return [
			'approval-base',
			'authent-token',
			'combodo-approval-extended',
			'combodo-approval-light',
			'combodo-autoclose-ticket',
			'combodo-autodispatch-ticket',
			'combodo-calendar-view',
			'combodo-coverage-windows-computation',
			'combodo-custom-hyperlinks',
			'combodo-dispatch-incident',
			'combodo-dispatch-userrequest',
			'combodo-email-synchro',
			'combodo-fulltext-search',
			'combodo-hybridauth',
			'combodo-impersonate',
			'combodo-notify-on-expiration',
			'combodo-oauth-email-synchro',
			'combodo-password-expiration',
			'combodo-portal-dynamic-branding-logo',
			'combodo-saml',
			'combodo-sla-computation',
			'combodo-webhook-integration',
			'combodo-workflow-graphical-view',
			'customer-survey',
			'email-reply',
			'itop-approval-portal',
			'itop-communications',
			'itop-fence',
			'itop-log-mgmt',
			'itop-object-copier',
			'itop-request-template',
			'itop-standard-email-synchro',
			'itop-synchro-dashboard',
			'itop-system-information',
			'itsm-designer-connector',
			'precanned-replies-pro',
			'precanned-replies',
			'templates-base',
			'combodo-backoffice-compact-themes',
			'itop-assign-to-me',
			'itop-icalendar-action',
		];
	}

	/**
	 * @dataProvider DictionaryFileProvider
	 */
	public function testDictKeyDefinedOncePerFile(string $sDictFileToTestFullPath): void {
		Dict::EnableLoadEntries(true);

		$sDictFileToTestPhp = $this->GetPhpCodeFromDictFile($sDictFileToTestFullPath);
		eval($sDictFileToTestPhp);

		$aDictKeysDefinedMultipleTimes = [];
		foreach (Dict::$aKeysDuplicate as $sDictKey => $iNumberOfDuplicates) {
			$sFirstKeyDeclaration = $this->FindDictKeyLineNumberInContent($sDictFileToTestPhp, $sDictKey);
			$aDictKeysDefinedMultipleTimes[$sDictKey] = $this->MakeFilePathClickable($sDictFileToTestFullPath, $sFirstKeyDeclaration);
		}
		$this->assertEmpty(Dict::$aKeysDuplicate, 'Some keys are defined multiple times in this file:'.var_export($aDictKeysDefinedMultipleTimes, true));
	}

	/**
	 * @dataProvider GetLanguagesData
	 *
	 * @param string $sLang
	 *
	 * @return void
	 */
	public function testDictKeyDefinedOnceForWholeProject(string $sLang): void {
		$this->markTestSkipped("Skip because duplicates exists in modules, while once is installed at setup. Possible solution : centralize common string in another dictionnary, and then enable this test.");

		Dict::EnableLoadEntries(true);
		$aDictKeysDefinedMultipleTimes = [];


		foreach ($this->DictionaryFileProvider() as $aDictFile) {

			if($aDictFile['sLanguagePrefix'] !== $sLang) continue;


			Dict::ResetFileDuplicate();
			$sDictFileToTestFullPath = $aDictFile['sDictFile'];

			$sDictFileToTestPhp = $this->GetPhpCodeFromDictFile($sDictFileToTestFullPath);
			eval($sDictFileToTestPhp);

			foreach (Dict::$aKeysDuplicate as $sDictKey => $iNumberOfDuplicates) {
				if (array_key_exists($sDictKey, $aDictKeysDefinedMultipleTimes)) {
					$aDictKeysDefinedMultipleTimes[$sDictKey]+= $iNumberOfDuplicates;
				} else {
					$aDictKeysDefinedMultipleTimes[$sDictKey] = $iNumberOfDuplicates;
				}
			}
		}
		$aDictKeysDefinedMoreThanOnce = array_filter($aDictKeysDefinedMultipleTimes, static function($iNumberOfDuplicates) {
			return $iNumberOfDuplicates > 0;
		});
		$this->assertEmpty($aDictKeysDefinedMoreThanOnce, "Some keys (". sizeof($aDictKeysDefinedMoreThanOnce).") are defined multiple times in the whole projectin lang $sLang: ".var_export($aDictKeysDefinedMoreThanOnce, true));

	}


		/**
	 * @dataProvider DictionaryFileProvider
	 */
	public function testNoRemainingTildesInTranslatedKeys(string $sDictFileToTestFullPath): void
	{
		Dict::EnableLoadEntries();
		$sReferenceLangCode = 'EN US';
		$sReferenceDictName = 'en';


		$sDictFileToTestPhp = $this->GetPhpCodeFromDictFile($sDictFileToTestFullPath);
		eval($sDictFileToTestPhp);

		$sLanguageCodeToTest = Dict::$sLastAddedLanguageCode;
		if (is_null($sLanguageCodeToTest)) {
			$this->assertTrue(true, 'No Dict::Add call in this file !');
			return;
		}
		if ($sLanguageCodeToTest === $sReferenceLangCode) {
			$this->assertTrue(true, 'Not testing reference lang !');
			return;
		}
		if (empty(Dict::$m_aData[$sLanguageCodeToTest])) {
			$this->assertTrue(true, 'No Dict key defined in this file !');
			return;
		}

		$oDictFileToTestInfo = pathinfo($sDictFileToTestFullPath);
		$sDictFilesDir = $oDictFileToTestInfo['dirname'];
		$sDictFileToTestFilename = $oDictFileToTestInfo['basename'];
		$sDictFileReferenceFilename = preg_replace('/^[^.]*./', $sReferenceDictName.'.', $sDictFileToTestFilename);
		$sDictFileReferenceFullPath = $sDictFilesDir.DIRECTORY_SEPARATOR.$sDictFileReferenceFilename;
		$sDictFileReferencePhp = $this->GetPhpCodeFromDictFile($sDictFileReferenceFullPath);
		eval($sDictFileReferencePhp);
		if (empty(Dict::$m_aData[$sReferenceLangCode])) {
			$this->assertTrue(true, 'No Dict key defined in the reference file !');
			return;
		}

		$aLangToTestDictEntries = Dict::$m_aData[$sLanguageCodeToTest];
		$aReferenceLangDictEntries = Dict::$m_aData[$sReferenceLangCode];


		$this->assertGreaterThan(0, count($aLangToTestDictEntries), 'There should be at least one entry in the dictionary file to test');
		$aLangToTestDictEntriesNotEmptyValues = array_filter(
			$aLangToTestDictEntries,
			static function ($value, $key) {
				return !empty($value);
			},
			ARRAY_FILTER_USE_BOTH
		);
		$this->assertNotEmpty($aLangToTestDictEntriesNotEmptyValues);


		$aTranslatedKeysWithTildes = [];
		foreach ($aReferenceLangDictEntries as $sDictKey => $sReferenceLangLabel) {
			if (false === array_key_exists($sDictKey, $aLangToTestDictEntries)) {
				continue;
			}

			$sTranslatedLabel = $aLangToTestDictEntries[$sDictKey];

			$bTranslatedLabelHasTildes = preg_match('/~~$/', $sTranslatedLabel) === 1;
			if (false === $bTranslatedLabelHasTildes) {
				continue;
			}

			$sTranslatedLabelWithoutTildes = preg_replace('/~~$/', '', $sTranslatedLabel);
			if ($sTranslatedLabelWithoutTildes === '') {
				continue;
			}

			if ($sTranslatedLabelWithoutTildes === $sReferenceLangLabel) {
				continue;
			}

			$sDictKeyLineNumberInDictFileToTest = $this->FindDictKeyLineNumberInContent($sDictFileToTestPhp, $sDictKey);
			$sDictKeyLineNumberInDictFileReference = $this->FindDictKeyLineNumberInContent($sDictFileReferencePhp, $sDictKey);
			$aTranslatedKeysWithTildes[$sDictKey] = [
				$sLanguageCodeToTest.'_file_location' => $this->MakeFilePathClickable($sDictFileToTestFullPath, $sDictKeyLineNumberInDictFileToTest),
				$sLanguageCodeToTest => $sTranslatedLabel,
				$sReferenceLangCode.'_file_location' => $this->MakeFilePathClickable($sDictFileReferenceFullPath, $sDictKeyLineNumberInDictFileReference),
				$sReferenceLangCode => $sReferenceLangLabel
			];
		}

		$sPathRoot = static::GetAppRoot();
		$sDictFileToTestRelativePath = str_replace($sPathRoot, '', $sDictFileToTestFullPath);
		$this->assertEmpty($aTranslatedKeysWithTildes, "In {$sDictFileToTestRelativePath} \n following keys are different from their '{$sReferenceDictName}' counterpart (translated ?) but have tildes at the end:\n" . var_export($aTranslatedKeysWithTildes, true));
	}

	/**
	 * @param string $sFullPath
	 * @param int $iLineNumber
	 *
	 * @return string a path that is clickable in PHPStorm 🤩
	 *              For this to happen we need full path with correct dir sep + line number
	 *              If it is not, check in File | Settings | Tools | Terminal the hyperlink option is checked
     */
	private function MakeFilePathClickable(string $sFullPath, int $iLineNumber):string {
		return str_replace(array('//', '/'), array('/', DIRECTORY_SEPARATOR), $sFullPath).':'.$iLineNumber;
	}

	private function FindDictKeyLineNumberInContent(string $sFileContent, string $sDictKey): int
	{
    	$aContentLines = explode("\n", $sFileContent);
		$sDictKeyToFind = "'{$sDictKey}'"; // adding string delimiters to match exact dict key (eg if not we would match 'Core:AttributeDateTime?SmartSearch' for 'Core:AttributeDateTime')

		foreach($aContentLines as $iLineNumber => $line) {
			if(strpos($line, $sDictKeyToFind) !== false){
				return $iLineNumber;
			}
		}

		return 1;
	}

	/**
	 * @param bool $bPrefixOnly
	 *
	 * @return array
	 */
	public function GetLanguagesData(bool $bPrefixOnly = true): array
	{
		$aLanguages = [
			'cs'    => ['CS CZ', 'Czech', 'Čeština'],
			'da'    => ['DA DA', 'Danish', 'Dansk'],
			'de'    => ['DE DE', 'German', 'Deutsch'],
			'en'    => ['EN US', 'English', 'English'],
			'en_gb' => ['EN GB', 'British English', 'British English'],
			'es_cr' => [
				'ES CR',
				'Spanish',
				[
					'Español, Castellaño', // old value
					'Español, Castellano', // new value since N°3635
				]
			],
			'fr'    => ['FR FR', 'French', 'Français'],
			'hu'    => ['HU HU', 'Hungarian', 'Magyar'],
			'it'    => ['IT IT', 'Italian', 'Italiano'],
			'ja'    => ['JA JP', 'Japanese', '日本語'],
			'nl'    => ['NL NL', 'Dutch', 'Nederlands'],
			'pl'    => ['PL PL', 'Polish', 'Polski'],
			'pt_br' => ['PT BR', 'Brazilian', 'Brazilian'],
			'ru'    => ['RU RU', 'Russian', 'Русский'],
			'sk'    => ['SK SK', 'Slovak', 'Slovenčina'],
			'tr'    => ['TR TR', 'Turkish', 'Türkçe'],
			'zh_cn' => ['ZH CN', 'Chinese', '简体中文'],
		];

		if ($bPrefixOnly) {
			$aPrefixLang = [];
			foreach ($aLanguages as $key => $value) {
				$aPrefixLang[$value[1]] = ['lang' => $key];
			}
			return $aPrefixLang;
		}
		return $aLanguages;
	}
}
