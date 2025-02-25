<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use Combodo\iTop\Test\UnitTest\ItopTestCase;

class ModuleDiscoveryTest extends ItopTestCase
{
	public function setUp(): void {
		parent::setUp();
		$this->RequireOnceItopFile('setup/modulediscovery.class.inc.php');
	}

	public function testSortModulesByCountOfDepencenciesDescending()
	{
		$aOngoingDependencies=[];
		$aExpectedKeys=[];
		for($i=5; $i>0; $i--){
			$sKey = "k$i";
			$aExpectedKeys[]=$sKey;
			$aDeps=[];
			for ($j=0; $j<$i; $j++){
				$aDeps[]=$j;
			}
			$aOngoingDependencies[$sKey]=$aDeps;
		}
		sort($aExpectedKeys);

		\ModuleDiscovery::SortModulesByCountOfDepencenciesDescending($aOngoingDependencies);

		$this->assertEquals($aExpectedKeys, array_keys($aOngoingDependencies));
	}

	public function testOrderModulesByDependencies_CheckMissingDependenciesAreCorrectlyOrderedInTheException()
	{
		$sExpectedMessage = <<<MSG
The following modules have unmet dependencies:
label2 (id: id2/456) depends on: ❌ id3/666,
label1 (id: id1/123) depends on: ❌ id3/666 + ❌ id4/666
MSG;

		$this->expectExceptionMessage($sExpectedMessage);

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
		\ModuleDiscovery::OrderModulesByDependencies($aModules, true);
	}

	public function testOrderModulesByDependencies_ValidateExceptionWithSomeDependenciesResolved()
	{
		$sExpectedMessage = <<<MSG
The following modules have unmet dependencies:
label1 (id: id1/123) depends on: ✅ id2/456 + ❌ id4/666
MSG;

		$this->expectExceptionMessage($sExpectedMessage);

		$aModules=[
			"id1/123" => [
				'dependencies' => [ 'id2/456', 'id4/666'],
				'label' => 'label1',
			],
			"id2/456" => [
				'dependencies' => [],
				'label' => 'label2',
			],
		];
		\ModuleDiscovery::OrderModulesByDependencies($aModules, true);
	}

	public function testOrderModulesByDependencies_ResolveOk()
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
				'dependencies' => [],
				'label' => 'label4',
			],
		];
		$aResult = \ModuleDiscovery::OrderModulesByDependencies($aModules, true);

		$aExpected = [
			"id4/4",
			"id3/3",
			"id2/2",
			"id1/1",
		];
		$this->assertEquals($aExpected, array_keys($aResult));
	}
}