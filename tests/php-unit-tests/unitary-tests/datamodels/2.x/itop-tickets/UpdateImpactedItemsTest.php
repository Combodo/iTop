<?php
// Copyright (c) 2010-2024 Combodo SAS
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
//

namespace Combodo\iTop\Test\UnitTest\Module\iTopTickets;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Exception;
use MetaModel;
use Organization;
use UserRights;


/**
 * @group itopVirtualizationMgmt
 * @group itopConfigMgmt
 * @group itopTickets
 */
class UpdateImpactedItemsTest extends ItopDataTestCase
{
	const CREATE_TEST_ORG = true;
	/**
	 * @var Object Names to Ids
	 */
	private array $aCIs = [];

	protected function setUp(): void
	{
		parent::setUp();
		$this->aCIs = [];
	}

	protected function tearDown(): void
	{
		parent::tearDown();
		UserRights::Logoff();
		$this->ResetMetaModelQueyCacheGetObject();
	}

	public function testImpactShouldBePropagatedToDirectDescendants()
	{
		/**
		 * Server1 +----> Hypervisor1
		 *         +====> Person1
		 */
		$this->GivenCITreeInDB(<<<EOF
			Hypervisor_1 -> Server_1
			Server_1 <-> Person_1
		EOF);
		$oTicket = $this->GivenTicketWithCIsOrPersons([
			'Server_1' => 'manual'
		]);

		$oTicket->UpdateImpactedItems(); // impact analysis

		$this->assertCIsOrPersonsListEquals($oTicket, [
			'Server_1' => 'manual',
			'Hypervisor_1' => 'computed',
			'Test Person_1' => 'computed'
		]);
	}



	public function testImpactShouldBePropagatedInOneWayOnly()
	{
		/**
		 * Server1 +----> Hypervisor1
		 */
		$this->GivenCITreeInDB(<<<EOF
			Hypervisor_1 -> Server_1
		EOF);
		$oTicket = $this->GivenTicketWithCIsOrPersons([
			'Hypervisor_1' => 'manual'
		]);

		$oTicket->UpdateImpactedItems(); // impact analysis

		$this->assertCIsOrPersonsListEquals($oTicket, [
			'Hypervisor_1' => 'manual',
		]);
	}

	public function testImpactShouldBePropagatedRecursively()
	{
		/**
		 * Server1 +----> Hypervisor1 +----> Farm +====> Person1
		 */
		$this->GivenCITreeInDB(<<<EOF
			Hypervisor_1 -> Server_1, Farm_1
			Farm_1 <-> Person_1
		EOF);
		$oTicket = $this->GivenTicketWithCIsOrPersons([
			'Server_1' => 'manual'
		]);

		$oTicket->UpdateImpactedItems(); // impact analysis

		$this->assertCIsOrPersonsListEquals($oTicket, [
			'Server_1' => 'manual',
			'Hypervisor_1' => 'computed',
			'Farm_1' => 'computed',
			'Test Person_1' => 'computed'
		]);
	}

	public function testImpactShouldBePropagatedRecursivelyUpToMaxDepth()
	{
		/**
		 * ApplicationSolution_0 +----> ApplicationSolution_1 +----> ApplicationSolution_2 +----->  ..... +----> ApplicationSolution_12
		 */
		$this->GivenCITreeInDB(<<<EOF
			ApplicationSolution_0 <-> ApplicationSolution_1
			ApplicationSolution_1 <-> ApplicationSolution_2
			ApplicationSolution_2 <-> ApplicationSolution_3
			ApplicationSolution_3 <-> ApplicationSolution_4
			ApplicationSolution_4 <-> ApplicationSolution_5
			ApplicationSolution_5 <-> ApplicationSolution_6
			ApplicationSolution_6 <-> ApplicationSolution_7
			ApplicationSolution_7 <-> ApplicationSolution_8
			ApplicationSolution_8 <-> ApplicationSolution_9
			ApplicationSolution_9 <-> ApplicationSolution_10
			ApplicationSolution_10 <-> ApplicationSolution_11
			ApplicationSolution_11 <-> ApplicationSolution_12
			ApplicationSolution_12 <-> ApplicationSolution_13
		EOF);
		$oTicket = $this->GivenTicketWithCIsOrPersons([
			'ApplicationSolution_0' => 'manual'
		]);

		$oTicket->UpdateImpactedItems(); // impact analysis

		// By default Max depth is 10. Can be changed by overriding _Ticket::GetImpactAnalysisMaxDepth
		$this->assertCIsOrPersonsListEquals($oTicket, [
			'ApplicationSolution_0' => 'manual',
			'ApplicationSolution_1' => 'computed',
			'ApplicationSolution_2' => 'computed',
			'ApplicationSolution_3' => 'computed',
			'ApplicationSolution_4' => 'computed',
			'ApplicationSolution_5' => 'computed',
			'ApplicationSolution_6' => 'computed',
			'ApplicationSolution_7' => 'computed',
			'ApplicationSolution_8' => 'computed',
			'ApplicationSolution_9' => 'computed',
			'ApplicationSolution_10' => 'computed',
		]);
	}

	public function testImpactShouldNotBeFurtherPropagatedWhenCINotAllowed()
	{
		/**
		 * Server1 +----> Hypervisor1 +----> Farm
		 */
		$this->GivenCITreeInDB(<<<EOF
			Hypervisor_1 -> Server_1, Farm_1
		EOF);
		$this->GivenCINotAllowedToCurrentUser('Hypervisor_1');
		$oTicket = $this->GivenTicketWithCIsOrPersons([
			'Server_1' => 'manual'
		]);

		MetaModel::GetConfig()->Set('relations.complete_analysis', false);

		$oTicket->UpdateImpactedItems(); // impact analysis

		$this->assertCIsOrPersonsListEquals($oTicket, [
			'Server_1' => 'manual',
		]);
	}

	public function testImpactShouldBeFurtherPropagatedAboveCINotAllowedWhenCompleteAnalysisRequested()
	{
		/**
		 * Server1 +----> Hypervisor1 +----> Farm
		 */
		$this->GivenCITreeInDB(<<<EOF
			Hypervisor_1 -> Server_1, Farm_1
		EOF);
		$this->GivenCINotAllowedToCurrentUser('Hypervisor_1');
		$oTicket = $this->GivenTicketWithCIsOrPersons([
			'Server_1' => 'manual'
		]);

		MetaModel::GetConfig()->Set('relations.complete_analysis', true);

		$oTicket->UpdateImpactedItems(); // impact analysis

		$this->assertCIsOrPersonsListEquals($oTicket, [
			'Server_1' => 'manual',
			'Farm_1' => 'computed',
		]);
	}

	public function testImpactShouldBePropagatedToAllDescendantsOfSameClass()
	{
		/**
		 * Server1 +----> Hypervisor1
		 *         +----> Hypervisor2
		 */
		$this->GivenCITreeInDB(<<<DOT
			Hypervisor_1 -> Server_1
			Hypervisor_2 -> Server_1
		DOT);
		$oTicket = $this->GivenTicketWithCIsOrPersons([
			'Server_1' => 'manual',
		]);

		$oTicket->UpdateImpactedItems(); // impact analysis

		$this->assertCIsOrPersonsListEquals($oTicket, [
			'Server_1' => 'manual',
			'Hypervisor_1' => 'computed',
			'Hypervisor_2' => 'computed',
		]);
	}
	public function testNodesImpactedByTwoWaysShouldBeFoundOnce()
	{
		/**
		 *           +------------------+
		 * Hypervisor1                  |
		 *           +====> Person1     +----> Farm1
		 * Hypervisor2                  |
		 *           +------------------+
		 */
		$this->GivenCITreeInDB(<<<DOT
			Hypervisor_1 -> Farm_1
			Hypervisor_2 -> Farm_1
			Hypervisor_1 <-> Person_1
			Hypervisor_2 <-> Person_1
		DOT);
		$oTicket = $this->GivenTicketWithCIsOrPersons([
			'Hypervisor_1' => 'manual',
			'Hypervisor_2' => 'manual',
		]);

		$oTicket->UpdateImpactedItems(); // impact analysis

		$this->assertCIsOrPersonsListEquals($oTicket, [
			'Hypervisor_1' => 'manual',
			'Hypervisor_2' => 'manual',
			'Farm_1' => 'computed',
			'Test Person_1' => 'computed'
		]);
	}
	public function testPreviouslyComputedNodesShouldBeIgnored()
	{
		/**
		 * Server1 +----> Hypervisor1 +----> Person1
		 *
		 * Server2 +----> Person2
		 */
		$this->GivenCITreeInDB(<<<EOF
			Hypervisor_1 -> Server_1
			Hypervisor_1 <-> Person_1
			Server_2 <-> Person_2
		EOF);
		$oTicket = $this->GivenTicketWithCIsOrPersons([
			'Server_1' => 'manual',
			'Server_2' => 'computed',
			'Person_2' => 'computed'
		]);

		$oTicket->UpdateImpactedItems(); // impact analysis

		$this->assertCIsOrPersonsListEquals($oTicket, [
			'Server_1' => 'manual',
			'Hypervisor_1' => 'computed',
			'Test Person_1' => 'computed'
		]);
	}

	public function testPreviouslyComputedNodesShouldBeIgnoredCausingTheListToCollapse()
	{
		/**
		 * Server1 +----> Hypervisor1 +----> Person1
		 */
		$this->GivenCITreeInDB(<<<EOF
			Hypervisor_1 -> Server_1
			Hypervisor_1 <-> Person_1
		EOF);
		$oTicket = $this->GivenTicketWithCIsOrPersons([
			'Hypervisor_1' => 'computed',
			'Person_1' => 'computed'
		]);

		$oTicket->UpdateImpactedItems(); // impact analysis

		$this->assertCIsOrPersonsListEquals($oTicket, []);
	}


	public function testNoImpactWhenNoCI()
	{
		$oTicket = $this->GivenTicketWithCIsOrPersons([]);

		$oTicket->UpdateImpactedItems(); // impact analysis

		$this->assertCIsOrPersonsListEquals($oTicket, []);
	}

	public function testRedundancyShouldPreventPropagationOfImpact()
	{
		/**
		 * Hypervisor1
		 *           +----> Farm1
		 * Hypervisor2
		 */
		$this->GivenCITreeInDB(<<<DOT
			Hypervisor_1 -> Farm_1
			Hypervisor_2 -> Farm_1
		DOT);
		$oTicket = $this->GivenTicketWithCIsOrPersons([
			'Hypervisor_1' => 'manual'
		]);

		$oTicket->UpdateImpactedItems(); // impact analysis

		$this->assertCIsOrPersonsListEquals($oTicket, [
			'Hypervisor_1' => 'manual'
		]);
	}

	public function testRedundancyShouldNotPreventPropagationWhenEverySourceIsImpacted()
	{
		/**
		 * Hypervisor1
		 *           +----> Farm1
		 * Hypervisor2
		 */
		$this->GivenCITreeInDB(<<<DOT
			Hypervisor_1 -> Farm_1
			Hypervisor_2 -> Farm_1
		DOT);
		$oTicket = $this->GivenTicketWithCIsOrPersons([
			'Hypervisor_1' => 'manual',
			'Hypervisor_2' => 'manual'
		]);

		$oTicket->UpdateImpactedItems(); // impact analysis

		$this->assertCIsOrPersonsListEquals($oTicket, [
			'Hypervisor_1' => 'manual',
			'Hypervisor_2' => 'manual',
			'Farm_1' => 'computed'
		]);
	}

	public function testCIsMarkedAsNotImpactedShouldRemainMarkedAndShouldNotPropagateTheImpact()
	{
		/**
		 * Server1 +----> Hypervisor1 +----> Farm1
		 *                            +====> Person1
		 */
		$this->GivenCITreeInDB(<<<DOT
			Hypervisor_1 -> Server_1, Farm_1
			Hypervisor_1 <-> Person_1
		DOT);
		$oTicket = $this->GivenTicketWithCIsOrPersons([
			'Server_1' => 'manual',
			'Hypervisor_1' => 'not_impacted',
			'Person_1' => 'do_not_notify'
		]);

		$oTicket->UpdateImpactedItems(); // impact analysis

		$this->assertCIsOrPersonsListEquals($oTicket, [
			'Server_1' => 'manual',
			'Hypervisor_1' => 'not_impacted',
			'Test Person_1' => 'do_not_notify'
		]);
	}

	public function testCIsMarkedAsNotImpactedShouldRemainMarkedWhenNotInImpactGraph()
	{
		/**
		 * Hypervisor1 +====> Person1
		 */
		$this->GivenCITreeInDB(<<<DOT
			Hypervisor_1 <-> Person_1
		DOT);
		$oTicket = $this->GivenTicketWithCIsOrPersons([
			'Hypervisor_1' => 'not_impacted',
			'Person_1' => 'do_not_notify'
		]);

		$oTicket->UpdateImpactedItems(); // impact analysis

		$this->assertCIsOrPersonsListEquals($oTicket, [
			'Hypervisor_1' => 'not_impacted',
			'Test Person_1' => 'do_not_notify'
		]);
	}

	public function testRedundancyShouldBeEvaluatedOnOtherTicketsToo()
	{
		/**
		 * Hypervisor1
		 *           +----> Farm1
		 * Hypervisor2
		 */
		$this->GivenCITreeInDB(<<<DOT
			Hypervisor_1 -> Farm_1
			Hypervisor_2 -> Farm_1
		DOT);
		$oTicket1 = $this->GivenTicketWithCIsOrPersons([
			'Hypervisor_1' => 'manual',
		]);

		$oTicket1->DBWrite(); // impact analysis
		$this->GivenRelationContextQueryWillFindTicket($oTicket1->GetKey());

		$oTicket2 = $this->GivenTicketWithCIsOrPersons([
			'Hypervisor_2' => 'manual',
		]);

		/// TEST Begins Here
		$oTicket2->UpdateImpactedItems();

		// The second ticket should have the impact propagated
		$this->assertCIsOrPersonsListEquals($oTicket2, [
			'Hypervisor_2' => 'manual',
			'Farm_1' => 'computed',
		]);

		$this->ReloadObject($oTicket1); // reload the links

		// The first ticket should remain in its initial state
		$this->assertCIsOrPersonsListEquals($oTicket1, [
			'Hypervisor_1' => 'manual',
		]);
	}

	private function assertCIsOrPersonsListEquals(\UserRequest $oTicket, array $aExpected)
	{
		$aActual = [];
		foreach ($oTicket->Get('functionalcis_list') as $oLnk) {
			$sKey = $oLnk->Get('functionalci_id_friendlyname');
			$aActual[$sKey] = $oLnk->Get('impact_code');
		}
		foreach ($oTicket->Get('contacts_list') as $oLnk) {
			$sKey = $oLnk->Get('contact_id_friendlyname');
			$aActual[$sKey] = $oLnk->Get('role_code');
		}
		$this->assertEquals($aExpected, $aActual, 'Unexpected value for functionalcis_list');
	}


	/**
	 * @param int|string|null $sTicketId
	 *
	 * @return void
	 */
	public function GivenRelationContextQueryWillFindTicket(int|string|null $sTicketId): void
	{
		$aRelationContext = \MetaModel::GetConfig()->GetModuleSetting('itop-tickets', 'relation_context');
		$aRelationContext['UserRequest']['impacts']['down']['items'][0]['oql'] = "SELECT FCI, R
            FROM FunctionalCI AS FCI
                     JOIN lnkFunctionalCIToTicket AS L ON L.functionalci_id = FCI.id
                     JOIN UserRequest AS R ON L.ticket_id = R.id
            WHERE (R.id = $sTicketId)";
		\MetaModel::GetConfig()->SetModuleSetting('itop-tickets', 'relation_context', $aRelationContext);
	}
	private function GivenCITreeInDB(string $sTree)
	{
		$aTree = explode("\n", $sTree);
		foreach ($aTree as $sLine) {
			$this->GivenCITreeLineInDB($sLine);
		}
	}

	private function GivenCITreeLineInDB(string $sLine)
	{
		if (strpos($sLine, '<->') !== false) {
			list($sCI, $sPerson) = explode('<->', $sLine);
			$sPersonId = $this->GivenCIOrPersonInDB(trim($sPerson));
			$sCIId = $this->GivenCIOrPersonInDB(trim($sCI));
			if (str_starts_with(trim($sPerson),'ApplicationSolution'))
			{
				$this->GivenLnkApplicationSolutionToFunctionalCIInDB($sPersonId, $sCIId);
			} else {
				$this->GivenLnkContactToFunctionalCIInDB($sPersonId, $sCIId);
			}
			return;
		}
		list($sCIParent, $sChildren) = explode('->', $sLine);
		$aChildren = explode(',', $sChildren);
		$aChildren = array_map('trim', $aChildren);
		$aChildrenIdsByClass = [];
		foreach ($aChildren as $sChildCI) {
			list($sChildClass, ) = explode('_', $sChildCI, 2);
			$aChildrenIdsByClass[$sChildClass] = $this->GivenCIOrPersonInDB($sChildCI);
		}
		$this->GivenCIOrPersonInDB($sCIParent, $aChildrenIdsByClass);
	}

	private function GivenCIOrPersonInDB(string $sDescriptor, array $aChildrenIdsByClass = []): string
	{
		$sDescriptor = trim($sDescriptor);
		if (isset($this->aCIs[$sDescriptor])) {
			return $this->aCIs[$sDescriptor];
		}
		list($sClass, $sRef) = explode('_', $sDescriptor, 2);
		switch ($sClass) {
			case 'Server':
				$sCIId = $this->GivenServerInDB($sRef);
				break;
			case 'Hypervisor':
				$sCIId = $this->GivenHypervisorInDB($sRef, $aChildrenIdsByClass['Server'] ?? 0, $aChildrenIdsByClass['Farm'] ?? 0);
				break;
			case 'Person':
				$sCIId = $this->GivenPersonInDB($sRef);
				break;
			case 'Farm':
				$sCIId = $this->GivenFarmInDB($sRef);
				break;
			case 'VirtualMachine':
				$sCIId = $this->GivenVirtualMachineInDB($sRef, $aChildrenIdsByClass['Farm']);
				break;
			case 'ApplicationSolution':
				$sCIId = $this->GivenApplicationSolutionInDB($sRef);
				break;
			default:
				throw new Exception("Unhandled class $sClass");
		}
		$this->aCIs[$sDescriptor] = $sCIId;
		return $this->aCIs[$sDescriptor];
	}

	private function GivenTicketWithCIsOrPersons(array $aLinkedObjects) : \UserRequest
	{
		$oTicket = $this->GivenTicketObject(1);
		foreach ($aLinkedObjects as $sObjectDescriptor => $sRole) {
			$sClass = trim(explode('_', $sObjectDescriptor)[0]);
			if ($sClass === 'Person') {
				$this->AddLnkContactToTicketObject($this->GivenCIOrPersonInDB($sObjectDescriptor), $oTicket, $sRole);
				continue;
			}
			$this->AddLnkFunctionalCIToTicketObject($this->GivenCIOrPersonInDB($sObjectDescriptor), $oTicket, $sRole);
		}
		return $oTicket;
	}

	private function GivenCINotAllowedToCurrentUser(string $sCIDescriptor)
	{
		$iAnotherOrg = $this->GivenObjectInDB(Organization::class, ['name' => 'Another Org']);

		// Change the organization of the CI to 'Another Org'
		$sCIDescriptor = trim($sCIDescriptor);
		if (!isset($this->aCIs[$sCIDescriptor])) {
			throw new Exception("CI $sCIDescriptor not found");
		}
		$sCIId = $this->aCIs[$sCIDescriptor];

		$oCI = \MetaModel::GetObject('FunctionalCI', $sCIId);
		$oCI->Set('org_id', $iAnotherOrg);
		$oCI->DBUpdate();

		$sConfigManagerProfileId = 3; // access to CIs
		$sLogin = $this->GivenUserRestrictedToAnOrganizationInDB($this->getTestOrgId(), $sConfigManagerProfileId);

		UserRights::Login($sLogin);
		$this->ResetMetaModelQueyCacheGetObject();
	}
}

