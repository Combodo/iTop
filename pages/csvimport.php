<?php
/**
 * CSV Import Page
 * Wizard to import CSV (or TSV) data into the database
 *
 * @package     iTopAppplication
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/lgpl-3.0.html LGPL
 * @link        http://www.combodo.com/itop iTop
 */
ini_set('memory_limit', '256M');
require_once('../application/application.inc.php');
require_once('../application/itopwebpage.class.inc.php');

require_once('../application/startup.inc.php');

require_once('../application/loginwebpage.class.inc.php');
LoginWebPage::DoLogin(); // Check user rights and prompt if needed

$oContext = new UserContext();
$oAppContext = new ApplicationContext();
$currentOrganization = utils::ReadParam('org_id', 1);
$iStep = utils::ReadParam('step', 1);

$oPage = new iTopWebPage(Dict::S('UI:Title:BulkImport'), $currentOrganization);

/**
 * Helper function to build a select from the list of valid classes for a given action
 * @param string $sName The name of the select in the HTML form
 * @param string $sDefaulfValue The defaut value (i.e the value selected by default)
 * @param integer $iWidthPx The width (in pixels) of the drop-down list
 * @param integer $iActionCode The ActionCode (from UserRights) to check for authorization for the classes
 * @return string The HTML fragment corresponding to the select tag
 */
function GetClassesSelect($sName, $sDefaultValue, $iWidthPx, $iActionCode = null)
{
	$sHtml = "<select id=\"select_$sName\" name=\"$sName\">";
	$sHtml .= "<option tyle=\"width: ".$iWidthPx."px;\" title=\"Select the class you want to load\" value=\"\">".Dict::S('UI:CSVImport:ClassesSelectOne')."</option>\n";
	$aValidClasses = array();
	foreach(MetaModel::GetClasses('bizmodel') as $sClassName)
	{
		if ( (is_null($iActionCode) || UserRights::IsActionAllowed($sClassName, $iActionCode)) &&
		     (!MetaModel::IsAbstract($sClassName)) )
		{
			$sSelected = ($sClassName == $sDefaultValue) ? " selected" : "";
			$sDescription = MetaModel::GetClassDescription($sClassName);
			$sDisplayName = MetaModel::GetName($sClassName);
			$aValidClasses[$sDisplayName] = "<option style=\"width: ".$iWidthPx."px;\" title=\"$sDescription\" value=\"$sClassName\"$sSelected>$sDisplayName</option>";
		}
	}
	ksort($aValidClasses);
	$sHtml .= implode("\n", $aValidClasses);
	
	$sHtml .= "</select>";
	return $sHtml;
}

/**
 * Helper to 'check' an input in an HTML form if the current value equals the value given
 * @param mixed $sCurrentValue The current value to be chacked against the value of the input
 * @param mixed $sProposedValue The value of the input
 * @param bool $bInverseCondition Set to true to perform the reversed comparison
 * @return string Either ' checked' or an empty string
 */
function IsChecked($sCurrentValue, $sProposedValue, $bInverseCondition  = false)
{
	$bCondition = ($sCurrentValue == $sProposedValue);
	
	return ($bCondition xor $bInverseCondition) ? ' checked' : '';
}

/**
 * Get the user friendly name for an 'extended' attribute code i.e 'name', becomes 'Name' and 'org_id->name' becomes 'Organization->Name'
 * @param string $sClassName The name of the class
 * @param string $sAttCodeEx Either an attribute code of ext_key_name->att_code
 * @return string A user friendly format of the string: AttributeName or AttributeName->ExtAttributeName
 */
function GetFriendlyAttCodeName($sClassName, $sAttCodeEx)
{
	$sFriendlyName = '';
	if (preg_match('/(.+)->(.+)/', $sAttCodeEx, $aMatches) > 0)
	{
		$sAttribute = $aMatches[1];
		$sField = $aMatches[2];
		$oAttDef = MetaModel::GetAttributeDef($sClassName, $sAttribute);
		if ($oAttDef->IsExternalKey())
		{
			$sTargetClass = $oAttDef->GetTargetClass();
			$oTargetAttDef = MetaModel::GetAttributeDef($sTargetClass, $sField);
			$sFriendlyName = $oAttDef->GetLabel().'->'.$oTargetAttDef->GetLabel();
		}
		else
		{
			 // hum, hum... should never happen, we'd better raise an exception
			 throw(new Exception(Dict::Format('UI:CSVImport:ErrorExtendedAttCode', $sAttCodeEx, $sAttribute, $sClassName)));
		}

	}
	else
	{
		if ($sAttCodeEx == 'id')
		{
			$sFriendlyName = Dict::S('UI:CSVImport:idField');
		}
		else
		{
			$oAttDef = MetaModel::GetAttributeDef($sClassName, $sAttCodeEx);
			$sFriendlyName = $oAttDef->GetLabel();
		}
	}
	return $sFriendlyName;
}

/**
 * Returns the number of occurences of each char from the set in the specified string
 * @param string $sString The input data
 * @param array $aSet The set of characters to count
 * @return hash 'char' => nb of occurences
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
 * @return hash 'separator' => the_guessed_separator, 'qualifier' => the_guessed_text_qualifier
 */
function GuessParameters($sCSVData)
{
	$aData = explode("\n", $sCSVData);
	$sSeparator = GuessFromFrequency($aData, array("\t", ',', ';', '|')); // Guess the most frequent (and regular) character on each line
	$sQualifier = GuessFromFrequency($aData, array('"', "'")); // Guess the most frequent (and regular) character on each line
	
	return array('separator' => $sSeparator, 'qualifier' => $sQualifier);
}

/**
 * Process the CSV data, for real or as a simulation
 * @param WebPage $oPage The page used to display the wizard
 * @param UserContext $oContext The current user context
 * @param bool $bSimulate Whether or not to simulate the data load
 * @return array The CSV lines in error that were rejected from the load (with the header line - if any) or null
 */
function ProcessCSVData(WebPage $oPage, UserContext $oContext, $bSimulate = true)
{
	$aResult = array();
	$sCSVData = utils::ReadParam('csvdata', '');
	$sCSVDataTruncated = utils::ReadParam('csvdata_truncated', '');
	$sSeparator = utils::ReadParam('separator', ',');
	$sTextQualifier = utils::ReadParam('text_qualifier', '"');
	$bHeaderLine = (utils::ReadParam('header_line', '0') == 1);
	$iRealSkippedLines = $iSkippedLines = utils::ReadParam('nb_skipped_lines', '0');
	$sClassName = utils::ReadParam('class_name', '');
	$aFieldsMapping = utils::ReadParam('field', array());
	$aSearchFields = utils::ReadParam('search_field', array());
	$iCurrentStep = $bSimulate ? 4 : 5;
	$bAdvanced = utils::ReadParam('advanced', 0);
	
	// Parse the data set
	$oCSVParser = new CSVParser($sCSVData, $sSeparator, $sTextQualifier);
	$aData = $oCSVParser->ToArray($iSkippedLines);
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
		$oMyChange = MetaModel::NewObject("CMDBChange");
		$oMyChange->Set("date", time());
		if (UserRights::GetUser() != UserRights::GetRealUser())
		{
			$sUserString = UserRights::GetRealUser()." on behalf of ".UserRights::GetUser();
		}
		else
		{
			$sUserString = UserRights::GetUser();
		}
		$oMyChange->Set("userinfo", $sUserString);
		$iChangeId = $oMyChange->DBInsert();		
	}

	$oBulk = new BulkChange(
		$sClassName,
		$aData,
		$aAttributes,
		$aExtKeys,
		array_keys($aSearchKeys)		
	);
	
	$oPage->add('<input type="hidden" name="csvdata_truncated" id="csvdata_truncated" value="'.htmlentities($sCSVDataTruncated, ENT_QUOTES, 'UTF-8').'"/>');
	$aRes = $oBulk->Process($oMyChange);
	
	$sHtml = '<table id="bulk_preview">';
	$sHtml .= '<tr><th>Line</th>';
	$sHtml .= '<th>Status</th>';
	$sHtml .= '<th>Object</th>';
	foreach($aFieldsMapping as $iNumber => $sAttCode)
	{
		if (!empty($sAttCode) && ($sAttCode != ':none:') && ($sAttCode != 'finalclass'))
		{
			$sHtml .= "<th>".GetFriendlyAttCodeName($sClassName, $sAttCode)."</th>";
		}
	}
	$sHtml .= '<th>Message</th>';
	$sHtml .= '</tr>';
	$iLine = 0;
	
	$iErrors = 0;
	$iCreated = 0;
	$iModified = 0;
	$iUnchanged = 0;
	
	foreach($aData as $aRow)
	{
		$oStatus = $aRes[$iLine]['__STATUS__'];
		$sUrl = '';
		$sMessage = '';
		$sCSSRowClass = '';
		$sCSSMessageClass = 'cell_ok';
		switch(get_class($oStatus))
		{
			case 'RowStatus_NoChange':
			$iUnchanged++;
			$sFinalClass = $aRes[$iLine]['finalclass'];
			$oObj = $oContext->GetObject($sFinalClass, $aRes[$iLine]['id']->GetValue());
			$sUrl = $oObj->GetHyperlink();
			$sStatus = '<img src="../images/unchanged.png" title="Unchanged">';
			$sCSSRowClass = 'row_unchanged';
			break;
					
			case 'RowStatus_Modify':
			$iModified++;
			$sFinalClass = $aRes[$iLine]['finalclass'];
			$oObj = $oContext->GetObject($sFinalClass, $aRes[$iLine]['id']->GetValue());
			$sUrl = $oObj->GetHyperlink();
			$sStatus = '<img src="../images/modified.png" title="Modified">';
			$sCSSRowClass = 'row_modified';
			break;
					
			case 'RowStatus_NewObj':
			$iCreated++;
			$sFinalClass = $aRes[$iLine]['finalclass'];
			$sStatus = '<img src="../images/added.png" title="Created">';
			$sCSSRowClass = 'row_added';
			if ($bSimulate)
			{
				$sMessage = 'Object will be created';				
			}
			else
			{
				$sFinalClass = $aRes[$iLine]['finalclass'];
				$oObj = $oContext->GetObject($sFinalClass, $aRes[$iLine]['id']->GetValue());
				$sUrl = $oObj->GetHyperlink();
				$sMessage = 'Object created';				
			}
			break;
					
			case 'RowStatus_Issue':
			$iErrors++;
			$sMessage .= $oPage->GetP($oStatus->GetDescription());
			$sStatus = '<img src="../images/error.png" title="Error">';
			$sCSSMessageClass = 'cell_error';
			$sCSSRowClass = 'row_error';
			$aResult[] = $sTextQualifier.implode($sTextQualifier.$sSeparator.$sTextQualifier,$aRow).$sTextQualifier; // Remove the first line and store it in case of error
			break;		
		}
		$sHtml .= '<tr class="'.$sCSSRowClass.'">';
		$sHtml .= "<td>".sprintf("%0{$sMaxLen}d", 1+$iLine+$iRealSkippedLines)."</td>";
		$sHtml .= "<td>$sStatus</td>";
		$sHtml .= "<td>$sUrl</td>";
		foreach($aFieldsMapping as $iNumber => $sAttCode)
		{
			if (!empty($sAttCode) && ($sAttCode != ':none:') && ($sAttCode != 'finalclass'))
			{
				$oCellStatus = $aRes[$iLine][$iNumber -1];
				$sCellMessage = '';
				if (isset($aExternalKeysByColumn[$iNumber -1]))
				{
					$sExtKeyName = $aExternalKeysByColumn[$iNumber -1];
					$oExtKeyCellStatus = $aRes[$iLine][$sExtKeyName];
					switch(get_class($oExtKeyCellStatus))
					{
						case 'CellStatus_Issue':
						$sCellMessage .= $oPage->GetP($oExtKeyCellStatus->GetDescription());
						break;
						
						case 'CellStatus_Ambiguous':
						$sCellMessage .= $oPage->GetP($oExtKeyCellStatus->GetDescription());
						break;
						
						default:
						// Do nothing
					}
				}
				switch(get_class($oCellStatus))
				{
					case 'CellStatus_Issue':
					$sCellMessage .= $oPage->GetP($oCellStatus->GetDescription());
					$sHtml .= '<td class="cell_error">ERROR: '.htmlentities($aData[$iLine][$iNumber-1], ENT_QUOTES, 'UTF-8').$sCellMessage.'</td>';
					break;
					
					case 'CellStatus_Ambiguous':
					$sCellMessage .= $oPage->GetP($oCellStatus->GetDescription());
					$sHtml .= '<td class="cell_error">AMBIGUOUS: '.htmlentities($aData[$iLine][$iNumber-1], ENT_QUOTES, 'UTF-8').$sCellMessage.'</td>';
					break;
					
					case 'CellStatus_Modify':
					$sHtml .= '<td class="cell_modified"><b>'.htmlentities($aData[$iLine][$iNumber-1], ENT_QUOTES, 'UTF-8').'</b></td>';
					break;
					
					default:
					$sHtml .= '<td class="cell_ok">'.htmlentities($aData[$iLine][$iNumber-1], ENT_QUOTES, 'UTF-8').$sCellMessage.'</td>';
				}
			}
		}
		$sHtml .= "<td class=\"$sCSSMessageClass\">$sMessage</td>";
		$iLine++;
		$sHtml .= '</tr>';
	}
	$sHtml .= '</table>';
	$oPage->add('<div class="wizContainer">');
	$oPage->add('<form enctype="multipart/form-data" id="wizForm" method="post" onSubmit="return CheckValues()">');
	$oPage->add('<input type="hidden" name="step" value="'.($iCurrentStep+1).'"/>');
	$oPage->add('<input type="hidden" name="separator" value="'.htmlentities($sSeparator, ENT_QUOTES, 'UTF-8').'"/>');
	$oPage->add('<input type="hidden" name="text_qualifier" value="'.htmlentities($sTextQualifier, ENT_QUOTES, 'UTF-8').'"/>');
	$oPage->add('<input type="hidden" name="header_line" value="'.$bHeaderLine.'"/>');
	$oPage->add('<input type="hidden" name="box_skiplines" value="'.(($iSkippedLines > 0) ? 1 : 0).'"/>');
	$oPage->add('<input type="hidden" name="nb_skipped_lines" value="'.$iSkippedLines.'"/>');
	$oPage->add('<input type="hidden" name="csvdata" value="'.htmlentities($sCSVData, ENT_QUOTES, 'UTF-8').'"/>');
	$oPage->add('<input type="hidden" name="csvdata_truncated" value="'.htmlentities($sCSVDataTruncated, ENT_QUOTES, 'UTF-8').'"/>');
	$oPage->add('<input type="hidden" name="class_name" value="'.$sClassName.'"/>');
	$oPage->add('<input type="hidden" name="advanced" value="'.$bAdvanced.'"/>');
	foreach($aFieldsMapping as $iNumber => $sAttCode)
	{
		$oPage->add('<input type="hidden" name="field['.$iNumber.']" value="'.$sAttCode.'"/>');
	}
	foreach($aSearchFields as $index => $sDummy)
	{
		$oPage->add('<input type="hidden" name="search_field['.$index.']" value="1"/>');
	}
	$aFieldsMapping = utils::ReadParam('field', array());
	$aSearchFields = utils::ReadParam('search_field', array());
	$aDisplayFilters = array();
	if ($bSimulate)
	{
		$aDisplayFilters['unchanged'] = Dict::S('UI:CSVImport:ObjectsWillStayUnchanged');
		$aDisplayFilters['modified'] = Dict::S('UI:CSVImport:ObjectsWillBeModified');
		$aDisplayFilters['added'] = Dict::S('UI:CSVImport:ObjectsWillBeAdded');
		$aDisplayFilters['errors'] = Dict::S('UI:CSVImport:ObjectsWillHaveErrors');
	}
	else
	{
		$aDisplayFilters['unchanged'] = Dict::S('UI:CSVImport:ObjectsRemainedUnchanged');
		$aDisplayFilters['modified'] = Dict::S('UI:CSVImport:ObjectsWereModified');
		$aDisplayFilters['added'] = Dict::S('UI:CSVImport:ObjectsWereAdded');
		$aDisplayFilters['errors'] = Dict::S('UI:CSVImport:ObjectsHadErrors');
	}
	$oPage->add('<p><input type="checkbox" checked id="show_unchanged" onClick="ToggleRows(\'row_unchanged\')"/>&nbsp;<img src="../images/unchanged.png">&nbsp;'.sprintf($aDisplayFilters['unchanged'], $iUnchanged).'&nbsp&nbsp;');
	$oPage->add('<input type="checkbox" checked id="show_modified" onClick="ToggleRows(\'row_modified\')"/>&nbsp;<img src="../images/modified.png">&nbsp;'.sprintf($aDisplayFilters['modified'], $iModified).'&nbsp&nbsp;');
	$oPage->add('<input type="checkbox" checked id="show_created" onClick="ToggleRows(\'row_added\')"/>&nbsp;<img src="../images/added.png">&nbsp;'.sprintf($aDisplayFilters['added'], $iCreated).'&nbsp&nbsp;');
	$oPage->add('<input type="checkbox" checked id="show_errors" onClick="ToggleRows(\'row_error\')"/>&nbsp;<img src="../images/error.png">&nbsp;'.sprintf($aDisplayFilters['errors'], $iErrors).'</p>');
	$oPage->add('<div style="overflow-y:auto">');
	$oPage->add($sHtml);
	$oPage->add('</div> <!-- end of preview -->');
	$oPage->add('<p><input type="button" value="'.Dict::S('UI:Button:Back').'" onClick="CSVGoBack()"/>&nbsp;&nbsp;');
	if ($bSimulate)
	{
		$oPage->add('<input type="submit" value="'.Dict::S('UI:Button:DoImport').'"/></p>');
	}
	else
	{
		$oPage->add('<input type="submit" value="'.Dict::S('UI:Button:Done').'"/></p>');
	}
	$oPage->add('</form>');
	$oPage->add('</div> <!-- end of wizForm -->');
	$oPage->add_script(
<<< EOF
	function CSVGoBack()
	{
		$('input[name=step]').val($iCurrentStep-1);
		$('#wizForm').submit();
		
	}

	function ToggleRows(sCSSClass)
	{
		$('.'+sCSSClass).toggle();
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
 * @param UserContext $oContext Current user's context
 * @return void
 */
function LoadData(WebPage $oPage, UserContext $oContext)
{
	$oPage->add('<h2>'.Dict::S('UI:Title:CSVImportStep5').'</h2>');
	$aResult = ProcessCSVData($oPage, $oContext, false /* simulate = false */);
	if (is_array($aResult))
	{
		$oPage->StartCollapsibleSection(Dict::S('UI:CSVImport:LinesNotImported'), false);
		$oPage->p(Dict::S('UI:CSVImport:LinesNotImported+'));
		$oPage->add('<textarea rows="30" cols="100">');
		$oPage->add(htmlentities(implode("\n", $aResult), ENT_QUOTES, 'UTF-8'));
		$oPage->add('</textarea>');
		$oPage->EndCollapsibleSection();
	}
}

/**
 * Simulate the load of the CSV data and display the results
 * @param WebPage $oPage The web page to display the wizard
 * @param UserContext $oContext Current user's context
 * @return void
 */
function Preview(WebPage $oPage, UserContext $oContext)
{
	$oPage->add('<h2>'.Dict::S('UI:Title:CSVImportStep4').'</h2>');
	ProcessCSVData($oPage, $oContext, true /* simulate */);
}

/**
 * Select the mapping between the CSV column and the fields of the objects
 * @param WebPage $oPage The web page to display the wizard
 * @return void
 */
function SelectMapping(WebPage $oPage)
{
	$sCSVData = utils::ReadParam('csvdata', '');
	$sCSVDataTruncated = utils::ReadParam('csvdata_truncated', '');;
	$sSeparator = utils::ReadParam('separator', ',');
	if ($sSeparator == 'tab') $sSeparator = "\t";
	if ($sSeparator == 'other')
	{
		$sSeparator = utils::ReadParam('other_separator', ',');
	}
	$sTextQualifier = utils::ReadParam('text_qualifier', '"');
	if ($sTextQualifier == 'other')
	{
		$sTextQualifier = utils::ReadParam('other_qualifier', '"');
	}
	$bHeaderLine = (utils::ReadParam('header_line', '0') == 1);
	$iSkippedLines = 0;
	if (utils::ReadParam('box_skiplines', '0') == 1)
	{
		$iSkippedLines = utils::ReadParam('nb_skipped_lines', '0');
	}
	$sClassName = utils::ReadParam('class_name', '');
	$bAdvanced = utils::ReadParam('advanced', 0);

	$oPage->add('<h2>'.Dict::S('UI:Title:CSVImportStep3').'</h2>');
	$oPage->add('<div class="wizContainer">');
	$oPage->add('<form enctype="multipart/form-data" id="wizForm" method="post" onSubmit="return CheckValues()"><table style="width:100%" class="transparent"><tr><td>'.Dict::S('UI:CSVImport:SelectClass').' ');
	$oPage->add(GetClassesSelect('class_name', $sClassName, 300, UR_ACTION_BULK_MODIFY));
	$oPage->add('</td><td style="text-align:right"><input type="checkbox" name="advanced" value="1" '.IsChecked($bAdvanced, 1).' onChange="DoMapping()">&nbsp;'.Dict::S('UI:CSVImport:AdvancedMode').'</td></tr></table>');
	$oPage->add('<div style="padding:1em;display:none" id="advanced_help" style="display:none">'.Dict::S('UI:CSVImport:AdvancedMode+').'</div>');
	$oPage->add('<div id="mapping"><p><br/>'.Dict::S('UI:CSVImport:SelectAClassFirst').'<br/></p></div>');
	$oPage->add('<input type="hidden" name="step" value="4"/>');
	$oPage->add('<input type="hidden" name="separator" value="'.htmlentities($sSeparator, ENT_QUOTES, 'UTF-8').'"/>');
	$oPage->add('<input type="hidden" name="text_qualifier" value="'.htmlentities($sTextQualifier, ENT_QUOTES, 'UTF-8').'"/>');
	$oPage->add('<input type="hidden" name="header_line" value="'.$bHeaderLine.'"/>');
	$oPage->add('<input type="hidden" name="box_skiplines" value="'.(($iSkippedLines > 0) ? 1 : 0).'"/>');
	$oPage->add('<input type="hidden" name="nb_skipped_lines" value="'.$iSkippedLines.'"/>');
	$oPage->add('<input type="hidden" name="csvdata_truncated" id="csvdata_truncated" value="'.htmlentities($sCSVDataTruncated, ENT_QUOTES, 'UTF-8').'"/>');
	$oPage->add('<input type="hidden" name="csvdata" value="'.htmlentities($sCSVData, ENT_QUOTES, 'UTF-8').'"/>');
	$oPage->add('<input type="hidden" name="_charset_"/>');
	$oPage->add('<p><input type="button" value="'.Dict::S('UI:Button:Back').'" onClick="CSVGoBack()"/>&nbsp;&nbsp;');
	$oPage->add('<input type="submit" value="'.Dict::S('UI:Button:SimulateImport').'"/></p>');
	$oPage->add('</form>');
	$oPage->add('</div>');
	
	$sAlertIncompleteMapping = Dict::S('UI:CSVImport:AlertIncompleteMapping');
	$sAlertNoSearchCriteria = Dict::S('UI:CSVImport:AlertNoSearchCriteria');
	
	$oPage->add_ready_script(
<<<EOF
	$('#select_class_name').change( DoMapping );
EOF
);
	if ($sClassName != '')
	{
		$oPage->add_ready_script("DoMapping();"); // There is already a class selected, run the mapping
	}

	$oPage->add_script(
<<<EOF
	var aDefaultKeys = new Array();
	
	function CSVGoBack()
	{
		$('input[name=step]').val(2);
		$('#wizForm').submit();
		
	}

	var ajax_request = null;
	
	function DoMapping()
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
			var nb_lines_skipped = $('input[name=nb_skipped_lines]').val();
			var csv_data = $('input[name=csvdata]').val();
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
	
			ajax_request = $.post('ajax.csvimport.php',
				   { operation: 'display_mapping_form', enctype: 'multipart/form-data', csvdata: csv_data, separator: separator, 
				   	 qualifier: text_qualifier, nb_lines_skipped: nb_lines_skipped, header_line: header_line, class_name: class_name,
				   	 advanced: advanced },
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
		bResult = true;
		bMappingOk = true;
		bSearchOk = false;
		$('select[name^=field]').each( function() {
			if ($(this).val() == '')
			{
				$(this).parent().css({'border': '2px #D81515 solid'});
				bMappingOk = false;
				bResult = false; 
			}
			else
			{
				$(this).parent().css({'border': '0'});
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
				$(this).attr('disabled', false);
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
				$('#search_'+index).attr('checked', false);
				$('#search_'+index).attr('disabled', true);
			}
			else if (index == idSelected)
			{
				// The 'id' field was mapped, it's the only possible reconciliation key
				$('#search_'+index).attr('checked', true);
				$('#search_'+index).attr('disabled', true);
			}
			else
			{
				if (idSelected > 0)
				{
					// The 'id' field was mapped, it's the only possible reconciliation key
					$('#search_'+index).attr('checked', false);
					$('#search_'+index).attr('disabled', true);
				}
				else
				{
					$('#search_'+index).attr('disabled', false);
					if (nbSearchKeys == 0)
					{
						// No search key was selected, select the default ones
						for(j =0; j < aDefaultKeys.length; j++)
						{
							if (sMappingValue == aDefaultKeys[j])
							{
								$('#search_'+index).attr('checked', true);
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
	$sOperation = utils::ReadParam('operation', 'csv_data', 'post');
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
		$sCSVData = utils::ReadParam('csvdata', '', 'post');
	}
	
	$aGuesses = GuessParameters($sCSVData); // Try to predict the parameters, based on the input data
	
	$sSeparator = utils::ReadParam('separator', '');
	if ($sSeparator == '') // May be set to an empty value by the previous page
	{
		$sSeparator = $aGuesses['separator'];	
	}
	$iSkippedLines = utils::ReadParam('nb_skipped_lines', '');
	$bBoxSkipLines = utils::ReadParam('box_skiplines', 0);
	if ($sSeparator == 'tab') $sSeparator = "\t";
	$sOtherSeparator = in_array($sSeparator, array(',', ';', "\t")) ? '' : $sSeparator;
	$sTextQualifier = utils::ReadParam('text_qualifier', '');
	if ($sTextQualifier == '') // May be set to an empty value by the previous page
	{
		$sTextQualifier = $aGuesses['qualifier'];	
	}
	$sOtherTextQualifier = in_array($sTextQualifier, array('"', "'")) ? '' : $sTextQualifier;
	$bHeaderLine = utils::ReadParam('header_line', 0);
	$sClassName = utils::ReadParam('class_name', '');
	$bAdvanced = utils::ReadParam('advanced', 0);
	
	// Create a truncated version of the data used for the fast preview
	// Take about 20 lines of data... knowing that some lines may contain carriage returns
	$iMaxLines = 20;
	$iMaxLen = strlen($sCSVData);
	$iCurPos = true;
	while ( ($iCurPos > 0) && ($iMaxLines > 0))
	{
		$pos = strpos($sCSVData, "\n", $iCurPos);
		if ($pos !== false)
		{
			$iCurPos = 1+$pos;
		}
		else
		{
			$iCurPos = strlen($sCSVData);
			$iMaxLines = 1;
		}
		$iMaxLines--;
	}
	$sCSVDataTruncated = substr($sCSVData, 0, $iCurPos);
	
	$oPage->add('<h2>'.Dict::S('UI:Title:CSVImportStep2').'</h2>');
	$oPage->add('<div class="wizContainer">');
	$oPage->add('<table><tr><td style="vertical-align:top;padding-right:50px;background:#E8F3CF">');
	$oPage->add('<form enctype="multipart/form-data" id="wizForm" method="post" id="csv_options">');
	$oPage->add('<h3>'.Dict::S('UI:CSVImport:SeparatorCharacter').'</h3>');
	$oPage->add('<p><input type="radio" name="separator" value="," onChange="DoPreview()"'.IsChecked($sSeparator, ',').'/> '.Dict::S('UI:CSVImport:SeparatorComma+').'<br/>');
	$oPage->add('<input type="radio" name="separator" value=";" onChange="DoPreview()"'.IsChecked($sSeparator, ';').'/> '.Dict::S('UI:CSVImport:SeparatorSemicolon+').'<br/>');
	$oPage->add('<input type="radio" name="separator" value="tab" onChange="DoPreview()"'.IsChecked($sSeparator, "\t").'/> '.Dict::S('UI:CSVImport:SeparatorTab+').'<br/>');
	$oPage->add('<input type="radio" name="separator" value="other"  onChange="DoPreview()"'.IsChecked($sOtherSeparator, '', true).'/> '.Dict::S('UI:CSVImport:SeparatorOther').' <input type="text" size="3" maxlength="1" name="other_separator" id="other_separator" value="'.$sOtherSeparator.'" onChange="DoPreview()"/>');
	$oPage->add('</p>');
	$oPage->add('</td><td style="vertical-align:top;padding-right:50px;background:#E8F3CF">');
	$oPage->add('<h3>'.Dict::S('UI:CSVImport:TextQualifierCharacter').'</h3>');
	$oPage->add('<p><input type="radio" name="text_qualifier" value="&#34;" onChange="DoPreview()"'.IsChecked($sTextQualifier, '"').'/> '.Dict::S('UI:CSVImport:QualifierDoubleQuote+').'<br/>');
	$oPage->add('<input type="radio" name="text_qualifier" value="&#39;"  onChange="DoPreview()"'.IsChecked($sTextQualifier, "'").'/> '.Dict::S('UI:CSVImport:QualifierSimpleQuote+').'<br/>');
	$oPage->add('<input type="radio" name="text_qualifier" value="other"  onChange="DoPreview()"'.IsChecked($sOtherTextQualifier, '', true).'/> '.Dict::S('UI:CSVImport:QualifierOther').' <input type="text" size="3" maxlength="1" name="other_qualifier"  value="'.htmlentities($sOtherTextQualifier, ENT_QUOTES, 'UTF-8').'" onChange="DoPreview()"/>');
	$oPage->add('</p>');
	$oPage->add('</td><td style="vertical-align:top;background:#E8F3CF">');
	$oPage->add('<h3>'.Dict::S('UI:CSVImport:CommentsAndHeader').'</h3>');
	$oPage->add('<p><input type="checkbox" name="header_line" id="box_header" value="1" onChange="DoPreview()"'.IsChecked($bHeaderLine, 1).'/> '.Dict::S('UI:CSVImport:TreatFirstLineAsHeader').'<p>');
	$oPage->add('<p><input type="checkbox" name="box_skiplines" value="1" id="box_skiplines" onChange="DoPreview()"'.IsChecked($bBoxSkipLines, 1).'/> '.Dict::Format('UI:CSVImport:Skip_N_LinesAtTheBeginning', '<input type="text" size=2 name="nb_skipped_lines" id="nb_skipped_lines" onChange="DoPreview()" value="'.$iSkippedLines.'">').'<p>');
	$oPage->add('</td></tr></table>');
	$oPage->add('<input type="hidden" name="csvdata_truncated" id="csvdata_truncated" value="'.htmlentities($sCSVDataTruncated, ENT_QUOTES, 'UTF-8').'"/>');
	$oPage->add('<input type="hidden" name="csvdata" id="csvdata" value="'.htmlentities($sCSVData, ENT_QUOTES, 'UTF-8').'"/>');
	$oPage->add('<input type="hidden" name="class_name" value="'.$sClassName.'"/>');
	$oPage->add('<input type="hidden" name="advanced" value="'.$bAdvanced.'"/>');
	$oPage->add('<input type="hidden" name="step" value="3"/>');
	$oPage->add('<div id="preview">');
	$oPage->add('<p style="text-align:center">'.Dict::S('UI:CSVImport:CSVDataPreview').'</p>');
	$oPage->add('</div>');
	$oPage->add('<input type="button" value="'.Dict::S('UI:Button:Back').'" onClick="GoBack()"/>');
	$oPage->add('<input type="submit" value="'.Dict::S('UI:Button:Next').'"/>');
	$oPage->add('</form>');
	$oPage->add('</div>');
	
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
		var nb_lines_skipped = 0;
		if ($('#box_skiplines:checked').val() != null)
		{
			nb_lines_skipped = $('#nb_skipped_lines').val();
		}
		var header_line = 0;
		if ($('#box_header:checked').val() != null)
		{
			header_line = 1;
		}

		$('#preview').block();
		
		// Make sure that we cancel any pending request before issuing another
		// since responses may arrive in arbitrary order
		if (ajax_request != null)
		{
			ajax_request.abort();
			ajax_request = null;
		}
		
		ajax_request = $.post('ajax.csvimport.php',
			   { operation: 'parser_preview', enctype: 'multipart/form-data', csvdata: $("#csvdata_truncated").val(), separator: separator, qualifier: text_qualifier, nb_lines_skipped: nb_lines_skipped, header_line: header_line },
			   function(data) {
				 $('#preview').empty();
				 $('#preview').append(data);
				 $('#preview').unblock();
				}
			 );
	}
EOF
);
	$oPage->add_ready_script('DoPreview();');
}

/**
 *  Prompt for the data to be loaded (either via a file or a copy/paste)
 * @param WebPage $oPage The current web page
 * @return void
 */
function Welcome(iTopWebPage $oPage)
{
	$oPage->add("<div><p><h1>".Dict::S('UI:Title:BulkImport+')."</h1></p></div>\n");
	$oPage->AddTabContainer('tabs1');	

	$sFileLoadHtml = '<div><form enctype="multipart/form-data" method="post"><p>'.Dict::S('UI:CSVImport:SelectFile').'</p>'.
			'<p><input type="file" name="csvdata"/></p>'.
			'<p><input type="submit" value="'.Dict::S('UI:Button:Next').'"/></p>'.
			'<p><input type="hidden" name="step" value="2"/></p>'.
			'<p><input type="hidden" name="operation" value="file_upload"/></p>'.
			'</form></div>';
	
	$oPage->AddToTab('tabs1', Dict::S('UI:CSVImport:Tab:LoadFromFile'), $sFileLoadHtml);	
	$sCSVData = utils::ReadParam('csvdata', '');
	$sSeparator = utils::ReadParam('separator', '');
	$sTextQualifier = utils::ReadParam('text_qualifier', '');
	$bHeaderLine = utils::ReadParam('header_line', true);
	$iSkippedLines = utils::ReadParam('nb_skipped_lines', '');
	$sClassName = utils::ReadParam('class_name', '');
	$bAdvanced = utils::ReadParam('advanced', 0);
	$sCSVData = utils::ReadParam('csvdata', '');
	$sPasteDataHtml = '<div><form enctype="multipart/form-data" method="post"><p>'.Dict::S('UI:CSVImport:PasteData').'</p>'.
			'<p><textarea cols="100" rows="30" name="csvdata">'.htmlentities($sCSVData, ENT_QUOTES, 'UTF-8').'</textarea></p>'.
			'<p><input type="submit" value="'.Dict::S('UI:Button:Next').'"/></p>'.
			'<input type="hidden" name="step" value="2"/>'.
			'<input type="hidden" name="operation" value="csv_data"/>'.
			'<input type="hidden" name="separator" value="'.htmlentities($sSeparator, ENT_QUOTES, 'UTF-8').'"/>'.
			'<input type="hidden" name="text_qualifier" value="'.htmlentities($sTextQualifier, ENT_QUOTES, 'UTF-8').'"/>'.
			'<input type="hidden" name="header_line" value="'.$bHeaderLine.'"/>'.
			'<input type="hidden" name="nb_skipped_lines" value="'.$iSkippedLines.'"/>'.
			'<input type="hidden" name="class_name" value="'.$sClassName.'"/>'.
			'<input type="hidden" name="advanced" value="'.$bAdvanced.'"/>'.
			'</form></div>';
			
	$oPage->AddToTab('tabs1', Dict::S('UI:CSVImport:Tab:CopyPaste'), $sPasteDataHtml);		
	$sTemplateHtml = '<div><p>'.Dict::S('UI:CSVImport:PickClassForTemplate').' ';
	$sTemplateHtml .= GetClassesSelect('template_class', '', 300, UR_ACTION_BULK_MODIFY);
	$sTemplateHtml .= '</div>';
	$sTemplateHtml .= '<div id="template" style="text-align:center">';
	$sTemplateHtml .= '</div>';

	$oPage->AddToTab('tabs1', Dict::S('UI:CSVImport:Tab:Templates'), $sTemplateHtml);		
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
	
	ajax_request = $.get('ajax.csvimport.php',
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
}
		
switch($iStep)
{
	case 5:
		LoadData($oPage, $oContext);
		break;
		
	case 4:
		Preview($oPage, $oContext);
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
?>
