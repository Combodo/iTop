<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DOMFormatException;
use Exception;
use RunTimeEnvironment;

class RunTimeEnvironmentTest extends ItopTestCase
{
	private ?string $sFixtureRootDir = null;
	private ?string $sBuildDirToCleanup = null;

	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('/setup/runtimeenv.class.inc.php');
	}

	protected function tearDown(): void
	{
		if (($this->sFixtureRootDir !== null) && is_dir($this->sFixtureRootDir)) {
			self::RecurseRmdir($this->sFixtureRootDir);
		}
		if (($this->sBuildDirToCleanup !== null) && is_dir($this->sBuildDirToCleanup)) {
			self::RecurseRmdir($this->sBuildDirToCleanup);
		}

		parent::tearDown();
	}

	public function testDoCompileDoesNotThrowWhenUnselectedExtensionCodeIsMissing(): void
	{
		[$sToken, $sSourceDirRelative, $sExtensionsDirRelative] = $this->CreateFixtureContext('runtimeenv-missing-code-');

		$sInvalidExtensionXml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<extension format="1.0">
	<label>Broken extension</label>
	<description>Test extension without code</description>
	<version>1.0.0</version>
	<mandatory>false</mandatory>
	<more_info_url></more_info_url>
</extension>
XML;
		file_put_contents(APPROOT.$sExtensionsDirRelative.'/extension.xml', $sInvalidExtensionXml);
		$oRunTimeEnvironment = $this->CreateRunTimeEnvironment($sToken);

		//early DOMFormatException to avoid any real compilation
		$this->expectException(DOMFormatException::class);
		$oRunTimeEnvironment->DoCompile([], [], [], $sSourceDirRelative, $sExtensionsDirRelative);
	}

	public function testDoCompileThrowsWhenSelectedExtensionCodeIsMissing(): void
	{
		[$sToken, $sSourceDirRelative, $sExtensionsDirRelative] = $this->CreateFixtureContext('runtimeenv-missing-code-');

		$sInvalidExtensionXml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<extension format="1.0">
	<label>Broken extension</label>
	<description>Test extension without code</description>
	<version>1.0.0</version>
	<mandatory>false</mandatory>
	<more_info_url></more_info_url>
</extension>
XML;
		file_put_contents(APPROOT.$sExtensionsDirRelative.'/extension.xml', $sInvalidExtensionXml);
		$oRunTimeEnvironment = $this->CreateRunTimeEnvironment($sToken);

		$this->expectException(Exception::class);
		$this->expectExceptionMessage('Selected extension(s) cannot be installed: Missing extension code');

		$oRunTimeEnvironment->DoCompile([""], [], [], $sSourceDirRelative, $sExtensionsDirRelative);
	}

	public function testDoCompileThrowsWhenSelectedExtensionCodeAndLabelAreMissing(): void
	{
		[$sToken, $sSourceDirRelative, $sExtensionsDirRelative] = $this->CreateFixtureContext('runtimeenv-missing-label-');

		$sInvalidExtensionXml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<extension format="1.0">
	<description>Test extension without code and label</description>
	<version>1.0.0</version>
	<mandatory>false</mandatory>
	<more_info_url></more_info_url>
</extension>
XML;
		$sExtensionsDirAbsolute = APPROOT.$sExtensionsDirRelative;
		file_put_contents($sExtensionsDirAbsolute.'/extension.xml', $sInvalidExtensionXml);
		$oRunTimeEnvironment = $this->CreateRunTimeEnvironment($sToken);

		$this->expectException(Exception::class);
		$this->expectExceptionMessage('Selected extension(s) cannot be installed: Missing extension code');

		$oRunTimeEnvironment->DoCompile([""], [], [], $sSourceDirRelative, $sExtensionsDirRelative, false);
	}

	private function CreateFixtureContext(string $sTokenPrefix): array
	{
		$sToken = str_replace('.', '-', uniqid($sTokenPrefix, true));
		$this->sFixtureRootDir = APPROOT.'data/'.$sToken;
		$sSourceDirRelative = 'data/'.$sToken.'/source';
		$sExtensionsDirRelative = 'data/'.$sToken.'/extensions';

		mkdir(APPROOT.$sSourceDirRelative, 0777, true);
		mkdir(APPROOT.$sExtensionsDirRelative, 0777, true);

		return [$sToken, $sSourceDirRelative, $sExtensionsDirRelative];
	}

	private function CreateRunTimeEnvironment(string $sToken): RunTimeEnvironment
	{
		$oRunTimeEnvironment = new RunTimeEnvironment('test-'.$sToken, false);
		$this->sBuildDirToCleanup = $oRunTimeEnvironment->GetBuildDir();

		return $oRunTimeEnvironment;
	}
}
