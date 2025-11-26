<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use Combodo\iTop\Setup\ModuleDependency\DependencyExpression;
use Combodo\iTop\Test\UnitTest\ItopTestCase;

class DependencyExpressionTest extends ItopTestCase
{
	public function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('setup/moduledependency/dependencyexpression.class.inc.php');
	}

	public function testModuleDependencyInit_Invalid()
	{
		$oModuleDependency = new DependencyExpression('||');
		$this->assertFalse($oModuleDependency->IsValid());
		$this->assertFalse($oModuleDependency->IsResolved());
	}

	public static function WithOperatorProvider()
	{
		return [
			"nominal case" => [
				"dep" => "itop-config-mgmt/2.4.0",
				'expected_operator' => '>='
			],
			">" => [
				"dep" => "itop-config-mgmt/>2.4.0",
				'expected_operator' => '>'
			],
			">=" => [
				"dep" => "itop-config-mgmt/>=2.4.0",
				'expected_operator' => '>='
			],
			"<" => [
				"dep" => "itop-config-mgmt/<2.4.0",
				'expected_operator' => '<'
			],
			"<=" => [
				"dep" => "itop-config-mgmt/<=2.4.0",
				'expected_operator' => '<='
			],
		];
	}

	/**
	 * @dataProvider WithOperatorProvider
	 */
	public function testModuleDependencyInit_WithOperator($sDepId, $sExpectedOperator)
	{
		$oModuleDependency = new DependencyExpression($sDepId);
		$this->assertEquals([$sDepId => ['itop-config-mgmt', $sExpectedOperator, '2.4.0']], $this->GetNonPublicProperty($oModuleDependency, 'aParamsPerModuleId'));
		$this->assertTrue($oModuleDependency->IsValid());
		$this->assertFalse($oModuleDependency->IsResolved());
		;
		$this->assertEquals(['itop-config-mgmt'], $oModuleDependency->GetRemainingModuleNamesToResolve());
	}

	public static function WithOperatorOperandProvider()
	{
		$aInternalStructure= ['itop-structure/3.0.0' => [ 'itop-structure',  ">=", '3.0.0'], 'itop-portal/<3.2.1' => [ 'itop-portal',  "<", '3.2.1']];
		return [
			'&&' => [
				'sDepId' => 'itop-structure/3.0.0 && itop-portal/<3.2.1',
				'expected_structure' => $aInternalStructure,
			],
			'&& with parenthesis' => [
				'sDepId' => '(itop-structure/3.0.0) && (itop-portal/<3.2.1)',
				'expected_structure' => $aInternalStructure,
			],
			'||' => [
				'sDepId' => 'itop-structure/3.0.0 || itop-portal/<3.2.1',
				'expected_structure' => $aInternalStructure,
			],
			'|| with parenthesis' => [
				'sDepId' => '(itop-structure/3.0.0) || (itop-portal/<3.2.1)',
				'expected_structure' => $aInternalStructure,
			],
		];
	}

	/**
	 * @dataProvider WithOperatorOperandProvider
	 */
	public function testModuleDependencyInit_WithOperand($sDepId, $sExpected)
	{
		$oModuleDependency = new DependencyExpression($sDepId);
		$this->assertEquals($sExpected, $this->GetNonPublicProperty($oModuleDependency, 'aParamsPerModuleId'));
		$this->assertTrue($oModuleDependency->IsValid());
		;
		$this->assertEquals(['itop-structure', 'itop-portal'], $oModuleDependency->GetRemainingModuleNamesToResolve());
	}

	public function testModuleIsDependencyResolved_SimpleCase_UnresolvedDueToMissingModule()
	{
		$oModuleDependency = new DependencyExpression('itop-config-mgmt/2.4.0');
		$oModuleDependency->UpdateModuleResolutionState([], ['itop-config-mgmt' => true]);
		$this->assertEquals(false, $oModuleDependency->IsResolved());
	}

	public function testModuleIsDependencyResolved_SimpleCase_UnresolvedDueToWrongModuleVersion()
	{
		$oModuleDependency = new DependencyExpression('itop-config-mgmt/2.4.0');
		$oModuleDependency->UpdateModuleResolutionState(['itop-config-mgmt' => '1.2.3'], ['itop-config-mgmt' => true]);
		$this->assertEquals(false, $oModuleDependency->IsResolved());
	}

	public function testModuleIsDependencyResolved_SimpleCase_ResolvedDue_MinorVersion()
	{
		$oModuleDependency = new DependencyExpression('itop-config-mgmt/2.4.1');
		$oModuleDependency->UpdateModuleResolutionState(['itop-config-mgmt' => '2.4.1-1'], ['itop-config-mgmt' => true]);
		$this->assertEquals(true, $oModuleDependency->IsResolved());
	}

	public function testModuleIsDependencyResolved_SimpleCase_ResolvedDue_MinorVersion2()
	{
		$oModuleDependency = new DependencyExpression('itop-config-mgmt/2.4.1-1');
		$oModuleDependency->UpdateModuleResolutionState(['itop-config-mgmt' => '2.4.1-2'], ['itop-config-mgmt' => true]);
		$this->assertEquals(true, $oModuleDependency->IsResolved());
	}

	public function testModuleIsDependencyResolved_SimpleCase_ResolvedDue_MinorVersion3()
	{
		$oModuleDependency = new DependencyExpression('itop-config-mgmt/2.4.1-1');
		$oModuleDependency->UpdateModuleResolutionState(['itop-config-mgmt' => '2.4.2'], ['itop-config-mgmt' => true]);
		$this->assertEquals(true, $oModuleDependency->IsResolved());
	}

	public function testModuleIsDependencyResolved_SimpleCase_UnresolvedDueToWrongModuleVersion_MinorVersion()
	{
		$oModuleDependency = new DependencyExpression('itop-config-mgmt/2.4.1');
		$oModuleDependency->UpdateModuleResolutionState(['itop-config-mgmt' => '2.4.0-1'], ['itop-config-mgmt' => true]);
		$this->assertEquals(false, $oModuleDependency->IsResolved());
	}

	public function testModuleIsDependencyResolved_SimpleCase_UnresolvedDueToWrongModuleVersion_MinorVersion2()
	{
		$oModuleDependency = new DependencyExpression('itop-config-mgmt/2.4.1-1');
		$oModuleDependency->UpdateModuleResolutionState(['itop-config-mgmt' => '2.4.1'], ['itop-config-mgmt' => true]);
		$this->assertEquals(false, $oModuleDependency->IsResolved());
	}

	public function testModuleIsDependencyResolved_SimpleCase_UnresolvedDueToWrongModuleVersion_MinorVersion3()
	{
		$oModuleDependency = new DependencyExpression('itop-config-mgmt/2.4.1-1');
		$oModuleDependency->UpdateModuleResolutionState(['itop-config-mgmt' => '2.4.1-0'], ['itop-config-mgmt' => true]);
		$this->assertEquals(false, $oModuleDependency->IsResolved());
	}

	public function testModuleIsDependencyResolved_SimpleCase_Resolved()
	{
		$oModuleDependency = new DependencyExpression('itop-config-mgmt/2.4.0');
		$this->assertEquals(['itop-config-mgmt'], $oModuleDependency->GetRemainingModuleNamesToResolve());
		$oModuleDependency->UpdateModuleResolutionState(['itop-config-mgmt' => '2.4.1'], ['itop-config-mgmt' => true]);
		$this->assertEquals(true, $oModuleDependency->IsResolved());
		$this->assertEquals([], $oModuleDependency->GetRemainingModuleNamesToResolve());
	}

	public function testIsDependencyResolved_AndOperand_UnresolvedDueToMissingModule()
	{
		$sDepId = "itop-structure/3.0.0 && itop-portal/3.2.1";
		$oModuleDependency = new DependencyExpression($sDepId);
		$this->assertEquals(['itop-structure/3.0.0' => [ 'itop-structure',  ">=", '3.0.0'], 'itop-portal/3.2.1' => [ 'itop-portal',  ">=", '3.2.1']], $this->GetNonPublicProperty($oModuleDependency, 'aParamsPerModuleId'));
		$this->assertTrue($oModuleDependency->IsValid());
		;
		$this->assertEquals(['itop-structure', 'itop-portal'], $oModuleDependency->GetRemainingModuleNamesToResolve());

		$oModuleDependency->UpdateModuleResolutionState(['itop-structure' => '3.0.0'], ['itop-structure' => true, 'itop-portal' => true]);
		$this->assertEquals(false, $oModuleDependency->IsResolved());
		$this->assertEquals(['itop-portal'], $oModuleDependency->GetRemainingModuleNamesToResolve());
	}

	public function testIsDependencyResolved_AndOperand_UnresolvedDueToWrongModuleVersion()
	{
		$sDepId = "itop-structure/3.0.0 && itop-portal/3.2.1";
		$oModuleDependency = new DependencyExpression($sDepId);
		$this->assertEquals(['itop-structure/3.0.0' => [ 'itop-structure',  ">=", '3.0.0'], 'itop-portal/3.2.1' => [ 'itop-portal',  ">=", '3.2.1']], $this->GetNonPublicProperty($oModuleDependency, 'aParamsPerModuleId'));
		$this->assertTrue($oModuleDependency->IsValid());
		;
		$this->assertEquals(['itop-structure', 'itop-portal'], $oModuleDependency->GetRemainingModuleNamesToResolve());

		$oModuleDependency->UpdateModuleResolutionState(['itop-structure' => '3.0.0', 'itop-portal' => '1.0.0'], ['itop-structure' => true, 'itop-portal' => true]);
		$this->assertEquals(false, $oModuleDependency->IsResolved());
		$this->assertEquals(['itop-portal'], $oModuleDependency->GetRemainingModuleNamesToResolve());
	}

	public function testIsDependencyResolved_AndOperand_Resolved()
	{
		$sDepId = "itop-structure/3.0.0 && itop-portal/3.2.1";
		$oModuleDependency = new DependencyExpression($sDepId);
		$this->assertEquals(['itop-structure/3.0.0' => [ 'itop-structure',  ">=", '3.0.0'], 'itop-portal/3.2.1' => [ 'itop-portal',  ">=", '3.2.1']], $this->GetNonPublicProperty($oModuleDependency, 'aParamsPerModuleId'));
		$this->assertTrue($oModuleDependency->IsValid());
		;
		$this->assertEquals(['itop-structure', 'itop-portal'], $oModuleDependency->GetRemainingModuleNamesToResolve());

		$oModuleDependency->UpdateModuleResolutionState(['itop-structure' => '3.0.0'], ['itop-structure' => true]);
		$this->assertEquals(false, $oModuleDependency->IsResolved());
		$this->assertEquals(['itop-portal'], $oModuleDependency->GetRemainingModuleNamesToResolve());
	}

	public function testIsDependencyResolved_OrOperand_ResolvedDueToMissingModule()
	{
		$sDepId = "itop-structure/3.0.0 || itop-portal/3.2.1";
		$oModuleDependency = new DependencyExpression($sDepId);
		$this->assertEquals(['itop-structure/3.0.0' => [ 'itop-structure',  ">=", '3.0.0'], 'itop-portal/3.2.1' => [ 'itop-portal',  ">=", '3.2.1']], $this->GetNonPublicProperty($oModuleDependency, 'aParamsPerModuleId'));
		$this->assertTrue($oModuleDependency->IsValid());
		;
		$this->assertEquals(['itop-structure', 'itop-portal'], $oModuleDependency->GetRemainingModuleNamesToResolve());

		$oModuleDependency->UpdateModuleResolutionState(['itop-structure' => '3.0.0'], ['itop-structure' => true]);
		$this->assertEquals(true, $oModuleDependency->IsResolved());
		$this->assertEquals(['itop-portal'], $oModuleDependency->GetRemainingModuleNamesToResolve());
	}
}
