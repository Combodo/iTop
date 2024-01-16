<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
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


/**
 * Wrapper to load dictionnary files without altering the main dictionnary
 * Eval will be called within the current namespace (this is done by adding a "namespace" statement)
 */
class Dict
{
	public static function Add($sLanguageCode, $sEnglishLanguageDesc, $sLocalizedLanguageDesc, $aEntries)
	{
	}
}
/**
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

		if (!preg_match('/^(.*)\\.dict/', basename($sDictFile), $aMatches)) {
			static::fail("Dictionary file '$sDictFile' not matching the naming convention");
		}

		$sLangPrefix = $aMatches[1];
		if (!array_key_exists($sLangPrefix, $aPrefixToLanguageData)) {
			static::fail("Unknown prefix '$sLangPrefix' for dictionary file '$sDictFile'");
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

		$aDictFiles = array_merge(
			glob($sAppRoot.'datamodels/2.x/*/*.dict*.php'), // legacy form in modules
			glob($sAppRoot.'datamodels/2.x/*/dictionaries/*.dict*.php'), // modern form in modules
			glob($sAppRoot.'dictionaries/*.dict*.php') // framework
		);
		$aTestCases = array();
		foreach ($aDictFiles as $sDictFile) {
			$aTestCases[$sDictFile] = array('sDictFile' => $sDictFile);
		}

		return $aTestCases;
	}

	/**
	 * @dataProvider DictionaryFileProvider
	 *
	 * @param string $sDictFile
	 *
	 * @group beforeSetup
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

	/**
	 * @param string $sDictFile complete path for the file to check
	 * @param bool $bIsSyntaxValid expected assert value
	 */
	private function CheckDictionarySyntax(string $sDictFile, $bIsSyntaxValid = true): void
	{
		$sPHP = file_get_contents($sDictFile);
		// Strip php tag to allow "eval"
		$sPHP = substr(trim($sPHP), strlen('<?php'));
		// Make sure the Dict class is the one declared in the current file
		$sPHP = 'namespace '.__NAMESPACE__.";\n".$sPHP;
		$iLineShift = 1; // Cope with the shift due to the namespace statement
		$sPHP = str_replace(
			['ITOP_APPLICATION_SHORT', 'ITOP_APPLICATION', 'ITOP_VERSION_NAME'],
			['\'itop\'', '\'itop\'', '\'1.2.3\''],
			$sPHP
		);
		try {
			eval($sPHP);
			// Reaching this point => No syntax error
			if (!$bIsSyntaxValid) {
				$this->fail("Failed to detect syntax error in dictionary `{$sDictFile}` (which is known as being INCORRECT)");
			}
		}
		catch (\Error $e) {
			if ($bIsSyntaxValid) {
				$iLine = $e->getLine() - $iLineShift;
				$this->fail("Invalid dictionary: {$e->getMessage()} in {$sDictFile}:{$iLine}");
			}
		}
		catch (\Exception $e) {
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
	 * @since 2.7.11 3.0.5 3.1.2 3.2.0 N°7143
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
			'itop-system-information',
			'itsm-designer-connector',
			'precanned-replies-pro',
			'precanned-replies',
			'templates-base',
		];
	}
}
