<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectOptionUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\TextArea;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\WebPage\AjaxPage;
use Combodo\iTop\Application\WebPage\CSVPage;
use Combodo\iTop\Application\WebPage\DownloadPage;
use Combodo\iTop\Renderer\BlockRenderer;

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/wizardhelper.class.inc.php');
require_once(APPROOT.'/application/ui.linkswidget.class.inc.php');

/**
 * Determines if the name of the field to be mapped correspond
 * to the name of an external key or an Id of the given class
 *
 * @param string $sClassName The name of the class
 * @param string $sFieldCode The attribute code of the field , or empty if no match
 *
 * @return bool true if the field corresponds to an id/External key, false otherwise
 * @throws \Exception
 */
function IsIdField($sClassName, $sFieldCode)
{
	$bResult = false;
	if (!empty($sFieldCode))
	{
		if ($sFieldCode == 'id')
		{
			$bResult = true;
		}
		else if (strpos($sFieldCode, '->') === false)
		{
			$oAttDef = MetaModel::GetAttributeDef($sClassName, $sFieldCode);
			$bResult = $oAttDef->IsExternalKey();
		}
	}
	return $bResult;
}

/**
 * Get all the fields xxx->yyy based on the field xxx which is an external key
 *
 * @param $sAttCode
 * @param AttributeDefinition $oExtKeyAttDef Attribute definition of the external key
 * @param bool $bAdvanced True if advanced mode
 *
 * @return array List of codes=>display name: xxx->yyy where yyy are the reconciliation keys for the object xxx
 * @throws \CoreException
 */
function GetMappingsForExtKey($sAttCode, AttributeDefinition $oExtKeyAttDef, $bAdvanced)
{
	$aResult = array();
	$sTargetClass = $oExtKeyAttDef->GetTargetClass();
	foreach(MetaModel::ListAttributeDefs($sTargetClass) as $sTargetAttCode => $oTargetAttDef)
	{
		if (MetaModel::IsReconcKey($sTargetClass, $sTargetAttCode))
		{
			$bExtKey = $oTargetAttDef->IsExternalKey();
			$sSuffix = '';
			if ($bExtKey)
			{
				$sSuffix = '->id';
			}
			if ($bAdvanced || !$bExtKey)
			{
				// When not in advanced mode do not allow to use reconciliation keys (on external keys) if they are themselves external keys !
				$aResult[$sAttCode.'->'.$sTargetAttCode] = $oExtKeyAttDef->GetLabel().'->'.$oTargetAttDef->GetLabel().$sSuffix;
			}
		}
	}
	return $aResult;	
}

/**
 * Helper function to build the mapping drop-down list for a field
 * Spec: Possible choices are "writable" fields in this class plus external fields that are listed as reconciliation keys
 *       for any class pointed to by an external key in the current class.
 *       If not in advanced mode, all "id" fields (id and external keys) must be mapped to ":none:" (i.e -- ignore this field --)
 *       External fields that do not correspond to a reconciliation key must be mapped to ":none:"
 *       Otherwise, if a field equals either the 'code' or the 'label' (translated) of a field, then it's mapped automatically
 *
 * @param string $sClassName Name of the class used for the mapping
 * @param string $sFieldName Name of the field, as it comes from the data file (header line)
 * @param integer $iFieldIndex Number of the field in the sequence
 * @param bool $bAdvancedMode Whether or not advanced mode was chosen
 * @param string $sDefaultChoice If set, this will be the item selected by default
 *
 * @return Select The block corresponding to the drop-down list for this field
 * @throws \CoreException
 */
function GetMappingForField($sClassName, $sFieldName, $iFieldIndex, $bAdvancedMode, $sDefaultChoice)
{
	$aChoices = array('' => Dict::S('UI:CSVImport:MappingSelectOne'));
	$aChoices[':none:'] = Dict::S('UI:CSVImport:MappingNotApplicable');
	$sFieldCode = ''; // Code of the attribute, if there is a match
	$aMatches = array();
	if (preg_match('/^(.+)\*$/', $sFieldName, $aMatches)) {
		// Remove any trailing "star" character.
		// A star character at the end can be used to indicate a mandatory field
		$sFieldName = $aMatches[1];
	} else if (preg_match('/^(.+)\*->(.+)$/', $sFieldName, $aMatches)) {
		// Remove any trailing "star" character before the arrow (->)
		// A star character at the end can be used to indicate a mandatory field
		$sFieldName = $aMatches[1].'->'.$aMatches[2];
	}
	if (($sFieldName == 'id') || ($sFieldName == Dict::S('UI:CSVImport:idField'))) {
		$sFieldCode = 'id';
	}
	if ($bAdvancedMode) {
		$aChoices['id'] = Dict::S('UI:CSVImport:idField');
	}
	foreach (MetaModel::ListAttributeDefs($sClassName) as $sAttCode => $oAttDef) {
		if ($oAttDef->IsExternalKey()) {
			if (($sFieldName == $oAttDef->GetLabel()) || ($sFieldName == $sAttCode)) {
				$sFieldCode = $sAttCode;
			}
			if ($bAdvancedMode) {
				$aChoices[$sAttCode] = $oAttDef->GetLabel();
			}
			// Get fields of the external class that are considered as reconciliation keys
			$sTargetClass = $oAttDef->GetTargetClass();
			foreach (MetaModel::ListAttributeDefs($sTargetClass) as $sTargetAttCode => $oTargetAttDef) {
				// Note: Could not use "MetaModel::GetFriendlyNameAttributeCode($sTargetClass) === $sTargetAttCode" as it would return empty because the friendlyname is composite.
				if (MetaModel::IsReconcKey($sTargetClass, $sTargetAttCode) || ($oTargetAttDef instanceof AttributeFriendlyName)) {
					$bExtKey = $oTargetAttDef->IsExternalKey();
					$aSignatures = array();
					$aSignatures[] = $oAttDef->GetLabel().'->'.$oTargetAttDef->GetLabel();
					$aSignatures[] = $sAttCode.'->'.$sTargetAttCode;
					if ($bExtKey) {
						$aSignatures[] = $oAttDef->GetLabel().'->'.$oTargetAttDef->GetLabel().'->id';
						$aSignatures[] = $sAttCode.'->'.$sTargetAttCode.'->id';
					}
					if ($bAdvancedMode || !$bExtKey) {

						// When not in advanced mode do not allow to use reconciliation keys (on external keys) if they are themselves external keys !
						$aChoices[$sAttCode.'->'.$sTargetAttCode] = MetaModel::GetLabel($sClassName, $sAttCode.'->'.$sTargetAttCode, true);
						foreach ($aSignatures as $sSignature) {
							if (strcasecmp($sFieldName, $sSignature) == 0) {
								$sFieldCode = $sAttCode.'->'.$sTargetAttCode;
							}
						}
					}
				}
			}
		}
		else if (
			($oAttDef->IsWritable() && (!$oAttDef->IsLinkset() || ($bAdvancedMode && $oAttDef->IsIndirect())))
			|| ($oAttDef instanceof AttributeFriendlyName)
		) {
			$aChoices[$sAttCode] = MetaModel::GetLabel($sClassName, $sAttCode, true);
			if (($sFieldName == $oAttDef->GetLabel()) || ($sFieldName == $sAttCode)) {
				$sFieldCode = $sAttCode;
			}
		}
	}
	asort($aChoices);

	$oSelect = SelectUIBlockFactory::MakeForSelect("field[$iFieldIndex]", "mapping_{$iFieldIndex}");
	$bIsIdField = IsIdField($sClassName, $sFieldCode);
	foreach ($aChoices as $sAttCode => $sLabel) {
		$bSelected = false;
		if ($bIsIdField && (!$bAdvancedMode)) // When not in advanced mode, ID are mapped to n/a
		{
			if ($sAttCode == ':none:') {
				$bSelected = true;
			}
		} else if (empty($sFieldCode) && (strpos($sFieldName, '->') !== false)) {
			if ($sAttCode == ':none:') {
				$bSelected = true;
			}
		} else if (is_null($sDefaultChoice) && ($sFieldCode == $sAttCode)) {
			$bSelected = true;
		} else if (!is_null($sDefaultChoice) && ($sDefaultChoice == $sAttCode)) {
			$bSelected = true;
		}
		$oOption = SelectOptionUIBlockFactory::MakeForSelectOption($sAttCode, $sLabel, $bSelected);
		$oSelect->AddOption($oOption);
	}

	return $oSelect;
}

try
{
	require_once(APPROOT.'/application/startup.inc.php');

	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	IssueLog::Trace('----- Request: '.utils::GetRequestUri(), LogChannels::WEB_REQUEST);
	LoginWebPage::DoLogin(); // Check user rights and prompt if needed


	$sOperation = utils::ReadParam('operation', '');

	switch($sOperation)
	{
		case 'parser_preview':
			$oPage = new AjaxPage("");
			$oPage->SetContentType('text/html');
			$sSeparator = utils::ReadParam('separator', ',', false, 'raw_data');
			if ($sSeparator == 'tab') {
				$sSeparator = "\t";
			}
			$sTextQualifier = utils::ReadParam('qualifier', '"', false, 'raw_data');
			$iLinesToSkip = utils::ReadParam('do_skip_lines', 0);
			$bFirstLineAsHeader = utils::ReadParam('header_line', true);
			$sEncoding = utils::ReadParam('encoding', 'UTF-8');
			$sData = stripslashes(utils::ReadParam('csvdata', true, false, 'raw_data'));
			$oCSVParser = new CSVParser($sData, $sSeparator, $sTextQualifier, MetaModel::GetConfig()->Get('max_execution_time_per_loop'));
			$iMaxIndex = 10; // Display maximum 10 lines for the preview
			$aData = $oCSVParser->ToArray($iLinesToSkip, null, $bFirstLineAsHeader ? $iMaxIndex + 1 : $iMaxIndex);
			$iTarget = count($aData);
			if ($iTarget == 0) {
				$oPage->p(Dict::S('UI:CSVImport:NoData'));
			} else {
				$sMaxLen = (strlen(''.$iTarget) < 3) ? 3 : strlen(''.$iTarget); // Pad line numbers to the appropriate number of chars, but at least 3
				$sFormat = '%0'.$sMaxLen.'d';

				$aColumns = [];
				$aTableData = [];
				$iNbCols = 0;

				// iterate throw data elements...
				for ($iDataLineNumber = 0 ; $iDataLineNumber < count($aData) && count($aTableData) <= $iMaxIndex ; $iDataLineNumber++) {

					// get data element
					$aRow = $aData[$iDataLineNumber];

					// when first line
					if ($iDataLineNumber === 0) {

						// columns
						$iNbCols = count($aRow);
						$aColumns[] = '';

						// first line as header
						if($bFirstLineAsHeader){
							foreach ($aRow as $sCell) {
								$aColumns[] = ["label" => utils::EscapeHtml($sCell)];
							}
							continue;
						}

						// default headers
						for ($iDataColumnNumber = 0 ; $iDataColumnNumber < count($aRow) ; $iDataColumnNumber++) {
							$aColumns[] = ["label" => Dict::Format('UI:CSVImport:Column', $iDataColumnNumber+1)];
						}

					}

					// create table row
					$aTableRow = [];
					$aTableRow[] = sprintf($sFormat, count($aTableData) + 1);
					foreach ($aRow as $sCell) {
						$aTableRow[] = utils::EscapeHtml($sCell);
					}
					$aTableData[] = $aTableRow;
				}
				$oTable = DataTableUIBlockFactory::MakeForForm("parser_preview", $aColumns, $aTableData);
				$oPage->AddSubBlock($oTable);
				if ($iNbCols == 1) {
					$oAlertMessage = AlertUIBlockFactory::MakeForFailure(Dict::S('UI:CSVImport:ErrorOnlyOneColumn'));
					$oPage->AddSubBlock($oAlertMessage);
				}
			}
			break;

		case 'display_mapping_form':
			$oPage = new AjaxPage("");
			$sSeparator = utils::ReadParam('separator', ',', false, 'raw_data');
			$sTextQualifier = utils::ReadParam('qualifier', '"', false, 'raw_data');
			$iLinesToSkip = utils::ReadParam('do_skip_lines', 0);
			$bFirstLineAsHeader = utils::ReadParam('header_line', false);
			$sData = stripslashes(utils::ReadParam('csvdata', '', false, 'raw_data'));
			$sClassName = utils::ReadParam('class_name', '');
			$bAdvanced = utils::ReadParam('advanced', false);
			$sEncoding = utils::ReadParam('encoding', 'UTF-8');

			$sInitFieldMapping = utils::ReadParam('init_field_mapping', '', false, 'raw_data');
			$sInitSearchField = utils::ReadParam('init_search_field', '', false, 'raw_data');
			$aInitFieldMapping = empty($sInitFieldMapping) ? array() : json_decode($sInitFieldMapping, true);
			$aInitSearchField = empty($sInitSearchField) ? array() : json_decode($sInitSearchField, true);

			$oCSVParser = new CSVParser($sData, $sSeparator, $sTextQualifier, MetaModel::GetConfig()->Get('max_execution_time_per_loop'));
			$aData = $oCSVParser->ToArray($iLinesToSkip, null, 3 /* Max: 1 header line + 2 lines of sample data */);
			$iTarget = count($aData);
			if ($iTarget == 0) {
				$oPage->p(Dict::S('UI:CSVImport:NoData'));
			} else {
				$aFirstLine = $aData[0]; // Use the first row to determine the number of columns
				$iStartLine = 0;
				$iNbColumns = count($aFirstLine);
				if ($bFirstLineAsHeader) {
					$iStartLine = 1;
					foreach ($aFirstLine as $sField) {
						$aHeader[] = $sField;
					}
				} else {
					// Build some conventional name for the fields: field1...fieldn
					$index = 1;
					foreach ($aFirstLine as $sField) {
						$aHeader[] = Dict::Format('UI:CSVImport:FieldName', $index);
						$index++;
					}
				}
				$aColumns = [];
				$aColumns ["HeaderFields"] = ["label" => Dict::S('UI:CSVImport:HeaderFields')];
				$aColumns ["HeaderMapipngs"] = ["label" => Dict::S('UI:CSVImport:HeaderMappings')];
				$aColumns ["HeaderSearch"] = ["label" => Dict::S('UI:CSVImport:HeaderSearch')];
				$aColumns ["DataLine1"] = ["label" => Dict::S('UI:CSVImport:DataLine1')];
				$aColumns ["DataLine2"] = ["label" => Dict::S('UI:CSVImport:DataLine2')];

				$aTableData = [];
				$index = 1;
				foreach ($aHeader as $sField) {
					$aTableRow = [];
					$sDefaultChoice = null;
					if (isset($aInitFieldMapping[$index])) {
						$sDefaultChoice = $aInitFieldMapping[$index];
					}
					$aTableRow['HeaderFields'] = utils::HtmlEntities($sField);
					$aTableRow['HeaderMapipngs'] = BlockRenderer::RenderBlockTemplates(GetMappingForField($sClassName, $sField, $index, $bAdvanced, $sDefaultChoice));
					$aTableRow['HeaderSearch'] = '<input id="search_'.$index.'" type="checkbox" name="search_field['.$index.']" value="1" />';
					$aTableRow['DataLine1'] = (isset($aData[$iStartLine][$index - 1]) ? utils::EscapeHtml($aData[$iStartLine][$index - 1]) : '&nbsp;');
					$aTableRow['DataLine2'] = (isset($aData[$iStartLine + 1][$index - 1]) ? utils::EscapeHtml($aData[$iStartLine + 1][$index - 1]) : '&nbsp;');
					$aTableData[$index] = $aTableRow;
					$index++;
				}
				$oTable = DataTableUIBlockFactory::MakeForForm("mapping", $aColumns, $aTableData);

				$oPanel = PanelUIBlockFactory::MakeNeutral('');
				$oPanel->AddCSSClass('ibo-datatable-panel');
				$oPanel->AddCSSClass('mt-5');
				$oPanel->AddSubBlock($oTable);

				$oPage->AddSubBlock($oPanel);
				if (empty($sInitSearchField)) {
					// Propose a reconciliation scheme
					//
					$aReconciliationKeys = MetaModel::GetReconcKeys($sClassName);
					$aMoreReconciliationKeys = array(); // Store: key => void to automatically remove duplicates
					foreach ($aReconciliationKeys as $sAttCode) {
						if (!MetaModel::IsValidAttCode($sClassName, $sAttCode)) {
							continue;
						}
						$oAttDef = MetaModel::GetAttributeDef($sClassName, $sAttCode);
						if ($oAttDef->IsExternalKey()) {
							// An external key is specified as a reconciliation key: this means that all the reconciliation
							// keys of this class are proposed to identify the target object
							$aMoreReconciliationKeys = array_merge($aMoreReconciliationKeys, GetMappingsForExtKey($sAttCode, $oAttDef, $bAdvanced));
						} elseif ($oAttDef->IsExternalField()) {
							// An external field is specified as a reconciliation key, translate the field into a field on the target class
							// since external fields are not writable, and thus never appears in the mapping form
							$sKeyAttCode = $oAttDef->GetKeyAttCode();
							$sTargetAttCode = $oAttDef->GetExtAttCode();
							$aMoreReconciliationKeys[$sKeyAttCode.'->'.$sTargetAttCode] = '';
						}
					}
					$sDefaultKeys = '"'.implode('", "', array_merge($aReconciliationKeys, array_keys($aMoreReconciliationKeys))).'"';
				} else {
					// The reconciliation scheme is given (navigating back in the wizard)
					//
					$aDefaultKeys = array();
					foreach ($aInitSearchField as $iSearchField => $void) {
						$sAttCodeEx = $aInitFieldMapping[$iSearchField];
						$aDefaultKeys[] = $sAttCodeEx;
					}
					$sDefaultKeys = '"'.implode('", "', $aDefaultKeys).'"';
				}

				// Read only attributes (will be forced to "search")
				$aReadOnlyKeys = array();
				foreach (MetaModel::ListAttributeDefs($sClassName) as $sAttCode => $oAttDef) {
					if (!$oAttDef->IsWritable()) {
						$aReadOnlyKeys[] = $sAttCode;
					}
				}
				$sReadOnlyKeys = '"'.implode('", "', $aReadOnlyKeys).'"';

				$oPage->add_ready_script(
					<<<EOF
		$('select[name^=field]').change( DoCheckMapping );
		aDefaultKeys = new Array($sDefaultKeys);
		aReadOnlyKeys = new Array($sReadOnlyKeys);
		DoCheckMapping();
EOF
				);
			}
		break;

		case 'get_csv_template':
			$sClassName = utils::ReadParam('class_name');
			$sFormat = utils::ReadParam('format', 'csv');
			if (MetaModel::IsValidClass($sClassName)) {
				$oSearch = new DBObjectSearch($sClassName);
				$oSearch->AddCondition('id', 0, '='); // Make sure we create an empty set
				$oSet = new CMDBObjectSet($oSearch);
				$sResult = cmdbAbstractObject::GetSetAsCSV($oSet, array('showMandatoryFields' => true));

				$sClassDisplayName = MetaModel::GetName($sClassName);
				$sDisposition = utils::ReadParam('disposition', 'inline');
				if ($sDisposition == 'attachment') {
					switch ($sFormat) {
						case 'xlsx':
							$oPage = new DownloadPage("");
							$oPage->SetContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
							$oPage->SetContentDisposition('attachment', $sClassDisplayName.'.xlsx');
							require_once(APPROOT.'/application/excelexporter.class.inc.php');
							$writer = new XLSXWriter();
							$writer->setAuthor(UserRights::GetUserFriendlyName());
							$aHeaders = array(0 => explode(',', $sResult)); // comma is the default separator
							$writer->writeSheet($aHeaders, $sClassDisplayName, array());
							$oPage->add($writer->writeToString());
							break;

						case 'csv':
						default:
							$oPage = new CSVPage("");
							$oPage->add_header("Content-type: text/csv; charset=utf-8");
							$oPage->add_header("Content-disposition: attachment; filename=\"{$sClassDisplayName}.csv\"");
							$oPage->add($sResult);
					}
				} else {
					$oPage = new AjaxPage("");
					$oButtonXls = ButtonUIBlockFactory::MakeIconLink('ibo-csv-import--download-file fas fa-file-csv', $sClassDisplayName.'.csv', utils::GetAbsoluteUrlAppRoot().'pages/ajax.csvimport.php?operation=get_csv_template&disposition=attachment&class_name='.$sClassName);
					$oPage->AddSubBlock($oButtonXls);
					$oButtonCsv = ButtonUIBlockFactory::MakeIconLink('ibo-csv-import--download-file fas fa-file-excel', $sClassDisplayName.'.xlsx', utils::GetAbsoluteUrlAppRoot().'pages/ajax.csvimport.php?operation=get_csv_template&disposition=attachment&format=xlsx&class_name='.$sClassName);
					$oPage->AddSubBlock($oButtonCsv);
					$oTextArea = new TextArea("", $sResult, "", 100, 5);
					$oPage->AddSubBlock($oTextArea);
				}
			} else {
				$oPage = new AjaxPage("Class $sClassName is not a valid class !");
			}
			break;
	}
	$oPage->output();
}
catch (Exception $e)
{
	IssueLog::Error($e->getMessage());
}

