<?php
// Copyright (c) 2010-2023 Combodo SARL
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
//

/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 30/10/2017
 * Time: 13:43
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use Dict;


class dictApcuTest extends ItopTestCase
{
	private $sEnvName;
	private $oApcService;
	private $sDictionaryFolder;

	protected function setUp(): void
	{
		parent::setUp();

		$this->RequireOnceItopFile('core'.DIRECTORY_SEPARATOR.'apc-service.class.inc.php');

		// This id will be used as path to the dictionary files
		// It must be unique enough for the magic of Dict to operate (due to the use of require_once to load dictionaries)
		$this->sEnvName = uniqid();
		$_SESSION['itop_env'] = $this->sEnvName;

		// Preserve the dictionary for test that will be executed later on
		static::BackupStaticProperties('Dict');

		// Reset and prepare the dictionary
		static::SetNonPublicStaticProperty('Dict', 'm_aData', []);
		$this->oApcService = $this->createMock(\ApcService::class);
		Dict::SetApcService($this->oApcService);
		Dict::EnableCache('toto');

		Dict::SetLanguagesList(['FR FR' => 'fr', 'EN US' => 'en', 'DE DE' => 'de', 'RU RU' => 'de']);

		$this->InitDictionnaries();
	}

	private function InitDictionnaries()
	{
		clearstatcache();
		$this->sDictionaryFolder = APPROOT."env-$this->sEnvName" . DIRECTORY_SEPARATOR . "dictionaries";
		@mkdir($this->sDictionaryFolder, 0777, true);

		$sLabels = <<<STR
        'label1' => 'fr1',
STR;
		$this->InitDictionnary($this->sDictionaryFolder, 'FR FR', 'fr-fr', $sLabels);

		$sLabels = <<<STR
        'label1' => 'ru1',
        'label2' => 'ru2',
STR;
		$this->InitDictionnary($this->sDictionaryFolder, 'RU RU', 'ru-ru', $sLabels);
		$sLabels = <<<STR
        'label1' => 'en1',
        'label2' => 'en2',
        'label3' => 'en3',
STR;
		$this->InitDictionnary($this->sDictionaryFolder, 'EN US', 'en-us', $sLabels);

		clearstatcache();
	}

	private function InitDictionnary($sDictionaryFolder, $sLanguageCode, $sLanguageCodeInFilename, $sLabels)
	{
		$sContent = <<<PHP
<?php
//
// Dictionary built by the compiler for the language "FR FR"
//
Dict::SetEntries('$sLanguageCode', array(
        $sLabels
));
PHP;
		file_put_contents($sDictionaryFolder . DIRECTORY_SEPARATOR . "$sLanguageCodeInFilename.dict.php", $sContent);
	}

	private function InitBrokenDictionnary($sDictionaryFolder, $sLanguageCode, $sLanguageCodeInFilename)
	{
		$sContent = <<<PHP
<?php
//
// Dictionary built by the compiler for the language "FR FR"
//
Dict::SetEntries('$sLanguageCode', 'stringinsteadofanarray');
PHP;
		file_put_contents($sDictionaryFolder . DIRECTORY_SEPARATOR . "$sLanguageCodeInFilename.dict.php", $sContent);
	}

	protected function tearDown(): void
	{
		foreach (glob(APPROOT."env-$this->sEnvName".DIRECTORY_SEPARATOR."dictionaries".DIRECTORY_SEPARATOR."*") as $sFile) {
			unlink($sFile);
		}
		rmdir(APPROOT."env-$this->sEnvName".DIRECTORY_SEPARATOR."dictionaries");
		rmdir(APPROOT."env-$this->sEnvName");

		static::RestoreStaticProperties('Dict');

		parent::tearDown();
	}

	public function InitLangIfNeeded_NoApcProvider()
	{
		return [
			'apcu mocked' => [ 'bApcuMocked' => true ],
			'integration test - apcu service like in production - install php-apcu before' => [ 'bApcuMocked' => false ],
		];
	}

	/**
	 * @dataProvider InitLangIfNeeded_NoApcProvider
	 */
	public function testInitLangIfNeeded_NoApc($bApcuMocked)
	{
		if ($bApcuMocked) {
			$this->oApcService->expects($this->any())
				->method('function_exists')
				->willReturn(false);

			$this->oApcService->expects($this->never())
				->method('apc_fetch');

			$this->oApcService->expects($this->never())
				->method('apc_store');
		} else {
			Dict::SetApcService(null);
		}

		//EN US default language
		$this->assertEquals('en1', Dict::S('label1'));
		$this->assertEquals('en2', Dict::S('label2'));
		$this->assertEquals('en3', Dict::S('label3'));
		$this->assertEquals('not_defined_label', Dict::S('not_defined_label'));

		//default language set to RU RU
		Dict::SetDefaultLanguage('RU RU');
		$this->assertEquals('ru1', Dict::S('label1'));
		$this->assertEquals('ru2', Dict::S('label2'));
		$this->assertEquals('en3', Dict::S('label3'));
		$this->assertEquals('not_defined_label', Dict::S('not_defined_label'));

		//user language set to FR FR
		Dict::SetUserLanguage('FR FR');
		$this->assertEquals('fr1', Dict::S('label1'));
		$this->assertEquals('ru2', Dict::S('label2'));
		$this->assertEquals('en3', Dict::S('label3'));
		$this->assertEquals('not_defined_label', Dict::S('not_defined_label'));
	}

	public function testInitLangIfNeeded_Apc_LanguageMismatchDictionnary()
	{
		//language mismatch!!
		$sLabels = <<<STR
        'label1' => 'de1',
STR;
		$this->InitDictionnary($this->sDictionaryFolder, 'RU RU', 'de-de', $sLabels);

		clearstatcache();
		$this->oApcService->expects($this->any())
			->method('function_exists')
			->willReturn(false);

		$this->oApcService->expects($this->exactly(0))
			->method('apc_fetch');

		$this->oApcService->expects($this->never())
			->method('apc_store');

		Dict::SetUserLanguage('DE DE');
		$this->assertEquals('label1', Dict::S('label1'));
	}

	 public function testInitLangIfNeeded_Apc_BrokenUserDictionnary()
	 {
		$this->InitBrokenDictionnary($this->sDictionaryFolder, 'DE DE', 'de-de');

		$this->oApcService->expects($this->any())
			->method('function_exists')
			->willReturn(false);

		$this->oApcService->expects($this->exactly(0))
			->method('apc_fetch');

		$this->oApcService->expects($this->never())
			->method('apc_store');

		Dict::SetUserLanguage('DE DE');
		$this->assertEquals('en1', Dict::S('label1'));

		Dict::SetDefaultLanguage('RU RU');
		$this->assertEquals('ru1', Dict::S('label1'));
	}

	public function testInitLangIfNeeded_Apc_BrokenDictionnary_UserAndDefault()
	{
		$this->InitBrokenDictionnary($this->sDictionaryFolder, 'DE DE', 'de-de');
		$this->InitBrokenDictionnary($this->sDictionaryFolder, 'RU RU', 'ru-ru');

		$this->oApcService->expects($this->any())
			->method('function_exists')
			->willReturn(false);

		$this->oApcService->expects($this->exactly(0))
			->method('apc_fetch');

		$this->oApcService->expects($this->never())
			->method('apc_store');

		Dict::SetUserLanguage('DE DE');
		Dict::SetDefaultLanguage('RU RU');
		$this->assertEquals('en1', Dict::S('label1'));
	}

	public function testInitLangIfNeeded_Apc_BrokenDictionnary_ALL()
	{
		$this->InitBrokenDictionnary($this->sDictionaryFolder, 'DE DE', 'de-de');
		$this->InitBrokenDictionnary($this->sDictionaryFolder, 'RU RU', 'ru-ru');
		$this->InitBrokenDictionnary($this->sDictionaryFolder, 'EN US', 'en-us');

		$this->oApcService->expects($this->any())
			->method('function_exists')
			->willReturn(false);

		$this->oApcService->expects($this->exactly(0))
			->method('apc_fetch');

		$this->oApcService->expects($this->never())
			->method('apc_store');

		Dict::SetUserLanguage('DE DE');
		Dict::SetDefaultLanguage('RU RU');
		$this->assertEquals('label1', Dict::S('label1'));
	}

	public function testInitLangIfNeeded_ApcFromCache_PropertyInUserDictionnary()
	{
		$this->oApcService->expects($this->any())
			->method('function_exists')
			->willReturn(true);

		$this->oApcService->expects($this->exactly(1))
			->method('apc_fetch')
			->with('toto-dict-FR FR')
			->willReturn(['label1' => 'fr1']);

		$this->oApcService->expects($this->exactly(0))
			->method('apc_store');

		Dict::SetDefaultLanguage('RU RU');
		Dict::SetUserLanguage('FR FR');
		$this->assertEquals('fr1', Dict::S('label1'));
	}

	public function testInitLangIfNeeded_ApcStore_PropertyInUserDictionnary()
	{
		$this->oApcService->expects($this->any())
			->method('function_exists')
			->willReturn(true);

		$this->oApcService->expects($this->exactly(1))
			->method('apc_fetch')
			->with('toto-dict-FR FR')
			->willReturn(false);

		$this->oApcService->expects($this->exactly(1))
			->method('apc_store')
			->with('toto-dict-FR FR', ['label1' => 'fr1']);

		Dict::SetDefaultLanguage('RU RU');
		Dict::SetUserLanguage('FR FR');
		$this->assertEquals('fr1', Dict::S('label1'));
	}

	//corrupted data not fixed
	//we will return label from another dictionary (defaut one => russian here)
	public function testInitLangIfNeeded_Apc_CorruptedCache_PropertyInUserDictionnary(){
		$this->oApcService->expects($this->any())
			->method('function_exists')
			->willReturn(true);

		$this->oApcService->expects($this->exactly(2))
			->method('apc_fetch')
			->withConsecutive(['toto-dict-FR FR'], ['toto-dict-RU RU'])
			->willReturnOnConsecutiveCalls('corrupteddata', ['label1' => 'ru1']);

		$this->oApcService->expects($this->exactly(0))
			->method('apc_store');

		Dict::SetDefaultLanguage('RU RU');
		Dict::SetUserLanguage('FR FR');
		$this->assertEquals('ru1', Dict::S('label1'));
	}

	public function testInitLangIfNeeded_Apc_PropertyInDefaultLanguageDictionnary(){
		$this->oApcService->expects($this->any())
			->method('function_exists')
			->willReturn(true);

		$this->oApcService->expects($this->exactly(2))
			->method('apc_fetch')
			->withConsecutive(['toto-dict-FR FR'], ['toto-dict-RU RU'])
			->willReturnOnConsecutiveCalls([], false);

		$this->oApcService->expects($this->exactly(1))
			->method('apc_store')
			->withConsecutive(['toto-dict-RU RU', ['label1' => 'ru1', 'label2' => 'ru2']]
			);

		Dict::SetDefaultLanguage('RU RU');
		Dict::SetUserLanguage('FR FR');
		$this->assertEquals('ru2', Dict::S('label2'));
	}

	//corrupted data not fixed
	//we will return label from default language dictionary (EN here)
	public function testInitLangIfNeeded_ApcCorrupted_PropertyInDefaultLanguageDictionnary(){
		$this->oApcService->expects($this->any())
			->method('function_exists')
			->willReturn(true);

		$this->oApcService->expects($this->exactly(3))
			->method('apc_fetch')
			->withConsecutive(['toto-dict-FR FR'], ['toto-dict-RU RU'], ['toto-dict-EN US'])
			->willReturnOnConsecutiveCalls([], 'corrupteddata', ['label1' => 'en1', 'label2' => 'en2', 'label3' => 'en3']);

		$this->oApcService->expects($this->exactly(0))
			->method('apc_store');

		Dict::SetDefaultLanguage('RU RU');
		Dict::SetUserLanguage('FR FR');
		$this->assertEquals('en2', Dict::S('label2'));
	}

	public function testInitLangIfNeeded_Apc_PropertyInDictDefaultLanguageDictionnary()
	{
		$this->oApcService->expects($this->any())
			->method('function_exists')
			->willReturn(true);

		$this->oApcService->expects($this->exactly(3))
			->method('apc_fetch')
			->withConsecutive(['toto-dict-FR FR'], ['toto-dict-RU RU'], ['toto-dict-EN US'])
			->willReturnOnConsecutiveCalls([], [], false);

		$this->oApcService->expects($this->exactly(1))
			->method('apc_store')
			->withConsecutive(
				['toto-dict-EN US', ['label1' => 'en1', 'label2' => 'en2', 'label3' => 'en3']]
			);

		Dict::SetDefaultLanguage('RU RU');
		Dict::SetUserLanguage('FR FR');
		$this->assertEquals('en3', Dict::S('label3'));
	}

	public function testInitLangIfNeeded_ApcCorrupted_PropertyInDictDefaultLanguageDictionnary()
	{
		$this->oApcService->expects($this->any())
			->method('function_exists')
			->willReturn(true);

		$this->oApcService->expects($this->exactly(3))
			->method('apc_fetch')
			->withConsecutive(['toto-dict-FR FR'], ['toto-dict-RU RU'], ['toto-dict-EN US'])
			->willReturnOnConsecutiveCalls([], [], 'corrupteddata');

		$this->oApcService->expects($this->exactly(0))
			->method('apc_store');

		Dict::SetDefaultLanguage('RU RU');
		Dict::SetUserLanguage('FR FR');
		$this->assertEquals('label3', Dict::S('label3'));
	}

	public function testInitLangIfNeeded_Apc_PropertyNotFound()
	{
		$this->oApcService->expects($this->any())
			->method('function_exists')
			->willReturn(true);

		$this->oApcService->expects($this->exactly(3))
			->method('apc_fetch')
			->withConsecutive(['toto-dict-FR FR'], ['toto-dict-RU RU'], ['toto-dict-EN US'])
			->willReturnOnConsecutiveCalls([], [], []);

		$this->oApcService->expects($this->exactly(0))
			->method('apc_store');

		Dict::SetDefaultLanguage('RU RU');
		Dict::SetUserLanguage('FR FR');
		$this->assertEquals('undefined_label', Dict::S('undefined_label'));
	}
}
