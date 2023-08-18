<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Service\Events;

use Combodo\iTop\Service\Events\Description\EventDescription;
use Combodo\iTop\Service\Events\EventData;
use Combodo\iTop\Service\Events\EventService;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use ContextTag;
use CoreException;
use TypeError;

/**
 * Class EventTest
 *
 * @package Combodo\iTop\Test\UnitTest\Application\Service
 *
 */
class EventTest extends ItopDataTestCase
{
	const USE_TRANSACTION = false;
	const CREATE_TEST_ORG = false;
	const DEBUG_UNIT_TEST = true;

	private static int $iEventCalls;

	protected function setUp(): void
	{
		parent::setUp();
		self::$iEventCalls = 0;
		EventService::UnRegisterAll();
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
		$this->EventService_RegisterListener('event', $callback);
	}

	public function BadCallbackProvider()
	{
		return [
			['toto'],
			['EventTest::toto'],
			[['EventTest', 'toto']],
			[[$this, 'toto']],
		];
	}

	public function testNoParameterCallbackFunction()
	{
		$sId = $this->EventService_RegisterListener('event', function () {
			$this->debug("Closure: event received !!!");
			self::IncrementCallCount();
		});
		$this->debug("Registered $sId");
		EventService::RegisterEvent(new EventDescription('event', [], 'test', '', [], ''));

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event'));
		$this->assertEquals(1, self::$iEventCalls);
	}

	/**
	 * @dataProvider GoodCallbackProvider
	 *
	 * @param callable $callback Assume that callbacks will increment self::$iEventCalls
	 *
	 * @throws \Exception
	 */
	public function testMethodCallbackFunction(callable $callback)
	{
		EventService::RegisterEvent(new EventDescription('event', [], 'test', '', [], ''));
		$sId = $this->EventService_RegisterListener('event', $callback);
		$this->debug("Registered 'event' with id $sId");

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event'));
		$this->assertEquals(1, self::$iEventCalls);

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event'));
		$this->assertEquals(1, self::$iEventCalls);
	}

	public function GoodCallbackProvider()
	{
		$oReceiver = new TestEventReceiver();

		return array(
			'method'  => array(array($oReceiver, 'OnEvent1')),
			'static'  => array('Combodo\iTop\Test\UnitTest\Service\Events\TestEventReceiver::OnStaticEvent1'),
			'static2' => array(array('Combodo\iTop\Test\UnitTest\Service\Events\TestEventReceiver', 'OnStaticEvent1')),
		);
	}

	public function testBrokenCallback()
	{
		EventService::RegisterEvent(new EventDescription('event_a', [], 'test', '', [], ''));
		$oReceiver = new TestEventReceiver();
		$this->EventService_RegisterListener('event_a', array($oReceiver, 'BrokenCallback'));

		$this->expectException(TypeError::class);
		EventService::FireEvent(new EventData('event_a'));
	}

	public function testRemovedCallback()
	{
		EventService::RegisterEvent(new EventDescription('event_a', [], 'test', '', [], ''));
		$oReceiver = new TestEventReceiver();
		$this->EventService_RegisterListener('event_a', array($oReceiver, 'OnEvent1'));

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event_a'));
		$this->assertEquals(1, self::$iEventCalls);

		$oReceiver = null;
		gc_collect_cycles();

		// The callback is held by the event service
		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event_a'));
		$this->assertEquals(1, self::$iEventCalls);
	}

	public function testMultiEvent()
	{
		EventService::RegisterEvent(new EventDescription('event_a', [], 'test', '', [], ''));
		EventService::RegisterEvent(new EventDescription('event_b', [], 'test', '', [], ''));
		$oReceiver = new TestEventReceiver();
		$this->EventService_RegisterListener('event_a', array($oReceiver, 'OnEvent1'));
		$this->EventService_RegisterListener('event_a', array($oReceiver, 'OnEvent2'));
		$this->EventService_RegisterListener('event_a', array('Combodo\iTop\Test\UnitTest\Service\Events\TestEventReceiver', 'OnStaticEvent1'));
		$this->EventService_RegisterListener('event_a', 'Combodo\iTop\Test\UnitTest\Service\Events\TestEventReceiver::OnStaticEvent2');

		$this->EventService_RegisterListener('event_b', array($oReceiver, 'OnEvent1'));
		$this->EventService_RegisterListener('event_b', array($oReceiver, 'OnEvent2'));
		$this->EventService_RegisterListener('event_b', array('Combodo\iTop\Test\UnitTest\Service\Events\TestEventReceiver', 'OnStaticEvent1'));
		$this->EventService_RegisterListener('event_b', 'Combodo\iTop\Test\UnitTest\Service\Events\TestEventReceiver::OnStaticEvent2');

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event_a'));
		$this->assertEquals(4, self::$iEventCalls);

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event_b'));
		$this->assertEquals(4, self::$iEventCalls);
	}

	public function testMultiSameEvent()
	{
		EventService::RegisterEvent(new EventDescription('event1', [], 'test', '', [], ''));
		$oReceiver = new TestEventReceiver();
		$sId = $this->EventService_RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = $this->EventService_RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = $this->EventService_RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = $this->EventService_RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event1'));
		$this->assertEquals(4, self::$iEventCalls);
	}

	public function testData()
	{
		EventService::RegisterEvent(new EventDescription('event1', [], 'test', '', [], ''));
		$oReceiver = new TestEventReceiver();
		$this->EventService_RegisterListener('event1', [$oReceiver, 'OnEventWithData'], '');
		$this->EventService_RegisterListener('event1', [$oReceiver, 'OnEventWithData'], '');

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event1', '', ['text' => 'Event Data 1']));
		$this->assertEquals(2, self::$iEventCalls);
	}

	public function testPriority()
	{
		EventService::RegisterEvent(new EventDescription('event1', [], 'test', '', [], ''));
		EventService::RegisterEvent(new EventDescription('event2', [], 'test', '', [], ''));
		$oReceiver = new TestEventReceiver();
		$this->EventService_RegisterListener('event1', [$oReceiver, 'OnEvent1'], '', [], null, 0);
		$this->EventService_RegisterListener('event1', [$oReceiver, 'OnEvent2'], '', [], null, 1);

		$this->EventService_RegisterListener('event2', [$oReceiver, 'OnEvent1'], '', [], null, 1);
		$this->EventService_RegisterListener('event2', [$oReceiver, 'OnEvent2'], '', [], null, 0);

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event1'));
		$this->assertEquals(2, self::$iEventCalls);

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event2'));
		$this->assertEquals(2, self::$iEventCalls);
	}

	public function testNoRegisterEvent()
	{
		$this->expectException(CoreException::class);
		EventService::FireEvent(new EventData('event1'));
	}

	public function testContext()
	{
		EventService::RegisterEvent(new EventDescription('event1', [], 'test', '', [], ''));
		$oReceiver = new TestEventReceiver();
		$this->EventService_RegisterListener('event1', [$oReceiver, 'OnEvent1'], '', [], null, 0);
		$this->EventService_RegisterListener('event1', [$oReceiver, 'OnEvent2'], '', [], 'test_context', 1);

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event1'));
		$this->assertEquals(1, self::$iEventCalls);

		/** @noinspection PhpUnusedLocalVariableInspection */
		$oUnused = new ContextTag('test_context');
		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event1'));
		$this->assertEquals(2, self::$iEventCalls);
	}

	public function testEventSource()
	{
		EventService::RegisterEvent(new EventDescription('event1', [], 'test', '', [], ''));
		EventService::RegisterEvent(new EventDescription('event2', [], 'test', '', [], ''));
		$oReceiver = new TestEventReceiver();
		$this->EventService_RegisterListener('event1', [$oReceiver, 'OnEvent1'], 'A', [], null, 0);
		$this->EventService_RegisterListener('event1', [$oReceiver, 'OnEvent2'], 'A', [], null, 1);
		$this->EventService_RegisterListener('event1', 'Combodo\iTop\Test\UnitTest\Service\Events\TestEventReceiver::OnStaticEvent1', null, [], null, 2);

		$this->EventService_RegisterListener('event2', [$oReceiver, 'OnEvent1'], 'A', [], null, 1);
		$this->EventService_RegisterListener('event2', 'Combodo\iTop\Test\UnitTest\Service\Events\TestEventReceiver::OnStaticEvent1', null, [], null, 2);
		$this->EventService_RegisterListener('event2', [$oReceiver, 'OnEvent2'], 'B', [], null, 0);

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event1', 'A'));
		$this->assertEquals(3, self::$iEventCalls);

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event2', 'A'));
		$this->assertEquals(2, self::$iEventCalls);

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event1'));
		$this->assertEquals(1, self::$iEventCalls);

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event2'));
		$this->assertEquals(1, self::$iEventCalls);

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event2', ['A', 'B']));
		$this->assertEquals(3, self::$iEventCalls);

	}


	public function testUnRegisterEventListener()
	{
		EventService::RegisterEvent(new EventDescription('event1', [], 'test', '', [], ''));
		EventService::RegisterEvent(new EventDescription('event2', [], 'test', '', [], ''));
		$oReceiver = new TestEventReceiver();
		$sId = $this->EventService_RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = $this->EventService_RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = $this->EventService_RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = $this->EventService_RegisterListener('event2', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event1'));
		$this->assertEquals(3, self::$iEventCalls);

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event2'));
		$this->assertEquals(1, self::$iEventCalls);

		EventService::UnRegisterEventListeners('event1');

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event1'));
		$this->assertEquals(0, self::$iEventCalls);

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event2'));
		$this->assertEquals(1, self::$iEventCalls);
	}

	public function testUnRegisterAll()
	{
		EventService::RegisterEvent(new EventDescription('event1', [], 'test', '', [], ''));
		EventService::RegisterEvent(new EventDescription('event2', [], 'test', '', [], ''));
		$oReceiver = new TestEventReceiver();
		$sId = $this->EventService_RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = $this->EventService_RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = $this->EventService_RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = $this->EventService_RegisterListener('event2', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event1'));
		$this->assertEquals(3, self::$iEventCalls);

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event2'));
		$this->assertEquals(1, self::$iEventCalls);

		EventService::UnRegisterAll();

		$this->expectException(CoreException::class);
		EventService::FireEvent(new EventData('event1'));
	}

	public function testUnRegisterCallback()
	{
		EventService::RegisterEvent(new EventDescription('event1', [], 'test', '', [], ''));
		EventService::RegisterEvent(new EventDescription('event2', [], 'test', '', [], ''));
		$oReceiver = new TestEventReceiver();
		$sIdToRemove = $this->EventService_RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sIdToRemove");
		$sId = $this->EventService_RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = $this->EventService_RegisterListener('event1', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");
		$sId = $this->EventService_RegisterListener('event2', array($oReceiver, 'OnEvent1'));
		$this->debug("Registered $sId");

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event1'));
		$this->assertEquals(3, self::$iEventCalls);

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event2'));
		$this->assertEquals(1, self::$iEventCalls);

		EventService::UnRegisterListener($sIdToRemove);

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event1'));
		$this->assertEquals(2, self::$iEventCalls);

		self::$iEventCalls = 0;
		EventService::FireEvent(new EventData('event2'));
		$this->assertEquals(1, self::$iEventCalls);
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
		if (static::$DEBUG_UNIT_TEST) {
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
		EventTest::DebugStatic($sMsg);
	}

	/**
	 * @param $sMsg
	 */
	public function Debug($sMsg)
	{
		EventTest::DebugStatic($sMsg);
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

