<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\DBTools\Service;

use CoreException;
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
	public static function GenerateReport($aResults)
	{
		$sDBToolsFolder = str_replace("\\", '/', APPROOT.'log/');
		$sReportFile = 'dbtools-report';

		$fReport = fopen($sDBToolsFolder.$sReportFile.'.log', 'w');
		fwrite($fReport, 'Database Maintenance tools: '.date('Y-m-d H:i:s')."\r\n");
		foreach ($aResults as $sClass => $aErrorList)
		{
			fwrite($fReport, '');
			foreach ($aErrorList as $sErrorLabel => $aError)
			{
				fwrite($fReport, "\r\n----------\r\n");
				fwrite($fReport, 'Class: '.MetaModel::GetName($sClass).' ('.$sClass.")\r\n");
				$iCount = $aError['count'];
				fwrite($fReport, 'Count: '.$iCount."\r\n");
				fwrite($fReport, 'Error: '.$sErrorLabel."\r\n");
				$sQuery = $aError['query'];
				fwrite($fReport, 'Query: '.$sQuery."\r\n");

				if (isset($aError['fixit']))
				{
					fwrite($fReport, "\r\nFix it (indication):\r\n\r\n");
					$aFixitQueries = $aError['fixit'];
					foreach ($aFixitQueries as $sFixitQuery)
					{
						fwrite($fReport, "$sFixitQuery\r\n");
					}
					fwrite($fReport, "\r\n");
				}

				$sQueryResult = '';
				$aIdList = array();
				foreach ($aError['res'] as $aRes)
				{
					foreach ($aRes as $sKey => $sValue)
					{
						$sQueryResult .= "'$sKey'='$sValue' ";
						if ($sKey == 'id')
						{
							$aIdList[] = $sValue;
						}
					}
					$sQueryResult .= "\r\n";

				}
				fwrite($fReport, "Result: \r\n".$sQueryResult);
				$sIdList = '('.implode(',', $aIdList).')';
				fwrite($fReport, 'Ids: '.$sIdList."\r\n");
			}
		}
		fclose($fReport);


		$sReportFile = $sDBToolsFolder.$sReportFile;

		return $sReportFile;
	}
}
