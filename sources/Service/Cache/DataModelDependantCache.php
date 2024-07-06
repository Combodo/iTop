<?php

namespace Combodo\iTop\Service\Cache;

use Exception;
use SetupUtils;
use utils;

/**
 * A simple cache service that stores data in files
 * - No TTL: automatically invalidated when iTop is recompiled
 * - Concurrent access safe
 *
 * @since 3.2.0
 * @experimental The API may change in future versions
 * @internal DO NOT use in extensions until it is declared stable
 */
class DataModelDependantCache
{
	private static DataModelDependantCache $oInstance;

	public static function GetInstance(): DataModelDependantCache
	{
		if (!isset(self::$oInstance))
		{
			self::$oInstance = new DataModelDependantCache(utils::GetCachePath());
		}
		return self::$oInstance;
	}

	private ?string $sStorageRootDir; // Nullable for test purposes

	private function __construct($sStorageRootDir)
	{
		$this->sStorageRootDir = $sStorageRootDir;
	}

	/**
	 * @param string $sPool
	 * @param string $sKey Any characters allowed, special characters equivalent to '_'
	 * @param mixed $value Any primitive type, immutable object, or array of such. NULL is not allowed.
	 * @param array $aMoreInfo Key-value pairs to store additional information about the cache entry
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function Store(string $sPool, string $sKey, mixed $value, array $aMoreInfo = []): void
	{
		if(is_null($value)) {
			// NULL cannot be stored as it collides with Fetch() returning NULL when the key does not exist
			throw new Exception('Cannot store NULL in the cache');
		}
		$sCacheFileName = $this->MakeCacheFileName($sPool, $sKey);
		SetupUtils::builddir(dirname($sCacheFileName));

		$sMoreInfo = '';
		foreach ($aMoreInfo as $sKey => $sValue) {
			$sMoreInfo .= "\n// $sKey: $sValue";
		}
		$sCacheContent = "<?php $sMoreInfo\nreturn ".var_export($value, true).";";
		file_put_contents($sCacheFileName, $sCacheContent, LOCK_EX);
	}

	/**
	 * Fetch the cached values for a given key, in the current pool
	 *
	 * @param string $sPool
	 * @param string $sKey
	 *
	 * @return mixed|null returns null if the key does not exist in the current pool
	 */
	public function Fetch(string $sPool, string $sKey): mixed
	{
		$sCacheFileName = $this->MakeCacheFileName($sPool, $sKey);
		if (!is_file($sCacheFileName)) return null;
		return include $sCacheFileName;
	}

	/**
	 * @param string $sPool
	 * @param string $sKey
	 *
	 * @return bool
	 */
	public function HasEntry(string $sPool, string $sKey): bool
	{
		return file_exists($this->MakeCacheFileName($sPool, $sKey));
	}

	/**
	 * Get the last modification time of a cache entry
	 *
	 * @param string $sPool
	 * @param string $sKey
	 *
	 * @return int|null Unix timestamp or null if the entry doesn't exist
	 */
	public function GetEntryModificationTime(string $sPool, string $sKey): int|null
	{
		$sCacheFileName = $this->MakeCacheFileName($sPool, $sKey);
		if (!is_file($sCacheFileName)) return null;
		return filemtime($sCacheFileName);
	}
	/**
	 * Remove an entry from the current pool
	 *
	 * @param string $sPool
	 * @param string $sKey
	 *
	 * @return void
	 */
	public function DeleteItem(string $sPool, string $sKey): void
	{
		$sCacheFileName = $this->MakeCacheFileName($sPool, $sKey);
		if (is_file($sCacheFileName)) {
			unlink($sCacheFileName);
		}
	}

	/**
	 * Remove all entries from the current pool
	 *
	 * @param string $sPool
	 *
	 * @return void
	 * @throws Exception
	 */
	public function Clear(string $sPool): void
	{
		$sPoolDir = $this->MakePoolDirPath($sPool);
		if (is_dir($sPoolDir)) {
			SetupUtils::tidydir($sPoolDir);
		}
	}

	private function MakeCacheFileName(string $sPool, string $sKey): string
	{
		// Replace all characters that are not alphanumeric by '_'
		$sKey = preg_replace('/[^a-zA-Z0-9]/', '_', $sKey);

		return $this->MakePoolDirPath($sPool).$sKey.'.php';
	}

	private function MakePoolDirPath(string $sPool): string
	{
		return $this->GetStorageRootDir()."/$sPool/";
	}

	/** Overridable for testing purposes */
	protected function GetStorageRootDir(): string
	{
		// Could be forced by tests
		return $this->sStorageRootDir ?? utils::GetCachePath();
	}
}