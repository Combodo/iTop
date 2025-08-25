<?php

namespace Combodo\iTop\Test\UnitTest\Setup\ModuleDiscovery;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use ModuleDiscoveryService;

class ModuleDiscoveryServiceTest extends ItopDataTestCase
{
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
		$this->expectExceptionMessage('Eval of \'(a || true)\' caused an error: Undefined constant "a"');
		$this->assertTrue(ModuleDiscoveryService::GetInstance()->ComputeBooleanExpression("(a || true)"));
	}
}