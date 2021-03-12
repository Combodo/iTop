<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
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

use Combodo\iTop\Test\UnitTest\ItopTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 * @covers utils
 */
class privUITransactionFileTest extends ItopTestCase
{
	public function setUp()
	{
		parent::setUp();
		require_once(APPROOT.'/application/startup.inc.php');
//		require_once(APPROOT.'application/utils.inc.php');
	}

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
}
