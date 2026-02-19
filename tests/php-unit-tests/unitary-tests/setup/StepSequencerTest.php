<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use ApplicationInstallSequencerFake;
use DataAuditSequencerFake;
use PHPParameters;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use SetupUtils;

class StepSequencerTest extends ItopTestCase
{
	protected function setUp(): void
	{
		static::LoadRequiredItopFiles();

		parent::setUp();

		$this->RequireOnceItopFile('/setup/sequencers/ApplicationInstallSequencer.php');
		$this->RequireOnceItopFile('/setup/sequencers/DataAuditSequencer.php');
		$this->RequireOnceItopFile('/setup/parameters.class.inc.php');
		$this->RequireOnceItopFile('/setup/setuputils.class.inc.php');
		require_once __DIR__.'/ApplicationInstallSequencerFake.php';
		require_once __DIR__.'/DataAuditSequencerFake.php';
	}

	public function testApplicationInstallSequencer()
	{
		$oParams = new PHPParameters();
		$oParams->LoadFromHash([]);

		$oInstallSequencer = new ApplicationInstallSequencerFake($oParams);
		$oInstallSequencer->ExecuteAllSteps();

		$aStepSequence = [
			'',
			'copy',
			'compile',
			'db-schema',
			'after-db-create',
			'load-data',
			'create-config',
		];
		$this->AssertStepsHistoryIs($aStepSequence, $oInstallSequencer);
	}

	public function testDataAuditSequencer()
	{
		$oParams = new PHPParameters();
		$oParams->LoadFromHash([]);

		$oInstallSequencer = new DataAuditSequencerFake($oParams);
		$oInstallSequencer->ExecuteAllSteps();

		$aStepSequence = [
			'',
			'compile',
			'write-config',
			'setup-audit',
			'cleanup',
		];
		$this->AssertStepsHistoryIs($aStepSequence, $oInstallSequencer);
	}

	protected function AssertStepsHistoryIs($aExpectedStepSequence, $oSequencer)
	{

		$aHistory = $oSequencer->GetHistory();
		$aStepSequence = [];
		foreach ($aHistory as $aStep) {
			$aStepSequence[] = $aStep['step'];
		}
		$this->assertEquals($aExpectedStepSequence, $aStepSequence, 'The step sequence should be '.implode(',', $aExpectedStepSequence));
	}
}
