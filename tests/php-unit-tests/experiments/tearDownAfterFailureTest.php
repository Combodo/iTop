<?php

namespace Combodo\iTop\Test\UnitTest;
use PHPUnit\Framework\TestCase;

/**
 * Shows that tearDown is called after a fatal error within a test
 */
class tearDownAfterFailureTest extends TestCase
{
	static $bIsCorrectlyInitialized = true;

	protected function tearDown(): void
	{
		parent::tearDown();
		static::$bIsCorrectlyInitialized = true;
	}

	function testIsInitializedAndChangeIt()
	{
		static::assertTrue(static::$bIsCorrectlyInitialized);

		static::$bIsCorrectlyInitialized = false;

		$this->expectException('Exception');
		throw new \Exception('hello');
	}

	function testIsStillInitialized()
	{
		static::assertTrue(static::$bIsCorrectlyInitialized);
	}

	function testFailingDueToUnexpectedException()
	{
		static::$bIsCorrectlyInitialized = false;
		This_Is_Not_A_Function_And_Causes_A_Fatal_Error();
	}

	function testIsStillInitializedAnyway()
	{
		static::assertTrue(static::$bIsCorrectlyInitialized);
	}

}
