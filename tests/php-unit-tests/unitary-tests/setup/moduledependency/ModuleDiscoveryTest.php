<?php

namespace Combodo\iTop\Test\Setup\ModuleDependency;

use Combodo\iTop\Setup\ModuleDependency\Module;
use Combodo\iTop\Setup\ModuleDependency\ModuleDependencySort;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class ModuleDiscoveryTest extends ItopDataTestCase
{
	public function setUp(): void {
		parent::setUp();

		$this->RequireOnceItopFile('setup/moduledependency/moduledependencysort.class.inc.php');
	}

	public function testSortModulesByCountOfDepencenciesDescending_NoDependencies(){
		$aUnresolvedDependencyModules = [];
		foreach (['c', 'b', 'a'] as $sModuleId){
			$this->AddModule($aUnresolvedDependencyModules, $sModuleId, []);
		}
		ModuleDependencySort::GetInstance()->SortModulesByCountOfDepencenciesDescending($aUnresolvedDependencyModules);
		$this->assertEquals(['a', 'b', 'c'], array_keys($aUnresolvedDependencyModules));
	}

	public function testSortModulesByCountOfDepencenciesDescending_NominalUseCase(){
		$aUnresolvedDependencyModules = [];
		$this->AddModule($aUnresolvedDependencyModules, 'itop-change-mgmt/456', ['itop-config-mgmt/2.2.0', 'itop-tickets/2.0.0']);
		$this->AddModule($aUnresolvedDependencyModules, 'itop-tickets/2.0.0', ['itop-structure/2.7.1']);
		$this->AddModule($aUnresolvedDependencyModules, 'itop-config-mgmt/123', ['itop-structure/2.7.1']);
		$this->AddModule($aUnresolvedDependencyModules, 'itop-structure/2.7.1', []);

		ModuleDependencySort::GetInstance()->SortModulesByCountOfDepencenciesDescending($aUnresolvedDependencyModules);
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

		ModuleDependencySort::GetInstance()->SortModulesByCountOfDepencenciesDescending($aUnresolvedDependencyModules);
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

		ModuleDependencySort::GetInstance()->SortModulesByCountOfDepencenciesDescending($aUnresolvedDependencyModules);
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
		$oModule = new Module($sModuleId);
		$oModule->SetDependencies($aDeps);
		$aUnresolvedDependencyModules[$sModuleId]= $oModule;
	}

	public function testSortModulesByCountOfDepencenciesDescending_RealExample(){
		$aUnresolvedDependencyModules = [];
		$aDependencies = json_decode(file_get_contents(__DIR__ . '/ressources/module_deps.json'), true);
		foreach ($aDependencies as $sModuleId => $aModuleData){
			$this->AddModule($aUnresolvedDependencyModules, $sModuleId, $aModuleData['dependencies']);
		}

		ModuleDependencySort::GetInstance()->SortModulesByCountOfDepencenciesDescending($aUnresolvedDependencyModules);

		$aExpected = json_decode(file_get_contents(__DIR__ . '/ressources/expected_ordered_module_ids2.json'), true);
		$this->assertEquals(
			$aExpected,
			array_keys($aUnresolvedDependencyModules));
	}
}