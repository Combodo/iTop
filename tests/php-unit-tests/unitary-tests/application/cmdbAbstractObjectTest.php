<?php

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class cmdbAbstractObjectTest extends ItopDataTestCase {
	const USE_TRANSACTION = true;
	const CREATE_TEST_ORG = true;

	protected function setUp(): void
	{
		parent::setUp();
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
}
