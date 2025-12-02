<?php

/**
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core\DBObject;

use Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase;
use IssueLog;
use LogChannels;
use MetaModel;
use utils;

use const EVENT_DB_LINKS_CHANGED;

class DBObjectWithModifiedDataModelTest extends ItopCustomDatamodelTestCase
{
	public function GetDatamodelDeltaAbsPath(): string
	{
		return __DIR__.'/Delta/dbobjecttest.xml';
	}

	public const USE_TRANSACTION = true;
	public const CREATE_TEST_ORG = false;

	private static string $sLogFile = 'log/test_error_CRUDEventTest.log';

	protected function setUp(): void
	{
		parent::setUp();
		static::$DEBUG_UNIT_TEST = false;

		if (static::$DEBUG_UNIT_TEST) {
			echo '--- logging in '.APPROOT.static::$sLogFile."\n\n";
			@unlink(APPROOT.static::$sLogFile);
			IssueLog::Enable(APPROOT.static::$sLogFile);
			$oConfig = utils::GetConfig();
			$oConfig->Set('log_level_min', [LogChannels::DM_CRUD => 'Debug', LogChannels::EVENT_SERVICE => 'Trace']);
		}
	}

	protected function tearDown(): void
	{
		if (is_file(APPROOT.static::$sLogFile)) {
			$sLog = file_get_contents(APPROOT.static::$sLogFile);
			echo "--- error.log\n$sLog\n\n";
			@unlink(APPROOT.static::$sLogFile);
		}

		parent::tearDown();
	}

	public function testStimulusStoppingActionPreventObjectModification()
	{
		// Given
		$sObjectKey = $this->GivenObjectInDB('TestDBObject', ['name' => 'parent', 'status' => 'new']);
		$oParent = MetaModel::GetObject('TestDBObject', $sObjectKey);

		// When actions ApplyStimulus then next action fails
		$oParent->ApplyStimulus('ev_assign');
		$oParent->Reload();

		// Then
		// Check status...
		$this->assertEquals('new', $oParent->Get('status'), 'The status should have remained unmodified due to action failure');
	}

	public function testCallingApplyStimulusWithinActionsWorks()
	{
		// Given
		$sObjectKey = $this->GivenObjectInDB('TestDBObject', ['name' => 'parent', 'status' => 'assigned']);
		$oParent = MetaModel::GetObject('TestDBObject', $sObjectKey);

		// When action ApplyStimulus
		$oParent->ApplyStimulus('ev_reassign');
		$oParent->Reload();

		// Then
		// Check that status has changed to the final status
		$this->assertEquals('resolved', $oParent->Get('status'), 'The status should have been modified to resolved (the final state after a nested stimulus)');
	}
}
