<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use Combodo\iTop\DataFeatureRemoval\Service\DataFeatureRemoverExtensionService;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use Config;
use CoreException;
use DOMFormatException;
use Exception;
use iTopExtensionsMap;
use RunTimeEnvironment;
use utils;

class RunTimeEnvironmentTest extends ItopTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('/setup/runtimeenv.class.inc.php');
	}

	public function testDoCompileCallCheckExtensionsValidity(): void
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

		$oRuntimeEnvironment = $this->CreateRunTimeEnvironment($sEnvironment);
		$oExtensionMap = $this->createMock(ItopExtensionsMap::class);
		$oExtensionMap->expects($this->once())->method('GetAllExtensions')->willReturn([]);

		$this->SetNonPublicStaticProperty(iTopExtensionsMap::class, 'aInstancesByEnvironment', [$oRuntimeEnvironment->GetBuildEnv() => $oExtensionMap]);

		$this->expectException(CoreException::class);

		$oExtensionMap->expects($this->once())->method('CheckExtensionsValidity')->willThrowException(new CoreException(''));
		$oRuntimeEnvironment->DoCompile([""], [], [], false);
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
