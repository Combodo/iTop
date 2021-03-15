<?php
// Copyright (c) 2010-2021 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
//

/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 31/10/2017
 * Time: 14:10
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use PHPUnit\Framework\TestCase;

define('UNIT_MAX_CACHE_FILES', 10);


/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class apcEmulationTest extends ItopTestCase
{

	protected function setUp()
	{
		parent::setUp();
		require_once(APPROOT.'core/apc-emulation.php');
		require_once 'mockApcEmulation.incphp';
		apc_clear_cache();
	}

	public function tearDown()
	{
		apc_clear_cache();
	}

	public function testBasic()
	{
		$this->assertTrue(apc_store('test-ttl', 'This is a test with TTL', 100));
		$this->assertTrue(apc_store('test-nottl', 'This is a test without TTL'));

		$this->assertEquals('This is a test with TTL', apc_fetch('test-ttl'));
		$this->assertEquals('This is a test without TTL', apc_fetch('test-nottl'));
	}

	public function testMultiple()
	{
		for($i = 0; $i < UNIT_MAX_CACHE_FILES; $i++)
		{
			$this->assertTrue(apc_store('testMultiple'.$i, 'This is a test', 100));
		}
		$aInfo = apc_cache_info();
		$this->assertEquals(UNIT_MAX_CACHE_FILES, count($aInfo['cache_list']));
	}

	public function testNumberOfFilesTTL()
	{
		for($i = 0; $i < 2 * UNIT_MAX_CACHE_FILES; $i++)
		{
			$this->assertTrue(apc_store('testNumberOfFilesTTL'.$i, 'This is a test', 100));
		}
		$aInfo = apc_cache_info();
		$this->assertEquals(UNIT_MAX_CACHE_FILES, count($aInfo['cache_list']));

		$this->assertFalse(apc_fetch('testNumberOfFilesTTL0'));
	}

	public function testNumberOfFilesNoTTL()
	{
		for($i = 0; $i < 2 * UNIT_MAX_CACHE_FILES; $i++)
		{
			$this->assertTrue(apc_store('testNumberOfFilesNoTTL'.$i, 'This is a test'));
		}
		$aInfo = apc_cache_info();
		$this->assertEquals(2 * UNIT_MAX_CACHE_FILES, count($aInfo['cache_list']));

		$this->assertTrue(apc_fetch('testNumberOfFilesNoTTL0') !== false);
	}

	public function testArray()
	{
		$aStoredEntries = array();
		$aFetchedEntries = array();
		for($i = 0; $i < UNIT_MAX_CACHE_FILES; $i++)
		{
			$sKey = 'testArray'.$i;
			$aStoredEntries[$sKey] = 'This is a test ARRAY'.rand();
			$aFetchedEntries[] = $sKey;
		}
		$aResStore = apc_store($aStoredEntries);
		$this->assertEquals(UNIT_MAX_CACHE_FILES, count($aResStore));
		foreach($aResStore as $bValue)
		{
			$this->assertTrue($bValue);
		}

		$aInfo = apc_cache_info();
		$this->assertEquals(UNIT_MAX_CACHE_FILES, count($aInfo['cache_list']));

		$aResFetch = apc_fetch($aFetchedEntries);
		$this->assertEquals(UNIT_MAX_CACHE_FILES, count($aResFetch));

		foreach($aResFetch as $sKey => $sValue)
		{
			$this->assertEquals($aStoredEntries[$sKey], $sValue);
		}
	}


	public function testSanity()
	{
		$this->assertTrue(apc_store('testSanity', null, 100));
		$this->assertTrue(is_null(apc_fetch('testSanity')));

		$this->assertFalse(apc_store(null, 'testSanity', 100));
		$this->assertFalse(apc_fetch(null));

		$this->assertTrue(apc_store('testSanity2', null));
		$this->assertFalse(apc_store(null, 'testSanity2'));
		$this->assertFalse(apc_store('', 'testSanity2'));

		$this->assertFalse(apc_delete(null));
		$this->assertFalse(apc_delete(''));
	}

	public function testDelete()
	{
		$this->assertTrue(apc_store('test-ttl', 'This is a test with TTL', 100));
		$this->assertTrue(apc_store('test-nottl', 'This is a test without TTL'));

		$this->assertTrue(apc_delete('test-ttl'));
		$this->assertFalse(apc_delete('test-ttl'));
		$this->assertTrue(apc_delete('test-nottl'));

		$this->assertFalse(apc_fetch('test-ttl'));
		$this->assertFalse(apc_fetch('test-nottl'));
	}

	public function testReuseSameKey()
	{
		// first use of the key
		$this->assertTrue(apc_store('testReuseSameKey', 'This is a test with TTL', 100));
		$this->assertEquals('This is a test with TTL', apc_fetch('testReuseSameKey'));
		// same key but no ttl
		$this->assertTrue(apc_store('testReuseSameKey', 'This is a test without TTL'));
		$this->assertEquals('This is a test without TTL', apc_fetch('testReuseSameKey'));
		// same key with ttl
		$this->assertTrue(apc_store('testReuseSameKey', 'This is a test with TTL', 100));
		$this->assertEquals('This is a test with TTL', apc_fetch('testReuseSameKey'));
		// same key with ttl but other content
		$this->assertTrue(apc_store('testReuseSameKey', 'This is a test', 100));
		$this->assertEquals('This is a test', apc_fetch('testReuseSameKey'));
		// remove entry
		$this->assertTrue(apc_delete('testReuseSameKey'));
		// check entry is removed
		$this->assertFalse(apc_fetch('testReuseSameKey'));
	}

	public function testHuge()
	{
		$ilen = 20000000;
		$sContent = str_pad(' TEST ', $ilen, "-=", STR_PAD_BOTH);
		for($i = 0; $i < UNIT_MAX_CACHE_FILES; $i++)
		{
			$this->assertTrue(apc_store('testHuge'.$i, $sContent, 100));
		}
		$aInfo = apc_cache_info();
		$this->assertEquals(UNIT_MAX_CACHE_FILES, count($aInfo['cache_list']));

		for($i = 0; $i < UNIT_MAX_CACHE_FILES; $i++)
		{
			$this->assertEquals($ilen, strlen(apc_fetch('testHuge'.$i)));
		}
	}

}