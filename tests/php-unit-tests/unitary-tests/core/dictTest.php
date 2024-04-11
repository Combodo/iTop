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
use Exception;

class dictTest extends ItopTestCase
{
	private $sEnvName;
	protected function setUp(): void
	{
		parent::setUp();

		$this->RequireOnceItopFile('core'.DIRECTORY_SEPARATOR.'apc-service.class.inc.php');

		// This id will be used as path to the dictionary files
		// It must be unique enough for the magic of Dict to operate (due to the use of require_once to load dictionaries)
		$this->sEnvName = uniqid();
		$sDictionaryFolder = APPROOT."env-$this->sEnvName".DIRECTORY_SEPARATOR."dictionaries";
		@mkdir($sDictionaryFolder, 0777, true);

		$sContent = <<<PHP
<?php
//
// Dictionary built by the compiler for the language "FR FR"
//
Dict::SetEntries('FR FR', array(
        'label1' => 'gabu',
));
PHP;
		file_put_contents($sDictionaryFolder.DIRECTORY_SEPARATOR."fr-fr.dict.php", $sContent);
		$sContent = <<<PHP
<?php
//
// Dictionary built by the compiler for the language "FR FR"
//
Dict::SetEntries('EN EN', array(
        'label1' => 'zomeu',
));
PHP;
		file_put_contents($sDictionaryFolder.DIRECTORY_SEPARATOR."en-en.dict.php", $sContent);

		$_SESSION['itop_env'] = $this->sEnvName;

		// Preserve the dictionary for test that will be executed later on
		static::BackupStaticProperties('Dict');
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

	/**
	 * @throws Exception
	 */
	public function testType()
	{
		$_SESSION['itop_env'] = 'production';
		$this->assertIsString(Dict::S('Core:AttributeURL'));
		$this->assertIsString(Dict::Format('Change:AttName_SetTo', '1', '2'));
	}

	public function testInitLangIfNeeded_NoApc()
	{
		// Reset the dictionary
		static::SetNonPublicStaticProperty('Dict', 'm_aData', []);

		$oApcService = $this->createMock(\ApcService::class);
		Dict::SetApcService($oApcService);
		Dict::EnableCache('toto');

		$oApcService->expects($this->any())
			->method('function_exists')
			->willReturn(false);

		$oApcService->expects($this->never())
			->method('apc_fetch')
			->willReturn(false);

		$oApcService->expects($this->never())
			->method('apc_store')
			->willReturn(false);

		Dict::SetLanguagesList(['FR FR' => 'fr', 'EN EN' => 'en']);
		Dict::SetUserLanguage('FR FR');
		$this->assertEquals('gabu', Dict::S('label1'));
		Dict::SetUserLanguage('EN EN');
		$this->assertEquals('zomeu', Dict::S('label1'));
	}
}
