<?php

namespace Combodo\iTop\Test\UnitTest\Service;

use Combodo\iTop\Service\Description\EventDescription;
use Combodo\iTop\Service\EventData;
use Combodo\iTop\Service\EventService;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use ContextTag;
use Exception;
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

	protected function setUp(): void
	{
		parent::setUp();
		self::$iEventCalls = 0;
	}

	/**
	 * @dataProvider BadCallbackProvider
	 *
	 * @param $callback
	 *
	 * @throws \Exception
	 */
	public function testRegisterBadCallback($callback)
	{
		$this->expectException(TypeError::class);
		EventService::RegisterListener('event', $callback);
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
		$sId = EventService::RegisterListener('event', function () {
			$this->debug("Closure: event received !!!");
			self::IncrementCallCount();
		});
		$this->debug("Registered $sId");
		EventService::RegisterEvent(new EventDescription('event', [], 'test', '', [], ''));
		EventService::FireEvent(new EventData('event'));
		$this->assertEquals(1, self::$iEventCalls);
	}

	/**
	 * @dataProvider GoodCallbackProvider
	 *
	 * @param $callback
	 *
	 * @throws \Exception
	 */
	public function testMethodCallbackFunction(callable $callback)
	{
		EventService::RegisterEvent(new EventDescription('event', [], 'test', '', [], ''));
		$sId = EventService::RegisterListener('event', $callback);
		$this->debug("Registered $sId");
		EventService::FireEvent(new EventData('event'));
		$this->assertEquals(1, self::$iEventCalls);
		EventService::FireEvent(new EventData('event'));
		$this->assertEquals(2, self::$iEventCalls);
	}

	public function GoodCallbackProvider()
	{
		$oReceiver = new TestEventReceiver();

		return array(
			'method'  => array(array($oReceiver, 'OnEvent1')),
			'static'  => array('Combodo\iTop\Test\UnitTest\Service\TestEventReceiver::OnStaticEvent1'),
			'static2' => array(array('Combodo\iTop\Test\UnitTest\Service\TestEventReceiver', 'OnStaticEvent1')),
		);
	}

	public function testBrokenCallback()
	{
		EventService::RegisterEvent(new EventDescription('event_a', [], 'test', '', [], ''));
		$oReceiver = new TestEventReceiver();
		EventService::RegisterListener('event_a', array($oReceiver, 'BrokenCallback'));

		$this->expectException(TypeError::class);
		EventService::FireEvent(new EventData('event_a'));
	}

	public function testRemovedCallback()
	{
		EventService::RegisterEvent(new EventDescription('event_a', [], 'test', '', [], ''));
		$oReceiver = new TestEventReceiver();
		EventService::RegisterListener('event_a', array($oReceiver, 'OnEvent1'));

		$oReceiver = null;
		gc_collect_cycles();

		EventService::FireEvent(new EventData('event_a'));
		$this->assertEquals(1, self::$iEventCalls);
	}

	public function testMultiEvent()
	{
		EventService::RegisterEvent(new EventDescription('event_a', [], 'test', '', [], ''));
		EventService::RegisterEvent(new EventDescription('event_b', [], 'test', '', [], ''));
		$oReceiver = new TestEventReceiver();
		EventService::RegisterListener('event_a', array($oReceiver, 'OnEvent1'));
		EventService::RegisterListener('event_a', array($oReceiver, 'OnEvent2'));
		EventService::RegisterListener('event_a', array('Combodo\iTop\Test\UnitTest\Service\TestEventReceiver', 'OnStaticEvent1'));
		EventService::RegisterListener('event_a', 'Combodo\iTop\Test\UnitTest\Service\TestEventReceiver::OnStaticEvent2');

		EventService::RegisterListener('event_b', array($oReceiver, 'OnEvent1'));
		EventService::RegisterListener('event_b', array($oReceiver, 'OnEvent2'));
		EventService::RegisterListener('event_b', array('Combodo\iTop\Test\UnitTest\Service\TestEventReceiver', 'OnStaticEvent1'));
		EventService::RegisterListener('event_b', 'Combodo\iTop\Test\UnitTest\Service\TestEventReceiver::OnStaticEvent2');

		EventService::FireEvent(new EventData('event_a'));
		$this->assertEquals(4, self::$iEventCalls);
		EventService::FireEvent(new EventData('event_b'));
		$this->assertEquals(8, self::$iEventCalls);
	}

	public function testMultiSameEvent()
	{
		EventService::RegisterEvent(new EventDescription('event1', [], 'test', '', [], ''));
		$oReceiver = new TestEventReceiver();
		$sId = EventService::RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = EventService::RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = EventService::RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = EventService::RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");

		EventService::FireEvent(new EventData('event1'));
		$this->assertEquals(4, self::$iEventCalls);
	}

	public function testData()
	{
		EventService::RegisterEvent(new EventDescription('event1', [], 'test', '', [], ''));
		$oReceiver = new TestEventReceiver();
		EventService::RegisterListener('event1', [$oReceiver, 'OnEventWithData'], '');
		EventService::RegisterListener('event1', [$oReceiver, 'OnEventWithData'], '');
		EventService::FireEvent(new EventData('event1', '', ['text' => 'Event Data 1']));
		$this->assertEquals(2, self::$iEventCalls);
	}

	public function testPriority()
	{
		EventService::RegisterEvent(new EventDescription('event1', [], 'test', '', [], ''));
		EventService::RegisterEvent(new EventDescription('event2', [], 'test', '', [], ''));
		$oReceiver = new TestEventReceiver();
		EventService::RegisterListener('event1', [$oReceiver, 'OnEvent1'], '', null, null, 0);
		EventService::RegisterListener('event1', [$oReceiver, 'OnEvent2'], '', null, null, 1);

		EventService::RegisterListener('event2', [$oReceiver, 'OnEvent1'], '', null, null, 1);
		EventService::RegisterListener('event2', [$oReceiver, 'OnEvent2'], '', null, null, 0);

		EventService::FireEvent(new EventData('event1'));
		$this->assertEquals(2, self::$iEventCalls);
		EventService::FireEvent(new EventData('event2'));
		$this->assertEquals(4, self::$iEventCalls);
	}

	public function testNoRegisterEvent()
	{
		try {
			EventService::FireEvent(new EventData('event1'));
			$this->assertTrue(false);
		} catch (\CoreException $e) {
			$this->assertTrue(true);
		}
	}

	public function testContext()
	{
		EventService::RegisterEvent(new EventDescription('event1', [], 'test', '', [], ''));
		$oReceiver = new TestEventReceiver();
		EventService::RegisterListener('event1', [$oReceiver, 'OnEvent1'], '', null, null, 0);
		EventService::RegisterListener('event1', [$oReceiver, 'OnEvent2'], '', null, 'test_context', 1);
		EventService::FireEvent(new EventData('event1'));
		$this->assertEquals(1, self::$iEventCalls);
		ContextTag::AddContext('test_context');
		EventService::FireEvent(new EventData('event1'));
		$this->assertEquals(3, self::$iEventCalls);
	}

	public function testEventSource()
	{
		EventService::RegisterEvent(new EventDescription('event1', [], 'test', '', [], ''));
		EventService::RegisterEvent(new EventDescription('event2', [], 'test', '', [], ''));
		$oReceiver = new TestEventReceiver();
		EventService::RegisterListener('event1', [$oReceiver, 'OnEvent1'], 'A', null, null, 0);
		EventService::RegisterListener('event1', [$oReceiver, 'OnEvent2'], 'A', null, null, 1);
		EventService::RegisterListener('event1', 'Combodo\iTop\Test\UnitTest\Service\TestEventReceiver::OnStaticEvent1', null, null, null, 2);

		EventService::RegisterListener('event2', [$oReceiver, 'OnEvent1'], 'A', null, null, 1);
		EventService::RegisterListener('event2', 'Combodo\iTop\Test\UnitTest\Service\TestEventReceiver::OnStaticEvent1', null, null, null, 2);
		EventService::RegisterListener('event2', [$oReceiver, 'OnEvent2'], 'B', null, null, 0);

		EventService::FireEvent(new EventData('event1', 'A'));
		$this->assertEquals(3, self::$iEventCalls);
		EventService::FireEvent(new EventData('event2', 'A'));
		$this->assertEquals(5, self::$iEventCalls);
		EventService::FireEvent(new EventData('event1'));
		$this->assertEquals(6, self::$iEventCalls);
		EventService::FireEvent(new EventData('event2'));
		$this->assertEquals(7, self::$iEventCalls);
		EventService::FireEvent(new EventData('event2', ['A', 'B']));
		$this->assertEquals(10, self::$iEventCalls);

	}


	public function testUnRegisterEvent()
	{
		EventService::RegisterEvent(new EventDescription('event1', [], 'test', '', [], ''));
		EventService::RegisterEvent(new EventDescription('event2', [], 'test', '', [], ''));
		$oReceiver = new TestEventReceiver();
		$sId = EventService::RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = EventService::RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = EventService::RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = EventService::RegisterListener('event2', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");

		EventService::FireEvent(new EventData('event1'));
		$this->assertEquals(3, self::$iEventCalls);

		EventService::FireEvent(new EventData('event2'));
		$this->assertEquals(4, self::$iEventCalls);

		EventService::UnRegisterEventListeners('event1');

		EventService::FireEvent(new EventData('event1'));
		$this->assertEquals(4, self::$iEventCalls);

		EventService::FireEvent(new EventData('event2'));
		$this->assertEquals(5, self::$iEventCalls);
	}

	public function testUnRegisterAll()
	{
		EventService::RegisterEvent(new EventDescription('event1', [], 'test', '', [], ''));
		EventService::RegisterEvent(new EventDescription('event2', [], 'test', '', [], ''));
		$oReceiver = new TestEventReceiver();
		$sId = EventService::RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = EventService::RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = EventService::RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = EventService::RegisterListener('event2', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");

		EventService::FireEvent(new EventData('event1'));
		$this->assertEquals(3, self::$iEventCalls);

		EventService::FireEvent(new EventData('event2'));
		$this->assertEquals(4, self::$iEventCalls);

		EventService::UnRegisterAll();

		EventService::FireEvent(new EventData('event1'));
		$this->assertEquals(4, self::$iEventCalls);

		EventService::FireEvent(new EventData('event2'));
		$this->assertEquals(4, self::$iEventCalls);
	}

	public function testUnRegisterCallback()
	{
		EventService::RegisterEvent(new EventDescription('event1', [], 'test', '', [], ''));
		EventService::RegisterEvent(new EventDescription('event2', [], 'test', '', [], ''));
		$oReceiver = new TestEventReceiver();
		$sIdToRemove = EventService::RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sIdToRemove");
		$sId = EventService::RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = EventService::RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = EventService::RegisterListener('event2', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");

		EventService::FireEvent(new EventData('event1'));
		$this->assertEquals(3, self::$iEventCalls);

		EventService::FireEvent(new EventData('event2'));
		$this->assertEquals(4, self::$iEventCalls);

		EventService::UnRegisterListener($sIdToRemove);

		EventService::FireEvent(new EventData('event1'));
		$this->assertEquals(6, self::$iEventCalls);

		EventService::FireEvent(new EventData('event2'));
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
		if (DEBUG_UNIT_TEST) {
			if (is_string($sMsg)) {
				echo "$sMsg\n";
			} else {
				print_r($sMsg);
			}
		}
	}
}

class TestClassesWithDebug
{
	/**
	 * static version of the debug to be accessible from other objects
	 *
	 * @param $sMsg
	 */
	public static function DebugStatic($sMsg)
	{
		if (DEBUG_UNIT_TEST) {
			if (is_string($sMsg)) {
				echo "$sMsg\n";
			} else {
				print_r($sMsg);
			}
		}
	}

	/**
	 * @param $sMsg
	 */
	public function Debug($sMsg)
	{
		if (DEBUG_UNIT_TEST) {
			if (is_string($sMsg)) {
				echo "$sMsg\n";
			} else {
				print_r($sMsg);
			}
		}
	}
}

class TestEventReceiver extends TestClassesWithDebug
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
		$this->Debug(__METHOD__.": received event '{$sEvent}'");
		EventTest::IncrementCallCount();
		$this->Debug($mEventData);
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
}

class TestCRUDObject extends TestClassesWithDebug
{
	private static $aEventList = [];

	public function DBInsert($sName)
	{
		$this->sName = $sName;
		$this->FireEvent('insert');
	}

	public function DBUpdate($sName)
	{
		$this->sName = $sName;
		$this->FireEvent('update');
	}

	public function FireEvent($sEvent, $aEventData = [])
	{
		$aEventData['name'] = $this->sName;
		$aEventData['object'] = $this;
		$oEventData = new EventData($sEvent, $this->sName, $aEventData);

		$bFireEventNow = empty(self::$aEventList['event']);
		self::$aEventList['event'][] = $oEventData;

		$iCount = 0;
		while ($bFireEventNow) {
			$sEvent = $oEventData->GetEvent();
			$sName = $oEventData->Get('name');
			$this->Debug("Object from $sName: Fire event '$sEvent'");
			EventService::FireEvent($oEventData);
			$this->Debug("Object from $sName: End of event '$sEvent'");

			array_shift(self::$aEventList['event']);
			$oEventData = reset(self::$aEventList['event']);

			if ($oEventData === false) {
				$bFireEventNow = false;
			}
			if ($iCount++ > 10) {
				throw new Exception('Infinite loop');
			}
		}
	}
}

class TestReentranceCRUD extends TestClassesWithDebug
{
	public $sName;
	public $sEvent1;
	public $sEvent2;
	public $bPermanentProtection;

	private static $iIndent1 = 0;
	private static $iIndent2 = 0;

	public $aSendEvent = [];

	/**
	 * @param $sName
	 * @param $fPriority
	 * @param array $aSendEvent
	 * @param bool $bPermanentProtection
	 */
	public function __construct($sName, $fPriority, array $aSendEvent, $bPermanentProtection)
	{
		$this->sName = $sName;
		$this->sEvent1 = EventService::RegisterListener('insert', [$this, 'OnInsert'], null, [], null, $fPriority);
		$this->sEvent2 = EventService::RegisterListener('update', [$this, 'OnUpdate'], null, [], null, $fPriority);
		$this->aSendEvent = $aSendEvent;
		$this->bPermanentProtection = $bPermanentProtection;
	}

	public function OnInsert(EventData $oData)
	{
		$sIndent = str_repeat('  ', self::$iIndent1);
		$oObject = $oData->Get('object');
		$sEvent = $oData->GetEvent();
		$sSource = $oData->GetEventSource();
		$sName = $sIndent.$this->sName;
		$this->Debug("$sName: received event '$sEvent' from '$sSource'");
		EventTest::IncrementCallCount();

		if ($this->aSendEvent['listener1']['update']) {
			self::$iIndent2 += 1;
			$this->Debug("$sName: Update");
			$oObject->DBUpdate($sName);
			self::$iIndent2 -= 1;
		}
	}

	public function OnUpdate(EventData $oData)
	{
		$sIndent = str_repeat('  ', self::$iIndent2);
		$oObject = $oData->Get('object');
		$sEvent = $oData->GetEvent();
		$sSource = $oData->GetEventSource();
		$sName = $sIndent.$this->sName;
		$this->Debug("$sName: received event '$sEvent' from '$sSource'");
		EventTest::IncrementCallCount();
		if ($this->aSendEvent['listener2']['update']) {
			self::$iIndent2 += 1;
			$this->Debug("$sName: Update");
			$oObject->DBUpdate($sName);
			self::$iIndent2 -= 1;
		}
	}
}

class TestReentrance extends TestClassesWithDebug
{
	public $sName;
	public $sEvent1;
	public $sEvent2;
	public $bPermanentProtection;

	private static $iIndent1 = 0;
	private static $iIndent2 = 0;

	public $aSendEvent = [];

	/**
	 * @param $sName
	 * @param $fPriority
	 * @param array $aSendEvent
	 * @param bool $bPermanentProtection
	 */
	public function __construct($sName, $fPriority, array $aSendEvent, $bPermanentProtection)
	{
		$this->sName = $sName;
		$this->sEvent1 = EventService::RegisterListener('event1', [$this, 'ListenerEvent1'], null, [], null, $fPriority);
		$this->sEvent2 = EventService::RegisterListener('event2', [$this, 'ListenerEvent2'], null, [], null, $fPriority);
		$this->aSendEvent = $aSendEvent;
		$this->bPermanentProtection = $bPermanentProtection;
	}

	public function ListenerEvent1(EventData $oData)
	{
		$sIndent = str_repeat('  ', self::$iIndent1);
		$sEvent = $oData->GetEvent();
		$sSource = $oData->GetEventSource();
		$sName = $sIndent.$this->sName;
		$this->Debug("$sName: received event '$sEvent' from '$sSource'");
		EventTest::IncrementCallCount();
		if ($this->aSendEvent['listener1']['event1']) {
			$this->Debug("$sName: Fire event 'event1'");
			self::$iIndent1 += 1;
			EventService::FireEvent(new EventData('event1', $sName));
			self::$iIndent1 -= 1;
			$this->Debug("$sName: End of event 'event1'");
		}
		if ($this->aSendEvent['listener1']['event2']) {
			$this->Debug("$sName: Fire event 'event2'");
			self::$iIndent2 += 1;
			EventService::FireEvent(new EventData('event2', $sName));
			self::$iIndent2 -= 1;
			$this->Debug("$sName: End of event 'event2'");
		}
	}

	public function ListenerEvent2(EventData $oData)
	{
		$sIndent = str_repeat('  ', self::$iIndent2);
		$sEvent = $oData->GetEvent();
		$sSource = $oData->GetEventSource();
		$sName = $sIndent.$this->sName;
		$this->Debug("$sName: received event '$sEvent' from '$sSource'");
		EventTest::IncrementCallCount();
		if ($this->aSendEvent['listener2']['event1']) {
			$this->Debug("$sName: Fire event 'event1'");
			self::$iIndent1 += 1;
			EventService::FireEvent(new EventData('event1', $sName));
			self::$iIndent1 -= 1;
			$this->Debug("$sName: End of event 'event1'");
		}
		if ($this->aSendEvent['listener2']['event2']) {
			$this->Debug("$sName: Fire event 'event2'");
			self::$iIndent2 += 1;
			EventService::FireEvent(new EventData('event2', $sName));
			self::$iIndent2 -= 1;
			$this->Debug("$sName: End of event 'event2'");
		}
	}
}