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

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use CoreCannotSaveObjectException;
use DBObject;
use MetaModel;
use UserRights;

/**
 * @group userRights
 */
class ContactEmailTest extends ItopDataTestCase
{
	public function testLinkedUserCanUpdateOwnContactEmailViaDbUpdate(): void
	{
		$this->CreateTestOrganization();
		$oContact = $this->CreatePerson(201);
		$this->CreateAndLoginContactEmailTestUser('contact-email-linked-', 3, $oContact);

		$oContact->Set('email', 'linked-user@example.com');
		$oContact->DBUpdate();

		$this->assertSame('linked-user@example.com', MetaModel::GetObject('Contact', $oContact->GetKey(), false, true)->Get('email'));
	}

	public function testAdministratorCanUpdateAnotherContactEmailViaDbUpdate(): void
	{
		$this->CreateTestOrganization();
		$oTargetContact = $this->CreatePerson(202);
		$this->CreateUser(uniqid('contact-email-admin-target-'), 3, 'A1b!Passw0rd', $oTargetContact->GetKey());
		$oAdministratorContact = $this->CreatePerson(203);
		$this->CreateAndLoginContactEmailTestUser('contact-email-admin-', 1, $oAdministratorContact);

		$oTargetContact->Set('email', 'administrator@example.com');
		$oTargetContact->DBUpdate();

		$this->assertSame('administrator@example.com', MetaModel::GetObject('Contact', $oTargetContact->GetKey(), false, true)->Get('email'));
	}

	public function testSuperUserCanUpdateAnotherContactEmailViaDbUpdate(): void
	{
		$this->CreateTestOrganization();
		$oTargetContact = $this->CreatePerson(204);
		$this->CreateUser(uniqid('contact-email-superuser-target-'), 3, 'A1b!Passw0rd', $oTargetContact->GetKey());
		$oSuperUserContact = $this->CreatePerson(205);
		$oSuperUserProfile = MetaModel::GetObjectFromOQL("SELECT URP_Profiles WHERE name = :name", ['name' => 'SuperUser'], true);
		$this->assertNotNull($oSuperUserProfile, 'The SuperUser profile must be available for this test');
		$this->CreateAndLoginContactEmailTestUser('contact-email-superuser-', $oSuperUserProfile->GetKey(), $oSuperUserContact);

		$oTargetContact->Set('email', 'superuser@example.com');
		$oTargetContact->DBUpdate();

		$this->assertSame('superuser@example.com', MetaModel::GetObject('Contact', $oTargetContact->GetKey(), false, true)->Get('email'));
	}

	public function testConfigurationManagerCannotUpdateAnotherUsersContactEmail(): void
	{
		$this->CreateTestOrganization();
		$oTargetContact = $this->CreatePerson(206);
		$this->CreateUser(uniqid('contact-email-target-'), 3, 'A1b!Passw0rd', $oTargetContact->GetKey());
		$oConfigurationManagerContact = $this->CreatePerson(207);
		$this->CreateAndLoginContactEmailTestUser('contact-email-config-manager-', 3, $oConfigurationManagerContact);

		try {
			(new \CoreServices())->ExecOperation('1.3', 'core/update', (object) [
				'comment' => 'Update another user contact email',
				'class' => 'Person',
				'key' => $oTargetContact->GetKey(),
				'fields' => (object) ['email' => 'forbidden@example.com'],
				'output_fields' => 'email',
			]);
			$this->fail('A configuration manager must not change the email of a contact linked to another user');
		} catch (CoreCannotSaveObjectException $e) {
			$this->assertNotEmpty($e->getIssues());
		}

		$this->assertSame('', MetaModel::GetObject('Contact', $oTargetContact->GetKey(), false, true)->Get('email'));
	}

	public function testConfigurationManagerCanUpdateContactEmailWhenNoUserIsLinked(): void
	{
		$this->CreateTestOrganization();
		$oTargetContact = $this->CreatePerson(208);
		$oConfigurationManagerContact = $this->CreatePerson(209);
		$this->CreateAndLoginContactEmailTestUser('contact-email-config-manager-unlinked-', 3, $oConfigurationManagerContact);

		$oResult = (new \CoreServices())->ExecOperation('1.3', 'core/update', (object) [
			'comment' => 'Update contact email without linked user',
			'class' => 'Person',
			'key' => $oTargetContact->GetKey(),
			'fields' => (object) ['email' => 'unlinked-contact@example.com'],
			'output_fields' => 'email',
		]);

		$this->assertSame(\RestResult::OK, $oResult->code);
		$this->assertSame('unlinked-contact@example.com', MetaModel::GetObject('Contact', $oTargetContact->GetKey(), false, true)->Get('email'));
	}

	public function testConfigurationManagerCanUpdateAnotherContactWhenRestRequestIncludesUnchangedEmail(): void
	{
		$this->CreateTestOrganization();
		$oTargetContact = $this->CreatePerson(210);
		$this->CreateUser(uniqid('contact-rest-target-'), 3, 'A1b!Passw0rd', $oTargetContact->GetKey());
		$oTargetContact->Set('email', 'existing@example.com');
		$oTargetContact->DBUpdate();
		$oConfigurationManagerContact = $this->CreatePerson(211);
		$this->CreateAndLoginContactEmailTestUser('contact-rest-config-manager-', 3, $oConfigurationManagerContact);

		$oResult = (new \CoreServices())->ExecOperation('1.3', 'core/update', (object) [
			'comment' => 'Update contact phone',
			'class' => 'Person',
			'key' => $oTargetContact->GetKey(),
			'fields' => (object) [
				'email' => 'existing@example.com',
				'phone' => '555-0100',
			],
			'output_fields' => 'email, phone',
		]);

		$this->assertSame(\RestResult::OK, $oResult->code);
		$oUpdatedContact = MetaModel::GetObject('Person', $oTargetContact->GetKey(), false, true);
		$this->assertSame('existing@example.com', $oUpdatedContact->Get('email'));
		$this->assertSame('555-0100', $oUpdatedContact->Get('phone'));
	}

	public function testConfigurationManagerCanCreatePersonContact(): void
	{
		$this->CreateTestOrganization();
		$oConfigurationManagerContact = $this->CreatePerson(212);
		$this->CreateAndLoginContactEmailTestUser('contact-create-config-manager-', 3, $oConfigurationManagerContact);

		$oCreatedContact = $this->CreatePerson(213);

		$this->assertGreaterThan(0, $oCreatedContact->GetKey());
		$this->assertNotNull(MetaModel::GetObject('Person', $oCreatedContact->GetKey(), false, true));
	}

	public function testContactlessConfigurationManagerCanCreatePersonAndTeamWithEmptyEmail(): void
	{
		$oOrganization = $this->CreateTestOrganization();
		$oUser = $this->CreateContactlessUser(uniqid('contactless-config-manager-'), 3, 'A1b!Passw0rd');
		$_SESSION = [];
		UserRights::Login($oUser->Get('login'));
		$this->assertNull(UserRights::GetContactObject());

		$oPerson = $this->createObject('Person', [
			'name' => 'Person_without_email',
			'first_name' => 'Test',
			'org_id' => $oOrganization->GetKey(),
		]);
		$oTeam = $this->createObject('Team', [
			'name' => 'Team_without_email',
			'org_id' => $oOrganization->GetKey(),
		]);

		$this->assertGreaterThan(0, $oPerson->GetKey());
		$this->assertGreaterThan(0, $oTeam->GetKey());
		$this->assertSame('', MetaModel::GetObject('Person', $oPerson->GetKey(), false, true)->Get('email'));
		$this->assertSame('', MetaModel::GetObject('Team', $oTeam->GetKey(), false, true)->Get('email'));
	}

	private function CreateAndLoginContactEmailTestUser(string $sLoginPrefix, int $iProfileId, DBObject $oContact): void
	{
		$_SESSION = [];
		$oUser = $this->CreateUser(uniqid($sLoginPrefix), $iProfileId, 'A1b!Passw0rd', $oContact->GetKey());
		UserRights::Login($oUser->Get('login'));
	}
}
