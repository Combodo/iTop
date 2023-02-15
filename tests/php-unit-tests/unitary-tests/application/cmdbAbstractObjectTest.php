<?php

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class cmdbAbstractObjectTest extends ItopDataTestCase {
	const USE_TRANSACTION = true;
	const CREATE_TEST_ORG = true;

	public function testCheckLinkModifications() {
		$aLinkModificationsStack = $this->GetObjectsAwaitingFireEventDbLinksChanged();
		$this->assertSame([], $aLinkModificationsStack);

		// retain events
		cmdbAbstractObject::SetEventDBLinksChangedAllowed(false);

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
				$oContactType1->GetKey() => 1,
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
		$oTeam->FireEventDbLinksChangedForCurrentObject();
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
		$oWebServer29->FireEventDbLinksChangedForCurrentObject();
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
}