<?php

namespace Combodo\iTop\Test\UnitTest\Service\Cache;

use Combodo\iTop\Service\Cache\DataModelDependantCache;
use Combodo\iTop\Test\UnitTest\ItopTestCase;

class DataModelDependantCacheTest extends ItopTestCase
{
	private DataModelDependantCache $oCacheService;
	private string $sCacheRootDir;

	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('setup/setuputils.class.inc.php');

		$this->sCacheRootDir = self::CreateTmpdir();

		$this->oCacheService = DataModelDependantCache::GetInstance();
		$this->oCacheService->SetStorageRootDir($this->sCacheRootDir);
	}

	protected function tearDown(): void
	{
		$this->oCacheService->SetStorageRootDir(null);
		self::RecurseRmdir($this->sCacheRootDir);

		parent::tearDown();
	}

	public function testShouldStoreAndFetchVariousDataTypes(): void
    {
	    $this->oCacheService->Store('pool-A', 'key-array', ['value1', 'value2']);
	    $this->oCacheService->Store('pool-A', 'key-string', 'foo');
	    $this->oCacheService->Store('pool-A', 'key-int', 1971);

		$this->assertEquals(['value1', 'value2'], $this->oCacheService->Fetch('pool-A', 'key-array'));
	    $this->assertEquals('foo', $this->oCacheService->Fetch('pool-A', 'key-string'));
	    $this->assertEquals(1971, $this->oCacheService->Fetch('pool-A', 'key-int'));
    }

	public function testShouldNotAllowToStoreNull(): void
	{
		$this->ExpectExceptionMessage('Cannot store NULL in the cache');
		$this->oCacheService->Store('pool-A', 'key', null);
	}

	public function testShouldStoreInADirectoryRebuiltOnCompilation(): void
	{
		// Given the storage is reset to the default
		$this->oCacheService->SetStorageRootDir(null);

		// Then
		$sFilePath = $this->InvokeNonPublicMethod(DataModelDependantCache::class, 'MakeCacheFileName', $this->oCacheService, ['pool-A', 'key']);
		$this->assertStringContainsString('data/cache-', $sFilePath);
	}

	public function testPoolShouldSeparateEntriesHavingTheSameKey(): void
	{
		// Given
		$this->oCacheService->Store('pool-A', 'key', ['data-default']);
		$this->oCacheService->Store('pool-B', 'key', ['data-pool-B']);

		// Then
		$this->assertEquals(['data-default'], $this->oCacheService->Fetch('pool-A', 'key'));
		$this->assertEquals(['data-pool-B'], $this->oCacheService->Fetch('pool-B', 'key'));
		$this->assertEquals(null, $this->oCacheService->Fetch('pool-C-unknown', 'key'));
	}
	public function testPoolsShouldBeVisibleInThePath(): void
	{
		$sFilePath = $this->InvokeNonPublicMethod(DataModelDependantCache::class, 'MakeCacheFileName', $this->oCacheService, ['pool-B', 'key']);
		$this->assertStringContainsString('pool-B', $sFilePath);
	}

	public function testFetchShouldReturnNullForNonExistingKey(): void
	{
		$this->assertNull($this->oCacheService->Fetch('pool-A', 'non-existing-key'));
	}

	public function testAnUnknownPoolShouldFailSilently(): void
	{
		$this->assertNull($this->oCacheService->Fetch('unknown-pool', 'non-existing-key'));
	}

	public function testDeleteItemAndHasEntry()
	{
		// Given an empy cache
		// Then
		$this->assertFalse($this->oCacheService->HasEntry('pool-A', 'key'), 'HasEntry should return false for non-existing key');

		// When
		$this->oCacheService->Store('pool-A', 'key', 'some data...');

		// Then
		$this->assertTrue($this->oCacheService->HasEntry('pool-A', 'key'), 'HasEntry should return true for newly created key');

		// When
		$this->oCacheService->DeleteItem('pool-A', 'key');

		// Then
		$this->assertFalse($this->oCacheService->HasEntry('pool-A', 'key'), 'HasEntry should return true for a removed key');
	}

	public function testDeleteItemShouldPreserveOtherEntries()
	{
		// Given
		$this->oCacheService->Store('pool-A', 'key', 'some data...');
		$this->oCacheService->Store('pool-A', 'key2', 'some data...');

		// Then
		$this->oCacheService->DeleteItem('pool-A', 'key');

		// When
		$this->assertTrue($this->oCacheService->HasEntry('pool-A', 'key2'));
	}

	public function testDeleteItemShouldPreserveHomonymsFromDifferentPools()
	{
		// Given
		$this->oCacheService->Store('pool-A', 'key', 'some data...');
		$this->oCacheService->Store('pool-B', 'key', 'some data...');

		// When
		$this->oCacheService->DeleteItem('pool-B', 'key');

		// Then
		$this->assertTrue($this->oCacheService->HasEntry('pool-A', 'key'), 'DeleteItem should not have altered the pool "default"');
	}

	public function testClearShouldRemoveAllEntriesFromTheCurrentPool()
	{
		// Given
		$this->oCacheService->Store('pool-A', 'key', 'some data...');
		$this->oCacheService->Store('pool-A', 'key2', 'some data...');
		$this->oCacheService->Store('pool-B', 'key', 'some data...');

		// When
		$this->oCacheService->Clear('pool-A');

		// Then
		$this->assertFalse($this->oCacheService->HasEntry('pool-A', 'key'), 'DeleteItem should remove all entries from the current pool');
		$this->assertFalse($this->oCacheService->HasEntry('pool-A', 'key2'), 'DeleteItem should remove all entries from the current pool');
		$this->assertTrue($this->oCacheService->HasEntry('pool-B', 'key'), 'DeleteItem should not alter entries from other pools');
	}

	public function testGetEntryModificationTime()
	{
		// Given an entry created at a specific time
		$this->oCacheService->Store('pool-A', 'key', 'some data...');
		$iRefTime = time();
		$sFilePath = $this->InvokeNonPublicMethod(DataModelDependantCache::class, 'MakeCacheFileName', $this->oCacheService, ['pool-A', 'key']);
		touch($sFilePath, $iRefTime);

		// Then
		$this->assertEquals($iRefTime, $this->oCacheService->GetEntryModificationTime('pool-A', 'key'), 'GetEntryModificationTime should return the modification time of the cache file');
		$this->assertEquals(null, $this->oCacheService->GetEntryModificationTime('pool-A', 'non-existing-key'), 'GetEntryModificationTime should return null for an invalid key');
	}
	public function testKeyUndesiredCharactersShouldBeTransformedToUnderscore()
	{
		$sUglyKey = 'key with ugly characters:\{&"#@ç^²/,;[(|🤔';
		$sFilePath = $this->InvokeNonPublicMethod(DataModelDependantCache::class, 'MakeCacheFileName', $this->oCacheService, ['pool-A', $sUglyKey]);
		$this->assertEquals('key_with_ugly_characters______________________.php', basename($sFilePath));
	}
}
