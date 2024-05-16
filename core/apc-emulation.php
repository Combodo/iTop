<?php
// Copyright (c) 2010-2024 Combodo SAS
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
 * Date: 27/09/2017
 */

/**
 * @param string $cache_type
 * @param bool $limited
 * @return array|bool
 */
function apc_cache_info($cache_type = '', $limited = false)
{
	$aInfo = array();
	$sRootCacheDir = apcFile::GetCacheFileName();
	$aInfo['cache_list'] = apcFile::GetCacheEntries($sRootCacheDir);
	return $aInfo;
}

/**
 * @param array|string $key
 * @param $var
 * @param int $ttl
 * @return array|bool
 */
function apc_store($key, $var = NULL, $ttl = 0)
{
	if (is_array($key))
	{
		$aResult = array();
		foreach($key as $sKey => $value)
		{
			$aResult[] = apcFile::StoreOneFile($sKey, $value, $ttl);
		}
		return $aResult;
	}
	return apcFile::StoreOneFile($key, $var, $ttl);
}

/**
 * @param $key string|array
 * @return mixed
 */
function apc_fetch($key)
{
	if (is_array($key)) {
		$aResult = [];
		foreach ($key as $sKey) {
			$aResult[$sKey] = apcFile::FetchOneFile($sKey);
		}

		return $aResult;
	} elseif (is_null($key)) {
		return false;
	}
	return apcFile::FetchOneFile($key);
}

/**
 * @param string $cache_type
 * @return bool
 */
function apc_clear_cache($cache_type = '')
{
	apcFile::DeleteEntry(utils::GetCachePath());
	return true;
}

/**
 * @param $key
 * @return bool|string[]
 */
function apc_delete($key)
{
	if (empty($key))
	{
		return false;
	}
	$bRet1 = apcFile::DeleteEntry(apcFile::GetCacheFileName($key));
	$bRet2 = apcFile::DeleteEntry(apcFile::GetCacheFileName('-'.$key));
	return $bRet1 || $bRet2;
}

/**
 * Checks if APCu emulation key exists
 *
 * @param string|string[] $keys A string, or an array of strings, that contain keys.
 *
 * @return bool|string[] Returns TRUE if the key exists, otherwise FALSE
 * Or if an array was passed to keys, then an array is returned that
 * contains all existing keys, or an empty array if none exist.
 * @since 3.2.0 N°7068
 */
function apc_exists($keys)
{
	if (is_array($keys)) {
		$aExistingKeys = [];
		foreach ($keys as $sKey) {
			if (apcFile::ExistsOneFile($sKey)) {
				$aExistingKeys[] = $sKey;
			}
		}
		return $aExistingKeys;
	} else {
		return apcFile::ExistsOneFile($keys);
	}
}

class apcFile
{
	// Check only once per request
	static public $aFilesByTime = null;
	static public $iFileCount = 0;

	/** Get the file name corresponding to the cache entry.
	 * If an empty key is provided, the root of the cache is returned.
	 * @param $sKey
	 * @return string
	 */
	static public function GetCacheFileName($sKey = '')
	{
		$sPath = str_replace(array(' ', '/', '\\', '.'), '-', $sKey ?? '');
		return utils::GetCachePath().'apc-emul/'.$sPath;
	}

	/** Get the list of entries from a starting folder.
	 * @param $sEntry string starting folder.
	 * @return array list of entries stored into array of key 'info'
	 */
	static public function GetCacheEntries($sEntry)
	{
		$aResult = array();
		if (is_dir($sEntry))
		{
			$aFiles = array_diff(scandir($sEntry), array('.', '..'));
			foreach($aFiles as $sFile)
			{
				$sSubFile = $sEntry.'/'.$sFile;
				$aResult = array_merge($aResult, self::GetCacheEntries($sSubFile));
			}
		}
		else
		{
			$sKey = basename($sEntry);
			if (strpos($sKey, '-') === 0)
			{
				$sKey = substr($sKey, 1);
			}
			$aResult[] = array('info' => $sKey);
		}
		return $aResult;
	}

	/** Delete one cache entry.
	 * @param $sCache
	 * @return bool true if the entry was deleted false if error occurs (like entry did not exist).
	 */
	static public function DeleteEntry($sCache)
	{
		if (is_dir($sCache))
		{
			$aFiles = array_diff(scandir($sCache), array('.', '..'));
			foreach($aFiles as $sFile)
			{
				$sSubFile = $sCache.'/'.$sFile;
				if (!self::DeleteEntry($sSubFile))
				{
					return false;
				}
			}
			if (!@rmdir($sCache))
			{
				return false;
			}
		}
		else
		{
			if (is_file($sCache))
			{
				if (!@unlink($sCache))
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}

		self::ResetFileCount();
		return true;
	}

	/**
	 * Check if cache key exists
	 * @param $sKey
	 * @return bool
	 * @since 3.2.0 N°7068
	 */
	static public function ExistsOneFile($sKey) {
		return is_file(self::GetCacheFileName('-' . $sKey)) || is_file(self::GetCacheFileName($sKey));
	}

	/** Get one cache entry content.
	 * @param $sKey
	 * @return bool|mixed
	 */
	static public function FetchOneFile($sKey)
	{
		// Try the 'TTLed' version
		$sValue = self::ReadCacheLocked(self::GetCacheFileName('-'.$sKey));
		if ($sValue === false)
		{
			$sValue = self::ReadCacheLocked(self::GetCacheFileName($sKey));
			if ($sValue === false)
			{
				return false;
			}
		}
		$oRes = @unserialize($sValue);
		return $oRes;
	}

	/** Add one cache entry.
	 * @param string $sKey
	 * @param $value
	 * @param int $iTTL time to live
	 * @return bool
	 */
	static public function StoreOneFile($sKey, $value, $iTTL)
	{
		if (empty($sKey)) {
			return false;
		}
		if (is_file(self::GetCacheFileName($sKey))) {
			@unlink(self::GetCacheFileName($sKey));
		}
		if (is_file(self::GetCacheFileName('-'.$sKey))) {
			@unlink(self::GetCacheFileName('-'.$sKey));
		}
		if ($iTTL > 0) {
			// hint for ttl management
			$sKey = '-'.$sKey;
		}

		$sFilename = self::GetCacheFileName($sKey);
		// try to create the folder
		$sDirname = dirname($sFilename);
		if (!is_dir($sDirname)) {
			if (!@mkdir($sDirname, 0755, true)) {
				return false;
			}
		}
		$bRes = !(@file_put_contents($sFilename, serialize($value), LOCK_EX) === false);
		self::AddFile($sFilename);

		return $bRes;
	}

	/** Manage the cache files when adding a new cache entry:
	 * remove older files if the mamximum is reached.
	 * @param $sNewFilename
	 */
	static protected function AddFile($sNewFilename)
	{
		if (strpos(basename($sNewFilename), '-') !== 0)
		{
			return;
		}

		$iMaxFiles = MetaModel::GetConfig()->Get('apc_cache_emulation.max_entries');
		if ($iMaxFiles == 0)
		{
			return;
		}
		if (!self::$aFilesByTime)
		{
			self::ListFilesByTime();
			self::$iFileCount = count(self::$aFilesByTime);
			if ($iMaxFiles !== 0)
			{
				asort(self::$aFilesByTime);
			}
		}
		else
		{
			self::$aFilesByTime[$sNewFilename] = time();
			self::$iFileCount++;
		}
		if (self::$iFileCount > $iMaxFiles)
		{
			$iFileNbToRemove = self::$iFileCount - $iMaxFiles;
			foreach(self::$aFilesByTime as $sFileToRemove => $iTime)
			{
				@unlink($sFileToRemove);
				if (--$iFileNbToRemove === 0)
				{
					break;
				}
			}
			self::$aFilesByTime = array_slice(self::$aFilesByTime, self::$iFileCount - $iMaxFiles, null, true);
			self::$iFileCount = $iMaxFiles;
		}
	}

	/** Get the list of files with their associated access time
	 * @param string $sCheck Directory to scan
	 */
	static protected function ListFilesByTime($sCheck = null)
	{
		if (empty($sCheck))
		{
			$sCheck = self::GetCacheFileName();
		}
		// Garbage collection
		$aFiles = array_diff(@scandir($sCheck), array('.', '..'));
		foreach($aFiles as $sFile)
		{
			$sSubFile = $sCheck.'/'.$sFile;
			if (is_dir($sSubFile))
			{
				self::ListFilesByTime($sSubFile);
			}
			else
			{
				if (strpos(basename($sSubFile), '-') === 0)
				{
					self::$aFilesByTime[$sSubFile] = @fileatime($sSubFile);
				}
			}
		}
	}

	/** Read the content of one cache file under lock protection
	 * @param $sFilename
	 * @return bool|string the content of the cache entry or false if error
	 */
	static protected function ReadCacheLocked($sFilename)
	{
		$sContent = false;
		$file = @fopen($sFilename, 'r');
		if ($file !== false) {
			if (flock($file, LOCK_SH)) {
				$sContent = file_get_contents($sFilename);
				flock($file, LOCK_UN);
			}
			fclose($file);
		}
		return $sContent;
	}

	static protected function ResetFileCount()
	{
		self::$aFilesByTime = null;
		self::$iFileCount = 0;
	}

}
