<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Field\FieldUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Form\FormUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectOptionUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\TextArea;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlockUIBlockFactory;
use Combodo\iTop\Application\WebPage\AjaxPage;
use Combodo\iTop\Application\WebPage\CLIPage;
use Combodo\iTop\Application\WebPage\DownloadPage;
use Combodo\iTop\Application\WebPage\iTopWebPage;
use Combodo\iTop\Application\WebPage\NiceWebPage;
use Combodo\iTop\Application\WebPage\Page;
use Combodo\iTop\Application\WebPage\WebPage;

require_once(__DIR__.'/../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/excelexporter.class.inc.php');
require_once(APPROOT.'/core/bulkexport.class.inc.php');

require_once(APPROOT.'/application/startup.inc.php');

const EXIT_CODE_ERROR = -1;
const EXIT_CODE_FATAL = -2;

function ReportErrorAndExit($sErrorMessage)
{
	if (utils::IsModeCLI())
	{
		$oP = new CLIPage("iTop - Export");
		$oP->p('ERROR: '.utils::HtmlEntities($sErrorMessage));
		$oP->output();
		exit(EXIT_CODE_ERROR);
	}
	else
	{
		$oP = new WebPage("iTop - Export");
		$oP->add_http_headers();
		$oP->p('ERROR: '.utils::HtmlEntities($sErrorMessage));
		$oP->output();
		exit(EXIT_CODE_ERROR);
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
		exit(EXIT_CODE_ERROR);
	}
	else {
		$oP = new WebPage("iTop - Export");
		$oP->add_http_headers();
		$oP->p('ERROR: '.$sErrorMessage);
		Usage($oP);
		$oP->output();
		exit(EXIT_CODE_ERROR);
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
	if (Utils::IsModeCLI())
	{
		$oP->p(" * with_archive: (optional, defaults to 0) if set to 1 then the result set will include archived objects");
	}
	else
	{
		$oP->p(" * with_archive: (optional, defaults to the current mode) if set to 1 then the result set will include archived objects");
	}
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
	//if (!Utils::IsModeCLI())
	//{
	//	$oP->add('</pre>');
	//}
}

function DisplayExpressionForm(WebPage $oP, $sAction, $sExpression = '', $sExceptionMessage = '', $oForm = null)
{
	$oPanel = PanelUIBlockFactory::MakeNeutral(Dict::S('Core:BulkExport:ScopeDefinition'));
	if ($oForm == null) {
		$oForm = FormUIBlockFactory::MakeStandard('export-form');
		$oForm->SetAction($sAction);
		$oP->AddSubBlock($oForm);
	}
	$oForm->AddSubBlock($oPanel);

	$oPanel->AddSubBlock(InputUIBlockFactory::MakeForHidden('interactive', '1'));

	$oFieldQuery = FieldUIBlockFactory::MakeStandard('<input type="radio" name="query_mode" value="oql" id="radio_oql" checked><label for="radio_oql">'.Dict::S('Core:BulkExportLabelOQLExpression').'</label>');
	$oTextArea = new TextArea('expression', utils::EscapeHtml($sExpression), "textarea_oql", 70, 8);
	$oTextArea->SetPlaceholder(Dict::S('Core:BulkExportQueryPlaceholder'));
	$oTextArea->AddCSSClasses(["ibo-input-text", "ibo-query-oql", "ibo-is-code"]);
	$oFieldQuery->AddSubBlock($oTextArea);
	$oPanel->AddSubBlock($oFieldQuery);
	if (!empty($sExceptionMessage)) {
		$oAlert = AlertUIBlockFactory::MakeForFailure($sExceptionMessage);
		$oAlert->SetIsCollapsible(false);
		$oPanel->AddSubBlock($oAlert);
	}

	$oFieldPhraseBook = FieldUIBlockFactory::MakeStandard('<input type="radio" name="query_mode" value="phrasebook" id="radio_phrasebook"><label for="radio_phrasebook">'.Dict::S('Core:BulkExportLabelPhrasebookEntry').'</label>');
	$oSelect = SelectUIBlockFactory::MakeForSelect('query', "select_phrasebook");
	$oSelect->AddSubBlock(SelectOptionUIBlockFactory::MakeForSelectOption("", Dict::S('UI:SelectOne'), false));

	$oSearch = DBObjectSearch::FromOQL('SELECT QueryOQL');
	$oSearch->UpdateContextFromUser();
	$oQueries = new DBObjectSet($oSearch);
	while ($oQuery = $oQueries->Fetch()) {
		$oSelect->AddSubBlock(SelectOptionUIBlockFactory::MakeForSelectOption($oQuery->GetKey(), $oQuery->Get('name'), false));
	}
	$oFieldPhraseBook->AddSubBlock($oSelect);
	$oPanel->AddSubBlock($oFieldPhraseBook);

	$oPanel->AddSubBlock(ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:Next'), "", "", true, "next-btn"));
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
			CombodoModal.OpenErrorModal($sJSEmptyOQL);
			return false;
		}
	}
	else
	{
		var sQueryId = $('#select_phrasebook').val();
		if (sQueryId == '')
		{
			CombodoModal.OpenErrorModal($sJSEmptyQueryId);
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
	$oP->add_script(DateTimeFormat::GetJSSQLToCustomFormat());
	$sJSDefaultDateTimeFormat = json_encode((string)AttributeDateTime::GetFormat());
	$oP->add_script(
		<<<EOF
function FormatDatesInPreview(sRadioSelector, sPreviewSelector)
{
	if ($('#'+sRadioSelector+'_date_time_format_default').prop('checked'))
	{
		sPHPFormat = $sJSDefaultDateTimeFormat;
	}
	else
	{
		sPHPFormat = $('#'+sRadioSelector+'_custom_date_time_format').val();
	}
	$('#interactive_fields_'+sPreviewSelector+' .user-formatted-date-time').each(function() {
		var val = $(this).attr('data-date');
		var sDisplay = DateTimeFormatFromPHP(val, sPHPFormat);
		$(this).html(sDisplay);
	});
	$('#interactive_fields_'+sPreviewSelector+' .user-formatted-date').each(function() {
		var val = $(this).attr('data-date');
		var sDisplay = DateFormatFromPHP(val, sPHPFormat);
		$(this).html(sDisplay);
	});
}
EOF
	);
	$oP->LinkScriptFromAppRoot('js/tabularfieldsselector.js');
	$oP->LinkScriptFromAppRoot('js/jquery.dragtable.js');
	$oP->LinkStylesheetFromAppRoot('css/dragtable.css');

	$oForm = FormUIBlockFactory::MakeStandard("export-form");
	$oForm->SetAction($sAction);
	$oForm->AddDataAttribute("state", "not-yet-started");
	$oP->AddSubBlock($oForm);

	$bExpressionIsValid = true;
	$sExpressionError = '';
	if (($sExpression === null) && ($sQueryId === null)) {
		$bExpressionIsValid = false;
	} else if ($sExpression !== '') {
		try {
			$oExportSearch = DBObjectSearch::FromOQL($sExpression);
			$oExportSearch->UpdateContextFromUser();
		}
		catch (OQLException $e) {
			$bExpressionIsValid = false;
			$sExpressionError = $e->getMessage();
		}
	}

	if (!$bExpressionIsValid) {
		DisplayExpressionForm($oP, $sAction, $sExpression, $sExpressionError,$oForm);

		return;
	}

	if ($sExpression !== '') {
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("expression", $sExpression));
		$oExportSearch = DBObjectSearch::FromOQL($sExpression);
		$oExportSearch->UpdateContextFromUser();
	} else {
		$oQuery = MetaModel::GetObject('QueryOQL', $sQueryId);
		$oExportSearch = DBObjectSearch::FromOQL($oQuery->Get('oql'));
		$oExportSearch->UpdateContextFromUser();
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("query", $sQueryId));
	}
	$aFormPartsByFormat = array();
	$aAllFormParts = array();
	if ($sFormat == null) {
		// No specific format chosen
		$sDefaultFormat = utils::ReadParam('format', 'xlsx');


		$oSelect = SelectUIBlockFactory::MakeForSelectWithLabel("format", Dict::S('Core:BulkExport:ExportFormatPrompt'), "format_selector");
		$oSelect->SetIsLabelBefore(true);
		$oForm->AddSubBlock($oSelect);

		$aSupportedFormats = BulkExport::FindSupportedFormats();
		asort($aSupportedFormats);
		foreach ($aSupportedFormats as $sFormatCode => $sLabel) {
			$oSelect->AddSubBlock(SelectOptionUIBlockFactory::MakeForSelectOption($sFormatCode, $sLabel, ($sFormatCode == $sDefaultFormat)));
			$oExporter = BulkExport::FindExporter($sFormatCode);
			$oExporter->SetObjectList($oExportSearch);
			$aParts = $oExporter->EnumFormParts();
			foreach ($aParts as $sPartId => $void) {
				$aAllFormParts[$sPartId] = $oExporter;
			}
			$aFormPartsByFormat[$sFormatCode] = array_keys($aParts);
		}

	} else {
		// One specific format was chosen
		$oSelect = InputUIBlockFactory::MakeForHidden("format", utils::EscapeHtml($sFormat));
		$oForm->AddSubBlock($oSelect);

		$oExporter = BulkExport::FindExporter($sFormat, $oExportSearch);
		$aParts = $oExporter->EnumFormParts();
		foreach ($aParts as $sPartId => $void) {
			$aAllFormParts[$sPartId] = $oExporter;
		}
		$aFormPartsByFormat[$sFormat] = array_keys($aAllFormParts);
	}
	foreach ($aAllFormParts as $sPartId => $oExport) {
		$UIContentBlock = UIContentBlockUIBlockFactory::MakeStandard('form_part_'.$sPartId)->AddCSSClass('form_part');
		$oForm->AddSubBlock($UIContentBlock);
		$UIContentBlock->AddSubBlock($oExport->GetFormPart($oP, $sPartId));
	}
	//end of form
	$oBlockExport = UIContentBlockUIBlockFactory::MakeStandard("export-feedback")->SetIsHidden(true);
	$oBlockExport->AddSubBlock(new Html('<p class="export-message" style="text-align:center;">'.Dict::S('ExcelExport:PreparingExport').'</p>'));
	$oBlockExport->AddSubBlock(new Html('<div class="export-progress-bar" style="max-width:30em; margin-left:auto;margin-right:auto;"><div class="export-progress-message" style="text-align:center;"></div></div>'));
	$oP->AddSubBlock($oBlockExport);
	if ($sFormat == null) {//if it's global export
		$oP->AddSubBlock(ButtonUIBlockFactory::MakeForPrimaryAction('export', Dict::S('UI:Button:Export'), 'export', false, 'export-btn'));
	}
	$oBlockResult = UIContentBlockUIBlockFactory::MakeStandard("export_text_result")->SetIsHidden(true);
	$oBlockResult->AddSubBlock(new Html(Dict::S('Core:BulkExport:ExportResult')));

	$oTextArea = new TextArea('export_content', '', 'export_content');
	$oTextArea->AddCSSClass('ibo-input-text--export');
	$oBlockResult->AddSubBlock($oTextArea);
	$oP->AddSubBlock($oBlockResult);

	$sJSParts = json_encode($aFormPartsByFormat);
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
	if ($sMode == 'dialog') {
		$sExportBtnLabel = json_encode(Dict::S('UI:Button:Export'));
		$sJSTitle = json_encode(utils::EscapeHtml(utils::ReadParam('dialog_title', '', false, 'raw_data')));
		$oP = new AjaxPage($sJSTitle);
		$oP->add('<div id="interactive_export_dlg">');
		$oP->add_ready_script(
			<<<EOF
		$('#interactive_export_dlg').dialog({
			autoOpen: true,
			modal: true,
			width: '80%',
			height: 'auto',
			maxHeight: $(window).height() - 50,
			title: $sJSTitle,
			close: function() { $('#export-form').attr('data-state', 'cancelled'); $(this).remove(); },
			buttons: [
				{text: $sExportBtnLabel, id: 'export-dlg-submit', click: function() {} }
			]
		});
			
		setTimeout(function() { $('#interactive_export_dlg').dialog('option', { position: { my: "center", at: "center", of: window }}); $('#export-btn').hide(); ExportInitButton('#export-dlg-submit'); }, 100);
EOF
		);
	} else {
		$oP = new iTopWebPage('iTop Export');
		$oP->SetBreadCrumbEntry('ui-tool-export', Dict::S('Menu:ExportMenu'), Dict::S('Menu:ExportMenu+'), '', 'fas fa-file-export', iTopWebPage::ENUM_BREADCRUMB_ENTRY_ICON_TYPE_CSS_CLASSES);
	}

	if ($sExpression === null) {
		// No expression supplied, let's check if phrasebook entry is given
		if ($sQueryId !== null) {
			$oSearch = DBObjectSearch::FromOQL('SELECT QueryOQL WHERE id = :query_id', array('query_id' => $sQueryId));
			$oSearch->UpdateContextFromUser();
			$oQueries = new DBObjectSet($oSearch);
			if ($oQueries->Count() > 0) {
				$oQuery = $oQueries->Fetch();
				$sExpression = $oQuery->Get('oql');
			} else {
				ReportErrorAndExit("Invalid query phrasebook identifier: '$sQueryId'");
			}
		} else {
			if (utils::IsModeCLI()) {
				Usage($oP);
				ReportErrorAndExit("No expression or query phrasebook identifier supplied.");
			} else {
				// form to enter an OQL query or pick a query phrasebook identifier
				DisplayForm($oP, utils::GetAbsoluteUrlAppRoot().'webservices/export-v2.php', $sExpression, $sQueryId, $sFormat);
				$oP->output();
				exit;
			}
		}
	}


	if ($sFormat !== null) {
		$oExporter = BulkExport::FindExporter($sFormat);
		if ($oExporter === null) {
			$aSupportedFormats = BulkExport::FindSupportedFormats();
			ReportErrorAndExit("Invalid output format: '$sFormat'. The supported formats are: ".implode(', ', array_keys($aSupportedFormats)));
		} else {
			DisplayForm($oP, utils::GetAbsoluteUrlAppRoot().'webservices/export-v2.php', $sExpression, $sQueryId, $sFormat);
		}
	} else {
		DisplayForm($oP, utils::GetAbsoluteUrlAppRoot().'webservices/export-v2.php', $sExpression, $sQueryId, $sFormat);
	}
	if ($sMode == 'dialog') {
		$oP->add('</div>');
	}
	$oP->output();
}

/**
 * Checks the parameters and returns the appropriate exporter (if any)
 * @param string $sExpression The OQL query to export or null
 * @param string $sQueryId The entry in the query phrasebook if $sExpression is null
 * @param string $sFormat The code of export format: csv, pdf, html, xlsx
 * @return BulkExport
 */
function CheckParameters($sExpression, $sQueryId, $sFormat)
{
	$oExporter = null;
	$oQuery = null;

	if (utils::IsArchiveMode() && !UserRights::CanBrowseArchive()) {
		ReportErrorAndExit("The user account is not authorized to access the archives");
	}

	if (($sExpression === null) && ($sQueryId === null)) {
		ReportErrorAndUsage("Missing parameter. The parameter 'expression' or 'query' must be specified.");
	}

	// Either $sExpression or $sQueryId must be specified
	if ($sExpression === null) {
		$oSearch = DBObjectSearch::FromOQL('SELECT QueryOQL WHERE id = :query_id', array('query_id' => $sQueryId));
		$oSearch->UpdateContextFromUser();
		$oQueries = new DBObjectSet($oSearch);
		if ($oQueries->Count() > 0) {
			$oQuery = $oQueries->Fetch();
			$sExpression = $oQuery->Get('oql');
		} else {
			ReportErrorAndExit("Invalid query phrasebook identifier: '$sQueryId'");
		}
	}
	if ($sFormat === null) {
		ReportErrorAndUsage("Missing parameter 'format'.");
	}

	// Check if the supplied query is valid (and all the parameters are supplied
	try {
		$oSearch = DBObjectSearch::FromOQL($sExpression);
		$oSearch->UpdateContextFromUser();
		$aArgs = array();
		foreach ($oSearch->GetQueryParams() as $sParam => $foo) {
			$value = utils::ReadParam('arg_'.$sParam, null, true, 'raw_data');
			if (!is_null($value)) {
				$aArgs[$sParam] = $value;
			} else {
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
		$oSearch = null;
		ReportErrorAndUsage("Invalid OQL query: '".utils::HtmlEntities($sExpression)."'.\n".utils::HtmlEntities($e->getMessage()));
	}
	catch(OQLException $e)
	{
		$oSearch = null;
		ReportErrorAndExit("Invalid OQL query: '".utils::HtmlEntities($sExpression)."'.\n".utils::HtmlEntities($e->getMessage()));
	}
	catch(Exception $e)
	{
		$oSearch = null;
		ReportErrorAndExit(utils::HtmlEntities($e->getMessage()));
	}

	// update last export information if check parameters ok
	if($oQuery != null){
		$oQuery->UpdateLastExportInformation();
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
///
/**
 * @since 3.1.0 N°6047
 */
$oCtx = new ContextTag(ContextTag::TAG_EXPORT);

if (utils::IsModeCLI()) {
	SetupUtils::CheckPhpAndExtensionsForCli(new CLIPage('iTop - Export'));

	try {
		// Do this before loging, in order to allow setting user credentials from within the file
		utils::UseParamFile();
	}
	catch (Exception $e) {
		echo "Error: ".utils::HtmlEntities($e->getMessage())."<br/>\n";
		exit(EXIT_CODE_FATAL);
	}

	$sAuthUser = utils::ReadParam('auth_user', null, true /* Allow CLI */, 'raw_data');
	$sAuthPwd = utils::ReadParam('auth_pwd', null, true /* Allow CLI */, 'raw_data');
	if ($sAuthUser == null) {
		ReportErrorAndUsage("Missing parameter '--auth_user'");
	}
	if ($sAuthPwd == null) {
		ReportErrorAndUsage("Missing parameter '--auth_pwd'");
	}

	if (UserRights::CheckCredentials($sAuthUser, $sAuthPwd)) {
		UserRights::Login($sAuthUser); // Login & set the user's language
	} else {
		ReportErrorAndExit("Access restricted or wrong credentials for user '$sAuthUser'");
	}

	$sExpression = utils::ReadParam('expression', null, true /* Allow CLI */, 'raw_data');
	$sQueryId = utils::ReadParam('query', null, true /* Allow CLI */, 'raw_data');
	$bLocalize = (utils::ReadParam('no_localize', 0) != 1);
	if (utils::IsArchiveMode() && !UserRights::CanBrowseArchive()) {
		ReportErrorAndExit("The user account is not authorized to access the archives");
	}

	if (($sExpression == null) && ($sQueryId == null)) {
		ReportErrorAndUsage("Missing parameter. At least one of '--expression' or '--query' must be specified.");
	}

	if ($sExpression === null) {
		$oSearch = DBObjectSearch::FromOQL('SELECT QueryOQL WHERE id = :query_id', array('query_id' => $sQueryId));
		$oSearch->UpdateContextFromUser();
		$oQueries = new DBObjectSet($oSearch);
		if ($oQueries->Count() > 0) {
			$oQuery = $oQueries->Fetch();
			$sExpression = $oQuery->Get('oql');
		} else {
			ReportErrorAndExit("Invalid query phrasebook identifier: '$sQueryId'");
		}
	}
	try {
		$oSearch = DBObjectSearch::FromOQL($sExpression);
		$oSearch->UpdateContextFromUser();
		$aArgs = array();
		foreach ($oSearch->GetQueryParams() as $sParam => $foo) {
			$value = utils::ReadParam('arg_'.$sParam, null, true, 'raw_data');
			if (!is_null($value)) {
				$aArgs[$sParam] = $value;
			} else {
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
		ReportErrorAndUsage("Invalid OQL query: '$sExpression'.\n".utils::HtmlEntities($e->getMessage()));
	}
	catch(OQLException $e)
	{
		ReportErrorAndExit("Invalid OQL query: '$sExpression'.\n".utils::HtmlEntities($e->getMessage()));
	}
	catch(Exception $e)
	{
		ReportErrorAndExit(utils::HtmlEntities($e->getMessage()));
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

	if ($bInteractive) {
		InteractiveShell($sExpression, $sQueryId, $sFormat, $sFileName, $sMode);
	} else {
		$oExporter = CheckParameters($sExpression, $sQueryId, $sFormat);
		$sMimeType = $oExporter->GetMimeType();
		if ($sMimeType == 'text/html') {
			// Note: Using NiceWebPage only for HTML export as it includes JS scripts & files, which makes no sense in other export formats. More over, it breaks Excel spreadsheet import.
			if ($oExporter instanceof HTMLBulkExport) {
				$oP = new NiceWebPage('iTop export');
				$oP->add_http_headers();
				$oP->add_ready_script("$('table.listResults').tablesorter({widgets: ['MyZebra']});");
				$oP->LinkStylesheetFromAppRoot('css/font-awesome/css/all.min.css');
				$oP->LinkStylesheetFromAppRoot('css/font-awesome/css/v4-shims.min.css');
			} else {
				$oP = new WebPage('iTop export');
				$oP->add_http_headers();
				$oP->add_style("table br { mso-data-placement:same-cell; }"); // Trick for Excel: keep line breaks inside the same cell !
			}
			$oP->add_style("body { overflow: auto; }");
		} else {
			$oP = new DownloadPage('iTop export');
			$oP->SetContentType($oExporter->GetMimeType());
		}
		DoExport($oP, $oExporter, false);
		$oP->output();
	}
}
catch (BulkExportMissingParameterException $e) {
	$oP = new AjaxPage('iTop Export');
	$oP->add(utils::HtmlEntities($e->getMessage()));
	Usage($oP);
	$oP->output();
}
catch (Exception $e) {
	$oP = new WebPage('iTop Export');
	$oP->add_http_headers();
	$oP->add('Error: '.utils::HtmlEntities($e->getMessage()));
	IssueLog::Error(utils::HtmlEntities($e->getMessage())."\n".$e->getTraceAsString());
	$oP->output();
}
