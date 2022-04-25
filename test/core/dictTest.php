<?php
// Copyright (c) 2010-2021 Combodo SARL
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


/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class dictTest extends ItopTestCase
{
	private $sEnvName;
	protected function setUp(): void
	{
		parent::setUp();

		require_once(APPROOT.'core'.DIRECTORY_SEPARATOR.'apc-service.class.inc.php');

		$this->sEnvName = time();
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
	}

	protected function tearDown(): void
	{
		foreach (glob(APPROOT."env-$this->sEnvName".DIRECTORY_SEPARATOR."dictionaries".DIRECTORY_SEPARATOR."*") as $sFile) {
			unlink($sFile);
		}
		rmdir(APPROOT."env-$this->sEnvName".DIRECTORY_SEPARATOR."dictionaries");
		rmdir(APPROOT."env-$this->sEnvName");
	}

	/**
	 * @throws Exception
	 */
	public function testType()
	{
		$_SESSION['itop_env'] = 'production';
		$this->assertInternalType('string', Dict::S('Core:AttributeURL'));
		$this->assertInternalType('string', Dict::Format('Change:AttName_SetTo', '1', '2'));
	}

	public function testInitLangIfNeeded_NoApc()
	{
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
