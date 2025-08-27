<?php

namespace Combodo\iTop\Test\UnitTest\Setup\ModuleDiscovery;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use ModuleDiscoveryEvaluationService;
use ModuleDiscoveryService;
use PhpParser\ParserFactory;

class ModuleDiscoveryEvaluationServiceTest extends ItopDataTestCase
{
	private string $sTempModuleFilePath;
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('setup/modulediscovery/ModuleDiscoveryService.php');
	}

	public static function EvaluateBooleanExpressionProvider()
	{
		return [
			"true" => [ "expr" => "true", "expected" => true],
			"(true)" => [ "expr" => "(true)", "expected" => true],
			"(false||true)" => [ "expr" => "(false||true)", "expected" => true],
			"false" => [ "expr" => "false", "expected" => false],
			"(false)" => [ "expr" => "(false)", "expected" => false],
			"(false&&true)" => [ "expr" => "(false&&true)", "expected" => false],
		];
	}

	/**
	 * @dataProvider EvaluateBooleanExpressionProvider
	 */
	public function testEvaluateBooleanExpression(string $sBooleanExpression, bool $expected){
		$this->assertEquals($expected, ModuleDiscoveryEvaluationService::GetInstance()->EvaluateBooleanExpression($sBooleanExpression), $sBooleanExpression);
	}

	public function testEvaluateBooleanExpression_BrokenBooleanExpression(){
		$this->expectException(\ModuleDiscoveryServiceException::class);
		$this->expectExceptionMessage('Eval of \'(a || true)\' caused an error');
		$this->assertTrue(ModuleDiscoveryEvaluationService::GetInstance()->EvaluateBooleanExpression("(a || true)"));
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
		$this->assertEquals($expected, ModuleDiscoveryEvaluationService::GetInstance()->EvaluateBooleanExpression($sBooleanExpression), $sBooleanExpression);
	}

	public function testEvaluateConstantExpression()
	{
		$sPHP = <<<PHP
<?php
APPROOT;
PHP;
		$aNodes = ModuleDiscoveryEvaluationService::GetInstance()->ParsePhpCode($sPHP);
		/** @var \PhpParser\Node\Expr $oExpr */
		$oExpr = $aNodes[0];
		$val = $this->InvokeNonPublicMethod(ModuleDiscoveryEvaluationService::class, "EvaluateConstantExpression", ModuleDiscoveryEvaluationService::GetInstance(), [$oExpr->expr]);
		$this->assertEquals(APPROOT, $val);
	}

	public static function EvaluateExpressionBooleanProvider() {
		$sTruePHP = <<<PHP
<?php
if (COND){
	echo "toto";
}
PHP;

		return [
			"true" => [
				"code" => str_replace("COND", "true", $sTruePHP),
				"bool_expected" => true,

			],
			"false" => [
				"code" => str_replace("COND", "false", $sTruePHP),
				"bool_expected" => false,

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
		$aNodes = ModuleDiscoveryEvaluationService::GetInstance()->ParsePhpCode($sPHP);
		/** @var \PhpParser\Node\Expr $oExpr */
		$oExpr = $aNodes[0];
		$val = $this->InvokeNonPublicMethod(ModuleDiscoveryEvaluationService::class, "EvaluateExpression", ModuleDiscoveryEvaluationService::GetInstance(), [$oExpr->cond]);
		$this->assertEquals($bExpected, $val);
	}
}