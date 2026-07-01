<?php

namespace Combodo\iTop\Test\UnitTest\Setup\FeatureRemoval;

use Combodo\iTop\Setup\FeatureRemoval\ModelReflectionSerializer;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use ContextTag;
use MetaModel;

class ModelSerializationTest extends ItopDataTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('/setup/feature_removal/ModelReflectionSerializer.php');
	}

	public function testGetModelFromEnvironment()
	{
		$aModel = ModelReflectionSerializer::GetInstance()->GetModelFromEnvironment($this->GetTestEnvironment());
		$this->assertEqualsCanonicalizing(MetaModel::GetClasses(), $aModel);
	}

	public function testCheckFail()
	{
		$sOuput = <<<OUTPUT
PHP 7.4.33 (cli) (built: Aug 2 2024 16:22:28) ( NTS )
OUTPUT;

		$this->expectException(\CoreException::class);
		$this->expectExceptionMessage("Data consistency check failed: Invalid PHP versions (CLI: 7.4/ UI: 6.6)");
		ModelReflectionSerializer::GetInstance()->CheckCliPhpVersionFromOutput('6.6', 'sPHPExec', [$sOuput]);
	}

	public function testCheckOK()
	{
		$sOuput = <<<OUTPUT
PHP 7.4.33 (cli) (built: Aug 2 2024 16:22:28) ( NTS )
OUTPUT;

		ModelReflectionSerializer::GetInstance()->CheckCliPhpVersionFromOutput('7.4', 'sPHPExec', [$sOuput]);
		$this->assertTrue(true);
	}

	public function testGetModelFromEnvironmentFailure_NoEnvt()
	{
		$this->expectException(\CoreException::class);
		$sEnvDir = APPROOT."env-gabuzomeu";
		$this->expectExceptionMessage("Data consistency check failed: Missing environment ($sEnvDir)");
		ModelReflectionSerializer::GetInstance()->GetModelFromEnvironment('gabuzomeu');
	}

	public function testGetModelFromEnvironmentFailure_NoConfiguration()
	{
		$sEnvDir = APPROOT."env-gabuzomeu";
		$this->aFileToClean [] = $sEnvDir;
		mkdir($sEnvDir);

		$this->expectException(\CoreException::class);
		$sConfigFile = APPROOT."conf/gabuzomeu/config-itop.php";
		$this->expectExceptionMessage("Data consistency check failed: Missing configuration ($sConfigFile)");
		ModelReflectionSerializer::GetInstance()->GetModelFromEnvironment('gabuzomeu');
	}

	public function testGetModelFromEnvironmentFailure_BrokenConfiguration()
	{
		$sEnvDir = APPROOT."env-gabuzomeu";
		mkdir($sEnvDir);
		$this->aFileToClean [] = $sEnvDir;

		mkdir(APPROOT."conf/gabuzomeu");
		$this->aFileToClean [] = APPROOT."conf/gabuzomeu";
		$sConfigFile = APPROOT."conf/gabuzomeu/config-itop.php";
		touch($sConfigFile);
		file_put_contents($sConfigFile, 'invalid php content...');

		$this->expectException(\CoreException::class);
		$sError = <<<ERROR
Syntax error in configuration file: file = $sConfigFile, error = <tt>invalid php content...</tt>
ERROR;

		$this->expectExceptionMessage("Data consistency check failed: $sError");
		ModelReflectionSerializer::GetInstance()->GetModelFromEnvironment('gabuzomeu');
	}

	public function testGetModelFromEnvironmentFailure_ItopInMaintenanceMode()
	{
		touch(MAINTENANCE_MODE_FILE);
		$this->aFileToClean [] = MAINTENANCE_MODE_FILE;

		$this->expectException(\CoreException::class);
		$sError = <<<ERROR
This application is currently under maintenance.
ERROR;

		$this->expectExceptionMessage("Data consistency check failed: $sError");
		ModelReflectionSerializer::GetInstance()->GetModelFromEnvironment($this->GetTestEnvironment());
	}
}
