<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core\CRUD;

use Combodo\iTop\Service\Events\EventData;
use Combodo\iTop\Service\Events\EventService;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use ContactType;
use CoreException;
use DBObjectSet;
use DBSearch;
use lnkPersonToTeam;
use MetaModel;
use ormLinkSet;
use Person;
use Team;
use utils;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class CRUDEventTest extends ItopDataTestCase
{
	const USE_TRANSACTION = true;
	const CREATE_TEST_ORG = true;

	// Count the events by name
	private static array $aEventCalls = [];
	private static int $iEventCalls = 0;

	protected function setUp(): void
	{
		parent::setUp();
	}

	public static function IncrementCallCount(string $sEvent)
	{
		self::$aEventCalls[$sEvent] = (self::$aEventCalls[$sEvent] ?? 0) + 1;
		self::$iEventCalls++;
	}

	/**
	 * Check that the 3 events EVENT_DB_COMPUTE_VALUES, EVENT_DB_CHECK_TO_WRITE and EVENT_DB_CREATE_DONE are called on insert
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function testDBInsert()
	{
		$oEventReceiver = new CRUDEventReceiver();
		$oEventReceiver->RegisterCRUDListeners();

		$oOrg = $this->CreateOrganization('Organization1');
		$this->assertIsObject($oOrg);
		$this->assertEquals(1, self::$aEventCalls[EVENT_DB_COMPUTE_VALUES]);
		$this->assertEquals(1, self::$aEventCalls[EVENT_DB_CHECK_TO_WRITE]);
		$this->assertEquals(1, self::$aEventCalls[EVENT_DB_CREATE_DONE]);
		$this->assertEquals(3, self::$iEventCalls);
	}

	/**
	 * Check that the 3 events EVENT_DB_COMPUTE_VALUES, EVENT_DB_CHECK_TO_WRITE and EVENT_DB_UPDATE_DONE are called on update
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function testDBUpdate()
	{
		$oOrg = $this->CreateOrganization('Organization1');
		$this->assertIsObject($oOrg);

		$oEventReceiver = new CRUDEventReceiver();
		$oEventReceiver->RegisterCRUDListeners();

		$oOrg->Set('name', 'test');
		$oOrg->DBUpdate();

		$this->assertEquals(1, self::$aEventCalls[EVENT_DB_COMPUTE_VALUES]);
		$this->assertEquals(1, self::$aEventCalls[EVENT_DB_CHECK_TO_WRITE]);
		$this->assertEquals(1, self::$aEventCalls[EVENT_DB_UPDATE_DONE]);
		$this->assertEquals(3, self::$iEventCalls);
	}

	/**
	 * Check that only 1 event EVENT_DB_COMPUTE_VALUES is called on update when nothing is modified
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function testDBUpdateNothing()
	{
		$oOrg = $this->CreateOrganization('Organization1');
		$this->assertIsObject($oOrg);

		$oEventReceiver = new CRUDEventReceiver();
		$oEventReceiver->RegisterCRUDListeners();

		$oOrg->DBUpdate();

		$this->assertEquals(1, self::$aEventCalls[EVENT_DB_COMPUTE_VALUES]);
		$this->assertEquals(1, self::$iEventCalls);
	}

	/**
	 * Check that an object can be modified during EVENT_DB_COMPUTE_VALUES
	 * and the modifications are saved to the DB
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function testComputeValuesOnInsert()
	{
		$oEventReceiver = new CRUDEventReceiver();
		// Set the person's first name during Compute Values
		$oEventReceiver->AddCallback(EVENT_DB_COMPUTE_VALUES, Person::class, 'SetPersonFirstName');
		$oEventReceiver->RegisterCRUDListeners(EVENT_DB_COMPUTE_VALUES);

		$oPerson = $this->CreatePerson(1);
		$this->assertIsObject($oPerson);
		$this->assertEquals(1, self::$aEventCalls[EVENT_DB_COMPUTE_VALUES]);
		$this->assertEquals(1, self::$iEventCalls);

		// Read the object explicitly from the DB to check that the first name has been set
		$oSet = new DBObjectSet(DBSearch::FromOQL('SELECT Person WHERE id=:id'), [], ['id' => $oPerson->GetKey()]);
		$oPersonResult = $oSet->Fetch();
		$this->assertTrue(utils::StartsWith($oPersonResult->Get('first_name'), 'CRUD'));
	}

	/**
	 * Check that an object can be modified during EVENT_DB_COMPUTE_VALUES
	 * and the modifications are saved to the DB
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function testComputeValuesOnUpdate()
	{
		$oPerson = $this->CreatePerson(1);
		$this->assertIsObject($oPerson);

		$oEventReceiver = new CRUDEventReceiver();
		// Set the person's first name during Compute Values
		$oEventReceiver->AddCallback(EVENT_DB_COMPUTE_VALUES, Person::class, 'SetPersonFirstName');
		$oEventReceiver->RegisterCRUDListeners(EVENT_DB_COMPUTE_VALUES);

		$oPerson->Set('function', 'MyFunction_'.rand());
		$oPerson->DBUpdate();

		$this->assertEquals(1, self::$aEventCalls[EVENT_DB_COMPUTE_VALUES]);
		$this->assertEquals(1, self::$iEventCalls);

		// Read the object explicitly from the DB to check that the first name has been set
		$oSet = new DBObjectSet(DBSearch::FromOQL('SELECT Person WHERE id=:id'), [], ['id' => $oPerson->GetKey()]);
		$oPersonResult = $oSet->Fetch();
		$this->assertTrue(utils::StartsWith($oPersonResult->Get('first_name'), 'CRUD'));
	}

	/**
	 * Check that a CoreException is sent when modifying an object during EVENT_DB_CHECK_TO_WRITE
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function testCheckToWriteProtectedOnInsert()
	{
		$oEventReceiver = new CRUDEventReceiver();
		// Modify the person's function
		$oEventReceiver->AddCallback(EVENT_DB_CHECK_TO_WRITE, Person::class, 'SetPersonFunction');
		$oEventReceiver->RegisterCRUDListeners(EVENT_DB_CHECK_TO_WRITE);

		$this->expectException(CoreException::class);
		$this->CreatePerson(1);
	}

	/**
	 * Check that a CoreException is sent when modifying an object during EVENT_DB_CHECK_TO_WRITE
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function testCheckToWriteProtectedOnUpdate()
	{
		$oPerson = $this->CreatePerson(1);
		$this->assertIsObject($oPerson);

		// Modify the person's function
		$oEventReceiver = new CRUDEventReceiver();
		$oEventReceiver->AddCallback(EVENT_DB_CHECK_TO_WRITE, Person::class, 'SetPersonFunction');
		$oEventReceiver->RegisterCRUDListeners(EVENT_DB_CHECK_TO_WRITE);

		$oPerson->Set('function', 'test');

		$this->expectException(CoreException::class);
		$oPerson->DBUpdate();
	}

	/**
	 * Modify one object during EVENT_DB_CREATE_DONE
	 * Check that all the events are sent (CREATE + UPDATE)
	 * Check that the modification is saved in DB
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function testModificationsDuringCreateDone()
	{
		$oEventReceiver = new CRUDEventReceiver();
		// Set the person's first name during Compute Values
		$oEventReceiver->AddCallback(EVENT_DB_CREATE_DONE, Person::class, 'SetPersonFirstName');
		$oEventReceiver->RegisterCRUDListeners();

		$oPerson = $this->CreatePerson(1);
		$this->assertIsObject($oPerson);

		$this->assertEquals(2, self::$aEventCalls[EVENT_DB_COMPUTE_VALUES]);
		$this->assertEquals(2, self::$aEventCalls[EVENT_DB_CHECK_TO_WRITE]);
		$this->assertEquals(1, self::$aEventCalls[EVENT_DB_CREATE_DONE]);
		$this->assertEquals(1, self::$aEventCalls[EVENT_DB_UPDATE_DONE]);
		$this->assertEquals(6, self::$iEventCalls);

		// Read the object explicitly from the DB to check that the first name has been set
		$oSet = new DBObjectSet(DBSearch::FromOQL('SELECT Person WHERE id=:id'), [], ['id' => $oPerson->GetKey()]);
		$oPersonResult = $oSet->Fetch();
		$this->assertTrue(utils::StartsWith($oPersonResult->Get('first_name'), 'CRUD'));
	}

	/**
	 * Modify one object during EVENT_DB_UPDATE_DONE
	 * Check that all the events are sent (UPDATE + UPDATE again)
	 * Check that the modification is saved in DB
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function testModificationsDuringUpdateDone()
	{
		$oPerson = $this->CreatePerson(1);
		$this->assertIsObject($oPerson);

		$oEventReceiver = new CRUDEventReceiver();
		// Set the person's first name during Compute Values
		$oEventReceiver->AddCallback(EVENT_DB_UPDATE_DONE, Person::class, 'SetPersonFirstName');
		$oEventReceiver->RegisterCRUDListeners();

		$oPerson->Set('function', 'test'.rand());
		$oPerson->DBUpdate();

		$this->assertEquals(2, self::$aEventCalls[EVENT_DB_COMPUTE_VALUES]);
		$this->assertEquals(2, self::$aEventCalls[EVENT_DB_CHECK_TO_WRITE]);
		$this->assertEquals(2, self::$aEventCalls[EVENT_DB_UPDATE_DONE]);
		$this->assertEquals(6, self::$iEventCalls);

		// Read the object explicitly from the DB to check that the first name has been set
		$oSet = new DBObjectSet(DBSearch::FromOQL('SELECT Person WHERE id=:id'), [], ['id' => $oPerson->GetKey()]);
		$oPersonResult = $oSet->Fetch();
		$this->assertTrue(utils::StartsWith($oPersonResult->Get('first_name'), 'CRUD'));
	}

	/**
	 * Modify one object during EVENT_DB_UPDATE_DONE
	 * Check that the CRUD is protected against infinite loops (when modifying an object in its EVENT_DB_UPDATE_DONE)
	 *
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function testInfiniteUpdateDoneLoop()
	{
		$this->markTestSkipped('TEST Skipped: Protection not working.');

		$oPerson = $this->CreatePerson(1);
		$this->assertIsObject($oPerson);

		$oEventReceiver = new CRUDEventReceiver();
		// Set the person's first name during Compute Values
		$oEventReceiver->AddCallback(EVENT_DB_UPDATE_DONE, Person::class, 'SetPersonFirstName', 100);
		$oEventReceiver->RegisterCRUDListeners(EVENT_DB_UPDATE_DONE);

		$oPerson->Set('function', 'test'.rand());
		$oPerson->DBUpdate();

		$this->assertLessThan(100, self::$iEventCalls);
	}

	/**
	 * Check that events are sent for links on insert (team of 3 persons)
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function testDBInsertTeam()
	{
		// Prepare the link set
		$sLinkedClass = lnkPersonToTeam::class;
		$aLinkedObjectsArray = [];
		$oSet = DBObjectSet::FromArray($sLinkedClass, $aLinkedObjectsArray);
		$oLinkSet = new ormLinkSet(Team::class, 'persons_list', $oSet);

		// Create the 3 persons
		for ($i = 0; $i < 3; $i++) {
			$oPerson = $this->CreatePerson($i);
			$this->assertIsObject($oPerson);
			// Add the person to the link
			$oLink = MetaModel::NewObject(lnkPersonToTeam::class, ['person_id' => $oPerson->GetKey()]);
			$oLinkSet->AddItem($oLink);
		}

		$this->debug("\n-------------> Test Starts HERE\n");

		$oEventReceiver = new CRUDEventReceiver();
		$oEventReceiver->RegisterCRUDListeners();

		$oTeam = MetaModel::NewObject(Team::class, ['name' => 'TestTeam1', 'persons_list' => $oLinkSet, 'org_id' => $this->getTestOrgId()]);
		$oTeam->DBInsert();
		$this->assertIsObject($oTeam);

		// 1 insert for Team, 3 insert for lnkPersonToTeam
		$this->assertEquals(4, self::$aEventCalls[EVENT_DB_COMPUTE_VALUES]);
		$this->assertEquals(4, self::$aEventCalls[EVENT_DB_CHECK_TO_WRITE]);
		$this->assertEquals(4, self::$aEventCalls[EVENT_DB_CREATE_DONE]);
		$this->assertEquals(12, self::$iEventCalls);
	}

	/**
	 * The test creates a team containing one Person.
	 * During the insert of the lnkPersonToTeam a modification is done on the link,
	 * check that all the events are sent,
	 * check that the link is saved correctly.
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function testDBInsertTeamWithModificationsDuringInsert()
	{
		// Create the person
		$oPerson = $this->CreatePerson(1);
		$this->assertIsObject($oPerson);

		// Prepare the link for the insertion with the team
		$sLinkedClass = lnkPersonToTeam::class;
		$aLinkedObjectsArray = [];
		$oSet = DBObjectSet::FromArray($sLinkedClass, $aLinkedObjectsArray);
		$oLinkSet = new ormLinkSet(Team::class, 'persons_list', $oSet);
		$oLink = MetaModel::NewObject(lnkPersonToTeam::class, ['person_id' => $oPerson->GetKey()]);
		$oLinkSet->AddItem($oLink);

		$this->debug("\n-------------> Test Starts HERE\n");
		$oEventReceiver = new CRUDEventReceiver();
		// Create a new role and add it to the newly created lnkPersonToTeam
		$oEventReceiver->AddCallback(EVENT_DB_CREATE_DONE, lnkPersonToTeam::class, 'AddRoleToLink');
		$oEventReceiver->RegisterCRUDListeners();

		// Create the team
		$oTeam = MetaModel::NewObject(Team::class, ['name' => 'TestTeam1', 'persons_list' => $oLinkSet, 'org_id' => $this->getTestOrgId()]);
		$oTeam->DBInsert();
		$this->assertIsObject($oTeam);

		// 1 for Team, 1 for lnkPersonToTeam, 1 for ContactType and 1 for the update of lnkPersonToTeam
		$this->assertEquals(4, self::$aEventCalls[EVENT_DB_COMPUTE_VALUES]);
		$this->assertEquals(4, self::$aEventCalls[EVENT_DB_CHECK_TO_WRITE]);
		$this->assertEquals(3, self::$aEventCalls[EVENT_DB_CREATE_DONE]);
		$this->assertEquals(1, self::$aEventCalls[EVENT_DB_UPDATE_DONE]);
		$this->assertEquals(12, self::$iEventCalls);

		// Read the object explicitly from the DB to check that the role has been set
		$oSet = new DBObjectSet(DBSearch::FromOQL('SELECT Team WHERE id=:id'), [], ['id' => $oTeam->GetKey()]);
		$oTeamResult = $oSet->Fetch();
		$oLinkSet = $oTeamResult->Get('persons_list');
		$oLinkSet->rewind();
		$oLink = $oLinkSet->current();
		// Check that role has been set
		$this->assertNotEquals(0, $oLink->Get('role_id'));
	}

	/**
	 * Check that updates during EVENT_DB_CREATE_DONE are postponed to the end of all events and only one update is done
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function testPostponedUpdates()
	{
		$oEventReceiver = new CRUDEventReceiver();
		// Set the person's function after the creation
		$oEventReceiver->AddCallback(EVENT_DB_CREATE_DONE, Person::class, 'SetPersonFunction');
		$oEventReceiver->RegisterCRUDListeners(EVENT_DB_CREATE_DONE);

		// Intentionally register twice so 2 modifications will be done
		$oEventReceiver = new CRUDEventReceiver();
		$oEventReceiver->AddCallback(EVENT_DB_CREATE_DONE, Person::class, 'SetPersonFirstName');
		$oEventReceiver->RegisterCRUDListeners(EVENT_DB_CREATE_DONE);
		// Used to count the updates
		$oEventReceiver->RegisterCRUDListeners(EVENT_DB_UPDATE_DONE);

		self::$iEventCalls = 0;
		$oPerson = $this->CreatePerson(1);
		$this->assertIsObject($oPerson);
		// 2 for insert => 2 modifications generate ONE update
		// 1 for update (if 2 updates were done 2 events were counted)
		$this->assertEquals(2, self::$aEventCalls[EVENT_DB_CREATE_DONE]);
		$this->assertEquals(1, self::$aEventCalls[EVENT_DB_UPDATE_DONE]);
		$this->assertEquals(3, self::$iEventCalls);
	}


	public static function DebugStatic($sMsg)
	{
		if (static::$DEBUG_UNIT_TEST) {
			if (is_string($sMsg)) {
				echo "$sMsg\n";
			} else {
				print_r($sMsg);
			}
		}
	}
}


class ClassesWithDebug
{
	/**
	 * static version of the debug to be accessible from other objects
	 *
	 * @param $sMsg
	 */
	public static function DebugStatic($sMsg)
	{
		CRUDEventTest::DebugStatic($sMsg);
	}

	/**
	 * @param $sMsg
	 */
	public function Debug($sMsg)
	{
		CRUDEventTest::DebugStatic($sMsg);
	}
}

/**
 * Count events received
 * And allow callbacks on events
 */
class CRUDEventReceiver extends ClassesWithDebug
{
	private $aCallbacks = [];

	public function AddCallback(string $sEvent, string $sClass, string $sFct, int $iCount = 1): void
	{
		$this->aCallbacks[$sEvent][$sClass] = [
			'callback' => [$this, $sFct],
			'count' => $iCount,
		];
	}

	// Event callbacks
	public function OnEvent(EventData $oData)
	{
		$sEvent = $oData->GetEvent();
		$oObject = $oData->Get('object');
		$sClass = get_class($oObject);
		$iKey = $oObject->GetKey();
		$this->Debug(__METHOD__.": received event '$sEvent' for $sClass::$iKey");
		CRUDEventTest::IncrementCallCount($sEvent);

		if (isset($this->aCallbacks[$sEvent][$sClass])) {
			$aCallBack = $this->aCallbacks[$sEvent][$sClass];
			if ($aCallBack['count'] > 0) {
				$this->aCallbacks[$sEvent][$sClass]['count']--;
				call_user_func($this->aCallbacks[$sEvent][$sClass]['callback'], $oObject);
			}
		}
	}

	public function RegisterCRUDListeners(string $sEvent = null)
	{
		$this->Debug('Registering Test event listeners');
		if (is_null($sEvent)) {
			EventService::RegisterListener(EVENT_DB_COMPUTE_VALUES, [$this, 'OnEvent']);
			EventService::RegisterListener(EVENT_DB_CHECK_TO_WRITE, [$this, 'OnEvent']);
			EventService::RegisterListener(EVENT_DB_CHECK_TO_DELETE, [$this, 'OnEvent']);
			EventService::RegisterListener(EVENT_DB_CREATE_DONE, [$this, 'OnEvent']);
			EventService::RegisterListener(EVENT_DB_UPDATE_DONE, [$this, 'OnEvent']);
			EventService::RegisterListener(EVENT_DB_DELETE_DONE, [$this, 'OnEvent']);

			return;
		}
		EventService::RegisterListener($sEvent, [$this, 'OnEvent']);
	}

	/**
	 * @param $oObject
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	private function AddRoleToLink($oObject): void
	{
		$this->Debug(__METHOD__);
		$oContactType = MetaModel::NewObject(ContactType::class, ['name' => 'test_'.$oObject->GetKey()]);
		$oContactType->DBInsert();
		$oObject->Set('role_id', $oContactType->GetKey());
	}

	private function SetPersonFunction($oObject): void
	{
		$this->Debug(__METHOD__);
		$oObject->Set('function', 'CRUD_function_'.rand());
	}

	private function SetPersonFirstName($oObject): void
	{
		$this->Debug(__METHOD__);
		$oObject->Set('first_name', 'CRUD_first_name_'.rand());
	}

}
