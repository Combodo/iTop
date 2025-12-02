<?php

/**
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace DBObject\Utils;

trait EventTest
{
	private static array $aEventCallsCount = [];
	private static int $iEventCallsTotalCount = 0;

	public static function IncrementCallCount(string $sEvent): void
	{
		self::$aEventCallsCount[$sEvent] = (self::$aEventCallsCount[$sEvent] ?? 0) + 1;
		self::$iEventCallsTotalCount++;
	}

	public static function CleanCallCount(): void
	{
		self::$aEventCallsCount = [];
		self::$iEventCallsTotalCount = 0;
	}

	public function AssertEventCountEquals(int $iExpectedCount, string $sEvent, string $sMessage = ''): void
	{
		$this->assertEquals($iExpectedCount, self::$aEventCallsCount[$sEvent] ?? 0, $sMessage);
	}

	public function AssertTotalEventCountEquals(int $iExpectedCount, string $sMessage = ''): void
	{
		$this->assertEquals($iExpectedCount, self::$iEventCallsTotalCount, $sMessage);
	}

	public function AssertReceivedEventsEquals(array $aExpectedEvents, string $sMessage = ''): void
	{
		$this->assertEquals($aExpectedEvents, array_keys(self::$aEventCallsCount), $sMessage);
	}

	public function AssertEventNotReceived(string $sEvent, string $sMessage = ''): void
	{
		$this->assertArrayNotHasKey($sEvent, self::$aEventCallsCount, $sMessage);
	}
}
