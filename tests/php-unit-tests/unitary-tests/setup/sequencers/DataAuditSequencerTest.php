<?php

namespace Combodo\iTop\Test\UnitTest\Setup\Sequencers;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DataAuditSequencer;
use PHPParameters;

class DataAuditSequencerTest extends ItopTestCase
{
	protected function setUp(): void
	{
		static::LoadRequiredItopFiles();

		parent::setUp();

		$this->RequireOnceItopFile('/setup/sequencers/ApplicationInstallSequencer.php');
		$this->RequireOnceItopFile('/setup/sequencers/DataAuditSequencer.php');
		$this->RequireOnceItopFile('/setup/parameters.class.inc.php');
		$this->RequireOnceItopFile('/setup/setuputils.class.inc.php');
		$this->RequireOnceItopFile('/setup/runtimeenv.class.inc.php');
	}

	public function testDataAuditFirstStep()
	{
		$oRunTimeEnvironment = $this->createMock(\RunTimeEnvironment::class);
		$oRunTimeEnvironment->expects($this->once())->method('GetBuildEnv')
		->willReturn('production-test');
		$oSequencer = new DataAuditSequencer($this->GivenParams(), $oRunTimeEnvironment);

		$aRes = $oSequencer->ExecuteStep();
		$aExpected = [
			'status' => 1,
			'message' => '',
			'next-step' => 'copy',
			'next-step-label' => 'Copying data model files',
			'percentage-completed' => 5,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	public function testDataUnknownStep()
	{
		$oRunTimeEnvironment = $this->createMock(\RunTimeEnvironment::class);
		$oRunTimeEnvironment->expects($this->once())->method('GetBuildEnv')
			->willReturn('production-test');
		$oSequencer = new DataAuditSequencer($this->GivenParams(), $oRunTimeEnvironment);

		$aRes = $oSequencer->ExecuteStep('gabuzomeu');
		$aExpected = [
			'status' => 2,
			'message' => '',
			'next-step' => '',
			'next-step-label' => 'Unknown setup step \'gabuzomeu\'.',
			'percentage-completed' => 100,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	public function testCopy()
	{
		$oRunTimeEnvironment = $this->createMock(\RunTimeEnvironment::class);
		$oRunTimeEnvironment->expects($this->once())->method('CopySetupFiles');
		$oSequencer = new DataAuditSequencer($this->GivenParams(), $oRunTimeEnvironment);

		$aRes = $oSequencer->ExecuteStep('copy');
		$aExpected = [
			'status' => 1,
			'message' => 'Copying...',
			'next-step' => 'compile',
			'next-step-label' => 'Compiling the data model',
			'percentage-completed' => 20,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	public function testCompileWithAudit()
	{
		$oRunTimeEnvironment = $this->createMock(\RunTimeEnvironment::class);
		$oRunTimeEnvironment->expects($this->once())->method('GetApplicationVersion')
			->willReturn(['product_version' => ITOP_VERSION_FULL]);
		$oRunTimeEnvironment->expects($this->once())->method('DoCompile');
		$oRunTimeEnvironment->expects($this->once())->method('GetFinalEnv')
			->willReturn('production');

		$aAdditionalParams = [
			'mode' => 'update',
		];
		$oSequencer = new DataAuditSequencer($this->GivenParams($aAdditionalParams), $oRunTimeEnvironment);

		$aRes = $oSequencer->ExecuteStep('compile');
		$aExpected = [
			'status' => 1,
			'message' => '',
			'next-step' => 'setup-audit',
			'next-step-label' => 'Checking data consistency with the new data model',
			'percentage-completed' => 70,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	public function testCompileNoAuditInFreshInstall()
	{
		$oRunTimeEnvironment = $this->createMock(\RunTimeEnvironment::class);
		$oRunTimeEnvironment->expects($this->once())->method('DoCompile');
		$oSequencer = new DataAuditSequencer($this->GivenParams(), $oRunTimeEnvironment);

		$aRes = $oSequencer->ExecuteStep('compile');
		$aExpected = [
			'status' => 1,
			'message' => '',
			'next-step' => 'complete',
			'next-step-label' => 'Check Completed',
			'percentage-completed' => 100,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	public function testCompileNoAuditInUpgradeWithoutAnyRuntimeEnv()
	{
		$oRunTimeEnvironment = $this->createMock(\RunTimeEnvironment::class);
		$oRunTimeEnvironment->expects($this->once())->method('GetApplicationVersion')
			->willReturn(['product_version' => ITOP_VERSION_FULL]);
		$oRunTimeEnvironment->expects($this->once())->method('DoCompile');
		$oRunTimeEnvironment->expects($this->once())->method('GetFinalEnv')
			->willReturn('gabuzomeu');

		$aAdditionalParams = [
			'mode' => 'update',
		];
		$oSequencer = new DataAuditSequencer($this->GivenParams($aAdditionalParams), $oRunTimeEnvironment);

		$aRes = $oSequencer->ExecuteStep('compile');
		$aExpected = [
			'status' => 1,
			'message' => '',
			'next-step' => 'complete',
			'next-step-label' => 'Check Completed',
			'percentage-completed' => 100,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	public function testCompileFailureInFreshInstallNoAudit()
	{
		$oRunTimeEnvironment = $this->createMock(\RunTimeEnvironment::class);
		$oRunTimeEnvironment->expects($this->once())->method('DoCompile')
			->willThrowException(new \CoreException('SHADOK MSG'));
		$oSequencer = new DataAuditSequencer($this->GivenParams(), $oRunTimeEnvironment);

		$aRes = $oSequencer->ExecuteStep('compile');
		$aExpected = [
			'status' => 2,
			'message' => 'SHADOK MSG',
			'next-step' => '',
			'next-step-label' => '',
			'percentage-completed' => 100,
			'error_code' => 0,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	public function testAuditCalled()
	{
		$oRunTimeEnvironment = $this->createMock(\RunTimeEnvironment::class);
		$oRunTimeEnvironment->expects($this->once())->method('GetApplicationVersion')
			->willReturn(['product_version' => ITOP_VERSION_FULL]);
		$oRunTimeEnvironment->expects($this->once())->method('DataToCleanupAudit');
		$oRunTimeEnvironment->expects($this->any())->method('GetFinalEnv')
			->willReturn('production');

		$aAdditionalParams = [
			'mode' => 'update',
		];
		$oSequencer = new DataAuditSequencer($this->GivenParams($aAdditionalParams), $oRunTimeEnvironment);

		$aRes = $oSequencer->ExecuteStep('setup-audit');
		$aExpected = [
			'status' => 1,
			'message' => '',
			'next-step' => 'complete',
			'next-step-label' => 'Check Completed',
			'percentage-completed' => 100,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	public function testNoAuditInUpgradeModeWhenNoRuntimeEnvironmentAvailable()
	{
		$oRunTimeEnvironment = $this->createMock(\RunTimeEnvironment::class);
		$oRunTimeEnvironment->expects($this->once())->method('GetApplicationVersion')
			->willReturn(['product_version' => ITOP_VERSION_FULL]);
		$oRunTimeEnvironment->expects($this->never())->method('DataToCleanupAudit');
		$oRunTimeEnvironment->expects($this->once())->method('GetFinalEnv')
			->willReturn('gabuzomeu');

		$aAdditionalParams = [
			'mode' => 'update',
		];
		$oSequencer = new DataAuditSequencer($this->GivenParams($aAdditionalParams), $oRunTimeEnvironment);
		$aRes = $oSequencer->ExecuteStep('setup-audit');
		$aExpected = [
			'status' => 1,
			'message' => '',
			'next-step' => 'complete',
			'next-step-label' => 'Check Completed',
			'percentage-completed' => 100,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	public function testNoAuditInFreshInstallMode()
	{
		$oRunTimeEnvironment = $this->createMock(\RunTimeEnvironment::class);
		$oRunTimeEnvironment->expects($this->never())->method('GetFinalEnv')
			->willReturn('gabuzomeu');
		$oRunTimeEnvironment->expects($this->never())->method('DataToCleanupAudit');

		$oSequencer = new DataAuditSequencer($this->GivenParams(), $oRunTimeEnvironment);

		$aRes = $oSequencer->ExecuteStep('setup-audit');
		$aExpected = [
			'status' => 1,
			'message' => '',
			'next-step' => 'complete',
			'next-step-label' => 'Check Completed',
			'percentage-completed' => 100,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	private function GivenParams(array $aAdditionalParams = []): PHPParameters
	{
		$oParams = new PHPParameters();
		$aParams = array_merge([
			'mode' => 'install',
			'database' => [
				'server' => 'server',
				'user' => 'user',
				'pwd' => 'pwd',
				'name' => 'name',
				'prefix' => 'prefix',
				'db_tls_enabled' => 'db_tls_enabled',
				'db_tls_ca' => 'db_tls_ca',
			],
			'application_path' => '',
			'language' => '',
			'graphviz_path' => '',
			'source_dir' => '',
		], $aAdditionalParams);

		$oParams->LoadFromHash($aParams);
		return $oParams;
	}

	public function testIsDataAuditRequired_NoAuditInFreshInstall()
	{
		$this->oConfig = $this->createMock(\Config::class);

		$oRunTimeEnvironment = $this->createMock(\RunTimeEnvironment::class);
		$oRunTimeEnvironment->expects($this->once())->method("GetBuildEnv")->willReturn("production-test");

		$oSequencer = new DataAuditSequencer($this->GivenParams(), $oRunTimeEnvironment);
		$this->SetNonPublicProperty($oSequencer, 'oTestConfig', $this->oConfig);
		self::assertFalse($this->InvokeNonPublicMethod(DataAuditSequencer::class, "IsDataAuditRequired", $oSequencer));
	}

	public function testIsDataAuditRequired_NoAuditOnPackageUpgrade()
	{
		$oRunTimeEnvironment = $this->createMock(\RunTimeEnvironment::class);
		$oRunTimeEnvironment->expects($this->once())->method('GetApplicationVersion')
			->willReturn(['product_version' => ITOP_VERSION_FULL."_alpha"]);
		$oRunTimeEnvironment->expects($this->never())->method("GetFinalEnv");

		$oSequencer = new DataAuditSequencer($this->GivenParams(['mode' => 'upgrade']), $oRunTimeEnvironment);
		self::assertFalse($this->InvokeNonPublicMethod(DataAuditSequencer::class, "IsDataAuditRequired", $oSequencer));
	}

	public function testIsDataAuditRequired_NoAuditOnSystemChange()
	{
		$oRunTimeEnvironment = $this->createMock(\RunTimeEnvironment::class);
		$oRunTimeEnvironment->expects($this->once())->method('GetApplicationVersion')
			->willReturn(['product_version' => ITOP_VERSION_FULL]);
		$oRunTimeEnvironment->expects($this->once())->method("GetFinalEnv")->willReturn("production-gabuzomeu");

		$oSequencer = new DataAuditSequencer($this->GivenParams(['mode' => 'upgrade']), $oRunTimeEnvironment);
		self::assertFalse($this->InvokeNonPublicMethod(DataAuditSequencer::class, "IsDataAuditRequired", $oSequencer));
	}

	public function testIsDataAuditRequired_AuditTriggered()
	{
		$oRunTimeEnvironment = $this->createMock(\RunTimeEnvironment::class);
		$oRunTimeEnvironment->expects($this->once())->method('GetApplicationVersion')
			->willReturn(['product_version' => ITOP_VERSION_FULL]);
		$oRunTimeEnvironment->expects($this->once())->method("GetFinalEnv")->willReturn("production");

		$oSequencer = new DataAuditSequencer($this->GivenParams(['mode' => 'upgrade']), $oRunTimeEnvironment);
		self::assertTrue($this->InvokeNonPublicMethod(DataAuditSequencer::class, "IsDataAuditRequired", $oSequencer));
	}
}
