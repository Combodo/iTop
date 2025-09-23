<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\DBTools\Service;

use CoreException;
use DatabaseAnalyzer;
use Dict;
use DictExceptionMissingString;
use MetaModel;

class DBAnalyzerUtils
{
	/**
	 * @param $aResults
	 *
	 * @return string
	 * @throws CoreException
	 * @throws DictExceptionMissingString
	 */
	public static function GenerateReport($aResults, $bVerbose = false)
	{
		$sDBToolsFolder = str_replace("\\", '/', APPROOT.'log/');
		$sReportFile = 'dbtools-report';

		$fReport = fopen($sDBToolsFolder.$sReportFile.'.log', 'w');
		fwrite($fReport, '-- Database Maintenance tools: '.date('Y-m-d H:i:s')."\r\n");
		fwrite($fReport, "-- ".Dict::S('DBTools:Disclaimer')."\r\n");
		fwrite($fReport, "-- ".Dict::S('DBTools:Indication')."\r\n");
		foreach ($aResults as $sClass => $aErrorList)
		{
			fwrite($fReport, '');
			foreach ($aErrorList as $sErrorLabel => $aError)
			{
				fwrite($fReport, "\r\n-- \r\n");
				fwrite($fReport, '-- Class: '.MetaModel::GetName($sClass).' ('.$sClass.")\r\n");
				$iCount = $aError['count'];
				if ($iCount === DatabaseAnalyzer::LIMIT) {
					$iCount = "$iCount(+)";
				}
				fwrite($fReport, '-- Count: '.$iCount."\r\n");
				fwrite($fReport, '-- Error: '.$sErrorLabel."\r\n");
				if (array_key_exists('query', $aError)) {
					$sQuery = $aError['query'];
					fwrite($fReport, '-- Query: '.$sQuery."\r\n");
				}
				
				if (isset($aError['fixit']))
				{
					fwrite($fReport, "\r\n-- Fix it (indication):\r\n\r\n");
					$aFixitQueries = $aError['fixit'];
					foreach ($aFixitQueries as $sFixitQuery)
					{
						fwrite($fReport, "$sFixitQuery\r\n");
					}
					fwrite($fReport, "\r\n");
				}

				if ($bVerbose) {
					$sQueryResult = '';
					$aIdList = [];
					foreach ($aError['res'] as $aRes) {
						$sQueryResult .= " - ";
						foreach ($aRes as $sKey => $sValue) {
							$sQueryResult .= "'$sKey'='$sValue' ";
							if ($sKey == 'id') {
								$aIdList[] = $sValue;
							}
						}
					}
					fwrite($fReport, "-- Result: ".$sQueryResult);
					$sIdList = '('.implode(',', $aIdList).')';
					fwrite($fReport, "\r\n-- Ids: ".$sIdList."\r\n");
				}
			}
		}
		fclose($fReport);

		return $sDBToolsFolder.$sReportFile;
	}
}
