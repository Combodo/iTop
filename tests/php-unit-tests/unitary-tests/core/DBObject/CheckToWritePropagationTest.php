<?php
/**
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core\DBObject;

use Combodo\iTop\Service\Events\EventData;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use URP_UserProfile;
use UserLocal;

class CheckToWritePropagationTest extends ItopDataTestCase
{
	private static array $aEventCalls;

	public function testCascadeCheckToWrite()
	{
		$sLogin = 'testCascadeCheckToWrite-'.uniqid('', true);
		$oUser1 = $this->CreateUser($sLogin, self::$aURP_Profiles['Administrator'], 'ABCD1234@gabuzomeu');
		$sUserId1 = $oUser1->GetKey();
		$sLogin = 'testCascadeCheckToWrite-'.uniqid('', true);
		$oUser2 = $this->CreateUser($sLogin, self::$aURP_Profiles['Administrator'], 'ABCD1234@gabuzomeu');
		$sUserId2 = $oUser2->GetKey();

		$this->EventService_RegisterListener(EVENT_DB_CHECK_TO_WRITE, [$this, 'CheckToWriteEventListener'], 'User');
		$sEventKeyUser1 = $this->GetEventKey(EVENT_DB_CHECK_TO_WRITE, UserLocal::class, $sUserId1);
		$sEventKeyUser2 = $this->GetEventKey(EVENT_DB_CHECK_TO_WRITE, UserLocal::class, $sUserId2);

		// Add URP_UserProfile
		self::$aEventCalls = [];
		$oURPUserProfile = new URP_UserProfile();
		$oURPUserProfile->Set('profileid', self::$aURP_Profiles['Support Agent']);
		$oURPUserProfile->Set('userid', $sUserId1);
		$oURPUserProfile->Set('reason', 'UNIT Tests');
		$oURPUserProfile->DBInsert();
		$this->assertArrayHasKey($sEventKeyUser1, self::$aEventCalls, 'User checkToWrite should be called when a URP_UserProfile is created');

		// Update URP_UserProfile (change profile)
		self::$aEventCalls = [];
		$oURPUserProfile->Set('profileid', self::$aURP_Profiles['Problem Manager']);
		$oURPUserProfile->DBUpdate();
		$this->assertArrayHasKey($sEventKeyUser1, self::$aEventCalls, 'User checkToWrite should be called when a URP_UserProfile is updated');

		// Update URP_UserProfile (move from User1 to User2)
		self::$aEventCalls = [];
		$oURPUserProfile->Set('userid', $sUserId2);
		$oURPUserProfile->DBUpdate();
		$this->assertCount(2, self::$aEventCalls, 'Previous User and new User checkToWrite should be called when a URP_UserProfile is moved from a User to another');
		$this->assertArrayHasKey($sEventKeyUser1, self::$aEventCalls, 'Previous User checkToWrite should be called when a URP_UserProfile is moved from a User to another');
		$this->assertArrayHasKey($sEventKeyUser2, self::$aEventCalls, 'New User checkToWrite should be called when a URP_UserProfile is moved from a User to another');

		// Delete URP_UserProfile from User2
		self::$aEventCalls = [];
		$oURPUserProfile->DBDelete();
		$this->assertArrayHasKey($sEventKeyUser2, self::$aEventCalls, 'User checkToWrite should be called when a URP_UserProfile is deleted');

		$oUser1->DBDelete();
		$oUser2->DBDelete();
	}

	public function CheckToWriteEventListener(EventData $oEventData)
	{
		$oObject = $oEventData->GetEventData()['object'];
		$sEvent = $oEventData->GetEvent();
		$sClass = get_class($oObject);
		$sId = $oObject->GetKey();
		self::$aEventCalls[$this->GetEventKey($sEvent, $sClass, $sId)] = true;
	}

	private function GetEventKey($sEvent, $sClass, $sId)
	{
		return "event: $sEvent, class: $sClass, id: $sId";
	}

}
