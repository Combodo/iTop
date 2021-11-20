<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\Helper\WebResourcesHelper;
use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\CollapsibleSection\CollapsibleSectionUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Field\FieldUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\FieldSet\FieldSetUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Form\FormUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Input\FileSelect\FileSelectUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\Select;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectOptionUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\SelectUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\TextArea;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Title\TitleUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\MultiColumn\Column\ColumnUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\MultiColumn\MultiColumnUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab\AjaxTab;
use Combodo\iTop\Application\UI\Base\Layout\TabContainer\TabContainer;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlockUIBlockFactory;
use Combodo\iTop\Core\CMDBChange\CMDBChangeOrigin;
use Combodo\iTop\Renderer\BlockRenderer;

try {
	require_once('../approot.inc.php');
	require_once(APPROOT.'/application/application.inc.php');
	require_once(APPROOT.'/application/ajaxwebpage.class.inc.php');

	require_once(APPROOT.'/application/startup.inc.php');
	require_once(APPROOT.'/application/loginwebpage.class.inc.php');

	if (utils::SetMinMemoryLimit('256M') === false) {
		IssueLog::Warning('csvimport : cannot set minimum memory_limit !');
	}

	LoginWebPage::DoLogin(); // Check user rights and prompt if needed

	$iStep = utils::ReadParam('step', 1);

	$oPage = new iTopWebPage(Dict::S('UI:Title:BulkImport'));
	$oPage->SetBreadCrumbEntry('ui-tool-bulkimport', Dict::S('Menu:CSVImportMenu'), Dict::S('UI:Title:BulkImport+'), '', 'fas fa-file-import', iTopWebPage::ENUM_BREADCRUMB_ENTRY_ICON_TYPE_CSS_CLASSES);

	/**
	 * Helper function to build a select from the list of valid classes for a given action
	 *
	 * @deprecated since 3.0.0 use GetClassesSelectUIBlock
	 *
	 * @param $sDefaultValue
	 * @param integer $iWidthPx The width (in pixels) of the drop-down list
	 * @param integer $iActionCode The ActionCode (from UserRights) to check for authorization for the classes
	 *
	 * @param string $sName The name of the select in the HTML form
	 *
	 * @return string The HTML fragment corresponding to the select tag
	 */
	function GetClassesSelect($sName, $sDefaultValue, $iWidthPx, $iActionCode = null)
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod('use GetClassesSelectUIBlock');
		$oSelectBlock = GetClassesSelectUIBlock($sName, $sDefaultValue, $iActionCode);

		return BlockRenderer::RenderBlockTemplates($oSelectBlock);
	}

	/**
	 * Helper function to build a select from the list of valid classes for a given action
	 *
	 * @param string $sName The name of the select in the HTML form
	 * @param $sDefaultValue
	 * @param integer $iWidthPx The width (in pixels) of the drop-down list
	 * @param integer $iActionCode The ActionCode (from UserRights) to check for authorization for the classes
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Input\Select\
	 */
	function GetClassesSelectUIBlock(string $sName, $sDefaultValue, int $iActionCode): Select
	{
		$oSelectBlock = SelectUIBlockFactory::MakeForSelect($sName, 'select_'.$sName);
		$oOption = SelectOptionUIBlockFactory::MakeForSelectOption("", Dict::S('UI:CSVImport:ClassesSelectOne'), false);
		$oSelectBlock->AddSubBlock($oOption);
		$aValidClasses = array();
		$aClassCategories = array('bizmodel', 'addon/authentication');
		if (UserRights::IsAdministrator()) {
			$aClassCategories = array('bizmodel', 'application', 'addon/authentication');
		}
		foreach ($aClassCategories as $sClassCategory) {
			foreach (MetaModel::GetClasses($sClassCategory) as $sClassName) {
				if ((is_null($iActionCode) || UserRights::IsActionAllowed($sClassName, $iActionCode)) &&
					(!MetaModel::IsAbstract($sClassName))) {
					$sDisplayName = MetaModel::GetName($sClassName);
					$aValidClasses[$sDisplayName] = SelectOptionUIBlockFactory::MakeForSelectOption($sClassName, $sDisplayName, ($sClassName == $sDefaultValue));
				}
			}
		}
		ksort($aValidClasses);
		foreach ($aValidClasses as $sValue => $oBlock) {
			$oSelectBlock->AddSubBlock($oBlock);
		}

		return $oSelectBlock;
	}

	/**
	 * Helper to 'check' an input in an HTML form if the current value equals the value given
	 *
	 * @param mixed $sCurrentValue The current value to be chacked against the value of the input
	 * @param mixed $sProposedValue The value of the input
	 * @param bool $bInverseCondition Set to true to perform the reversed comparison
	 *
	 * @return string Either ' checked' or an empty string
	 */
	function IsChecked($sCurrentValue, $sProposedValue, $bInverseCondition = false)
	{
		$bCondition = ($sCurrentValue == $sProposedValue);

		return ($bCondition xor $bInverseCondition) ? ' checked' : '';
	}

	/**
	 * Returns the number of occurences of each char from the set in the specified string
	 * @param string $sString The input data
	 * @param array $aSet The set of characters to count
	 * @return array 'char' => nb of occurences
	 */
	function CountCharsFromSet($sString, $aSet)
	{
		$aResult = array();
		$aCount = count_chars($sString);
		foreach($aSet as $sChar)
		{
			$aResult[$sChar] = isset($aCount[ord($sChar)]) ? $aCount[ord($sChar)] : 0;
		}
		return $aResult;
	}
	
	/**
	 * Return the most frequent (and regularly occuring) character among the given set, in the specified lines
	 * @param array $aCSVData The input data, one entry per line
	 * @param array $aPossibleSeparators The list of characters to count
	 * @return string The most frequent character from the set
	 */
	function GuessFromFrequency($aCSVData, $aPossibleSeparators)
	{
		$iLine = 0;
		$iMaxLine = 20; // Process max 20 lines to guess the parameters
		foreach($aPossibleSeparators as $sSep)
		{
			$aGuesses[$sSep]['total'] = $aGuesses[$sSep]['max'] = 0;
			$aGuesses[$sSep]['min'] = 999;
		}
		$aStats = array();
		while(($iLine < count($aCSVData)) && ($iLine < $iMaxLine) )
		{
			if (strlen($aCSVData[$iLine]) > 0)
			{
				$aStats[$iLine] = CountCharsFromSet($aCSVData[$iLine], $aPossibleSeparators);
			}
			$iLine++;
		}
		$iLine = 1;
		foreach($aStats as $aLineStats)
		{
			foreach($aPossibleSeparators as $sSep)
			{
				$aGuesses[$sSep]['total'] += $aLineStats[$sSep];
				if ($aLineStats[$sSep] > $aGuesses[$sSep]['max']) $aGuesses[$sSep]['max'] = $aLineStats[$sSep];
				if ($aLineStats[$sSep] < $aGuesses[$sSep]['min']) $aGuesses[$sSep]['min'] = $aLineStats[$sSep];
			}
			$iLine++;
		}
		
		$aScores = array();
		foreach($aGuesses as $sSep => $aData)
		{
			$aScores[$sSep] = $aData['total'] + $aData['max'] - $aData['min'];
		}
		arsort($aScores, SORT_NUMERIC); // Sort the array, higher scores first
		$aKeys = array_keys($aScores);
		$sSeparator = $aKeys[0]; // Take the first key, the one with the best score
		return $sSeparator;
	}
	
	/**
	 * Try to predict the CSV parameters based on the input data
	 * @param string $sCSVData The input data
	 * @return array 'separator' => the_guessed_separator, 'qualifier' => the_guessed_text_qualifier
	 */
	function GuessParameters($sCSVData)
	{
		$aData = explode("\n", $sCSVData);
		$sSeparator = GuessFromFrequency($aData, array("\t", ',', ';', '|')); // Guess the most frequent (and regular) character on each line
		$sQualifier = GuessFromFrequency($aData, array('"', "'")); // Guess the most frequent (and regular) character on each line
		
		return array('separator' => $sSeparator, 'qualifier' => $sQualifier);
	}
	
	/**
	 * Display a banner for the special "synchro" mode
	 * @param WebPage $oP The Page for the output
	 * @param string $sClass The class of objects to synchronize
	 * @param integer $iCount The number of objects to synchronize
	 */
	 function DisplaySynchroBanner(WebPage $oP, $sClass, $iCount)
	 {
		 $oP->AddSubBlock(AlertUIBlockFactory::MakeForInformation(MetaModel::GetClassIcon($sClass)."&nbsp;".Dict::Format('UI:Title:BulkSynchro_nbItem_ofClass_class', $iCount, MetaModel::GetName($sClass))));
	 }

	/**
	 * Add a paragraph to the body of the page
	 *
	 * @param string $s_html
	 *
	 * @return string
	 */
	function GetDivAlert($s_html)
	{
		return "<div class=\"ibo-csv-import--cell-error ibo-csv-import--cell-message\">$s_html</div>\n";
	}
	/**
	 * Process the CSV data, for real or as a simulation
	 * @param WebPage $oPage The page used to display the wizard
	 * @param bool $bSimulate Whether or not to simulate the data load
	 * @return array The CSV lines in error that were rejected from the load (with the header line - if any) or null
	 */
	function ProcessCSVData(WebPage $oPage, $bSimulate = true)
	{
		$sClassName = utils::ReadParam('class_name', '', false, 'class');
		// Class access right check for the import
		if (UserRights::IsActionAllowed($sClassName, UR_ACTION_MODIFY) == UR_ALLOWED_NO) {
			throw new CoreException(Dict::S('UI:ActionNotAllowed'));
		}

		$aResult = array();
		$sCSVData = utils::ReadParam('csvdata', '', false, 'raw_data');
		$sCSVDataTruncated = utils::ReadParam('csvdata_truncated', '', false, 'raw_data');
		$sSeparator = utils::ReadParam('separator', ',', false, 'raw_data');
		$sTextQualifier = utils::ReadParam('text_qualifier', '"', false, 'raw_data');
		$bHeaderLine = (utils::ReadParam('header_line', '0') == 1);
		$iSkippedLines = 0;
		if (utils::ReadParam('box_skiplines', '0') == 1) {
			$iSkippedLines = utils::ReadParam('nb_skipped_lines', '0');
		}
		$aFieldsMapping = utils::ReadParam('field', array(), false, 'raw_data');
		$aSearchFields = utils::ReadParam('search_field', array(), false, 'field_name');
		$iCurrentStep = $bSimulate ? 4 : 5;
		$bAdvanced = utils::ReadParam('advanced', 0);
		$sEncoding = utils::ReadParam('encoding', 'UTF-8');
		$sSynchroScope = utils::ReadParam('synchro_scope', '', false, 'raw_data');
		$sDateTimeFormat = utils::ReadParam('date_time_format', 'default');
		$sCustomDateTimeFormat = utils::ReadParam('custom_date_time_format', (string)AttributeDateTime::GetFormat(), false, 'raw_data');
		
		$sChosenDateFormat = ($sDateTimeFormat == 'default') ? (string)AttributeDateTime::GetFormat() : $sCustomDateTimeFormat;
		
		if (!empty($sSynchroScope))
		{
			$oSearch = DBObjectSearch::FromOQL($sSynchroScope);
			$sClassName = $oSearch->GetClass(); // If a synchronization scope is set, then the class is fixed !
			$oSet = new DBObjectSet($oSearch);
			$iCount = $oSet->Count();
			DisplaySynchroBanner($oPage, $sClassName, $iCount);
			$aSynchroUpdate = utils::ReadParam('synchro_update', array());
		}
		else
		{
			$sSynchroScope  = '';
			$aSynchroUpdate = null;
		}
				
		// Parse the data set
		$oCSVParser = new CSVParser($sCSVData, $sSeparator, $sTextQualifier, MetaModel::GetConfig()->Get('max_execution_time_per_loop'));
		$aData = $oCSVParser->ToArray($iSkippedLines);
		$iRealSkippedLines = $iSkippedLines;
		if ($bHeaderLine)
		{
			$aResult[] = $sTextQualifier.implode($sTextQualifier.$sSeparator.$sTextQualifier, array_shift($aData)).$sTextQualifier; // Remove the first line and store it in case of error
			$iRealSkippedLines++;
		}
	
		// Format for the line numbers
		$sMaxLen = (strlen(''.count($aData)) < 3) ? 3 : strlen(''.count($aData)); // Pad line numbers to the appropriate number of chars, but at least 3
	
		// Compute the list of search/reconciliation criteria
		$aSearchKeys = array();
		foreach($aSearchFields as $index => $sDummy)
		{
			$sSearchField = $aFieldsMapping[$index];
			$aMatches = array();
			if (preg_match('/(.+)->(.+)/', $sSearchField, $aMatches) > 0)
			{
				$sSearchField = $aMatches[1];
				$aSearchKeys[$aMatches[1]] = '';
			}
			else
			{
				$aSearchKeys[$sSearchField] = '';			
			}
			if (!MetaModel::IsValidFilterCode($sClassName, $sSearchField))
			{
				// Remove invalid or unmapped search fields
				$aSearchFields[$index] = null;
				unset($aSearchKeys[$sSearchField]);			
			}
		}
		
		// Compute the list of fields and external keys to process
		$aExtKeys = array();
		$aAttributes = array();
		$aExternalKeysByColumn = array();
		foreach($aFieldsMapping as $iNumber => $sAttCode)
		{
			$iIndex = $iNumber-1;
			if (!empty($sAttCode) && ($sAttCode != ':none:') && ($sAttCode != 'finalclass'))
			{
				if (preg_match('/(.+)->(.+)/', $sAttCode, $aMatches) > 0)
				{
					$sAttribute = $aMatches[1];
					$sField = $aMatches[2];
					$aExtKeys[$sAttribute][$sField] = $iIndex;
					$aExternalKeysByColumn[$iIndex] = $sAttribute;
				}
				else
				{
					if ($sAttCode == 'id')
					{
							$aAttributes['id'] = $iIndex;
					}
					else
					{
						$oAttDef = MetaModel::GetAttributeDef($sClassName, $sAttCode);
						if ($oAttDef->IsExternalKey())
						{
							$aExtKeys[$sAttCode]['id'] = $iIndex;
							$aExternalKeysByColumn[$iIndex] = $sAttCode;
						}
						else
						{
							$aAttributes[$sAttCode] = $iIndex;				
						}
					}
				}
			}		
		}
		
		$oMyChange = null;
		if (!$bSimulate)
		{
			// We're doing it for real, let's create a change
			$sUserString = CMDBChange::GetCurrentUserName().' (CSV)';
			CMDBObject::SetTrackInfo($sUserString);
			CMDBObject::SetTrackOrigin(CMDBChangeOrigin::CSV_INTERACTIVE);
			$oMyChange = CMDBObject::GetCurrentChange();
		}
		CMDBObject::SetTrackOrigin('csv-interactive');
	
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
		$oBulk->SetReportHtml();

		$oPage->AddSubBlock(InputUIBlockFactory::MakeForHidden("csvdata_truncated", htmlentities($sCSVDataTruncated, ENT_QUOTES, 'UTF-8'), "csvdata_truncated"));
		$aRes = $oBulk->Process($oMyChange);

		$aColumns = [];
		$aColumns ["line"] = ["label" => "Line"];
		$aColumns ["status"] = ["label" => "Status"];
		$aColumns ["object"] = ["label" => "Object"];
		foreach ($aFieldsMapping as $iNumber => $sAttCode) {
			if (!empty($sAttCode) && ($sAttCode != ':none:') && ($sAttCode != 'finalclass')) {
				$aColumns[$sClassName.'/'.$sAttCode] = ["label" => MetaModel::GetLabel($sClassName, $sAttCode)];
			}
		}
		$aColumns["message"] = ["label" => "Message"];

		$iErrors = 0;
		$iCreated = 0;
		$iModified = 0;
		$iUnchanged = 0;

		$aTableData = [];

		foreach ($aRes as $iLine => $aResRow) {
			$aTableRow = [];
			$oStatus = $aResRow['__STATUS__'];
			$sUrl = '';
			$sMessage = '';
			$sCSSRowClass = '';
			$sCSSMessageClass = 'cell_ok';
			switch (get_class($oStatus)) {
				case 'RowStatus_NoChange':
					$iUnchanged++;
					$sFinalClass = $aResRow['finalclass'];
					$oObj = MetaModel::GetObject($sFinalClass, $aResRow['id']->GetPureValue());
					$sUrl = $oObj->GetHyperlink();
					$sStatus = '<img src="../images/unchanged.png" title="'.Dict::S('UI:CSVReport-Icon-Unchanged').'">';
					$sCSSRowClass = 'ibo-csv-import--row-unchanged';
					break;

				case 'RowStatus_Modify':
					$iModified++;
					$sFinalClass = $aResRow['finalclass'];
					$oObj = MetaModel::GetObject($sFinalClass, $aResRow['id']->GetPureValue());
					$sUrl = $oObj->GetHyperlink();
					$sStatus = '<img src="../images/modified.png" title="'.Dict::S('UI:CSVReport-Icon-Modified').'">';
					$sCSSRowClass = 'ibo-csv-import--row-modified';
					break;

				case 'RowStatus_Disappeared':
					$iModified++;
					$sFinalClass = $aResRow['finalclass'];
					$oObj = MetaModel::GetObject($sFinalClass, $aResRow['id']->GetPureValue());
					$sUrl = $oObj->GetHyperlink();
					$sStatus = '<img src="../images/delete.png" title="'.Dict::S('UI:CSVReport-Icon-Missing').'">';
					$sCSSRowClass = 'ibo-csv-import--row-modified';
					if ($bSimulate) {
						$sMessage = Dict::S('UI:CSVReport-Object-MissingToUpdate');
					} else {
						$sMessage = Dict::S('UI:CSVReport-Object-MissingUpdated');
					}
					break;

				case 'RowStatus_NewObj':
					$iCreated++;
					$sFinalClass = $aResRow['finalclass'];
					$sStatus = '<img src="../images/added.png" title="'.Dict::S('UI:CSVReport-Icon-Created').'">';
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
					$sMessage .= GetDivAlert($oStatus->GetDescription());
					$sStatus = '<img src="../images/error.png" title="'.Dict::S('UI:CSVReport-Icon-Error').'">';//translate
					$sCSSMessageClass = 'ibo-csv-import--cell-error';
					$sCSSRowClass = 'ibo-csv-import--row-error';
					if (array_key_exists($iLine, $aData)) {
						$aRow = $aData[$iLine];
						$aResult[] = $sTextQualifier.implode($sTextQualifier.$sSeparator.$sTextQualifier, $aRow).$sTextQualifier; // Remove the first line and store it in case of error
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
						switch (get_class($oExtKeyCellStatus)) {
							case 'CellStatus_Issue':
							case 'CellStatus_SearchIssue':
							case 'CellStatus_NullIssue':
							case 'CellStatus_Ambiguous':
								$sCellMessage .= GetDivAlert($oExtKeyCellStatus->GetDescription());
								break;

							default:
								// Do nothing
						}
					}
					$sHtmlValue = $oCellStatus->GetDisplayableValue();
					switch (get_class($oCellStatus)) {
						case 'CellStatus_Issue':
							$sCellMessage .= GetDivAlert($oCellStatus->GetDescription());
							$aTableRow[$sClassName.'/'.$sAttCode] = '<div class="ibo-csv-import--cell-error">'.Dict::Format('UI:CSVReport-Object-Error', $sHtmlValue).$sCellMessage.'</div>';
							break;

						case 'CellStatus_SearchIssue':
							$sCellMessage .= GetDivAlert($oCellStatus->GetDescription());
							$aTableRow[$sClassName.'/'.$sAttCode] = '<div class="ibo-csv-import--cell-error">ERROR: '.$sHtmlValue.$sCellMessage.'</div>';
							break;

						case 'CellStatus_Ambiguous':
							$sCellMessage .= GetDivAlert($oCellStatus->GetDescription());
							$aTableRow[$sClassName.'/'.$sAttCode] = '<div class="ibo-csv-import--cell-error" >'.Dict::Format('UI:CSVReport-Object-Ambiguous', $sHtmlValue).$sCellMessage.'</div>';
							break;

						case 'CellStatus_Modify':
							$aTableRow[$sClassName.'/'.$sAttCode] = '<div class="ibo-csv-import--cell-modified"><b>'.$sHtmlValue.'</b></div>';
							break;

						default:
							$aTableRow[$sClassName.'/'.$sAttCode] = $sHtmlValue.$sCellMessage;
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

		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("step", ($iCurrentStep + 1)));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("separator", htmlentities($sSeparator, ENT_QUOTES, 'UTF-8')));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("text_qualifier", htmlentities($sTextQualifier, ENT_QUOTES, 'UTF-8')));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("header_line", $bHeaderLine));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("nb_skipped_lines", utils::ReadParam('nb_skipped_lines', '0')));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("box_skiplines", utils::ReadParam('box_skiplines', '0')));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("csvdata_truncated", htmlentities($sCSVDataTruncated, ENT_QUOTES, 'UTF-8'), "csvdata_truncated"));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("csvdata", htmlentities($sCSVData, ENT_QUOTES, 'UTF-8')));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("encoding", $sEncoding));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("synchro_scope", $sSynchroScope));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("class_name", $sClassName));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("advanced", $bAdvanced));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("date_time_format", htmlentities($sDateTimeFormat, ENT_QUOTES, 'UTF-8')));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("custom_date_time_format", htmlentities($sCustomDateTimeFormat, ENT_QUOTES, 'UTF-8')));

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
		$aDisplayFilters = array();
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

		$oCheckBoxUnchanged = InputUIBlockFactory::MakeForInputWithLabel('<img src="../images/unchanged.png">&nbsp;'.sprintf($aDisplayFilters['unchanged'], $iUnchanged), '', "1", "show_unchanged", "checkbox");
		$oCheckBoxUnchanged->GetInput()->SetIsChecked(true);
		$oCheckBoxUnchanged->SetBeforeInput(false);
		$oCheckBoxUnchanged->GetInput()->AddCSSClass('ibo-input-checkbox');
		$oMulticolumn->AddColumn(ColumnUIBlockFactory::MakeForBlock($oCheckBoxUnchanged));
		$oPage->add_ready_script("$('#show_unchanged').on('click', function(){ToggleRows('ibo-csv-import--row-unchanged')})");

		$oCheckBoxUnchanged = InputUIBlockFactory::MakeForInputWithLabel('<img src="../images/modified.png">&nbsp;'.sprintf($aDisplayFilters['modified'], $iModified), '', "1", "show_modified", "checkbox");
		$oCheckBoxUnchanged->GetInput()->SetIsChecked(true);
		$oCheckBoxUnchanged->SetBeforeInput(false);
		$oCheckBoxUnchanged->GetInput()->AddCSSClass('ibo-input-checkbox');
		$oMulticolumn->AddColumn(ColumnUIBlockFactory::MakeForBlock($oCheckBoxUnchanged));
		$oPage->add_ready_script("$('#show_modified').on('click', function(){ToggleRows('ibo-csv-import--row-modified')})");

		$oCheckBoxUnchanged = InputUIBlockFactory::MakeForInputWithLabel('<img src="../images/added.png">&nbsp;'.sprintf($aDisplayFilters['added'], $iCreated), '', "1", "show_created", "checkbox");
		$oCheckBoxUnchanged->GetInput()->SetIsChecked(true);
		$oCheckBoxUnchanged->SetBeforeInput(false);
		$oCheckBoxUnchanged->GetInput()->AddCSSClass('ibo-input-checkbox');
		$oMulticolumn->AddColumn(ColumnUIBlockFactory::MakeForBlock($oCheckBoxUnchanged));
		$oPage->add_ready_script("$('#show_created').on('click', function(){ToggleRows('ibo-csv-import--row-added')})");

		$oCheckBoxUnchanged = InputUIBlockFactory::MakeForInputWithLabel('<img src="../images/error.png">&nbsp;'.sprintf($aDisplayFilters['errors'], $iErrors), '', "1", "show_errors", "checkbox");
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
					$sMessage = Dict::Format('UI:CSVReport-Stats-Errors', $fErrorsPercentage);
					$bShouldConfirm = true;
				}
				$fCreatedPercentage = (100.0 * $iCreated) / count($aRes);
				if ($fCreatedPercentage >= MetaModel::GetConfig()->Get('csv_import_creations_percentage')) {
					$sMessage = Dict::Format('UI:CSVReport-Stats-Created', $fCreatedPercentage);
					$bShouldConfirm = true;
				}
				$fModifiedPercentage = (100.0 * $iModified) / count($aRes);
				if ($fModifiedPercentage >= MetaModel::GetConfig()->Get('csv_import_modifications_percentage')) {
					$sMessage = Dict::Format('UI:CSVReport-Stats-Modified', $fModifiedPercentage);
					$bShouldConfirm = true;
				}

			}
			$sConfirm = $bShouldConfirm ? 'true' : 'false';
			$oForm->AddSubBlock(ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:DoImport'))->SetOnClickJsCode("return DoSubmit({$sConfirm})"));

		} else {
			$oForm->AddSubBlock(ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:Done'), "", "", true));
		}

		if ($bShouldConfirm) {
			$sYesButton = Dict::S('UI:Button:Ok');
			$sNoButton = Dict::S('UI:Button:Cancel');
			$oDlg = UIContentBlockUIBlockFactory::MakeStandard("dlg_confirmation")->AddCSSClass('ibo-hidden');
			$oPage->AddSubBlock($oDlg);
			$oDlg->AddSubBlock(new Html($sMessage));
			$oDlg->AddSubBlock(new Html(htmlentities(Dict::S('UI:CSVImportConfirmMessage'), ENT_QUOTES, 'UTF-8')));

			$oDlgConfirm = UIContentBlockUIBlockFactory::MakeStandard("confirmation_chart")->AddCSSClass('ibo-hidden');
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
			{
				'$sYesButton': RunImport,
				'$sNoButton': CancelImport 
			} 
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
		if ($iErrors > 0)
		{
			return $aResult;
		}
		else
		{
			return null;
		}
	
	}
	/**
	 * Perform the actual load of the CSV data and display the results
	 * @param WebPage $oPage The web page to display the wizard
	 * @return void
	 */
	function LoadData(WebPage $oPage)
	{
		$oTitle = TitleUIBlockFactory::MakeForPage(Dict::S('UI:Title:CSVImportStep5'));
		$oPage->AddSubBlock($oTitle);

		$aResult = ProcessCSVData($oPage, false /* simulate = false */);
		if (is_array($aResult)) {
			$oCollapsibleSection = CollapsibleSectionUIBlockFactory::MakeStandard(Dict::S('UI:CSVImport:LinesNotImported'));
			$oPage->AddSubBlock($oCollapsibleSection);

			$oField = FieldUIBlockFactory::MakeLarge(Dict::S('UI:CSVImport:LinesNotImported+'));
			$oCollapsibleSection->AddSubBlock($oField);

			$oText = new TextArea("", htmlentities(implode("\n", $aResult), ENT_QUOTES, 'UTF-8'), "", 150, 50);
			$oField->AddSubBlock($oText);
		}
	}
	
	/**
	 * Simulate the load of the CSV data and display the results
	 * @param WebPage $oPage The web page to display the wizard
	 * @return void
	 */
	function Preview(WebPage $oPage)
	{
		$oPanel = TitleUIBlockFactory::MakeForPage(Dict::S('UI:Title:CSVImportStep4'));
		$oPage->AddSubBlock($oPanel);
		ProcessCSVData($oPage, true /* simulate */);
	}
	
	/**
	 * Select the mapping between the CSV column and the fields of the objects
	 * @param WebPage $oPage The web page to display the wizard
	 * @return void
	 */
	function SelectMapping(WebPage $oPage)
	{
		$sCSVData = utils::ReadParam('csvdata', '', false, 'raw_data');
		$sCSVDataTruncated = utils::ReadParam('csvdata_truncated', '', false, 'raw_data');
		$sSeparator = utils::ReadParam('separator', ',', false, 'raw_data');
		if ($sSeparator == 'tab') $sSeparator = "\t";
		if ($sSeparator == 'other')
		{
			$sSeparator = utils::ReadParam('other_separator', ',', false, 'raw_data');
		}
		$sTextQualifier = utils::ReadParam('text_qualifier', '"', false, 'raw_data');
		if ($sTextQualifier == 'other')
		{
			$sTextQualifier = utils::ReadParam('other_qualifier', '"', false, 'raw_data');
		}
		$bHeaderLine = (utils::ReadParam('header_line', '0') == 1);
		$sClassName = utils::ReadParam('class_name', '', false, 'class');
		$bAdvanced = utils::ReadParam('advanced', 0);
		$sEncoding = utils::ReadParam('encoding', 'UTF-8');
		$sDateTimeFormat = utils::ReadParam('date_time_format', 'default');
		$sCustomDateTimeFormat = utils::ReadParam('custom_date_time_format', (string)AttributeDateTime::GetFormat(), false, 'raw_data');

		$sSynchroScope = utils::ReadParam('synchro_scope', '', false, 'raw_data');
		if (!empty($sSynchroScope)) {
			$oSearch = DBObjectSearch::FromOQL($sSynchroScope);
			$sClassName = $oSearch->GetClass(); // If a synchronization scope is set, then the class is fixed !
			$oSet = new DBObjectSet($oSearch);
			$iCount = $oSet->Count();
			DisplaySynchroBanner($oPage, $sClassName, $iCount);
			$oClassesSelect = SelectUIBlockFactory::MakeForSelect("class_name", "select_class_name");
			$oDefaultSelect = SelectOptionUIBlockFactory::MakeForSelectOption("$sClassName", MetaModel::GetName($sClassName), true);
			$oClassesSelect->AddSubBlock($oDefaultSelect);
			$aSynchroUpdate = utils::ReadParam('synchro_update', array());
		} else {
			$oClassesSelect = GetClassesSelectUIBlock('class_name', $sClassName, UR_ACTION_BULK_MODIFY);
		}
		$oPanel = TitleUIBlockFactory::MakeForPage(Dict::S('UI:Title:CSVImportStep3'));
		$oPage->AddSubBlock($oPanel);

		$oContainer = UIContentBlockUIBlockFactory::MakeStandard();
		$oContainer->AddCSSClass("wizContainer");
		$oPage->AddSubBlock($oContainer);

		$oForm = FormUIBlockFactory::MakeStandard('wizForm');
		$oForm->SetOnSubmitJsCode("return CheckValues()");
		$oContainer->AddSubBlock($oForm);

		$oMulticolumn = MultiColumnUIBlockFactory::MakeStandard();
		$oForm->AddSubBlock($oMulticolumn);

		$oFieldSelectClass = FieldUIBlockFactory::MakeFromObject(Dict::S('UI:CSVImport:SelectClass'), $oClassesSelect);
		$oFieldSelectClass->AddCSSClass('ibo-field-large');
		$oMulticolumn->AddColumn(ColumnUIBlockFactory::MakeForBlock($oFieldSelectClass));

		$oAdvancedMode = InputUIBlockFactory::MakeForInputWithLabel(Dict::S('UI:CSVImport:AdvancedMode'), "advanced", 1, "advanced", 'checkbox');
		$oAdvancedMode->GetInput()->SetIsChecked(($bAdvanced == 1));
		$oAdvancedMode->SetBeforeInput(false);
		$oAdvancedMode->GetInput()->AddCSSClass('ibo-input-checkbox');
		$oMulticolumn->AddColumn(ColumnUIBlockFactory::MakeForBlock($oAdvancedMode));

		$oDivAdvancedHelp = UIContentBlockUIBlockFactory::MakeStandard("advanced_help")->AddCSSClass('ibo-is-hidden');
		$oForm->AddSubBlock($oDivAdvancedHelp);

		$oDivMapping = UIContentBlockUIBlockFactory::MakeStandard("mapping")->AddCSSClass('mt-5');
		$oMessage = AlertUIBlockFactory::MakeForInformation(Dict::S('UI:CSVImport:SelectAClassFirst'))->SetIsClosable(false)->SetIsCollapsible(false);
		$oDivMapping->AddSubBlock($oMessage);
		$oForm->AddSubBlock($oDivMapping);

		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("step", "4"));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("separator", htmlentities($sSeparator, ENT_QUOTES, 'UTF-8')));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("text_qualifier", htmlentities($sTextQualifier, ENT_QUOTES, 'UTF-8')));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("header_line", $bHeaderLine));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("nb_skipped_lines", utils::ReadParam('nb_skipped_lines', '0')));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("box_skiplines", utils::ReadParam('box_skiplines', '0')));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("csvdata_truncated", htmlentities($sCSVDataTruncated, ENT_QUOTES, 'UTF-8'), "csvdata_truncated"));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("csvdata", htmlentities($sCSVData, ENT_QUOTES, 'UTF-8')));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("encoding", $sEncoding));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("synchro_scope", $sSynchroScope));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("date_time_format", htmlentities($sDateTimeFormat, ENT_QUOTES, 'UTF-8')));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("custom_date_time_format", htmlentities($sCustomDateTimeFormat, ENT_QUOTES, 'UTF-8')));

		if (!empty($sSynchroScope)) {
			foreach ($aSynchroUpdate as $sKey => $value) {
				$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("synchro_update[$sKey]", $value));
			}
		}
		$oForm->AddSubBlock(new Html('<br>'));
		$oForm->AddSubBlock(ButtonUIBlockFactory::MakeForCancel(Dict::S('UI:Button:Restart'))->SetOnClickJsCode("CSVRestart()"));
		$oForm->AddSubBlock(ButtonUIBlockFactory::MakeForSecondaryAction(Dict::S('UI:Button:Back'))->SetOnClickJsCode("CSVGoBack()"));
		$oForm->AddSubBlock(ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:SimulateImport'), '', '', true));

		$sAlertIncompleteMapping = addslashes(Dict::S('UI:CSVImport:AlertIncompleteMapping'));
		$sAlertMultipleMapping = addslashes(Dict::S('UI:CSVImport:AlertMultipleMapping'));
		$sAlertNoSearchCriteria = addslashes(Dict::S('UI:CSVImport:AlertNoSearchCriteria'));

		$oPage->add_ready_script(
			<<<EOF
	$('#select_class_name').on('change', function(ev) { DoMapping(); } );
	$('#advanced').on('click', function(ev) { DoMapping(); } );
EOF
		);
		if ($sClassName != '')
		{
			$aFieldsMapping = utils::ReadParam('field', array(), false, 'raw_data');
			$aSearchFields = utils::ReadParam('search_field', array(), false, 'field_name');
			$sFieldsMapping = addslashes(json_encode($aFieldsMapping));
			$sSearchFields = addslashes(json_encode($aSearchFields));
		
			$oPage->add_ready_script("DoMapping('$sFieldsMapping', '$sSearchFields');"); // There is already a class selected, run the mapping
		}
	
		$oPage->add_script(
<<<EOF
	var aDefaultKeys = new Array();
	var aReadOnlyKeys = new Array();
	
	function CSVGoBack()
	{
		$('input[name=step]').val(2);
		$('#wizForm').removeAttr('onsubmit'); // No need to perform validation checks when going back
		$('#wizForm').submit();
		
	}

	function CSVRestart()
	{
		$('input[name=step]').val(1);
		$('#wizForm').removeAttr('onsubmit'); // No need to perform validation checks when going back
		$('#wizForm').submit();
		
	}

	var ajax_request = null;
	
	function DoMapping(sInitFieldsMapping, sInitSearchFields)
	{
		var class_name = $('select[name=class_name]').val();
		var advanced = $('input[name=advanced]:checked').val();
		if (advanced != 1)
		{
			$('#advanced_help').hide();
		}
		else
		{
			$('#advanced_help').show();
		}
		if (class_name != '')
		{
			var separator = $('input[name=separator]').val();
			var text_qualifier = $('input[name=text_qualifier]').val();
			var header_line = $('input[name=header_line]').val();
			var do_skip_lines = 0;
			if ($('input[name=box_skiplines]').val() == '1')
			{
				do_skip_lines = $('input[name=nb_skipped_lines]').val();
			}
			var csv_data = $('input[name=csvdata]').val();
			var encoding = $('input[name=encoding]').val();
			if (advanced != 1)
			{
				advanced = 0;
			}
			$('#mapping').block();
	
			// Make sure that we cancel any pending request before issuing another
			// since responses may arrive in arbitrary order
			if (ajax_request != null)
			{
				ajax_request.abort();
				ajax_request = null;
			}

			var aParams = { operation: 'display_mapping_form', enctype: 'multipart/form-data', csvdata: csv_data, separator: separator, 
			   	 qualifier: text_qualifier, do_skip_lines: do_skip_lines, header_line: header_line, class_name: class_name,
			   	 advanced: advanced, encoding: encoding };
		
			if (sInitFieldsMapping != undefined)
			{
				aParams.init_field_mapping = sInitFieldsMapping;
				aParams.init_search_field = sInitSearchFields;
			}

			ajax_request = $.post(GetAbsoluteUrlAppRoot()+'pages/ajax.csvimport.php',
				   aParams,
				   function(data) {
					 $('#mapping').empty();
					 $('#mapping').append(data);
					 $('#mapping').unblock();
					}
				 );
		}
	}
	
	function CheckValues()
	{
		// Reset the highlight in case the check has already been executed with failure
		$('select[name^=field]').each( function() {
			$(this).parent().css({'border': '0'});
		});

		bResult = true;
		bMappingOk = true;
		bMultipleMapping = false;
		bSearchOk = false;
		$('select[name^=field]').each( function() {
			$(this).parent().css({'border': '0'});
			if ($(this).val() == '')
			{
				$(this).parent().css({'border': '2px #D81515 solid'});
				bMappingOk = false;
				bResult = false; 
			}
			else
			{
				iOccurences = 0;
				sRefValue = $(this).val();
				$('select[name^=field]').each( function() {
					if ($(this).val() == sRefValue)
					{
						iOccurences++;
					}
				});
				if ((iOccurences > 1) && (sRefValue != ':none:'))
				{
					$(this).parent().css({'border': '2px #D81515 solid'});
					bResult = false; 
					bMultipleMapping = true;
				}
			}
		});
		// At least one search field must be checked
		$('input[name^=search]:checked').each( function() {
				bSearchOk = true;
		});
		if (!bMappingOk)
		{
			alert("$sAlertIncompleteMapping");
		}
		if (bMultipleMapping)
		{
			alert("$sAlertMultipleMapping");
		}
		if (!bSearchOk)
		{
				bResult = false; 
				alert("$sAlertNoSearchCriteria");
		}
		
		if (bResult)
		{
			$('#mapping').block();
			// Re-enable all search_xxx checkboxes so that their value gets posted
			$('input[name^=search]').each(function() {
				$(this).prop('disabled', false);
			});
		}
		return bResult;
	}

	function DoCheckMapping()
	{
		// Check if there is a field mapped to 'id'
		// In which case, it's the only possible search key
		var idSelected = 0;
		var nbSearchKeys = $('input[name^=search]:checked').length;
		var nbMappings = $('select[name^=field]').length;
		for(index=1; index <= nbMappings; index++)
		{
			var selectedValue = $('#mapping_'+index).val();
			 
			if (selectedValue == 'id')
			{
				idSelected = index;
			}
		}
		
		for (index=1; index <= nbMappings; index++)
		{
			sMappingValue = $('#mapping_'+index).val();
			if ((sMappingValue == '') || (sMappingValue == ':none:'))
			{
				// Non-mapped field, uncheck and disabled
				$('#search_'+index).prop('checked', false);
				$('#search_'+index).prop('disabled', true);
			}
			else if (aReadOnlyKeys.indexOf(sMappingValue) >= 0)
			{
				// Read-only attribute forced to reconciliation key
				$('#search_'+index).prop('checked', true);
				$('#search_'+index).prop('disabled', true);
			}
			else if (index == idSelected)
			{
				// The 'id' field was mapped, it's the only possible reconciliation key
				$('#search_'+index).prop('checked', true);
				$('#search_'+index).prop('disabled', true);
			}
			else
			{
				if (idSelected > 0)
				{
					// The 'id' field was mapped, it's the only possible reconciliation key
					$('#search_'+index).prop('checked', false);
					$('#search_'+index).prop('disabled', true);
				}
				else
				{
					$('#search_'+index).prop('disabled', false);
					if (nbSearchKeys == 0)
					{
						// No search key was selected, select the default ones
						for(j =0; j < aDefaultKeys.length; j++)
						{
							if (sMappingValue == aDefaultKeys[j])
							{
								$('#search_'+index).prop('checked', true);
							}
						}
					}
				}
			}
		}
	}
EOF
	);
	}
	
	/**
	 * Select the options of the CSV load and check for CSV parsing errors
	 * @param WebPage $oPage The current web page
	 * @return void
	 */
	function SelectOptions(WebPage $oPage)
	{
		$sOperation = utils::ReadParam('operation', 'csv_data');
		$sCSVData = '';
		switch($sOperation)
		{
			case 'file_upload':
			$oDocument = utils::ReadPostedDocument('csvdata');
			if (!$oDocument->IsEmpty())
			{
				$sCSVData = $oDocument->GetData();
			}
			break;

			default:
			$sCSVData = utils::ReadPostedParam('csvdata', '', 'raw_data');
		}
		$sEncoding = utils::ReadParam('encoding', 'UTF-8');
	
		// Compute a subset of the data set, now that we know the charset
		if ($sEncoding == 'UTF-8')
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
			$sUTF8Data = iconv($sEncoding, 'UTF-8//IGNORE//TRANSLIT', $sCSVData);
		}
	
		$aGuesses = GuessParameters($sUTF8Data); // Try to predict the parameters, based on the input data
		
		$iSkippedLines = utils::ReadParam('nb_skipped_lines', '');
		$bBoxSkipLines = utils::ReadParam('box_skiplines', 0);
		$sTextQualifier = utils::ReadParam('text_qualifier', '', false, 'raw_data');
		if ($sTextQualifier == '') // May be set to an empty value by the previous page
		{
			$sTextQualifier = $aGuesses['qualifier'];	
		}
		$sOtherTextQualifier = in_array($sTextQualifier, array('"', "'")) ? '' : $sTextQualifier;
		$bHeaderLine = utils::ReadParam('header_line', 0);
		$sClassName = utils::ReadParam('class_name', '', false, 'class');
		$bAdvanced = utils::ReadParam('advanced', 0);
		$aFieldsMapping = utils::ReadParam('field', array(), false, 'raw_data');
		$aSearchFields = utils::ReadParam('search_field', array(), false, 'field_name');

		// Create a truncated version of the data used for the fast preview
		// Take about 20 lines of data... knowing that some lines may contain carriage returns
		$iMaxLen = strlen($sUTF8Data);
		if ($iMaxLen > 0) {
			$iMaxLines = 20;
			$iCurPos = true;
			while (($iCurPos > 0) && ($iMaxLines > 0)) {
				$pos = strpos($sUTF8Data, "\n", $iCurPos);
				if ($pos !== false) {
					$iCurPos = 1 + $pos;
				} else {
					$iCurPos = strlen($sUTF8Data);
					$iMaxLines = 1;
				}
				$iMaxLines--;
			}
			$sCSVDataTruncated = substr($sUTF8Data, 0, $iCurPos);
		} else {
			$sCSVDataTruncated = '';
		}

		$sSynchroScope = utils::ReadParam('synchro_scope', '', false, 'raw_data');
		if (!empty($sSynchroScope)) {
			$oSearch = DBObjectSearch::FromOQL($sSynchroScope);
			$sClassName = $oSearch->GetClass();
			$oSet = new DBObjectSet($oSearch);
			$iCount = $oSet->Count();
			DisplaySynchroBanner($oPage, $sClassName, $iCount);
			$aSynchroUpdate = utils::ReadParam('synchro_update', array());
		}
		$oPanel = TitleUIBlockFactory::MakeForPage(Dict::S('UI:Title:CSVImportStep2'));
		$oPage->AddSubBlock($oPanel);

		$oForm = FormUIBlockFactory::MakeStandard('wizForm');
		$oPage->AddSubBlock($oForm);


		$oContainer = PanelUIBlockFactory::MakeNeutral('');
		$oForm->AddSubBlock($oContainer);

		$oMulticolumn = MultiColumnUIBlockFactory::MakeStandard();
		$oMulticolumn->AddCSSClass('wizContainer');
		$oContainer->AddSubBlock($oMulticolumn);

		//SeparatorCharacter
		$oFieldSetSeparator = FieldSetUIBlockFactory::MakeStandard(Dict::S('UI:CSVImport:SeparatorCharacter'));
		$oMulticolumn->AddColumn(ColumnUIBlockFactory::MakeForBlock($oFieldSetSeparator));

		$aSep = array(
			';' => Dict::S('UI:CSVImport:SeparatorSemicolon+'),
			',' => Dict::S('UI:CSVImport:SeparatorComma+'),
			'tab' => Dict::S('UI:CSVImport:SeparatorTab+'),
		);
		$sSeparator = utils::ReadParam('separator', '', false, 'raw_data');
		if ($sSeparator == '') // May be set to an empty value by the previous page
		{
			$sSeparator = $aGuesses['separator'];
		}
		if ($sSeparator == "\t") {
			$sSeparator = "tab";
		}
		$sOtherSeparator = in_array($sSeparator, array(',', ';', "\t")) ? '' : $sSeparator;
		$aSep['other'] = Dict::S('UI:CSVImport:SeparatorOther').' <input type="text" size="3" maxlength="1" name="other_separator"  id="other_separator" value="'.htmlentities($sOtherSeparator, ENT_QUOTES, 'UTF-8').'" onChange="DoPreview()"/>';

		foreach ($aSep as $sVal => $sLabel) {
			$oRadio = InputUIBlockFactory::MakeForInputWithLabel($sLabel, "separator", htmlentities($sVal, ENT_QUOTES, 'UTF-8'), $sLabel, "radio");
			$oRadio->GetInput()->SetIsChecked(($sVal == $sSeparator));
			$oRadio->SetBeforeInput(false);
			$oRadio->GetInput()->AddCSSClass('ibo-input--label-right');
			$oRadio->GetInput()->AddCSSClass('ibo-input-checkbox');
			$oFieldSetSeparator->AddSubBlock($oRadio);
			$oFieldSetSeparator->AddSubBlock(new Html('</br>'));
		}
		$oPage->add_ready_script("$('[name=\"separator\"]').on('click', function() { DoPreview(); });");

		//TextQualifierCharacter
		$oFieldSetTextQualifier = FieldSetUIBlockFactory::MakeStandard(Dict::S('UI:CSVImport:TextQualifierCharacter'));
		$oMulticolumn->AddColumn(ColumnUIBlockFactory::MakeForBlock($oFieldSetTextQualifier));

		$aQualifiers = array(
			'"' => Dict::S('UI:CSVImport:QualifierDoubleQuote+'),
			'\'' => Dict::S('UI:CSVImport:QualifierSimpleQuote+'),
		);
		$aQualifiers['other'] = Dict::S('UI:CSVImport:QualifierOther').' <input type="text" size="3" maxlength="1" name="other_qualifier" value="'.htmlentities($sOtherTextQualifier, ENT_QUOTES, 'UTF-8').'" onChange="DoPreview()/>';
		//	<input type="text" size="3" maxlength="1" name="other_qualifier"  value="'.htmlentities($sOtherTextQualifier, ENT_QUOTES, 'UTF-8').'" onChange="DoPreview()"/>
		foreach ($aQualifiers as $sVal => $sLabel) {
			$oRadio = InputUIBlockFactory::MakeForInputWithLabel($sLabel, "text_qualifier", htmlentities($sVal, ENT_QUOTES, 'UTF-8'), $sLabel, "radio");
			$oRadio->GetInput()->SetIsChecked(($sVal == $sTextQualifier));
			$oRadio->SetBeforeInput(false);
			$oRadio->GetInput()->AddCSSClass('ibo-input-checkbox');
			$oFieldSetTextQualifier->AddSubBlock($oRadio);
			$oFieldSetTextQualifier->AddSubBlock(new Html('</br>'));
		}
		$oPage->add_ready_script("$('[name=\"text_qualifier\"]').on('click', function() { DoPreview(); });");

		//CommentsAndHeader
		$oFieldSetCommentsAndHeader = FieldSetUIBlockFactory::MakeStandard(Dict::S('UI:CSVImport:CommentsAndHeader'));
		$oMulticolumn->AddColumn(ColumnUIBlockFactory::MakeForBlock($oFieldSetCommentsAndHeader));

		$oCheckBoxHeader = InputUIBlockFactory::MakeForInputWithLabel(Dict::S('UI:CSVImport:TreatFirstLineAsHeader'), "header_line", "1", "box_header", "checkbox");
		$oCheckBoxHeader->GetInput()->SetIsChecked(($bHeaderLine == 1));
		$oCheckBoxHeader->SetBeforeInput(false);
		$oCheckBoxHeader->GetInput()->AddCSSClass('ibo-input-checkbox');
		$oFieldSetCommentsAndHeader->AddSubBlock($oCheckBoxHeader);
		$oFieldSetCommentsAndHeader->AddSubBlock(new Html('</br>'));

		$oCheckBoxSkip = InputUIBlockFactory::MakeForInputWithLabel(Dict::Format('UI:CSVImport:Skip_N_LinesAtTheBeginning', '<input type="text" size=2 name="nb_skipped_lines" id="nb_skipped_lines" onChange="DoPreview()" value="'.$iSkippedLines.'">'), "box_skiplines", "1", "box_skiplines",
			"checkbox");
		$oCheckBoxSkip->GetInput()->AddCSSClass('ibo-input-checkbox');
		$oCheckBoxSkip->GetInput()->SetIsChecked(($bBoxSkipLines == 1));
		$oCheckBoxSkip->SetBeforeInput(false);
		$oFieldSetCommentsAndHeader->AddSubBlock($oCheckBoxSkip);

		$oPage->add_ready_script("$('#box_header').on('click', function() { DoPreview(); });");
		$oPage->add_ready_script("$('#box_skiplines').on('click', function() { DoPreview(); });");

		//date format
		$oFieldSetDate = FieldSetUIBlockFactory::MakeStandard(Dict::S('UI:CSVImport:DateAndTimeFormats'));
		$oMulticolumn->AddColumn(ColumnUIBlockFactory::MakeForBlock($oFieldSetDate));

		$sDateTimeFormat = utils::ReadParam('date_time_format', 'default');
		$sCustomDateTimeFormat = utils::ReadParam('custom_date_time_format', (string)AttributeDateTime::GetFormat(), false, 'raw_data');

		$sDefaultFormat = htmlentities((string)AttributeDateTime::GetFormat(), ENT_QUOTES, 'UTF-8');
		$sExample = htmlentities(date((string)AttributeDateTime::GetFormat()), ENT_QUOTES, 'UTF-8');
		$oRadioDefault = InputUIBlockFactory::MakeForInputWithLabel(Dict::Format('UI:CSVImport:DefaultDateTimeFormat_Format_Example', $sDefaultFormat, $sExample), "date_time_format", "default", "radio_date_time_std", "radio");
		$oRadioDefault->GetInput()->SetIsChecked(($sDateTimeFormat == (string)AttributeDateTime::GetFormat()));
		$oRadioDefault->SetBeforeInput(false);
		$oRadioDefault->GetInput()->AddCSSClass('ibo-input-checkbox');
		$oFieldSetDate->AddSubBlock($oRadioDefault);
		$oFieldSetDate->AddSubBlock(new Html('</br>'));

		$sFormatInput = '<input type="text" size="15" name="custom_date_time_format" id="excel_custom_date_time_format" title="" value="'.htmlentities($sCustomDateTimeFormat, ENT_QUOTES, 'UTF-8').'"/>';
		$oRadioCustom = InputUIBlockFactory::MakeForInputWithLabel(Dict::Format('UI:CSVImport:CustomDateTimeFormat', $sFormatInput), "date_time_format", "custom", "radio_date_time_custom", "radio");
		$oRadioCustom->GetInput()->SetIsChecked($sDateTimeFormat !== (string)AttributeDateTime::GetFormat());
		$oRadioCustom->SetBeforeInput(false);
		$oRadioCustom->GetInput()->AddCSSClass('ibo-input-checkbox');
		$oFieldSetDate->AddSubBlock($oRadioCustom);

		$oPage->add_ready_script("$('#custom_date_time_format').on('click', function() { DoPreview(); });");
		$oPage->add_ready_script("$('#radio_date_time_std').on('click', function() { DoPreview(); });");

		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("csvdata_truncated", htmlentities($sCSVDataTruncated, ENT_QUOTES, 'UTF-8'), "csvdata_truncated"));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("csvdata", htmlentities($sUTF8Data, ENT_QUOTES, 'UTF-8'), 'csvdata'));
		// The encoding has changed, keep that information within the wizard
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("encoding", "UTF-8"));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("class_name", $sClassName));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("advanced", $bAdvanced));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("synchro_scope", $sSynchroScope));

		foreach ($aFieldsMapping as $iNumber => $sAttCode) {
			$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("field[$iNumber]", $sAttCode));
		}
		foreach ($aSearchFields as $index => $sDummy) {
			$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("search_field[$index]", "1"));
		}
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("step", "3"));
		if (!empty($sSynchroScope)) {
			foreach ($aSynchroUpdate as $sKey => $value) {
				$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("synchro_update[$sKey]", $value));
			}
		}
		$oFieldSetSeparator = PanelUIBlockFactory::MakeNeutral(Dict::S('UI:CSVImport:CSVDataPreview'));
		$oFieldSetSeparator->AddCSSClass('ibo-datatable-panel');
		$oFieldSetSeparator->AddCSSClass('mt-5');
		$oForm->AddSubBlock($oFieldSetSeparator);

		$oDivPreview = UIContentBlockUIBlockFactory::MakeStandard('preview');
		$oDivPreview->AddCSSClass('ibo-is-visible');
		$oFieldSetSeparator->AddSubBlock($oDivPreview);
		$oDivPreview->AddSubBlock(new Html('</br>'));

		$oForm->AddSubBlock(ButtonUIBlockFactory::MakeForSecondaryAction(Dict::S('UI:Button:Back'))->SetOnClickJsCode("GoBack()"));
		$oForm->AddSubBlock(ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:Next'), '', "", true));

		$oPage->add_script(
			<<<EOF
	function GoBack()
	{
		$('input[name=step]').val(1);
		$('#wizForm').submit();
	}
	
	var ajax_request = null;
	
	function DoPreview()
	{
		var separator = $('input[name=separator]:checked').val();
		if (separator == 'other')
		{
			separator = $('#other_separator').val();
		}
		var text_qualifier = $('input[name=text_qualifier]:checked').val();
		if (text_qualifier == 'other')
		{
			text_qualifier = $('#other_qualifier').val();
		}
		var do_skip_lines = 0;
		if ($('#box_skiplines:checked').val() != null)
		{
			do_skip_lines = $('#nb_skipped_lines').val();
		}
		var header_line = 0;
		if ($('#box_header:checked').val() != null)
		{
			header_line = 1;
		}
		var encoding = $('input[name=encoding]').val();

		$('#preview').block();
		
		// Make sure that we cancel any pending request before issuing another
		// since responses may arrive in arbitrary order
		if (ajax_request != null)
		{
			ajax_request.abort();
			ajax_request = null;
		}
		
		ajax_request = $.post(GetAbsoluteUrlAppRoot()+'pages/ajax.csvimport.php',
			   { operation: 'parser_preview', enctype: 'multipart/form-data', csvdata: $("#csvdata_truncated").val(), separator: separator, qualifier: text_qualifier, do_skip_lines: do_skip_lines, header_line: header_line, encoding: encoding },
			   function(data) {
				 $('#preview').empty();
				 $('#preview').append(data);
				 $('#preview').unblock();
				}
			 );
	}
EOF
	);
		$sJSTooltip = json_encode('<div class="date_format_tooltip">'.Dict::S('UI:CSVImport:CustomDateTimeFormatTooltip').'</div>');
		$oPage->add_ready_script(
<<<EOF
DoPreview();
$('#custom_date_time_format').tooltip({content: function() { return $sJSTooltip; } });
$('#custom_date_time_format').on('click', function() { $('#radio_date_time_custom').prop('checked', true); });
EOF
		);
	}

	/**
	 *  Prompt for the data to be loaded (either via a file or a copy/paste)
	 * @param WebPage $oPage The current web page
	 * @return void
	 */
	function Welcome(iTopWebPage $oPage)
	{
		$sSynchroScope = utils::ReadParam('synchro_scope', '', false, 'raw_data');
		if (!empty($sSynchroScope)) {
			$oSearch = DBObjectSearch::FromOQL($sSynchroScope);
			$sClassName = $oSearch->GetClass();
			$oSet = new DBObjectSet($oSearch);
			$iCount = $oSet->Count();
			DisplaySynchroBanner($oPage, $sClassName, $iCount);
			$aSynchroUpdate = utils::ReadParam('synchro_update', array());
		} else {
			$aSynchroUpdate = null;
		}

		$oPanel = PanelUIBlockFactory::MakeForInformation(Dict::S('UI:Title:BulkImport+'));
		$oPage->AddSubBlock($oPanel);

		$oTabContainer = new TabContainer('tabs1', 'import');
		$oPanel->AddMainBlock($oTabContainer);

		//** Tab:LoadFromFile */
		$oTabFile = $oTabContainer->AddTab('LoadFromFile', Dict::S('UI:CSVImport:Tab:LoadFromFile'));
		$oFormFile = FormUIBlockFactory::MakeStandard();
		$oTabFile->AddSubBlock($oFormFile);

		$oSelectFile = FileSelectUIBlockFactory::MakeStandard("csvdata");
		$oFieldFile = FieldUIBlockFactory::MakeFromObject(Dict::S('UI:CSVImport:SelectFile'), $oSelectFile);
		$oFormFile->AddSubBlock($oFieldFile);

		$oSelectEncodingFile = SelectUIBlockFactory::MakeForSelect("encoding");
		$oFieldEncodingFile = FieldUIBlockFactory::MakeFromObject(Dict::S('UI:CSVImport:Encoding'), $oSelectEncodingFile);
		$oFormFile->AddSubBlock($oFieldEncodingFile);

		$sSeparator = utils::ReadParam('separator', '', false, 'raw_data');
		$sTextQualifier = utils::ReadParam('text_qualifier', '', false, 'raw_data');
		$bHeaderLine = utils::ReadParam('header_line', true);
		$sClassName = utils::ReadParam('class_name', '');
		$bAdvanced = utils::ReadParam('advanced', 0);
		$sEncoding = utils::ReadParam('encoding', '');
		$sDateTimeFormat = utils::ReadParam('date_time_format', 'default');
		$sCustomDateTimeFormat = utils::ReadParam('custom_date_time_format', (string)AttributeDateTime::GetFormat(), false, 'raw_data');
		if ($sEncoding == '') {
			$sEncoding = MetaModel::GetConfig()->Get('csv_file_default_charset');
		}
		$aFieldsMapping = utils::ReadParam('field', array(), false, 'raw_data');
		$aSearchFields = utils::ReadParam('search_field', array(), false, 'field_name');
		$aPossibleEncodings = utils::GetPossibleEncodings(MetaModel::GetConfig()->GetCSVImportCharsets());

		foreach ($aPossibleEncodings as $sIconvCode => $sDisplayName) {
			$oSelectEncodingFile->AddSubBlock(SelectOptionUIBlockFactory::MakeForSelectOption($sIconvCode, $sDisplayName, ($sEncoding == $sIconvCode)));
		}
		$oFormFile->AddSubBlock(InputUIBlockFactory::MakeForHidden("step", '2'));
		$oFormFile->AddSubBlock(InputUIBlockFactory::MakeForHidden("operation", "file_upload"));
		$oFormFile->AddSubBlock(InputUIBlockFactory::MakeForHidden("header_line", $bHeaderLine));
		$oFormFile->AddSubBlock(InputUIBlockFactory::MakeForHidden("nb_skipped_lines", utils::ReadParam('nb_skipped_lines', '0')));
		$oFormFile->AddSubBlock(InputUIBlockFactory::MakeForHidden("box_skiplines", utils::ReadParam('box_skiplines', '0')));
		$oFormFile->AddSubBlock(InputUIBlockFactory::MakeForHidden("class_name", $sClassName));
		$oFormFile->AddSubBlock(InputUIBlockFactory::MakeForHidden("advanced", $bAdvanced));
		$oFormFile->AddSubBlock(InputUIBlockFactory::MakeForHidden("synchro_scope", $sSynchroScope));

		if (!empty($sSynchroScope)) {
			foreach ($aSynchroUpdate as $sKey => $value) {
				$oFormFile->AddSubBlock(InputUIBlockFactory::MakeForHidden("synchro_update['.$sKey.']", $value));
			}
		}
		foreach ($aFieldsMapping as $iNumber => $sAttCode) {
			$oFormFile->AddSubBlock(InputUIBlockFactory::MakeForHidden("['field$iNumber]", $sAttCode));
		}
		foreach ($aSearchFields as $index => $sDummy) {
			$oFormFile->AddSubBlock(InputUIBlockFactory::MakeForHidden("search_field[$index]", "1"));
		}
		$oToolbarFile = ToolbarUIBlockFactory::MakeForButton();
		$oToolbarFile->AddSubBlock(ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:Next'), '', '', true));
		$oFormFile->AddSubBlock($oToolbarFile);

		//** Tab:PasteData */
		$oTabPaste = $oTabContainer->AddTab('UI:CSVImport:Tab:PasteData', Dict::S('UI:CSVImport:Tab:CopyPaste'));
		$oFormPaste = FormUIBlockFactory::MakeStandard();
		$oTabPaste->AddSubBlock($oFormPaste);


		$sCSVData = utils::ReadParam('csvdata', '', false, utils::ENUM_SANITIZATION_FILTER_STRING);
		$oTextarea = new TextArea('csvdata', $sCSVData, '', 120, 30);
		$oTextarea->AddCSSClasses(['ibo-input-text', 'ibo-is-code']);
		$oFieldPaste = FieldUIBlockFactory::MakeFromObject(Dict::S('UI:CSVImport:PasteData'), $oTextarea);
		$oFormPaste->AddSubBlock($oFieldPaste);

		$oFormPaste->AddSubBlock(InputUIBlockFactory::MakeForHidden("encoding", 'UTF-8'));
		$oFormPaste->AddSubBlock(InputUIBlockFactory::MakeForHidden("step", '2'));
		$oFormPaste->AddSubBlock(InputUIBlockFactory::MakeForHidden("separator", htmlentities($sSeparator, ENT_QUOTES, 'UTF-8')));
		$oFormPaste->AddSubBlock(InputUIBlockFactory::MakeForHidden("text_qualifier", htmlentities($sTextQualifier, ENT_QUOTES, 'UTF-8')));
		$oFormPaste->AddSubBlock(InputUIBlockFactory::MakeForHidden("date_time_format", htmlentities($sDateTimeFormat, ENT_QUOTES, 'UTF-8')));
		$oFormPaste->AddSubBlock(InputUIBlockFactory::MakeForHidden("custom_date_time_format", htmlentities($sCustomDateTimeFormat, ENT_QUOTES, 'UTF-8')));
		$oFormPaste->AddSubBlock(InputUIBlockFactory::MakeForHidden("header_line", $bHeaderLine));
		$oFormPaste->AddSubBlock(InputUIBlockFactory::MakeForHidden("nb_skipped_lines", utils::ReadParam('nb_skipped_lines', '0')));
		$oFormPaste->AddSubBlock(InputUIBlockFactory::MakeForHidden("box_skiplines", utils::ReadParam('box_skiplines', '0')));
		$oFormPaste->AddSubBlock(InputUIBlockFactory::MakeForHidden("class_name", $sClassName));
		$oFormPaste->AddSubBlock(InputUIBlockFactory::MakeForHidden("advanced", $bAdvanced));
		$oFormPaste->AddSubBlock(InputUIBlockFactory::MakeForHidden("synchro_scope", $sSynchroScope));
		$oFormPaste->AddSubBlock(ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:Next'), '', "", true));

		if (!empty($sSynchroScope)) {
			foreach ($aSynchroUpdate as $sKey => $value) {
				$oFormPaste->AddSubBlock(InputUIBlockFactory::MakeForHidden("synchro_update[$sKey]", $value));
			}
		}
		foreach ($aFieldsMapping as $iNumber => $sAttCode) {
			$oFormPaste->AddSubBlock(InputUIBlockFactory::MakeForHidden("field[$iNumber]", $sAttCode));
		}
		foreach ($aSearchFields as $index => $sDummy) {
			$oFormPaste->AddSubBlock(InputUIBlockFactory::MakeForHidden("search_field[$index]", "1"));
		}

		/*	if (!empty($sCSVData)) {
				// When there are some data, activate the 'copy & paste' tab by default
				$oPage->SelectTab('tabs1', Dict::S('UI:CSVImport:Tab:CopyPaste'));
			}*/
		//Tab:Template
		$oTabTemplate = $oTabContainer->AddTab('tabsTemplate', Dict::S('UI:CSVImport:Tab:Templates'));
		$oFieldTemplate = FieldUIBlockFactory::MakeFromObject(Dict::S('UI:CSVImport:PickClassForTemplate'), GetClassesSelectUIBlock('template_class', '', UR_ACTION_BULK_MODIFY));
		$oTabTemplate->AddSubBlock($oFieldTemplate);
		$oDivTemplate = UIContentBlockUIBlockFactory::MakeStandard("template")->AddCSSClass("ibo-is-visible");
		$oTabTemplate->AddSubBlock($oDivTemplate);

		$oPage->add_script(
			<<<EOF
var ajax_request = null;

function DisplayTemplate(sClassName) {

$('#template').block();

// Make sure that we cancel any pending request before issuing another
// since responses may arrive in arbitrary order
if (ajax_request != null)
{
	ajax_request.abort();
	ajax_request = null;
}

ajax_request = $.post(GetAbsoluteUrlAppRoot()+'pages/ajax.csvimport.php',
	   { operation: 'get_csv_template', class_name: sClassName },
	   function(data) {
		 $('#template').empty();
		 $('#template').append(data);
		 $('#template').unblock();
		}
	 );
}
EOF
	);
		$oPage->add_ready_script(
<<<EOF
$('#select_template_class').change( function() {
	DisplayTemplate(this.value);
});
EOF
	);

		if (Utils::GetConfig()->Get('csv_import_history_display'))
		{
			$oPage->SetCurrentTabContainer('tabs1');
			$oPage->AddAjaxTab('UI:History:BulkImports', utils::GetAbsoluteUrlAppRoot().'pages/csvimport.php?step=11', true /* bCache */,
				null, AjaxTab::ENUM_TAB_PLACEHOLDER_MISC);
		}
	}
			
	switch($iStep)
	{
		case 11:
			// Asynchronous tab
			$oPage = new AjaxPage('');
			BulkChange::DisplayImportHistory($oPage);
			$oPage->add_ready_script('$("#CSVImportHistory table.listResults").tableHover();');
			$oPage->add_ready_script('$("#CSVImportHistory table.listResults").tablesorter( { widgets: ["myZebra", "truncatedList"]} );');	
			break;
		
		case 10:
			// Case generated by BulkChange::DisplayImportHistory
			$iChange = (int)utils::ReadParam('changeid', 0);
			BulkChange::DisplayImportHistoryDetails($oPage, $iChange);
			break;
			
		case 5:
			LoadData($oPage);
			break;
			
		case 4:
			Preview($oPage);
			break;
			
		case 3:
			SelectMapping($oPage);
			break;
			
		case 2:
			SelectOptions($oPage);
			break;
			
		case 1:
		case 6: // Loop back here when we are done
		default:
			Welcome($oPage);
	}
	
	$oPage->output();
}
catch(CoreException $e)
{
	require_once(APPROOT.'/setup/setuppage.class.inc.php');
	$oP = new ErrorPage(Dict::S('UI:PageTitle:FatalError'));
	$oP->add("<h1>".Dict::S('UI:FatalErrorMessage')."</h1>\n");	
	$oP->error(Dict::Format('UI:Error_Details', $e->getHtmlDesc()));	
	$oP->output();

	if (MetaModel::IsLogEnabledIssue())
	{
		if (MetaModel::IsValidClass('EventIssue'))
		{
			$oLog = new EventIssue();

			$oLog->Set('message', $e->getMessage());
			$oLog->Set('userinfo', '');
			$oLog->Set('issue', $e->GetIssue());
			$oLog->Set('impact', 'Page could not be displayed');
			$oLog->Set('callstack', $e->getTrace());
			$oLog->Set('data', $e->getContextData());
			$oLog->DBInsertNoReload();
		}

		IssueLog::Error($e->getMessage());
	}

	// For debugging only
	//throw $e;
}
catch(Exception $e)
{
	require_once(APPROOT.'/setup/setuppage.class.inc.php');
	$oP = new ErrorPage(Dict::S('UI:PageTitle:FatalError'));
	$oP->add("<h1>".Dict::S('UI:FatalErrorMessage')."</h1>\n");	
	$oP->error(Dict::Format('UI:Error_Details', $e->getMessage()));	
	$oP->output();

	if (MetaModel::IsLogEnabledIssue())
	{
		if (MetaModel::IsValidClass('EventIssue'))
		{
			$oLog = new EventIssue();

			$oLog->Set('message', $e->getMessage());
			$oLog->Set('userinfo', '');
			$oLog->Set('issue', 'PHP Exception');
			$oLog->Set('impact', 'Page could not be displayed');
			$oLog->Set('callstack', $e->getTrace());
			$oLog->Set('data', array());
			$oLog->DBInsertNoReload();
		}

		IssueLog::Error($e->getMessage());
	}
}