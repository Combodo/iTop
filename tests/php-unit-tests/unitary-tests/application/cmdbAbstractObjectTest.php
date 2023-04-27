<?php

use Combodo\iTop\Service\Events\EventData;
use Combodo\iTop\Service\Events\EventService;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class cmdbAbstractObjectTest extends ItopDataTestCase {
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

	public function testCheckLinkModifications() {
		$aLinkModificationsStack = $this->GetObjectsAwaitingFireEventDbLinksChanged();
		$this->assertSame([], $aLinkModificationsStack);

		// retain events
		cmdbAbstractObject::SetEventDBLinksChangedBlocked(true);

		// Create the person
		$oPerson = $this->CreatePerson(1);
		$this->assertIsObject($oPerson);
		// Create the team
		$oTeam = MetaModel::NewObject(Team::class, ['name' => 'TestTeam1', 'org_id' => $this->getTestOrgId()]);
		$oTeam->DBInsert();
		// contact types
		$oContactType1 = MetaModel::NewObject(ContactType::class, ['name' => 'test_'.rand(10000, 99999)]);
		$oContactType1->DBInsert();
		$oContactType2 = MetaModel::NewObject(ContactType::class, ['name' => 'test_'.rand(10000, 99999)]);
		$oContactType2->DBInsert();

		// Prepare the link for the insertion with the team

		$aValues = [
			'person_id' => $oPerson->GetKey(),
			'role_id' => $oContactType1->GetKey(),
			'team_id' => $oTeam->GetKey(),
		];
		$oLinkPersonToTeam1 = MetaModel::NewObject(lnkPersonToTeam::class, $aValues);
		$oLinkPersonToTeam1->DBInsert();

		$aLinkModificationsStack = $this->GetObjectsAwaitingFireEventDbLinksChanged();
		self::assertCount(3, $aLinkModificationsStack);
		$aExpectedLinkStack = [
			'Team'        => [$oTeam->GetKey() => 1],
			'Person'      => [$oPerson->GetKey() => 1],
			'ContactType' => [$oContactType1->GetKey() => 1],
		];
		self::assertSame($aExpectedLinkStack, $aLinkModificationsStack);

		$oLinkPersonToTeam1->Set('role_id', $oContactType2->GetKey());
		$oLinkPersonToTeam1->DBWrite();
		$aLinkModificationsStack = $this->GetObjectsAwaitingFireEventDbLinksChanged();
		self::assertCount(3, $aLinkModificationsStack);
		$aExpectedLinkStack = [
			'Team'        => [$oTeam->GetKey() => 2],
			'Person'      => [$oPerson->GetKey() => 2],
			'ContactType' => [
				$oContactType1->GetKey() => 2,
				$oContactType2->GetKey() => 1,
			],
		];
		self::assertSame($aExpectedLinkStack, $aLinkModificationsStack);
	}

	public function testProcessClassIdDeferredUpdate()
	{
		// Create the team
		$oTeam = MetaModel::NewObject(Team::class, ['name' => 'TestTeam1', 'org_id' => $this->getTestOrgId()]);
		$oTeam->DBInsert();

		// --- Simulating modifications of :
		// - lnkPersonToTeam:1 is sample data with : team_id=39 ; person_id=9 ; role_id=3
		// - lnkPersonToTeam:2 is sample data with : team_id=39 ; person_id=14 ; role_id=0
		$aLinkStack = [
			'Team'        => [$oTeam->GetKey() => 2],
			'Person'      => [
				'9'  => 1,
				'14' => 1,
			],
			'ContactType' => [
				'1' => 1,
				'0' => 1,
			],
		];
		$this->SetObjectsAwaitingFireEventDbLinksChanged($aLinkStack);

		// Processing deferred updates for Team
		$this->InvokeNonPublicMethod(get_class($oTeam), 'FireEventDbLinksChangedForCurrentObject', $oTeam, []);
		$aLinkModificationsStack = $this->GetObjectsAwaitingFireEventDbLinksChanged();
		$aExpectedLinkStack = [
			'Team'        => [],
			'Person'      => [
				'9'  => 1,
				'14' => 1,
			],
			'ContactType' => [
				'1' => 1,
				'0' => 1,
			],
		];
		self::assertSame($aExpectedLinkStack, $aLinkModificationsStack);


		// --- Simulating modifications of :
		// - lnkApplicationSolutionToFunctionalCI::2 : applicationsolution_id=13 ; functionalci_id=29
		// - lnkApplicationSolutionToFunctionalCI::8 : applicationsolution_id=13 ; functionalci_id=27
		// The lnkApplicationSolutionToFunctionalCI points on root classes, so we can test unstacking for a leaf class
		$aLinkStack = [
			'ApplicationSolution' => ['13' => 2],
			'FunctionalCI'        => [
				'29' => 1,
				'27' => 1,
			],
		];
		$this->SetObjectsAwaitingFireEventDbLinksChanged($aLinkStack);

		// Processing deferred updates for WebServer::29
		/** @var \cmdbAbstractObject $oLinkPersonToTeam1 */
		$oWebServer29 = MetaModel::GetObject(WebServer::class, 29);
		$this->InvokeNonPublicMethod(get_class($oWebServer29), 'FireEventDbLinksChangedForCurrentObject', $oWebServer29, []);
		$aLinkModificationsStack = $this->GetObjectsAwaitingFireEventDbLinksChanged();
		$aExpectedLinkStack = [
			'ApplicationSolution' => ['13' => 2],
			'FunctionalCI'        => [
				'27' => 1,
			],
		];
		self::assertSame($aExpectedLinkStack, $aLinkModificationsStack);
	}

	private function GetObjectsAwaitingFireEventDbLinksChanged(): array
	{
		return $this->GetNonPublicStaticProperty(cmdbAbstractObject::class, 'aObjectsAwaitingEventDbLinksChanged');
	}

	private function SetObjectsAwaitingFireEventDbLinksChanged(array $aObjects): void
	{
		$this->SetNonPublicStaticProperty(cmdbAbstractObject::class, 'aObjectsAwaitingEventDbLinksChanged', $aObjects);
	}

	/**
	 * Check that EVENT_DB_LINKS_CHANGED events are not sent to the current updated/created object (Team)
	 * the events are sent to the other side (Person)
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

		$oEventReceiver = new LinksEventReceiver();
		$oEventReceiver->RegisterCRUDListeners();

		$oTeam = MetaModel::NewObject(Team::class, ['name' => 'TestTeam1', 'persons_list' => $oLinkSet, 'org_id' => $this->getTestOrgId()]);
		$oTeam->DBInsert();
		$this->assertIsObject($oTeam);

		// 3 links added to person  + 1 for the Team
		$this->assertEquals(4, self::$aEventCalls[EVENT_DB_LINKS_CHANGED]);
	}

	/**
	 * Check that EVENT_DB_LINKS_CHANGED events are sent to all the linked objects when creating a new lnk object
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
	public function testAddLinkToTeam()
	{
		// Create a person
		$oPerson = $this->CreatePerson(1);
		$this->assertIsObject($oPerson);

		// Create a Team
		$oTeam = MetaModel::NewObject(Team::class, ['name' => 'TestTeam1', 'org_id' => $this->getTestOrgId()]);
		$oTeam->DBInsert();
		$this->assertIsObject($oTeam);

		$this->debug("\n-------------> Test Starts HERE\n");
		$oEventReceiver = new LinksEventReceiver();
		$oEventReceiver->RegisterCRUDListeners();

		// The link creation will signal both the Person an the Team
		$oLink = MetaModel::NewObject(lnkPersonToTeam::class, ['person_id' => $oPerson->GetKey(), 'team_id' => $oTeam->GetKey()]);
		$oLink->DBInsert();

		// 2 events one for Person and One for Team
		$this->assertEquals(2, self::$aEventCalls[EVENT_DB_LINKS_CHANGED]);
	}

	/**
	 * Check that EVENT_DB_LINKS_CHANGED events are sent to all the linked objects when updating an existing lnk object
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
	public function testUpdateLinkRole()
	{
		// Create a person
		$oPerson = $this->CreatePerson(1);
		$this->assertIsObject($oPerson);

		// Create a Team
		$oTeam = MetaModel::NewObject(Team::class, ['name' => 'TestTeam1', 'org_id' => $this->getTestOrgId()]);
		$oTeam->DBInsert();
		$this->assertIsObject($oTeam);

		// Create the link
		$oLink = MetaModel::NewObject(lnkPersonToTeam::class, ['person_id' => $oPerson->GetKey(), 'team_id' => $oTeam->GetKey()]);
		$oLink->DBInsert();

		$this->debug("\n-------------> Test Starts HERE\n");
		$oEventReceiver = new LinksEventReceiver();
		$oEventReceiver->RegisterCRUDListeners();

		// The link update will signal both the Person, the Team and the ContactType
		// Change the role
		$oContactType = MetaModel::NewObject(ContactType::class, ['name' => 'test_'.$oLink->GetKey()]);
		$oContactType->DBInsert();
		$oLink->Set('role_id', $oContactType->GetKey());
		$oLink->DBUpdate();

		// 3 events one for Person, one for Team and one for ContactType
		$this->assertEquals(3, self::$aEventCalls[EVENT_DB_LINKS_CHANGED]);
	}

	/**
	 * Check that when a link changes from an object to another, then both objects are notified
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
	public function testUpdateLinkPerson()
	{
		// Create 2 person
		$oPerson1 = $this->CreatePerson(1);
		$this->assertIsObject($oPerson1);

		$oPerson2 = $this->CreatePerson(2);
		$this->assertIsObject($oPerson2);

		// Create a Team
		$oTeam = MetaModel::NewObject(Team::class, ['name' => 'TestTeam1', 'org_id' => $this->getTestOrgId()]);
		$oTeam->DBInsert();
		$this->assertIsObject($oTeam);

		// Create the link between Person1 and Team
		$oLink = MetaModel::NewObject(lnkPersonToTeam::class, ['person_id' => $oPerson1->GetKey(), 'team_id' => $oTeam->GetKey()]);
		$oLink->DBInsert();

		$this->debug("\n-------------> Test Starts HERE\n");
		$oEventReceiver = new LinksEventReceiver();
		$oEventReceiver->RegisterCRUDListeners();

		// The link update will signal both the Persons and the Team
		// Change the person
		$oLink->Set('person_id', $oPerson2->GetKey());
		$oLink->DBUpdate();

		// 3 events 2 for Person, one for Team
		$this->assertEquals(3, self::$aEventCalls[EVENT_DB_LINKS_CHANGED]);
	}

	/**
	 * Check that EVENT_DB_LINKS_CHANGED events are sent to all the linked objects when deleting an existing lnk object
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
	public function testDeleteLink()
	{
		// Create a person
		$oPerson = $this->CreatePerson(1);
		$this->assertIsObject($oPerson);

		// Create a Team
		$oTeam = MetaModel::NewObject(Team::class, ['name' => 'TestTeam1', 'org_id' => $this->getTestOrgId()]);
		$oTeam->DBInsert();
		$this->assertIsObject($oTeam);

		// Create the link
		$oLink = MetaModel::NewObject(lnkPersonToTeam::class, ['person_id' => $oPerson->GetKey(), 'team_id' => $oTeam->GetKey()]);
		$oLink->DBInsert();

		$this->debug("\n-------------> Test Starts HERE\n");
		$oEventReceiver = new LinksEventReceiver();
		$oEventReceiver->RegisterCRUDListeners();

		// The link delete will signal both the Person an the Team
		$oLink->DBDelete();

		// 3 events one for Person, one for Team
		$this->assertEquals(2, self::$aEventCalls[EVENT_DB_LINKS_CHANGED]);
	}

	/**
	 * Debug called by event receivers
	 * 
	 * @param $sMsg
	 *
	 * @return void
	 */
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
 * Count events received
 * And allow callbacks on events
 */
class LinksEventReceiver
{
	private $aCallbacks = [];

	public static $bIsObjectInCrudStack;

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
	}

	// Event callbacks
	public function OnEvent(EventData $oData)
	{
		$sEvent = $oData->GetEvent();
		$oObject = $oData->Get('object');
		$sClass = get_class($oObject);
		$iKey = $oObject->GetKey();
		$this->Debug(__METHOD__.": received event '$sEvent' for $sClass::$iKey");
		cmdbAbstractObjectTest::IncrementCallCount($sEvent);

		if (isset($this->aCallbacks[$sEvent][$sClass])) {
			$aCallBack = $this->aCallbacks[$sEvent][$sClass];
			if ($aCallBack['count'] > 0) {
				$this->aCallbacks[$sEvent][$sClass]['count']--;
				call_user_func($this->aCallbacks[$sEvent][$sClass]['callback'], $oObject);
			}
		}
	}

	public function RegisterCRUDListeners(string $sEvent = null, $mEventSource = null)
	{
		$this->Debug('Registering Test event listeners');
		if (is_null($sEvent)) {
			EventService::RegisterListener(EVENT_DB_LINKS_CHANGED, [$this, 'OnEvent']);
			return;
		}
		EventService::RegisterListener($sEvent, [$this, 'OnEvent'], $mEventSource);
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

	private function CheckCrudStack(DBObject $oObject): void
	{
		self::$bIsObjectInCrudStack = DBObject::IsObjectCurrentlyInCrud(get_class($oObject), $oObject->GetKey());
	}

	private function CheckUpdateInLnk(lnkPersonToTeam $oLnkPersonToTeam)
	{
		$iTeamId = $oLnkPersonToTeam->Get('team_id');
		self::$bIsObjectInCrudStack = DBObject::IsObjectCurrentlyInCrud(Team::class, $iTeamId);
	}

	private function Debug($msg)
	{
		cmdbAbstractObjectTest::DebugStatic($msg);
	}
}
