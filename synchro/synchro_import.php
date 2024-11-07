<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
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

//
// Known limitations
// - reconciliation is made on the column primary_key
//

use Combodo\iTop\Application\WebPage\CLILikeWebPage;
use Combodo\iTop\Application\WebPage\CLIPage;

require_once __DIR__.'/../approot.inc.php';
require_once APPROOT.'/application/application.inc.php';
require_once APPROOT.'/application/startup.inc.php';

class ExchangeException extends Exception
{
}

$aPageParams = array
(
	'auth_user' => array
	(
		'mandatory' => true,
		'modes' => 'cli',
		'default' => null,
		'description' => 'login (must have enough rights to create objects of the given class)',
	),
	'auth_pwd' => array
	(
		'mandatory' => true,
		'modes' => 'cli',
		'default' => null,
		'description' => 'password',
	),
	'data_source_id' => array
	(
		'mandatory' => true,
		'modes' => 'http,cli',
		'default' => null,
		'description' => 'Synchro data source id',
	),
	'csvdata' => array
	(
		'mandatory' => true,
		'modes' => 'http',
		'default' => null,
		'description' => 'data',
	),
	'csvfile' => array
	(
		'mandatory' => true,
		'modes' => 'cli',
		'default' => '',
		'description' => 'local data file, replaces csvdata if specified',
	),
	'synchronize' => array
	(
		'mandatory' => false,
		'modes' => 'http,cli',
		'default' => '1',
		'description' => 'If set to 1, then the synchronization will be executed right after the data load',
	),
	'charset' => array
	(
		'mandatory' => false,
		'modes' => 'http,cli',
		'default' => 'UTF-8',
		'description' => 'Character set encoding of the CSV data: UTF-8, ISO-8859-1, WINDOWS-1251, WINDOWS-1252, ISO-8859-15',
	),
	'date_format' => array
	(
		'mandatory' => false,
		'modes' => 'http,cli',
		'default' => 'Y-m-d H:i:s',
		'description' => 'Input date format (used both for dates and datetimes) - Examples: Y-m-d, d/m/Y (Europe) - no transformation is applied if the argument is omitted. (note: old format specification using %Y %m %d is also supported for backward compatibility)',
	),
	'separator' => array
	(
		'mandatory' => false,
		'modes' => 'http,cli',
		'default' => ';',
		'description' => 'column separator in CSV data (1 char, or \'tab\')',
	),
	'qualifier' => array
	(
		'mandatory' => false,
		'modes' => 'http,cli',
		'default' => '"',
		'description' => 'test qualifier in CSV data',
	),
	'output' => array
	(
		'mandatory' => false,
		'modes' => 'http,cli',
		'default' => 'summary',
		'description' => '[retcode] to return the count of lines in error, [summary] to return a concise report, [details] to get a detailed report (each line listed)',
	),
	'max_chunk_size' => array
	(
		'mandatory' => false,
		'modes' => 'cli',
		'default' => '0',
		'description' => 'Limit on the count of records that can be loaded at once while performing the synchronization',
	),
	/*
		'reportlevel' => array
		(
			'mandatory' => false,
			'modes' => 'http,cli',
			'default' => 'errors|warnings|created|changed|unchanged',
			'description' => 'combination of flags to limit the detailed output',
		),
	*/
	'simulate' => array
	(
		'mandatory' => false,
		'modes' => 'http,cli',
		'default' => '0',
		'description' => 'If set to 1, then the load will not be executed, but the expected report will be produced',
	),
	'comment' => array
	(
		'mandatory' => false,
		'modes' => 'http,cli',
		'default' => '',
		'description' => 'Comment to be added into the change log',
	),
	'no_stop_on_import_error' => array
	(
		'mandatory' => false,
		'modes' => 'http,cli',
		'default' => '0',
		'description' => 'Don\'t stop the import in case of SQL import error. By default the import will stop at the first error (and rollback all changes). If this flag is set to 1 the import will continue anyway',
	),
);

function UsageAndExit($oP)
{
	global $aPageParams;
	$sMode = utils::IsModeCLI() ? 'cli' : 'http';

	$oP->p("USAGE:\n");
	foreach ($aPageParams as $sParam => $aParamData)
	{
		$aModes = explode(',', $aParamData['modes']);
		if (in_array($sMode, $aModes, false))
		{
			$sDesc = $aParamData['description'].', '.($aParamData['mandatory'] ? 'mandatory' : 'optional, defaults to ['.$aParamData['default'].']');
			$oP->p("$sParam = $sDesc");
		}
	}
	$oP->output();
	exit;
}


function ReadParam($oP, $sParam, $sSanitizationFilter = 'parameter')
{
	global $aPageParams;
	assert(isset($aPageParams[$sParam]));
	assert(!$aPageParams[$sParam]['mandatory']);
	$sValue = utils::ReadParam($sParam, $aPageParams[$sParam]['default'], true /* Allow CLI */, $sSanitizationFilter);

	return trim($sValue);
}

function ReadMandatoryParam($oP, $sParam, $sSanitizationFilter)
{
	global $aPageParams;
	assert(isset($aPageParams[$sParam]));
	assert($aPageParams[$sParam]['mandatory']);

	$sValue = utils::ReadParam($sParam, null, true /* Allow CLI */, $sSanitizationFilter);
	if ($sValue === null)
	{
		$oP->p("ERROR: Missing argument '$sParam'\n");
		UsageAndExit($oP);
	}

	return trim($sValue);
}

function ChangeDateFormat($sProposedDate, $sFormat, $bDateOnly)
{
	if ($sProposedDate === '')
	{
		// An empty string means 'reset'
		return '';
	}
	// Convert to a valid MySQL datetime
	$oFormat = new DateTimeFormat($sFormat);
	$sRegExpr = $oFormat->ToRegExpr('/');
	if (!preg_match($sRegExpr, $sProposedDate))
	{
		return false;
	}

	$oDate = $oFormat->Parse($sProposedDate);
	if ($oDate !== null)
	{
		$oTargetFormat = $bDateOnly ? AttributeDate::GetInternalFormat() : AttributeDateTime::GetInternalFormat();
		$sDate = $oDate->format($oTargetFormat);

		return $sDate;
	}

	return false;
}


/////////////////////////////////
// Main program

if (utils::IsModeCLI())
{
	$oP = new CLIPage(Dict::S('TitleSynchroExecution'));
	SetupUtils::CheckPhpAndExtensionsForCli($oP, -2);
}
else
{
	$oP = new CLILikeWebPage(Dict::S('TitleSynchroExecution'));
}

try
{
	utils::UseParamFile();
}
catch (Exception $e)
{
	$oP->p("Error: ".$e->GetMessage());
	$oP->output();
	exit -2;
}

if (utils::IsModeCLI())
{
	// Next steps:
	//   specific arguments: 'csvfile'
	//
	$sAuthUser = ReadMandatoryParam($oP, 'auth_user', 'raw_data');
	$sAuthPwd = ReadMandatoryParam($oP, 'auth_pwd', 'raw_data');
	$sCsvFile = ReadMandatoryParam($oP, 'csvfile', 'raw_data');
	if (UserRights::CheckCredentials($sAuthUser, $sAuthPwd))
	{
		UserRights::Login($sAuthUser); // Login & set the user's language
	}
	else
	{
		$oP->p("Access restricted or wrong credentials ('$sAuthUser')");
		$oP->output();
		exit -1;
	}

	if (!is_readable($sCsvFile))
	{
		$oP->p("Input file could not be found or could not be read: '$sCsvFile'");
		$oP->output();
		exit -1;
	}
	$sCSVData = file_get_contents($sCsvFile);

}
else
{
	require_once APPROOT.'/application/loginwebpage.class.inc.php';
	//N°6022 - Make synchro scripts work by http via token authentication with SYNCHRO scopes
	$oCtx = new ContextTag(ContextTag::TAG_SYNCHRO);
	LoginWebPage::ResetSession(true);
    $iRet = LoginWebPage::DoLogin(false, false, LoginWebPage::EXIT_RETURN);
    if ($iRet !== LoginWebPage::EXIT_CODE_OK) {
        switch ($iRet) {
            case LoginWebPage::EXIT_CODE_MISSINGLOGIN:
                $oP->p("Missing parameter 'auth_user'");
                break;

            case LoginWebPage::EXIT_CODE_MISSINGPASSWORD:
                $oP->p("Missing parameter 'auth_pwd'");
                break;

            case LoginWebPage::EXIT_CODE_WRONGCREDENTIALS:
                $oP->p('Invalid login');
                break;

            case LoginWebPage::EXIT_CODE_PORTALUSERNOTAUTHORIZED:
                $oP->p('Portal user is not allowed');
                break;

            case LoginWebPage::EXIT_CODE_NOTAUTHORIZED:
                $oP->p('This user is not authorized to use the web services. (The profile REST Services User is required to access the REST web services)');
                break;

            default:
                $oP->p("Unknown authentication error (retCode=$iRet)");
        }
        $oP->output();
        exit -1;
    }

	$sCSVData = utils::ReadPostedParam('csvdata', '', 'raw_data');
}


try
{
	//////////////////////////////////////////////////
	//
	// Read parameters
	//
	$iDataSourceId = ReadMandatoryParam($oP, 'data_source_id', 'raw_data');
	$sSynchronize = ReadParam($oP, 'synchronize');
	$sSep = ReadParam($oP, 'separator', 'raw_data');
	$sQualifier = ReadParam($oP, 'qualifier', 'raw_data');
	$sCharSet = ReadParam($oP, 'charset', 'raw_data');
	$sDateTimeFormat = ReadParam($oP, 'date_format', 'raw_data');
	if ($sDateTimeFormat === '')
	{
		$sDateTimeFormat = 'Y-m-d H:i:s'; // By default use the SQL date & time format
	}
	if (strpos($sDateTimeFormat, '%') !== false)
	{
		$sDateTimeFormat = utils::DateTimeFormatToPHP($sDateTimeFormat);
	}
	$oDateTimeFormat = new DateTimeFormat($sDateTimeFormat);
	$sDateFormat = $oDateTimeFormat->ToDateFormat(); // Keep only the date part
	$sOutput = ReadParam($oP, 'output');
//	$sReportLevel = ReadParam($oP, 'reportlevel');
	$sSimulate = ReadParam($oP, 'simulate');
	$sComment = ReadParam($oP, 'comment', 'raw_data');
	$sNoStopOnImportError = ReadParam($oP, 'no_stop_on_import_error');

	if (strtolower(trim($sSep)) === 'tab')
	{
		$sSep = "\t";
	}

	// Saving script launch datetime : if we're doing both import AND exec phases (--synchronize=1 parameter)
	// then exec phase will need this !
	$oLoadStartDate = SynchroExecution::GetDataBaseCurrentDateTime();

	// Note about date formatting: These MySQL settings are read-only... and in fact unused :-(
	// SET SESSION date_format = '%d/%m/%Y';
	// SET SESSION datetime_format = '%d/%m/%Y %H:%i:%s';
	// Therefore, we have to allow users to transform the format according to a given specification: date_format


	//////////////////////////////////////////////////
	//
	// Statistics
	//
	$iCountErrors = 0;
	$iCountCreations = 0;
	$iCountUpdates = 0;

	//////////////////////////////////////////////////
	//
	// Check parameters format/consistency
	//
	if (strlen($sCSVData) === 0)
	{
		throw new ExchangeException('Missing data - at least one line is expected');
	}

	/** @var \SynchroDataSource $oDataSource */
	$oDataSource = MetaModel::GetObject('SynchroDataSource', $iDataSourceId, false);
	if ($oDataSource === null)
	{
		throw new ExchangeException("Unknown data source id: '$iDataSourceId'");
	}
	$sClass = $oDataSource->GetTargetClass();

	if (strlen($sSep) > 1)
	{
		throw new ExchangeException("Separator is limited to one character, found '$sSep'");
	}

	if (strlen($sQualifier) > 1)
	{
		throw new ExchangeException("Text qualifier is limited to one character, found '$sQualifier'");
	}

	if (!in_array($sOutput, array('retcode', 'summary', 'details')))
	{
		throw new ExchangeException("Unknown output format: '$sOutput'");
	}

	/*
		$aReportLevels = explode('|', $sReportLevel);
		foreach($aReportLevels as $sLevel)
		{
			if (!in_array($sLevel, explode('|', 'errors|warnings|created|changed|unchanged')))
			{
				throw new ExchangeException("Unknown level in reporting level: '$sLevel'");
			}
		}
	*/

	if ($sSimulate === '1')
	{
		$bSimulate = true;
	}
	else
	{
		$bSimulate = false;
	}

	if ($sSynchronize === '1')
	{
		$bSynchronize = true;
	}
	else
	{
		$bSynchronize = false;
	}

	//////////////////////////////////////////////////
	//
	// Parse first line, check attributes, analyse the request
	//
	if ($sCharSet === 'UTF-8')
	{
		$sUTF8Data = $sCSVData;
	}
	else
	{
		$sUTF8Data = iconv($sCharSet, 'UTF-8//IGNORE//TRANSLIT', $sCSVData);
	}
	$oCSVParser = new CSVParser($sUTF8Data, $sSep, $sQualifier, MetaModel::GetConfig()->Get('max_execution_time_per_loop'));

	$aInputColumns = $oCSVParser->ListFields();
	$iColCount = count($aInputColumns);

	// Check columns
	$aColumns = $oDataSource->GetSQLColumns();

	$aDateColumns = array();
	foreach (MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
	{
		if ($oAttDef instanceof AttributeDate)
		{
			$aDateColumns[$sAttCode] = 'DATE';
		}
		elseif ($oAttDef instanceof AttributeDateTime)
		{
			$aDateColumns[$sAttCode] = 'DATETIME';
		}
	}

	$aIsDateToTransform = array();
	$aDateToTransformReport = array();
	$aIsBinaryToTransform = array();
	foreach ($aInputColumns as $iFieldId => $sInputColumn)
	{
		$aIsBinaryToTransform[$iFieldId] = false;

		if (array_key_exists($sInputColumn, $aDateColumns))
		{
			$aIsDateToTransform[$iFieldId] = $aDateColumns[$sInputColumn]; // either DATE or DATETIME
			$aDateToTransformReport[] = $sInputColumn;
		}
		else
		{
			$aIsDateToTransform[$iFieldId] = false;
		}

		if ($sInputColumn === 'primary_key')
		{
			$iPrimaryKeyCol = $iFieldId;
			$aIsBinaryToTransform[$iFieldId] = false;
			continue;
		}
		if (!array_key_exists($sInputColumn, $aColumns))
		{
			throw new ExchangeException("Unknown column '$sInputColumn' (class: '$sClass')");
		}

		$aIsBinaryToTransform[$iFieldId] = ($aColumns[$sInputColumn] === 'LONGBLOB');
	}
	if (!isset($iPrimaryKeyCol))
	{
		throw new ExchangeException("Missing reconciliation column 'primary_key'");
	}

	//////////////////////////////////////////////////
	//
	// Go for parsing and interpretation
	//
	try
	{
		if ($sOutput === 'details')
		{
			$oP->add_comment('------------------------------------------------------------');
			$oP->add_comment(' Import phase');
			$oP->add_comment('------------------------------------------------------------');
		}

		if ($bSimulate)
		{
			CMDBSource::Query('START TRANSACTION');
		}
		$aData = $oCSVParser->ToArray();
		$iLineCount = count($aData);

		$sTable = $oDataSource->GetDataTable();

		// Prepare insert columns
		$sInsertColumns = '`'.implode('`, `', $aInputColumns).'`';

		$iPreviousTimeLimit = ini_get('max_execution_time');
		$iLoopTimeLimit = MetaModel::GetConfig()->Get('max_execution_time_per_loop');
		$oMutex = new iTopMutex('synchro_import_'.$oDataSource->GetKey());
		$oMutex->Lock();
		foreach ($aData as $iRow => $aRow)
		{
			/** @noinspection DisconnectedForeachInstructionInspection */
			set_time_limit($iLoopTimeLimit);
			$sReconciliationCondition = '`primary_key` = '.CMDBSource::Quote($aRow[$iPrimaryKeyCol]);
			$sSelect = "SELECT COUNT(*) FROM `$sTable` WHERE $sReconciliationCondition";
			$aRes = CMDBSource::QueryToArray($sSelect);
			$iCount = (int)$aRes[0]['COUNT(*)'];

			if ($iCount === 0) {
				// No record... create it
				//
				$iCountCreations++;
				if ($sOutput === 'details')
				{
					$oP->add("$iRow: New entry, reconciliation: '$sReconciliationCondition'\n");
				}

				$aValues = array(); // Used to build the insert query
				foreach ($aRow as $iCol => $value)
				{
					if ($aIsDateToTransform[$iCol] !== false)
					{
						$bDateOnly = false;
						$sFormat = $sDateTimeFormat;
						if ($aIsDateToTransform[$iCol] === 'DATE')
						{
							$bDateOnly = true;
							$sFormat = $sDateFormat;
						}
						$sDate = ChangeDateFormat($value, $sFormat, $bDateOnly);
						if ($sDate === false)
						{
							$aValues[] = '';
							if ($sOutput === 'details')
							{
								$oP->add("$iRow: Wrong format for {$aIsDateToTransform[$iCol]} column $iCol: '$value' does not match the expected format: '$sFormat' (column skipped)\n");
							}
						}
						else
						{
							$aValues[] = $sDate;
						}
					}
					elseif ($aIsBinaryToTransform[$iCol])
					{
						$aValues[] = base64_decode($value);
					}
					else
					{
						$aValues[] = $value;
					}
				}
				$sValues = implode(', ', CMDBSource::Quote($aValues));
				$sInsert = "INSERT INTO `$sTable` ($sInsertColumns) VALUES ($sValues)";
				try
				{
					CMDBSource::Query($sInsert);
				}
				catch (Exception $e)
				{
					if ($sNoStopOnImportError === '1')
					{
						$iCountErrors++;
						$oP->add("$iRow: Import error '".$e->getMessage()."' (continuing)...\n");
					}
					else // Fatal error
					{
						throw $e;
					}
				}
			}
			elseif ($iCount === 1)
			{
				// Found a match... update it
				//
				$iCountUpdates++;
				if ($sOutput === 'details')
				{
					$oP->add("$iRow: Update entry, reconciliation: '$sReconciliationCondition'\n");
				}
				$aValuePairs = array(); // Used to build the update query
				foreach ($aRow as $iCol => $value)
				{
					// Skip reconciliation column
					if ($iCol === $iPrimaryKeyCol)
					{
						continue;
					}

					$sCol = $aInputColumns[$iCol];
					if ($aIsDateToTransform[$iCol] !== false)
					{
						$bDateOnly = false;
						$sFormat = $sDateTimeFormat;
						if ($aIsDateToTransform[$iCol] === 'DATE')
						{
							$bDateOnly = true;
							$sFormat = $sDateFormat;
						}
						$sDate = ChangeDateFormat($value, $sFormat, $bDateOnly);
						if ($sDate === false)
						{
							if ($sOutput === 'details')
							{
								$oP->add("$iRow: Wrong format for {$aIsDateToTransform[$iCol]} column $iCol: '$value' does not match the expected format: '$sFormat' (column skipped)\n");
							}
						}
						else
						{
							$aValuePairs[] = "`$sCol` = ".CMDBSource::Quote($sDate);
						}
					}
					elseif ($aIsBinaryToTransform[$iCol])
					{
						$aValuePairs[] = "`$sCol` = FROM_BASE64(".CMDBSource::Quote($aRow[$iCol], true).")";
					}
					else
					{
						$aValuePairs[] = "`$sCol` = ".CMDBSource::Quote($aRow[$iCol]);
					}
				}
				$sValuePairs = implode(', ', $aValuePairs);
				$sUpdateQuery = "UPDATE `$sTable` SET $sValuePairs WHERE $sReconciliationCondition";
				try
				{
					CMDBSource::Query($sUpdateQuery);
				}
				catch (Exception $e)
				{
					if ($sNoStopOnImportError === '1')
					{
						$iCountErrors++;
						$oP->add("$iRow: Import error '".$e->getMessage()."' (continuing)...\n");
					}
					else // Fatal error
					{
						throw $e;
					}
				}
			}
			else
			{
				// Too many records... ambiguity
				//
				$iCountErrors++;
				$oP->add("$iRow: Error - Failed to reconcile, found $iCount rows having '$sReconciliationCondition'\n");
			}
		}
		$oMutex->Unlock();
		set_time_limit(intval($iPreviousTimeLimit));

		if (($sOutput === 'summary') || ($sOutput === 'details'))
		{
			$oP->add_comment('------------------------------------------------------------');
			$oP->add_comment(' Import phase summary');
			$oP->add_comment('------------------------------------------------------------');
			$oP->add_comment('Data Source: '.$iDataSourceId);
			$oP->add_comment('Synchronize: '.($bSynchronize ? '1' : '0'));
			$oP->add_comment('Class: '.$sClass);
			$oP->add_comment('Separator: '.$sSep);
			$oP->add_comment('Qualifier: '.$sQualifier);
			$oP->add_comment('Charset Encoding:'.$sCharSet);

			if (strlen($sDateTimeFormat) > 0)
			{
				$aDateTimeColumns = array();
				$aDateColumns = array();
				foreach ($aIsDateToTransform as $iCol => $sType)
				{
					if ($sType !== false)
					{
						$sCol = $aInputColumns[$iCol];
						if ($sType === 'DATE')
						{
							$aDateColumns[] = $sCol;
						}
						else
						{
							$aDateTimeColumns[] = $sCol;
						}
					}
				}
				$sFormatedDateTimeColumns = (count($aDateTimeColumns) > 0) ? ', applied to columns ['.implode(', ',
						$aDateTimeColumns).']' : '';
				$sFormatedDateColumns = (count($aDateColumns) > 0) ? ', applied to columns ['.implode(', ', $aDateColumns).']' : '';
				$oP->add_comment("Date and time format: '$sDateTimeFormat' $sFormatedDateTimeColumns");
				$oP->add_comment("Date only format: '$sDateFormat' $sFormatedDateColumns");
			}
			else
			{
				// shall never get there
				$oP->add_comment('Date format: <none>');
			}
			$oP->add_comment('Data Size: '.strlen($sCSVData));
			$oP->add_comment('Data Lines: '.$iLineCount);
			$oP->add_comment('Columns: '.implode(', ', $aInputColumns));
			$oP->add_comment('Output format: '.$sOutput);
			//		$oP->add_comment("Report level: ".$sReportLevel);
			$oP->add_comment('Simulate: '.($bSimulate ? '1' : '0'));
			$oP->add_comment('Change tracking comment: '.$sComment);
			$oP->add_comment('Issues (before synchro): '.$iCountErrors);
			//		$oP->add_comment("Warnings: ".$iCountWarnings);
			$oP->add_comment('Created (before synchro): '.$iCountCreations);
			$oP->add_comment('Updated (before synchro): '.$iCountUpdates);
		}

		//////////////////////////////////////////////////
		//
		// Synchronize
		//
		if ($bSynchronize)
		{
			$oSynchroExec = new SynchroExecution($oDataSource, $oLoadStartDate);
			$oStatLog = $oSynchroExec->Process();
			if ($sOutput === 'details')
			{
				$oP->add_comment('------------------------------------------------------------');
				$oP->add_comment(' Synchronization phase');
				$oP->add_comment('------------------------------------------------------------');
				$iCount = 0;
				foreach ($oStatLog->GetTraces() as $sMessage)
				{
					$iCount++;
					$oP->add_comment($sMessage);
				}
				if ($iCount === 0)
				{
					$oP->add_comment(' No traces. (To activate the traces set "synchro_trace" => true in the configuration file)');
				}
			}
			if ($oStatLog->Get('status') === 'error')
			{
				$oP->p('ERROR: '.$oStatLog->Get('last_error'));
			}
			$oP->add_comment('------------------------------------------------------------');
			$oP->add_comment(' Synchronization phase summary');
			$oP->add_comment('------------------------------------------------------------');
			$oP->add_comment('Replicas: '.$oStatLog->Get('stats_nb_replica_total'));
			$oP->add_comment('Replicas touched since last synchro: '.$oStatLog->Get('stats_nb_replica_seen'));
			$oP->add_comment('Objects deleted: '.$oStatLog->Get('stats_nb_obj_deleted'));
			$oP->add_comment('Objects deletion errors: '.$oStatLog->Get('stats_nb_obj_deleted_errors'));
			$oP->add_comment('Objects obsoleted: '.$oStatLog->Get('stats_nb_obj_obsoleted'));
			$oP->add_comment('Objects obsolescence errors: '.$oStatLog->Get('stats_nb_obj_obsoleted_errors'));
			$oP->add_comment('Objects created: '.$oStatLog->Get('stats_nb_obj_created').' ('.$oStatLog->Get('stats_nb_obj_created_warnings').' warnings)');
			$oP->add_comment('Objects creation errors: '.$oStatLog->Get('stats_nb_obj_created_errors'));
			$oP->add_comment('Objects updated: '.$oStatLog->Get('stats_nb_obj_updated').' ('.$oStatLog->Get('stats_nb_obj_updated_warnings')." warnings)");
			$oP->add_comment('Objects update errors: '.$oStatLog->Get('stats_nb_obj_updated_errors'));
			$oP->add_comment('Objects reconciled (updated): '.$oStatLog->Get('stats_nb_obj_new_updated').' ('.$oStatLog->Get('stats_nb_obj_new_updated_warnings').' warnings)');
			$oP->add_comment('Objects reconciled (unchanged): '.$oStatLog->Get('stats_nb_obj_new_unchanged').' ('.$oStatLog->Get('stats_nb_obj_new_updated_warnings').' warnings)');
			$oP->add_comment('Objects reconciliation errors: '.$oStatLog->Get('stats_nb_replica_reconciled_errors'));
			$oP->add_comment('Replica disappeared, no action taken: '.$oStatLog->Get('stats_nb_replica_disappeared_no_action'));
		}
	}
	catch (Exception $e)
	{
		if ($bSimulate)
		{
			CMDBSource::Query('ROLLBACK');
		}
		throw $e;
	}
	if ($bSimulate)
	{
		CMDBSource::Query('ROLLBACK');
	}

	//////////////////////////////////////////////////
	//
	// Summary of settings and results
	//
	if ($sOutput === 'retcode')
	{
		$oP->add($iCountErrors);
	}
}
catch (ExchangeException $e)
{
	$oP->add_comment($e->getMessage());
}
catch (Exception $e)
{
	$oP->add_comment((string)$e);
}

$oP->output();
