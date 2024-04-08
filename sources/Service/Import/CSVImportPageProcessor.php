<?php
declare(strict_types=1);

namespace Combodo\iTop\Service\Import;

use ArchivedObjectException;
use AttributeDateTime;
use BulkChange;
use CellChangeSpec;
use CMDBChange;
use CMDBObject;
use Combodo\iTop\Application\Helper\WebResourcesHelper;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Form\Form;
use Combodo\iTop\Application\UI\Base\Component\Form\FormUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\MultiColumn\Column\ColumnUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\MultiColumn\MultiColumnUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlockUIBlockFactory;
use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\Core\CMDBChange\CMDBChangeOrigin;
use CoreException;
use CSVParser;
use CSVParserException;
use DBObjectSearch;
use DBObjectSet;
use Dict;
use DictExceptionMissingString;
use Exception;
use MetaModel;
use MissingQueryArgument;
use MySQLException;
use MySQLHasGoneAwayException;
use OQLException;
use ReflectionException;
use utils;

/**
 *
 */
class CSVImportPageProcessor
{

	/**
	 * @param mixed $iBoxSkipLines
	 * @param mixed $iNbSkippedLines
	 * @param mixed $sDateTimeFormat
	 * @param mixed $sCustomDateTimeFormat
	 * @param mixed $sClassName
	 * @param WebPage $oPage
	 * @param $aSynchroUpdate
	 * @param mixed $sCSVData
	 * @param mixed $sSeparator
	 * @param mixed $sTextQualifier
	 * @param bool $bHeaderLine
	 * @param array $aResult
	 * @param mixed $aSearchFields
	 * @param mixed $aFieldsMapping
	 * @param bool $bSimulate
	 * @param mixed $sCSVDataTruncated
	 * @param int $iCurrentStep
	 * @param mixed $sEncoding
	 * @param mixed $bAdvanced
	 * @param mixed $sSynchroScope
	 *
	 * @return array|null
	 * @throws ArchivedObjectException
	 * @throws CSVParserException
	 * @throws CoreException
	 * @throws DictExceptionMissingString
	 * @throws MissingQueryArgument
	 * @throws MySQLException
	 * @throws MySQLHasGoneAwayException
	 * @throws OQLException
	 * @throws ReflectionException
	 * @throws Exception
	 */
	public static function ProcessData(
		mixed $iBoxSkipLines, mixed $iNbSkippedLines, mixed $sDateTimeFormat, mixed $sCustomDateTimeFormat, mixed $sClassName, WebPage $oPage, $aSynchroUpdate, mixed $sCSVData, mixed $sSeparator,
		mixed $sTextQualifier, bool $bHeaderLine, array $aResult, mixed $aSearchFields, mixed $aFieldsMapping,
		bool $bSimulate, mixed $sCSVDataTruncated, int $iCurrentStep, mixed $sEncoding, mixed $bAdvanced, mixed $sSynchroScope
	): ?array
	{
		$iSkippedLines = 0;
		if ($iBoxSkipLines == 1) {
			$iSkippedLines = $iNbSkippedLines;
		}
		$sChosenDateFormat = ($sDateTimeFormat == 'default') ? (string)AttributeDateTime::GetFormat() : $sCustomDateTimeFormat;

		if (!empty($sSynchroScope)) {
			$oSearch = DBObjectSearch::FromOQL($sSynchroScope);
			$sClassName = $oSearch->GetClass(); // If a synchronization scope is set, then the class is fixed !
			$oSet = new DBObjectSet($oSearch);
			$iCount = $oSet->Count();
			DisplaySynchroBanner($oPage, $sClassName, $iCount);
		} else {
			$sSynchroScope = '';
			$aSynchroUpdate = null;
		}

		// Parse the data set
		$oCSVParser = new CSVParser($sCSVData, $sSeparator, $sTextQualifier, MetaModel::GetConfig()->Get('max_execution_time_per_loop'));
		$aData = $oCSVParser->ToArray($iSkippedLines);
		$iRealSkippedLines = $iSkippedLines;
		if ($bHeaderLine) {
			$aResult[] = $sTextQualifier . implode($sTextQualifier . $sSeparator . $sTextQualifier, array_shift($aData)) . $sTextQualifier; // Remove the first line and store it in case of error
			$iRealSkippedLines++;
		}

		// Format for the line numbers
		$sMaxLen = (strlen('' . count($aData)) < 3) ? 3 : strlen('' . count($aData)); // Pad line numbers to the appropriate number of chars, but at least 3

		// Compute the list of search/reconciliation criteria
		$aSearchKeys = [];
		foreach ($aSearchFields as $index => $sDummy) {
			$sSearchField = $aFieldsMapping[$index];
			$aMatches = [];
			if (preg_match('/(.+)->(.+)/', $sSearchField, $aMatches) > 0) {
				$sSearchField = $aMatches[1];
				$aSearchKeys[$aMatches[1]] = '';
			} else {
				$aSearchKeys[$sSearchField] = '';
			}
			if (!MetaModel::IsValidFilterCode($sClassName, $sSearchField)) {
				// Remove invalid or unmapped search fields
				$aSearchFields[$index] = null;
				unset($aSearchKeys[$sSearchField]);
			}
		}

		// Compute the list of fields and external keys to process
		$aExtKeys = [];
		$aAttributes = [];
		$aExternalKeysByColumn = [];
		foreach ($aFieldsMapping as $iNumber => $sAttCode) {
			$iIndex = $iNumber - 1;
			if (!empty($sAttCode) && ($sAttCode != ':none:') && ($sAttCode != 'finalclass')) {
				if (preg_match('/(.+)->(.+)/', $sAttCode, $aMatches) > 0) {
					$sAttribute = $aMatches[1];
					$sField = $aMatches[2];
					$aExtKeys[$sAttribute][$sField] = $iIndex;
					$aExternalKeysByColumn[$iIndex] = $sAttribute;
				} else {
					if ($sAttCode == 'id') {
						$aAttributes['id'] = $iIndex;
					} else {
						$oAttDef = MetaModel::GetAttributeDef($sClassName, $sAttCode);
						if ($oAttDef->IsExternalKey()) {
							$aExtKeys[$sAttCode]['id'] = $iIndex;
							$aExternalKeysByColumn[$iIndex] = $sAttCode;
						} else {
							$aAttributes[$sAttCode] = $iIndex;
						}
					}
				}
			}
		}

		$oMyChange = null;
		if (!$bSimulate) {
			// We're doing it for real, let's create a change
			$sUserString = CMDBChange::GetCurrentUserName() . ' (CSV)';
			CMDBObject::SetCurrentChangeFromParams($sUserString, CMDBChangeOrigin::CSV_INTERACTIVE);
			$oMyChange = CMDBObject::GetCurrentChange();
		}

		$oBulk = new BulkChange(
			$sClassName,
			$aData,
			$aAttributes,
			$aExtKeys,
			array_keys($aSearchKeys),
			empty($sSynchroScope) ? null : $sSynchroScope,
			$aSynchroUpdate,
			$sChosenDateFormat, // date format
			true // localize
		);

		$oPage->AddSubBlock(InputUIBlockFactory::MakeForHidden("csvdata_truncated", $sCSVDataTruncated, "csvdata_truncated"));
		$aRes = $oBulk->Process($oMyChange);

		$aColumns = [];
		$aColumns ["line"] = ["label" => "Line"];
		$aColumns ["status"] = ["label" => "Status"];
		$aColumns ["object"] = ["label" => "Object"];
		foreach ($aFieldsMapping as $sAttCode) {
			if (!empty($sAttCode) && ($sAttCode != ':none:') && ($sAttCode != 'finalclass')) {
				$aColumns[$sClassName . '/' . $sAttCode] = ["label" => MetaModel::GetLabel($sClassName, $sAttCode)];
			}
		}
		$aColumns["message"] = ["label" => "Message"];

		$iErrors = 0;
		$iCreated = 0;
		$iModified = 0;

		$aTableData = [];
		$sAppRootUrl = utils::GetAbsoluteUrlAppRoot();

		foreach ($aRes as $iLine => $aResRow) {
			/** @var string[]|CellChangeSpec[] $aResRow */
			$aTableRow = [];
			$oStatus = $aResRow['__STATUS__'];
			$sUrl = '';
			$sMessage = '';
			$sCSSRowClass = '';
			$sCSSMessageClass = 'cell_ok';
			switch (get_class($oStatus)) {
				case 'RowStatus_NoChange':
					$sFinalClass = $aResRow['finalclass'];
					$oObj = MetaModel::GetObject($sFinalClass, $aResRow['id']->GetPureValue());
					$sUrl = $oObj->GetHyperlink();
					$sStatus = '<img src="' . $sAppRootUrl . 'images/unchanged.png" title="' . Dict::S('UI:CSVReport-Icon-Unchanged') . '">';
					$sCSSRowClass = 'ibo-csv-import--row-unchanged';
					break;

				case 'RowStatus_Modify':
					$iModified++;
					$sFinalClass = $aResRow['finalclass'];
					$oObj = MetaModel::GetObject($sFinalClass, $aResRow['id']->GetPureValue());
					$sUrl = $oObj->GetHyperlink();
					$sStatus = '<img src="' . $sAppRootUrl . 'images/modified.png" title="' . Dict::S('UI:CSVReport-Icon-Modified') . '">';
					$sCSSRowClass = 'ibo-csv-import--row-modified';
					break;

				case 'RowStatus_Disappeared':
					$iModified++;
					$sFinalClass = $aResRow['finalclass'];
					$oObj = MetaModel::GetObject($sFinalClass, $aResRow['id']->GetPureValue());
					$sUrl = $oObj->GetHyperlink();
					$sStatus = '<img src="' . $sAppRootUrl . 'images/delete.png" title="' . Dict::S('UI:CSVReport-Icon-Missing') . '">';
					$sCSSRowClass = 'ibo-csv-import--row-modified';
					if ($bSimulate) {
						$sMessage = Dict::S('UI:CSVReport-Object-MissingToUpdate');
					} else {
						$sMessage = Dict::S('UI:CSVReport-Object-MissingUpdated');
					}
					break;

				case 'RowStatus_NewObj':
					$iCreated++;
					$sStatus = '<img src="' . $sAppRootUrl . 'images/added.png" title="' . Dict::S('UI:CSVReport-Icon-Created') . '">';
					$sCSSRowClass = 'ibo-csv-import--row-added';
					if ($bSimulate) {
						$sMessage = Dict::S('UI:CSVReport-Object-ToCreate');
					} else {
						$sFinalClass = $aResRow['finalclass'];
						$oObj = MetaModel::GetObject($sFinalClass, $aResRow['id']->GetPureValue());
						$sUrl = $oObj->GetHyperlink();
						$sMessage = Dict::S('UI:CSVReport-Object-Created');
					}
					break;

				case 'RowStatus_Issue':
					$iErrors++;
					$sMessage = self::GetDivAlert($oStatus->GetDescription());
					$sStatus = '<div class="ibo-csv-import--cell-error"><i class="fas fa-exclamation-triangle" title="' . Dict::S('UI:CSVReport-Icon-Error') . '" /></div>';//translate
					$sCSSMessageClass = 'ibo-csv-import--cell-error';
					$sCSSRowClass = 'ibo-csv-import--row-error';
					if (array_key_exists($iLine, $aData)) {
						$aRow = $aData[$iLine];
						$aResult[] = $sTextQualifier . implode($sTextQualifier . $sSeparator . $sTextQualifier, $aRow) . $sTextQualifier; // Remove the first line and store it in case of error
					}
					break;
			}
			$aTableRow['@class'] = $sCSSRowClass;
			$aTableRow['line'] = sprintf("%0{$sMaxLen}d", 1 + $iLine + $iRealSkippedLines);
			$aTableRow['status'] = $sStatus;
			$aTableRow['object'] = $sUrl;

			foreach ($aFieldsMapping as $iNumber => $sAttCode) {
				if (!empty($sAttCode) && ($sAttCode != ':none:') && ($sAttCode != 'finalclass')) {
					$oCellStatus = $aResRow[$iNumber - 1];
					$sCellMessage = '';
					if (isset($aExternalKeysByColumn[$iNumber - 1])) {
						$sExtKeyName = $aExternalKeysByColumn[$iNumber - 1];
						$oExtKeyCellStatus = $aResRow[$sExtKeyName];
						$oExtKeyCellStatus->SetDisplayableValue($oCellStatus->GetCLIValue());
						$oCellStatus = $oExtKeyCellStatus;
					}
					$sHtmlValue = $oCellStatus->GetHTMLValue();
					switch (get_class($oCellStatus)) {
						case 'CellStatus_Issue':
						case 'CellStatus_NullIssue':
							$sCellMessage .= self::GetDivAlert($oCellStatus->GetDescription());
							$aTableRow[$sClassName . '/' . $sAttCode] = '<div class="ibo-csv-import--cell-error">' . Dict::Format('UI:CSVReport-Object-Error', $sHtmlValue) . $sCellMessage . '</div>';
							break;

						case 'CellStatus_SearchIssue':
							$sMessage = Dict::Format('UI:CSVReport-Object-Error', $sHtmlValue);
							$sDivAlert = self::GetDivAlert($oCellStatus->GetDescription());
							$sAllowedValuesLinkUrl = $oCellStatus->GetAllowedValuesLinkUrl();
							$sAllowedValuesLinkLabel = Dict::S('UI:CSVImport:ViewAllPossibleValues');
							$aTableRow[$sClassName . '/' . $sAttCode] =
								<<<HTML
								<div class="ibo-csv-import--cell-error">
									$sMessage
									$sDivAlert
									<a class="ibo-button ibo-is-regular ibo-is-neutral" target="_blank" href="$sAllowedValuesLinkUrl"><i class="fas fa-search"></i>&nbsp;$sAllowedValuesLinkLabel</a>
								</div>
HTML;
							break;

						case 'CellStatus_Ambiguous':
							$sMessage = Dict::Format('UI:CSVReport-Object-Ambiguous', $sHtmlValue);
							$sDivAlert = self::GetDivAlert($oCellStatus->GetDescription());
							$sSearchLinkUrl = $oCellStatus->GetSearchLinkUrl();
							$sSearchLinkLabel = Dict::S('UI:CSVImport:ViewAllAmbiguousValues');
							$aTableRow[$sClassName . '/' . $sAttCode] =
								<<<HTML
								<div class="ibo-csv-import--cell-error">
									$sMessage
									$sDivAlert
									<a class="ibo-button ibo-is-regular ibo-is-neutral" target="_blank" href="$sSearchLinkUrl"><i class="fas fa-search"></i>&nbsp;$sSearchLinkLabel</a>
								</div>
HTML;
							break;

						case 'CellStatus_Modify':
							$aTableRow[$sClassName . '/' . $sAttCode] = '<div class="ibo-csv-import--cell-modified"><b>' . $sHtmlValue . '</b></div>';
							break;

						default:
							$aTableRow[$sClassName . '/' . $sAttCode] = $sHtmlValue . $sCellMessage;
					}
				}
			}
			$aTableRow['message'] = "<div class=\"$sCSSMessageClass\">$sMessage</div>";

			$aTableData[] = $aTableRow;
		}

		$iUnchanged = count($aRes) - $iErrors - $iModified - $iCreated;
		$oContainer = UIContentBlockUIBlockFactory::MakeStandard();
		$oContainer->AddCSSClass("wizContainer");
		$oPage->AddSubBlock($oContainer);

		$oForm = FormUIBlockFactory::MakeStandard('wizForm');
		$oContainer->AddSubBlock($oForm);

		self::addHiddenInputToForm($oForm, "transaction_id", utils::GetNewTransactionId());
		self::addHiddenInputToForm($oForm, "step", ($iCurrentStep + 1));
		self::addHiddenInputToForm($oForm, "separator", $sSeparator);
		self::addHiddenInputToForm($oForm, "text_qualifier", $sTextQualifier);
		self::addHiddenInputToForm($oForm, "header_line", $bHeaderLine);
		self::addHiddenInputToForm($oForm, "nb_skipped_lines", $iNbSkippedLines);
		self::addHiddenInputToForm($oForm, "box_skiplines", $iBoxSkipLines);
		self::addHiddenInputToForm($oForm, "csvdata_truncated", $sCSVDataTruncated, "csvdata_truncated");
		self::addHiddenInputToForm($oForm, "csvdata", $sCSVData);
		self::addHiddenInputToForm($oForm, "encoding", $sEncoding);
		self::addHiddenInputToForm($oForm, "synchro_scope", $sSynchroScope);
		self::addHiddenInputToForm($oForm, "class_name", $sClassName);
		self::addHiddenInputToForm($oForm, "advanced", $bAdvanced);
		self::addHiddenInputToForm($oForm, "date_time_format", $sDateTimeFormat);
		self::addHiddenInputToForm($oForm, "custom_date_time_format", $sCustomDateTimeFormat);
		if (!empty($sSynchroScope)) {
			foreach ($aSynchroUpdate as $sKey => $value) {
				$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("synchro_update[$sKey]", $value));
			}
		}
		foreach ($aFieldsMapping as $iNumber => $sAttCode) {
			$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("field[$iNumber]", $sAttCode));
		}
		foreach ($aSearchFields as $index => $sDummy) {
			$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("search_field[$index]", "1"));
		}
		$aDisplayFilters = [];
		if ($bSimulate) {
			$aDisplayFilters['unchanged'] = Dict::S('UI:CSVImport:ObjectsWillStayUnchanged');
			$aDisplayFilters['modified'] = Dict::S('UI:CSVImport:ObjectsWillBeModified');
			$aDisplayFilters['added'] = Dict::S('UI:CSVImport:ObjectsWillBeAdded');
			$aDisplayFilters['errors'] = Dict::S('UI:CSVImport:ObjectsWillHaveErrors');
		} else {
			$aDisplayFilters['unchanged'] = Dict::S('UI:CSVImport:ObjectsRemainedUnchanged');
			$aDisplayFilters['modified'] = Dict::S('UI:CSVImport:ObjectsWereModified');
			$aDisplayFilters['added'] = Dict::S('UI:CSVImport:ObjectsWereAdded');
			$aDisplayFilters['errors'] = Dict::S('UI:CSVImport:ObjectsHadErrors');
		}
		$oMulticolumn = MultiColumnUIBlockFactory::MakeStandard();
		$oMulticolumn->AddCSSClass('ml-1');
		$oForm->AddSubBlock($oMulticolumn);

		$oCheckBoxUnchanged = InputUIBlockFactory::MakeForInputWithLabel('<img src="' . $sAppRootUrl . 'images/unchanged.png">&nbsp;' . sprintf($aDisplayFilters['unchanged'], $iUnchanged), '', "1", "show_unchanged", "checkbox");
		$oCheckBoxUnchanged->GetInput()->SetIsChecked(true);
		$oCheckBoxUnchanged->SetBeforeInput(false);
		$oCheckBoxUnchanged->GetInput()->AddCSSClass('ibo-input-checkbox');
		$oMulticolumn->AddColumn(ColumnUIBlockFactory::MakeForBlock($oCheckBoxUnchanged));
		$oPage->add_ready_script("$('#show_unchanged').on('click', function(){ToggleRows('ibo-csv-import--row-unchanged')})");

		$oCheckBoxUnchanged = InputUIBlockFactory::MakeForInputWithLabel('<img src="' . $sAppRootUrl . 'images/modified.png">&nbsp;' . sprintf($aDisplayFilters['modified'], $iModified), '', "1", "show_modified", "checkbox");
		$oCheckBoxUnchanged->GetInput()->SetIsChecked(true);
		$oCheckBoxUnchanged->SetBeforeInput(false);
		$oCheckBoxUnchanged->GetInput()->AddCSSClass('ibo-input-checkbox');
		$oMulticolumn->AddColumn(ColumnUIBlockFactory::MakeForBlock($oCheckBoxUnchanged));
		$oPage->add_ready_script("$('#show_modified').on('click', function(){ToggleRows('ibo-csv-import--row-modified')})");

		$oCheckBoxUnchanged = InputUIBlockFactory::MakeForInputWithLabel('<img src="' . $sAppRootUrl . 'images/added.png">&nbsp;' . sprintf($aDisplayFilters['added'], $iCreated), '', "1", "show_created", "checkbox");
		$oCheckBoxUnchanged->GetInput()->SetIsChecked(true);
		$oCheckBoxUnchanged->SetBeforeInput(false);
		$oCheckBoxUnchanged->GetInput()->AddCSSClass('ibo-input-checkbox');
		$oMulticolumn->AddColumn(ColumnUIBlockFactory::MakeForBlock($oCheckBoxUnchanged));
		$oPage->add_ready_script("$('#show_created').on('click', function(){ToggleRows('ibo-csv-import--row-added')})");

		$oCheckBoxUnchanged = InputUIBlockFactory::MakeForInputWithLabel('<span style="color:#A33; background-color: #FFF0F0;"><i class="fas fa-exclamation-triangle"></i>&nbsp;' . sprintf($aDisplayFilters['errors'], $iErrors) . '</span>', '', "1", "show_errors", "checkbox");
		$oCheckBoxUnchanged->GetInput()->SetIsChecked(true);
		$oCheckBoxUnchanged->SetBeforeInput(false);
		$oCheckBoxUnchanged->GetInput()->AddCSSClass('ibo-input-checkbox');
		$oMulticolumn->AddColumn(ColumnUIBlockFactory::MakeForBlock($oCheckBoxUnchanged));
		$oPage->add_ready_script("$('#show_errors').on('click', function(){ToggleRows('ibo-csv-import--row-error')})");

		$oPanel = PanelUIBlockFactory::MakeNeutral('');
		$oPanel->AddCSSClasses(['ibo-datatable-panel', 'mb-5']);
		$oForm->AddSubBlock($oPanel);

		$oTable = DataTableUIBlockFactory::MakeForForm("csvImport", $aColumns, $aTableData);
		$oTable->AddOption('bFullscreen', true);
		$oPanel->AddSubBlock($oTable);


		if ($bSimulate) {
			$oForm->AddSubBlock(ButtonUIBlockFactory::MakeForCancel(Dict::S('UI:Button:Restart'))->SetOnClickJsCode("CSVRestart()"));
		}
		$oForm->AddSubBlock(ButtonUIBlockFactory::MakeForSecondaryAction(Dict::S('UI:Button:Back'))->SetOnClickJsCode("CSVGoBack()"));

		$bShouldConfirm = false;
		if ($bSimulate) {
			// if there are *too many* changes, we should ask the user for a confirmation
			if (count($aRes) >= MetaModel::GetConfig()->Get('csv_import_min_object_confirmation')) {
				$fErrorsPercentage = (100.0 * $iErrors) / count($aRes);
				if ($fErrorsPercentage >= MetaModel::GetConfig()->Get('csv_import_errors_percentage')) {
					$sConfirmationMessage = Dict::Format('UI:CSVReport-Stats-Errors', $fErrorsPercentage);
					$bShouldConfirm = true;
				}
				$fCreatedPercentage = (100.0 * $iCreated) / count($aRes);
				if ($fCreatedPercentage >= MetaModel::GetConfig()->Get('csv_import_creations_percentage')) {
					$sConfirmationMessage = Dict::Format('UI:CSVReport-Stats-Created', $fCreatedPercentage);
					$bShouldConfirm = true;
				}
				$fModifiedPercentage = (100.0 * $iModified) / count($aRes);
				if ($fModifiedPercentage >= MetaModel::GetConfig()->Get('csv_import_modifications_percentage')) {
					$sConfirmationMessage = Dict::Format('UI:CSVReport-Stats-Modified', $fModifiedPercentage);
					$bShouldConfirm = true;
				}

			}
			$sConfirm = $bShouldConfirm ? 'true' : 'false';
			$oForm->AddSubBlock(ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:DoImport'))->SetOnClickJsCode("return DoSubmit($sConfirm)"));

		} else {
			$oForm->AddSubBlock(ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:Done'), "", "", true));
		}

		if ($bShouldConfirm) {
			$sYesButton = Dict::S('UI:Button:Ok');
			$sNoButton = Dict::S('UI:Button:Cancel');
			$oDlg = UIContentBlockUIBlockFactory::MakeStandard("dlg_confirmation")->SetHasForcedDiv(true);
			$oPage->AddSubBlock($oDlg);
			$oDlg->AddSubBlock(new Html($sConfirmationMessage));
			$oDlg->AddSubBlock(new Html(utils::EscapeHtml(Dict::S('UI:CSVImportConfirmMessage'))));

			$oDlgConfirm = UIContentBlockUIBlockFactory::MakeStandard("confirmation_chart")->SetHasForcedDiv(true);
			$oDlg->AddSubBlock($oDlgConfirm);

			$sDlgTitle = Dict::S('UI:CSVImportConfirmTitle');

			$oPage->add_ready_script(
				<<<EOF
	$('#dlg_confirmation').dialog( 
		{
			height: 'auto',
			width: 500,
			modal:true, 
			autoOpen: false, 
			title:'$sDlgTitle',
			buttons:
			[
				{ 
					text: "$sNoButton",
					click: CancelImport,
				},
				{ 
					text: "$sYesButton",
				    class: "ibo-is-primary",
					click: RunImport,
				},
			]
		});
EOF
			);
		}

		$sErrors = json_encode(Dict::Format('UI:CSVImportError_items', $iErrors));
		$sCreated = json_encode(Dict::Format('UI:CSVImportCreated_items', $iCreated));
		$sModified = json_encode(Dict::Format('UI:CSVImportModified_items', $iModified));
		$sUnchanged = json_encode(Dict::Format('UI:CSVImportUnchanged_items', $iUnchanged));

		// Add graphs dependencies
		WebResourcesHelper::EnableC3JSToWebPage($oPage);

		$oPage->add_script(
			<<< EOF
function CSVGoBack()
{
	$('input[name=step]').val($iCurrentStep-1);
	$('#wizForm').submit();
	
}

function CSVRestart()
{
	$('input[name=step]').val(1);
	$('#wizForm').submit();
	
}

function ToggleRows(sCSSClass)
{
	$('.'+sCSSClass).toggle();
}

function DoSubmit(bConfirm)
{
	if (bConfirm) //Ask for a confirmation
	{
		$('#dlg_confirmation').dialog('open');
				
		var chart = c3.generate({
		    bindto: '#confirmation_chart',
		    data: {
		    	columns:  [
					['errors', $iErrors],
					['created', $iCreated],
					['modified', $iModified],
					['unchanged', $iUnchanged]
				],
				colors: {
					errors: '#FF6666',
					created: '#66FF66',
					modified: '#6666FF',
					unchanged: '#666666'
				},
				names: {
					errors: $sErrors,
					created: $sCreated,
					modified: $sModified,
					unchanged: $sUnchanged
				},
		      	type: 'donut'
		    },
		    legend: {
		      show: true,
		    }
		});
	}
	else
	{
		// Submit the form
		$('#wizForm').block();
		$('#wizForm').submit();
	}
	return false;
}

function CancelImport()
{
	$('#dlg_confirmation').dialog('close');
}

function RunImport()
{
	$('#dlg_confirmation').dialog('close');
	// Submit the form
	$('#wizForm').block();
	$('#wizForm').submit();
}
EOF
		);
		if ($iErrors > 0) {
			return $aResult;
		} else {
			return null;
		}
	}

	/**
	 * @param string $message
	 *
	 * @return string
	 */
	private static function GetDivAlert(string $message): string
	{
		return "<div class=\"ibo-csv-import--cell-error ibo-csv-import--cell-message\">$message</div>\n";
	}

	/**
	 * @param Form $oForm
	 * @param string $name
	 * @param mixed $value
	 * @param null $id
	 *
	 * @return void
	 */
	public static function AddHiddenInputToForm(Form $oForm, string $name, mixed $value, $id = null): void
	{
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden($name, (string)$value, $id));
	}
}