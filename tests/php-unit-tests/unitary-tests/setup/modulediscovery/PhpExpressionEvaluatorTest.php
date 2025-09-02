<?php

namespace Combodo\iTop\Test\UnitTest\Setup\ModuleDiscovery;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use evaluation\expression\PhpExpressionEvaluator;

class PhpExpressionEvaluatorTest extends ItopDataTestCase {
	public static $STATIC_PROPERTY = 123;
	private static $PRIVATE_STATIC_PROPERTY = 123;
	private const PRIVATE_CONSTANT = 123;

	public static function EvaluateExpressionProvider() {
		return [
			'ConstFetch: false' => [ 'sExpression' => 'false'],
			'ConstFetch: (false)' => [ 'sExpression' => 'false'],
			'ConstFetch: true' => [ 'sExpression' => 'true'],
			//'ConstFetch: __FILE__' => [ 'sExpression' => __FILE__],
			'ConstFetch: (true)' => [ 'sExpression' => 'true'],
			'ClassConstFetch: public existing constant' => [ 'sExpression' => 'SetupUtils::PHP_MIN_VERSION'],
			'ClassConstFetch: unknown constant' => [ 'sExpression' => 'SetupUtils::UNKNOWN_CONSTANT'],
			'ClassConstFetch: unknown class:constant' => [ 'sExpression' => 'GabuZomeuUnknownClass::UNKNOWN_CONSTANT'],
			'ClassConstFetch: unknown class:class' => [ 'sExpression' => 'GabuZomeuUnknownClass::class'],
			'ClassConstFetch: private existing constant' => [
				'sExpression' => 'Combodo\iTop\Test\UnitTest\Setup\ModuleDiscovery\PhpExpressionEvaluatorTest::PRIVATE_CONSTANT',
				'forced_expected' => null
			],
			'StaticProperty: public existing constant' => [ 'sExpression' => 'Combodo\iTop\Test\UnitTest\Setup\ModuleDiscovery\PhpExpressionEvaluatorTest::$STATIC_PROPERTY'],
			'StaticProperty: private existing constant' => [
				'sExpression' => 'Combodo\iTop\Test\UnitTest\Setup\ModuleDiscovery\PhpExpressionEvaluatorTest::$PRIVATE_STATIC_PROPERTY',
				'forced_expected' => null
			],
			'BinaryOperator: false|true' => [ 'sExpression' => 'false|true'],
			'BinaryOperator: false||true' => [ 'sExpression' => 'false||true'],
			'BinaryOperator: false&&true' => [ 'sExpression' => 'false&&true'],
			'BinaryOperator: true&&true&&true&&false' => [ 'sExpression' => 'true&&true&&true&&false'],
			'BinaryOperator: false&true' => [ 'sExpression' => 'false&true'],
			'BinaryOperator: ! true' => [ 'sExpression' => '! true'],
			'BinaryOperator: 10 * 5' => [ 'sExpression' => '10 * 5'],
			'BinaryOperator: 1 > 2' => [ 'sExpression' => '1 > 2'],
			'BinaryOperator: 1 >= 1' => [ 'sExpression' => '1 >= 1'],
			'BinaryOperator: 1 <= 1' => [ 'sExpression' => '1 <= 1'],
			'BinaryOperator: PHP_VERSION_ID == PHP_VERSION_ID' => [ 'sExpression' => 'PHP_VERSION_ID == PHP_VERSION_ID'],
			'BinaryOperator: PHP_VERSION_ID != PHP_VERSION_ID' => [ 'sExpression' => 'PHP_VERSION_ID != PHP_VERSION_ID'],
			'FuncCall: function_exists(\'ldap_connect\')' => [ 'sExpression' => 'function_exists(\'ldap_connect\')'],
			'FuncCall: function_exists(\'gabuzomeushouldnotexist\')' => [ 'sExpression' => 'function_exists(\'gabuzomeushouldnotexist\')'],
			'UnaryMinus: -1' => ['sExpression' => '-1'],
			'Concat: "a"."b"' => ['sExpression' => '"a"."b"'],
			'ArrayDimFetch: $_SERVER[\'toto\']' => ['sExpression' => '$_SERVER[\'toto\']'],
			'Variable: $_SERVER' => ['sExpression' => '$_SERVER'],
			'Array: [1000 => "a"]' => ['sExpression' => '[1000 => "a"]'],
			'Array: ["a"]' => ['sExpression' => '["a"]'],
			'Array dict: ["a"=>"b"]' => ['sExpression' => '["a"=>"b"]'],
			'StaticCall utils::GetItopVersionWikiSyntax()' => ['sExpression' => 'utils::GetItopVersionWikiSyntax()']
		];
	}

	/**
	 * @dataProvider EvaluateExpressionProvider
	 */
	public function testEvaluateExpression($sExpression, $forced_expected="NOTPROVIDED")
	{
		$_SERVER=[
			'toto' => 'titi'
		];

		$res = PhpExpressionEvaluator::GetInstance()->ParseAndEvaluateExpression($sExpression);
		if ($forced_expected === "NOTPROVIDED"){
			$this->assertEquals($this->UnprotectedComputeExpression($sExpression), $res, $sExpression);
		} else {
			$this->assertEquals($forced_expected, $res, $sExpression);
		}
	}

	/**
	 * @param string $sBooleanExpr
	 *
	 * @return mixed
	 * @throws \ModuleFileReaderException
	 */
	private function UnprotectedComputeExpression(string $sExpr) : mixed
	{
		try {
			$bResult = null;
			@eval('$bResult = '.$sExpr.';');

			return $bResult;
		} catch (\Throwable $t){
			return null;
		}
	}

	public function testParseAndEvaluateBooleanExpression_BrokenBooleanExpression(){
		$this->expectException(\ModuleFileReaderException::class);
		$this->expectExceptionMessage('Eval of \'(a || true)\' caused an error');
		$this->assertTrue(PhpExpressionEvaluator::GetInstance()->ParseAndEvaluateBooleanExpression("(a || true)"));
	}

	public static function ParseAndEvaluateBooleanExpression_AutoselectProvider()
	{
		$sSimpleCallToModuleIsSelected = "SetupInfo::ModuleIsSelected(\"itop-storage-mgmt\")";
		$sSimpleCallToModuleIsSelected2 = "SetupInfo::ModuleIsSelected(\"itop-storage-mgmt-notselected\")";
		$sCallToModuleIsSelectedCombinedWithAndOperator = "SetupInfo::ModuleIsSelected(\"itop-storage-mgmt\") || SetupInfo::ModuleIsSelected(\"itop-virtualization-mgmt\")";
		$sCallToModuleIsSelectedCombinedWithAndOperator2 = "SetupInfo::ModuleIsSelected(\"itop-storage-mgmt-notselected\") || SetupInfo::ModuleIsSelected(\"itop-virtualization-mgmt\")";

		return [
			"simple call to SetupInfo::ModuleIsSelected SELECTED" => [
				"expr" => $sSimpleCallToModuleIsSelected,
				"expected" => true,
			],
			"simple call to SetupInfo::ModuleIsSelected NOT SELECTED" => [
				"expr" => $sSimpleCallToModuleIsSelected2,
				"expected" => false,
			],
			"call to SetupInfo::ModuleIsSelected + OR => SELECTED" => [
				"expr" => $sCallToModuleIsSelectedCombinedWithAndOperator,
				"expected" => true,
			],
			"simple call to SetupInfo::ModuleIsSelected + OR => NOT SELECTED" => [
				"expr" => $sCallToModuleIsSelectedCombinedWithAndOperator2,
				"expected" => false,
			],
		];
	}


	/**
	 * @dataProvider ParseAndEvaluateBooleanExpression_AutoselectProvider
	 */
	public function testEvaluateBooleanExpression_Autoselect(string $sBooleanExpression, bool $expected){
		\SetupInfo::SetSelectedModules(["itop-storage-mgmt" => "123"]);
		$this->assertEquals($expected, PhpExpressionEvaluator::GetInstance()->ParseAndEvaluateBooleanExpression($sBooleanExpression), $sBooleanExpression);
	}
}