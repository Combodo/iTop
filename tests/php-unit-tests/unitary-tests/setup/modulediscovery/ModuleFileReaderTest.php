<?php

namespace Combodo\iTop\Test\UnitTest\Setup\ModuleDiscovery;

use Combodo\iTop\Setup\ModuleDiscovery\ModuleFileReaderException;
use Combodo\iTop\Setup\ModuleDiscovery\ModuleFileReader;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class ModuleFileReaderTest extends ItopDataTestCase
{
	private string $sTempModuleFilePath;
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('setup/modulediscovery/ModuleFileReader.php');
	}

	public function testReadModuleFileInformationUnsafe()
	{
		$sModuleFilePath = __DIR__.'/resources/module.itop-full-itil.php';
		$aRes = ModuleFileReader::GetInstance()->ReadModuleFileInformationUnsafe($sModuleFilePath);

		$this->assertCount(3, $aRes);
		$this->assertEquals($sModuleFilePath, $aRes[0]);
		$this->assertEquals('itop-full-itil/3.3.0', $aRes[1]);
		$this->assertIsArray($aRes[2]);
		$this->assertArrayHasKey('label', $aRes[2]);
		$this->assertEquals('Bridge - Request management ITIL + Incident management ITIL', $aRes[2]['label'] ?? null);
	}

	public static function ReadModuleFileConfigurationFileNameProvider()
	{
		$aUsecases=[];
		foreach (glob(__DIR__.'/resources/*.php') as $sModuleFilePath){
			if (false !== strpos($sModuleFilePath, "module.__MODULE__.php")){
				continue;
			}
			$aUsecases[basename($sModuleFilePath)]=[$sModuleFilePath];
		}

		return $aUsecases;
	}

	/**
	 * @dataProvider ReadModuleFileConfigurationFileNameProvider
	 */
	public function testReadModuleFileConfigurationVsLegacyMethod(string $sModuleFilePath)
	{
		$_SERVER=[
			'SERVER_NAME' => 'titi'
		];

		$aRes = ModuleFileReader::GetInstance()->ReadModuleFileInformation($sModuleFilePath);
		$aExpected = ModuleFileReader::GetInstance()->ReadModuleFileInformationUnsafe($sModuleFilePath);

		$this->assertEquals($aExpected, $aRes);
	}

	public function testReadModuleFileConfigurationParsingIssue()
	{
		$sModuleFilePath = __DIR__.'/resources/module.__MODULE__.php';

		$this->expectException(ModuleFileReaderException::class);
		$this->expectExceptionMessage("Syntax error, unexpected T_CONSTANT_ENCAPSED_STRING, expecting ',' or ']' or ')' on line 31");

		ModuleFileReader::GetInstance()->ReadModuleFileInformation($sModuleFilePath);
	}

	/**
	 * local tool function
	 */
	private function CallReadModuleFileConfiguration($sPHpCode)
	{
		$this->sTempModuleFilePath = tempnam(__DIR__, "test");
		file_put_contents($this->sTempModuleFilePath, $sPHpCode);
		try {
			return ModuleFileReader::GetInstance()->ReadModuleFileInformation($this->sTempModuleFilePath);
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

	public function testGetAndCheckModuleInstallerClass()
	{
		$sModuleInstallerClass = "TicketsInstaller" . uniqid();
		$sPHpCode = file_get_contents(__DIR__.'/resources/module.itop-tickets.php');
		$sPHpCode = str_replace("TicketsInstaller", $sModuleInstallerClass, $sPHpCode);
		$this->sTempModuleFilePath = tempnam(__DIR__, "test");
		file_put_contents($this->sTempModuleFilePath, $sPHpCode);
		var_dump($sPHpCode);

		try {
			$this->assertFalse(class_exists($sModuleInstallerClass));
			$aModuleInfo = ModuleFileReader::GetInstance()->ReadModuleFileInformation($this->sTempModuleFilePath);
			$this->assertFalse(class_exists($sModuleInstallerClass));

			$this->assertEquals($sModuleInstallerClass, ModuleFileReader::GetInstance()->GetAndCheckModuleInstallerClass($aModuleInfo[2]));
		}
		finally {
			@unlink($this->sTempModuleFilePath);
		}

		$this->assertTrue(class_exists($sModuleInstallerClass));
	}
}