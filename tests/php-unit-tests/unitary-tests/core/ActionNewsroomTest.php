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

use ArchivedObjectException;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use CoreException;
use CoreUnexpectedValue;
use DBObjectSearch;
use DBObjectSet;
use Exception;
use MetaModel;
use MissingQueryArgument;
use MySQLException;
use MySQLHasGoneAwayException;


class ActionNewsroomTest extends ItopDataTestCase
{
	const CREATE_TEST_ORG = true;
	private int $iTrigger;
	private int $iRecipientId;

	protected function setUp(): void
	{
		parent::setUp();
		$this->iTrigger = $this->GivenObjectInDB('TriggerOnObjectCreate',
			array(
				'description' => '[TEST] TriggerOnObjectCreate',
				'target_class' => 'UserRequest',
			)
		);
		$this->iRecipientId = $this->GivenPersonInDB(1);
	}

	private function ActionNewsroomProvider(): array
	{
		return [
			'With Sync ActionNewsroom' => ['bIsAsynchronous' => false],
			'With Async ActionNewsroom' => ['bIsAsynchronous' => true]
		];
	}

	/**
	 * @throws CoreException
	 * @throws MissingQueryArgument
	 * @throws CoreUnexpectedValue
	 * @throws ArchivedObjectException
	 * @throws MySQLException
	 * @throws MySQLHasGoneAwayException
	 * @throws Exception
	 */
	public function testActionNewsroomRecordsEvent()
	{
		$iActionNewsroomId = $this->GivenActionNewsroomInDB(false, 'Body of the notification');

		$this->CreateUserRequest(1,
			[
				'title'       => '[TEST] ActionNewsroom',
				'org_id'      => $this->getTestOrgId(),
				'caller_id'   => $this->iRecipientId,
				'description' => 'PHPUnit Test',
			]
		);

		$this->AssertUniqueObjectInDB(
			'EventNotificationNewsroom',
			[
				'action_id' => $iActionNewsroomId,
				'message' => 'Body of the notification',
				'title' => 'Title'
			]
		);
	}

	/**
	 * @dataProvider ActionNewsroomProvider
	 * @return void
	 * @throws Exception
	 */
	public function testActionNewsroomRecordsSpecificEventIfAMandatoryFieldIsEmpty(bool $bIsAsynchronous)
	{
		$iActionNewsroomId = $this->GivenActionNewsroomInDB($bIsAsynchronous, '$this->service_name$');

		$this->CreateUserRequest(1,
			[
				'title'       => '[TEST] ActionNewsroom',
				'org_id'      => $this->getTestOrgId(),
				'caller_id'   => $this->iRecipientId,
				'description' => 'PHPUnit Test',
				'service_id' => 0
			]
		);

		$this->AssertUniqueObjectInDB(
			'EventNotificationNewsroom',
			[
				'action_id' => $iActionNewsroomId,
				'title' => 'Notification not sent',
				'message' => 'An error occurred while saving the notification'
			]
		);
	}

	/**
	 * @throws Exception
	 */
	private function GivenActionNewsroomInDB(bool $bIsAsynchronous, string $sMessage): int
	{
		$this->GivenObjectInDB('UserLocal', [
			'login' => 'demo_test_'.uniqid(__CLASS__, true),
			'password' => 'admin_123',
			'language' => 'EN US',
			'profile_list' => ['profileid:'.self::$aURP_Profiles['Administrator']],
			'contactid' => $this->iRecipientId
		]);

		return $this->GivenObjectInDB('ActionNewsroom', [
			'name' => 'ActionNewsroom TriggerOnObjectCreate',
			'status' => 'test',
			'test_recipient_id' => $this->iRecipientId,
			'title' => 'Title',
			'message' => $sMessage,
			'recipients' => "SELECT Person WHERE id = $this->iRecipientId",
			'asynchronous' => $bIsAsynchronous ? 'yes' : 'no',
			'trigger_list' => [
				"trigger_id:$this->iTrigger"
			],
		]);
	}

	/**
	 * @throws Exception
	 */
	private function GivenService(string $sName): int
	{
		return $this->GivenObjectInDB('Service', [
				'name' => $sName,
				'org_id' => $this->getTestOrgId()
			]
		);
	}
}