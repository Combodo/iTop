<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
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
		require_once 'iApplicationObjectExtension/ObjectModifyExtension.php';

		// Add ObjectModifyExtension to the plugin list
		$this->InvokeNonPublicStaticMethod(MetaModel::class, 'InitExtensions', []);
		// Instantiate the new object
		$this->InvokeNonPublicStaticMethod(PluginManager::class, 'ResetPlugins', []);
		ObjectModifyExtension::SetCallBack([ApplicationObjectExtensionTest::class, 'IncrementCallCount']);
	}

	public function tearDown(): void
	{
		ObjectModifyExtension::SetModifications('Person', 'name', 0);
		ObjectModifyExtension::SetAlwaysChanged(false);
		ObjectModifyExtension::SetCallBack(null);
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
		ObjectModifyExtension::SetModifications('Person', 'name', 1);
		self::ResetCallCount();
		$oPerson->DBUpdate();
		$this->assertEquals(1, self::$iCalls);
	}

	public function testUpdateReentranceProtection()
	{
		$oPerson = $this->CreatePerson(1);

		// Check that loop limit is 10
		$i = 15;
		self::ResetCallCount();
		ObjectModifyExtension::SetModifications('Person', 'name', $i);
		$oPerson->Set('first_name', 'testUpdateReentranceProtection');
		$oPerson->DBUpdate();
		$this->assertEquals(10, self::$iCalls);
	}

	public function testModificationsLost()
	{
		self::ResetCallCount();
		$oPerson = $this->CreatePerson(1);
		$oPerson->Set('first_name', 'testUpdateReentranceProtection');

		ObjectModifyExtension::SetModifications('Person', 'name', 1);
		ObjectModifyExtension::SetAlwaysChanged(true);
		$oPerson->DBUpdate();
		$this->assertEquals(1, self::$iCalls);
	}

}