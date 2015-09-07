<?php
// Copyright (C) 2015 Combodo SARL
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
 * Export data specified by an OQL or a query phrasebook entry
 *
 * @copyright   Copyright (C) 2015 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

if (!defined('__DIR__')) define('__DIR__', dirname(__FILE__));
require_once(__DIR__.'/../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/nicewebpage.class.inc.php');
require_once(APPROOT.'/application/ajaxwebpage.class.inc.php');
require_once(APPROOT.'/application/csvpage.class.inc.php');
require_once(APPROOT.'/application/itopwebpage.class.inc.php');
require_once(APPROOT.'/application/xmlpage.class.inc.php');
require_once(APPROOT.'/application/clipage.class.inc.php');
require_once(APPROOT.'/application/excelexporter.class.inc.php');
require_once(APPROOT.'/core/bulkexport.class.inc.php');

require_once(APPROOT.'/application/startup.inc.php');

function ReportErrorAndExit($sErrorMessage)
{
	if (utils::IsModeCLI())
	{
		$oP = new CLIPage("iTop - Export");
		$oP->p('ERROR: '.$sErrorMessage);
		$oP->output();
		exit -1;
	}
	else
	{
		$oP = new WebPage("iTop - Export");
		$oP->p('ERROR: '.$sErrorMessage);
		$oP->output();
		exit -1;		
	}
}

function ReportErrorAndUsage($sErrorMessage)
{
	if (utils::IsModeCLI())
	{
		$oP = new CLIPage("iTop - Export");
		$oP->p('ERROR: '.$sErrorMessage);
		Usage($oP);
		$oP->output();
		exit -1;
	}
	else
	{
		$oP = new WebPage("iTop - Export");
		$oP->p('ERROR: '.$sErrorMessage);
		Usage($oP);
		$oP->output();
		exit -1;
	}
}

function Usage(Page $oP)
{
	if (Utils::IsModeCLI())
	{
		$oP->p('Usage: php '.basename(__FILE__).' --auth_user=<user> --auth_pwd=<password> --expression=<OQL Query> --query=<phrasebook_id> [--arg_xxx=<query_arguments>] [--no_localize=0|1] [--format=<format>] [--format-options...]');
		$oP->p("Parameters:");
		$oP->p(" * auth_user: the iTop user account for authentication");
		$oP->p(" * auth_pwd: the password of the iTop user account");
	}
	else
	{
		$oP->p("Parameters:");
	}
	$oP->p(" * expression: an OQL expression (e.g. SELECT Contact WHERE name LIKE 'm%')");
	$oP->p(" * query: (alternative to 'expression') the id of an entry from the query phrasebook");
	$oP->p(" * arg_xxx: (needed if the query has parameters) the value of the parameter 'xxx'");
	$aSupportedFormats = BulkExport::FindSupportedFormats();
	$oP->p(" * format: (optional, default is html) the desired output format. Can be one of '".implode("', '", array_keys($aSupportedFormats))."'");
	foreach($aSupportedFormats as $sFormatCode => $sLabel)
	{
		$oExporter = BulkExport::FindExporter($sFormatCode);
		if ($oExporter !== null)
		{
			if (!Utils::IsModeCLI())
			{
				$oP->add('<hr/>');
			}
			$oExporter->DisplayUsage($oP);
			if (!Utils::IsModeCLI())
			{
				$oP->add('</div>');
			}
		}
	}
	if (!Utils::IsModeCLI())
	{
		//$oP->add('</pre>');
	}
}

function DisplayExpressionForm(WebPage $oP, $sAction, $sExpression = '', $sExceptionMessage = '')
{
	$oP->add('<fieldset><legend>'.Dict::S('Core:BulkExport:ScopeDefinition').'</legend>');
	$oP->add('<form id="export-form" action="'.$sAction.'" method="post">');
	$oP->add('<input type="hidden" name="interactive" value="1">');
	$oP->add('<table style="width:100%" class="export_parameters">');
	$sExpressionHint = empty($sExceptionMessage) ? '' : '<tr><td colspan="2">'.htmlentities($sExceptionMessage, ENT_QUOTES, 'UTF-8').'</td></tr>';
	$oP->add('<tr><td class="column-label"><span style="white-space: nowrap;"><input type="radio" name="query_mode" value="oql" id="radio_oql" checked><label for="radio_oql">'.Dict::S('Core:BulkExportLabelOQLExpression').'</label></span></td>');
	$oP->add('<td><textarea style="width:100%" cols="70" rows="8" name="expression" id="textarea_oql" placeholder="'.Dict::S('Core:BulkExportQueryPlaceholder').'">'.htmlentities($sExpression, ENT_QUOTES, 'UTF-8').'</textarea></td></tr>');
	$oP->add($sExpressionHint);
	$oP->add('<tr><td class="column-label"><span style="white-space: nowrap;"><input type="radio" name="query_mode" value="phrasebook" id="radio_phrasebook"><label for="radio_phrasebook">'.Dict::S('Core:BulkExportLabelPhrasebookEntry').'</label></span></td>');
	$oP->add('<td><select name="query" id="select_phrasebook">');
	$oP->add('<option value="">'.Dict::S('UI:SelectOne').'</option>');
	$oSearch = DBObjectSearch::FromOQL('SELECT QueryOQL');
	$oQueries = new DBObjectSet($oSearch);
	while ($oQuery = $oQueries->Fetch())
	{
		$oP->add('<option value="'.$oQuery->GetKey().'">'.htmlentities($oQuery->Get('name'), ENT_QUOTES, 'UTF-8').'</option>');
	}
	$oP->add('</select></td></tr>');	
	$oP->add('<tr><td colspan="2" style="text-align:right"><button type="submit" id="next-btn">'.Dict::S('UI:Button:Next').'</button></td></tr>');
	$oP->add('</table>');
	$oP->add('</form>');
	$oP->add('</fieldset>');
	$oP->p('<a target="_blank" href="'.utils::GetAbsoluteUrlAppRoot().'webservices/export-v2.php">'.Dict::S('Core:BulkExportCanRunNonInteractive').'</a>');
	$oP->p('<a target="_blank" href="'.utils::GetAbsoluteUrlAppRoot().'webservices/export.php">'.Dict::S('Core:BulkExportLegacyExport').'</a>');
	$sJSEmptyOQL = json_encode(Dict::S('Core:BulkExportMessageEmptyOQL'));
	$sJSEmptyQueryId = json_encode(Dict::S('Core:BulkExportMessageEmptyPhrasebookEntry'));
	
	$oP->add_ready_script(
<<<EOF
var colWidth = 0;
$('td.column-label').each(function() {
	var jLabel = $(this).find('span');
	colWidth = Math.max(colWidth, jLabel.width());
});
$('td.column-label').each(function() {
	var jLabel = $(this).width(colWidth);
});
		
$('#textarea_oql').on('change keyup', function() {
	$('#radio_oql').prop('checked', true);
});
$('#select_phrasebook').on('change', function() {
	$('#radio_phrasebook').prop('checked', true);
});
$('#export-form').on('submit', function() {
	if ($('#radio_oql').prop('checked'))
	{
		var sOQL = $('#textarea_oql').val();
		if (sOQL == '')
		{
			alert($sJSEmptyOQL);
			return false;
		}
	}
	else
	{
		var sQueryId = $('#select_phrasebook').val();
		if (sQueryId == '')
		{
			alert($sJSEmptyQueryId);
			return false;
		}
	}
	return true;
});
EOF
	);
}

function DisplayForm(WebPage $oP, $sAction = '', $sExpression = '', $sQueryId = '', $sFormat = null)
{
	$oExportSearch = null;
	$oP->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/tabularfieldsselector.js');
	$oP->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.dragtable.js');
	$oP->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'css/dragtable.css');
	$oP->add('<form id="export-form" action="'.$sAction.'" method="post" data-state="not-yet-started">');
	$bExpressionIsValid = true;
	$sExpressionError = '';
	if (($sExpression === null) && ($sQueryId === null))
	{
		$bExpressionIsValid = false;	
	}
	else if ($sExpression !== '')
	{
		try
		{
			$oExportSearch = DBObjectSearch::FromOQL($sExpression);
		}
		catch(OQLException $e)
		{
			$bExpressionIsValid = false;
			$sExpressionError = $e->getMessage();
		}
	}
	
	if (!$bExpressionIsValid)
	{
		DisplayExpressionForm($oP, $sAction, $sExpression, $sExpressionError);
		return;
	}
	
	if ($sExpression !== '')
	{
		$oP->add('<input type="hidden" name="expression" value="'.htmlentities($sExpression, ENT_QUOTES, 'UTF-8').'">');
		$oExportSearch = DBObjectSearch::FromOQL($sExpression);
	}
	else
	{
		$oQuery = MetaModel::GetObject('QueryOQL', $sQueryId);
		$oExportSearch = DBObjectSearch::FromOQL($oQuery->Get('oql'));
		$oP->add('<input type="hidden" name="query" value="'.htmlentities($sQueryId, ENT_QUOTES, 'UTF-8').'">');
	}
	$aFormPartsByFormat = array();
	$aAllFormParts = array();
	if ($sFormat == null)
	{
		// No specific format chosen
		$sDefaultFormat = utils::ReadParam('format', 'xlsx');
		$oP->add('<p>'.Dict::S('Core:BulkExport:ExportFormatPrompt').' <select name="format" id="format_selector">');
		$aSupportedFormats = BulkExport::FindSupportedFormats();
		asort($aSupportedFormats);
		foreach($aSupportedFormats as $sFormatCode => $sLabel)
		{
			$sSelected = ($sFormatCode == $sDefaultFormat) ? 'selected' : '';
			$oP->add('<option value="'.$sFormatCode.'" '.$sSelected.'>'.htmlentities($sLabel, ENT_QUOTES, 'UTF-8').'</option>');
			$oExporter = BulkExport::FindExporter($sFormatCode);
			$oExporter->SetObjectList($oExportSearch);
			$aParts = $oExporter->EnumFormParts();
			foreach($aParts as $sPartId => $void)
			{
				$aAllFormParts[$sPartId] = $oExporter;
			}
			$aFormPartsByFormat[$sFormatCode] = array_keys($aParts);
		}
		$oP->add('</select></p>');
	}
	else 
	{
		// One specific format was chosen
		$oP->add('<input type="hidden" name="format" value="'.htmlentities($sFormat, ENT_QUOTES, 'UTF-8').'">');
		
		$oExporter = BulkExport::FindExporter($sFormat, $oExportSearch);
		$aParts = $oExporter->EnumFormParts();
		foreach($aParts as $sPartId => $void)
		{
			$aAllFormParts[$sPartId] = $oExporter;
		}
		$aFormPartsByFormat[$sFormat] = array_keys($aAllFormParts);
	}
	foreach($aAllFormParts as $sPartId => $oExport)
	{
		$oP->add('<div class="form_part" id="form_part_'.$sPartId.'">');
		$oExport->DisplayFormPart($oP, $sPartId);
		$oP->add('</div>');
	}
	$oP->add('</form>');
	$oP->add('<div id="export-feedback" style="display:none;"><p class="export-message" style="text-align:center;">'.Dict::S('ExcelExport:PreparingExport').'</p><div class="export-progress-bar" style="max-width:30em; margin-left:auto;margin-right:auto;"><div class="export-progress-message" style="text-align:center;"></div></div></div>');
	$oP->add('<button type="button" id="export-btn">'.Dict::S('UI:Button:Export').'</button>');
	$oP->add('<div id="export_text_result" style="display:none;">');
	$oP->add('<div>'.Dict::S('Core:BulkExport:ExportResult').'</div>');
	$oP->add('<textarea id="export_content" style="width:100%;min-height:15em;"></textarea>');
	$oP->add('</div>');
	
	$sJSParts = json_encode($aFormPartsByFormat);
	$sJSCancel = json_encode(Dict::S('UI:Button:Cancel'));
	$sJSClose = json_encode(Dict::S('UI:Button:Done'));
	
	$oP->add_ready_script(
<<<EOF
window.aFormParts = $sJSParts;
$('#format_selector').on('change init', function() {
	ExportToggleFormat($(this).val());
}).trigger('init');
		
$('.export-progress-bar').progressbar({
	 value: 0,
	 change: function() {
		$('.export-progress-message').text( $(this).progressbar( "value" ) + "%" );
	 },
	 complete: function() {
		 $('.export-progress-message').text( '100 %' );
	 }
});

ExportInitButton('#export-btn');

EOF
	);
	
}

function InteractiveShell($sExpression, $sQueryId, $sFormat, $sFileName, $sMode)
{
	if ($sMode == 'dialog')
	{
		$oP = new ajax_page('');
		$oP->add('<div id="interactive_export_dlg">');
		$sExportBtnLabel = json_encode(Dict::S('UI:Button:Export'));
		$sJSTitle = json_encode(htmlentities(utils::ReadParam('dialog_title', '', false, 'raw_data'), ENT_QUOTES, 'UTF-8'));
		$oP->add_ready_script(
<<<EOF
		$('#interactive_export_dlg').dialog({
			autoOpen: true,
			modal: true,
			width: '80%',
			title: $sJSTitle,
			close: function() { $('#export-form').attr('data-state', 'cancelled'); $(this).remove(); },
			buttons: [
				{text: $sExportBtnLabel, id: 'export-dlg-submit', click: function() {} }
			]
		});
			
		setTimeout(function() { $('#interactive_export_dlg').dialog('option', { position: { my: "center", at: "center", of: window }}); $('#export-btn').hide(); ExportInitButton('#export-dlg-submit'); }, 100);
EOF
		);
	}
	else
	{
		$oP = new iTopWebPage('iTop Export');
	}
	
	if ($sExpression === null)
	{
		// No expression supplied, let's check if phrasebook entry is given
		if ($sQueryId !== null)
		{
			$oSearch = DBObjectSearch::FromOQL('SELECT QueryOQL WHERE id = :query_id', array('query_id' => $sQueryId));
			$oQueries = new DBObjectSet($oSearch);
			if ($oQueries->Count() > 0)
			{
				$oQuery = $oQueries->Fetch();
				$sExpression = $oQuery->Get('oql');
				$sFields = trim($oQuery->Get('fields'));
			}
			else
			{
				ReportErrorAndExit("Invalid query phrasebook identifier: '$sQueryId'");
			}
		}
		else
		{
			if (utils::IsModeCLI())
			{
				Usage();
				ReportErrorAndExit("No expression or query phrasebook identifier supplied.");
			}
			else
			{
				// form to enter an OQL query or pick a query phrasebook identifier
				DisplayForm($oP, utils::GetAbsoluteUrlAppRoot().'webservices/export-v2.php', $sExpression, $sQueryId, $sFormat);
				$oP->output();
				exit;
			}
		}
	}
	
	if ($sFormat !== null)
	{
		$oExporter = BulkExport::FindExporter($sFormat);
		if ($oExporter === null)
		{
			$aSupportedFormats = BulkExport::FindSupportedFormats();
			ReportErrorAndExit("Invalid output format: '$sFormat'. The supported formats are: ".implode(', ', array_keys($aSupportedFormats)));
		}
		else
		{
			DisplayForm($oP, utils::GetAbsoluteUrlAppRoot().'webservices/export-v2.php', $sExpression, $sQueryId, $sFormat);
		}
	}
	else
	{
		DisplayForm($oP, utils::GetAbsoluteUrlAppRoot().'webservices/export-v2.php', $sExpression, $sQueryId, $sFormat);
	}
	if ($sMode == 'dialog')
	{
		$oP->add('</div>');
	}
	$oP->output();	
}

/**
 * Checks the parameters and returns the appropriate exporter (if any)
 * @param string $sExpression The OQL query to export or null
 * @param string $sQueryId The entry in the query phrasebook if $sExpression is null
 * @param string $sFormat The code of export format: csv, pdf, html, xlsx
 * @throws MissingQueryArgument
 * @return Ambigous <iBulkExport, NULL>
 */
function CheckParameters($sExpression, $sQueryId, $sFormat)
{
	$oExporter  = null;	
	
	if (($sExpression === null) && ($sQueryId === null))
	{
		ReportErrorAndUsage("Missing parameter. The parameter 'expression' or 'query' must be specified.");
	}
	
	// Either $sExpression or $sQueryId must be specified
	if ($sExpression === null)
	{
		$oSearch = DBObjectSearch::FromOQL('SELECT QueryOQL WHERE id = :query_id', array('query_id' => $sQueryId));
		$oQueries = new DBObjectSet($oSearch);
		if ($oQueries->Count() > 0)
		{
			$oQuery = $oQueries->Fetch();
			$sExpression = $oQuery->Get('oql');
			$sFields = $oQuery->Get('fields');
			if (strlen($sFields) == 0)
			{
				$sFields = trim($oQuery->Get('fields'));
			}
		}
		else
		{
			ReportErrorAndExit("Invalid query phrasebook identifier: '$sQueryId'");
		}
	}
	if ($sFormat === null)
	{
		ReportErrorAndUsage("Missing parameter 'format'.");
	}
	
	// Check if the supplied query is valid (and all the parameters are supplied
	try
	{
		$oSearch = DBObjectSearch::FromOQL($sExpression);
		$aArgs = array();
		foreach($oSearch->GetQueryParams() as $sParam => $foo)
		{
			$value = utils::ReadParam('arg_'.$sParam, null, true, 'raw_data');
			if (!is_null($value))
			{
				$aArgs[$sParam] = $value;
			}
			else
			{
				throw new MissingQueryArgument("Missing parameter '--arg_$sParam'");
			}
		}
		$oSearch->SetInternalParams($aArgs);
	
		$sFormat = utils::ReadParam('format', 'html', true /* Allow CLI */, 'raw_data');
		$oExporter = BulkExport::FindExporter($sFormat, $oSearch);
		if ($oExporter == null)
		{
			$aSupportedFormats = BulkExport::FindSupportedFormats();
			ReportErrorAndExit("Invalid output format: '$sFormat'. The supported formats are: ".implode(', ', array_keys($aSupportedFormats)));
		}
	}
	catch(MissingQueryArgument $e)
	{
		ReportErrorAndUsage("Invalid OQL query: '$sExpression'.\n".$e->getMessage());
	}
	catch(OQLException $e)
	{
		ReportErrorAndExit("Invalid OQL query: '$sExpression'.\n".$e->getMessage());
	}
	catch(Exception $e)
	{
		ReportErrorAndExit($e->getMessage());
	}
	
	$oExporter->SetFormat($sFormat);
	$oExporter->SetChunkSize(EXPORTER_DEFAULT_CHUNK_SIZE);
	$oExporter->SetObjectList($oSearch);
	$oExporter->ReadParameters();
	
	return $oExporter;
}

function DoExport(WebPage $oP, BulkExport $oExporter, $bInteractive = false)
{
	$oExporter->SetHttpHeaders($oP);
	$exportResult = $oExporter->GetHeader();
	$aStatus = array();
	do
	{
		$exportResult .= $oExporter->GetNextChunk($aStatus);
	}
	while (($aStatus['code'] != 'done') && ($aStatus['code'] != 'error'));
	
	if ($aStatus['code'] == 'error')
	{
		$oExporter->Cleanup();
		ReportErrorAndExit("Export failed: '{$aStatus['message']}'");
	}
	else
	{
		$exportResult .= $oExporter->GetFooter();
		$sMimeType = $oExporter->GetMimeType();
		if (substr($sMimeType, 0, 5) == 'text/')
		{
			$sMimeType .= ';charset='.strtolower($oExporter->GetCharacterSet());
		}
		$oP->SetContentType($sMimeType);
		$oP->SetContentDisposition('attachment', $oExporter->GetDownloadFileName());
		$oP->add($exportResult);
		$oExporter->Cleanup();
	}
}


/////////////////////////////////////////////////////////////////////////////
//
// Command Line mode
//
/////////////////////////////////////////////////////////////////////////////
if (utils::IsModeCLI())
{
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
	
	$sAuthUser = utils::ReadParam('auth_user', null, true /* Allow CLI */, 'raw_data');
	$sAuthPwd = utils::ReadParam('auth_pwd', null, true /* Allow CLI */, 'raw_data');
	if ($sAuthUser == null)
	{
		ReportErrorAndUsage("Missing parameter '--auth_user'");
	}
	if ($sAuthPwd == null)
	{
		ReportErrorAndUsage("Missing parameter '--auth_pwd'");
	}
	
	if (UserRights::CheckCredentials($sAuthUser, $sAuthPwd))
	{
		UserRights::Login($sAuthUser); // Login & set the user's language
	}
	else
	{
		ReportErrorAndExit("Access restricted or wrong credentials for user '$sAuthUser'");
	}
	
	$sExpression = utils::ReadParam('expression', null, true /* Allow CLI */, 'raw_data');
	$sQueryId = utils::ReadParam('query', null, true /* Allow CLI */, 'raw_data');
	$bLocalize = (utils::ReadParam('no_localize', 0) != 1);
	
	if (($sExpression == null) && ($sQueryId == null))
	{
		ReportErrorAndUsage("Missing parameter. At least one of '--expression' or '--query' must be specified.");
	}
	
	if ($sExpression === null)
	{
		$oSearch = DBObjectSearch::FromOQL('SELECT QueryOQL WHERE id = :query_id', array('query_id' => $sQueryId));
		$oQueries = new DBObjectSet($oSearch);
		if ($oQueries->Count() > 0)
		{
			$oQuery = $oQueries->Fetch();
			$sExpression = $oQuery->Get('oql');
		}
		else
		{
			ReportErrorAndExit("Invalid query phrasebook identifier: '$sQueryId'");
		}		
	}
	try
	{
		$oSearch = DBObjectSearch::FromOQL($sExpression);
		$aArgs = array();
		foreach($oSearch->GetQueryParams() as $sParam => $foo)
		{
			$value = utils::ReadParam('arg_'.$sParam, null, true, 'raw_data');
			if (!is_null($value))
			{
				$aArgs[$sParam] = $value;
			}
			else
			{
				throw new MissingQueryArgument("Missing parameter '--arg_$sParam'");
			}
		}
		$oSearch->SetInternalParams($aArgs);

		$sFormat = utils::ReadParam('format', 'html', true /* Allow CLI */, 'raw_data');
		$oExporter = BulkExport::FindExporter($sFormat);
		if ($oExporter == null)
		{
			$aSupportedFormats = BulkExport::FindSupportedFormats();
			ReportErrorAndExit("Invalid output format: '$sFormat'. The supported formats are: ".implode(', ', array_keys($aSupportedFormats)));
		}
		
		$oExporter->SetFormat($sFormat);
		$oExporter->SetChunkSize(EXPORTER_DEFAULT_CHUNK_SIZE);
		$oExporter->SetObjectList($oSearch);
		$oExporter->ReadParameters();
		
		$exportResult = $oExporter->GetHeader();
		$aStatus = array();
		
		do
		{
			$exportResult .= $oExporter->GetNextChunk($aStatus);
		}
		while (($aStatus['code'] != 'done') && ($aStatus['code'] != 'error'));
		
		if ($aStatus['code'] == 'error')
		{
			ReportErrorAndExit("Export failed: '{$aStatus['message']}'");
		}
		else
		{
			$exportResult .= $oExporter->GetFooter();
			echo $exportResult;
		}
		$oExporter->Cleanup();
		
	}
	catch(MissingQueryArgument $e)
	{
		ReportErrorAndUsage("Invalid OQL query: '$sExpression'.\n".$e->getMessage());
	}
	catch(OQLException $e)
	{
		ReportErrorAndExit("Invalid OQL query: '$sExpression'.\n".$e->getMessage());
	}
	catch(Exception $e)
	{
		ReportErrorAndExit($e->getMessage());
	}
	
	exit;
}

/////////////////////////////////////////////////////////////////////////////
//
// Web Server mode
//
/////////////////////////////////////////////////////////////////////////////

try
{
	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	
	// Main parameters
	$sExpression = utils::ReadParam('expression', null, true /* Allow CLI */, 'raw_data');
	$sQueryId = utils::ReadParam('query', null, true /* Allow CLI */, 'raw_data');
	$sFormat = utils::ReadParam('format', null, true /* Allow CLI */);
	$sFileName = utils::ReadParam('filename', '', true, 'string');
	$bInteractive = utils::ReadParam('interactive', false);
	$sMode = utils::ReadParam('mode', '');

	LoginWebPage::DoLogin(); // Check user rights and prompt if needed

	ApplicationContext::SetUrlMakerClass('iTopStandardURLMaker');
	
	if ($bInteractive)
	{
		InteractiveShell($sExpression, $sQueryId, $sFormat, $sFileName, $sMode);
	}
	else 
	{
		$oExporter = CheckParameters($sExpression, $sQueryId, $sFormat);
		$sMimeType = $oExporter->GetMimeType();
		if ($sMimeType == 'text/html')
		{
			$oP = new NiceWebPage('iTop export');
			$oP->add_style("body { overflow: auto; }");
			$oP->add_ready_script("$('table.listResults').tablesorter({widgets: ['MyZebra']});");
		}
		else
		{
			$oP = new ajax_page('iTop export');
			$oP->SetContentType($oExporter->GetMimeType());
		}
		DoExport($oP, $oExporter, false);
		$oP->output();
	}
}
catch (BulkExportMissingParameterException $e)
{
	$oP = new ajax_page('iTop Export');
	$oP->add($e->getMessage());
	Usage($oP);
	$oP->output();
}
catch (Exception $e)
{
	$oP = new WebPage('iTop Export');
	$oP->add('Error: '.$e->getMessage());
	IssueLog::Error($e->getMessage()."\n".$e->getTraceAsString());
	$oP->output();
}