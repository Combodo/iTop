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
 * Date: 06/10/2017
 */


require_once('../approot.inc.php');
require_once(APPROOT.'bootstrap.inc.php');
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

		$sChecksum = md5($sOQL);
		if (isset($aFoundOQLs[$sChecksum])) { continue; }
		$aFoundOQLs[$sChecksum] = true;

		$iCount++;
		$sOrderBy = 'unserialize(\''.serialize($aRecord['order_by']).'\')';
		$sArgs = 'unserialize(\''.serialize($aRecord['args']).'\')';
		$sAttToLoad = 'unserialize(\''.serialize($aRecord['att_to_load']).'\')';
		$sExtendedDataSpec = 'unserialize(\''.serialize($aRecord['extended_data_spec']).'\')';
		$iLimitCount = $aRecord['limit_count'];
		$iLimitStart = $aRecord['limit_start'];

		// $sOQL, $aOrderBy, $aArgs, $aAttToLoad, $aExtendedDataSpec, $iLimitCount, $iLimitStart

		$sLine = "\$aData[\"SELECT $iCount\"] = array(\"$sOQL\", $sOrderBy, $sArgs, $sAttToLoad, $sExtendedDataSpec, $iLimitCount, $iLimitStart);\n";
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
