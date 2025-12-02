<?php

/**
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core\DBObject;

use Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase;
use DBObject\Utils\ClassesWithDebug;
use DBObject\Utils\CRUDEventReceiver;
use DBObject\Utils\EventTest;
use IssueLog;
use LogChannels;
use MetaModel;
use utils;

use const EVENT_DB_LINKS_CHANGED;

class CRUDEventWithModifiedDataModelTest extends ItopCustomDatamodelTestCase
{
	use EventTest;
	use ClassesWithDebug;
	public function GetDatamodelDeltaAbsPath(): string
	{
		return __DIR__.'/Delta/dbobjecttest.xml';
	}

	public const USE_TRANSACTION = true;
	public const CREATE_TEST_ORG = false;

	private static string $sLogFile = 'log/test_error_CRUDEventTest.log';

	protected function setUp(): void
	{
		static::CleanCallCount();
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

	/*
	 * Test that when an object is deleted while having a link set with php computation
	 * linked to a nullable external key, the db_links_changed event is not fired
	 */
	public function testDBLinksChangedNotCalledOnDeletedObjects()
	{
		$oEventReceiver = new CRUDEventReceiver($this);
		$oEventReceiver->RegisterCRUDEventListeners(EVENT_DB_LINKS_CHANGED, 'TestDBObject');

		$sObjectParentKey = $this->GivenObjectInDB('TestDBObject', ['name' => 'parent']);
		$oChild = $this->createObject('TestDBObject', ['name' => 'child', 'parent_id' => $sObjectParentKey]);
		$this->AssertEventCountEquals(1, EVENT_DB_LINKS_CHANGED, 'Event EVENT_DB_LINKS_CHANGED should have been thrown on child creation');

		$oParent = MetaModel::GetObject('TestDBObject', $sObjectParentKey);
		static::CleanCallCount();
		$oParent->DBDelete();

		$oChild->Reload();
		$this->assertEquals(0, $oChild->Get('parent_id'));
		$this->AssertEventCountEquals(0, EVENT_DB_LINKS_CHANGED, 'Event EVENT_DB_LINKS_CHANGED should not have been thrown on deleted objects');
	}
}
