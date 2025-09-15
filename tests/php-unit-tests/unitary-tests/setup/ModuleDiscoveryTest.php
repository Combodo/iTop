<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MissingDependencyException;
use ModuleDiscovery;

class ModuleDiscoveryTest extends ItopDataTestCase
{
	public function setUp(): void {
		parent::setUp();
		$this->RequireOnceItopFile('setup/modulediscovery.class.inc.php');
	}

	public function testOrderModulesByDependencies_CheckMissingDependenciesAreCorrectlyOrderedInTheException()
	{
		$aModules=[
			"id1/123" => [
				'dependencies' => [ 'id3/666', 'id4/666'],
				'label' => 'label1',
			],
			"id2/456" => [
				'dependencies' => ['id3/666'],
				'label' => 'label2',
			],
		];

		$sExpectedMessage = <<<MSG
The following modules have unmet dependencies:
label2 (id: id2/456) depends on: ❌ id3/666,
label1 (id: id1/123) depends on: ❌ id3/666 + ❌ id4/666
MSG;
		$this->expectException(MissingDependencyException::class);
		$this->expectExceptionMessage($sExpectedMessage);

		ModuleDiscovery::OrderModulesByDependencies($aModules, true, null);
	}

	public function testOrderModulesByDependencies_ValidateExceptionWithSomeDependenciesResolved()
	{
		$aModules=[
			"id1/123" => [
				'dependencies' => [ 'id2/456', 'id4/666', 'id3/789'],
				'label' => 'label1',
			],
			"id2/456" => [
				'dependencies' => [],
				'label' => 'label2',
			],
			"id3/789" => [
				'dependencies' => [ 'id2/456', 'id4/666'],
				'label' => 'label3',
			],
		];

		$sExpectedMessage = <<<MSG
The following modules have unmet dependencies:
label3 (id: id3/789) depends on: ✅ id2/456 + ❌ id4/666,
label1 (id: id1/123) depends on: ✅ id2/456 + ❌ id4/666 + ❌ id3/789
MSG;
		$this->expectException(MissingDependencyException::class);
		$this->expectExceptionMessage($sExpectedMessage);

		ModuleDiscovery::OrderModulesByDependencies($aModules, true, null);
	}

	public function testOrderModulesByDependencies_KeepGoingEvenWithFailure_WithSomeDependenciesResolved()
	{
		$aModules=[
			"id1/123" => [
				'dependencies' => [ 'id2/456', 'id4/666', 'id3/789'],
				'label' => 'label1',
			],
			"id2/456" => [
				'dependencies' => [],
				'label' => 'label2',
			],
			"id3/789" => [
				'dependencies' => [ 'id2/456', 'id4/666'],
				'label' => 'label3',
			],
		];

		$aResult = ModuleDiscovery::OrderModulesByDependencies($aModules, false, null);

		$aExpected = [
			'id2/456',
		];

		$this->assertEquals($aExpected, array_keys($aResult));
	}

	public function testOrderModulesByDependencies_UnResolveWithCircularDependency()
	{
		$aModules=[
			"id1/1" => [
				'dependencies' => [ 'id2/2'],
				'label' => 'label1',
			],
			"id2/2" => [
				'dependencies' => ['id3/3'],
				'label' => 'label2',
			],
			"id3/3" => [
				'dependencies' => ['id4/4'],
				'label' => 'label3',
			],
			"id4/4" => [
				'dependencies' => ['id1/1'],
				'label' => 'label4',
			],
		];

		$sExpectedMessage = <<<MSG
The following modules have unmet dependencies:
label1 (id: id1/1) depends on: ❌ id2/2,
label4 (id: id4/4) depends on: ❌ id1/1,
label3 (id: id3/3) depends on: ❌ id4/4,
label2 (id: id2/2) depends on: ❌ id3/3
MSG;
		$this->expectException(MissingDependencyException::class);
		$this->expectExceptionMessage($sExpectedMessage);

		ModuleDiscovery::OrderModulesByDependencies($aModules, true, null);
	}

	public function testOrderModulesByDependencies_ResolveOk()
	{
		$aModules=[
			"id0/1" => [
				'dependencies' => [ 'id2/2 || id1/1'],
				'label' => 'label1',
			],
			"id1/1" => [
				'dependencies' => [ 'id2/2'],
				'label' => 'label1',
			],
			"id2/2" => [
				'dependencies' => ['id3/3'],
				'label' => 'label2',
			],
			"id3/3" => [
				'dependencies' => ['id4/4'],
				'label' => 'label3',
			],
			"id4/4" => [
				'dependencies' => [],
				'label' => 'label4',
			],
		];

		$aExpected = [
			"id4/4",
			"id3/3",
			"id2/2",
			"id1/1",
			"id0/1",
		];

		$aResult = ModuleDiscovery::OrderModulesByDependencies($aModules, true, null);

		$this->assertEquals($aExpected, array_keys($aResult));
	}

	public function testOrderModulesByDependencies_ResolveOk2()
	{
		$aModules=[
			"id0/1" => [
				'dependencies' => [ 'id2/2'],
				'label' => 'label1',
			],
			"id1/1" => [
				'dependencies' => [ 'id2/2'],
				'label' => 'label1',
			],
			"id2/2" => [
				'dependencies' => ['id3/3'],
				'label' => 'label2',
			],
			"id3/3" => [
				'dependencies' => ['id4/4'],
				'label' => 'label3',
			],
			"id4/4" => [
				'dependencies' => [],
				'label' => 'label4',
			],
		];

		$aExpected = [
			"id4/4",
			"id3/3",
			"id2/2",
			"id0/1",
			"id1/1",
		];

		$aResult = ModuleDiscovery::OrderModulesByDependencies($aModules, true, null);

		$this->assertEquals($aExpected, array_keys($aResult));
	}


	public function testOrderModulesByDependencies_ResolveNoDependendenciesOrderByAlphabeticalOrder()
	{
		$aModules=[
			"id2/2" => [
				'dependencies' => [],
				'label' => 'label2',
			],
			"id1/1" => [
				'dependencies' => [ ],
				'label' => 'label1',
			],
			"id3/3" => [
				'dependencies' => [],
				'label' => 'label3',
			],
			"id4/4" => [
				'dependencies' => [],
				'label' => 'label4',
			],
			"id0/1" => [
				'dependencies' => [],
				'label' => 'label0',
			],
		];

		$aExpected = [
			"id0/1",
			"id1/1",
			"id2/2",
			"id3/3",
			"id4/4",
		];

		$aResult = ModuleDiscovery::OrderModulesByDependencies($aModules, true, null);

		$this->assertEquals($aExpected, array_keys($aResult));
	}

	public function testOrderModulesByDependencies_ResolveOk_ModulesToLoadProvided()
	{
		$aModules=[
			"id1/1" => [
				'dependencies' => [ 'id2/2'],
				'label' => 'label1',
			],
			"id2/2" => [
				'dependencies' => ['id3/3 || id3-itil/3'],
				'label' => 'label2',
			],
			"id3/3" => [
				'dependencies' => [],
				'label' => 'label3',
			],
			"id3-itil/3" => [
				'dependencies' => [],
				'label' => 'label3-itil',
			],
		];

		foreach(["id3", "id3-itil"] as $sLastModuleNameToLoad) {
			$aExpected = [
				"$sLastModuleNameToLoad/3",
				"id2/2",
				"id1/1",
			];

			$aResult = ModuleDiscovery::OrderModulesByDependencies($aModules, true, ['id1', 'id2', $sLastModuleNameToLoad]);

			$this->assertEquals($aExpected, array_keys($aResult));
		}
	}

	public function testOrderModulesByDependenciesNewComputation_RealExample(){
		$aModules = json_decode(file_get_contents(__DIR__ . '/ressources/module_deps.json'), true);

		$aResult = ModuleDiscovery::OrderModulesByDependencies($aModules, true, null);

		$aExpected = json_decode(file_get_contents(__DIR__ . '/ressources/expected_ordered_module_ids.json'), true);
		$this->assertEquals($aExpected, array_keys($aResult));
	}
}