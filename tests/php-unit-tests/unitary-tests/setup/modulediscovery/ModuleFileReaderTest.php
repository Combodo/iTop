<?php

namespace Combodo\iTop\Test\UnitTest\Setup\ModuleDiscovery;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use ModuleFileReader;

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

	public function testAllReadModuleFileConfiguration()
	{
		$_SERVER=[
			'SERVER_NAME' => 'titi'
		];

		$aErrors=[];
		foreach (glob(__DIR__.'/resources/all_designer/*.php') as $sModuleFilePath){
			//var_dump($sModuleFilePath);
			try{
				$aRes = ModuleFileReader::GetInstance()->ReadModuleFileInformation($sModuleFilePath);
			} catch(\Exception $e){
				$aErrors[]=basename($sModuleFilePath);
				continue;
			}

			$aExpected = ModuleFileReader::GetInstance()->ReadModuleFileInformationUnsafe($sModuleFilePath);

			if ($aExpected !== $aRes){
				$aErrors[]=basename($sModuleFilePath);
				continue;
			}
			//break;
			//$this->assertEquals($aExpected, $aRes, $sModuleFilePath);
		}

		$this->assertEquals([], $aErrors, var_export($aErrors, true));
	}

	public static function ReadModuleFileConfigurationFileNameProvider()
	{
		return [
			'nominal case : module.itop-full-itil.php' => ['module.itop-full-itil.php'],
			'constant as value of a dict entry: module.authent-ldap.php' => ['module.authent-ldap.php'],
			'int operation evaluation required: email-synchro' => ['module.combodo-email-synchro.php'],
			'module.itop-admin-delegation-profiles-bridge-for-combodo-email-synchro.php' => ['module.itop-admin-delegation-profiles-bridge-for-combodo-email-synchro.php'],
			'unknown class name to evaluation as installer: module.itop-global-requests-mgmt.php' => ['module.itop-global-requests-mgmt.php'],
		];
	}

	/**
	 * @dataProvider ReadModuleFileConfigurationFileNameProvider
	 */
	public function testReadModuleFileConfigurationVsLegacyMethod(string $sModuleBasename)
	{
		$sModuleFilePath = __DIR__."/resources/$sModuleBasename";
		$aRes = ModuleFileReader::GetInstance()->ReadModuleFileInformation($sModuleFilePath);
		$aExpected = ModuleFileReader::GetInstance()->ReadModuleFileInformationUnsafe($sModuleFilePath);

		$this->assertEquals($aExpected, $aRes);
	}

	/**
	 * Covers below legacy usecase
	 * 'dependencies' => array(
	 * 'itop-config-mgmt/2.0.0'||'itop-structure/3.0.0',
	 * 'itop-request-mgmt/2.0.0||itop-request-mgmt-itil/2.0.0||itop-incident-mgmt-itil/2.0.0',
	 * ),
	 *
	 * @param string $sModuleBasename
	 *
	 * @return void
	 * @throws \ModuleFileReaderException
	 */
	public function testReadModuleFileConfiguration_BadlyWrittenDependencies(){
		//$sModuleFilePath = __DIR__."/resources/module.combodo-make-it-vip.php";
		$sModuleFilePath = __DIR__."/resources/module.itop-admin-delegation-profiles.php";
		$aRes = ModuleFileReader::GetInstance()->ReadModuleFileInformation($sModuleFilePath);
		$aExpected = ModuleFileReader::GetInstance()->ReadModuleFileInformationUnsafe($sModuleFilePath);

		$this->assertEquals($aExpected, $aRes);
	}

	public function testReadModuleFileConfigurationParsingIssue()
	{
		$sModuleFilePath = __DIR__.'/resources/module.__MODULE__.php';

		$this->expectException(\ModuleFileReaderException::class);
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