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
 * Import web service 
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

//
// Known limitations
// - reconciliation is made on the first column
//
// Known issues
// - ALMOST impossible to troubleshoot when an externl key has a wrong value
// - no character escaping in the xml output (yes !?!?!)
// - not outputing xml when a wrong input is given (class, attribute names)
//

if (!defined('__DIR__')) define('__DIR__', dirname(__FILE__));
require_once(__DIR__.'/../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/webpage.class.inc.php');
require_once(APPROOT.'/application/csvpage.class.inc.php');
require_once(APPROOT.'/application/clipage.class.inc.php');

require_once(APPROOT.'/application/startup.inc.php');

class BulkLoadException extends Exception
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
	'class' => array
	(
		'mandatory' => true,
		'modes' => 'http,cli',
		'default' => null,
		'description' => 'class of loaded objects',
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
	'charset' => array
	(
		'mandatory' => false,
		'modes' => 'http,cli',
		'default' => '',
		'description' => 'Character set encoding of the CSV data: UTF-8, ISO-8859-1, WINDOWS-1251, WINDOWS-1252, ISO-8859-15, If blank, then the charset is set to config(csv_file_default_charset)',
	),
	'date_format' => array
	(
		'mandatory' => false,
		'modes' => 'http,cli',
		'default' => '',
		'description' => 'Input date format (used both for dates and datetimes) - Examples: %Y-%m-%d, %d/%m/%Y (Europe) - no transformation is applied if the argument is omitted',
	),
	'separator' => array
	(
		'mandatory' => false,
		'modes' => 'http,cli',
		'default' => ',',
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
/*
	'reportlevel' => array
	(
		'mandatory' => false,
		'modes' => 'http,cli',
		'default' => 'errors|warnings|created|changed|unchanged',
		'description' => 'combination of flags to limit the detailed output',
	),
*/
	'reconciliationkeys' => array
	(
		'mandatory' => false,
		'modes' => 'http,cli',
		'default' => '',
		'description' => 'name of the columns used to identify existing objects and update them, or create a new one',
	),
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
	'no_localize' => array
	(
		'mandatory' => false,
		'modes' => 'http,cli',
		'default' => '0',
		'description' => 'If set to 0, then header and values are supposed to be localized in the language of the logged in user. Set to 1 to use internal attribute codes and values (enums)',
	),
);

function UsageAndExit($oP)
{
	global $aPageParams;
	$bModeCLI = utils::IsModeCLI();

	$oP->p("USAGE:\n");
	foreach($aPageParams as $sParam => $aParamData)
	{
		$aModes = explode(',', $aParamData['modes']);
		if ($bModeCLI)
		{
			if (in_array('cli', $aModes))
			{
				$sDesc = $aParamData['description'].', '.($aParamData['mandatory'] ? 'mandatory' : 'optional, defaults to ['.$aParamData['default'].']');
				$oP->p("$sParam = $sDesc");
			}
		}
		else
		{
			if (in_array('http', $aModes))
			{
				$sDesc = $aParamData['description'].', '.($aParamData['mandatory'] ? 'mandatory' : 'optional, defaults to ['.$aParamData['default'].']');
				$oP->p("$sParam = $sDesc");
			}
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
	if (is_null($sValue))
	{
		$oP->p("ERROR: Missing argument '$sParam'\n");
		UsageAndExit($oP);
	}
	return trim($sValue);
}

/////////////////////////////////
// Main program

if (utils::IsModeCLI())
{
	$oP = new CLIPage("iTop - Bulk import");
}
else
{
	$oP = new CSVPage("iTop - Bulk import");
}

try
{
	utils::UseParamFile();
}
catch(Exception $e)
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
	$_SESSION['login_mode'] = 'basic';
	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	LoginWebPage::DoLogin(); // Check user rights and prompt if needed

	$sCSVData = utils::ReadPostedParam('csvdata', '', 'raw_data');
}


try
{
	$aWarnings = array();

	//////////////////////////////////////////////////
	//
	// Read parameters
	//
	$sClass = ReadMandatoryParam($oP, 'class', 'raw_data'); // do not filter as a valid class, we want to produce the report "wrong class" ourselves 
	$sSep = ReadParam($oP, 'separator', 'raw_data');
	$sQualifier = ReadParam($oP, 'qualifier', 'raw_data');
	$sCharSet = ReadParam($oP, 'charset', 'raw_data');
	$sDateFormat = ReadParam($oP, 'date_format', 'raw_data');
	$sOutput = ReadParam($oP, 'output', 'string');
	$sReconcKeys = ReadParam($oP, 'reconciliationkeys', 'raw_data');
	$sSimulate = ReadParam($oP, 'simulate');
	$sComment = ReadParam($oP, 'comment', 'raw_data');
	$bLocalize = (ReadParam($oP, 'no_localize') != 1);

	if (strtolower(trim($sSep)) == 'tab')
	{
		$sSep = "\t";
	}

	//////////////////////////////////////////////////
	//
	// Check parameters format/consistency
	//
	if (strlen($sCSVData) == 0)
	{
		throw new BulkLoadException("Missing data - at least one line is expected");
	}

	if (!MetaModel::IsValidClass($sClass))
	{
		throw new BulkLoadException("Unknown class: '$sClass'");
	}

	if (strlen($sSep) > 1)
	{
		throw new BulkLoadException("Separator is limited to one character, found '$sSep'");
	}

	if (strlen($sQualifier) > 1)
	{
		throw new BulkLoadException("Text qualifier is limited to one character, found '$sQualifier'");
	}

	if (!in_array($sOutput, array('retcode', 'summary', 'details')))
	{
		throw new BulkLoadException("Unknown output format: '$sOutput'");
	}

	if (strlen($sDateFormat) == 0)
	{
		$sDateFormat = null;
	}
	
	if ($sCharSet == '')
	{
		$sCharSet = MetaModel::GetConfig()->Get('csv_file_default_charset');
	}

	if ($sSimulate == '1')
	{
		$bSimulate = true;
	}
	else
	{
		$bSimulate = false;
	}

	if (($sOutput == "summary") || ($sOutput == 'details'))
	{
		$oP->add_comment("Output format: ".$sOutput);
		$oP->add_comment("Class: ".$sClass);
		$oP->add_comment("Separator: ".$sSep);
		$oP->add_comment("Qualifier: ".$sQualifier);
		$oP->add_comment("Charset Encoding:".$sCharSet);
		if (strlen($sDateFormat) > 0)
		{
			$oP->add_comment("Date format: '$sDateFormat'");
		}
		else
		{
			$oP->add_comment("Date format: <none>");
		}
		$oP->add_comment("Localize: ".($bLocalize?'yes':'no'));
		$oP->add_comment("Data Size: ".strlen($sCSVData));
	}
	//////////////////////////////////////////////////
	//
	// Security
	//
	if (!UserRights::IsActionAllowed($sClass, UR_ACTION_BULK_MODIFY))
	{
		throw new SecurityException(Dict::Format('UI:Error:BulkModifyNotAllowedOn_Class', $sClass));
	}

	//////////////////////////////////////////////////
	//
	// Create an index of the known column names (in lower case)
	// If data is localized, an array of <TranslatedName> => array of <ExtendedAttCode> (several leads to ambiguity)
	// Otherwise             an array of <ExtendedAttCode> => array of <ExtendedAttCode> (1 element by construction)
	//
	// Examples (localized in french):
	//   'lieu' => 'location_id'
	//   'lieu->name' => 'location_id->name'
	//
	// Note: it may happen that an external field has the same label as the external key
	//       in that case, we consider that the external key has precedence
	//
	$aKnownColumnNames = array();
	foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
	{
		if ($bLocalize)
		{
	  		$sColName = strtolower(MetaModel::GetLabel($sClass, $sAttCode));
	  	}
	  	else
	  	{
	  		$sColName = strtolower($sAttCode);
	  	}
	  	if (!$oAttDef->IsExternalField() || !array_key_exists($sColName, $aKnownColumnNames))
	  	{
		  	$aKnownColumnNames[$sColName][] = $sAttCode;
		}
	  	if ($oAttDef->IsExternalKey(EXTKEY_RELATIVE))
	  	{
	  		$sRemoteClass = $oAttDef->GetTargetClass();
			foreach(MetaModel::ListAttributeDefs($sRemoteClass) as $sRemoteAttCode => $oRemoteAttDef)
		  	{
	  			$sAttCodeEx = $sAttCode.'->'.$sRemoteAttCode;
				if ($bLocalize)
				{
		  			$sColName = strtolower(MetaModel::GetLabel($sClass, $sAttCodeEx));
			  	}
			  	else
			  	{
			  		$sColName = strtolower($sAttCodeEx);
			  	}
		  		if (!array_key_exists($sColName, $aKnownColumnNames))
		  		{
		  			$aKnownColumnNames[$sColName][] = $sAttCodeEx;
		  		}
		  	}
		}
   }

	//print_r($aKnownColumnNames);
	//print_r(array_keys($aKnownColumnNames));
	//exit;

	//////////////////////////////////////////////////
	//
	// Parse first line, check attributes, analyse the request
	//
	if ($sCharSet == 'UTF-8')
	{
		// Remove the BOM if any
		if (substr($sCSVData, 0, 3) == UTF8_BOM)
		{
			$sCSVData = substr($sCSVData, 3);
		}
		// Clean the input
		// Todo: warn the user if some characters are lost/substituted
		$sUTF8Data = iconv('UTF-8', 'UTF-8//IGNORE//TRANSLIT', $sCSVData);
	}
	else
	{
		$sUTF8Data = iconv($sCharSet, 'UTF-8//IGNORE//TRANSLIT', $sCSVData);
	}
	$oCSVParser = new CSVParser($sUTF8Data, $sSep, $sQualifier); 

	// Limitation: as the attribute list is in the first line, we can not match external key by a third-party attribute
	$aRawFieldList = $oCSVParser->ListFields();
	$iColCount = count($aRawFieldList);

	// Translate into internal names
	$aFieldList = array();
	foreach($aRawFieldList as $iFieldId => $sFieldName)
	{
		$sFieldName = trim($sFieldName);
		$aMatches = array();
		if (preg_match('/^(.+)\*$/', $sFieldName, $aMatches))
		{
			// Ignore any trailing "star" (*) that simply indicates a mandatory field
			$sFieldName = $aMatches[1];
		}
		else if (preg_match('/^(.+)\*->(.+)$/', $sFieldName, $aMatches))
		{
			// Remove any trailing "star" character before the arrow (->)
			// A star character at the end can be used to indicate a mandatory field
			$sFieldName = $aMatches[1].'->'.$aMatches[2];
		}	
		if (array_key_exists(strtolower($sFieldName), $aKnownColumnNames))
		{
			$aColumns = $aKnownColumnNames[strtolower($sFieldName)];
			if (count($aColumns) > 1)
			{
				$aCompetitors = array();
				foreach ($aColumns as $sAttCodeEx)
				{
					$aCompetitors[] = $sAttCodeEx;
				}
				$aWarnings[] = "Input column '$sFieldName' is ambiguous. Could be related to ".implode (' or ', $aCompetitors).". The first one will be used: ".$aColumns[0];
			}
			$aFieldList[$iFieldId] = $aColumns[0];
		}
		else
		{
			// Protect against XSS injection
			$sSafeName = str_replace(array('"', '<', '>'), '', $sFieldName);
			throw new BulkLoadException("Unknown column: '$sSafeName'. Possible columns: ".implode(', ', array_keys($aKnownColumnNames)));
		}
	}
	// Note: at this stage the list of fields is supposed to be made of attcodes (and the symbol '->')	

	$aAttList = array();
	$aExtKeys = array();
	foreach($aFieldList as $iFieldId => $sFieldName)
	{
		$aMatches = array();
		if (preg_match('/^(.+)->(.+)$/', trim($sFieldName), $aMatches))
		{
			// The column has been specified as "extkey->attcode"
			//
			$sExtKeyAttCode = $aMatches[1];
			$sRemoteAttCode = $aMatches[2];
			if (!MetaModel::IsValidAttCode($sClass, $sExtKeyAttCode))
			{
				// Safety net - should not happen now that column names are checked against known names
				throw new BulkLoadException("Unknown attribute '$sExtKeyAttCode' (class: '$sClass')");
			}
			$oAtt = MetaModel::GetAttributeDef($sClass, $sExtKeyAttCode);
			if (!$oAtt->IsExternalKey())
			{
				// Safety net - should not happen now that column names are checked against known names
				throw new BulkLoadException("Not an external key '$sExtKeyAttCode' (class: '$sClass')");
			}
			$sTargetClass = $oAtt->GetTargetClass();
			if (!MetaModel::IsValidAttCode($sTargetClass, $sRemoteAttCode))
			{
				// Safety net - should not happen now that column names are checked against known names
				throw new BulkLoadException("Unknown attribute '$sRemoteAttCode' (key: '$sExtKeyAttCode', class: '$sTargetClass')");
			}
			$aExtKeys[$sExtKeyAttCode][$sRemoteAttCode] = $iFieldId;
		}
		elseif ($sFieldName == 'id')
		{
			$aAttList[$sFieldName] = $iFieldId;
		}
		else
		{
			// The column has been specified as "attcode"
			//
			if (!MetaModel::IsValidAttCode($sClass, $sFieldName))
			{
				// Safety net - should not happen now that column names are checked against known names
				throw new BulkLoadException("Unknown attribute '$sFieldName' (class: '$sClass')");
			}
			$oAtt = MetaModel::GetAttributeDef($sClass, $sFieldName);
			if ($oAtt->IsExternalKey())
			{
				$aExtKeys[$sFieldName]['id'] = $iFieldId;
				$aAttList[$sFieldName] = $iFieldId;
			}
			elseif ($oAtt->IsExternalField())
			{
				$sExtKeyAttCode = $oAtt->GetKeyAttCode();
				$sRemoteAttCode = $oAtt->GetExtAttCode();
				$aExtKeys[$sExtKeyAttCode][$sRemoteAttCode] = $iFieldId;
			}
			else
			{
				$aAttList[$sFieldName] = $iFieldId;
			}
		}
	}

	// Make sure there are some reconciliation keys
	//
	if (empty($sReconcKeys))
	{
		$aReconcSpec = array();
		// Base reconciliation scheme on the default one
		// The reconciliation attributes not present in the data will be ignored
		foreach(MetaModel::GetReconcKeys($sClass) as $sReconcKeyAttCode)
		{
			if (in_array($sReconcKeyAttCode, $aFieldList))
			{
				if ($bLocalize)
				{
					$aReconcSpec[] = MetaModel::GetLabel($sClass, $sReconcKeyAttCode);
				}
				else
				{
					$aReconcSpec[] = $sReconcKeyAttCode;
				}
			}
		}
		if (count($aReconcSpec) == 0)
		{
			throw new BulkLoadException("No reconciliation scheme could be defined, please add a column corresponding to one defined reconciliation key (class: '$sClass', reconciliation:".implode(',', MetaModel::GetReconcKeys($sClass)).")");
		}
		$sReconcKeys = implode(',', $aReconcSpec);
	}

	// Interpret the list of reconciliation keys
	//
	$aFinalReconcilKeys = array();
	$aReconcilKeysReport = array();
	foreach (explode(',', $sReconcKeys) as $sReconcKey)
	{
		$sReconcKey = trim($sReconcKey);
		if (empty($sReconcKey)) continue; // skip empty spec

		if (array_key_exists(strtolower($sReconcKey), $aKnownColumnNames))
		{
			// Translate from a translated name to codes
			$aColumns = $aKnownColumnNames[strtolower($sReconcKey)];
			if (count($aColumns) > 1)
			{
				$aCompetitors = array();
				foreach ($aColumns as $sAttCodeEx)
				{
					$aCompetitors[] = $sAttCodeEx;
				}
				$aWarnings[] = "Reconciliation key '$sReconcKey' is ambiguous. Could be related to ".implode (' or ', $aCompetitors).". The first one will be used: ".$aColumns[0];
			}
			$sReconcKey = $aColumns[0];
		}
		else
		{
			// Protect against XSS injection
			$sSafeName = str_replace(array('"', '<', '>'), '', $sReconcKey);
			throw new BulkLoadException("Unknown reconciliation key: '$sSafeName'");
		}

		// Check that the reconciliation key is either a given column, or an external key
		if (!in_array($sReconcKey, $aFieldList))
		{
			if (!array_key_exists($sReconcKey, $aExtKeys))
			{
				// Protect against XSS injection
				$sSafeName = str_replace(array('"', '<', '>'), '', $sReconcKey);
				throw new BulkLoadException("Reconciliation key not found in the input columns: '$sSafeName'");
			}
		}

		if (preg_match('/^(.+)->(.+)$/', trim($sReconcKey), $aMatches))
		{
			// The column has been specified as "extkey->attcode"
			//
			$sExtKeyAttCode = $aMatches[1];
			$sRemoteAttCode = $aMatches[2];

			$aFinalReconcilKeys[] = $sExtKeyAttCode;
			$aReconcilKeysReport[$sExtKeyAttCode][] = $sRemoteAttCode;
		}
		else
		{
			if (!MetaModel::IsValidAttCode($sClass, $sReconcKey))
			{
				// Safety net - should not happen now that column names are checked against known names
				throw new BulkLoadException("Unknown reconciliation attribute '$sReconcKey' (class: '$sClass')");
			}
			$oAtt = MetaModel::GetAttributeDef($sClass, $sReconcKey);
			if ($oAtt->IsExternalKey())
			{
				$aFinalReconcilKeys[] = $sReconcKey;
				$aReconcilKeysReport[$sReconcKey][] = 'id';
			}
			elseif ($oAtt->IsExternalField())
			{
				$sReconcAttCode = $oAtt->GetKeyAttCode();
				$sReconcKeyReport = "$sReconcAttCode ($sReconcKey)";

				$aFinalReconcilKeys[] = $sReconcAttCode;
				$aReconcilKeysReport[$sReconcAttCode][] = $sReconcKeyReport;
			}
			else
			{
				$aFinalReconcilKeys[] = $sReconcKey;
				$aReconcilKeysReport[$sReconcKey] = array();
			}
		}
	}

	//////////////////////////////////////////////////
	//
	// Go for parsing and interpretation
	//

	$aData = $oCSVParser->ToArray();
	$iLineCount = count($aData);

	if (($sOutput == "summary") || ($sOutput == 'details'))
	{
		$oP->add_comment("Data Lines: ".$iLineCount);
		$oP->add_comment("Simulate: ".($bSimulate ? '1' : '0'));
		$oP->add_comment("Columns: ".implode(', ', $aFieldList));

		$aReconciliationReport = array();
		foreach($aReconcilKeysReport as $sKey => $aKeyDetails)
		{
			if (count($aKeyDetails) > 0)
			{
				$aReconciliationReport[] = $sKey.' ('.implode(',', $aKeyDetails).')';
			}
			else
			{
				$aReconciliationReport[] = $sKey;
			}
		}
		$oP->add_comment("Reconciliation Keys: ".implode(', ', $aReconciliationReport));

		foreach ($aWarnings as $sWarning)
		{
			$oP->add_comment("Warning: ".$sWarning);
		}
	}

	$oBulk = new BulkChange(
		$sClass,
		$aData,
		$aAttList,
		$aExtKeys,
		$aFinalReconcilKeys,
		null, // synchro scope
		null, // on delete
		$sDateFormat,
		$bLocalize
	);

	if ($bSimulate)
	{
		$oMyChange = null;
	}
	else
	{
		if (strlen($sComment) > 0)
		{
			$sMoreInfo = CMDBChange::GetCurrentUserName().', Web Service (CSV) - '.$sComment;
		}
		else
		{
			$sMoreInfo = CMDBChange::GetCurrentUserName().', Web Service (CSV)';
		}
		CMDBObject::SetTrackInfo($sMoreInfo);
		CMDBObject::SetTrackOrigin('csv-import.php');
		
		$oMyChange = CMDBObject::GetCurrentChange();
	}

	$aRes = $oBulk->Process($oMyChange);

	//////////////////////////////////////////////////
	//
	// Compute statistics
	//
	$iCountErrors = 0;
	$iCountWarnings = 0;
	$iCountCreations = 0;
	$iCountUpdates = 0;
	$iCountUnchanged = 0;
	foreach($aRes as $iRow => $aRowData)
	{
		$bWritten = false;

		$oStatus = $aRowData["__STATUS__"];
		switch(get_class($oStatus))
		{
		case 'RowStatus_NoChange':
			$iCountUnchanged++;
			break;
		case 'RowStatus_Modify':
			$iCountUpdates++;
			$bWritten = true;
			break;
		case 'RowStatus_NewObj':
			$iCountCreations++;
			$bWritten = true;
			break;
		case 'RowStatus_Issue':
			$iCountErrors++;
			break;		
		}

		if ($bWritten)
		{
			// Something has been done, still there may be some issues to report
			foreach($aRowData as $key => $value)
			{
				if (!is_object($value)) continue;

				switch (get_class($value))
				{
					case 'CellStatus_Void':
					case 'CellStatus_Modify':
						break;
					case 'CellStatus_Issue':
					case 'CellStatus_SearchIssue':
					case 'CellStatus_NullIssue':
					case 'CellStatus_Ambiguous':
						$iCountWarnings++;
						break;
				}
			}
		}
	}

	//////////////////////////////////////////////////
	//
	// Summary of settings and results
	//
	if ($sOutput == 'retcode')
	{
		$oP->add($iCountErrors);
	}

	if (($sOutput == "summary") || ($sOutput == 'details'))
	{
		$oP->add_comment("Change tracking comment: ".$sComment);
		$oP->add_comment("Issues: ".$iCountErrors);
		$oP->add_comment("Warnings: ".$iCountWarnings);
		$oP->add_comment("Created: ".$iCountCreations);
		$oP->add_comment("Updated: ".$iCountUpdates);
		$oP->add_comment("Unchanged: ".$iCountUnchanged);
	}


	if ($sOutput == 'details')
	{
		// Setup result presentation
		//
		$aDisplayConfig = array();
		$aDisplayConfig["__LINE__"] = array("label"=>"Line", "description"=>"");
		$aDisplayConfig["__STATUS__"] = array("label"=>"Status", "description"=>"");
		$aDisplayConfig["__OBJECT_CLASS__"] = array("label"=>"Object Class", "description"=>"");
		$aDisplayConfig["__OBJECT_ID__"] = array("label"=>"Object Id", "description"=>"");
		foreach($aExtKeys as $sExtKeyAttCode => $aRemoteAtt)
		{
			$sLabel = MetaModel::GetAttributeDef($sClass, $sExtKeyAttCode)->GetLabel();
			$aDisplayConfig["$sExtKeyAttCode"] = array("label"=>$sExtKeyAttCode, "description"=>$sLabel." - ext key");
		}
		foreach($aFinalReconcilKeys as $iCol => $sAttCode)
		{
	//		$sLabel = MetaModel::GetAttributeDef($sClass, $sAttCode)->GetLabel();
	//		$aDisplayConfig["$iCol"] = array("label"=>"$sLabel", "description"=>"");
		}
		foreach ($aAttList as $sAttCode => $iCol)
		{
			if ($sAttCode == 'id')
			{
				$sLabel = Dict::S('UI:CSVImport:idField');

				$aDisplayConfig["$iCol"] = array("label"=>$sAttCode, "description"=>$sLabel);
			}
			else
			{
				$sLabel = MetaModel::GetAttributeDef($sClass, $sAttCode)->GetLabel();
				$aDisplayConfig["$iCol"] = array("label"=>$sAttCode, "description"=>$sLabel);
			}
		}
	
		$aResultDisp = array(); // to be displayed
		foreach($aRes as $iRow => $aRowData)
		{
			$aRowDisp = array();
			$aRowDisp["__LINE__"] = $iRow;
			if (is_object($aRowData["__STATUS__"]))
			{
				$aRowDisp["__STATUS__"] = $aRowData["__STATUS__"]->GetDescription();
			}
			else
			{
				$aRowDisp["__STATUS__"] = "*No status available*";
			}
			if (isset($aRowData["finalclass"]) && isset($aRowData["id"]))
			{
				$aRowDisp["__OBJECT_CLASS__"] = $aRowData["finalclass"];
				$aRowDisp["__OBJECT_ID__"] = $aRowData["id"]->GetDisplayableValue();
			}
			else
			{
				$aRowDisp["__OBJECT_CLASS__"] = "n/a";
				$aRowDisp["__OBJECT_ID__"] = "n/a";
			}
			foreach($aRowData as $key => $value)
			{
				$sKey = (string) $key;
	
				if ($sKey == '__STATUS__') continue;
				if ($sKey == 'finalclass') continue;
				if ($sKey == 'id') continue;
	
				if (is_object($value))
				{
					$aRowDisp["$sKey"] = $value->GetDisplayableValue().$value->GetDescription();
				}
				else
				{
					$aRowDisp["$sKey"] = $value;
				}
			}
			$aResultDisp[$iRow] = $aRowDisp;
		}
		$oP->table($aDisplayConfig, $aResultDisp);
	}
}
catch(BulkLoadException $e)
{
	$oP->add_comment($e->getMessage());		
}
catch(SecurityException $e)
{
	$oP->add_comment($e->getMessage());		
}
catch(Exception $e)
{
	$oP->add_comment((string)$e);		
}

$oP->output();
?>
