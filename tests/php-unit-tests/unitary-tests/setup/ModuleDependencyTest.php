<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use ModuleDependency;

class ModuleDependencyTest extends ItopTestCase
{
	public function setUp(): void {
		parent::setUp();
		$this->RequireOnceItopFile('setup/modulediscovery.class.inc.php');
	}

	public function testModuleDependencyInit_Invalid()
	{
		$oModuleDependency = new ModuleDependency('||');
		$this->assertEquals(true, $this->GetNonPublicProperty($oModuleDependency, 'bAlwaysUnresolved'));
	}

	public function testModuleDependencyInit()
	{
		$oModuleDependency = new ModuleDependency('itop-config-mgmt/2.4.0');
		$this->assertEquals(['itop-config-mgmt/2.4.0' => [ 'itop-config-mgmt',  '>=', '2.4.0']], $this->GetNonPublicProperty($oModuleDependency, 'aParamsPerModuleId'));
		$this->assertEquals(false, $this->GetNonPublicProperty($oModuleDependency, 'bAlwaysUnresolved'));
		$this->assertEquals(['itop-config-mgmt'], $oModuleDependency->GetPotentialPrerequisiteModuleNames());
	}

	public static function WithOperatorProvider()
	{
		$aUsecases=[];
		foreach (['>', '>=', '<', '<='] as $sOperator){
			$aUsecases[$sOperator]=[$sOperator];
		}
		return $aUsecases;
	}

	/**
	 * @dataProvider WithOperatorProvider
	 */
	public function testModuleDependencyInit_WithOperator($sOperator)
	{
		$sDepId = "itop-config-mgmt/{$sOperator}2.4.0";
		$oModuleDependency = new ModuleDependency($sDepId);
		$this->assertEquals([$sDepId => [ 'itop-config-mgmt',  $sOperator, '2.4.0']], $this->GetNonPublicProperty($oModuleDependency, 'aParamsPerModuleId'));
		$this->assertEquals(false, $this->GetNonPublicProperty($oModuleDependency, 'bAlwaysUnresolved'));
		$this->assertEquals(['itop-config-mgmt'], $oModuleDependency->GetPotentialPrerequisiteModuleNames());
	}

	public static function WithOperatorOperand()
	{
		$aUsecases=[];
		foreach (['&&', '||'] as $sOperand){
			$aUsecases[$sOperand]=[$sOperand, "itop-structure/3.0.0 $sOperand itop-portal/<3.2.1"];
			$aUsecases["$sOperand + parenthesis"]=[$sOperand, "(itop-structure/3.0.0 $sOperand itop-portal/<3.2.1)"];
		}
		return $aUsecases;
	}

	/**
	 * @dataProvider WithOperatorOperand
	 */
	public function testModuleDependencyInit_WithOperand($sOperand, $sDepId)
	{
		$sDepId = "itop-structure/3.0.0 $sOperand itop-portal/<3.2.1";
		$oModuleDependency = new ModuleDependency($sDepId);
		$this->assertEquals(['itop-structure/3.0.0' => [ 'itop-structure',  ">=", '3.0.0'], 'itop-portal/<3.2.1' => [ 'itop-portal',  "<", '3.2.1']], $this->GetNonPublicProperty($oModuleDependency, 'aParamsPerModuleId'));
		$this->assertEquals(false, $this->GetNonPublicProperty($oModuleDependency, 'bAlwaysUnresolved'));
		$this->assertEquals(['itop-structure', 'itop-portal'], $oModuleDependency->GetPotentialPrerequisiteModuleNames());
	}

	public function testModuleIsDependencyResolved_SimpleCase_UnresolvedDueToMissingModule()
	{
		$oModuleDependency = new ModuleDependency('itop-config-mgmt/2.4.0');
		$this->assertEquals(false, $oModuleDependency->IsDependencyResolved([], ['itop-config-mgmt' => true]));
	}

	public function testModuleIsDependencyResolved_SimpleCase_UnresolvedDueToWrongModuleVersion()
	{
		$oModuleDependency = new ModuleDependency('itop-config-mgmt/2.4.0');
		$this->assertEquals(false, $oModuleDependency->IsDependencyResolved(['itop-config-mgmt' => '1.2.3'], ['itop-config-mgmt' => true]));
	}

	public function testModuleIsDependencyResolved_SimpleCase_ResolvedDue_MinorVersion()
	{
		$oModuleDependency = new ModuleDependency('itop-config-mgmt/2.4.1');
		$this->assertEquals(true, $oModuleDependency->IsDependencyResolved(['itop-config-mgmt' => '2.4.1-1'], ['itop-config-mgmt' => true]));
	}

	public function testModuleIsDependencyResolved_SimpleCase_ResolvedDue_MinorVersion2()
	{
		$oModuleDependency = new ModuleDependency('itop-config-mgmt/2.4.1-1');
		$this->assertEquals(true, $oModuleDependency->IsDependencyResolved(['itop-config-mgmt' => '2.4.1-2'], ['itop-config-mgmt' => true]));
	}

	public function testModuleIsDependencyResolved_SimpleCase_ResolvedDue_MinorVersion3()
	{
		$oModuleDependency = new ModuleDependency('itop-config-mgmt/2.4.1-1');
		$this->assertEquals(true, $oModuleDependency->IsDependencyResolved(['itop-config-mgmt' => '2.4.2'], ['itop-config-mgmt' => true]));
	}

	public function testModuleIsDependencyResolved_SimpleCase_UnresolvedDueToWrongModuleVersion_MinorVersion()
	{
		$oModuleDependency = new ModuleDependency('itop-config-mgmt/2.4.1');
		$this->assertEquals(false, $oModuleDependency->IsDependencyResolved(['itop-config-mgmt' => '2.4.0-1'], ['itop-config-mgmt' => true]));
	}

	public function testModuleIsDependencyResolved_SimpleCase_UnresolvedDueToWrongModuleVersion_MinorVersion2()
	{
		$oModuleDependency = new ModuleDependency('itop-config-mgmt/2.4.1-1');
		$this->assertEquals(false, $oModuleDependency->IsDependencyResolved(['itop-config-mgmt' => '2.4.1'], ['itop-config-mgmt' => true]));
	}

	public function testModuleIsDependencyResolved_SimpleCase_UnresolvedDueToWrongModuleVersion_MinorVersion3()
	{
		$oModuleDependency = new ModuleDependency('itop-config-mgmt/2.4.1-1');
		$this->assertEquals(false, $oModuleDependency->IsDependencyResolved(['itop-config-mgmt' => '2.4.1-0'], ['itop-config-mgmt' => true]));
	}

	public function testModuleIsDependencyResolved_SimpleCase_Resolved()
	{
		$oModuleDependency = new ModuleDependency('itop-config-mgmt/2.4.0');
		$this->assertEquals(['itop-config-mgmt'], $oModuleDependency->GetPotentialPrerequisiteModuleNames());
		$this->assertEquals(true, $oModuleDependency->IsDependencyResolved(['itop-config-mgmt' => '2.4.1'], ['itop-config-mgmt' => true]));
		$this->assertEquals([], $oModuleDependency->GetPotentialPrerequisiteModuleNames());
	}

	public function testIsDependencyResolved_AndOperand_UnresolvedDueToMissingModule()
	{
		$sDepId = "itop-structure/3.0.0 && itop-portal/3.2.1";
		$oModuleDependency = new ModuleDependency($sDepId);
		$this->assertEquals(['itop-structure/3.0.0' => [ 'itop-structure',  ">=", '3.0.0'], 'itop-portal/3.2.1' => [ 'itop-portal',  ">=", '3.2.1']], $this->GetNonPublicProperty($oModuleDependency, 'aParamsPerModuleId'));
		$this->assertEquals(false, $this->GetNonPublicProperty($oModuleDependency, 'bAlwaysUnresolved'));
		$this->assertEquals(['itop-structure', 'itop-portal'], $oModuleDependency->GetPotentialPrerequisiteModuleNames());

		$this->assertEquals(false, $oModuleDependency->IsDependencyResolved(['itop-structure' => '3.0.0'], ['itop-structure' => true, 'itop-portal' => true]));
		$this->assertEquals(['itop-portal'], $oModuleDependency->GetPotentialPrerequisiteModuleNames());
	}

	public function testIsDependencyResolved_AndOperand_UnresolvedDueToWrongModuleVersion()
	{
		$sDepId = "itop-structure/3.0.0 && itop-portal/3.2.1";
		$oModuleDependency = new ModuleDependency($sDepId);
		$this->assertEquals(['itop-structure/3.0.0' => [ 'itop-structure',  ">=", '3.0.0'], 'itop-portal/3.2.1' => [ 'itop-portal',  ">=", '3.2.1']], $this->GetNonPublicProperty($oModuleDependency, 'aParamsPerModuleId'));
		$this->assertEquals(false, $this->GetNonPublicProperty($oModuleDependency, 'bAlwaysUnresolved'));
		$this->assertEquals(['itop-structure', 'itop-portal'], $oModuleDependency->GetPotentialPrerequisiteModuleNames());

		$this->assertEquals(false, $oModuleDependency->IsDependencyResolved(['itop-structure' => '3.0.0', 'itop-portal' => '1.0.0'], ['itop-structure' => true, 'itop-portal' => true]));
		$this->assertEquals(['itop-portal'], $oModuleDependency->GetPotentialPrerequisiteModuleNames());
	}

	public function testIsDependencyResolved_AndOperand_Resolved()
	{
		$sDepId = "itop-structure/3.0.0 && itop-portal/3.2.1";
		$oModuleDependency = new ModuleDependency($sDepId);
		$this->assertEquals(['itop-structure/3.0.0' => [ 'itop-structure',  ">=", '3.0.0'], 'itop-portal/3.2.1' => [ 'itop-portal',  ">=", '3.2.1']], $this->GetNonPublicProperty($oModuleDependency, 'aParamsPerModuleId'));
		$this->assertEquals(false, $this->GetNonPublicProperty($oModuleDependency, 'bAlwaysUnresolved'));
		$this->assertEquals(['itop-structure', 'itop-portal'], $oModuleDependency->GetPotentialPrerequisiteModuleNames());

		$this->assertEquals(false, $oModuleDependency->IsDependencyResolved(['itop-structure' => '3.0.0'], ['itop-structure' => true]));
		$this->assertEquals(['itop-portal'], $oModuleDependency->GetPotentialPrerequisiteModuleNames());
	}

	public function testIsDependencyResolved_OrOperand_ResolvedDueToMissingModule()
	{
		$sDepId = "itop-structure/3.0.0 || itop-portal/3.2.1";
		$oModuleDependency = new ModuleDependency($sDepId);
		$this->assertEquals(['itop-structure/3.0.0' => [ 'itop-structure',  ">=", '3.0.0'], 'itop-portal/3.2.1' => [ 'itop-portal',  ">=", '3.2.1']], $this->GetNonPublicProperty($oModuleDependency, 'aParamsPerModuleId'));
		$this->assertEquals(false, $this->GetNonPublicProperty($oModuleDependency, 'bAlwaysUnresolved'));
		$this->assertEquals(['itop-structure', 'itop-portal'], $oModuleDependency->GetPotentialPrerequisiteModuleNames());

		$this->assertEquals(true, $oModuleDependency->IsDependencyResolved(['itop-structure' => '3.0.0'], ['itop-structure' => true]));
		$this->assertEquals(['itop-portal'], $oModuleDependency->GetPotentialPrerequisiteModuleNames());
	}
}