<?php

namespace Combodo\iTop\Test\UnitTest\Setup\ModuleDiscovery;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use ModuleDiscoveryService;
use PhpParser\ParserFactory;

class ModuleDiscoveryServiceTest extends ItopDataTestCase
{
	private string $sTempModuleFilePath;
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('setup/modulediscovery/ModuleDiscoveryService.php');
	}

	public function testReadModuleFileConfigurationLegacy()
	{
		$sModuleFilePath = __DIR__.'/resources/module.itop-full-itil.php';
		$aRes = ModuleDiscoveryService::GetInstance()->ReadModuleFileConfiguration($sModuleFilePath);

		$this->assertCount(3, $aRes);
		$this->assertEquals($sModuleFilePath, $aRes[0]);
		$this->assertEquals('itop-full-itil/3.3.0', $aRes[1]);
		$this->assertIsArray($aRes[2]);
		$this->assertArrayHasKey('label', $aRes[2]);
		$this->assertEquals('Bridge - Request management ITIL + Incident management ITIL', $aRes[2]['label'] ?? null);
	}

	/*public function testAllReadModuleFileConfiguration()
	{
		foreach (glob(__DIR__.'/resources/all/module.*.php') as $sModuleFilePath){
			$aRes = ModuleDiscoveryService::GetInstance()->ReadModuleFileConfiguration($sModuleFilePath);
			$aExpected = ModuleDiscoveryService::GetInstance()->ReadModuleFileConfigurationLegacy($sModuleFilePath);

			$this->assertEquals($aExpected, $aRes);

			$aAutoselect = $aRes[2]['auto_select'] ?? "";
			if (strlen($aAutoselect) >0){
				var_dump($aAutoselect);
			}
		}
	}*/

	public function testReadModuleFileConfiguration()
	{
		$sModuleFilePath = __DIR__.'/resources/module.itop-full-itil.php';
		$aRes = ModuleDiscoveryService::GetInstance()->ReadModuleFileConfiguration($sModuleFilePath);
		$aExpected = ModuleDiscoveryService::GetInstance()->ReadModuleFileConfigurationLegacy($sModuleFilePath);

		$this->assertEquals($aExpected, $aRes);
	}

	public function testReadModuleFileConfigurationWithConstants()
	{
		$sModuleFilePath = __DIR__.'/resources/module.authent-ldap.php';
		$aRes = ModuleDiscoveryService::GetInstance()->ReadModuleFileConfiguration($sModuleFilePath);
		$aExpected = ModuleDiscoveryService::GetInstance()->ReadModuleFileConfigurationLegacy($sModuleFilePath);

		$this->assertEquals($aExpected, $aRes);
	}

	public function testReadModuleFileConfigurationParsingIssue()
	{
		$sModuleFilePath = __DIR__.'/resources/module.__MODULE__.php';

		$this->expectException(\ModuleDiscoveryServiceException::class);
		$this->expectExceptionMessage("Syntax error, unexpected T_CONSTANT_ENCAPSED_STRING, expecting ',' or ']' or ')' on line 31");

		ModuleDiscoveryService::GetInstance()->ReadModuleFileConfiguration($sModuleFilePath);
	}


	public static function ComputeBooleanExpressionProvider()
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
	 * @dataProvider ComputeBooleanExpressionProvider
	 */
	public function testComputeBooleanExpression(string $sBooleanExpression, bool $expected){
		$this->assertEquals($expected, ModuleDiscoveryService::GetInstance()->ComputeBooleanExpression($sBooleanExpression), $sBooleanExpression);
	}

	public function testComputeBooleanExpression_BrokenBooleanExpression(){
		$this->expectException(\ModuleDiscoveryServiceException::class);
		$this->expectExceptionMessage('Eval of \'(a || true)\' caused an error');
		$this->assertTrue(ModuleDiscoveryService::GetInstance()->ComputeBooleanExpression("(a || true)"));
	}


	public static function ComputeBooleanExpressionAutoselectProvider()
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
	 * @dataProvider ComputeBooleanExpressionAutoselectProvider
	 */
	public function testComputeBooleanExpressionAutoselect(string $sBooleanExpression, bool $expected){
		\SetupInfo::SetSelectedModules(["itop-storage-mgmt" => "123"]);
		$this->assertEquals($expected, ModuleDiscoveryService::GetInstance()->ComputeBooleanExpression($sBooleanExpression), $sBooleanExpression);
	}

	public function testEvaluateConstantExpression()
	{
		$sPHP = <<<PHP
<?php
APPROOT;
PHP;
		$aNodes = ModuleDiscoveryService::GetInstance()->parsePhpCode($sPHP);
		/** @var \PhpParser\Node\Expr $oExpr */
		$oExpr = $aNodes[0];
		$val = $this->InvokeNonPublicMethod(ModuleDiscoveryService::class, "EvaluateConstantExpression", ModuleDiscoveryService::GetInstance(), [$oExpr->expr]);
		$this->assertEquals(APPROOT, $val);
	}

	public function CallReadModuleFileConfiguration($sPHpCode)
	{
		$this->sTempModuleFilePath = tempnam(__DIR__, "test");
		file_put_contents($this->sTempModuleFilePath, $sPHpCode);
		try {
			return ModuleDiscoveryService::GetInstance()->ReadModuleFileConfiguration($this->sTempModuleFilePath);
		}
		finally {
			@unlink($this->sTempModuleFilePath);
		}
	}

	public function testReadModuleFileConfigurationCheckBasicStatementWithoutIf()
	{
		$sPHP = <<<PHP
<?php
\$a=1;
SetupWebPage::AddModule("a", "noif", ["c" => "d"]);
\$b=2;
PHP;
		$val = $this->CallReadModuleFileConfiguration($sPHP);
		$this->assertEquals([$this->sTempModuleFilePath, "noif", ["c" => "d", 'module_file_path' => $this->sTempModuleFilePath]], $val);
	}

	public function testReadModuleFileConfigurationCheckBasicStatement_IfConditionVerified()
	{
		$sPHP = <<<PHP
<?php
\$a=1;
if (true){
	SetupWebPage::AddModule("a", "if", ["c" => "d"]);
} elseif (true){
	SetupWebPage::AddModule("a", "elseif1", ["c" => "d"]);
} elseif (true){
	SetupWebPage::AddModule("a", "elseif2", ["c" => "d"]);
} else {
	SetupWebPage::AddModule("a", "else", ["c" => "d"]);
}
SetupWebPage::AddModule("a", "outsideif", ["c" => "d"]);
\$b=2;
PHP;
		$val = $this->CallReadModuleFileConfiguration($sPHP);
		$this->assertEquals([$this->sTempModuleFilePath, "if", ["c" => "d", 'module_file_path' => $this->sTempModuleFilePath]], $val);
	}

	public function testReadModuleFileConfigurationCheckBasicStatement_IfNoConditionVerifiedAndNoElse()
	{
		$sPHP = <<<PHP
<?php
\$a=1;
if (false){
	SetupWebPage::AddModule("a", "if", ["c" => "d"]);
} elseif (false){
	SetupWebPage::AddModule("a", "elseif1", ["c" => "d"]);
} elseif (false){
	SetupWebPage::AddModule("a", "elseif2", ["c" => "d"]);
}
SetupWebPage::AddModule("a", "outsideif", ["c" => "d"]);
\$b=2;
PHP;
		$val = $this->CallReadModuleFileConfiguration($sPHP);
		$this->assertEquals([$this->sTempModuleFilePath, "outsideif", ["c" => "d", 'module_file_path' => $this->sTempModuleFilePath]], $val);
	}

	public function testReadModuleFileConfigurationCheckBasicStatement_ElseApplied()
	{
		$sPHP = <<<PHP
<?php
\$a=1;
if (false){
	SetupWebPage::AddModule("a", "if", ["c" => "d"]);
} elseif (false){
	SetupWebPage::AddModule("a", "elseif1", ["c" => "d"]);
} elseif (false){
	SetupWebPage::AddModule("a", "elseif2", ["c" => "d"]);
} else {
	SetupWebPage::AddModule("a", "else", ["c" => "d"]);
}
SetupWebPage::AddModule("a", "outsideif", ["c" => "d"]);
\$b=2;
PHP;
		$val = $this->CallReadModuleFileConfiguration($sPHP);
		$this->assertEquals([$this->sTempModuleFilePath, "else", ["c" => "d", 'module_file_path' => $this->sTempModuleFilePath]], $val);
	}

	public function testReadModuleFileConfigurationCheckBasicStatement_FirstElseIfApplied()
	{
		$sPHP = <<<PHP
<?php
\$a=1;
if (false){
	SetupWebPage::AddModule("a", "if", ["c" => "d"]);
} elseif (true){
	SetupWebPage::AddModule("a", "elseif1", ["c" => "d"]);
} elseif (true){
	SetupWebPage::AddModule("a", "elseif2", ["c" => "d"]);
} else {
	SetupWebPage::AddModule("a", "else", ["c" => "d"]);
}
SetupWebPage::AddModule("a", "outsideif", ["c" => "d"]);
\$b=2;
PHP;
		$val = $this->CallReadModuleFileConfiguration($sPHP);
		$this->assertEquals([$this->sTempModuleFilePath, "elseif1", ["c" => "d", 'module_file_path' => $this->sTempModuleFilePath]], $val);
	}

	public function testReadModuleFileConfigurationCheckBasicStatement_LastElseIfApplied()
	{
		$sPHP = <<<PHP
<?php
\$a=1;
if (false){
	SetupWebPage::AddModule("a", "if", ["c" => "d"]);
} elseif (false){
	SetupWebPage::AddModule("a", "elseif1", ["c" => "d"]);
} elseif (true){
	SetupWebPage::AddModule("a", "elseif2", ["c" => "d"]);
} else {
	SetupWebPage::AddModule("a", "else", ["c" => "d"]);
}
SetupWebPage::AddModule("a", "outsideif", ["c" => "d"]);
\$b=2;
PHP;
		$val = $this->CallReadModuleFileConfiguration($sPHP);
		$this->assertEquals([$this->sTempModuleFilePath, "elseif2", ["c" => "d", 'module_file_path' => $this->sTempModuleFilePath]], $val);
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
	public function testEvaluateExpressionBoolean($sPHP, $bExpected)
	{
		$aNodes = ModuleDiscoveryService::GetInstance()->parsePhpCode($sPHP);
		/** @var \PhpParser\Node\Expr $oExpr */
		$oExpr = $aNodes[0];
		$val = $this->InvokeNonPublicMethod(ModuleDiscoveryService::class, "EvaluateBooleanExpression", ModuleDiscoveryService::GetInstance(), [$oExpr->cond]);
		$this->assertEquals($bExpected, $val);
	}

	public function testCallDeclaredInstaller()
	{
		$sModuleInstallerClass = "TicketsInstaller" . uniqid();
		$sPHpCode = file_get_contents(__DIR__.'/resources/module.itop-tickets.php');
		$sPHpCode = str_replace("TicketsInstaller", $sModuleInstallerClass, $sPHpCode);
		$this->sTempModuleFilePath = tempnam(__DIR__, "test");
		file_put_contents($this->sTempModuleFilePath, $sPHpCode);
		var_dump($sPHpCode);

		try {
			$this->assertFalse(class_exists($sModuleInstallerClass));
			$aModuleInfo = ModuleDiscoveryService::GetInstance()->ReadModuleFileConfiguration($this->sTempModuleFilePath);
			$this->assertFalse(class_exists($sModuleInstallerClass));

			ModuleDiscoveryService::GetInstance()->CallInstallerBeforeWritingConfigMethod(\MetaModel::GetConfig(), $aModuleInfo[2]);
		}
		finally {
			@unlink($this->sTempModuleFilePath);
		}

		$this->assertTrue(class_exists($sModuleInstallerClass));
	}
}