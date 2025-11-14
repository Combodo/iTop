<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core\CRUD;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use ContactType;
use CoreException;
use DBObject;
use DBObject\Utils\CRUDEventReceiver;
use DBObjectSet;
use IssueLog;
use lnkFunctionalCIToTicket;
use lnkPersonToTeam;
use LogChannels;
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
	use DBObject\Utils\EventTest;
	use DBObject\Utils\ClassesWithDebug;
	public const USE_TRANSACTION = true;
	public const CREATE_TEST_ORG = true;

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
			$oConfig->Set('log_level_min', [LogChannels::DM_CRUD => 'Trace', LogChannels::EVENT_SERVICE => 'Trace']);
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

		$this->AssertReceivedEventsEquals([EVENT_DB_COMPUTE_VALUES, EVENT_DB_BEFORE_WRITE, EVENT_DB_CHECK_TO_WRITE, EVENT_DB_AFTER_WRITE], 'CRUD events must be fired in the following order: EVENT_DB_COMPUTE_VALUES, EVENT_DB_BEFORE_WRITE, EVENT_DB_CHECK_TO_WRITE, EVENT_DB_AFTER_WRITE');
		$this->AssertEventCountEquals(1, EVENT_DB_COMPUTE_VALUES);
		$this->AssertEventCountEquals(1, EVENT_DB_BEFORE_WRITE);
		$this->AssertEventCountEquals(1, EVENT_DB_CHECK_TO_WRITE);
		$this->AssertEventCountEquals(1, EVENT_DB_AFTER_WRITE);
		$this->AssertTotalEventCountEquals(4);
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

		$this->AssertReceivedEventsEquals([EVENT_DB_COMPUTE_VALUES, EVENT_DB_BEFORE_WRITE, EVENT_DB_CHECK_TO_WRITE, EVENT_DB_AFTER_WRITE], 'CRUD events must be fired in the following order: EVENT_DB_COMPUTE_VALUES, EVENT_DB_BEFORE_WRITE, EVENT_DB_CHECK_TO_WRITE, EVENT_DB_AFTER_WRITE');
		$this->AssertEventCountEquals(1, EVENT_DB_COMPUTE_VALUES);
		$this->AssertEventCountEquals(1, EVENT_DB_CHECK_TO_WRITE);
		$this->AssertEventCountEquals(1, EVENT_DB_BEFORE_WRITE);
		$this->AssertEventCountEquals(1, EVENT_DB_AFTER_WRITE);
		$this->AssertTotalEventCountEquals(4);
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

		$this->AssertTotalEventCountEquals(0);
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

		$this->AssertEventCountEquals(1, EVENT_DB_COMPUTE_VALUES);

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

		$this->AssertEventCountEquals(1, EVENT_DB_COMPUTE_VALUES);

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

		$this->AssertEventCountEquals(1, EVENT_DB_BEFORE_WRITE);

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

		$this->AssertEventCountEquals(1, EVENT_DB_BEFORE_WRITE);

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
		$this->AssertEventCountEquals(2, EVENT_DB_AFTER_WRITE, 'EVENT_DB_AFTER_WRITE is called once on DBInsert and once to persist the modifications done by the event handler');
		$this->AssertTotalEventCountEquals(8, 'Each events is called twice due to the modifications done by the EVENT_DB_AFTER_WRITE handler');

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

		$this->AssertEventCountEquals(2, EVENT_DB_AFTER_WRITE, 'EVENT_DB_AFTER_WRITE is called once on DBUpdate and once to persist the modifications done by the event handler');
		$this->AssertTotalEventCountEquals(8, 'Each events is called twice due to the modifications done by the EVENT_DB_AFTER_WRITE handler');

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

		$this->AssertEventCountEquals(1, EVENT_DB_AFTER_DELETE, 'EVENT_DB_AFTER_DELETE must be called when deleting an object and the object attributes must remain accessible');
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

		$this->AssertTotalEventCountEquals(DBObject::MAX_UPDATE_LOOP_COUNT);
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

		$this->AssertEventNotReceived(EVENT_DB_LINKS_CHANGED, 'Event EVENT_DB_LINKS_CHANGED must not be fired on host object update');
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
		$this->AssertEventCountEquals(4, EVENT_DB_COMPUTE_VALUES);
		$this->AssertEventCountEquals(4, EVENT_DB_CHECK_TO_WRITE);
		$this->AssertEventCountEquals(4, EVENT_DB_BEFORE_WRITE);
		$this->AssertEventCountEquals(4, EVENT_DB_AFTER_WRITE);
		$this->AssertEventNotReceived(EVENT_DB_LINKS_CHANGED, 'Event must not be fired if host object is created with links');
		$this->AssertTotalEventCountEquals(16);

		$this->debug("\n-------------> Delete Starts HERE\n");

		self::CleanCallCount();
		$oUserRequest->DBDelete();

		// 1 delete for UserRequest, 3 delete for lnkFunctionalCIToTicket
		$this->AssertEventCountEquals(4, EVENT_DB_CHECK_TO_DELETE);
		$this->AssertEventCountEquals(4, EVENT_DB_ABOUT_TO_DELETE);
		$this->AssertEventCountEquals(4, EVENT_DB_AFTER_DELETE);
		$this->AssertEventNotReceived(EVENT_DB_LINKS_CHANGED, 'Event not to be sent on delete');
		$this->AssertTotalEventCountEquals(12);

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
		$this->AssertEventCountEquals(4, EVENT_DB_COMPUTE_VALUES);
		$this->AssertEventCountEquals(4, EVENT_DB_CHECK_TO_WRITE);
		$this->AssertEventCountEquals(4, EVENT_DB_BEFORE_WRITE);
		$this->AssertEventCountEquals(4, EVENT_DB_AFTER_WRITE);
		$this->AssertTotalEventCountEquals(16);

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
		$this->AssertEventCountEquals(4, EVENT_DB_AFTER_WRITE, 'DBUpdate must be postponed to the end of all EVENT_DB_AFTER_WRITE calls');
		$this->AssertTotalEventCountEquals(4, 'Updates must be postponed to the end of all EVENT_DB_AFTER_WRITE events');
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
		$this->AssertEventCountEquals(4, EVENT_DB_AFTER_WRITE, 'Updates must be postponed to the end of all events');
		$this->AssertTotalEventCountEquals(4, 'Updates must be postponed to the end of all events');
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

		$this->AssertEventNotReceived(EVENT_DB_LINKS_CHANGED, 'LinkSet without with_php_computation attribute should not receive EVENT_DB_LINKS_CHANGED');

		// Modify link
		$oContactType = MetaModel::NewObject(ContactType::class, ['name' => 'test_'.$oLnk->GetKey()]);
		$oContactType->DBInsert();
		$oLnk->Set('role_id', $oContactType->GetKey());
		$oLnk->DBUpdate();

		$this->AssertEventNotReceived(EVENT_DB_LINKS_CHANGED, 'LinkSet without with_php_computation attribute should not receive EVENT_DB_LINKS_CHANGED');

		// Delete link
		$oLnk->DBDelete();

		$this->AssertEventNotReceived(EVENT_DB_LINKS_CHANGED, 'LinkSet without with_php_computation attribute should not receive EVENT_DB_LINKS_CHANGED');
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
		$this->AssertEventCountEquals(1, EVENT_DB_LINKS_CHANGED, 'LinkSet with with_php_computation attribute should receive EVENT_DB_LINKS_CHANGED');
		$this->AssertTotalEventCountEquals(1, 'Only EVENT_DB_LINKS_CHANGED event must be fired on host class during link modification');

		self::CleanCallCount();
		// Update the link with a new server
		$oServer2 = $this->CreateServer(2);
		$oLink->Set('functionalci_id', $oServer2->GetKey());
		$oLink->DBUpdate();

		// one link where modified outside the object
		$this->AssertEventCountEquals(1, EVENT_DB_LINKS_CHANGED, 'LinkSet with with_php_computation attribute should receive EVENT_DB_LINKS_CHANGED');
		$this->AssertTotalEventCountEquals(1, 'Only EVENT_DB_LINKS_CHANGED event must be fired on host class during link modification');

		self::CleanCallCount();
		// Delete link
		$oLink->DBDelete();

		// one link where deleted outside the object
		$this->AssertEventCountEquals(1, EVENT_DB_LINKS_CHANGED, 'LinkSet with with_php_computation attribute should receive EVENT_DB_LINKS_CHANGED');
		$this->AssertTotalEventCountEquals(1, 'Only EVENT_DB_LINKS_CHANGED event must be fired on host class during link modification');
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
		$this->AssertTotalEventCountEquals(0, 'EVENT_ENUM_TRANSITIONS should not be fired for objects without lifecycle');

		// Object with lifecycle
		$oTicket = $this->CreateTicket(1);
		$aRefTransitions = array_keys($oTicket->EnumTransitions());
		$oEventReceiver->AddCallback(EVENT_ENUM_TRANSITIONS, UserRequest::class, 'DenyAllTransitions');
		self::CleanCallCount();
		$aTransitions = $oTicket->EnumTransitions();
		$this->AssertEventCountEquals(1, EVENT_ENUM_TRANSITIONS, 'EVENT_ENUM_TRANSITIONS should be fired for objects with lifecycle');
		$this->AssertTotalEventCountEquals(1, 'EVENT_ENUM_TRANSITIONS is the only event fired by DBObject::EnumTransitions()');
		$this->assertCount(0, $aTransitions, 'All transitions should have been denied');

		$oEventReceiver->AddCallback(EVENT_ENUM_TRANSITIONS, UserRequest::class, 'DenyAssignTransition');
		self::CleanCallCount();
		$aTransitions = $oTicket->EnumTransitions();
		$this->AssertEventCountEquals(1, EVENT_ENUM_TRANSITIONS, 'EVENT_ENUM_TRANSITIONS should be fired for objects with lifecycle');
		$this->AssertTotalEventCountEquals(1, 'EVENT_ENUM_TRANSITIONS is the only event fired by DBObject::EnumTransitions()');
		$this->assertArrayNotHasKey('ev_assign', $aTransitions, 'Assign transition should have been removed by EVENT_ENUM_TRANSITIONS handler');
		$this->assertEquals(1, count($aRefTransitions) - count($aTransitions), 'Only one transition should have been removed');
	}
}
