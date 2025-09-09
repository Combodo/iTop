<?php

namespace Combodo\iTop\Test\UnitTest\Sources\PhpParser\Evaluation;

use Combodo\iTop\PhpParser\Evaluation\PhpExpressionEvaluator;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use ModuleFileReader;

class PhpExpressionEvaluatorTest extends ItopDataTestCase {
	public static $STATIC_PROPERTY = 123;
	private static $PRIVATE_STATIC_PROPERTY = 123;
	private const PRIVATE_CONSTANT = 123;

	protected function tearDown(): void
	{
		parent::tearDown();
	}

	public static function EvaluateExpressionProvider() {
		return [
			'Array: [1000 => "a"]' => ['sExpression' => '[1000 => "a"]'],
			'Array: ["a"]' => ['sExpression' => '["a"]'],
			'Array dict: ["a"=>"b"]' => ['sExpression' => '["a"=>"b"]'],
			'ArrayDimFetch: $_SERVER[\'toto\']' => ['sExpression' => '$_SERVER[\'toto\']'],
			'BinaryOperator: false|true' => [ 'sExpression' => 'false|true'],
			'BinaryOperator: false||true' => [ 'sExpression' => 'false||true'],
			'BinaryOperator: false&&true' => [ 'sExpression' => 'false&&true'],
			'BinaryOperator: true&&true&&true&&false' => [ 'sExpression' => 'true && true && true && false'],
			'BinaryOperator: false&true' => [ 'sExpression' => 'false&true'],
			'BinaryOperator: ! true' => [ 'sExpression' => '! true'],
			'BinaryOperator: 10 * 5' => [ 'sExpression' => '10 * 5'],
			'BinaryOperator: 1 > 2' => [ 'sExpression' => '1 > 2'],
			'BinaryOperator: 1 >= 1' => [ 'sExpression' => '1 >= 1'],
			'BinaryOperator: 1 <= 1' => [ 'sExpression' => '1 <= 1'],
			'BinaryOperator: PHP_VERSION_ID == PHP_VERSION_ID' => [ 'sExpression' => 'PHP_VERSION_ID == PHP_VERSION_ID'],
			'BinaryOperator: PHP_VERSION_ID != PHP_VERSION_ID' => [ 'sExpression' => 'PHP_VERSION_ID != PHP_VERSION_ID'],
			'BitwiseNot: ~3' => ['sExpression' => '~3'],
			'BitwiseXor: 3^2' => ['sExpression' => '3^2'],
			'BooleanAnd: true && false' => ['sExpression' => 'true && false'],
			'Cast: (array)3' => ['sExpression' => '(array)3'],
			'Cast: (bool)1' => ['sExpression' => '(bool)1'],
			'Cast: (bool)0' => ['sExpression' => '(bool)0'],
			'Cast: (double)3' => ['sExpression' => '(double)3'],
			'Cast: (float)3' => ['sExpression' => '(float)3'],
			'Cast: (int)3' => ['sExpression' => '(int)3'],
			'Cast: (object)3' => ['sExpression' => '(object)3'],
			'Cast: (string) $oEvaluationFakeClass' => ['sExpression' => '(string) $oEvaluationFakeClass', "toString"],
			'ClassConstFetch: public existing constant' => [ 'sExpression' => 'SetupUtils::PHP_MIN_VERSION'],
			'ClassConstFetch: unknown class:class' => [ 'sExpression' => 'GabuZomeuUnknownClass::class'],
			'Coalesce: $oNullVar ?? 1' => ['sExpression' => '$oNullVar ?? 1', 1],
			'Coalesce: $oNonNullVar ?? 1' => ['sExpression' => '$oNonNullVar ?? 1', 1],
			'Coalesce: $_SERVER["toto"] ?? 1' => ['sExpression' => '$_SERVER["toto"] ?? 1', "titi"],
			'Coalesce: $_SERVER["unknown_key"] ?? 1' => ['sExpression' => '$_SERVER["unknown_key"] ?? 1', 1],
			'Coalesce: $oGlobalNonNullVar ?? 1' => ['sExpression' => '$oGlobalNonNullVar ?? 1', "a"],
			'Coalesce: $oGlobalNullVar ?? 1' => ['sExpression' => '$oGlobalNullVar ?? 1', 1],
			'Concat: "a"."b"' => ['sExpression' => '"a"."b"'],
			'ConstFetch: false' => [ 'sExpression' => 'false'],
			'ConstFetch: (false)' => [ 'sExpression' => 'false'],
			'ConstFetch: true' => [ 'sExpression' => 'true'],
			'ConstFetch: (true)' => [ 'sExpression' => 'true'],
			'Equal: 1 == true' => [ 'sExpression' => '1 == true', true],
			'Equal: 1 == false' => [ 'sExpression' => '1 == false', false],
			'FuncCall: function_exists(\'ldap_connect\')' => [ 'sExpression' => 'function_exists(\'ldap_connect\')'],
			'FuncCall: function_exists(\'gabuzomeushouldnotexist\')' => [ 'sExpression' => 'function_exists(\'gabuzomeushouldnotexist\')'],
			'Identical: 1==="1"' => ['sExpression' => '1==="1"', false],
			'Identical: "1"==="1"' => ['sExpression' => '"1"==="1"', true],
			'Isset: isset($oNonNullVar)' => ['sExpression' => 'isset($oNonNullVar)', false],
			'Isset: isset($oGlobalNonNullVar)' => ['sExpression' => 'isset($oGlobalNonNullVar)', true],
			'Isset: isset($a, $_SERVER)' => ['sExpression' => 'isset($a, $_SERVER)', false],
			'Isset: isset($_SERVER)' => ['sExpression' => 'isset($_SERVER)', true],
			'Isset: isset($_SERVER, $a)' => ['sExpression' => 'isset($_SERVER, $a)', false],
			'Isset: isset($oGlobalNonNullVar, $_SERVER)' => ['sExpression' => 'isset($oGlobalNonNullVar, $_SERVER)', true],
			'MethodCall: $oEvaluationFakeClass->GetName()' => ['sExpression' => '$oEvaluationFakeClass->GetName()', "gabuzomeu"],
			'MethodCall: $oEvaluationFakeClass->GetLongName("aa")' => ['sExpression' => '$oEvaluationFakeClass->GetLongName("aa")', "gabuzomeu_aa"],
			'Mod: 3%2' => ['sExpression' => '3%2'],
			'NullsafeMethodCall: $oNullVar?->GetName()' => ['sExpression' => '$oNullVar?->GetName()', null],
			'NullsafeMethodCall: $oNullVar?->GetLongName("aa")' => ['sExpression' => '$oNullVar?->GetLongName("aa")', null],
			'NullsafeMethodCall: $oEvaluationFakeClass?->GetName()' => ['sExpression' => '$oEvaluationFakeClass?->GetName()', "gabuzomeu"],
			'NullsafeMethodCall: $oEvaluationFakeClass?->GetLongName("aa")' => ['sExpression' => '$oEvaluationFakeClass?->GetLongName("aa")', "gabuzomeu_aa"],
			'NullsafePropertyFetch: $oNullVar?->b' => ['sExpression' => '$oNullVar?->b', null],
			'NullsafePropertyFetch: $oEvaluationFakeClass?->iIsOk' => ['sExpression' => '$oEvaluationFakeClass?->iIsOk', "IsOkValue"],
			'PropertyFetch: $oEvaluationFakeClass->iIsOk' => ['sExpression' => '$oEvaluationFakeClass->iIsOk', "IsOkValue"],
			'StaticCall utils::GetItopVersionWikiSyntax()' => ['sExpression' => 'utils::GetItopVersionWikiSyntax()'],
			'StaticProperty: public existing constant' => [ 'sExpression' => 'Combodo\iTop\Test\UnitTest\Sources\PhpParser\Evaluation\PhpExpressionEvaluatorTest::$STATIC_PROPERTY'],

			'Ternary: (true) ? 1 : 2' => ['sExpression' => '(true) ? 1 : 2'],
			'Ternary: (false) ? 1 : 2' => ['sExpression' => '(false) ? 1 : 2'],
			'UnaryMinus: -1' => ['sExpression' => '-1'],
			'UnaryPlus: +1' => ['sExpression' => '+1'],
			'Variable: $_SERVER' => ['sExpression' => '$_SERVER', ['toto' => 'titi']],
			'Variable: $oGlobalNonNullVar' => ['sExpression' => '$oGlobalNonNullVar', "a"],
			'Variable: $oEvaluationFakeClass' => ['sExpression' => '$oEvaluationFakeClass', new EvaluationFakeClass()],
		];
	}

	/**
	 * @dataProvider EvaluateExpressionProvider
	 */
	public function testEvaluateExpression($sExpression, $forced_expected="NOTPROVIDED")
	{
		global $oGlobalNonNullVar;
		$oGlobalNonNullVar="a";

		global $oGlobalNullVar;
		$oGlobalNullVar=null;

		$oNonNullVar="a";

		$oNullVar=null;
		$_SERVER=[
			'toto' => 'titi',
		];

		global $oEvaluationFakeClass;
		$oEvaluationFakeClass = new EvaluationFakeClass();

		$oPhpExpressionEvaluator = new PhpExpressionEvaluator(ModuleFileReader::FUNC_CALL_WHITELIST, ModuleFileReader::STATIC_CALLWHITELIST);
		$res = $oPhpExpressionEvaluator->ParseAndEvaluateExpression($sExpression);
		if ($forced_expected === "NOTPROVIDED"){
			$this->assertEquals($this->UnprotectedComputeExpression($sExpression), $res, $sExpression);
		} else {
			$this->assertEquals($forced_expected, $res, $sExpression);
		}
	}

	public static function EvaluateExpressionThrowsExceptionProvider()
	{
		return [
			'StaticProperty: private existing constant' => [
				'sExpression' => 'Combodo\iTop\Test\UnitTest\Setup\ModuleDiscovery\PhpExpressionEvaluatorTest::$PRIVATE_STATIC_PROPERTY',
				'forced_expected' => null,
			],
			'ClassConstFetch: unknown constant' => [ 'sExpression' => 'SetupUtils::UNKNOWN_CONSTANT'],
			'ClassConstFetch: unknown class:constant' => [ 'sExpression' => 'GabuZomeuUnknownClass::UNKNOWN_CONSTANT'],
			'ClassConstFetch: private existing constant' => [
				'sExpression' => 'Combodo\iTop\Test\UnitTest\Setup\ModuleDiscovery\PhpExpressionEvaluatorTest::PRIVATE_CONSTANT',
				'forced_expected' => null,
			],
			'Variable: $oNonNullVar' => ['sExpression' => '$oNonNullVar', null],
			'FuncCall: function_exists(\'ldap_connect\')' => [ 'sExpression' => 'function_exists(\'ldap_connect\')'],
			'StaticCall utils::GetItopVersionWikiSyntax()' => ['sExpression' => 'utils::GetItopVersionWikiSyntax()'],
		];
	}
	/**
	 * @dataProvider EvaluateExpressionThrowsExceptionProvider
	 */
	public function testEvaluateExpressionThrowsException($sExpression)
	{
		global $oGlobalNonNullVar;
		$oGlobalNonNullVar="a";

		global $oGlobalNullVar;
		$oGlobalNullVar=null;

		$oNonNullVar="a";

		$oNullVar=null;
		$_SERVER=[
			'toto' => 'titi',
		];

		global $oEvaluationFakeClass;
		$oEvaluationFakeClass = new EvaluationFakeClass();

		$this->expectException(\ModuleFileReaderException::class);
		$oPhpExpressionEvaluator = new PhpExpressionEvaluator();
		$oPhpExpressionEvaluator->ParseAndEvaluateExpression($sExpression);
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
		$oPhpExpressionEvaluator = new PhpExpressionEvaluator([], ["SetupInfo::ModuleIsSelected"]);
		$this->assertEquals($expected, $oPhpExpressionEvaluator->ParseAndEvaluateBooleanExpression($sBooleanExpression), $sBooleanExpression);
	}
}

class EvaluationFakeClass {
	public string $iIsOk="IsOkValue";

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
		return "toString";
	}
}