<?php

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class cmdbAbstractObjectTest extends ItopDataTestCase {
	public function testCheckLinkModifications() {
		$aLinkModificationsStack = $this->GetObjectsAwaitingFireEventDbLinksChanged();
		$this->assertSame([], $aLinkModificationsStack);

		// lnkPersonToTeam:1 is sample data with : team_id=39 ; person_id=9 ; role_id=3
		$oLinkPersonToTeam1 = MetaModel::GetObject(lnkPersonToTeam::class, 1);
		$oLinkPersonToTeam1->Set('role_id', 1);
		$oLinkPersonToTeam1->DBWrite();
		$aLinkModificationsStack = $this->GetObjectsAwaitingFireEventDbLinksChanged();
		self::assertCount(3, $aLinkModificationsStack);
		$aExpectedLinkStack = [
			'Team'        => ['39' => 1],
			'Person'      => ['9' => 1],
			'ContactType' => ['1' => 1],
		];
		self::assertSame($aExpectedLinkStack, $aLinkModificationsStack);

		$oLinkPersonToTeam1->Set('role_id', 2);
		$oLinkPersonToTeam1->DBWrite();
		$aLinkModificationsStack = $this->GetObjectsAwaitingFireEventDbLinksChanged();
		self::assertCount(3, $aLinkModificationsStack);
		$aExpectedLinkStack = [
			'Team'        => ['39' => 2],
			'Person'      => ['9' => 2],
			'ContactType' => [
				'1' => 1,
				'2' => 1,
			],
		];
		self::assertSame($aExpectedLinkStack, $aLinkModificationsStack);
	}

	public function testProcessClassIdDeferedUpdate()
	{
		// --- Simulating modifications of :
		// - lnkPersonToTeam:1 is sample data with : team_id=39 ; person_id=9 ; role_id=3
		// - lnkPersonToTeam:2 is sample data with : team_id=39 ; person_id=14 ; role_id=0
		$aLinkStack = [
			'Team'        => ['39' => 2],
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

		// Processing deferred updates for Team::39
		/** @var \cmdbAbstractObject $oTeam39 */
		$oTeam39 = MetaModel::GetObject(Team::class, 39);
		$oTeam39->FireEventDbLinksChangedForCurrentObject();
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