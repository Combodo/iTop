<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Import web service 
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
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

require_once('../approot.inc.php');
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
		'default' => 'UTF-8',
		'description' => 'Character set encoding of the CSV data: UTF-8, ISO-8859-1, WINDOWS-1251, WINDOWS-1252, ISO-8859-15',
	),
	'separator' => array
	(
		'mandatory' => false,
		'modes' => 'http,cli',
		'default' => ',',
		'description' => 'column separator in CSV data',
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


function ReadParam($oP, $sParam)
{
	global $aPageParams;
	assert(isset($aPageParams[$sParam]));
	assert(!$aPageParams[$sParam]['mandatory']);
	$sValue = utils::ReadParam($sParam, $aPageParams[$sParam]['default'], true /* Allow CLI */);
	return trim($sValue);
}

function ReadMandatoryParam($oP, $sParam)
{
	global $aPageParams;
	assert(isset($aPageParams[$sParam]));
	assert($aPageParams[$sParam]['mandatory']);

	$sValue = utils::ReadParam($sParam, null, true /* Allow CLI */);
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

	// Next steps:
	//   specific arguments: 'csvfile'
	//   
	$sAuthUser = ReadMandatoryParam($oP, 'auth_user');
	$sAuthPwd = ReadMandatoryParam($oP, 'auth_pwd');
	$sCsvFile = ReadMandatoryParam($oP, 'csvfile');
	if (UserRights::CheckCredentials($sAuthUser, $sAuthPwd))
	{
		UserRights::Login($sAuthUser); // Login & set the user's language
	}
	else
	{
		$oP->p("Access restricted or wrong credentials ('$sAuthUser')");
		exit;
	}

	if (!is_readable($sCsvFile))
	{
		$oP->p("Input file could not be found or could not be read: '$sCsvFile'");
		exit;
	}
	$sCSVData = file_get_contents($sCsvFile);

}
else
{
	$_SESSION['login_mode'] = 'basic';
	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	LoginWebPage::DoLogin(); // Check user rights and prompt if needed

	$oP = new CSVPage("iTop - Bulk import");
	$sCSVData = utils::ReadPostedParam('csvdata');
}


try
{
	//////////////////////////////////////////////////
	//
	// Read parameters
	//
	$sClass = ReadMandatoryParam($oP, 'class');
	$sSep = ReadParam($oP, 'separator');
	$sQualifier = ReadParam($oP, 'qualifier');
	$sCharSet = ReadParam($oP, 'charset');
	$sOutput = ReadParam($oP, 'output');
//	$sReportLevel = ReadParam($oP, 'reportlevel');
	$sReconcKeys = ReadParam($oP, 'reconciliationkeys');
	$sSimulate = ReadParam($oP, 'simulate');
	$sComment = ReadParam($oP, 'comment');

	//////////////////////////////////////////////////
	//
	// Check parameters format/consistency
	//
	if (strlen($sCSVData) == 0)
	{
		throw new ExchangeException("Missing data - at least one line is expected");
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

/*
	$aReportLevels = explode('|', $sReportLevel);
	foreach($aReportLevels as $sLevel)
	{
		if (!in_array($sLevel, explode('|', 'errors|warnings|created|changed|unchanged')))
		{
			throw new BulkLoadException("Unknown level in reporting level: '$sLevel'");
		}
	}
*/

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
	// Make translated header reference
	//
	$aFriendlyToInternalAttCode = array();
	foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
	{
	  	$aFriendlyToInternalAttCode[strtolower(BulkChange::GetFriendlyAttCodeName($sClass, $sAttCode))] = $sAttCode;
	  	if ($oAttDef->IsExternalKey(EXTKEY_RELATIVE))
	  	{
	  		$sRemoteClass = $oAttDef->GetTargetClass();
			foreach(MetaModel::ListAttributeDefs($sRemoteClass) as $sRemoteAttCode => $oRemoteAttDef)
		  	{
		  		$sAttCodeEx = $sAttCode.'->'.$sRemoteAttCode;
		  		$aFriendlyToInternalAttCode[strtolower(BulkChange::GetFriendlyAttCodeName($sClass, $sAttCodeEx))] = $sAttCodeEx;
		  	}
		}
   }
   
	//////////////////////////////////////////////////
	//
	// Parse first line, check attributes, analyse the request
	//
	if ($sCharSet == 'UTF-8')
	{
		$sUTF8Data = $sCSVData;		
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
		if (array_key_exists(strtolower($sFieldName), $aFriendlyToInternalAttCode))
		{
			$aFieldList[$iFieldId] = $aFriendlyToInternalAttCode[strtolower($sFieldName)];
		}
		else
		{
			$aFieldList[$iFieldId] = $sFieldName;
		}
	}	

	$aAttList = array();
	$aExtKeys = array();
	foreach($aFieldList as $iFieldId => $sFieldName)
	{
		$aMatches = array();
		if (preg_match('/^(.+)\*$/', $sFieldName, $aMatches))
		{
			// Ignore any trailing "star" (*) that simply indicates a mandatory field
			$sFieldName = $aMatches[1];
		}
		if (preg_match('/^(.+)->(.+)$/', trim($sFieldName), $aMatches))
		{
			// The column has been specified as "extkey->attcode"
			//
			$sExtKeyAttCode = $aMatches[1];
			$sRemoteAttCode = $aMatches[2];
			if (!MetaModel::IsValidAttCode($sClass, $sExtKeyAttCode))
			{
				throw new BulkLoadException("Unknown attribute '$sExtKeyAttCode' (class: '$sClass')");
			}
			$oAtt = MetaModel::GetAttributeDef($sClass, $sExtKeyAttCode);
			if (!$oAtt->IsExternalKey())
			{
				throw new BulkLoadException("Not an external key '$sExtKeyAttCode' (class: '$sClass')");
			}
			$sTargetClass = $oAtt->GetTargetClass();
			if (!MetaModel::IsValidAttCode($sTargetClass, $sRemoteAttCode))
			{
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
				$aReconcSpec[] = $sReconcKeyAttCode;
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

		if (!in_array($sReconcKey, $aFieldList))
		{
			throw new BulkLoadException("Reconciliation keys not found in the input columns '$sReconcKey' (class: '$sClass')");
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
				// Safety net: should never happen, but...
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
	}

	$oBulk = new BulkChange(
		$sClass,
		$aData,
		$aAttList,
		$aExtKeys,
		$aFinalReconcilKeys
	);

	if ($bSimulate)
	{
		$oMyChange = null;
	}
	else
	{
		$oMyChange = MetaModel::NewObject("CMDBChange");
		$oMyChange->Set("date", time());
		$sUserString = CMDBChange::GetCurrentUserName();
		if (strlen($sComment) > 0)
		{
			$sMoreInfo = 'Web Service (CSV) - '.$sComment;
		}
		else
		{
			$sMoreInfo = 'Web Service (CSV)';
		}
		$oMyChange->Set("userinfo", $sUserString.', '.$sMoreInfo);
		$iChangeId = $oMyChange->DBInsert();
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
//		$oP->add_comment("Report level: ".$sReportLevel);
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
