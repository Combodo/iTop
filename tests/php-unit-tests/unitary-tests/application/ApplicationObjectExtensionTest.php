<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class ApplicationObjectExtensionTest extends \Combodo\iTop\Test\UnitTest\ItopDataTestCase
{
	const CREATE_TEST_ORG = true;

	// Count the calls by name
	private static array $aCalls = [];
	private static int $iCalls = 0;

	protected function setUp(): void
	{
		parent::setUp();

		$this->RequireOnceUnitTestFile('iApplicationObjectExtension/MockApplicationObjectExtensionForTest1.php');
		$this->ResetApplicationObjectExtensions();
		// Count all the calls to this object
		MockApplicationObjectExtensionForTest1::SetCallBack([ApplicationObjectExtensionTest::class, 'IncrementCallCount']);
	}

	public function tearDown(): void
	{
		MockApplicationObjectExtensionForTest1::SetModifications('Person', 'name', 0);
		MockApplicationObjectExtensionForTest1::SetCallBack(null);
		parent::tearDown();
	}

	public static function IncrementCallCount(string $sOrigin)
	{
		self::$aCalls[$sOrigin] = (self::$aCalls[$sOrigin] ?? 0) + 1;
		self::$iCalls++;
	}

	public static function ResetCallCount()
	{
		self::$aCalls = [];
		self::$iCalls = 0;
	}

	public function testExtensionCalled()
	{
		// Check that extension is called
		$oPerson = $this->CreatePerson(1);
		$oPerson->Set('first_name', 'testUpdateReentranceProtection');
		MockApplicationObjectExtensionForTest1::SetModifications('Person', 'name', 1);
		self::ResetCallCount();
		$oPerson->DBUpdate();
		// Called twice, the first call will provoke the DBUpdate and call again the object extension
		$this->assertEquals(2, self::$iCalls);
	}

	public function testUpdateReentranceProtection()
	{
		$oPerson = $this->CreatePerson(1);

		// Check that loop limit is 10
		$i = 15;
		self::ResetCallCount();
		MockApplicationObjectExtensionForTest1::SetModifications('Person', 'name', $i);
		$oPerson->Set('first_name', 'testUpdateReentranceProtection');
		$oPerson->DBUpdate();
		$this->assertEquals(10, self::$iCalls);
	}

	public function testModificationsOnUpdate()
	{
		$oPerson = $this->CreatePerson(1);
		$oPerson->Set('first_name', 'testUpdateReentranceProtection');

		self::ResetCallCount();
		MockApplicationObjectExtensionForTest1::SetModifications('Person', 'name', 1);
		$oPerson->DBUpdate();
		$this->assertEquals(2, self::$iCalls);
	}

	public function testModificationsOnInsert()
	{
		self::ResetCallCount();
		MockApplicationObjectExtensionForTest1::SetModifications('Person', 'name', 1);
		$oPerson = $this->CreatePerson(1);
		$this->assertEquals(2, self::$iCalls);
	}


	public function testModificationsOnInsertWith2Extensions()
	{
		self::ResetCallCount();
		$this->RequireOnceUnitTestFile('iApplicationObjectExtension/MockApplicationObjectExtensionForTest2.php');
		$this->ResetApplicationObjectExtensions();
		// Count all the calls to this object
		MockApplicationObjectExtensionForTest2::SetCallBack([ApplicationObjectExtensionTest::class, 'IncrementCallCount']);

		MockApplicationObjectExtensionForTest1::SetModifications('Person', 'name', 2);
		MockApplicationObjectExtensionForTest2::SetModifications('Person', 'first_name', 2);
		$oPerson = $this->CreatePerson(1);
		$this->assertEquals(6, self::$iCalls);
	}

}