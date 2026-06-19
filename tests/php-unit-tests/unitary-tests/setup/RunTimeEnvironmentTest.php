<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DOMFormatException;
use Exception;
use RunTimeEnvironment;

class RunTimeEnvironmentTest extends ItopTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('/setup/runtimeenv.class.inc.php');
	}

	public function testDoCompileDoesNotThrowWhenUnselectedExtensionCodeIsMissing(): void
	{
		[$sEnvironment, $sExtensionsDirRelative] = $this->CreateFixtureContext('env-missing-code-');

		$sInvalidExtensionXml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<extension format="1.0">
	<label>Broken extension</label>
	<description>Test extension without code</description>
	<version>1.0.0</version>
	<mandatory>false</mandatory>
	<more_info_url/>
</extension>
XML;
		file_put_contents(APPROOT.$sExtensionsDirRelative.'/extension.xml', $sInvalidExtensionXml);
		$oRunTimeEnvironment = $this->CreateRunTimeEnvironment($sEnvironment);

		//early DOMFormatException to avoid any real compilation
		$this->expectException(DOMFormatException::class);
		$oRunTimeEnvironment->DoCompile([], [], []);
	}

	public function testDoCompileThrowsWhenSelectedExtensionCodeIsMissing(): void
	{
		[$sEnvironment, $sExtensionsDirRelative] = $this->CreateFixtureContext('env-missing-code-');

		$sInvalidExtensionXml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<extension format="1.0">
	<label>Broken extension</label>
	<description>Test extension without code</description>
	<version>1.0.0</version>
	<mandatory>false</mandatory>
	<more_info_url/>
</extension>
XML;
		file_put_contents(APPROOT.$sExtensionsDirRelative.'/extension.xml', $sInvalidExtensionXml);
		$oRunTimeEnvironment = $this->CreateRunTimeEnvironment($sEnvironment);

		$this->expectException(Exception::class);
		$this->expectExceptionMessage("Selected extension(s) cannot be installed: Missing extension code (Broken extension)");

		$oRunTimeEnvironment->DoCompile([""], [], []);
	}

	public function testDoCompileThrowsWhenSelectedExtensionCodeAndLabelAreMissing(): void
	{
		[$sEnvironment, $sExtensionsDirRelative] = $this->CreateFixtureContext('env-missing-label-');

		$sInvalidExtensionXml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<extension format="1.0">
	<description>Test extension without code and label</description>
	<version>1.0.0</version>
	<mandatory>false</mandatory>
	<more_info_url/>
</extension>
XML;
		$sExtensionsDirAbsolute = APPROOT.$sExtensionsDirRelative;
		file_put_contents($sExtensionsDirAbsolute.'/extension.xml', $sInvalidExtensionXml);
		$oRunTimeEnvironment = $this->CreateRunTimeEnvironment($sEnvironment);

		$this->expectException(Exception::class);
		$this->expectExceptionMessage("Selected extension(s) cannot be installed: Missing extension code ($sExtensionsDirAbsolute)");

		$oRunTimeEnvironment->DoCompile([""], [], [], false);
	}

	private function CreateFixtureContext(string $sEnvPrefix): array
	{
		$sEnvironment = str_replace('.', '-', uniqid($sEnvPrefix, true));
		$sExtensionsDirRelative = 'data/'.$sEnvironment.'-modules';

		mkdir(APPROOT.$sExtensionsDirRelative, 0777, true);
		$this->aFileToClean[] = APPROOT.$sExtensionsDirRelative;

		return [$sEnvironment, $sExtensionsDirRelative];
	}

	private function CreateRunTimeEnvironment(string $sEnvironment): RunTimeEnvironment
	{
		$oRunTimeEnvironment = new RunTimeEnvironment($sEnvironment, false);
		$this->aFileToClean[] = $oRunTimeEnvironment->GetBuildDir();

		return $oRunTimeEnvironment;
	}
}
