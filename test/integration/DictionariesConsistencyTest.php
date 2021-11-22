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
		$aPrefixToLanguageData = array(
			'cs' => array('CS CZ', 'Czech', 'Čeština'),
			'da' => array('DA DA', 'Danish', 'Dansk'),
			'de' => array('DE DE', 'German', 'Deutsch'),
			'en' => array('EN US', 'English', 'English'),
			'es_cr' => array('ES CR', 'Spanish', 'Español, Castellano'),
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

		[$sExpectedLanguageCode, $sExpectedEnglishLanguageDesc, $sExpectedLocalizedLanguageDesc] = $aPrefixToLanguageData[$sLangPrefix];

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
		foreach ($aMatches[3] as $sLocalizedLanguageDesc) {
			static::assertSame($sExpectedLocalizedLanguageDesc, $sLocalizedLanguageDesc,
				"Unexpected language description for Dict::Add in dictionary $sDictFile");
		}
	}

	public function DictionaryFileProvider(): array
	{
		$this->setUp();

		$aDictFiles = array_merge(
			glob(APPROOT.'datamodels/2.x/*/*.dict*.php'), // legacy form in modules
			glob(APPROOT.'datamodels/2.x/*/dictionaries/*.dict*.php'), // modern form in modules
			glob(APPROOT.'dictionaries/*.dict*.php') // framework
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
	 *
	 * @uses `php -l`
	 * @uses \assertEquals()
	 */
	private function CheckDictionarySyntax(string $sDictFile, $bIsSyntaxValid = true): void
	{
		exec("php -l {$sDictFile}", $output, $return);

		$bDictFileSyntaxOk = ($return === 0);

		$sMessage = "File `{$sDictFile}` syntax didn't matched expectations\nparsing results=".var_export($output, true);
		self::assertEquals($bIsSyntaxValid, $bDictFileSyntaxOk, $sMessage);
	}
}
