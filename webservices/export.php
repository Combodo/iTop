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
 * Export data specified by an OQL
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

if (!defined('__DIR__')) define('__DIR__', dirname(__FILE__));
require_once(__DIR__.'/../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/nicewebpage.class.inc.php');
require_once(APPROOT.'/application/csvpage.class.inc.php');
require_once(APPROOT.'/application/xmlpage.class.inc.php');
require_once(APPROOT.'/application/clipage.class.inc.php');

require_once(APPROOT.'/application/startup.inc.php');

try
{
	// Do this before loging, in order to allow setting user credentials from within the file
	utils::UseParamFile();
}
catch(Exception $e)
{
	echo "Error: ".$e->GetMessage()."<br/>\n";
	exit -2;
}

if (utils::IsModeCLI()) 
{
	$sAuthUser = utils::ReadParam('auth_user', null, true /* Allow CLI */, 'raw_data'); 
	$sAuthPwd = utils::ReadParam('auth_pwd', null, true /* Allow CLI */, 'raw_data'); 

	if (UserRights::CheckCredentials($sAuthUser, $sAuthPwd)) 
	{ 
		UserRights::Login($sAuthUser); // Login & set the user's language 
	} 
	else 
	{ 
		$oP = new CLIPage("iTop - Export"); 
		$oP->p("Access restricted or wrong credentials ('$sAuthUser')"); 
		$oP->output(); 
		exit -1; 
	}
} 
else 
{
	require_once(APPROOT.'/application/loginwebpage.class.inc.php'); 
	LoginWebPage::DoLogin(); // Check user rights and prompt if needed 
}
ApplicationContext::SetUrlMakerClass('iTopStandardURLMaker');


$oAppContext = new ApplicationContext();
$iActiveNodeId = utils::ReadParam('menu', -1);
$currentOrganization = utils::ReadParam('org_id', '');

$bLocalize = (utils::ReadParam('no_localize', 0) != 1);
$sFileName = utils::ReadParam('filename', '', true, 'string');

// Main program
$sExpression = utils::ReadParam('expression', '', true /* Allow CLI */, 'raw_data');
$sFields = trim(utils::ReadParam('fields', '', true, 'raw_data')); // CSV field list (allows to specify link set attributes, still not taken into account for XML export)
$bFieldsAdvanced = utils::ReadParam('fields_advanced', 0);

if (strlen($sExpression) == 0)
{
	$sQueryId = trim(utils::ReadParam('query', '', true /* Allow CLI */, 'raw_data'));
	if (strlen($sQueryId) > 0)
	{
		$oSearch = DBObjectSearch::FromOQL('SELECT QueryOQL WHERE id = :query_id', array('query_id' => $sQueryId));
		$oQueries = new DBObjectSet($oSearch);
		if ($oQueries->Count() > 0)
		{
			$oQuery = $oQueries->Fetch();
			$sExpression = $oQuery->Get('oql');
			if (strlen($sFields) == 0)
			{
				$sFields = trim($oQuery->Get('fields'));
			} 
		}
	}
}

$sFormat = strtolower(utils::ReadParam('format', 'html', true /* Allow CLI */));


$aFields = explode(',', $sFields);
// Clean the list of columns (empty it if every string is empty)
foreach($aFields as $index => $sField)
{
	$aFields[$index] = trim($sField);
	if(strlen($aFields[$index]) == 0)
	{
		unset($aFields[$index]);
	}
}

$oP = null;

if (!empty($sExpression))
{
	try
	{
		$oFilter = DBObjectSearch::FromOQL($sExpression);

		// Check and adjust column names
		//
		foreach($aFields as $index => $sField)
		{
			if (preg_match('/^(.*)\.(.*)$/', $sField, $aMatches))
			{
				$sClassAlias = $aMatches[1];
				$sAttCode = $aMatches[2];
			}
			else
			{
				$sClassAlias = $oFilter->GetClassAlias();
				$sAttCode = $sField;
				$aFields[$index] = $sClassAlias.'.'.$sAttCode;
			}
			$sClass = $oFilter->GetClassName($sClassAlias);
			if (!MetaModel::IsValidAttCode($sClass, $sAttCode))
			{
				throw new CoreException("Invalid field specification $sField: $sAttCode is not a valid attribute for $sClass");
			}
		}

		// Read query parameters
		//
		$aArgs = array();
		foreach($oFilter->GetQueryParams() as $sParam => $foo)
		{
			$value = utils::ReadParam('arg_'.$sParam, null, true, 'raw_data');
			if (!is_null($value))
			{
				$aArgs[$sParam] = $value;
			}
		}
		$oFilter->SetInternalParams($aArgs);

		if ($oFilter)
		{
			$oSet = new CMDBObjectSet($oFilter, array(), $aArgs);
			switch($sFormat)
			{
				case 'html':
				$oP = new NiceWebPage("iTop - Export");

				// Integration within MS-Excel web queries + HTTPS + IIS:
				// MS-IIS set these header values with no-cache... while Excel fails to do the job if using HTTPS
				// Then the fix is to force the reset of header values Pragma and Cache-control 
				header("Pragma:", true);
				header("Cache-control:", true);

				// The HTML output is made for pages located in the /pages/ folder
				// since this page is in a different folder, let's adjust the HTML 'base' attribute
				// to make the relative hyperlinks in the page work
				$sUrl = utils::GetAbsoluteUrlAppRoot();
				$oP->set_base($sUrl.'pages/');

				if(count($aFields) > 0)
				{
					$iSearch = array_search('id', $aFields);
					if ($iSearch !== false)
					{
						$bViewLink = true;
						unset($aFields[$iSearch]);
					}
					else
					{
						$bViewLink = false;
					}
					$sFields = implode(',', $aFields);
					$aExtraParams = array('menu' => false, 'toolkit_menu' => false, 'display_limit' => false, 'localize_values' => $bLocalize, 'zlist' => false, 'extra_fields' => $sFields, 'view_link' => $bViewLink);
				}
				else
				{
					$aExtraParams = array('menu' => false, 'toolkit_menu' => false, 'display_limit' => false, 'localize_values' => $bLocalize, 'zlist' => 'details');
				}

				$oResultBlock = new DisplayBlock($oFilter, 'list', false, $aExtraParams);
				$oResultBlock->Display($oP, 'expresult');
				break;
				
				case 'csv':
				$oP = new CSVPage("iTop - Export");
				$sFields = implode(',', $aFields);
				$sCSVData = cmdbAbstractObject::GetSetAsCSV($oSet, array('fields' => $sFields, 'fields_advanced' => $bFieldsAdvanced, 'localize_values' => $bLocalize));
				$sCharset = MetaModel::GetConfig()->Get('csv_file_default_charset');
				if ($sCharset == 'UTF-8')
				{
					$sOutputData = UTF8_BOM.iconv('UTF-8', 'UTF-8//IGNORE//TRANSLIT', $sCSVData);
				}
				else
				{
					$sOutputData = iconv('UTF-8', $sCharset.'//IGNORE//TRANSLIT', $sCSVData);
				}
				if ($sFileName == '')
				{
					// Plain text => Firefox will NOT propose to download the file
					$oP->add_header("Content-type: text/plain; charset=$sCharset");
				}
				else
				{
					$oP->add_header("Content-type: text/csv; charset=$sCharset");
				}
				$oP->add($sOutputData);
				break;
				
				case 'spreadsheet':
				$oP = new WebPage("iTop - Export for spreadsheet");

				// Integration within MS-Excel web queries + HTTPS + IIS:
				// MS-IIS set these header values with no-cache... while Excel fails to do the job if using HTTPS
				// Then the fix is to force the reset of header values Pragma and Cache-control 
				header("Pragma:", true);
				header("Cache-control:", true);

				$sFields = implode(',', $aFields);
				$oP->add_style('table br {mso-data-placement:same-cell;}'); // Trick for Excel: keep line breaks inside the same cell !
				cmdbAbstractObject::DisplaySetAsHTMLSpreadsheet($oP, $oSet, array('fields' => $sFields, 'fields_advanced' => $bFieldsAdvanced, 'localize_values' => $bLocalize));
				break;

				case 'xml':
				$oP = new XMLPage("iTop - Export", true /* passthrough */);
				cmdbAbstractObject::DisplaySetAsXML($oP, $oSet, array('localize_values' => $bLocalize));
				break;
				
				default:
				$oP = new WebPage("iTop - Export");
				$oP->add("Unsupported format '$sFormat'. Possible values are: html, csv, spreadsheet or xml.");
			}
		}
	}
	catch(Exception $e)
	{
		$oP = new WebPage("iTop - Export");
		$oP->p("Error the query can not be executed.");
		if ($e instanceof CoreException)
		{		
			$oP->p($e->GetHtmlDesc());
		}
		else
		{
			$oP->p($e->getMessage());
		}		
	}
}
if (!$oP)
{
	// Display a short message about how to use this page
	$bModeCLI = utils::IsModeCLI();
	if ($bModeCLI)
	{
		$oP = new CLIPage("iTop - Export");
	}
	else
	{
		$oP = new WebPage("iTop - Export");
	} 
	$oP->p("General purpose export page.");
	$oP->p("Parameters:");
	$oP->p(" * expression: an OQL expression (URL encoded if needed)");
	$oP->p(" * query: (alternative to 'expression') the id of an entry from the query phrasebook");
	$oP->p(" * arg_xxx: (needed if the query has parameters) the value of the parameter 'xxx'");
	$oP->p(" * format: (optional, default is html) the desired output format. Can be one of 'html', 'spreadsheet', 'csv' or 'xml'");
	$oP->p(" * fields: (optional, no effect on XML format) list of fields (attribute codes, or alias.attcode) separated by a coma");
	$oP->p(" * fields_advanced: (optional, no effect on XML/HTML formats ; ignored is fields is specified) If set to 1, the default list of fields will include the external keys and their reconciliation keys");
	$oP->p(" * filename: (optional, no effect in CLI mode) if set then the results will be downloaded as a file");
}

if ($sFileName != '')
{
	$oP->add_header('Content-Disposition: attachment; filename="'.$sFileName.'"');
}

$oP->TrashUnexpectedOutput();
$oP->output();
?>
