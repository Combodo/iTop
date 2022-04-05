<?php

namespace Combodo\iTop\Test\UnitTest\Service;

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

	protected function setUp()
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
		$oReceiver = new TestEventReceiver();
		EventService::RegisterListener('event_a', array($oReceiver, 'BrokenCallback'));

		$this->expectException(TypeError::class);
		EventService::FireEvent(new EventData('event_a'));
	}

	public function testRemovedCallback()
	{
		$oReceiver = new TestEventReceiver();
		EventService::RegisterListener('event_a', array($oReceiver, 'OnEvent1'));

		$oReceiver = null;
		gc_collect_cycles();

		EventService::FireEvent(new EventData('event_a'));
		$this->assertEquals(1, self::$iEventCalls);
	}

	public function testMultiEvent()
	{
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
		$oReceiver = new TestEventReceiver();
		EventService::RegisterListener('event1', [$oReceiver, 'OnEventWithData'], '');
		EventService::RegisterListener('event1', [$oReceiver, 'OnEventWithData'], '');
		EventService::FireEvent(new EventData('event1', '', ['text' => 'Event Data 1']));
		$this->assertEquals(2, self::$iEventCalls);
	}

	public function testPriority()
	{
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

	public function testContext()
	{
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

		EventService::UnRegisterEvent('event1');

		EventService::FireEvent(new EventData('event1'));
		$this->assertEquals(4, self::$iEventCalls);

		EventService::FireEvent(new EventData('event2'));
		$this->assertEquals(5, self::$iEventCalls);
	}

	public function testUnRegisterAll()
	{
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

		EventService::UnRegisterCallback($sIdToRemove);

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
	 * @dataProvider ReentranceProvider
	 */
	public function testReentrance($aClasses, $iEventCount)
	{
		foreach ($aClasses as $sName => $aClass) {
			new TestReentrance($sName, $aClass['prio'], $aClass['events'], $aClass['permanent_protection']);
		}
		EventService::FireEvent(new EventData('event1', 'main'));
		$this->assertEquals($iEventCount, self::$iEventCalls);
	}

	public function ReentranceProvider()
	{
		return [
			'1 class'                   => [
				'aClasses'    => [
					'Class A' => ['prio' => 0, 'permanent_protection' => true, 'events' => ['listener1' => ['event1' => true, 'event2' => false], 'listener2' => ['event1' => false, 'event2' => false]]],
				],
				'iEventCount' => 1,
			],
			'2 classes - 1'             => [
				'aClasses'    => [
					'Class A' => ['prio' => 0, 'permanent_protection' => true, 'events' => ['listener1' => ['event1' => true, 'event2' => false], 'listener2' => ['event1' => false, 'event2' => false]]],
					'Class B' => ['prio' => 10, 'permanent_protection' => true, 'events' => ['listener1' => ['event1' => false, 'event2' => false], 'listener2' => ['event1' => false, 'event2' => false]]],
				],
				'iEventCount' => 3,
			],
			'2 classes - 2'             => [
				'aClasses'    => [
					'Class A' => ['prio' => 0, 'permanent_protection' => true, 'events' => ['listener1' => ['event1' => true, 'event2' => true], 'listener2' => ['event1' => false, 'event2' => false]]],
					'Class B' => ['prio' => 10, 'permanent_protection' => true, 'events' => ['listener1' => ['event1' => false, 'event2' => false], 'listener2' => ['event1' => false, 'event2' => false]]],
				],
				'iEventCount' => 4,
			],
			'2 classes -  3'            => [
				'aClasses'    => [
					'Class A' => ['prio' => 0, 'permanent_protection' => true, 'events' => ['listener1' => ['event1' => false, 'event2' => true], 'listener2' => ['event1' => false, 'event2' => false]]],
					'Class B' => ['prio' => 10, 'permanent_protection' => true, 'events' => ['listener1' => ['event1' => false, 'event2' => false], 'listener2' => ['event1' => false, 'event2' => true]]],
				],
				'iEventCount' => 3,
			],
			'3 classes -  1'            => [
				'aClasses'    => [
					'Class A' => ['prio' => 0, 'permanent_protection' => true, 'events' => ['listener1' => ['event1' => false, 'event2' => true], 'listener2' => ['event1' => false, 'event2' => false]]],
					'Class B' => ['prio' => 10, 'permanent_protection' => true, 'events' => ['listener1' => ['event1' => false, 'event2' => true], 'listener2' => ['event1' => false, 'event2' => false]]],
					'Class C' => ['prio' => 20, 'permanent_protection' => true, 'events' => ['listener1' => ['event1' => false, 'event2' => true], 'listener2' => ['event1' => false, 'event2' => true]]],
				],
				'iEventCount' => 11,
			],
			'3 classes - non permanent' => [
				'aClasses'    => [
					'Class A' => ['prio' => 0, 'permanent_protection' => false, 'events' => ['listener1' => ['event1' => false, 'event2' => true], 'listener2' => ['event1' => false, 'event2' => false]]],
					'Class B' => ['prio' => 10, 'permanent_protection' => true, 'events' => ['listener1' => ['event1' => false, 'event2' => true], 'listener2' => ['event1' => false, 'event2' => false]]],
					'Class C' => ['prio' => 20, 'permanent_protection' => true, 'events' => ['listener1' => ['event1' => false, 'event2' => true], 'listener2' => ['event1' => false, 'event2' => true]]],
				],
				'iEventCount' => 12,
			],
			'2 classes -  loop'         => [
				'aClasses'    => [
					'Class A' => ['prio' => 0, 'permanent_protection' => true, 'events' => ['listener1' => ['event1' => true, 'event2' => false], 'listener2' => ['event1' => false, 'event2' => false]]],
					'Class B' => ['prio' => 10, 'permanent_protection' => true, 'events' => ['listener1' => ['event1' => true, 'event2' => false], 'listener2' => ['event1' => false, 'event2' => false]]],
				],
				'iEventCount' => 4,
			],
			'2 classes -  loop2'        => [
				'aClasses'    => [
					'Class A' => ['prio' => 0, 'permanent_protection' => false, 'events' => ['listener1' => ['event1' => true, 'event2' => false], 'listener2' => ['event1' => false, 'event2' => false]]],
					'Class B' => ['prio' => 10, 'permanent_protection' => true, 'events' => ['listener1' => ['event1' => true, 'event2' => false], 'listener2' => ['event1' => false, 'event2' => false]]],
				],
				'iEventCount' => 5,
			],
		];
	}

	/**
	 * @dataProvider ReentranceCRUDProvider
	 */
	public function testReentranceCRUD($aClasses, $iEventCount)
	{
		foreach ($aClasses as $sName => $aClass) {
			new TestReentranceCRUD($sName, $aClass['prio'], $aClass['events'], $aClass['permanent_protection']);
		}
		$oObject = new TestCRUDObject();
		$oObject->DBInsert('main');
		$this->assertEquals($iEventCount, self::$iEventCalls);
	}

	public function ReentranceCRUDProvider()
	{
		return [
			'1 class' => [
				'aClasses'    => [
					'Class A' => ['prio' => 0, 'permanent_protection' => true, 'events' => ['listener1' => ['update' => false], 'listener2' => ['update' => false]]],
				],
				'iEventCount' => 1,
			],
			'2 classes - 1' => [
				'aClasses'    => [
					'Class A' => ['prio' => 0, 'permanent_protection' => true, 'events' => ['listener1' => ['update' => false], 'listener2' => ['update' => false]]],
					'Class B' => ['prio' => 10, 'permanent_protection' => true, 'events' => ['listener1' => ['update' => false], 'listener2' => ['update' => false]]],
				],
				'iEventCount' => 2,
			],
			'2 classes - 2'=> [
				'aClasses'    => [
					'Class A' => ['prio' => 0, 'permanent_protection' => true, 'events' => ['listener1' => ['update' => true], 'listener2' => ['update' => false]]],
					'Class B' => ['prio' => 10, 'permanent_protection' => true, 'events' => ['listener1' => ['update' => false], 'listener2' => ['update' => false]]],
				],
				'iEventCount' => 4,
			],
//			'2 classes -  3'  => [
//				'aClasses'    => [
//					'Class A' => ['prio' => 0, 'permanent_protection' => true, 'events' => ['listener1' => ['update' => true], 'listener2' => ['update' => false]]],
//					'Class B' => ['prio' => 10, 'permanent_protection' => true, 'events' => ['listener1' => ['update' => false], 'listener2' => ['update' => true]]],
//				],
//				'iEventCount' => 3,
//			],
//			'3 classes -  1'  => [
//				'aClasses'    => [
//					'Class A' => ['prio' => 0, 'permanent_protection' => true, 'events' => ['listener1' => ['update' => true], 'listener2' => ['update' => false]]],
//					'Class B' => ['prio' => 10, 'permanent_protection' => true, 'events' => ['listener1' => ['update' => true], 'listener2' => ['update' => false]]],
//					'Class C' => ['prio' => 20, 'permanent_protection' => true, 'events' => ['listener1' => ['update' => true], 'listener2' => ['update' => true]]],
//				],
//				'iEventCount' => 11,
//			],
//			'3 classes - non permanent' => [
//				'aClasses'    => [
//					'Class A' => ['prio' => 0, 'permanent_protection' => false, 'events' => ['listener1' => ['update' => true], 'listener2' => ['update' => false]]],
//					'Class B' => ['prio' => 10, 'permanent_protection' => true, 'events' => ['listener1' => ['update' => true], 'listener2' => ['update' => false]]],
//					'Class C' => ['prio' => 20, 'permanent_protection' => true, 'events' => ['listener1' => ['update' => true], 'listener2' => ['update' => true]]],
//				],
//				'iEventCount' => 12,
//			],
//			'2 classes -  loop' => [
//				'aClasses'    => [
//					'Class A' => ['prio' => 0, 'permanent_protection' => true, 'events' => ['listener1' => ['update' => false], 'listener2' => ['update' => false]]],
//					'Class B' => ['prio' => 10, 'permanent_protection' => true, 'events' => ['listener1' => ['update' => false], 'listener2' => ['update' => false]]],
//				],
//				'iEventCount' => 4,
//			],
//			'2 classes -  loop2' => [
//				'aClasses'    => [
//					'Class A' => ['prio' => 0, 'permanent_protection' => false, 'events' => ['listener1' => ['update' => false], 'listener2' => ['update' => false]]],
//					'Class B' => ['prio' => 10, 'permanent_protection' => true, 'events' => ['listener1' => ['update' => false], 'listener2' => ['update' => false]]],
//				],
//				'iEventCount' => 5,
//			],
		];
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
			EventService::EnableReentranceProtection($this->sEvent2, $this->bPermanentProtection);
			$oObject->DBUpdate($sName);
			EventService::DisableReentranceProtection($this->sEvent2);
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
			EventService::EnableReentranceProtection($this->sEvent2, $this->bPermanentProtection);
			$oObject->DBUpdate($sName);
			EventService::DisableReentranceProtection($this->sEvent2);
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
			EventService::EnableReentranceProtection($this->sEvent1, $this->bPermanentProtection);
			EventService::FireEvent(new EventData('event1', $sName));
			EventService::DisableReentranceProtection($this->sEvent1);
			self::$iIndent1 -= 1;
			$this->Debug("$sName: End of event 'event1'");
		}
		if ($this->aSendEvent['listener1']['event2']) {
			$this->Debug("$sName: Fire event 'event2'");
			self::$iIndent2 += 1;
			EventService::EnableReentranceProtection($this->sEvent2, $this->bPermanentProtection);
			EventService::FireEvent(new EventData('event2', $sName));
			EventService::DisableReentranceProtection($this->sEvent2);
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
			EventService::EnableReentranceProtection($this->sEvent1, $this->bPermanentProtection);
			EventService::FireEvent(new EventData('event1', $sName));
			EventService::DisableReentranceProtection($this->sEvent1);
			self::$iIndent1 -= 1;
			$this->Debug("$sName: End of event 'event1'");
		}
		if ($this->aSendEvent['listener2']['event2']) {
			$this->Debug("$sName: Fire event 'event2'");
			self::$iIndent2 += 1;
			EventService::EnableReentranceProtection($this->sEvent2, $this->bPermanentProtection);
			EventService::FireEvent(new EventData('event2', $sName));
			EventService::DisableReentranceProtection($this->sEvent2);
			self::$iIndent2 -= 1;
			$this->Debug("$sName: End of event 'event2'");
		}
	}
}