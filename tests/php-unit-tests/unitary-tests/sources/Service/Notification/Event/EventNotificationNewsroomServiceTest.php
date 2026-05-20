<?php

namespace Combodo\iTop\Test\UnitTest\Service\Notification\Event;

use Action;
use ActionNewsroom;
use Combodo\iTop\Application\WebPage\CaptureWebPage;
use Combodo\iTop\Service\Notification\Event\EventNotificationNewsroomService;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Contact;
use EventNotificationNewsroom;
use Person;
use Ticket;
use Trigger;
use TriggerOnObjectMention;
use UserRequest;

class EventNotificationNewsroomServiceTest extends ItopDataTestCase
{
	public const CREATE_TEST_ORG = true;

	private Contact $oContact;
	private Trigger $oTrigger;
	private Action $oAction;
	private Ticket $oTicket;
	private EventNotificationNewsroom $oEvent;

	protected function setUp(): void
	{
		parent::setUp();

		/** @var Contact $oContact */
		$oContact = $this->createObject(Person::class, [
			'name' => 'Khalo',
			'first_name' => 'Frida',
			'org_id' => $this->getTestOrgId(),
		]);
		$this->oContact = $oContact;

		/** @var Trigger $oTrigger */
		$oTrigger = $this->createObject(TriggerOnObjectMention::class, [
			'description' => 'Person mentioned on Ticket',
			'target_class' => 'Ticket',
		]);
		$this->oTrigger = $oTrigger;

		/** @var Action $oAction */
		$oAction = $this->createObject(ActionNewsroom::class, [
			'name' => 'Notification to persons mentioned in logs',
			'status' => 'enabled',
			'title' => '$this->friendlyname$',
			'message' => 'You have been mentioned by $current_contact->friendlyname$',
			'recipients' => 'SELECT Person WHERE id = :mentioned->id',
		]);
		$this->oAction = $oAction;

		/** @var Ticket $oTicket */
		$oTicket = $this->createObject(UserRequest::class, [
			'org_id' => $this->getTestOrgId(),
			'title' => 'Houston, got a problem',
			'description' => 'Test description',
		]);
		$this->oTicket = $oTicket;
	}

	protected function tearDown(): void
	{
		parent::tearDown();

		$this->oEvent->DBDelete();
	}

	public function testDownloadIsTriggeredWhenDownloaderIsNotificationRecipient(): void
	{
		$this->oEvent = EventNotificationNewsroomService::MakeEventFromAction(
			oAction: $this->oAction,
			iContactId: $this->oContact->GetKey(),
			iTriggerId: $this->oTrigger->GetKey(),
			sMessage: 'Test message',
			sTitle: 'Test event',
			sUrl: 'https://localhost/itop/pages/UI.php?operation=details&class=UserRequest&id=1',
			iObjectId: $this->oTicket->GetKey(),
			sObjectClass: UserRequest::class,
		);
		$this->oEvent->DBInsert();

		$oPage = new CaptureWebPage();
		$bDownloadIcon = EventNotificationNewsroomService::DownloadIcon($oPage, $this->oEvent->GetKey(), $this->oContact->GetKey());
		$sHtml = $oPage->GetHtml();

		$this->assertTrue($bDownloadIcon);
		$this->assertNotEquals('', $sHtml);
	}

	public function testDownloadIsNotTriggeredWhenDownloaderIsNotNotificationRecipient(): void
	{
		$oContact = $this->createObject(Person::class, [
			'name' => 'Doe',
			'first_name' => 'John',
			'org_id' => $this->getTestOrgId(),
		]);
		$this->oEvent = EventNotificationNewsroomService::MakeEventFromAction(
			oAction: $this->oAction,
			iContactId: $oContact->GetKey(),
			iTriggerId: $this->oTrigger->GetKey(),
			sMessage: 'Test message',
			sTitle: 'Test event',
			sUrl: 'https://localhost/itop/pages/UI.php?operation=details&class=UserRequest&id=1',
			iObjectId: $this->oTicket->GetKey(),
			sObjectClass: UserRequest::class,
		);
		$this->oEvent->DBInsert();

		$oPage = new CaptureWebPage();
		$bDownloadIcon = EventNotificationNewsroomService::DownloadIcon($oPage, $this->oEvent->GetKey(), $this->oContact->GetKey());
		$sHtml = $oPage->GetHtml();

		$this->assertFalse($bDownloadIcon);
		$this->assertEquals('', $sHtml);
	}
}
