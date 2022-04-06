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
 * Date: 06/10/2017
 */


require_once('../approot.inc.php');
require_once(APPROOT.'application/startup.inc.php');

\LoginWebPage::DoLogin(true);

$sOQLFile = APPROOT.'log/oql_records.txt';
$sTestFile = APPROOT.'test/core/oql_records.php';

$oTestHandle = @fopen($sTestFile, "w");

@fwrite($oTestHandle, "<?php\n\n");

$aFoundOQLs = array();
$iCount = 0;
$iRead = 0;

$oOQLHandle = @fopen($sOQLFile, "r");
if ($oOQLHandle) {
	while (($sBuffer = fgets($oOQLHandle)) !== false) {
		$iRead++;
		$aRecord = unserialize($sBuffer);

		$sOQL = $aRecord['oql'];

		$sChecksum = md5($sBuffer);
		if (isset($aFoundOQLs[$sChecksum])) { continue; }
		$aFoundOQLs[$sChecksum] = true;

		$iCount++;
		$sOrderBy = ConvertArray($aRecord['order_by']);
		$sAttToLoad = ConvertArray($aRecord['att_to_load']);
		$iLimitCount = $aRecord['limit_count'];
		$iLimitStart = $aRecord['limit_start'];

		// $sOQL, $aOrderBy, $aArgs, $aAttToLoad, $aExtendedDataSpec, $iLimitCount, $iLimitStart

		$sLine = "\$aData[\"SELECT $iCount\"] = array(\"$sOQL\", $sOrderBy, array(), $sAttToLoad, array(), $iLimitCount, $iLimitStart);\n";
		@fwrite($oTestHandle, $sLine);
	}
	if (!feof($oOQLHandle)) {
		echo "Erreur: fgets() a échoué\n";
	}
	@fclose($oOQLHandle);
}
@fwrite($oTestHandle, "\n");

@fclose($oTestHandle);

echo "File '$sTestFile' generated with $iCount entries (from $iRead captured OQL).\n";


/// Group by


$sOQLFile = APPROOT.'log/oql_group_by_records.txt';
$sTestFile = APPROOT.'test/core/oql_group_by_records.php';

$oTestHandle = @fopen($sTestFile, "w");

@fwrite($oTestHandle, "<?php\n\n");

$aFoundOQLs = array();
$iCount = 1000;
$iRead = 0;

$oOQLHandle = @fopen($sOQLFile, "r");
if ($oOQLHandle) {
	while (($sBuffer = fgets($oOQLHandle)) !== false) {
		$iRead++;
		$aRecord = unserialize($sBuffer);

		$sOQL = $aRecord['oql'];

		$sChecksum = md5($sBuffer);
		if (isset($aFoundOQLs[$sChecksum])) { continue; }
		$aFoundOQLs[$sChecksum] = true;

		$iCount++;
		$sOrderBy = ConvertArray($aRecord['order_by']);
		$sGroupByExpr = ConvertArray($aRecord['group_by_expr']);
		$sSelectExpr = ConvertArray($aRecord['select_expr']);
		if ($aRecord['exclude_null_values'])
		{
			$bExcludeNullValues = 'true';
		}
		else
		{
			$bExcludeNullValues = 'false';
		}
		$iLimitCount = $aRecord['limit_count'];
		$iLimitStart = $aRecord['limit_start'];

		// $sOQL, $aArgs, $aGroupByExpr, $bExcludeNullValues, $aSelectExpr, $aOrderBy, $iLimitCount, $iLimitStart

		$sLine = "\$aData[\"SELECT $iCount\"] = array(\"$sOQL\", array(), $sGroupByExpr, $bExcludeNullValues, $sSelectExpr, $sOrderBy, $iLimitCount, $iLimitStart);\n";
		@fwrite($oTestHandle, $sLine);
	}
	if (!feof($oOQLHandle)) {
		echo "Erreur: fgets() a échoué\n";
	}
	@fclose($oOQLHandle);
}
@fwrite($oTestHandle, "\n");

@fclose($oTestHandle);

echo "<br>File '$sTestFile' generated with ".($iCount-1000)." entries (from $iRead captured OQL).\n";

function ConvertArray($aArray)
{
	if (is_null($aArray))
	{
		return 'null';
	}

	if (empty($aArray))
	{
		return 'array()';
	}

	return 'unserialize(\''.str_replace("'", "\\'",serialize($aArray)).'\')';
}
