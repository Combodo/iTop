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
use ormCaseLog;
use MetaModel;

class UpdateChildTicketLogTest extends ItopDataTestCase
{
	public function testUpdateChildTicketLog_PublicLogOnTwoChild(): void
	{
		//Given a parent ticket with two child ticket
		list($iParentTicket, $aChildrenTree) = $this->GivenUserRequests(2);
		$this->assertCount(2, $aChildrenTree[$iParentTicket], 'The test setup should create exactly two child tickets.');
		$sParentPublicLogEntry = 'This is a public log entry for the parent ticket.';
		$oParentTicket = MetaModel::GetObject('UserRequest', $iParentTicket);

		// When I enter a public_log entry for the parent ticket
		$oParentTicket->Set('public_log', $sParentPublicLogEntry);
		$oParentTicket->DBUpdate();

		// Then the log should be copied to all descendants and contain parent references recursively
		$this->AssertLogContainsAncestorReferencesRecursively($iParentTicket, $aChildrenTree[$iParentTicket], 'public_log', $sParentPublicLogEntry);
	}

	public function testUpdateChildTicketLog_PrivateAndPublicLog(): void
	{
		//Given a parent ticket with two child ticket
		list($iParentTicket, $aChildrenTree) = $this->GivenUserRequests(3);
		$sParentPublicLogEntry = 'This is a public log entry for the parent ticket.';
		$sParentPrivateLogEntry = 'This is a private log entry for the parent ticket.';

		// When I enter both a public_log and a private_log entry for the parent ticket
		$oParentTicket = MetaModel::GetObject('UserRequest', $iParentTicket);
		$oParentTicket->Set('public_log', $sParentPublicLogEntry);
		$oParentTicket->Set('private_log', $sParentPrivateLogEntry);
		$oParentTicket->DBUpdate();

		// Then both logs should be copied to all descendants and keep ancestor references recursively
		$this->AssertLogContainsAncestorReferencesRecursively($iParentTicket, $aChildrenTree[$iParentTicket], 'public_log', $sParentPublicLogEntry);
		$this->AssertLogContainsAncestorReferencesRecursively($iParentTicket, $aChildrenTree[$iParentTicket], 'private_log', $sParentPrivateLogEntry);
	}

	public function testUpdateChildTicketLog_PrivateLogOnThreeLevels(): void
	{
		//Given a parent ticket with two child ticket
		list($iParentTicket, $aChildrenTree) = $this->GivenUserRequests(1, 3);
		$sParentPrivateLogEntry = 'This is a private log entry for the parent ticket.';

		// When I enter both a public_log and a private_log entry for the parent ticket
		$oParentTicket = MetaModel::GetObject('UserRequest', $iParentTicket);
		$oParentTicket->Set('private_log', $sParentPrivateLogEntry);
		$oParentTicket->DBUpdate();

		// Then the private log should be copied on each level with parent + grand-parent references
		$this->AssertLogContainsAncestorReferencesRecursively($iParentTicket, $aChildrenTree[$iParentTicket], 'private_log', $sParentPrivateLogEntry);
	}

	private function GivenChildTicketsRecursive(int $iParentTicket, int $iCount, int $iRemainingLevels, int $iOrg, int &$iRefCounter): array
	{
		if ($iRemainingLevels <= 0) {
			return [];
		}

		$aChildren = [];
		for ($i = 1; $i <= $iCount; $i++) {
			$iRefCounter++;
			$sRef = sprintf('R-%05d', $iRefCounter);
			$iChildTicket = $this->GivenObjectInDB('UserRequest', [
				'title'             => "Child Ticket $sRef for Log Update Test",
				'description'       => "This is child ticket $sRef for testing log updates.",
				'org_id'            => $iOrg,
				'parent_request_id' => $iParentTicket,
				'ref'               => $sRef,
			]);

			if ($iRemainingLevels > 1) {
				$aChildren[$iChildTicket] = $this->GivenChildTicketsRecursive($iChildTicket, $iCount, $iRemainingLevels - 1, $iOrg, $iRefCounter);
			} else {
				$aChildren[] = $iChildTicket;
			}
		}

		return $aChildren;
	}

	private function AssertLogContainsAncestorReferencesRecursively(int $iParentTicket, array $aChildrenTree, string $sLogAttCode, string $sExpectedEntry, array $aAncestorRefs = []): void
	{
		$oParentTicket = MetaModel::GetObject('UserRequest', $iParentTicket);
		$sParentRef = $oParentTicket->Get('ref');
		$aRefsToFind = array_merge($aAncestorRefs, [$sParentRef]);

		foreach ($aChildrenTree as $iChildOrIndex => $aChildNodeOrId) {
			if (is_array($aChildNodeOrId)) {
				$iChildTicket = (int) $iChildOrIndex;
				$aGrandChildrenTree = $aChildNodeOrId;
			} else {
				$iChildTicket = (int) $aChildNodeOrId;
				$aGrandChildrenTree = [];
			}

			$oChildTicket = MetaModel::GetObject('UserRequest', $iChildTicket);
			$sLastLogEntry = $oChildTicket->Get($sLogAttCode)->GetLatestEntry();
			$this->assertNotEmpty($sLastLogEntry, "The $sLogAttCode entry was not copied to child ticket #$iChildTicket.");
			$this->assertStringContainsString($sExpectedEntry, $sLastLogEntry, "The $sLogAttCode entry on child ticket #$iChildTicket does not contain the original parent entry.");
			foreach ($aRefsToFind as $sExpectedRef) {
				$this->assertStringContainsString($sExpectedRef, $sLastLogEntry, "The $sLogAttCode entry on child ticket #$iChildTicket does not contain ancestor reference $sExpectedRef.");
			}

			if ($aGrandChildrenTree !== []) {
				$this->AssertLogContainsAncestorReferencesRecursively($iChildTicket, $aGrandChildrenTree, $sLogAttCode, $sExpectedEntry, $aRefsToFind);
			}
		}
	}
	/**
	 * @return array
	 * @throws \Exception
	 */
	private function GivenUserRequests(int $iCount, int $iLevel = 2): array
	{
		$iOrg = $this->GivenObjectInDB('Organization', [
			'name' => 'Test Organization for Log Update',
		]);
		// Given a parent ticket
		$iParentTicket = $this->GivenObjectInDB('UserRequest', [
			'title'       => 'Parent Ticket for Log Update Test',
			'description' => 'This is the parent ticket for testing log updates.',
			'org_id'      => $iOrg,
			'ref'         => 'R-00001',
		]);

		$iRemainingLevels = max(0, $iLevel - 1);
		$iRefCounter = 1;
		$aChildrenTree = [
			$iParentTicket => $this->GivenChildTicketsRecursive($iParentTicket, $iCount, $iRemainingLevels, $iOrg, $iRefCounter),
		];

		return [$iParentTicket, $aChildrenTree];
	}
}
