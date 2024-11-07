<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core\CRUD;

use Combodo\iTop\Service\Events\EventData;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use ContactType;
use CoreException;
use DBObject;
use DBObjectSet;
use IssueLog;
use lnkFunctionalCIToTicket;
use lnkPersonToTeam;
use MetaModel;
use ormLinkSet;
use Person;
use Server;
use Team;
use UserRequest;
use utils;
use const EVENT_DB_ABOUT_TO_DELETE;
use const EVENT_DB_AFTER_DELETE;
use const EVENT_DB_AFTER_WRITE;
use const EVENT_DB_BEFORE_WRITE;
use const EVENT_DB_CHECK_TO_DELETE;
use const EVENT_DB_CHECK_TO_WRITE;
use const EVENT_DB_COMPUTE_VALUES;
use const EVENT_DB_LINKS_CHANGED;
use const EVENT_ENUM_TRANSITIONS;

class CRUDEventTest extends ItopDataTestCase
{
	const USE_TRANSACTION = true;
	const CREATE_TEST_ORG = true;

	// Count the events by name
	private static array $aEventCallsCount = [];
	private static int $iEventCallsTotalCount = 0;
	private static string $sLogFile = 'log/test_error_CRUDEventTest.log';

	protected function setUp(): void
	{
		static::CleanCallCount();
		parent::setUp();
		static::$DEBUG_UNIT_TEST = false;

		if (static::$DEBUG_UNIT_TEST) {
			echo "--- logging in ".APPROOT.static::$sLogFile."\n\n";
			@unlink(APPROOT.static::$sLogFile);
			IssueLog::Enable(APPROOT.static::$sLogFile);
			$oConfig = utils::GetConfig();
			$oConfig->Set('log_level_min', ['DMCRUD' => 'Trace', 'EventService' => 'Trace']);
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

	public static function IncrementCallCount(string $sEvent)
	{
		self::$aEventCallsCount[$sEvent] = (self::$aEventCallsCount[$sEvent] ?? 0) + 1;
		self::$iEventCallsTotalCount++;
	}

	public static function CleanCallCount()
	{
		self::$aEventCallsCount = [];
		self::$iEventCallsTotalCount = 0;
	}

	/**
	 * Check that the events are called on insert
	 */
	public function testDBInsertEvents()
	{
		$oEventReceiver = new CRUDEventReceiver($this);
		$oEventReceiver->RegisterCRUDEventListeners();

		$oPerson = MetaModel::NewObject(Person::class, [
			'name' => 'Person_1',
			'first_name' => 'Test',
			'org_id' => $this->getTestOrgId(),
		]);
		$oPerson->DBInsert();

		$this->assertEquals(
			[EVENT_DB_COMPUTE_VALUES, EVENT_DB_BEFORE_WRITE, EVENT_DB_CHECK_TO_WRITE, EVENT_DB_AFTER_WRITE],
			array_keys(self::$aEventCallsCount),
			'CRUD events must be fired in the following order: EVENT_DB_COMPUTE_VALUES, EVENT_DB_BEFORE_WRITE, EVENT_DB_CHECK_TO_WRITE, EVENT_DB_AFTER_WRITE'
		);

		$this->assertEquals(1, self::$aEventCallsCount[EVENT_DB_COMPUTE_VALUES]);
		$this->assertEquals(1, self::$aEventCallsCount[EVENT_DB_BEFORE_WRITE]);
		$this->assertEquals(1, self::$aEventCallsCount[EVENT_DB_CHECK_TO_WRITE]);
		$this->assertEquals(1, self::$aEventCallsCount[EVENT_DB_AFTER_WRITE]);
		$this->assertEquals(4, self::$iEventCallsTotalCount);
	}

	/**
	 * Check that the 3 events EVENT_DB_COMPUTE_VALUES, EVENT_DB_CHECK_TO_WRITE and EVENT_DB_AFTER_WRITE are called on update
	 */
	public function testDBUpdateEvents()
	{
		$oPerson = 	$this->createObject('Person', [
			'name' => 'Person_1',
			'first_name' => 'Test',
			'org_id' => $this->getTestOrgId(),
		]);

		// ----- Test Starts Here
		$oEventReceiver = new CRUDEventReceiver($this);
		$oEventReceiver->RegisterCRUDEventListeners();

		$oPerson->Set('first_name', 'TestToTouch');
		$oPerson->DBUpdate();

		$this->assertEquals(
			[EVENT_DB_COMPUTE_VALUES, EVENT_DB_BEFORE_WRITE, EVENT_DB_CHECK_TO_WRITE, EVENT_DB_AFTER_WRITE],
			array_keys(self::$aEventCallsCount),
			'CRUD events must be fired in the following order: EVENT_DB_COMPUTE_VALUES, EVENT_DB_BEFORE_WRITE, EVENT_DB_CHECK_TO_WRITE, EVENT_DB_AFTER_WRITE'
		);

		$this->assertEquals(1, self::$aEventCallsCount[EVENT_DB_COMPUTE_VALUES]);
		$this->assertEquals(1, self::$aEventCallsCount[EVENT_DB_CHECK_TO_WRITE]);
		$this->assertEquals(1, self::$aEventCallsCount[EVENT_DB_BEFORE_WRITE]);
		$this->assertEquals(1, self::$aEventCallsCount[EVENT_DB_AFTER_WRITE]);
		$this->assertEquals(4, self::$iEventCallsTotalCount);
	}

	/**
	 * Check that only 1 event EVENT_DB_COMPUTE_VALUES is called on update when nothing is modified
	 */
	public function testDBUpdateNothingNoEvent()
	{
		$oPerson = 	$this->createObject('Person', [
			'name' => 'Person_1',
			'first_name' => 'Test',
			'org_id' => $this->getTestOrgId(),
		]);

		$oEventReceiver = new CRUDEventReceiver($this);
		$oEventReceiver->RegisterCRUDEventListeners();

		$oPerson->DBUpdate();

		$this->assertEquals(0, self::$iEventCallsTotalCount);
	}

	/**
	 * Check that an object can be modified during EVENT_DB_COMPUTE_VALUES
	 * and the modifications are saved to the DB
	 */
	public function testComputeValuesOnInsert()
	{
		$oEventReceiver = new CRUDEventReceiver($this);
		// Set the person's first name during Compute Values
		$oEventReceiver->AddCallback(EVENT_DB_COMPUTE_VALUES, Person::class, 'SetRandomPersonFirstNameStartingWithCRUD');
		$oEventReceiver->RegisterCRUDEventListeners(EVENT_DB_COMPUTE_VALUES);

		$oPerson = MetaModel::NewObject(Person::class, [
			'name' => 'Person_1',
			'first_name' => 'Test',
			'org_id' => $this->getTestOrgId(),
		]);
		$oPerson->DBInsert();

		$this->assertEquals(1, self::$aEventCallsCount[EVENT_DB_COMPUTE_VALUES]);

		$oPerson = MetaModel::GetObject(\Person::class, $oPerson->GetKey());
		$this->assertStringStartsWith('CRUD', $oPerson->Get('first_name'), 'The object should have been modified and recorded in DB by EVENT_DB_COMPUTE_VALUES handler');
	}

	/**
	 * Check that an object can be modified during EVENT_DB_COMPUTE_VALUES
	 * and the modifications are saved to the DB
	 */
	public function testComputeValuesOnUpdate()
	{
		$oPerson = 	$this->createObject('Person', [
			'name' => 'Person_1',
			'first_name' => 'Test',
			'org_id' => $this->getTestOrgId(),
		]);

		$oEventReceiver = new CRUDEventReceiver($this);
		// Set the person's first name during Compute Values
		$oEventReceiver->AddCallback(EVENT_DB_COMPUTE_VALUES, Person::class, 'SetRandomPersonFirstNameStartingWithCRUD');
		$oEventReceiver->RegisterCRUDEventListeners(EVENT_DB_COMPUTE_VALUES);

		$oPerson->Set('first_name', 'TestToTouch');
		$oPerson->DBUpdate();

		$this->assertEquals(1, self::$aEventCallsCount[EVENT_DB_COMPUTE_VALUES]);

		$oPerson = MetaModel::GetObject(\Person::class, $oPerson->GetKey());
		$this->assertStringStartsWith('CRUD', $oPerson->Get('first_name'), 'The object should have been modified and recorded in DB by EVENT_DB_COMPUTE_VALUES handler');
	}

	/**
	 * Check that an object can be modified during EVENT_DB_COMPUTE_VALUES
	 * and the modifications are saved to the DB
	 */
	public function testBeforeWriteOnInsert()
	{
		$oEventReceiver = new CRUDEventReceiver($this);
		// Set the person's first name during Compute Values
		$oEventReceiver->AddCallback(EVENT_DB_BEFORE_WRITE, Person::class, 'SetRandomPersonFirstNameStartingWithCRUD');
		$oEventReceiver->RegisterCRUDEventListeners(EVENT_DB_BEFORE_WRITE);

		$oPerson = MetaModel::NewObject(Person::class, [
			'name' => 'Person_1',
			'first_name' => 'Test',
			'org_id' => $this->getTestOrgId(),
		]);
		$oPerson->DBInsert();

		$this->assertEquals(1, self::$aEventCallsCount[EVENT_DB_BEFORE_WRITE]);

		$oPerson = MetaModel::GetObject(\Person::class, $oPerson->GetKey());
		$this->assertStringStartsWith('CRUD', $oPerson->Get('first_name'), 'The object should have been modified and recorded in DB by EVENT_DB_BEFORE_WRITE handler');
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
	public function testBeforeWriteOnUpdate()
	{
		$oPerson = 	$this->createObject('Person', [
			'name' => 'Person_1',
			'first_name' => 'Test',
			'org_id' => $this->getTestOrgId(),
		]);

		$oEventReceiver = new CRUDEventReceiver($this);
		// Set the person's first name during Compute Values
		$oEventReceiver->AddCallback(EVENT_DB_BEFORE_WRITE, Person::class, 'SetRandomPersonFirstNameStartingWithCRUD');
		$oEventReceiver->RegisterCRUDEventListeners(EVENT_DB_BEFORE_WRITE);

		$oPerson->Set('first_name', 'TestToTouch');
		$oPerson->DBUpdate();

		$this->assertEquals(1, self::$aEventCallsCount[EVENT_DB_BEFORE_WRITE]);

		$oPerson = MetaModel::GetObject(\Person::class, $oPerson->GetKey());
		$this->assertStringStartsWith('CRUD', $oPerson->Get('first_name'), 'The object should have been modified and recorded in DB by EVENT_DB_BEFORE_WRITE handler');
	}

	/**
	 * Check that a CoreException is sent when modifying an object during EVENT_DB_CHECK_TO_WRITE
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function testObjectModificationIsNotAllowedDuringCheckToWriteOnInsert()
	{
		$oEventReceiver = new CRUDEventReceiver($this);
		// Modify the person's function
		$oEventReceiver->AddCallback(EVENT_DB_CHECK_TO_WRITE, Person::class, 'SetRandomPersonFirstNameStartingWithCRUD');
		$oEventReceiver->RegisterCRUDEventListeners(EVENT_DB_CHECK_TO_WRITE);

		$this->expectException(CoreException::class);
		$oPerson = MetaModel::NewObject(Person::class, [
			'name' => 'Person_1',
			'first_name' => 'Test',
			'org_id' => $this->getTestOrgId(),
		]);
		$oPerson->DBInsert();
	}

	/**
	 * Check that a CoreException is sent when modifying an object during EVENT_DB_CHECK_TO_WRITE
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function testObjectModificationIsNotAllowedDuringCheckToWriteOnUpdate()
	{
		$oPerson = 	$this->createObject('Person', [
			'name' => 'Person_1',
			'first_name' => 'Test',
			'org_id' => $this->getTestOrgId(),
		]);

		// Modify the person's function
		$oEventReceiver = new CRUDEventReceiver($this);
		$oEventReceiver->AddCallback(EVENT_DB_CHECK_TO_WRITE, Person::class, 'SetRandomPersonFirstNameStartingWithCRUD');
		$oEventReceiver->RegisterCRUDEventListeners(EVENT_DB_CHECK_TO_WRITE);

		$oPerson->Set('first_name', 'TestToTouch');

		$this->expectException(CoreException::class);
		$oPerson->DBUpdate();
	}

	/**
	 * Modify one object during EVENT_DB_AFTER_WRITE
	 * Check that all the events are sent (CREATE + UPDATE)
	 * Check that the modification is saved in DB
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function testAfterWriteOnInsert()
	{
		$oEventReceiver = new CRUDEventReceiver($this);
		// Set the person's first name during Compute Values
		$oEventReceiver->AddCallback(EVENT_DB_AFTER_WRITE, Person::class, 'SetRandomPersonFirstNameStartingWithCRUD');
		$oEventReceiver->RegisterCRUDEventListeners();

		$oPerson = MetaModel::NewObject(Person::class, [
			'name' => 'Person_1',
			'first_name' => 'Test',
			'org_id' => $this->getTestOrgId(),
		]);
		$oPerson->DBInsert();

		// 1 for insert and 1 for update
		$this->assertEquals(2, self::$aEventCallsCount[EVENT_DB_AFTER_WRITE], 'EVENT_DB_AFTER_WRITE is called once on DBInsert and once to persist the modifications done by the event handler');
		$this->assertEquals(8, self::$iEventCallsTotalCount, 'Each events is called twice due to the modifications done by the EVENT_DB_AFTER_WRITE handler');

		$oPerson = MetaModel::GetObject(\Person::class, $oPerson->GetKey());
		$this->assertStringStartsWith('CRUD', $oPerson->Get('first_name'), 'The object should have been modified and recorded in DB by EVENT_DB_AFTER_WRITE handler');
	}

	/**
	 * Modify one object during EVENT_DB_AFTER_WRITE
	 * Check that all the events are sent (UPDATE + UPDATE again)
	 * Check that the modification is saved in DB
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function testAfterWriteOnUpdate()
	{
		$oPerson = 	$this->createObject('Person', [
			'name' => 'Person_1',
			'first_name' => 'Test',
			'org_id' => $this->getTestOrgId(),
		]);

		$oEventReceiver = new CRUDEventReceiver($this);
		// Set the person's first name during Compute Values
		$oEventReceiver->AddCallback(EVENT_DB_AFTER_WRITE, Person::class, 'SetRandomPersonFirstNameStartingWithCRUD');
		$oEventReceiver->RegisterCRUDEventListeners();

		$oPerson->Set('first_name', 'TestToTouch');
		$oPerson->DBUpdate();

		$this->assertEquals(2, self::$aEventCallsCount[EVENT_DB_AFTER_WRITE], 'EVENT_DB_AFTER_WRITE is called once on DBUpdate and once to persist the modifications done by the event handler');
		$this->assertEquals(8, self::$iEventCallsTotalCount, 'Each events is called twice due to the modifications done by the EVENT_DB_AFTER_WRITE handler');

		$oPerson = MetaModel::GetObject(\Person::class, $oPerson->GetKey());
		$this->assertStringStartsWith('CRUD', $oPerson->Get('first_name'), 'The object should have been modified and recorded in DB by EVENT_DB_AFTER_WRITE handler');
	}

	public function testAfterDeleteObjectAttributesExceptLinkedSetAreUsable()
	{
		$oPerson = 	$this->createObject('Person', [
			'name' => 'Person_1',
			'first_name' => 'Test',
			'org_id' => $this->getTestOrgId(),
		]);

		$oFetchPerson = MetaModel::GetObject('Person', $oPerson->GetKey());

		$oEventReceiver = new CRUDEventReceiver($this);
		// Set the person's first name during Compute Values
		$oEventReceiver->AddCallback(EVENT_DB_AFTER_DELETE, Person::class, 'GetObjectAttributesValues');
		$oEventReceiver->RegisterCRUDEventListeners(EVENT_DB_AFTER_DELETE);
		$oEventReceiver->RegisterCRUDEventListeners(EVENT_DB_OBJECT_RELOAD);

		$oFetchPerson->DBDelete();

		$this->assertEquals(1, self::$aEventCallsCount[EVENT_DB_AFTER_DELETE], 'EVENT_DB_AFTER_DELETE must be called when deleting an object and the object attributes must remain accessible');
	}

	/**
	 * Modify one object during EVENT_DB_AFTER_WRITE
	 * Check that the CRUD is protected against infinite loops (when modifying an object in its EVENT_DB_AFTER_WRITE)
	 *
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function testProtectionAgainstInfiniteAfterWriteModificationsLoop()
	{
		$oPerson = 	$this->createObject('Person', [
			'name' => 'Person_1',
			'first_name' => 'Test',
			'org_id' => $this->getTestOrgId(),
		]);

		$oEventReceiver = new CRUDEventReceiver($this);
		// Set the person's first name during Compute Values
		$oEventReceiver->AddCallback(EVENT_DB_AFTER_WRITE, Person::class, 'SetRandomPersonFirstNameStartingWithCRUD', 2 * DBObject::MAX_UPDATE_LOOP_COUNT);
		$oEventReceiver->RegisterCRUDEventListeners(EVENT_DB_AFTER_WRITE);

		$oPerson->Set('first_name', 'test'.rand());
		$oPerson->DBUpdate();

		$this->assertEquals(DBObject::MAX_UPDATE_LOOP_COUNT, self::$iEventCallsTotalCount);
	}

	public function testDBLinksChangedNotFiredOnDBUpdateWhenLinksAreModifiedAsLinkSetAttribute()
	{
		$oUserRequest = $this->CreateUserRequest(1);

		// Prepare the empty link set
		$oLinkSet = new ormLinkSet(UserRequest::class, 'functionalcis_list', DBObjectSet::FromScratch(lnkFunctionalCIToTicket::class));

		// Create the 3 servers
		for ($i = 0; $i < 3; $i++) {
			$oServer = $this->CreateServer($i);
			// Add the person to the link
			$oLink = MetaModel::NewObject(lnkFunctionalCIToTicket::class, ['functionalci_id' => $oServer->GetKey()]);
			$oLinkSet->AddItem($oLink);
		}

		$oEventReceiver = new CRUDEventReceiver($this);
		$oEventReceiver->RegisterCRUDEventListeners();

		$oUserRequest->Set('functionalcis_list', $oLinkSet);
		$oUserRequest->DBUpdate();

		$this->assertArrayNotHasKey(EVENT_DB_LINKS_CHANGED, self::$aEventCallsCount, 'Event EVENT_DB_LINKS_CHANGED must not be fired on host object update');
	}

	public function testAllEventsForDBInsertAndDBDeleteForObjectWithLinkSet()
	{
		// Prepare the empty link set
		$oLinkSet = new ormLinkSet(UserRequest::class, 'functionalcis_list', DBObjectSet::FromScratch(lnkFunctionalCIToTicket::class));

		// Create the 3 servers
		for ($i = 0; $i < 3; $i++) {
			$oServer = $this->CreateServer($i);
			// Add the person to the link
			$oLink = MetaModel::NewObject(lnkFunctionalCIToTicket::class, ['functionalci_id' => $oServer->GetKey()]);
			$oLinkSet->AddItem($oLink);
		}

		$this->debug("\n-------------> Insert Starts HERE\n");

		$oEventReceiver = new CRUDEventReceiver($this);
		$oEventReceiver->RegisterCRUDEventListeners();

		$oUserRequest = MetaModel::NewObject(UserRequest::class, array_merge($this->GetUserRequestParams(0), ['functionalcis_list' => $oLinkSet]));
		$oUserRequest->DBInsert();

		// 1 insert for UserRequest, 3 insert for lnkFunctionalCIToTicket
		$this->assertEquals(4, self::$aEventCallsCount[EVENT_DB_COMPUTE_VALUES]);
		$this->assertEquals(4, self::$aEventCallsCount[EVENT_DB_CHECK_TO_WRITE]);
		$this->assertEquals(4, self::$aEventCallsCount[EVENT_DB_BEFORE_WRITE]);
		$this->assertEquals(4, self::$aEventCallsCount[EVENT_DB_AFTER_WRITE]);
		$this->assertArrayNotHasKey(EVENT_DB_LINKS_CHANGED, self::$aEventCallsCount, 'Event must not be fired if host object is created with links');
		$this->assertEquals(16, self::$iEventCallsTotalCount);

		$this->debug("\n-------------> Delete Starts HERE\n");

		self::CleanCallCount();
		$oUserRequest->DBDelete();

		// 1 delete for UserRequest, 3 delete for lnkFunctionalCIToTicket
		$this->assertEquals(4, self::$aEventCallsCount[EVENT_DB_CHECK_TO_DELETE]);
		$this->assertEquals(4, self::$aEventCallsCount[EVENT_DB_ABOUT_TO_DELETE]);
		$this->assertEquals(4, self::$aEventCallsCount[EVENT_DB_AFTER_DELETE]);
		$this->assertArrayNotHasKey(EVENT_DB_LINKS_CHANGED, self::$aEventCallsCount, 'Event not to be sent on delete');
		$this->assertEquals(12, self::$iEventCallsTotalCount);

	}

	/**
	 * The test creates a team containing one Person.
	 * During the insert of the lnkPersonToTeam a modification is done on the link,
	 * check that all the events are sent,
	 * check that the link is saved correctly.
	 */
	public function testDBInsertTeamWithModificationsOnLinkDuringInsert()
	{
		// Create the person
		$oPerson = $this->CreatePerson(1);

		// Prepare the link for the insertion with the team
		$oLinkSet = new ormLinkSet(Team::class, 'persons_list', DBObjectSet::FromScratch(lnkPersonToTeam::class));
		$oLink = MetaModel::NewObject(lnkPersonToTeam::class, ['person_id' => $oPerson->GetKey()]);
		$oLinkSet->AddItem($oLink);

		$oEventReceiver = new CRUDEventReceiver($this);
		// Create a new role and add it to the newly created lnkPersonToTeam
		$oEventReceiver->AddCallback(EVENT_DB_AFTER_WRITE, lnkPersonToTeam::class, 'AddRoleToLink');
		$oEventReceiver->RegisterCRUDEventListeners();

		// Create the team
		$oTeam = MetaModel::NewObject(Team::class, ['name' => 'TestTeam1', 'persons_list' => $oLinkSet, 'org_id' => $this->getTestOrgId()]);
		$oTeam->DBInsert();

		// 1 for Team, 1 for lnkPersonToTeam, 1 for ContactType and 1 for the update of lnkPersonToTeam
		$this->assertEquals(4, self::$aEventCallsCount[EVENT_DB_COMPUTE_VALUES]);
		$this->assertEquals(4, self::$aEventCallsCount[EVENT_DB_CHECK_TO_WRITE]);
		$this->assertEquals(4, self::$aEventCallsCount[EVENT_DB_BEFORE_WRITE]);
		$this->assertEquals(4, self::$aEventCallsCount[EVENT_DB_AFTER_WRITE]);
		$this->assertEquals(16, self::$iEventCallsTotalCount);

		// Read the object explicitly from the DB to check that the role has been set
		$oTeam = MetaModel::GetObject(Team::class, $oTeam->GetKey());
		$oLinkSet = $oTeam->Get('persons_list');
		$oLinkSet->rewind();
		$oLink = $oLinkSet->current();
		// Check that role has been set
		$this->assertNotEquals(0, $oLink->Get('role_id'));
	}

	/**
	 * Check that DBUpdates() during all the events are ignored
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function testReentrancyProtectionOnInsert()
	{
		$oEventReceiver = new CRUDEventReceiver($this);
		$oEventReceiver->RegisterCRUDEventListeners();

		// Set the person's function
		$oEventReceiver->AddCallback(EVENT_DB_COMPUTE_VALUES, Person::class, 'SetRandomPersonFunctionAndVerifyThatUpdateIsIgnored');
		$oEventReceiver->AddCallback(EVENT_DB_BEFORE_WRITE, Person::class, 'SetRandomPersonFunctionAndVerifyThatUpdateIsIgnored');
		$oEventReceiver->AddCallback(EVENT_DB_AFTER_WRITE, Person::class, 'SetRandomPersonFunctionAndVerifyThatUpdateIsIgnored');

		$oPerson = MetaModel::NewObject(Person::class, [
			'name' => 'Person_1',
			'first_name' => 'Test',
			'function' => 'Test',
			'org_id' => $this->getTestOrgId(),
		]);
		$oPerson->DBInsert();

		$this->assertEquals(false, $oEventReceiver->bDBUpdateCalledSuccessfullyDuringEvent, 'DBUpdate must not be performed during the events (reentrancy protection)');
	}

	/**
	 * Check that DBUpdates() during all the events are ignored
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function testReentrancyProtectionOnUpdates()
	{
		$oPerson = 	$this->createObject('Person', [
			'name' => 'Person_1',
			'first_name' => 'Test',
			'function' => 'Test',
			'org_id' => $this->getTestOrgId(),
		]);

		$oEventReceiver = new CRUDEventReceiver($this);
		$oEventReceiver->RegisterCRUDEventListeners();

		// Set the person's function
		$oEventReceiver->AddCallback(EVENT_DB_COMPUTE_VALUES, Person::class, 'SetRandomPersonFunctionAndVerifyThatUpdateIsIgnored');
		$oEventReceiver->AddCallback(EVENT_DB_BEFORE_WRITE, Person::class, 'SetRandomPersonFunctionAndVerifyThatUpdateIsIgnored');
		$oEventReceiver->AddCallback(EVENT_DB_AFTER_WRITE, Person::class, 'SetRandomPersonFunctionAndVerifyThatUpdateIsIgnored');

		$oPerson->Set('function', 'TestToTouch');
		$oPerson->DBUpdate();

		$this->assertEquals(false, $oEventReceiver->bDBUpdateCalledSuccessfullyDuringEvent, 'DBUpdate must not be performed during the events (reentrancy protection)');
	}

	/**
	 * Check that updates during EVENT_DB_AFTER_WRITE are postponed to the end of all events and only one update is done
	 */
	public function testGroupUpdatesWhenMultipleModificationsAreDoneAfterWriteOnInsert()
	{
		$oEventReceiver1 = new CRUDEventReceiver($this);
		// Set the person's function after the creation
		$oEventReceiver1->AddCallback(EVENT_DB_AFTER_WRITE, Person::class, 'SetRandomPersonFunction');
		$oEventReceiver1->RegisterCRUDEventListeners(EVENT_DB_AFTER_WRITE);

		// Intentionally register twice so 2 modifications will be done
		$oEventReceiver2 = new CRUDEventReceiver($this);
		$oEventReceiver2->AddCallback(EVENT_DB_AFTER_WRITE, Person::class, 'SetRandomPersonFirstNameStartingWithCRUD');
		$oEventReceiver2->RegisterCRUDEventListeners(EVENT_DB_AFTER_WRITE);

		$oPerson = MetaModel::NewObject(Person::class, [
			'name' => 'Person_1',
			'first_name' => 'Test',
			'function' => 'Test',
			'org_id' => $this->getTestOrgId(),
		]);
		$oPerson->DBInsert();

		// 2 for insert => 2 modifications generate ONE update
		// 2 for update (if 2 updates were done then 4 events would have been counted)
		$this->assertEquals(4, self::$aEventCallsCount[EVENT_DB_AFTER_WRITE], 'DBUpdate must be postponed to the end of all EVENT_DB_AFTER_WRITE calls');
		$this->assertEquals(4, self::$iEventCallsTotalCount, 'Updates must be postponed to the end of all EVENT_DB_AFTER_WRITE events');
	}

	/**
	 * Check that updates during EVENT_DB_AFTER_WRITE are postponed to the end of all events and only one update is done
	 */
	public function testGroupUpdatesWhenMultipleModificationsAreDoneAfterWriteOnUpdate()
	{
		$oPerson = 	$this->createObject('Person', [
			'name' => 'Person_1',
			'first_name' => 'Test',
			'function' => 'Test',
			'org_id' => $this->getTestOrgId(),
		]);

		$oEventReceiver1 = new CRUDEventReceiver($this);
		// Set the person's function after the creation
		$oEventReceiver1->AddCallback(EVENT_DB_AFTER_WRITE, Person::class, 'SetRandomPersonFunction');
		$oEventReceiver1->RegisterCRUDEventListeners(EVENT_DB_AFTER_WRITE);

		// Intentionally register twice so 2 modifications will be done
		$oEventReceiver2 = new CRUDEventReceiver($this);
		$oEventReceiver2->AddCallback(EVENT_DB_AFTER_WRITE, Person::class, 'SetRandomPersonFunction');
		$oEventReceiver2->RegisterCRUDEventListeners(EVENT_DB_AFTER_WRITE);

		$oPerson->Set('function', 'TestToTouch');
		$oPerson->DBUpdate();

		// Each DBUpdate fires 2 times the EVENT_DB_AFTER_WRITE
		// Each callback modifies the object but only one DBUpdate is called again, firing again 2 times the EVENT_DB_AFTER_WRITE
		$this->assertEquals(4, self::$aEventCallsCount[EVENT_DB_AFTER_WRITE], 'Updates must be postponed to the end of all events');
		$this->assertEquals(4, self::$iEventCallsTotalCount, 'Updates must be postponed to the end of all events');
	}

	public function testDBLinksChangedNotFiredWhenLinksAreManipulatedOutsideAnObjectWithoutFlag()
	{
		// Create a Person
		$oPerson = $this->CreatePerson(1);

		// Create a Team
		$oTeam = MetaModel::NewObject(Team::class, ['name' => 'TestTeamWithLinkToAPerson', 'org_id' => $this->getTestOrgId()]);
		$oTeam->DBInsert();

		// Start receiving events
		$oEventReceiver = new CRUDEventReceiver($this);
		$oEventReceiver->RegisterCRUDEventListeners();

		// Create a link between Person and Team
		$oLnk = MetaModel::NewObject(lnkPersonToTeam::class, ['person_id' => $oPerson->GetKey(), 'team_id' => $oTeam->GetKey()]);
		$oLnk->DBInsert();

		$this->assertArrayNotHasKey(EVENT_DB_LINKS_CHANGED, self::$aEventCallsCount, 'LinkSet without with_php_computation attribute should not receive EVENT_DB_LINKS_CHANGED');

		// Modify link
		$oContactType = MetaModel::NewObject(ContactType::class, ['name' => 'test_'.$oLnk->GetKey()]);
		$oContactType->DBInsert();
		$oLnk->Set('role_id', $oContactType->GetKey());
		$oLnk->DBUpdate();

		$this->assertArrayNotHasKey(EVENT_DB_LINKS_CHANGED, self::$aEventCallsCount, 'LinkSet without with_php_computation attribute should not receive EVENT_DB_LINKS_CHANGED');

		// Delete link
		$oLnk->DBDelete();

		$this->assertArrayNotHasKey(EVENT_DB_LINKS_CHANGED, self::$aEventCallsCount, 'LinkSet without with_php_computation attribute should not receive EVENT_DB_LINKS_CHANGED');
	}

	public function testDBLinksChangedFiredWhenLinksAreManipulatedOutsideAnObjectWithFlag()
	{
		$oUserRequest = $this->CreateUserRequest(1);

		$oEventReceiver = new CRUDEventReceiver($this);
		$oEventReceiver->RegisterCRUDEventListeners(null, \UserRequest::class);

		// Create the server and corresponding lnkFunctionalCIToTicket
		$oServer = $this->CreateServer(1);
		$oLink = MetaModel::NewObject(lnkFunctionalCIToTicket::class, ['functionalci_id' => $oServer->GetKey(), 'ticket_id' => $oUserRequest->GetKey()]);
		$oLink->DBInsert();

		// one link where added outside the object
		$this->assertEquals(1, self::$aEventCallsCount[EVENT_DB_LINKS_CHANGED], 'LinkSet with with_php_computation attribute should receive EVENT_DB_LINKS_CHANGED');
		$this->assertEquals(1, self::$iEventCallsTotalCount, 'Only EVENT_DB_LINKS_CHANGED event must be fired on host class during link modification');

		self::CleanCallCount();
		// Update the link with a new server
		$oServer2 = $this->CreateServer(2);
		$oLink->Set('functionalci_id', $oServer2->GetKey());
		$oLink->DBUpdate();

		// one link where modified outside the object
		$this->assertEquals(1, self::$aEventCallsCount[EVENT_DB_LINKS_CHANGED], 'LinkSet with with_php_computation attribute should receive EVENT_DB_LINKS_CHANGED');
		$this->assertEquals(1, self::$iEventCallsTotalCount, 'Only EVENT_DB_LINKS_CHANGED event must be fired on host class during link modification');

		self::CleanCallCount();
		// Delete link
		$oLink->DBDelete();

		// one link where deleted outside the object
		$this->assertEquals(1, self::$aEventCallsCount[EVENT_DB_LINKS_CHANGED], 'LinkSet with with_php_computation attribute should receive EVENT_DB_LINKS_CHANGED');
		$this->assertEquals(1, self::$iEventCallsTotalCount, 'Only EVENT_DB_LINKS_CHANGED event must be fired on host class during link modification');
	}

	public function testDenyTransitionsWithEventEnumTransitions()
	{
		$oEventReceiver = new CRUDEventReceiver($this);
		$oEventReceiver->RegisterCRUDEventListeners();

		// Object with no lifecycle
		/** @var DBObject $oPerson */
		$oPerson = $this->CreatePerson(1);
		$oEventReceiver->AddCallback(EVENT_ENUM_TRANSITIONS, Person::class, 'DenyAllTransitions');
		self::CleanCallCount();
		$oPerson->EnumTransitions();
		$this->assertEquals(0, self::$iEventCallsTotalCount, 'EVENT_ENUM_TRANSITIONS should not be fired for objects without lifecycle');

		// Object with lifecycle
		$oTicket = $this->CreateTicket(1);
		$aRefTransitions = array_keys($oTicket->EnumTransitions());
		$oEventReceiver->AddCallback(EVENT_ENUM_TRANSITIONS, UserRequest::class, 'DenyAllTransitions');
		self::CleanCallCount();
		$aTransitions = $oTicket->EnumTransitions();
		$this->assertEquals(1, self::$aEventCallsCount[EVENT_ENUM_TRANSITIONS], 'EVENT_ENUM_TRANSITIONS should be fired for objects with lifecycle');
		$this->assertEquals(1, self::$iEventCallsTotalCount, 'EVENT_ENUM_TRANSITIONS is the only event fired by DBObject::EnumTransitions()');
		$this->assertCount(0, $aTransitions, 'All transitions should have been denied');

		$oEventReceiver->AddCallback(EVENT_ENUM_TRANSITIONS, UserRequest::class, 'DenyAssignTransition');
		self::CleanCallCount();
		$aTransitions = $oTicket->EnumTransitions();
		$this->assertEquals(1, self::$aEventCallsCount[EVENT_ENUM_TRANSITIONS], 'EVENT_ENUM_TRANSITIONS should be fired for objects with lifecycle');
		$this->assertEquals(1, self::$iEventCallsTotalCount, 'EVENT_ENUM_TRANSITIONS is the only event fired by DBObject::EnumTransitions()');
		$this->assertArrayNotHasKey('ev_assign', $aTransitions, 'Assign transition should have been removed by EVENT_ENUM_TRANSITIONS handler');
		$this->assertEquals(1, count($aRefTransitions) - count($aTransitions), 'Only one transition should have been removed');
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

/**
 * Add debug feature to test support class
 */
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
 * Test support class used to count events received
 * And allow callbacks on events
 */
class CRUDEventReceiver extends ClassesWithDebug
{
	public bool $bDBUpdateCalledSuccessfullyDuringEvent = false;

	private $oTestCase;
	private $aCallbacks = [];

	public function __construct(ItopDataTestCase $oTestCase)
	{
		$this->oTestCase = $oTestCase;
	}

	//

	/**
	 * Add a specific callback for an event
	 *
	 * @param string $sEvent event name
	 * @param string $sClass event source class name
	 * @param string $sFct   function to call on CRUDEventReceiver object
	 * @param int $iCount    limit the number of calls to the callback
	 *
	 * @return void
	 */
	public function AddCallback(string $sEvent, string $sClass, string $sFct, int $iCount = 1): void
	{
		$this->aCallbacks[$sEvent][$sClass] = [
			'callback' => [$this, $sFct],
			'count' => $iCount,
		];
	}

	public function CleanCallbacks()
	{
		$this->aCallbacks = [];
		$this->bDBUpdateCalledSuccessfullyDuringEvent = false;
	}


	/**
	 * Event callbacks => this function counts the received events by event name and source class
	 * If AddCallback() method has been called a specific callback is called, else only the count is done
	 *
	 * @param \Combodo\iTop\Service\Events\EventData $oData
	 *
	 * @return void
	 */
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
				call_user_func($this->aCallbacks[$sEvent][$sClass]['callback'], $oData);
			}
		}
	}

	public function RegisterCRUDEventListeners(string $sEvent = null, $mEventSource = null)
	{
		$this->Debug('Registering Test event listeners');
		if (is_null($sEvent)) {
			$this->oTestCase->EventService_RegisterListener(EVENT_DB_COMPUTE_VALUES, [$this, 'OnEvent'], $mEventSource);
			$this->oTestCase->EventService_RegisterListener(EVENT_DB_CHECK_TO_WRITE, [$this, 'OnEvent'], $mEventSource);
			$this->oTestCase->EventService_RegisterListener(EVENT_DB_CHECK_TO_DELETE, [$this, 'OnEvent'], $mEventSource);
			$this->oTestCase->EventService_RegisterListener(EVENT_DB_BEFORE_WRITE, [$this, 'OnEvent'], $mEventSource);
			$this->oTestCase->EventService_RegisterListener(EVENT_DB_AFTER_WRITE, [$this, 'OnEvent'], $mEventSource);
			$this->oTestCase->EventService_RegisterListener(EVENT_DB_ABOUT_TO_DELETE, [$this, 'OnEvent'], $mEventSource);
			$this->oTestCase->EventService_RegisterListener(EVENT_DB_AFTER_DELETE, [$this, 'OnEvent'], $mEventSource);
			$this->oTestCase->EventService_RegisterListener(EVENT_DB_LINKS_CHANGED, [$this, 'OnEvent'], $mEventSource);
			$this->oTestCase->EventService_RegisterListener(EVENT_ENUM_TRANSITIONS, [$this, 'OnEvent'], $mEventSource);

			return;
		}
		$this->oTestCase->EventService_RegisterListener($sEvent, [$this, 'OnEvent'], $mEventSource);
	}

	/**
	 * @noinspection PhpUnusedPrivateMethodInspection Used as a callback
	 */
	private function AddRoleToLink(EventData $oData): void
	{
		$this->Debug(__METHOD__);
		$oObject = $oData->Get('object');
		$oContactType = MetaModel::NewObject(ContactType::class, ['name' => 'test_'.$oObject->GetKey()]);
		$oContactType->DBInsert();
		$oObject->Set('role_id', $oContactType->GetKey());
	}

	/**
	 * @noinspection PhpUnusedPrivateMethodInspection Used as a callback
	 */
	private function SetRandomPersonFunction(EventData $oData): void
	{
		$this->Debug(__METHOD__);
		$oObject = $oData->Get('object');
		$oObject->Set('function', 'CRUD_function_'.rand());
	}

	/**
	 * @noinspection PhpUnusedPrivateMethodInspection Used as a callback
	 */
	private function SetRandomPersonFirstNameStartingWithCRUD(EventData $oData): void
	{
		$this->Debug(__METHOD__);
		$oObject = $oData->Get('object');
		$oObject->Set('first_name', 'CRUD_first_name_'.rand());
	}

	/**
	 * @noinspection PhpUnusedPrivateMethodInspection Used as a callback
	 */
	private function GetObjectAttributesValues(EventData $oData): void
	{
		$this->Debug(__METHOD__);
		$oObject = $oData->Get('object');
		foreach (MetaModel::ListAttributeDefs(get_class($oObject)) as $sAttCode => $oAttDef) {
			if (!$oAttDef->IsLinkSet()) {
				$oObject->Get($sAttCode);
			}
		}
	}

	/**
	 * @noinspection PhpUnusedPrivateMethodInspection Used as a callback
	 */
	private function SetRandomPersonFunctionAndVerifyThatUpdateIsIgnored(EventData $oData): void
	{
		$this->Debug(__METHOD__);
		$oObject = $oData->Get('object');
		$oObject->Set('function', 'CRUD_function_'.rand());
		$oObject->DBUpdate(); // Should be ignored
		if (empty($oObject->ListChanges())) {
			$this->bDBUpdateCalledSuccessfullyDuringEvent = true;
		}
	}

	/**
	 * @noinspection PhpUnusedPrivateMethodInspection Used as a callback
	 */
	private function DenyAllTransitions(EventData $oData): void
	{
		$this->Debug(__METHOD__);
		/** @var \DBObject $oObject */
		$oObject = $oData->Get('object');
		$aAllowedStimuli = $oData->Get('allowed_stimuli');
		// Deny all transitions
		foreach ($aAllowedStimuli as $sStimulus) {
			$this->debug(" * Deny $sStimulus");
			$oObject->DenyTransition($sStimulus);
		}
	}

	/**
	 * @noinspection PhpUnusedPrivateMethodInspection Used as a callback
	 */
	private function DenyAssignTransition(EventData $oData): void
	{
		$this->Debug(__METHOD__);
		/** @var \DBObject $oObject */
		$oObject = $oData->Get('object');
		$oObject->DenyTransition('ev_assign');
	}
}
