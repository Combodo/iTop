<?php
// Copyright (c) 2010-2017 Combodo SARL
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
			$aResult[] = apc_emul_store_unit($sKey, $value, $ttl);
		}
		return $aResult;
	}
	return apc_emul_store_unit($key, $var, $ttl);
}

/**
 * @param string $sKey
 * @param $value
 * @param int $iTTL time to live
 * @return bool
 */
function apc_emul_store_unit($sKey, $value, $iTTL)
{
	if ($iTTL > 0)
	{
		// hint for ttl management
		$sKey = '-'.$sKey;
	}

	$sFilename = apc_emul_get_cache_filename($sKey);
	// try to create the folder
	$sDirname = dirname($sFilename);
	if (!file_exists($sDirname))
	{
		if (!@mkdir($sDirname, 0755, true))
		{
			return false;
		}
	}
	$bRes = !(@file_put_contents($sFilename, serialize($value), LOCK_EX) === false);
	apc_emul_manage_new_entry($sFilename);
	return $bRes;
}

/**
 * @param $key string|array
 * @return mixed
 */
function apc_fetch($key)
{
	if (is_array($key))
	{
		$aResult = array();
		foreach($key as $sKey)
		{
			$aResult[$sKey] = apc_emul_fetch_unit($sKey);
		}
		return $aResult;
	}
	return apc_emul_fetch_unit($key);
}

/**
 * @param $sKey
 * @return bool|mixed
 */
function apc_emul_fetch_unit($sKey)
{
	// Try the 'TTLed' version
	$sValue = apc_emul_readcache_locked(apc_emul_get_cache_filename('-'.$sKey));
	if ($sValue === false)
	{
		$sValue = apc_emul_readcache_locked(apc_emul_get_cache_filename($sKey));
		if ($sValue === false)
		{
			return false;
		}
	}
	$oRes = @unserialize($sValue);
	return $oRes;
}

function apc_emul_readcache_locked($sFilename)
{
	$file = @fopen($sFilename, 'r');
	if ($file === false)
	{
		return false;
	}
	flock($file, LOCK_SH);
	$sContent = @fread($file, @filesize($sFilename));
	flock($file, LOCK_UN);
	fclose($file);
	return $sContent;
}

/**
 * @param string $cache_type
 * @return bool
 */
function apc_clear_cache($cache_type = '')
{
	$sRootCacheDir = apc_emul_get_cache_filename('');
	apc_emul_delete_entry($sRootCacheDir);
	return true;
}

function apc_emul_delete_entry($sCache)
{
	if (is_dir($sCache))
	{
		$aFiles = array_diff(scandir($sCache), array('.', '..'));
		foreach($aFiles as $sFile)
		{
			$sSubFile = $sCache.'/'.$sFile;
			if (!apc_emul_delete_entry($sSubFile))
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
		if (!@unlink($sCache))
		{
			return false;
		}
	}
	return true;
}

/**
 * @param $key
 * @return bool|string[]
 */
function apc_delete($key)
{
	return apc_emul_delete_entry(apc_emul_get_cache_filename($key));
}


function apc_emul_get_cache_filename($sKey)
{
	$sPath = str_replace(array(' ', '/', '\\', '.'), '-', $sKey);
	return utils::GetCachePath().'apc-emul/'.$sPath;
}


/** Manage the cache files when a new cache entry is added
 * @param string $sNewFilename new cache file added
 */
function apc_emul_manage_new_entry($sNewFilename)
{
	// Check only once per request
	static $aFilesByTime = null;
	static $iFileCount = 0;
	$iMaxFiles = MetaModel::GetConfig()->Get('apc_cache_emulation.max_entries');
	if (!$aFilesByTime)
	{
		$sRootCacheDir = apc_emul_get_cache_filename('');
		$aFilesByTime = apc_emul_list_files_time($sRootCacheDir);
		$iFileCount = count($aFilesByTime);
		if ($iMaxFiles !== 0)
		{
			asort($aFilesByTime);
		}
	}
	else
	{
		$aFilesByTime[$sNewFilename] = time();
		$iFileCount++;
	}
	if (($iMaxFiles !== 0) && ($iFileCount > $iMaxFiles))
	{
		$iFileNbToRemove = $iFileCount - $iMaxFiles;
		foreach($aFilesByTime as $sFileToRemove => $iTime)
		{
			@unlink($sFileToRemove);
			if ($iFileNbToRemove-- === 0)
			{
				break;
			}
		}
		$aFilesByTime = array_slice($aFilesByTime, $iFileCount - $iMaxFiles, null, true);
		$iFileCount = $iMaxFiles;
	}
}

/** Get the list of files with their associated access time
 * @param string $sCheck Directory to scan
 * @param array $aFilesByTime used by recursion
 * @return array
 */
function apc_emul_list_files_time($sCheck, &$aFilesByTime = array())
{
	// Garbage collection
	$aFiles = array_diff(@scandir($sCheck), array('.', '..'));
	foreach($aFiles as $sFile)
	{
		$sSubFile = $sCheck.'/'.$sFile;
		if (is_dir($sSubFile))
		{
			apc_emul_list_files_time($sSubFile, $aFilesByTime);
		}
		else
		{
			$iTime = apc_emul_get_file_time($sSubFile);
			if ($iTime !== false)
			{
				$aFilesByTime[$sSubFile] = $iTime;
			}
		}
	}
	return $aFilesByTime;
}

/** Get the file access time if TTL is managed
 * @param string $sFilename
 * @return bool|int returns the file atime or false if not relevant
 */
function apc_emul_get_file_time($sFilename)
{
	if (strpos(basename($sFilename), '-') === 0)
	{
		return @fileatime($sFilename);
	}
	return false;
}
