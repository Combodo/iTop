<?php
/**
 * Copyright (C) 2013-2023 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Test\UnitTest\Application;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;
use privUITransactionFile;
use UserRights;

/**
 * @covers utils
 * @group sampleDataNeeded
 * @group defaultProfiles
 */
class privUITransactionFileTest extends ItopDataTestCase
{
	/** @var int ID of the "support agent" pofile in the sample data */
	const SAMPLE_DATA_SUPPORT_PROFILE_ID = 5;
	const USER1_TEST_LOGIN = 'user1_support_test_privUITransaction';
	const USER2_TEST_LOGIN = 'user2_support_test_privUITransaction';

	/**
	 * @dataProvider cleanupOldTransactionsProvider
	 */
	public function testCleanupOldTransactions($iCleanableCreated, $iPreservableCreated, $sCleanablePrefix, $sPreservablePrefix)
	{
		MetaModel::GetConfig()->Set('transactions_gc_threshold', 100);

		$iBaseLimit = time() - 24*3600; //24h

		$sBaseDir = sys_get_temp_dir();
		$sDir = "$sBaseDir/privUITransactionFileTest/cleanupOldTransactions";
		if (is_dir($sDir)) {
			$this->rm($sDir);
		}
		mkdir("$sDir", 0777, true);

		for ($i = 0; $i < $iCleanableCreated; $i++) {
			touch("$sDir/{$sCleanablePrefix}$i", $iBaseLimit - 10*60);
		}
		for ($i = 0; $i < $iPreservableCreated; $i++) {
			touch("$sDir/{$sPreservablePrefix}$i", $iBaseLimit + 10*60);
		}

		$iCleanableCount = count(glob("$sDir/{$sCleanablePrefix}*"));
		$iPreservableCount = count(glob("$sDir/{$sPreservablePrefix}*"));
		$this->assertEquals($iCleanableCreated, $iCleanableCount);
		$this->assertEquals($iPreservableCreated, $iPreservableCount);

		$aArgs = [
			'sTransactionDir' => "$sDir",
		];
		$oprivUITransactionFile = new privUITransactionFile();
		$this->InvokeNonPublicMethod(get_class($oprivUITransactionFile), 'CleanupOldTransactions', $oprivUITransactionFile, $aArgs);

		$iCleanableCount = count(glob("$sDir/{$sCleanablePrefix}*"));
		$iPreservableCount = count(glob("$sDir/{$sPreservablePrefix}*"));
		$this->assertEquals(0, $iCleanableCount);
		$this->assertEquals($iPreservableCreated, $iPreservableCount);
	}

	public function cleanupOldTransactionsProvider()
	{
		$iBaseLimit = time() - 60 * 10; //ten minutes ago

		$sBaseDir = sys_get_temp_dir();
		$sDir = "$sBaseDir/privUITransactionFileTest/cleanupOldTransactions";

		return  [
			'linux - no content' => [
				'iCleanableCreated' => 0,
				'iPreservableCreated' => 0,
				'sCleanablePrefix' => 'cleanable-',
				'sPreservablePrefix' => 'preservable-',
			],
			'linux - cleanable content' => [
				'iCleanableCreated' => 2,
				'iPreservableCreated' => 0,
				'sCleanablePrefix' => 'cleanable-',
				'sPreservablePrefix' => 'preservable-',
			],
			'linux - preseved content' => [
				'iCleanableCreated' => 0,
				'iPreservableCreated' => 2,
				'sCleanablePrefix' => 'cleanable-',
				'sPreservablePrefix' => 'preservable-',
			],
			'win - no content' => [
				'iCleanableCreated' => 0,
				'iPreservableCreated' => 0,
				'sCleanablePrefix' => 'cle',
				'sPreservablePrefix' => 'pre',
			],
			'win - cleanable content' => [
				'iCleanableCreated' => 2,
				'iPreservableCreated' => 0,
				'sCleanablePrefix' => 'cle',
				'sPreservablePrefix' => 'pre',
			],
			'win - preseved content' => [
				'iCleanableCreated' => 0,
				'iPreservableCreated' => 2,
				'sCleanablePrefix' => 'cle',
				'sPreservablePrefix' => 'pre',
			],
		];
	}

	public function rm($sDir) {
		$aFiles = array_diff(scandir($sDir), ['.','..']);
		foreach ($aFiles as $sFile) {
			if ((is_dir("$sDir/$sFile"))) {
				$this->rm("$sDir/$sFile");
			} else {
				unlink("$sDir/$sFile");
			}
		}
		return rmdir($sDir);
	}

	const USER_TEST_LOGIN = 'support_test_privUITransaction';

	/**
	 * @throws \SecurityException
	 * @uses self::SAMPLE_DATA_SUPPORT_PROFILE_ID
	 * @uses self::USER1_TEST_LOGIN
	 * @uses self::USER2_TEST_LOGIN
	 */
	public function testIsTransactionValid()
	{
		$this->CreateUser(static::USER1_TEST_LOGIN, self::SAMPLE_DATA_SUPPORT_PROFILE_ID);
		$this->CreateUser(static::USER2_TEST_LOGIN, self::SAMPLE_DATA_SUPPORT_PROFILE_ID);

		// created users aren't admin, so each one can't see the other (\UserRights::GetSelectFilter)
		// If calling \UserRights::Login(user1) then \UserRights::Login(user2) we won't be able to load user2 !
		// As now we are in the admin context, we are calling FindUser() directly so that user objects will be saved in the UserRights cache !
		// we can skip doing this for user1 as the first \UserRights::Login call will initialize the UserRights cache !
		$this->InvokeNonPublicStaticMethod(UserRights::class, 'FindUser', [self::USER2_TEST_LOGIN]);

		// create token in the user1 context
		$bUser1Login1 = UserRights::Login(self::USER1_TEST_LOGIN);
		$this->assertTrue($bUser1Login1, 'Login with user1 throw an error');
		$sTransactionIdUserSupport = privUITransactionFile::GetNewTransactionId();
		$bResult = privUITransactionFile::IsTransactionValid($sTransactionIdUserSupport, false);
		$this->assertTrue($bResult, 'Token created by support user must be valid in the support user context');

		// test token in the user2 context
		$bUser2Login = UserRights::Login(self::USER2_TEST_LOGIN);
		$this->assertTrue($bUser2Login, 'Login with user2 throw an error');
		$bResult = privUITransactionFile::IsTransactionValid($sTransactionIdUserSupport, false);
		$this->assertFalse($bResult, 'Token created by support user must be invalid in the admin user context');
		$bResult = privUITransactionFile::RemoveTransaction($sTransactionIdUserSupport);
		$this->assertFalse($bResult, 'Token created by support user cannot be removed in the admin user context');

		// test other methods in the user1 context
		$bUser1Login2 = UserRights::Login(self::USER1_TEST_LOGIN);
		$this->assertTrue($bUser1Login2, 'Login with user1 throw an error');
		$bResult = privUITransactionFile::RemoveTransaction($sTransactionIdUserSupport);
		$this->assertTrue($bResult, 'Token created by support user must be removed in the support user context');

		// test when no user logged (combodo-unauthenticated-form module for example)
		UserRights::Logoff();
		$sTransactionIdUnauthenticatedUser = privUITransactionFile::GetNewTransactionId();
		$bResult = privUITransactionFile::IsTransactionValid($sTransactionIdUnauthenticatedUser, false);
		$this->assertTrue($bResult, 'Token created by unauthenticated user must be valid when no user logged');
		$bResult = privUITransactionFile::RemoveTransaction($sTransactionIdUnauthenticatedUser);
		$this->assertTrue($bResult, 'Token created by unauthenticated user must be removed when no user logged');
	}
}
