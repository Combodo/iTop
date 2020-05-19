<?php

namespace Combodo\iTop\Test\UnitTest\Service;

use Combodo\iTop\Service\Event;
use Combodo\iTop\Service\EventData;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use TypeError;

/**
 * Class EventTest
 *
 * @package Combodo\iTop\Test\UnitTest\Service
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class EventTest extends ItopTestCase
{
	const USE_TRANSACTION = false;
	const CREATE_TEST_ORG = false;

	private static $iEventCalls;

	protected function setUp()
	{
		parent::setUp();
		self::$iEventCalls = 0;
	}

	/**
	 * @dataProvider BadCallbackProvider
	 * @throws \CoreException
	 */
	public function testRegisterBadCallback($callback)
	{
		$this->expectException(TypeError::class);
		Event::Register('event', $callback);
	}

	public function BadCallbackProvider()
	{
		return array(
			array('toto'),
			array('EventTest::toto'),
			array(array('EventTest', 'toto')),
			array(array($this, 'toto')),
		);
	}

	public function testNoParameterCallbackFunction()
	{
		$sId = Event::Register('event', function () { $this->debug("Closure: event received !!!"); self::IncrementCallCount(); });
		$this->debug("Registered $sId");
		Event::FireEvent('event');
		$this->assertEquals(1, self::$iEventCalls);
	}

	/**
	 * @dataProvider GoodCallbackProvider
	 * @param $callback
	 *
	 * @throws \CoreException
	 */
	public function testMethodCallbackFunction(callable $callback)
	{
		$sId = Event::Register('event', $callback);
		$this->debug("Registered $sId");
		Event::FireEvent('event');
		$this->assertEquals(1, self::$iEventCalls);
		Event::FireEvent('event');
		$this->assertEquals(2, self::$iEventCalls);
	}

	public function GoodCallbackProvider()
	{
		$oReceiver = new TestEventReceiver();
		return array(
			'method' => array(array($oReceiver, 'OnEvent1')),
			'static' => array('Combodo\iTop\Test\UnitTest\Service\TestEventReceiver::OnStaticEvent1'),
			'static2' => array(array('Combodo\iTop\Test\UnitTest\Service\TestEventReceiver', 'OnStaticEvent1')),
		);
	}

	public function testBrokenCallback()
	{
		$oReceiver = new TestEventReceiver();
		Event::Register('event_a', array($oReceiver, 'BrokenCallback'));

		$this->expectException(TypeError::class);
		Event::FireEvent('event_a');
	}

	public function testRemovedCallback()
	{
		$oReceiver = new TestEventReceiver();
		Event::Register('event_a', array($oReceiver, 'OnEvent1'));

		$oReceiver = null;
		gc_collect_cycles();

		Event::FireEvent('event_a');
		$this->assertEquals(1, self::$iEventCalls);
	}

	public function testMultiEvent()
	{
		$oReceiver = new TestEventReceiver();
		Event::Register('event_a', array($oReceiver, 'OnEvent1'));
		Event::Register('event_a', array($oReceiver, 'OnEvent2'));
		Event::Register('event_a', array('Combodo\iTop\Test\UnitTest\Service\TestEventReceiver', 'OnStaticEvent1'));
		Event::Register('event_a', 'Combodo\iTop\Test\UnitTest\Service\TestEventReceiver::OnStaticEvent2');

		Event::Register('event_b', array($oReceiver, 'OnEvent1'));
		Event::Register('event_b', array($oReceiver, 'OnEvent2'));
		Event::Register('event_b', array('Combodo\iTop\Test\UnitTest\Service\TestEventReceiver', 'OnStaticEvent1'));
		Event::Register('event_b', 'Combodo\iTop\Test\UnitTest\Service\TestEventReceiver::OnStaticEvent2');

		Event::FireEvent('event_a');
		$this->assertEquals(4, self::$iEventCalls);
		Event::FireEvent('event_b');
		$this->assertEquals(8, self::$iEventCalls);
	}

	public function testMultiSameEvent()
	{
		$oReceiver = new TestEventReceiver();
		$sId = Event::Register('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = Event::Register('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = Event::Register('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = Event::Register('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");

		Event::FireEvent('event1');
		$this->assertEquals(4, self::$iEventCalls);
	}

	public function testData()
	{
		$oReceiver = new TestEventReceiver();
		Event::Register('event1', array($oReceiver, 'OnEventWithData'), '', 'User Data Static');
		Event::Register('event1', array($oReceiver, 'OnEventWithData'), '', $oReceiver);
		Event::FireEvent('event1', '', 'Event Data 1');
		$this->assertEquals(2, self::$iEventCalls);
		Event::FireEvent('event1', '', array('text' => 'Event Data 2'));
		$this->assertEquals(4, self::$iEventCalls);
	}

	public function testPriority()
	{
		$oReceiver = new TestEventReceiver();
		Event::Register('event1', array($oReceiver, 'OnEvent1'), '', null, 0);
		Event::Register('event1', array($oReceiver, 'OnEvent2'), '', null, 1);

		Event::Register('event2', array($oReceiver, 'OnEvent1'), '', null, 1);
		Event::Register('event2', array($oReceiver, 'OnEvent2'), '', null, 0);

		Event::FireEvent('event1');
		$this->assertEquals(2, self::$iEventCalls);
		Event::FireEvent('event2');
		$this->assertEquals(4, self::$iEventCalls);
	}


	public function testEventSource()
	{
		$oReceiver = new TestEventReceiver();
		Event::Register('event1', array($oReceiver, 'OnEvent1'), 'A', null, 0);
		Event::Register('event1', array($oReceiver, 'OnEvent2'), 'A', null, 1);
		Event::Register('event1', 'Combodo\iTop\Test\UnitTest\Service\TestEventReceiver::OnStaticEvent1', '', null, 2);

		Event::Register('event2', array($oReceiver, 'OnEvent1'), 'A', null, 1);
		Event::Register('event2', 'Combodo\iTop\Test\UnitTest\Service\TestEventReceiver::OnStaticEvent1', '', null, 2);
		Event::Register('event2', array($oReceiver, 'OnEvent2'), 'B', null, 0);

		Event::FireEvent('event1', 'A');
		$this->assertEquals(3, self::$iEventCalls);
		Event::FireEvent('event2', 'A');
		$this->assertEquals(5, self::$iEventCalls);
	}


	public function testUnRegisterEvent()
	{
		$oReceiver = new TestEventReceiver();
		$sId = Event::Register('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = Event::Register('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = Event::Register('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = Event::Register('event2', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");

		Event::FireEvent('event1');
		$this->assertEquals(3, self::$iEventCalls);

		Event::FireEvent('event2');
		$this->assertEquals(4, self::$iEventCalls);

		Event::UnRegisterEvent('event1');

		Event::FireEvent('event1');
		$this->assertEquals(4, self::$iEventCalls);

		Event::FireEvent('event2');
		$this->assertEquals(5, self::$iEventCalls);
	}

	public function testUnRegisterAll()
	{
		$oReceiver = new TestEventReceiver();
		$sId = Event::Register('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = Event::Register('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = Event::Register('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = Event::Register('event2', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");

		Event::FireEvent('event1');
		$this->assertEquals(3, self::$iEventCalls);

		Event::FireEvent('event2');
		$this->assertEquals(4, self::$iEventCalls);

		Event::UnRegisterAll();

		Event::FireEvent('event1');
		$this->assertEquals(4, self::$iEventCalls);

		Event::FireEvent('event2');
		$this->assertEquals(4, self::$iEventCalls);
	}

	public function testUnRegisterCallback()
	{
		$oReceiver = new TestEventReceiver();
		$sIdToRemove = Event::Register('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sIdToRemove");
		$sId = Event::Register('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = Event::Register('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = Event::Register('event2', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");

		Event::FireEvent('event1');
		$this->assertEquals(3, self::$iEventCalls);

		Event::FireEvent('event2');
		$this->assertEquals(4, self::$iEventCalls);

		Event::UnRegisterCallback($sIdToRemove);

		Event::FireEvent('event1');
		$this->assertEquals(6, self::$iEventCalls);

		Event::FireEvent('event2');
		$this->assertEquals(7, self::$iEventCalls);
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
	public static function DebugStatic($sMsg)
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

class TestEventReceiver
{

	// Event callbacks
	public function OnEvent1(EventData $oData)
	{
		$sEvent = $oData->GetEvent();
		$this->Debug(__METHOD__.": received event '{$sEvent}'");
		EventTest::IncrementCallCount();
	}

	// Event callbacks
	public function BrokenCallback(array $aData)
	{
		$sEvent = $aData['event'];
		$this->Debug(__METHOD__.": received event '{$sEvent}'");
		EventTest::IncrementCallCount();
	}

	// Event callbacks
	public function OnEvent2(EventData $oData)
	{
		$sEvent = $oData->GetEvent();
		$this->Debug(__METHOD__.": received event '{$sEvent}'");
		EventTest::IncrementCallCount();
	}

	public function OnEventWithData(EventData $oData)
	{
		$sEvent = $oData->GetEvent();
		$mEventData = $oData->GetEventData();
		$mUserData = $oData->GetUserData();
		$this->Debug(__METHOD__.": received event '{$sEvent}'");
		EventTest::IncrementCallCount();
		$this->Debug($mEventData);
		$this->Debug($mUserData);
	}

	// Event callbacks
	public static function OnStaticEvent1(EventData $oData)
	{
		$sEvent = $oData->GetEvent();
		self::DebugStatic(__METHOD__.": received event '{$sEvent}'");
		EventTest::IncrementCallCount();
	}

	// Event callbacks
	public static function OnStaticEvent2(EventData $oData)
	{
		$sEvent = $oData->GetEvent();
		self::DebugStatic(__METHOD__.": received event '{$sEvent}'");
		EventTest::IncrementCallCount();
	}

	/**
	 * static version of the debug to be accessible from other objects
	 *
	 * @param $sMsg
	 */
	public static function DebugStatic($sMsg)
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
	/**
	 * static version of the debug to be accessible from other objects
	 *
	 * @param $sMsg
	 */
	public function Debug($sMsg)
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
