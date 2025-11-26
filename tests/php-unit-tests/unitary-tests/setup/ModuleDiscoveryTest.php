<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use MissingDependencyException;
use ModuleDiscovery;

class ModuleDiscoveryTest extends ItopTestCase
{
	public function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('setup/runtimeenv.class.inc.php');
		$this->RequireOnceItopFile('setup/modulediscovery.class.inc.php');
	}

	public function testOrderModulesByDependencies_RealExample()
	{
		$aModules = json_decode(file_get_contents(__DIR__.'/ressources/reallife_discovered_modules.json'), true);

		$aResult = ModuleDiscovery::OrderModulesByDependencies($aModules, true);

		$aExpected = json_decode(file_get_contents(__DIR__.'/ressources/reallife_expected_ordered_modules.json'), true);
		$this->assertEquals($aExpected, array_keys($aResult));
	}

	public function testOrderModulesByDependencies_LoadOnlyChoosenModules()
	{
		$aChoices = ['id1', 'id2'];

		$aModules = [
			"id1/1" => [
				'dependencies' => [ 'id2/2'],
				'label' => 'label1',
			],
			"id2/2" => [
				'dependencies' => [],
				'label' => 'label2',
			],
			"id3/3" => [
				'dependencies' => [],
				'label' => 'label3',
			],
		];

		$aResult = ModuleDiscovery::OrderModulesByDependencies($aModules, true, $aChoices);

		$aExpected = [
			"id2/2",
			"id1/1",
		];
		$this->assertEquals($aExpected, array_keys($aResult));
	}

	public function testOrderModulesByDependencies_FailWhenChoosenModuleDependsOnUnchoosenModule()
	{
		$aChoices = ['id1'];

		$aModules = [
			"id1/1" => [
				'dependencies' => [ 'id2/2'],
				'label' => 'label1',
			],
			"id2/2" => [
				'dependencies' => [],
				'label' => 'label2',
			],
		];

		$sExpectedMessage = <<<TXT
The following modules have unmet dependencies:
label1 (id: id1/1) depends on: ❌ id2/2
TXT;
		$this->expectException(MissingDependencyException::class);
		$this->expectExceptionMessage($sExpectedMessage);

		ModuleDiscovery::OrderModulesByDependencies($aModules, true, $aChoices);
	}
}
