<?php

namespace Combodo\iTop\Test\UnitTest\Service;

use Combodo\iTop\Service\iEventServiceCallable;
use Combodo\iTop\Service\iEventServiceCallableStatic;

use Combodo\iTop\Service\EventService;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use CoreException;

/**
 * Class EventServiceTest
 *
 * @package Combodo\iTop\Test\UnitTest\Service
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class EventServiceTest extends ItopTestCase
{
	const USE_TRANSACTION = false;
	const CREATE_TEST_ORG = false;

	private static $iEventCalls;

	protected function setUp()
	{
		parent::setUp();
		self::$iEventCalls = 0;
	}

	public function testRegisterBadCallbackClass()
	{
		$this->expectException(CoreException::class);
		EventService::Register('event1', $this);
	}

	public function testRegisterBadCallbackStaticClass()
	{
		$this->expectException(CoreException::class);

		EventService::Register('event1', 'Combodo\iTop\Test\UnitTest\Service\TestEventServiceNotCallable');
	}

	public function testStatic()
	{
		$sId = EventService::Register('event1', 'Combodo\iTop\Test\UnitTest\Service\TestEventServiceCallableStatic');
		$this->debug("Registered $sId");
		EventService::Trigger('event1');
		$this->assertEquals(1, self::$iEventCalls);
		EventService::Trigger('event1');
		$this->assertEquals(2, self::$iEventCalls);
	}

	public function testObject()
	{
		$oObj = new TestEventServiceCallable();
		$sId = EventService::Register('event1', $oObj);
		$this->debug("Registered $sId");
		EventService::Trigger('event1');
		$this->assertEquals(1, self::$iEventCalls);
		EventService::Trigger('event1');
		$this->assertEquals(2, self::$iEventCalls);
	}

	public function testMultiEvent()
	{
		EventService::Register('event1', 'Combodo\iTop\Test\UnitTest\Service\TestEventServiceCallableStatic');
		$oObj = new TestEventServiceCallable();
		EventService::Register('event1', $oObj);

		EventService::Register('event2', 'Combodo\iTop\Test\UnitTest\Service\TestEventServiceCallableStatic');
		EventService::Register('event2', $oObj);

		EventService::Trigger('event1');
		$this->assertEquals(2, self::$iEventCalls);
		EventService::Trigger('event2');
		$this->assertEquals(4, self::$iEventCalls);
	}

	public function testMultiSameEvent()
	{
		$oObj = new TestEventServiceCallable();
		$sId = EventService::Register('event1', $oObj);
		$this->debug("Registered $sId");
		$sId = EventService::Register('event1', $oObj);
		$this->debug("Registered $sId");
		$sId = EventService::Register('event1', $oObj);
		$this->debug("Registered $sId");
		$sId = EventService::Register('event1', $oObj);
		$this->debug("Registered $sId");

		EventService::Trigger('event1');
		$this->assertEquals(4, self::$iEventCalls);
	}

	public function testData()
	{
		EventService::Register('event1', 'Combodo\iTop\Test\UnitTest\Service\TestEventServiceCallableStatic', 'User Data Static');
		$oObj = new TestEventServiceCallable();
		EventService::Register('event1', $oObj, $oObj);
		EventService::Trigger('event1', 'Event Data 1');
		$this->assertEquals(2, self::$iEventCalls);
		EventService::Trigger('event1', array('text' => 'Event Data 2'));
		$this->assertEquals(4, self::$iEventCalls);
	}

	public function testPriority()
	{
		EventService::Register('event1', 'Combodo\iTop\Test\UnitTest\Service\TestEventServiceCallableStatic', null, '',0);
		$oObj = new TestEventServiceCallable();
		EventService::Register('event1', $oObj, null, '',1);

		EventService::Register('event2', 'Combodo\iTop\Test\UnitTest\Service\TestEventServiceCallableStatic', null, '',1);
		EventService::Register('event2', $oObj, null, '',0);

		EventService::Trigger('event1');
		$this->assertEquals(2, self::$iEventCalls);
		EventService::Trigger('event2');
		$this->assertEquals(4, self::$iEventCalls);
	}



	public static function IncrementCallCount()
	{
		self::$iEventCalls++;
	}

	/**
	 * static version of the debug to be accessible from other objects
	 *
	 * @param $sMsg
	 */
	public static function DebugExt($sMsg)
	{
		if (DEBUG_UNIT_TEST)
		{
			if (is_string($sMsg))
			{
				echo "$sMsg\n";
			}
			else
			{
				print_r($sMsg);
			}
		}
	}
}

///////////////////////////////////////
/// Classes used for the tests
///////////////////////////////////////

class TestEventServiceNotCallable
{

}

require_once '../sources/application/service/EventService.php';
class TestEventServiceCallableStatic implements iEventServiceCallableStatic
{

	public static function OnEvent($sEvent, $mEventData = null, $mUserData = null)
	{
		echo "Event $sEvent called for TestEventServiceCallableStatic\n";
		EventServiceTest::IncrementCallCount();
		EventServiceTest::DebugExt($mEventData);
		EventServiceTest::DebugExt($mUserData);
	}
}

class TestEventServiceCallable implements iEventServiceCallable
{

	public function OnEvent($sEvent, $mEventData = null, $mUserData = null)
	{
		echo "Event $sEvent called for TestEventServiceCallable\n";
		EventServiceTest::IncrementCallCount();
		EventServiceTest::DebugExt($mEventData);
		EventServiceTest::DebugExt($mUserData);
	}
}
