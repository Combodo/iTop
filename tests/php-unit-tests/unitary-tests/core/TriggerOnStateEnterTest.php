<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;
use Person;

class TriggerOnStateEnterTest extends ItopDataTestCase
{
	const CREATE_TEST_ORG = true;

	protected function setUp(): void
	{
		parent::setUp();
		$this->RemoveAllObjects(\Trigger::class);
		$this->RemoveAllObjects(\EventNotificationEmail::class);
	}

	public function testIsTriggeredOnTransition()
	{
		$iTrigger = $this->GivenTriggerWithAction('TriggerOnStateEnter', 'assigned');
		$oUserRequest = $this->GivenUserRequest('new');

		$oUserRequest->ApplyStimulus('ev_assign');
		$this->AssertTriggerExecuted($iTrigger, 1, 'The trigger should have been executed');

		$oUserRequest->ApplyStimulus('ev_assign');
		$this->AssertTriggerExecuted($iTrigger, 1, 'The trigger should not be executed when stimulus not expected in current state');
	}

	public function testIsTriggeredOnTransitionStayingInSameState()
	{
		$iTrigger = $this->GivenTriggerWithAction('TriggerOnStateEnter', 'assigned');
		$oUserRequest = $this->GivenUserRequest('new');
		$oUserRequest->ApplyStimulus('ev_assign');

		$bTransitioned = $oUserRequest->ApplyStimulus('ev_reassign');
		$this->assertTrue($bTransitioned, 'The stimulus should have been accepted');

		$this->AssertTriggerExecuted($iTrigger, 2, 'The trigger should have been executed twice');
	}
	public function testIsTriggeredOnNewObject()
	{
		$iTrigger = $this->GivenTriggerWithAction('TriggerOnStateEnter', 'new');
		$oUserRequest = $this->GivenUserRequest('new');
		$this->AssertTriggerExecuted($iTrigger, 0, 'The trigger TriggerOnStateEnter should not be executed on created object');
	}

	private function GivenTriggerWithAction(string $sTriggerClass, string $sState)
	{
		$iTrigger = $this->GivenObjectInDB($sTriggerClass, [
			'description' => 'Description',
			'target_class' => 'UserRequest',
			'state' => $sState,
		]);
		$this->GivenObjectInDB('ActionEmail', [
			'from' => 'test@combodo.com',
			'subject' => 'Subject',
			'body' => 'Body',
			'description' => 'Description',
			'test_recipient' => 'test@combodo.com',
			'name' => 'UserRequest',
			'asynchronous' => 'yes',
			'trigger_list' => [
				"trigger_id:$iTrigger",
			],
		]);
		return $iTrigger;
	}

	private function AssertTriggerExecuted(int $iTrigger, $iCount, $sMessage = '')
	{
		$oSearch = new \DBObjectSearch('EventNotificationEmail');
		$oSearch->AddCondition('trigger_id', $iTrigger);
		$oSet = new \DBObjectSet($oSearch);
		$this->assertEquals($iCount, $oSet->Count(), $sMessage);
	}

	public function GivenUserRequest(string $sStatus): ?\DBObject
	{
		$iUserRequest = $this->GivenObjectInDB('UserRequest', [
			'title'       => 'Title',
			'description' => 'Description',
			'status'      => $sStatus,
		]);
		return MetaModel::GetObject('UserRequest', $iUserRequest);
	}
}