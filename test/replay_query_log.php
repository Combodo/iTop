<?php
// Copyright (C) 2010-2012 Combodo SARL
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

/**
 * Replay the query log made when log_queries = 1
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


function LogResult($sString)
{
	file_put_contents(APPROOT.'data/queries.results.log', "\n".$sString, FILE_APPEND);
}

function LogBenchmarkCSV()
{
	$aValues = array();
	foreach (func_get_args() as $arg)
	{
		if (is_string($arg))
		{
			$aValues[] = '"'.str_replace('"', '\\"', $arg).'"';
		}
		else
		{
			$aValues[] = (string) $arg;
		}
	}
	$sLine = implode(';', $aValues); // the preferred for MS Excel
	file_put_contents(APPROOT.'data/queries.benchmark.csv', "\n".$sLine, FILE_APPEND);
}


/////////////////////////////////////////////////////////////////////////////
//
// Main program
//
/////////////////////////////////////////////////////////////////////////////

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/ajaxwebpage.class.inc.php');

require_once(APPROOT.'/application/startup.inc.php');
$operation = utils::ReadParam('operation', '');

require_once(APPROOT.'/application/loginwebpage.class.inc.php');
LoginWebPage::DoLogin(); // Check user rights and prompt if needed

$oP = new WebPage('Replay queries.log');


ini_set('memory_limit', '512M');

require_once(APPROOT.'/data/queries.log');
$iCount = count($aQueriesLog);
$oP->p("Nombre de requêtes: ".$iCount);

$sOperation = utils::ReadParam('operation', '');

switch ($sOperation)
{
case '':
default:
	$oP->add("<ol>\n");
	foreach ($aQueriesLog as $sQueryId => $aOqlData)
	{
		$sOql = $aOqlData['oql'];
		$sOqlHtml = htmlentities($sOql, ENT_QUOTES, 'UTF-8');
		$oP->add("<li>$sOqlHtml</li>\n");
	}
	$oP->add("</ol>\n");
	
	$oP->add("<form action=\"?operation=benchmark\" method=\"post\">\n");
	$oP->add("<input type=\"submit\" value=\"Benchmark!\">\n");
	$oP->add("</form>\n");
	break;

case 'benchmark':
	// Reset the log contents
	file_put_contents(APPROOT.'data/queries.results.log', date('Y-m-d H:i:s')."\n");
	file_put_contents(APPROOT.'data/queries.benchmark.csv', '');
	LogBenchmarkCSV('oql', 'type', 'properties', 'make duration', 'tables', 'query length', 'exec duration', 'rows');

	foreach ($aQueriesLog as $sQueryId => $aOqlData)
	{
		$sOql = $aOqlData['oql'];
		$sOqlHtml = htmlentities($sOql, ENT_QUOTES, 'UTF-8');
		$aQueryData = unserialize($aOqlData['data']);

		$oFilter = $aQueryData['filter'];
		$aArgs = $aQueryData['args'];

		if ($aQueryData['type'] == 'select')
		{
			$aOrderBy = $aQueryData['order_by'];
			$aAttToLoad = $aQueryData['att_to_load'];
			$aExtendedDataSpec = $aQueryData['extended_data_spec'];
			$iLimitCount = $aQueryData['limit_count'];
			$iLimitStart = $aQueryData['limit_start'];
			$bGetCount = $aQueryData['is_count'];

			if ($bGetCount)
			{
				$sQueryType = 'COUNT';
				$sQueryDesc = '';
			}
			else
			{
				$sQueryType = 'LIST';
				$sQueryDesc = "limit count: $iLimitCount";
				$sQueryDesc .= "; limit start: $iLimitStart";
				if (count($aOrderBy) > 0)
				{
					$sQueryDesc .= "; order by: ".implode(',', array_keys($aOrderBy));
				}
				if (is_array($aAttToLoad))
				{
					$sQueryDesc .= "; attributes: ".implode(',', array_keys($aAttToLoad));
				}
			}

			$fRefTime = MyHelpers::getmicrotime();
			try
			{
				$sSql = MetaModel::MakeSelectQuery($oFilter, $aOrderBy, $aArgs, $aAttToLoad, $aExtendedDataSpec, $iLimitCount, $iLimitStart, $bGetCount);
			}
			catch(Exception $e)
			{
				LogResult("Failed to create the SQL:".$e->getMessage());
				$sSql = '';
			}
			$fMakeDuration = MyHelpers::getmicrotime() - $fRefTime;
		}
		elseif ($aQueryData['type'] == 'group_by')
		{
			$aGroupByExpr = $aQueryData['group_by_expr'];

			$sQueryType = 'GROUP BY';
			$sQueryDesc = 'expr: '.serialize($aGroupByExpr);

			$fRefTime = MyHelpers::getmicrotime();
			try
			{
				$sSql = MetaModel::MakeGroupByQuery($oFilter, $aArgs, $aGroupByExpr);
			}
			catch(Exception $e)
			{
				LogResult("Failed to create the SQL:".$e->getMessage());
				$sSql = '';
			}
			$fMakeDuration = MyHelpers::getmicrotime() - $fRefTime;
		}
		else
		{
			// unsupported
			$sQueryType = 'ERROR';
			$sQueryDesc = "Unkown type of query: ".$aQueryData['type'];
			$fMakeDuration = 0;
		}

		LogResult($sOql);
		LogResult($sQueryType);
		if (strlen($sQueryDesc) > 0)
		{
			LogResult($sQueryDesc);
		}

		if ($sSql != '')
		{
			try
			{
				$fRefTime = MyHelpers::getmicrotime();
				$resQuery = CMDBSource::Query($sSql);
				$fExecDuration = MyHelpers::getmicrotime() - $fRefTime;

				$iTableCount = count(CMDBSource::ExplainQuery($sSql));
			}
			catch (Exception $e)
			{
				LogResult("Failed to execute the SQL:".$e->getMessage());
				LogResult("The failing SQL:\n".$sSql);
				$resQuery = null;
				$fExecDuration = 0;
				$iTableCount = 0;
			}
			$iRowCount = 0;
			if ($resQuery)
			{
				while ($aRow = CMDBSource::FetchArray($resQuery))
				{
					LogResult("row: ".serialize($aRow));
					$iRowCount++;
				}
				CMDBSource::FreeResult($resQuery);
			}
			LogResult("row count = ".$iRowCount);

			LogBenchmarkCSV($sOql, $sQueryType, $sQueryDesc, round($fMakeDuration, 3), $iTableCount, strlen($sSql), round($fExecDuration, 3), $iRowCount);
		}
	}
}

$oP->output();
?>