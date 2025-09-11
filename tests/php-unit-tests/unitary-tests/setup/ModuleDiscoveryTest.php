<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use iTopCoreModuleDependencySort;
use MissingDependencyException;
use ModuleDiscovery;

class ModuleDiscoveryTest extends ItopDataTestCase
{
	public function setUp(): void {
		parent::setUp();
		$this->RequireOnceItopFile('setup/modulediscovery.class.inc.php');
	}

	public static function CheckMissingDependenciesAreCorrectlyOrderedInTheExceptionProvider()
	{
		$sLegacyExpectedMessage = <<<MSG
The following modules have unmet dependencies:
label1 (id: id1/123) depends on: ❌ id3/666 + ❌ id4/666,
label2 (id: id2/456) depends on: ❌ id3/666
MSG;
		$sExpectedMessage = <<<MSG
The following modules have unmet dependencies:
label2 (id: id2/456) depends on: ❌ id3/666,
label1 (id: id1/123) depends on: ❌ id3/666 + ❌ id4/666
MSG;

		return [
			'legacy' => [ 'message' => $sLegacyExpectedMessage, 'is_legacy' => true ],
			'new computation' => [ 'message' => $sExpectedMessage, 'is_legacy' => false ],
		];
	}

	/**
	 * @dataProvider CheckMissingDependenciesAreCorrectlyOrderedInTheExceptionProvider
	 */
	public function testOrderModulesByDependencies_CheckMissingDependenciesAreCorrectlyOrderedInTheException(string $sMessage, bool $bIsLegacy)
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

		$this->expectException(MissingDependencyException::class);
		$this->expectExceptionMessage($sMessage);

		if ($bIsLegacy){
			ModuleDiscovery::OrderModulesByDependencies($aModules, true, null);
		} else {
			iTopCoreModuleDependencySort::OrderModulesByDependencies($aModules, true, null);
		}
	}

	public static function ValidateExceptionWithSomeDependenciesResolvedProvider()
	{
		$sExpectedMessage = <<<MSG
The following modules have unmet dependencies:
label3 (id: id3/789) depends on: ✅ id2/456 + ❌ id4/666,
label1 (id: id1/123) depends on: ✅ id2/456 + ❌ id4/666 + ❌ id3/789
MSG;
		$sLegacyExpectedMessage = <<<MSG
The following modules have unmet dependencies:
label1 (id: id1/123) depends on: ✅ id2/456 + ❌ id4/666 + ❌ id3/789,
label3 (id: id3/789) depends on: ✅ id2/456 + ❌ id4/666
MSG;

		return [
			'legacy' => [ 'message' => $sLegacyExpectedMessage, 'is_legacy' => true ],
			'new computation' => [ 'message' => $sExpectedMessage, 'is_legacy' => false ],
		];
	}

	/**
	 * @dataProvider ValidateExceptionWithSomeDependenciesResolvedProvider
	 */
	public function testOrderModulesByDependencies_ValidateExceptionWithSomeDependenciesResolved(string $sMessage, bool $bIsLegacy)
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

		$this->expectException(MissingDependencyException::class);
		$this->expectExceptionMessage($sMessage);

		if ($bIsLegacy){
			ModuleDiscovery::OrderModulesByDependencies($aModules, true, null);
		} else {
			iTopCoreModuleDependencySort::OrderModulesByDependencies($aModules, true, null);
		}
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

		$aResult = iTopCoreModuleDependencySort::OrderModulesByDependencies($aModules, false, null);
		$aLegacyResult = ModuleDiscovery::OrderModulesByDependencies($aModules, false, null);

		$aExpected = [
			'id2/456',
		];

		$this->assertEquals($aExpected, array_keys($aLegacyResult));
		$this->assertEquals( $aLegacyResult, $aResult);
	}

	public static function UnResolveWithCircularDependencyProvider()
	{
		$sExpectedMessage = <<<MSG
The following modules have unmet dependencies:
label1 (id: id1/1) depends on: ❌ id2/2,
label4 (id: id4/4) depends on: ❌ id1/1,
label3 (id: id3/3) depends on: ❌ id4/4,
label2 (id: id2/2) depends on: ❌ id3/3
MSG;
		$sLegacyExpectedMessage = <<<MSG
The following modules have unmet dependencies:
label1 (id: id1/1) depends on: ❌ id2/2,
label2 (id: id2/2) depends on: ❌ id3/3,
label3 (id: id3/3) depends on: ❌ id4/4,
label4 (id: id4/4) depends on: ❌ id1/1
MSG;

		return [
			'legacy' => [ 'message' => $sLegacyExpectedMessage, 'is_legacy' => true ],
			'new computation' => [ 'message' => $sExpectedMessage, 'is_legacy' => false ],
		];
	}

	/**
	 * @dataProvider UnResolveWithCircularDependencyProvider
	 */
	public function testOrderModulesByDependencies_UnResolveWithCircularDependency(string $sMessage, bool $bIsLegacy)
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

		$this->expectException(MissingDependencyException::class);
		$this->expectExceptionMessage($sMessage);

		if ($bIsLegacy){
			ModuleDiscovery::OrderModulesByDependencies($aModules, true, null);
		} else {
			iTopCoreModuleDependencySort::OrderModulesByDependencies($aModules, true, null);
		}
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

		$aResult = iTopCoreModuleDependencySort::OrderModulesByDependencies($aModules, true, null);
		$aLegacyResult = ModuleDiscovery::OrderModulesByDependencies($aModules, true, null);

		$this->assertEquals($aExpected, array_keys($aLegacyResult));
		$this->assertEquals( $aLegacyResult, $aResult);
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

		$aResult = iTopCoreModuleDependencySort::OrderModulesByDependencies($aModules, true, null);
		$aLegacyResult = ModuleDiscovery::OrderModulesByDependencies($aModules, true, null);

		$this->assertEquals($aExpected, array_keys($aLegacyResult));
		$this->assertEquals( $aLegacyResult, $aResult);
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

			$aResult = iTopCoreModuleDependencySort::OrderModulesByDependencies($aModules, true, ['id1', 'id2', $sLastModuleNameToLoad]);
			$aLegacyResult = ModuleDiscovery::OrderModulesByDependencies($aModules, true, ['id1', 'id2', $sLastModuleNameToLoad]);

			$this->assertEquals($aExpected, array_keys($aLegacyResult));
			$this->assertEquals( $aLegacyResult, $aResult);
		}
	}

	public function testOrderModulesByDependenciesNewComputation_RealExample(){
		$aModules = json_decode(file_get_contents(__DIR__ . '/ressources/module_deps.json'), true);

		$aResult = iTopCoreModuleDependencySort::OrderModulesByDependencies($aModules, true, null);
		$aLegacyResult = ModuleDiscovery::OrderModulesByDependencies($aModules, true, null);

		$aExpected = json_decode(file_get_contents(__DIR__ . '/ressources/expected_ordered_module_ids.json'), true);
		$this->assertEquals( $aLegacyResult, $aResult);
		$this->assertEquals($aExpected, array_keys($aLegacyResult));
	}

	public function testSortModulesByCountOfDepencenciesDescending_NoDependencies(){
		$aUnresolvedDependencyModules = [];
		foreach (['c', 'b', 'a'] as $sModuleId){
			$this->AddModule($aUnresolvedDependencyModules, $sModuleId, []);
		}
		iTopCoreModuleDependencySort::GetInstance()->SortModulesByCountOfDepencenciesDescending($aUnresolvedDependencyModules);
		$this->assertEquals(['a', 'b', 'c'], array_keys($aUnresolvedDependencyModules));
	}

	public function testSortModulesByCountOfDepencenciesDescending_NominalUseCase(){
		$aUnresolvedDependencyModules = [];
		$this->AddModule($aUnresolvedDependencyModules, 'itop-change-mgmt/456', ['itop-config-mgmt/2.2.0', 'itop-tickets/2.0.0']);
		$this->AddModule($aUnresolvedDependencyModules, 'itop-tickets/2.0.0', ['itop-structure/2.7.1']);
		$this->AddModule($aUnresolvedDependencyModules, 'itop-config-mgmt/123', ['itop-structure/2.7.1']);
		$this->AddModule($aUnresolvedDependencyModules, 'itop-structure/2.7.1', []);

		iTopCoreModuleDependencySort::GetInstance()->SortModulesByCountOfDepencenciesDescending($aUnresolvedDependencyModules);
		$this->assertEquals(
			[
				'itop-structure/2.7.1',
				'itop-config-mgmt/123',
				'itop-tickets/2.0.0',
				'itop-change-mgmt/456',
			], array_keys($aUnresolvedDependencyModules));
	}

	public function testSortModulesByCountOfDepencenciesDescending_NominalUseCaseWithMissingDependency(){
		$aUnresolvedDependencyModules = [];
		$this->AddModule($aUnresolvedDependencyModules, 'itop-change-mgmt/456', ['itop-config-mgmt/2.2.0', 'itop-tickets/2.0.0']);
		$this->AddModule($aUnresolvedDependencyModules, 'itop-tickets/2.0.0', ['itop-structure/2.7.1']);
		$this->AddModule($aUnresolvedDependencyModules, 'itop-config-mgmt/123', ['itop-structure/2.7.1']);

		iTopCoreModuleDependencySort::GetInstance()->SortModulesByCountOfDepencenciesDescending($aUnresolvedDependencyModules);
		$this->assertEquals(
			[
				'itop-config-mgmt/123',
				'itop-tickets/2.0.0',
				'itop-change-mgmt/456',
			],
			array_keys($aUnresolvedDependencyModules));
	}

	public function testSortModulesByCountOfDepencenciesDescending_FurtherVersionsOfSameModule(){
		$aUnresolvedDependencyModules = [];
		$this->AddModule($aUnresolvedDependencyModules, 'moduleA/1', []);
		$this->AddModule($aUnresolvedDependencyModules, 'moduleA/2', ['moduleC/1']);
		$this->AddModule($aUnresolvedDependencyModules, 'moduleB/1', ['moduleA/1']);
		$this->AddModule($aUnresolvedDependencyModules, 'moduleC/1', []);

		iTopCoreModuleDependencySort::GetInstance()->SortModulesByCountOfDepencenciesDescending($aUnresolvedDependencyModules);
		$this->assertEquals(
			[
				'moduleA/1',
				'moduleC/1',
				'moduleA/2',
				'moduleB/1',
			],
			array_keys($aUnresolvedDependencyModules));
	}

	private function AddModule(array &$aUnresolvedDependencyModules, string $sModuleId, array $aDeps){
		$oModule = new \iTopCoreModule($sModuleId);
		$oModule->SetDependencies($aDeps);
		$aUnresolvedDependencyModules[$sModuleId]= $oModule;
	}

	public function testSortModulesByCountOfDepencenciesDescending_RealExample(){
		$aUnresolvedDependencyModules = [];
		$aDependencies = json_decode(file_get_contents(__DIR__ . '/ressources/module_deps.json'), true);
		foreach ($aDependencies as $sModuleId => $aModuleData){
			$this->AddModule($aUnresolvedDependencyModules, $sModuleId, $aModuleData['dependencies']);
		}

		iTopCoreModuleDependencySort::GetInstance()->SortModulesByCountOfDepencenciesDescending($aUnresolvedDependencyModules);

		$aExpected = json_decode(file_get_contents(__DIR__ . '/ressources/expected_ordered_module_ids2.json'), true);
		$this->assertEquals(
			$aExpected,
			array_keys($aUnresolvedDependencyModules));
	}
}