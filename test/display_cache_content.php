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

/**
 * Date: 06/10/2017
 */

require_once('../approot.inc.php');
require_once(APPROOT.'application/startup.inc.php');


$sEnvironment = MetaModel::GetEnvironmentId();
$aEntries = array();
$aCacheUserData = apc_cache_info_compat();
if (is_array($aCacheUserData) && isset($aCacheUserData['cache_list']))
{
	$sPrefix = 'itop-'.$sEnvironment.'-query-cache-';

	foreach($aCacheUserData['cache_list'] as $i => $aEntry)
	{
		$sEntryKey = array_key_exists('info', $aEntry) ? $aEntry['info'] : $aEntry['key'];
		if (strpos($sEntryKey, $sPrefix) === 0)
		{
			$aEntries[] = $sEntryKey;
		}
	}
}

echo "<pre>";

if (empty($aEntries))
{
	echo "No Data";
	return;
}

$sKey = $aEntries[0];
$result = apc_fetch($sKey);
if (!is_object($result))
{
	return;
}
$oSQLQuery = $result;

echo "NB Tables before;NB Tables after;";
foreach($oSQLQuery->m_aContextData as $sField => $oValue)
{
	echo $sField.';';
}
echo "\n";

sort($aEntries);

foreach($aEntries as $sKey)
{
	$result = apc_fetch($sKey);
	if (is_object($result))
	{
		$oSQLQuery = $result;
		if (isset($oSQLQuery->m_aContextData))
		{
			echo $oSQLQuery->m_iOriginalTableCount.";".$oSQLQuery->CountTables().';';
			foreach($oSQLQuery->m_aContextData as $oValue)
			{
				if (is_array($oValue))
				{
					$sVal = json_encode($oValue);
				}
				else
				{
					if (empty($oValue))
					{
						$sVal = '';
					}
					else
					{
						$sVal = $oValue;
					}
				}
				echo $sVal.';';
			}
			echo "\n";
		}
	}
}

echo "</pre>";

