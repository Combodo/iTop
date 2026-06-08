<?php

namespace Combodo\iTop\Test\UnitTest\Setup\Sequencers;

use ApplicationInstallSequencer;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use Config;
use PHPParameters;
use RunTimeEnvironment;

class ApplicationInstallerSequencerTest extends ItopTestCase
{
	private \RunTimeEnvironment&\PHPUnit\Framework\MockObject\MockObject $oRunTimeEnvironment;
	private \Config&\PHPUnit\Framework\MockObject\MockObject $oConfig;
	private ApplicationInstallSequencer $oSequencer;

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

	public static function FirstStepProvider()
	{
		return [
			'next is db-update' => [
				'next-step' => 'db-schema',
				'next-step-label' => 'Updating database schema',
				'prev-step-success-message' => '',
				'percentage-completed' => 16,
				'optional_steps' => [],
			],
			'next is log-parameters' => [
				'next-step' => 'log-parameters',
				'next-step-label' => 'Log parameters',
				'prev-step-success-message' => '',
				'percentage-completed' => 11,
				'optional_steps' => [
					'log-parameters' => true,
					'backup' => true,
					'migrate-before' => true,
				],
			],
			'next is backup' => [
				'next-step' => 'backup',
				'next-step-label' => 'Performing a backup of the database',
				'prev-step-success-message' => '',
				'percentage-completed' => 12,
				'optional_steps' => [
					'backup' => true,
					'migrate-before' => true,
				],
			],
			'next is migrate-before' => [
				'next-step' => 'migrate-before',
				'next-step-label' => 'Migrate data before database upgrade',
				'prev-step-success-message' => '',
				'percentage-completed' => 14,
				'optional_steps' => [
					'migrate-before' => true,
				],
				'bCallEnterMaintenanceMode' => true,
			],
		];
	}

	/**
	 * @dataProvider FirstStepProvider
	 */
	public function testFirstStep($sNextStep, $sNextLabel, $sPrevStepSuccessMessage, $iPercent, $aOptionalSteps, bool $bCallEnterMaintenanceMode = false, bool $bCallExitMaintenanceMode = false)
	{
		$aAdditionalParams = [
			'optional_steps' => $aOptionalSteps,
		];
		$this->GivenApplicationInstallSequencer($aAdditionalParams, $bCallEnterMaintenanceMode, $bCallExitMaintenanceMode);

		$aRes = $this->oSequencer->ExecuteStep();
		$aExpected = [
			'status' => 1,
			'message' => '',
			'next-step' => $sNextStep,
			'next-step-label' => $sNextLabel,
			'prev-step-success-message' => $sPrevStepSuccessMessage,
			'percentage-completed' => $iPercent,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	public function testLogStep()
	{
		$this->GivenApplicationInstallSequencer();

		$aRes = $this->oSequencer->ExecuteStep('log-parameters');
		$aExpected = [
			'status' => 1,
			'message' => '',
			'next-step' => 'migrate-before',
			'next-step-label' => 'Migrate data before database upgrade',
			'prev-step-success-message' => 'Parameters logged',
			'percentage-completed' => 22,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	public static function BackupStepProvider()
	{
		return [
			'next is db-update' => [
				'next-step' => 'db-schema',
				'next-step-label' => 'Updating database schema',
				'prev-step-success-message' => 'Database backup completed',
				'percentage-completed' => 28,
				'optional_steps' => [
					'backup' => true,
				],
			],
			'next is migrate-before' => [
				'next-step' => 'migrate-before',
				'next-step-label' => 'Migrate data before database upgrade',
				'prev-step-success-message' => 'Database backup completed',
				'percentage-completed' => 25,
				'optional_steps' => [
					'backup' => true,
					'migrate-before' => true,
				],
			],
		];
	}

	/**
	 * @dataProvider BackupStepProvider
	 */
	public function testBackup($sNextStep, $sNextLabel, $sPrevStepSuccessMessage, $iPercent, $aOptionalSteps)
	{
		$aAdditionalParams = [
			'optional_steps' => $aOptionalSteps,
		];
		$aAdditionalParams['optional_steps']['backup'] = [
			'destination' => '/my_backup_file_path',
			'configuration_file' => '/my_config_file_path',
		];

		$this->GivenApplicationInstallSequencer($aAdditionalParams);
		$this->oRunTimeEnvironment->expects($this->once())->method('Backup')
			->with($this->oConfig, '/my_backup_file_path', '/my_config_file_path', null);

		$aRes = $this->oSequencer->ExecuteStep('backup');
		$aExpected = [
			'status' => 1,
			'message' => '',
			'next-step' => $sNextStep,
			'next-step-label' => $sNextLabel,
			'prev-step-success-message' => $sPrevStepSuccessMessage,
			'percentage-completed' => $iPercent,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	public function testMigrateBefore()
	{
		$aAdditionalParams = [
			'optional_steps' => [
				'migrate-before' => true,
			],
		];
		$this->GivenApplicationInstallSequencer($aAdditionalParams, bCallEnterMaintenanceMode: true);

		$this->oRunTimeEnvironment->expects($this->once())->method('MigrateDataBeforeUpdateStructure')
			->with('install', $this->oConfig);

		$aRes = $this->oSequencer->ExecuteStep('migrate-before');
		$aExpected = [
			'status' => 1,
			'message' => '',
			'next-step' => 'db-schema',
			'next-step-label' => 'Updating database schema',
			'prev-step-success-message' => 'Pre-upgrade data migration completed',
			'percentage-completed' => 28,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	public static function DbUpdateStepProvider()
	{
		return [
			'next is migrate-after' => [
				'next-step' => 'migrate-after',
				'next-step-label' => 'Migrate data after database upgrade',
				'prev-step-success-message' => 'Database schema updated',
				'percentage-completed' => 28,
				'optional_steps' => [
					'migrate-after' => true,
				],
			],
			'next is after-db-create' => [
				'next-step' => 'after-db-create',
				'next-step-label' => 'Load data after database create',
				'prev-step-success-message' => 'Database schema updated',
				'percentage-completed' => 33,
				'optional_steps' => [],
			],
		];
	}

	/**
	 * @dataProvider DbUpdateStepProvider
	 */
	public function testDbUpdate($sNextStep, $sNextLabel, $sPrevStepSuccessMessage, $iPercent, $aOptionalSteps)
	{
		$aAdditionalParams = [
			'selected_modules' => ["a" => "b"],
			'optional_steps' => $aOptionalSteps,
		];
		$this->GivenApplicationInstallSequencer($aAdditionalParams);

		$this->oRunTimeEnvironment->expects($this->once())->method('UpdateDBSchema')
			->with($this->oConfig, 'install', ["a" => "b"]);
		$this->oRunTimeEnvironment->expects($this->once())->method('SetDbUUID')
			->with();

		$aRes = $this->oSequencer->ExecuteStep('db-schema');
		$aExpected = [
			'status' => 1,
			'message' => '',
			'next-step' => $sNextStep,
			'next-step-label' => $sNextLabel,
			'prev-step-success-message' => $sPrevStepSuccessMessage,
			'percentage-completed' => $iPercent,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	public function testMigrateAfter()
	{
		$aAdditionalParams = [
			'optional_steps' => [
				'migrate-after' => true,
			],
		];
		$this->GivenApplicationInstallSequencer($aAdditionalParams);

		$this->oRunTimeEnvironment->expects($this->once())->method('MigrateDataAfterUpdateStructure')
			->with('install', $this->oConfig);

		$aRes = $this->oSequencer->ExecuteStep('migrate-after');
		$aExpected = [
			'status' => 1,
			'message' => '',
			'next-step' => 'after-db-create',
			'next-step-label' => 'Load data after database create',
			'prev-step-success-message' => 'Post-upgrade data migration completed',
			'percentage-completed' => 42,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	public function testAfterDbCreate()
	{
		$aAdminParams = [
			'user' => "ga",
			'pwd' => "zo",
			'language' => "meu",
		];
		$aAdditionalParams = [
			'selected_modules' => ["a" => "b"],
			'admin_account' => $aAdminParams,
		];
		$this->GivenApplicationInstallSequencer($aAdditionalParams);

		$this->oRunTimeEnvironment->expects($this->once())->method('AfterDBCreate')
			->with($this->oConfig, 'install', ["a" => "b"], $aAdminParams);

		$aRes = $this->oSequencer->ExecuteStep('after-db-create');
		$aExpected = [
			'status' => 1,
			'message' => '',
			'next-step' => 'load-data',
			'next-step-label' => 'Loading data',
			'prev-step-success-message' => 'Post-creation data loaded',
			'percentage-completed' => 66,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	public function testLoadData()
	{
		$aAdditionalParams = [
			'selected_modules' => ["a" => "b"],
			'sample_data' => 1,
		];
		$this->GivenApplicationInstallSequencer($aAdditionalParams);

		$this->oRunTimeEnvironment->expects($this->once())->method('DoLoadData')
			->with($this->oConfig, true, ["a" => "b"]);

		$aRes = $this->oSequencer->ExecuteStep('load-data');
		$aExpected = [
			'message' => '',
			'next-step' => 'create-config',
			'next-step-label' => 'Creating the configuration File',
			'prev-step-success-message' => 'Data loaded',
			'percentage-completed' => 77,
			'status' => 1,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	public function testCreateConfig()
	{
		$aAdditionalParams = [
			'datamodel_version' => "6.6.6",
			'selected_extensions' => ["c" => "d"],
			'selected_modules' => ["a" => "b"],
			'sample_data' => 1,
		];
		$this->GivenApplicationInstallSequencer($aAdditionalParams);
		$this->oRunTimeEnvironment->expects($this->once())->method('DoCreateConfig')
			->with($this->oConfig, "6.6.6", ["a" => "b"], ["c" => "d"], null);

		$aRes = $this->oSequencer->ExecuteStep('create-config');
		$aExpected = [
			'message' => '',
			'next-step' => 'commit',
			'next-step-label' => 'Finalize',
			'prev-step-success-message' => 'Configuration file created',
			'percentage-completed' => 88,
			'status' => 1,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	public function testCommit()
	{
		$this->GivenApplicationInstallSequencer(bCallExitMaintenanceMode: true);
		$this->oRunTimeEnvironment->expects($this->once())->method('Commit');
		$this->oRunTimeEnvironment->expects($this->once())->method('ExitReadOnlyMode');

		$aRes = $this->oSequencer->ExecuteStep('commit');
		$aExpected = [
			'message' => '',
			'next-step' => '',
			'next-step-label' => 'Completed',
			'prev-step-success-message' => '',
			'percentage-completed' => 100,
			'status' => 1,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	public function testAnyFailure()
	{
		$this->GivenApplicationInstallSequencer();
		$this->oRunTimeEnvironment->expects($this->once())->method('GetBuildEnv')
			->willThrowException(new \CoreException('SHADOK MSG'));

		$aRes = $this->oSequencer->ExecuteStep('db-schema');
		$aExpected = [
			'status' => 2,
			'message' => 'SHADOK MSG',
			'next-step' => '',
			'next-step-label' => '',
			'prev-step-success-message' => '',
			'percentage-completed' => 100,
			'error_code' => 0,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	public function testUnknownStep()
	{
		$this->GivenApplicationInstallSequencer();
		$aRes = $this->oSequencer->ExecuteStep('gabuzomeu');
		$aExpected = [
			'status' => 2,
			'message' => '',
			'next-step' => '',
			'next-step-label' => 'Unknown setup step \'gabuzomeu\'.',
			'prev-step-success-message' => '',
			'percentage-completed' => 100,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	private function GivenParams(array $aAdditionalParams = []): PHPParameters
	{
		$oParams = new PHPParameters();
		$aParams = array_merge_recursive([
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

	public function testGetStepNamesWithAllSteps()
	{
		$aAdditionalParams = [
			'optional_steps' => [
				'log-parameters' => true,
				'backup' => true,
				'migrate-before' => true,
				'migrate-after' => true,
			],
		];
		$this->GivenApplicationInstallSequencer($aAdditionalParams, true);

		$expected = [
			'',
			'log-parameters',
			'backup',
			'migrate-before',
			'db-schema',
			'migrate-after',
			'after-db-create',
			'load-data',
			'create-config',
			'commit',
		];
		$this->assertEquals($expected, $this->oSequencer->GetStepNames());
	}

	public static function WithoutOneStepProvider()
	{
		return [
			['log-parameters'],
			['backup'],
			['migrate-before'],
			['migrate-after'],
		];
	}
	/**
	 * @dataProvider WithoutOneStepProvider
	 */
	public function testAllWithoutOneStep($sMissingStepName)
	{
		$aAdditionalParams = [
			'optional_steps' => [
				'log-parameters' => true,
				'backup' => true,
				'migrate-before' => true,
				'migrate-after' => true,
			],
		];
		unset($aAdditionalParams['optional_steps'][$sMissingStepName]);
		$this->GivenApplicationInstallSequencer($aAdditionalParams, true);

		$expected = [
			'' => true,
			'log-parameters' => true,
			'backup' => true,
			'migrate-before' => true,
			'db-schema' => true,
			'migrate-after' => true,
			'after-db-create' => true,
			'load-data' => true,
			'create-config' => true,
			'commit' => true,
		];
		unset($expected[$sMissingStepName]);
		$this->assertEquals(array_keys($expected), $this->oSequencer->GetStepNames());
	}

	public function testGetStepNamesWithOnlyMandatorySteps()
	{
		$this->GivenApplicationInstallSequencer([], true);
		$expected = [
			'',
			'log-parameters',
			'migrate-before',
			'db-schema',
			'migrate-after',
			'after-db-create',
			'load-data',
			'create-config',
			'commit',
		];
		$this->assertEquals($expected, $this->oSequencer->GetStepNames());
	}

	public function testGetStepAfterWithPercent()
	{
		$this->GivenApplicationInstallSequencer([], true);
		$this->assertEquals(['log-parameters', 11], $this->oSequencer->GetStepAfterWithPercent(''));
		$this->assertEquals(['migrate-after', 44], $this->oSequencer->GetStepAfterWithPercent('db-schema'));
		$this->assertEquals(['load-data', 66], $this->oSequencer->GetStepAfterWithPercent('after-db-create'));
		$this->assertEquals(['create-config', 77], $this->oSequencer->GetStepAfterWithPercent('load-data'));
		$this->assertEquals(['commit', 88], $this->oSequencer->GetStepAfterWithPercent('create-config'));
		$this->assertEquals(['', 100], $this->oSequencer->GetStepAfterWithPercent('commit'));
	}

	private function GivenRunTimeEnvironment(bool $bStepComputationOnly = false, bool $bCallEnterMaintenanceMode = false, bool $bCallExitMaintenanceMode = false): void
	{
		$this->oRunTimeEnvironment = $this->createMock(RunTimeEnvironment::class);
		if (! $bStepComputationOnly) {
			$this->oRunTimeEnvironment->expects($this->once())->method('EnterReadOnlyMode')
				->with($this->oConfig);
		}

		$this->oRunTimeEnvironment->expects($this->exactly($bCallEnterMaintenanceMode ? 1 : 0))->method('EnterMaintenanceMode')
			->with($this->oConfig);

		$this->oRunTimeEnvironment->expects($this->exactly($bCallExitMaintenanceMode ? 1 : 0))->method('ExitMaintenanceMode');
	}

	private function GivenConfig(): void
	{
		$this->oConfig = $this->createMock(Config::class);
	}

	private function GivenApplicationInstallSequencer(array $aAdditionalParams = [], bool $bStepComputationOnly = false, bool $bCallEnterMaintenanceMode = false, bool $bCallExitMaintenanceMode = false): void
	{
		$this->GivenConfig();
		$this->GivenRunTimeEnvironment($bStepComputationOnly, $bCallEnterMaintenanceMode, $bCallExitMaintenanceMode);
		$this->oSequencer = new ApplicationInstallSequencer($this->GivenParams($aAdditionalParams), $this->oRunTimeEnvironment);
		$this->SetNonPublicProperty($this->oSequencer, 'oTestConfig', $this->oConfig);
	}
}
