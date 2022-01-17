<?php

use Combodo\iTop\Application\Helper\Session;
use Combodo\iTop\Test\UnitTest\ItopTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class SessionTest extends ItopTestCase
{
	/**
	 * @covers \Combodo\iTop\Application\Helper\Session::Start
	 */
	public function testStart()
	{
		$this->assertNull(Session::$iSessionId);
		Session::Start();
		$this->assertNotNull(Session::$iSessionId);
	}

	/**
	 * @covers \Combodo\iTop\Application\Helper\Session::WriteClose
	 */
	public function testWriteClose()
	{
		$this->assertNull(Session::$iSessionId);
		Session::Start();
		$this->assertNotNull(Session::$iSessionId);
		Session::WriteClose();
		$_SESSION['test'] = 'OK';
		Session::Start();
		$this->assertArrayNotHasKey('test', $_SESSION);
	}

	public function testReopenningSession()
	{
		Session::Start();
		$_SESSION['test'] = 'OK';
		Session::WriteClose();
		unset($_SESSION['test']);
		Session::Start();
		$this->assertEquals('OK', $_SESSION['test']);
	}

	public function testSet()
	{
		Session::Start();
		Session::Set('test', 'OK');
		$this->assertEquals('OK', Session::Get('test'));
		Session::WriteClose();
		$this->assertEquals('OK', Session::Get('test'));
		unset($_SESSION['test']);
		Session::Start();
		$this->assertEquals('OK', Session::Get('test'));
	}

	public function testSetArray()
	{
		Session::Start();
		Session::Set(['test1', 'test2', 'test3'], 'OK');
		$this->assertEquals('OK', Session::Get(['test1', 'test2', 'test3']));
		Session::WriteClose();
		$this->assertEquals('OK', Session::Get(['test1', 'test2', 'test3']));
		unset($_SESSION['test1']);
		Session::Start();
		$this->assertEquals('OK', Session::Get(['test1', 'test2', 'test3']));
	}

	public function testSetOnClosedSession()
	{
		Session::Start();
		Session::Set('test', 'OK');
		$this->assertEquals('OK', Session::Get('test'));
		Session::WriteClose();
		$this->assertEquals('OK', Session::Get('test'));
		Session::Set('test', 'OK');
		$this->assertEquals('OK', Session::Get('test'));
		unset($_SESSION['test']);
		Session::Start();
		$this->assertEquals('OK', Session::Get('test'));
	}


	public function testIsSet()
	{
		$this->assertFalse(Session::IsSet('test'));
		Session::Start();
		Session::Set('test', 'OK');
		$this->assertTrue(Session::IsSet('test'));
		Session::Set(['test1', 'test2', 'test3'], 'OK');
		$this->assertTrue(Session::IsSet('test1'));
		$this->assertTrue(Session::IsSet(['test1', 'test2', 'test3']));
		Session::WriteClose();
	}

	public function testGet()
	{
		Session::Start();
		$this->assertNull(Session::Get('test'));
		Session::Set('test', 'OK');
		$this->assertEquals('OK', Session::Get('test'));
		Session::WriteClose();
	}

	public function testUnset()
	{
		Session::Start();
		Session::Unset('test');
		$this->assertFalse(Session::IsSet('test'));
		Session::Set('test', 'OK');
		$this->assertTrue(Session::IsSet('test'));
		Session::Unset('test');
		$this->assertFalse(Session::IsSet('test'));
		Session::Set('test', 'OK');
		$this->assertTrue(Session::IsSet('test'));
		Session::Set(['test1', 'test2', 'test3'], 'OK');
		$this->assertTrue(Session::IsSet(['test1', 'test2', 'test3']));
		Session::Unset(['test1', 'test2', 'test3']);
		$this->assertFalse(Session::IsSet(['test1', 'test2', 'test3']));
	}

}
