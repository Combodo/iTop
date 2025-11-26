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
				'expected_operator' => '>=',
			],
			">" => [
				"dep" => "itop-config-mgmt/>2.4.0",
				'expected_operator' => '>',
			],
			">=" => [
				"dep" => "itop-config-mgmt/>=2.4.0",
				'expected_operator' => '>=',
			],
			"<" => [
				"dep" => "itop-config-mgmt/<2.4.0",
				'expected_operator' => '<',
			],
			"<=" => [
				"dep" => "itop-config-mgmt/<=2.4.0",
				'expected_operator' => '<=',
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
		$aInternalStructure = ['itop-structure/3.0.0' => [ 'itop-structure',  ">=", '3.0.0'], 'itop-portal/<3.2.1' => [ 'itop-portal',  "<", '3.2.1']];
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

	public static function SimpleDependencyExpressionIsResolvedProvider()
	{
		return [
			'unresolved with major version' => [
				'expr' => 'itop-config-mgmt/2.4.0',
				'module_versions' => ['itop-config-mgmt' => '1.2.3'],
				'expected_is_resolved' => false,
			],
			'unresolved with minor version' => [
				'expr' => 'itop-config-mgmt/2.4.1',
				'module_versions' => ['itop-config-mgmt' => '2.4.0-1'],
				'expected_is_resolved' => false,
			],
			'resolution OK with major version' => [
				'expr' => 'itop-config-mgmt/2.4.0',
				'module_versions' => ['itop-config-mgmt' => '2.4.2'],
				'expected_is_resolved' => true,
			],
			'resolution OK with minor version' => [
				'expr' => 'itop-config-mgmt/2.4.0',
				'module_versions' => ['itop-config-mgmt' => '2.4.0-1'],
				'expected_is_resolved' => true,
			],
			'unproper use of api' => [
				'expr' => 'itop-config-mgmt/2.4.0',
				'module_versions' => [],
				'expected_is_resolved' => false,
			],
		];
	}

	/**
	 * @dataProvider SimpleDependencyExpressionIsResolvedProvider
	 */
	public function testSimpleDependencyExpressionIsResolved($sExpression, $aModuleVersions, $bExpectedResolved)
	{
		$oModuleDependency = new DependencyExpression($sExpression);
		$oModuleDependency->UpdateModuleResolutionState($aModuleVersions, ['itop-config-mgmt' => true]);
		$this->assertEquals($bExpectedResolved, $oModuleDependency->IsResolved());
		if ($bExpectedResolved) {
			$this->assertEquals([], $oModuleDependency->GetRemainingModuleNamesToResolve());
		}
	}

	public static function ComplexDependencyExpressionIsResolvedProvider()
	{
		return [
			'and + unresolved due to missing itop-portal' => [
				'expr' => 'itop-structure/3.0.0 && itop-portal/3.2.1',
				'module_versions' => ['itop-structure' => '3.0.0'],
				'expected_is_resolved' => false,
				'remaining_module_names' => ['itop-portal'],
			],
			'and + unresolved due to unsifficient itop-portal version' => [
				'expr' => 'itop-structure/3.0.0 && itop-portal/3.2.1',
				'module_versions' => ['itop-structure' => '3.0.0', 'itop-portal' => '1.0.0'],
				'expected_is_resolved' => false,
				'remaining_module_names' => ['itop-portal'],
			],
			'and + resolved' => [
				'expr' => 'itop-structure/3.0.0 && itop-portal/3.2.1',
				'module_versions' => ['itop-structure' => '3.0.0', 'itop-portal' => '3.3.3'],
				'expected_is_resolved' => true,
				'remaining_module_names' => [],
			],
			'or + resolved' => [
				'expr' => 'itop-structure/3.0.0 || itop-portal/3.2.1',
				'module_versions' => ['itop-structure' => '3.0.0'],
				'expected_is_resolved' => false,
				'remaining_module_names' => ['itop-portal'],
			],
			'or + resolved with less prerequisites' => [
				'expr' => 'itop-structure/3.0.0 || itop-portal/3.2.1',
				'module_versions' => ['itop-structure' => '3.0.0'],
				'expected_is_resolved' => true,
				'remaining_module_names' => ['itop-portal'],
				'prerequisites' => ['itop-structure' => true],
			],
		];
	}

	/**
	 * @dataProvider ComplexDependencyExpressionIsResolvedProvider
	 */
	public function testComplexDependencyExpressionIsResolved($sExpression, $aModuleVersions, $bExpectedResolved, $aRemainingModuleNames, $aPrerequisites = ['itop-structure' => true, 'itop-portal' => true])
	{
		$oModuleDependency = new DependencyExpression($sExpression);

		$oModuleDependency->UpdateModuleResolutionState($aModuleVersions, $aPrerequisites);
		$this->assertEquals($aRemainingModuleNames, $oModuleDependency->GetRemainingModuleNamesToResolve());
		$this->assertEquals($bExpectedResolved, $oModuleDependency->IsResolved());
	}
}
