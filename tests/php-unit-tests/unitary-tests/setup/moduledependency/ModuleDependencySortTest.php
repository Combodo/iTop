<?php

namespace Combodo\iTop\Test\Setup\ModuleDependency;

use Combodo\iTop\Setup\ModuleDependency\Module;
use Combodo\iTop\Setup\ModuleDependency\ModuleDependencySort;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use MissingDependencyException;

class ModuleDependencySortTest extends ItopTestCase
{
	public function setUp(): void
	{
		parent::setUp();

		$this->RequireOnceItopFile('setup/modulediscovery.class.inc.php');
		$this->RequireOnceItopFile('setup/moduledependency/moduledependencysort.class.inc.php');
	}

	public function testOrderModulesByDependencies_CheckExceptionWhenAllModuleUnresolved()
	{
		$aModules = [
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

		ModuleDependencySort::GetInstance()->GetModulesOrderedForInstallation($aModules, true);
	}

	public function testOrderModulesByDependencies_CheckExceptionWhenSomeModuleUnresolved()
	{
		$aModules = [
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
label3 (id: id3/789) depends on: ❌ id4/666,
label1 (id: id1/123) depends on: ❌ id4/666 + ❌ id3/789
MSG;
		$this->expectException(MissingDependencyException::class);
		$this->expectExceptionMessage($sExpectedMessage);

		ModuleDependencySort::GetInstance()->GetModulesOrderedForInstallation($aModules, true);
	}

	public function testOrderModulesByDependencies_CheckExceptionWhenCircularDependencies()
	{
		$aModules = [
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

		ModuleDependencySort::GetInstance()->GetModulesOrderedForInstallation($aModules, true);
	}

	public function testOrderModulesByDependencies_KeepGoingEvenWithFailure()
	{
		$aModules = [
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

		$aResult = ModuleDependencySort::GetInstance()->GetModulesOrderedForInstallation($aModules, false);

		$aExpected = [
			'id2/456',
		];

		$this->assertEquals($aExpected, array_keys($aResult));
	}

	public function testOrderModulesByDependencies_Nominalcase()
	{
		$aModules = [
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

		$aResult = ModuleDependencySort::GetInstance()->GetModulesOrderedForInstallation($aModules, true);

		$this->assertEquals($aExpected, array_keys($aResult));
	}

	//warning : tricky usecase
	public function testOrderModulesByDependencies_AllTermsOfOrExpressionWillImpactTheOrder()
	{
		$aModules = [
			"id0/1" => [
				'dependencies' => [ 'id2/2 || id1/1'],
				'label' => 'label1',
			],
			"id1/1" => [
				'dependencies' => [ 'id2/2'],
				'label' => 'label1',
			],
			"id2/2" => [
				'dependencies' => [],
				'label' => 'label2',
			],
		];

		$aExpected = [
			"id2/2",
			"id1/1",
			"id0/1",
		];

		$aResult = ModuleDependencySort::GetInstance()->GetModulesOrderedForInstallation($aModules, true);

		$this->assertEquals($aExpected, array_keys($aResult));
	}

	//WARNING: alphabetical order make setup are determinititic
	public function testOrderModulesByDependencies_ResolveNoDependendenciesOrderByAlphabeticalOrder()
	{
		$aModules = [
			"id2/2" => [
				'dependencies' => [],
				'label' => 'label2',
			],
			"id1/1" => [
				'dependencies' => [],
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

		$aResult = ModuleDependencySort::GetInstance()->GetModulesOrderedForInstallation($aModules, true);

		$this->assertEquals($aExpected, array_keys($aResult));
	}

	public function testOrderModulesByDependencies_AlphabeticalOrderWithDependencies()
	{
		$aModules = [
			"id2/2" => [
				'dependencies' => ["id1/1"],
				'label' => 'label2',
			],
			"id1/1" => [
				'dependencies' => [],
				'label' => 'label1',
			],
			"id3/3" => [
				'dependencies' => ["id1/1"],
				'label' => 'label3',
			],
		];

		$aExpected = [
			"id1/1",
			"id2/2",
			"id3/3",
		];

		$aResult = ModuleDependencySort::GetInstance()->GetModulesOrderedForInstallation($aModules, true);

		$this->assertEquals($aExpected, array_keys($aResult));
	}

	public function testOrderModulesByDependencies_AlphabeticalOrderWithDependencies2()
	{
		$aModules = [
			"z_id2/2" => [ //difference here
				'dependencies' => ["id1/1"],
				'label' => 'label2',
			],
			"id1/1" => [
				'dependencies' => [],
				'label' => 'label1',
			],
			"id3/3" => [
				'dependencies' => ["id1/1"],
				'label' => 'label3',
			],
		];

		$aExpected = [
			"id1/1",
			"id3/3",
			"z_id2/2",
		];

		$aResult = ModuleDependencySort::GetInstance()->GetModulesOrderedForInstallation($aModules, true);

		$this->assertEquals($aExpected, array_keys($aResult));
	}

	public function testSortModulesByCountOfDepencenciesDescending_NoDependencies()
	{
		$aUnresolvedDependencyModules = [];
		$this->AddModule($aUnresolvedDependencyModules, 'c', []);
		$this->AddModule($aUnresolvedDependencyModules, 'b', []);
		$this->AddModule($aUnresolvedDependencyModules, 'a', []);

		$this->SortModulesByCountOfDepencenciesDescending($aUnresolvedDependencyModules);
		$this->assertEquals(['a', 'b', 'c'], array_keys($aUnresolvedDependencyModules));
	}

	public function testSortModulesByCountOfDepencenciesDescending_NominalUseCase()
	{
		$aUnresolvedDependencyModules = [];
		$this->AddModule($aUnresolvedDependencyModules, 'itop-change-mgmt/456', ['itop-config-mgmt/2.2.0', 'itop-tickets/2.0.0']);
		$this->AddModule($aUnresolvedDependencyModules, 'itop-tickets/2.0.0', ['itop-structure/2.7.1']);
		$this->AddModule($aUnresolvedDependencyModules, 'itop-config-mgmt/123', ['itop-structure/2.7.1']);
		$this->AddModule($aUnresolvedDependencyModules, 'itop-structure/2.7.1', []);

		$this->SortModulesByCountOfDepencenciesDescending($aUnresolvedDependencyModules);
		$this->assertEquals(
			[
				'itop-structure/2.7.1',
				'itop-config-mgmt/123',
				'itop-tickets/2.0.0',
				'itop-change-mgmt/456',
			],
			array_keys($aUnresolvedDependencyModules)
		);
	}

	public function testSortModulesByCountOfDepencenciesDescending_NominalUseCaseWithMissingDependency()
	{
		$aUnresolvedDependencyModules = [];
		$this->AddModule($aUnresolvedDependencyModules, 'itop-change-mgmt/456', ['itop-config-mgmt/2.2.0', 'itop-tickets/2.0.0']);
		$this->AddModule($aUnresolvedDependencyModules, 'itop-tickets/2.0.0', ['itop-structure/2.7.1']);
		$this->AddModule($aUnresolvedDependencyModules, 'itop-config-mgmt/123', ['itop-structure/2.7.1']);

		$this->SortModulesByCountOfDepencenciesDescending($aUnresolvedDependencyModules);
		$this->assertEquals(
			[
				'itop-config-mgmt/123',
				'itop-tickets/2.0.0',
				'itop-change-mgmt/456',
			],
			array_keys($aUnresolvedDependencyModules)
		);
	}

	public function testSortModulesByCountOfDepencenciesDescending_FurtherVersionsOfSameModule()
	{
		$aUnresolvedDependencyModules = [];
		$this->AddModule($aUnresolvedDependencyModules, 'moduleA/1', []);
		$this->AddModule($aUnresolvedDependencyModules, 'moduleA/2', ['moduleC/1']);
		$this->AddModule($aUnresolvedDependencyModules, 'moduleB/1', ['moduleA/1']);
		$this->AddModule($aUnresolvedDependencyModules, 'moduleC/1', []);

		$this->SortModulesByCountOfDepencenciesDescending($aUnresolvedDependencyModules);
		$this->assertEquals(
			[
				'moduleA/1',
				'moduleC/1',
				'moduleA/2',
				'moduleB/1',
			],
			array_keys($aUnresolvedDependencyModules)
		);
	}

	private function AddModule(array &$aUnresolvedDependencyModules, string $sModuleId, array $aDeps)
	{
		$oModule = new Module($sModuleId);
		$oModule->SetDependencies($aDeps);
		$aUnresolvedDependencyModules[$sModuleId] = $oModule;
	}

	private function SortModulesByCountOfDepencenciesDescending(array &$aUnresolvedDependencyModules)
	{
		$this->InvokeNonPublicMethod(ModuleDependencySort::class, 'SortModulesByCountOfDepencenciesDescending', ModuleDependencySort::GetInstance(), [&$aUnresolvedDependencyModules]);
	}
}
