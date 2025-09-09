<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use iTopCoreModuleDependencySort;
use ModuleDiscovery;

class ModuleDiscoveryTest extends ItopDataTestCase
{
	public function setUp(): void {
		parent::setUp();
		$this->RequireOnceItopFile('setup/modulediscovery.class.inc.php');
	}

	public function tearDown() : void {
		ModuleDiscovery::UseNewUiFeedback(false);
	}
	public static function OrderModulesByDependenciesProvider(){
		return [
			'legacy computation' => [0],
			'hybrid computation: order is legacy/ error is new usr friendly one' => [1],
			'new computation' => [2],
		];
	}

	/**
	 * @dataProvider OrderModulesByDependenciesProvider
	 */
	public function testOrderModulesByDependencies_CheckMissingDependenciesAreCorrectlyOrderedInTheException(int $mode)
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
		$iLoopCount=0;
		try{
			if ($mode===2){
				iTopCoreModuleDependencySort::OrderModulesByDependencies($aModules, true, null, $iLoopCount);
			} else {
				if ($mode===1){
					ModuleDiscovery::UseNewUiFeedback(true);
				}
				ModuleDiscovery::OrderModulesByDependencies($aModules, true, null);
			}
		} catch(\MissingDependencyException $e){
			if ($mode!==0) {
				$sExpectedMessage = <<<MSG
The following modules have unmet dependencies:
label2 (id: id2/456) depends on: ❌ id3/666,
label1 (id: id1/123) depends on: ❌ id3/666 + ❌ id4/666
MSG;
			} else {
				$sExpectedMessage = <<<MSG
The following modules have unmet dependencies:
label1 (id: id1/123) depends on: ❌ id3/666 + ❌ id4/666,
label2 (id: id2/456) depends on: ❌ id3/666
MSG;
			}

			$this->assertEquals($sExpectedMessage, $e->getMessage());
			if ($mode===2) {
				$this->assertEquals(1, $iLoopCount);
			}
		}
	}

	/**
	 * @dataProvider OrderModulesByDependenciesProvider
	 */
	public function testOrderModulesByDependencies_ValidateExceptionWithSomeDependenciesResolved(int $mode)
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
		$iLoopCount=0;
		try{
			if ($mode===2){
				iTopCoreModuleDependencySort::OrderModulesByDependencies($aModules, true, null, $iLoopCount);
			} else {
				if ($mode===1){
					ModuleDiscovery::UseNewUiFeedback(true);
				}
				ModuleDiscovery::OrderModulesByDependencies($aModules, true, null);
			}
		} catch(\MissingDependencyException $e){
			if ($mode!==0) {
				$sExpectedMessage = <<<MSG
The following modules have unmet dependencies:
label3 (id: id3/789) depends on: ✅ id2/456 + ❌ id4/666,
label1 (id: id1/123) depends on: ✅ id2/456 + ❌ id4/666 + ❌ id3/789
MSG;
			} else {
				$sExpectedMessage = <<<MSG
The following modules have unmet dependencies:
label1 (id: id1/123) depends on: ✅ id2/456 + ❌ id4/666 + ❌ id3/789,
label3 (id: id3/789) depends on: ✅ id2/456 + ❌ id4/666
MSG;
			}
			$this->assertEquals($sExpectedMessage, $e->getMessage());
			if ($mode===2) {
				$this->assertEquals(2, $iLoopCount);
			}
		}
	}

	/**
	 * @dataProvider OrderModulesByDependenciesProvider
	 */
	public function testOrderModulesByDependencies_KeepGoingEvenWithFailure_WithSomeDependenciesResolved(int $mode)
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
		$iLoopCount=0;
		if ($mode===2){
			$aResult = iTopCoreModuleDependencySort::OrderModulesByDependencies($aModules, false, null, $iLoopCount);
		} else {
			if ($mode===1){
				ModuleDiscovery::UseNewUiFeedback(true);
			}
			$aResult = ModuleDiscovery::OrderModulesByDependencies($aModules, false, null);
		}

		$aExpected = [
			'id2/456',
		];
		$this->assertEquals($aExpected, array_keys($aResult));
		if ($mode===2) {
			$this->assertEquals(2, $iLoopCount);
		}
	}

	/**
	 * @dataProvider OrderModulesByDependenciesProvider
	 */
	public function testOrderModulesByDependencies_UnResolveWithCircularDependency(int $mode)
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
		$iLoopCount=0;

		try{
			if ($mode===2){
				iTopCoreModuleDependencySort::OrderModulesByDependencies($aModules, true, null, $iLoopCount);
			} else {
				if ($mode===1){
					ModuleDiscovery::UseNewUiFeedback(true);
				}
				ModuleDiscovery::OrderModulesByDependencies($aModules, true, null);
			}
		} catch(\MissingDependencyException $e){
			if ($mode!==0) {
				$sExpectedMessage = <<<MSG
The following modules have unmet dependencies:
label1 (id: id1/1) depends on: ❌ id2/2,
label4 (id: id4/4) depends on: ❌ id1/1,
label3 (id: id3/3) depends on: ❌ id4/4,
label2 (id: id2/2) depends on: ❌ id3/3
MSG;
			} else {
				$sExpectedMessage = <<<MSG
The following modules have unmet dependencies:
label1 (id: id1/1) depends on: ❌ id2/2,
label2 (id: id2/2) depends on: ❌ id3/3,
label3 (id: id3/3) depends on: ❌ id4/4,
label4 (id: id4/4) depends on: ❌ id1/1
MSG;
			}
			$this->assertEquals($sExpectedMessage, $e->getMessage());
			if ($mode===2) {
				$this->assertEquals(1, $iLoopCount);
			}
		}
	}

	/**
	 * @dataProvider OrderModulesByDependenciesProvider
	 */
	public function testOrderModulesByDependencies_ResolveOk(int $mode)
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
		$iLoopCount=0;
		if ($mode===2){
			$aResult = iTopCoreModuleDependencySort::OrderModulesByDependencies($aModules, true, null, $iLoopCount);
		} else {
			if ($mode===1){
				ModuleDiscovery::UseNewUiFeedback(true);
			}
			$aResult = ModuleDiscovery::OrderModulesByDependencies($aModules, true, null);
		}

		$aExpected = [
			"id4/4",
			"id3/3",
			"id2/2",
			"id1/1",
			"id0/1",
		];
		$this->assertEquals($aExpected, array_keys($aResult));
		if ($mode===2) {
			$this->assertEquals(1, $iLoopCount);
		}
	}

	/**
	 * @dataProvider OrderModulesByDependenciesProvider
	 */
	public function testOrderModulesByDependencies_ResolveNoDependendenciesOrderByAlphabeticalOrder(int $mode)
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
		$iLoopCount=0;
		if ($mode===2){
			$aResult = iTopCoreModuleDependencySort::OrderModulesByDependencies($aModules, true, null, $iLoopCount);
		} else {
			if ($mode===1){
				ModuleDiscovery::UseNewUiFeedback(true);
			}
			$aResult = ModuleDiscovery::OrderModulesByDependencies($aModules, true, null);
		}

		$aExpected = [
			"id0/1",
			"id1/1",
			"id2/2",
			"id3/3",
			"id4/4",
		];
		$this->assertEquals($aExpected, array_keys($aResult));
		if ($mode===2) {
			$this->assertEquals(1, $iLoopCount);
		}
	}

	/**
	 * @dataProvider OrderModulesByDependenciesProvider
	 */
	public function testOrderModulesByDependencies_ResolveOk_ModulesToLoadProvided(int $mode)
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
			$iLoopCount = 0;
			if ($mode===2){
				$aResult = iTopCoreModuleDependencySort::OrderModulesByDependencies($aModules, true, ['id1', 'id2', $sLastModuleNameToLoad], $iLoopCount);
			} else {
				if ($mode===1){
					ModuleDiscovery::UseNewUiFeedback(true);
				}
				$aResult = ModuleDiscovery::OrderModulesByDependencies($aModules, true, ['id1', 'id2', $sLastModuleNameToLoad]);
			}

			$aExpected = [
				"$sLastModuleNameToLoad/3",
				"id2/2",
				"id1/1",
			];
			$this->assertEquals($aExpected, array_keys($aResult));
			if ($mode===2) {
				$this->assertEquals(1, $iLoopCount);
			}
		}
	}

	/*public function testOrderModulesByDependencies_RealExample(){
		$aModules = json_decode(file_get_contents(__DIR__ . '/ressources/module_deps.json'), true);
		$aResult = ModuleDiscovery::OrderModulesByDependencies($aModules, true, null);

		$aExpected = json_decode(file_get_contents(__DIR__ . '/ressources/expected_ordered_module_ids.json'), true);
		$this->assertEquals($aExpected, array_keys($aResult));
	}*/

	public function testOrderModulesByDependenciesNewwComputation_RealExample(){
		$aModules = json_decode(file_get_contents(__DIR__ . '/ressources/module_deps.json'), true);
		$iLoopCount=0;
		$aResult = iTopCoreModuleDependencySort::OrderModulesByDependencies($aModules, true, null, $iLoopCount);

		$aExpected = json_decode(file_get_contents(__DIR__ . '/ressources/expected_ordered_module_ids.json'), true);
		$this->assertEquals($aExpected, array_keys($aResult));
		$this->assertEquals(1, $iLoopCount);
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

		$aExpected = json_decode(file_get_contents(__DIR__ . '/ressources/expected_ordered_module_ids.json'), true);
		$this->assertEquals(
			$aExpected,
			array_keys($aUnresolvedDependencyModules));
	}
}