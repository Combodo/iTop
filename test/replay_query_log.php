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
			$aValues[] = '"'.str_replace('"', '""', $arg).'"';
		}
		else
		{
			$aValues[] = (string) $arg;
		}
	}
	$sLine = implode(';', $aValues); // the preferred for MS Excel
	file_put_contents(APPROOT.'data/queries.benchmark.csv', "\n".$sLine, FILE_APPEND);
}

class QueryLogEntry
{
	public function __construct($aLogEntryId, $aLogEntryData)
	{
		$this->aErrors = array();
		$this->sSql = '';
		$this->MakeDuration = 0;
		$this->fExecDuration = 0;
		$this->iTableCount = 0;
		$this->aRows = array();

		$this->sLogId = $aLogEntryId;
		$this->sOql = $aLogEntryData['oql'];
		$this->sOqlHtml = htmlentities($this->sOql, ENT_QUOTES, 'UTF-8');


		$aQueryData = unserialize($aLogEntryData['data']);
		$this->oFilter = $aQueryData['filter'];
		$this->sClass = $this->oFilter->GetClass();
		$this->aArgs = $aQueryData['args'];

		$iRepeat = utils::ReadParam('repeat', 3);

		if ($aQueryData['type'] == 'select')
		{
			$this->aOrderBy = $aQueryData['order_by'];
			$this->aAttToLoad = $aQueryData['att_to_load'];
			$this->aExtendedDataSpec = $aQueryData['extended_data_spec'];
			$this->iLimitCount = $aQueryData['limit_count'];
			$this->iLimitStart = $aQueryData['limit_start'];
			$this->bGetCount = $aQueryData['is_count'];

			if ($this->bGetCount)
			{
				$this->sQueryType = 'COUNT';
				$this->sQueryDesc = '';
			}
			else
			{
				$this->sQueryType = 'LIST';
				$this->sQueryDesc = "limit count: $this->iLimitCount";
				$this->sQueryDesc .= "; limit start: $this->iLimitStart";
				if (count($this->aOrderBy) > 0)
				{
					$this->sQueryDesc .= "; order by: ".implode(',', array_keys($this->aOrderBy));
				}
				if (is_array($this->aAttToLoad))
				{
					$this->sQueryDesc .= "; attributes: ".implode(',', array_keys($this->aAttToLoad));
				}
			}

			$fRefTime = MyHelpers::getmicrotime();
			try
			{
				for($i = 0 ; $i < $iRepeat ; $i++)
				{
					$this->sSql = $this->oFilter->MakeSelectQuery($this->aOrderBy, $this->aArgs, $this->aAttToLoad, $this->aExtendedDataSpec, $this->iLimitCount, $this->iLimitStart, $this->bGetCount);
				}
			}
			catch(Exception $e)
			{
				$this->aErrors[] = "Failed to create the SQL:".$e->getMessage();
			}
			$this->fMakeDuration = (MyHelpers::getmicrotime() - $fRefTime) / $iRepeat;
		}
		elseif ($aQueryData['type'] == 'group_by')
		{
			$this->aGroupByExpr = $aQueryData['group_by_expr'];

			$this->sQueryType = 'GROUP BY';
			$aGroupedBy = array();
			foreach ($this->aGroupByExpr as $oExpr)
			{
				$aGroupedBy[] = $oExpr->Render();
			}
			$this->sQueryDesc = implode(', ', $aGroupedBy);

			$fRefTime = MyHelpers::getmicrotime();
			try
			{
				for($i = 0 ; $i < $iRepeat ; $i++)
				{
					$this->sSql = $this->oFilter->MakeGroupByQuery($this->aArgs, $this->aGroupByExpr);
				}
			}
			catch(Exception $e)
			{
				$this->aErrors[] = "Failed to create the SQL:".$e->getMessage();
			}
			$this->fMakeDuration = (MyHelpers::getmicrotime() - $fRefTime) / $iRepeat;
		}
		else
		{
			// unsupported
			$this->sQueryType = 'ERROR';
			$this->sQueryDesc = "Unkown type of query: ".$aQueryData['type'];
		}
	}

	public function Exec()
	{
		if ($this->sSql != '')
		{
			$iRepeat = utils::ReadParam('repeat', 3);
			try
			{
				$fRefTime = MyHelpers::getmicrotime();
				for($i = 0 ; $i < $iRepeat ; $i++)
				{
					$resQuery = CMDBSource::Query($this->sSql);
				}
				$this->fExecDuration = (MyHelpers::getmicrotime() - $fRefTime) / $iRepeat;

				// This is not relevant... 
				if (preg_match_all('|\s*JOIN\s*\(\s*`|', $this->sSql, $aMatches)) // JOIN (`mytable...
				{
					$this->iTableCount = 1 + count($aMatches[0]);
				}
				else
				{
					$this->iTableCount = 1;
				}
			}
			catch (Exception $e)
			{
				$this->aErrors[] = "Failed to execute the SQL:".$e->getMessage();
				$resQuery = null;
			}
			if ($resQuery)
			{
				while ($aRow = CMDBSource::FetchArray($resQuery))
				{
					$this->aRows[] = $aRow;
				}
				CMDBSource::FreeResult($resQuery);
			}
		}
	}
		
	public function HasErrors()
	{
		return (count($this->aErrors) > 0);
	}

	public function Display($oP)
	{
		$oP->p($this->sOqlHtml);
		$oP->p($this->sQueryType);
		$oP->p($this->sQueryDesc);
		foreach ($this->aErrors as $sError)
		{
			$oP->p($sError);
		}
	}
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
		$oP->add("<li>$sOqlHtml <a href=\"?operation=zoom&query=$sQueryId\">zoom</a></li>\n");
	}
	$oP->add("</ol>\n");
	
	$oP->add("<form action=\"?operation=benchmark&repeat=3\" method=\"post\">\n");
	$oP->add("<input type=\"submit\" value=\"Benchmark (3 repeats)!\">\n");
	$oP->add("</form>\n");

	$oP->add("<form action=\"?operation=check\" method=\"post\">\n");
	$oP->add("<input type=\"submit\" value=\"Check!\">\n");
	$oP->add("</form>\n");
	break;

case 'zoom':
	$sQueryId = utils::ReadParam('query', '', false, 'raw_data');
	$oP->add("<h2>Zoom on query</h2>\n");
	$oQuery = new QueryLogEntry($sQueryId, $aQueriesLog[$sQueryId]);
	$oQuery->Exec();
	$oQuery->Display($oP);

	$oP->add("<pre>$oQuery->sSql</pre>\n");
	$oP->p("Tables: $oQuery->iTableCount");

	if (strlen($oQuery->sSql) > 0)
	{
		$aExplain = CMDBSource::ExplainQuery($oQuery->sSql);
		$oP->add("<h4>Explain</h4>\n");
		$oP->add("<table style=\"border=1px;\">\n");
		foreach ($aExplain as $aRow)
		{
			$oP->add("   <tr>\n");
			$oP->add("      <td>".implode('</td><td>', $aRow)."</td>\n");
			$oP->add("   </tr>\n");
		}
		$oP->add("</table>\n");
	}
	
	if (count($oQuery->aRows))
	{
		$oP->add("<h4>Values</h4>\n");
		$oP->add("<table style=\"border=1px;\">\n");
		foreach ($oQuery->aRows as $iRow => $aRow)
		{
			$oP->add("   <tr>\n");
			$oP->add("      <td>".implode('</td><td>', $aRow)."</td>\n");
			$oP->add("   </tr>\n");
		}
		$oP->add("</table>\n");
	}
	else
	{
		$oP->p("No data");
	}
	
	break;


case 'check':
	$oP->add("<h2>List queries in error</h2>\n");
	foreach ($aQueriesLog as $sQueryId => $aOqlData)
	{
		$oQuery = new QueryLogEntry($sQueryId, $aOqlData);
		$oQuery->Exec();
		
		if ($oQuery->HasErrors())
		{
			$oQuery->Display($oP);
			$oP->p("<a href=\"?operation=zoom&query=$sQueryId\">zoom</a>");
		}
	}
	break;


case 'benchmark':
	$oP->add("<h2>Create data/queries.xxx reports</h2>\n");
	// Reset the log contents
	file_put_contents(APPROOT.'data/queries.results.log', date('Y-m-d H:i:s')."\n");
	file_put_contents(APPROOT.'data/queries.benchmark.csv', '');
	LogBenchmarkCSV('type', 'properties', 'make duration', 'class', 'tables', 'query length', 'exec duration', 'rows', 'oql');

	$iErrors = 0;

	foreach ($aQueriesLog as $sQueryId => $aOqlData)
	{
		$oQuery = new QueryLogEntry($sQueryId, $aOqlData);
		$oQuery->Exec();
	
		LogResult('-----------------------------------------------------------');
		LogResult($oQuery->sOql);
		LogResult($oQuery->sQueryType);
		if (strlen($oQuery->sQueryDesc) > 0)
		{
			LogResult($oQuery->sQueryDesc);
		}
	
		if ($oQuery->HasErrors())
		{
			foreach($oQuery->aErrors as $sError)
			{
				LogResult($sError);
				$iErrors++;
			}
		}
		else
		{
			LogResult("row count = ".count($oQuery->aRows));
			foreach($oQuery->aRows as $iRow => $aRow)
			{
				LogResult("row: ".serialize($aRow));
				if ($iRow > 100) break;
			}
	
			LogBenchmarkCSV($oQuery->sQueryType, $oQuery->sQueryDesc, sprintf('%1.3f', round($oQuery->fMakeDuration, 3)), $oQuery->sClass, $oQuery->iTableCount, strlen($oQuery->sSql), sprintf('%1.4f', round($oQuery->fExecDuration, 4)), count($oQuery->aRows), $oQuery->sOql);
		}
	}
}

$oP->output();
?>
