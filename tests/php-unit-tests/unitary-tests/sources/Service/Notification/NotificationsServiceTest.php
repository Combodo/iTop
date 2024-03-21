<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Test\UnitTest\Service\Notification;


use Combodo\iTop\Core\Trigger\Enum\SubscriptionPolicy;
use Combodo\iTop\Service\Notification\NotificationsRepository;
use Combodo\iTop\Service\Notification\NotificationsService;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

/**
 * Class NotificationsServiceTest
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Test\UnitTest\Service\Notification
 * @covers \Combodo\iTop\Service\Notification\NotificationsService
 */
class NotificationsServiceTest extends ItopDataTestCase {
	public const CREATE_TEST_ORG = true;

	/**
	 * @covers \Combodo\iTop\Service\Notification\NotificationsService::IsSubscribed
	 * @return void
	 */
	public function testIsSubscribed(): void
	{
		// Prepare base data
		/** @var \Contact $oPerson */
		$oPerson = $this->createObject(\Person::class, [
			"name" => "Khalo",
			"first_name" => "Frida",
			"org_id" => $this->getTestOrgId(),
		]);
		$iPersonID = $oPerson->GetKey();
		/** @var \Location $oLocation */
		$oLocation = $this->createObject(\Location::class, [
			"name" => "Test location",
			"org_id" => $this->getTestOrgId(),
		]);
		/** @var \Trigger $oTrigger */
		$oTrigger = $this->createObject(\TriggerOnObjectCreate::class, [
			"description" => "Test trigger",
			"subscription_policy" => SubscriptionPolicy::AllowNoChannel->value,
		]);
		$iTriggerID = $oTrigger->GetKey();
		/** @var \ActionNotification $oActionNotification */
		$oActionNotification = $this->createObject(\ActionEmail::class, [
			"name" => "Test action",
			"from" => "test@test.com",
			"to" => "SELECT Person WHERE id = $iPersonID",
			"subject" => "Test subject",
			"body" => "Test body",
		]);
		$iActionNotificationID = $oActionNotification->GetKey();


		$oService = NotificationsService::GetInstance();

		// Case 1: Person hasn't received action so far, so it is implicitly subscribed by default
		// - Assert
		$this->assertTrue($oService->IsSubscribed($oTrigger, $oActionNotification, $oPerson));


		// Case 2: Activate an action, the person should have an explicit subscription
		// - Prepare
		$oActionNotification->DoExecute($oTrigger, [
			"this->object()" => $oLocation,
			"trigger->object()" => $oTrigger,
		]);

		// - Assert
		$iSubscribedCount = NotificationsRepository::GetInstance()->SearchSubscribedSubscriptionsByTriggerContactAndAction($iTriggerID, $iActionNotificationID, $iPersonID)->Count();
		$this->assertEquals(1, $iSubscribedCount, "There should be 1 explicit subscription");
		$this->assertTrue($oService->IsSubscribed($oTrigger, $oActionNotification, $oPerson));


		// Case 3: Unsubscribe, person should have an explicit unsubscribe
		// - Prepare
		$oSubscription = NotificationsRepository::GetInstance()->SearchSubscribedSubscriptionsByTriggerContactAndAction($iTriggerID, $iActionNotificationID, $iPersonID)->Fetch();
		$oSubscription->Set('subscribed', false);
		$oSubscription->DBUpdate();

		// - Assert
		$iSubscribedCount = NotificationsRepository::GetInstance()->SearchSubscribedSubscriptionsByTriggerContactAndAction($iTriggerID, $iActionNotificationID, $iPersonID)->Count();
		$this->assertEquals(0, $iSubscribedCount, "There should be 0 explicit subscription");
		$iUnsubscribedCount = NotificationsRepository::GetInstance()->SearchUnsubscribedSubscriptionsByTriggerContactAndAction($iTriggerID, $iActionNotificationID, $iPersonID)->Count();
		$this->assertEquals(1, $iUnsubscribedCount, "There should be 1 explicit unsubscription");
		$this->assertFalse($oService->IsSubscribed($oTrigger, $oActionNotification, $oPerson));
	}
}