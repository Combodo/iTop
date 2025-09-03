<?php

namespace Combodo\iTop\Test\UnitTest\Sources\PhpParser\Evaluation;

use Combodo\iTop\PhpParser\Evaluation\PhpExpressionEvaluator;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class PhpExpressionEvaluatorTest extends ItopDataTestCase {
	public static $STATIC_PROPERTY = 123;
	private static $PRIVATE_STATIC_PROPERTY = 123;
	private const PRIVATE_CONSTANT = 123;

	public static function EvaluateExpressionProvider() {
		return [
			'ConstFetch: false' => [ 'sExpression' => 'false'],
			'ConstFetch: (false)' => [ 'sExpression' => 'false'],
			'ConstFetch: true' => [ 'sExpression' => 'true'],
			'ConstFetch: (true)' => [ 'sExpression' => 'true'],
			'ClassConstFetch: public existing constant' => [ 'sExpression' => 'SetupUtils::PHP_MIN_VERSION'],
			'ClassConstFetch: unknown constant' => [ 'sExpression' => 'SetupUtils::UNKNOWN_CONSTANT'],
			'ClassConstFetch: unknown class:constant' => [ 'sExpression' => 'GabuZomeuUnknownClass::UNKNOWN_CONSTANT'],
			'ClassConstFetch: unknown class:class' => [ 'sExpression' => 'GabuZomeuUnknownClass::class'],
			'ClassConstFetch: private existing constant' => [
				'sExpression' => 'Combodo\iTop\Test\UnitTest\Setup\ModuleDiscovery\PhpExpressionEvaluatorTest::PRIVATE_CONSTANT',
				'forced_expected' => null,
			],
			'StaticProperty: public existing constant' => [ 'sExpression' => 'Combodo\iTop\Test\UnitTest\Setup\ModuleDiscovery\PhpExpressionEvaluatorTest::$STATIC_PROPERTY'],
			'StaticProperty: private existing constant' => [
				'sExpression' => 'Combodo\iTop\Test\UnitTest\Setup\ModuleDiscovery\PhpExpressionEvaluatorTest::$PRIVATE_STATIC_PROPERTY',
				'forced_expected' => null,
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
			'UnaryPlus: +1' => ['sExpression' => '+1'],
			'Concat: "a"."b"' => ['sExpression' => '"a"."b"'],
			'ArrayDimFetch: $_SERVER[\'toto\']' => ['sExpression' => '$_SERVER[\'toto\']'],
			//'Variable: $_SERVER' => ['sExpression' => '$_SERVER'],
			'Variable: $oNonNullVar' => ['sExpression' => '$oNonNullVar'],
			'Array: [1000 => "a"]' => ['sExpression' => '[1000 => "a"]'],
			'Array: ["a"]' => ['sExpression' => '["a"]'],
			'Array dict: ["a"=>"b"]' => ['sExpression' => '["a"=>"b"]'],
			'StaticCall utils::GetItopVersionWikiSyntax()' => ['sExpression' => 'utils::GetItopVersionWikiSyntax()'],
			'NullsafePropertyFetch: $oNullVar?->b' => ['sExpression' => '$oNullVar?->b'],
			'NullsafePropertyFetch: $oEvaluationFakeClass?->bIsOk' => ['sExpression' => '$oEvaluationFakeClass?->bIsOk'],
			'PropertyFetch: $oEvaluationFakeClass->bIsOk' => ['sExpression' => '$oEvaluationFakeClass->bIsOk'],
			'NullsafeMethodCall: $oEvaluationFakeClass?->GetName()' => ['sExpression' => '$oEvaluationFakeClass?->GetName()'],
			'NullsafeMethodCall: $oEvaluationFakeClass?->GetLongName("aa")' => ['sExpression' => '$oEvaluationFakeClass?->GetLongName("aa")'],
			'MethodCall: $oEvaluationFakeClass->GetName()' => ['sExpression' => '$oEvaluationFakeClass->GetName()'],
			'MethodCall: $oEvaluationFakeClass->GetLongName("aa")' => ['sExpression' => '$oEvaluationFakeClass->GetLongName("aa")'],
			'Coalesce: $oNullVar ?? 1' => ['sExpression' => '$oNullVar ?? 1'],
			'Coalesce: $oNonNullVar ?? 1' => ['sExpression' => '$oNonNullVar ?? 1'],
			'Isset: isset($a)' => ['sExpression' => 'isset($a)'],
			'Isset: isset($a, $_SERVER)' => ['sExpression' => 'isset($a, $_SERVER)'],
			'Isset: isset($_SERVER)' => ['sExpression' => 'isset($_SERVER)'],
			'Isset: isset($_SERVER, $a)' => ['sExpression' => 'isset($_SERVER, $a)'],
			'BitwiseNot: ~3' => ['sExpression' => '~3'],
			'Mod: 3%2' => ['sExpression' => '3%2'],
			'BitwiseXor: 3^2' => ['sExpression' => '3^2'],
			'Ternary: (true) ? 1 : 2' => ['sExpression' => '(true) ? 1 : 2'],
			'Ternary: (false) ? 1 : 2' => ['sExpression' => '(false) ? 1 : 2'],
			'Cast: (array)3' => ['sExpression' => '(array)3'],
			'Cast: (bool)1' => ['sExpression' => '(bool)1'],
			'Cast: (bool)0' => ['sExpression' => '(bool)0'],
			'Cast: (double)3' => ['sExpression' => '(double)3'],
			'Cast: (float)3' => ['sExpression' => '(float)3'],
			'Cast: (int)3' => ['sExpression' => '(int)3'],
			'Cast: (object)3' => ['sExpression' => '(object)3'],
			'Cast: (string)$oEvaluationFakeClass' => ['sExpression' => '(string)$oEvaluationFakeClass'],
		];
	}

	/**
	 * @dataProvider EvaluateExpressionProvider
	 */
	public function testEvaluateExpression($sExpression, $forced_expected="NOTPROVIDED")
	{
		$oNullVar=null;
		$oNonNullVar="a";
		$_SERVER=[
			'toto' => 'titi',
		];

		$oEvaluationFakeClass = new EvaluationFakeClass();

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

class EvaluationFakeClass {
	public bool $bIsOk=true;

	public function GetName()
	{
		return "gabuzomeu";
	}

	public function GetLongName($suffix)
	{
		return "gabuzomeu_" . $suffix;
	}

	public function __toString(): string
	{
		return "a";
	}
}