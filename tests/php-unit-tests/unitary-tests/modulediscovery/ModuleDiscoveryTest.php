<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use Combodo\iTop\Test\UnitTest\ItopTestCase;

class ModuleDiscoveryTest extends ItopTestCase
{
	public function GetModuleNameProvider()
	{
		return [
			'nominal' => [
				'sModuleId' => 'a/1.2.3',
				'name' => 'a',
				'version' => '1.2.3',
			],
			'develop' => [
				'sModuleId' => 'a/1.2.3-dev',
				'name' => 'a',
				'version' => '1.2.3-dev',
			],
			'missing version => 1.0.0' => [
				'sModuleId' => 'a/',
				'name' => 'a',
				'version' => '1.0.0',
			],
			'missing everything except name' => [
				'sModuleId' => 'a',
				'name' => 'a',
				'version' => '1.0.0',
			],
		];
	}

	protected function setUp(): void
	{
		parent::setUp();

		$this->RequireOnceItopFile('setup/modulediscovery.class.inc.php');
	}

	/**
	 * @dataProvider GetModuleNameProvider
	 */
	public function testGetModuleName($sModuleId, $expectedName, $expectedVersion)
	{
		$this->assertEquals([$expectedName, $expectedVersion], \ModuleDiscovery::GetModuleName($sModuleId));
	}

}
