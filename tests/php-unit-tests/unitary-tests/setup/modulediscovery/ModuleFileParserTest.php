<?php

namespace Combodo\iTop\Test\UnitTest\Setup\ModuleDiscovery;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use ModuleFileParser;
use ModuleFileReader;
use PhpParser\ParserFactory;
use SetupUtils;

class ModuleFileParserTest extends ItopDataTestCase
{
	public static $STATIC_PROPERTY = 123;
	private static $PRIVATE_STATIC_PROPERTY = 123;
	private const PRIVATE_CONSTANT = 123;

	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('setup/modulediscovery/ModuleFileReader.php');
	}

	public static function EvaluateBooleanExpressionProvider()
	{
		return [
			"true" => [ "expr" => "true", "expected" => true],
			"(true)" => [ "expr" => "(true)", "expected" => true],
			"(false|true)" => [ "expr" => "(false|true)", "expected" => true],
			"(false||true)" => [ "expr" => "(false||true)", "expected" => true],
			"false" => [ "expr" => "false", "expected" => false],
			"(false)" => [ "expr" => "(false)", "expected" => false],
			"(false&&true)" => [ "expr" => "(false&&true)", "expected" => false],
			"(false&true)" => [ "expr" => "(false&true)", "expected" => false],
			"10 * 10" => [ "expr" => "10 * 10", "expected" => 100],
		];
	}

	/**
	 * @dataProvider EvaluateBooleanExpressionProvider
	 */
	public function testEvaluateBooleanExpression(string $sBooleanExpression, $expected){
		$this->assertEquals($expected, ModuleFileParser::GetInstance()->EvaluateBooleanExpression($sBooleanExpression), $sBooleanExpression);
	}

	public function testEvaluateBooleanExpression_BrokenBooleanExpression(){
		$this->expectException(\ModuleFileReaderException::class);
		$this->expectExceptionMessage('Eval of \'(a || true)\' caused an error');
		$this->assertTrue(ModuleFileParser::GetInstance()->EvaluateBooleanExpression("(a || true)"));
	}


	public static function EvaluateBooleanExpressionAutoselectProvider()
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
	 * @dataProvider EvaluateBooleanExpressionAutoselectProvider
	 */
	public function testEvaluateBooleanExpression_Autoselect(string $sBooleanExpression, bool $expected){
		\SetupInfo::SetSelectedModules(["itop-storage-mgmt" => "123"]);
		$this->assertEquals($expected, ModuleFileParser::GetInstance()->EvaluateBooleanExpression($sBooleanExpression), $sBooleanExpression);
	}

	public function testEvaluateConstantExpression()
	{
		$sPHP = <<<PHP
<?php
APPROOT;
PHP;
		$aNodes = ModuleFileParser::GetInstance()->ParsePhpCode($sPHP);
		/** @var \PhpParser\Node\Expr $oExpr */
		$oExpr = $aNodes[0];
		$val = $this->InvokeNonPublicMethod(ModuleFileParser::class, "EvaluateConstantExpression", ModuleFileParser::GetInstance(), [$oExpr->expr]);
		$this->assertEquals(APPROOT, $val);
	}

	public function testEvaluateClassConstantExpression_PublicConstant()
	{
		$this->validateEvaluateClassConstantExpression('SetupUtils::PHP_MIN_VERSION', SetupUtils::PHP_MIN_VERSION);
	}

	public function testEvaluateClassConstantExpression_PrivateConstantShouldNotBeFound()
	{
		$this->validateEvaluateClassConstantExpression('Combodo\iTop\Test\UnitTest\Setup\ModuleDiscovery\ModuleFileParserTest::PRIVATE_CONSTANT', null);
	}

	public function testEvaluateClassConstant_UnknownConstant()
	{
		$this->validateEvaluateClassConstantExpression('SetupUtils::UNKOWN_CONSTANT', null);
	}

	public function testEvaluateClassConstant_UnknownClass()
	{
		$this->validateEvaluateClassConstantExpression('UnknownGaBuZoMeuClass::PHP_MIN_VERSION', null);
	}

	public function testEvaluateClassConstant_UnknownClassGetClass()
	{
		$this->validateEvaluateClassConstantExpression('UnknownGaBuZoMeuClass::class', 'UnknownGaBuZoMeuClass');
	}

	public function validateEvaluateClassConstantExpression($sExpression, $expected)
	{
		$sPHP = <<<PHP
<?php
$sExpression;
PHP;
		$aNodes = ModuleFileParser::GetInstance()->ParsePhpCode($sPHP);
		/** @var \PhpParser\Node\Expr $oExpr */
		$oExpr = $aNodes[0];
		$val = $this->InvokeNonPublicMethod(ModuleFileParser::class, "EvaluateClassConstantExpression", ModuleFileParser::GetInstance(), [$oExpr->expr]);
		$this->assertEquals($expected, $val, "$sExpression");
	}

	public function testEvaluateClassConstant_PublicGetStaticProperty()
	{
		$this->validateEvaluateStaticPropertyExpression('Combodo\iTop\Test\UnitTest\Setup\ModuleDiscovery\ModuleFileParserTest::$STATIC_PROPERTY', ModuleFileParserTest::$STATIC_PROPERTY);
	}

	public function testEvaluateClassConstant_PrivateGetStaticPropertyShouldNotBeFound()
	{
		$this->validateEvaluateStaticPropertyExpression('Combodo\iTop\Test\UnitTest\Setup\ModuleDiscovery\ModuleFileParserTest::$PRIVATE_STATIC_PROPERTY', null);
	}

	public function validateEvaluateStaticPropertyExpression($sExpression, $expected)
	{
		$sPHP = <<<PHP
<?php
$sExpression;
PHP;
		$aNodes = ModuleFileParser::GetInstance()->ParsePhpCode($sPHP);
		/** @var \PhpParser\Node\Expr $oExpr */
		$oExpr = $aNodes[0];
		$val = $this->InvokeNonPublicMethod(ModuleFileParser::class, "EvaluateStaticPropertyExpression", ModuleFileParser::GetInstance(), [$oExpr->expr]);
		$this->assertEquals($expected, $val, "$sExpression");
	}

	public static function EvaluateExpressionBooleanProvider() {
		$sTruePHP = <<<PHP
<?php
if (COND){
	echo "toto";
}
PHP;

		return [
			'"true"' => [
				"code" => str_replace("COND", '"true"', $sTruePHP),
				"bool_expected" => "true",

			],
			"true" => [
				"code" => str_replace("COND", "true", $sTruePHP),
				"bool_expected" => true,

			],
			"false" => [
				"code" => str_replace("COND", "false", $sTruePHP),
				"bool_expected" => false,

			],
			'"false"' => [
				"code" => str_replace("COND", '"false"', $sTruePHP),
				"bool_expected" => "false",

			],
			"not ok" => [
				"code" => str_replace("COND", "! false", $sTruePHP),
				"bool_expected" => true,

			],
			"not ko" => [
				"code" => str_replace("COND", "! (true)", $sTruePHP),
				"bool_expected" => false,

			],
			"AND ko" => [
				"code" => str_replace("COND", "true && false", $sTruePHP),
				"bool_expected" => false,

			],
			"AND ok1" => [
				"code" => str_replace("COND", "true && true", $sTruePHP),
				"bool_expected" => true,

			],
			"AND ko2" => [
				"code" => str_replace("COND", "true && true && false", $sTruePHP),
				"bool_expected" => false,

			],
			"OR ko" => [
				"code" => str_replace("COND", "false || false", $sTruePHP),
				"bool_expected" => false,

			],
			"OR ok" => [
				"code" => str_replace("COND", "false ||true", $sTruePHP),
				"bool_expected" => true,

			],
			"OR ok2" => [
				"code" => str_replace("COND", "false ||false||true", $sTruePHP),
				"bool_expected" => true,

			],
			"function_exists('ldap_connect')" => [
				"code" => str_replace("COND", "function_exists('ldap_connect')", $sTruePHP),
				"bool_expected" => function_exists('ldap_connect'),

			],
			"function_exists('gabuzomeushouldnotexist')" => [
				"code" => str_replace("COND", "function_exists('gabuzomeushouldnotexist')", $sTruePHP),
				"bool_expected" => function_exists('gabuzomeushouldnotexist'),

			],
			"1 > 2" => [
				"code" => str_replace("COND", "1 > 2", $sTruePHP),
				"bool_expected" => false,

			],
			"1 == 1" => [
				"code" => str_replace("COND", "1 == 1", $sTruePHP),
				"bool_expected" => true,

			],
			"1 < 2" => [
				"code" => str_replace("COND", "1 < 2", $sTruePHP),
				"bool_expected" => true,
			],
			"PHP_VERSION_ID == PHP_VERSION_ID" => [
				"code" => str_replace("COND", "PHP_VERSION_ID == PHP_VERSION_ID", $sTruePHP),
				"bool_expected" => true,
			],
			"PHP_VERSION_ID != PHP_VERSION_ID" => [
				"code" => str_replace("COND", "PHP_VERSION_ID != PHP_VERSION_ID", $sTruePHP),
				"bool_expected" => false,
			],
		];
	}

	/**
	 * @dataProvider EvaluateExpressionBooleanProvider
	 */
	public function testEvaluateExpression($sPHP, $bExpected)
	{
		$aNodes = ModuleFileParser::GetInstance()->ParsePhpCode($sPHP);
		/** @var \PhpParser\Node\Expr $oExpr */
		$oExpr = $aNodes[0];
		$val = $this->InvokeNonPublicMethod(ModuleFileParser::class, "EvaluateExpression", ModuleFileParser::GetInstance(), [$oExpr->cond]);
		$this->assertEquals($bExpected, $val);
	}
}