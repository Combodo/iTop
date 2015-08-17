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
 * CSV Import Page
 * Wizard to import CSV (or TSV) data into the database
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
try
{
	ini_set('memory_limit', '256M');
	require_once('../approot.inc.php');
	require_once(APPROOT.'/application/application.inc.php');
	require_once(APPROOT.'/application/itopwebpage.class.inc.php');
	require_once(APPROOT.'/application/ajaxwebpage.class.inc.php');
	
	require_once(APPROOT.'/application/startup.inc.php');
	
	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	LoginWebPage::DoLogin(); // Check user rights and prompt if needed
	
	$iStep = utils::ReadParam('step', 1);
	
	$oPage = new iTopWebPage(Dict::S('UI:Title:BulkImport'));
	
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
		$aClassCategories = array('bizmodel');
		if (UserRights::IsAdministrator())
		{
			$aClassCategories = array('bizmodel', 'application', 'addon/authentication');
		}
		foreach($aClassCategories as $sClassCategory)
		{
			foreach(MetaModel::GetClasses($sClassCategory) as $sClassName)
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
	 * Display a banner for the special "synchro" mode
	 * @param WebPage $oP The Page for the output
	 * @param string $sClass The class of objects to synchronize
	 * @param integer $iCount The number of objects to synchronize
	 * @return none
	 */
	 function DisplaySynchroBanner(WebPage $oP, $sClass, $iCount)
	 {
		$oP->add("<div class=\"notification\"><p><h1>".MetaModel::GetClassIcon($sClass)."&nbsp;".Dict::Format('UI:Title:BulkSynchro_nbItem_ofClass_class', $iCount, MetaModel::GetName($sClass))."</h1></p></div>\n");
	 }
	 
	/**
	 * Process the CSV data, for real or as a simulation
	 * @param WebPage $oPage The page used to display the wizard
	 * @param bool $bSimulate Whether or not to simulate the data load
	 * @return array The CSV lines in error that were rejected from the load (with the header line - if any) or null
	 */
	function ProcessCSVData(WebPage $oPage, $bSimulate = true)
	{
		$aResult = array();
		$sCSVData = utils::ReadParam('csvdata', '', false, 'raw_data');
		$sCSVDataTruncated = utils::ReadParam('csvdata_truncated', '', false, 'raw_data');
		$sSeparator = utils::ReadParam('separator', ',', false, 'raw_data');
		$sTextQualifier = utils::ReadParam('text_qualifier', '"', false, 'raw_data');
		$bHeaderLine = (utils::ReadParam('header_line', '0') == 1);
		$iSkippedLines = 0;
		if (utils::ReadParam('box_skiplines', '0') == 1)
		{
			$iSkippedLines = utils::ReadParam('nb_skipped_lines', '0');
		}
		$sClassName = utils::ReadParam('class_name', '', false, 'class');
		$aFieldsMapping = utils::ReadParam('field', array(), false, 'raw_data');
		$aSearchFields = utils::ReadParam('search_field', array(), false, 'field_name');
		$iCurrentStep = $bSimulate ? 4 : 5;
		$bAdvanced = utils::ReadParam('advanced', 0);
		$sEncoding = utils::ReadParam('encoding', 'UTF-8');
		$sSynchroScope = utils::ReadParam('synchro_scope', '', false, 'raw_data');
		if (!empty($sSynchroScope))
		{
			$oSearch = DBObjectSearch::FromOQL($sSynchroScope);
			$sClassName = $oSearch->GetClass(); // If a synchronization scope is set, then the class is fixed !
			$oSet = new DBObjectSet($oSearch);
			$iCount = $oSet->Count();
			DisplaySynchroBanner($oPage, $sClassName, $iCount);
			$sClassesSelect = "<select id=\"select_class_name\" name=\"class_name\"><option value=\"$sClassName\" selected>".MetaModel::GetName($sClassName)."</option>";
			$aSynchroUpdate = utils::ReadParam('synchro_update', array());
		}
		else
		{
			$sSynchroScope  = '';
			$aSynchroUpdate = null;
		}
				
		// Parse the data set
		$oCSVParser = new CSVParser($sCSVData, $sSeparator, $sTextQualifier);
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
			CMDBObject::SetTrackOrigin('csv-interactive');
			
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
			null, // date format
			true // localize		
		);
		$oBulk->SetReportHtml();

		$oPage->add('<input type="hidden" name="csvdata_truncated" id="csvdata_truncated" value="'.htmlentities($sCSVDataTruncated, ENT_QUOTES, 'UTF-8').'"/>');
		$aRes = $oBulk->Process($oMyChange);

		$sHtml = '<table id="bulk_preview" style="border-collapse: collapse;">';
		$sHtml .= '<tr><th style="padding:2px;border-right: 2px #fff solid;">Line</th>';
		$sHtml .= '<th style="padding:2px;border-right: 2px #fff solid;">Status</th>';
		$sHtml .= '<th style="padding:2px;border-right: 2px #fff solid;">Object</th>';
		foreach($aFieldsMapping as $iNumber => $sAttCode)
		{
			if (!empty($sAttCode) && ($sAttCode != ':none:') && ($sAttCode != 'finalclass'))
			{
				$sHtml .= "<th style=\"padding:2px;border-right: 2px #fff solid;\">".MetaModel::GetLabel($sClassName, $sAttCode)."</th>";
			}
		}
		$sHtml .= '<th>Message</th>';
		$sHtml .= '</tr>';
		
		$iErrors = 0;
		$iCreated = 0;
		$iModified = 0;
		$iUnchanged = 0;
		
		foreach($aRes as $iLine => $aResRow)
		{
			$oStatus = $aResRow['__STATUS__'];
			$sUrl = '';
			$sMessage = '';
			$sCSSRowClass = '';
			$sCSSMessageClass = 'cell_ok';
			switch(get_class($oStatus))
			{
				case 'RowStatus_NoChange':
				$iUnchanged++;
				$sFinalClass = $aResRow['finalclass'];
				$oObj = MetaModel::GetObject($sFinalClass, $aResRow['id']->GetPureValue());
				$sUrl = $oObj->GetHyperlink();
				$sStatus = '<img src="../images/unchanged.png" title="'.Dict::S('UI:CSVReport-Icon-Unchanged').'">';
				$sCSSRowClass = 'row_unchanged';
				break;
						
				case 'RowStatus_Modify':
				$iModified++;
				$sFinalClass = $aResRow['finalclass'];
				$oObj = MetaModel::GetObject($sFinalClass, $aResRow['id']->GetPureValue());
				$sUrl = $oObj->GetHyperlink();
				$sStatus = '<img src="../images/modified.png" title="'.Dict::S('UI:CSVReport-Icon-Modified').'">';
				$sCSSRowClass = 'row_modified';
				break;
						
				case 'RowStatus_Disappeared':
				$iModified++;
				$sFinalClass = $aResRow['finalclass'];
				$oObj = MetaModel::GetObject($sFinalClass, $aResRow['id']->GetPureValue());
				$sUrl = $oObj->GetHyperlink();
				$sStatus = '<img src="../images/delete.png" title="'.Dict::S('UI:CSVReport-Icon-Missing').'">';
				$sCSSRowClass = 'row_modified';
				if ($bSimulate)
				{
					$sMessage = Dict::S('UI:CSVReport-Object-MissingToUpdate');				
				}
				else
				{
					$sMessage = Dict::S('UI:CSVReport-Object-MissingUpdated');
				}
				break;
						
				case 'RowStatus_NewObj':
				$iCreated++;
				$sFinalClass = $aResRow['finalclass'];
				$sStatus = '<img src="../images/added.png" title="'.Dict::S('UI:CSVReport-Icon-Created').'">';
				$sCSSRowClass = 'row_added';
				if ($bSimulate)
				{
					$sMessage = Dict::S('UI:CSVReport-Object-ToCreate');				
				}
				else
				{
					$sFinalClass = $aResRow['finalclass'];
					$oObj = MetaModel::GetObject($sFinalClass, $aResRow['id']->GetPureValue());
					$sUrl = $oObj->GetHyperlink();
					$sMessage = Dict::S('UI:CSVReport-Object-Created');
				}
				break;
						
				case 'RowStatus_Issue':
				$iErrors++;
				$sMessage .= $oPage->GetP($oStatus->GetDescription());
				$sStatus = '<img src="../images/error.png" title="'.Dict::S('UI:CSVReport-Icon-Error').'">';//translate
				$sCSSMessageClass = 'cell_error';
				$sCSSRowClass = 'row_error';
				if (array_key_exists($iLine, $aData))
				{
					$aRow = $aData[$iLine];
					$aResult[] = $sTextQualifier.implode($sTextQualifier.$sSeparator.$sTextQualifier,$aRow).$sTextQualifier; // Remove the first line and store it in case of error
				}
				break;		
			}
			$sHtml .= '<tr class="'.$sCSSRowClass.'">';
			$sHtml .= "<td style=\"background-color:#f1f1f1;border-right:2px #fff solid;\">".sprintf("%0{$sMaxLen}d", 1+$iLine+$iRealSkippedLines)."</td>";
			$sHtml .= "<td style=\"text-align:center;background-color:#f1f1f1;border-right:2px #fff solid;\">$sStatus</td>";
			$sHtml .= "<td style=\"text-align:center;background-color:#f1f1f1;\">$sUrl</td>";
			foreach($aFieldsMapping as $iNumber => $sAttCode)
			{
				if (!empty($sAttCode) && ($sAttCode != ':none:') && ($sAttCode != 'finalclass'))
				{
					$oCellStatus = $aResRow[$iNumber -1];
					$sCellMessage = '';
					if (isset($aExternalKeysByColumn[$iNumber -1]))
					{
						$sExtKeyName = $aExternalKeysByColumn[$iNumber -1];
						$oExtKeyCellStatus = $aResRow[$sExtKeyName];
						switch(get_class($oExtKeyCellStatus))
						{
							case 'CellStatus_Issue':
							case 'CellStatus_SearchIssue':
							case 'CellStatus_NullIssue':
							$sCellMessage .= $oPage->GetP($oExtKeyCellStatus->GetDescription());
							break;
							
							case 'CellStatus_Ambiguous':
							$sCellMessage .= $oPage->GetP($oExtKeyCellStatus->GetDescription());
							break;
							
							default:
							// Do nothing
						}
					}
					$sHtmlValue = $oCellStatus->GetDisplayableValue();
					switch(get_class($oCellStatus))
					{
						case 'CellStatus_Issue':
						$sCellMessage .= $oPage->GetP($oCellStatus->GetDescription());
						$sHtml .= '<td class="cell_error" style="border-right:1px #eee solid;">'.Dict::Format('UI:CSVReport-Object-Error', $sHtmlValue).$sCellMessage.'</td>';
						break;
						
						case 'CellStatus_SearchIssue':
						$sCellMessage .= $oPage->GetP($oCellStatus->GetDescription());
						$sHtml .= '<td class="cell_error">ERROR: '.$sHtmlValue.$sCellMessage.'</td>';
						break;
						
						case 'CellStatus_Ambiguous':
						$sCellMessage .= $oPage->GetP($oCellStatus->GetDescription());
						$sHtml .= '<td class="cell_error" style="border-right:1px #eee solid;">'.Dict::Format('UI:CSVReport-Object-Ambiguous', $sHtmlValue).$sCellMessage.'</td>';
						break;
						
						case 'CellStatus_Modify':
						$sHtml .= '<td class="cell_modified" style="border-right:1px #eee solid;"><b>'.$sHtmlValue.'</b></td>';
						break;
						
						default:
						$sHtml .= '<td class="cell_ok" style="border-right:1px #eee solid;">'.$sHtmlValue.$sCellMessage.'</td>';
					}
				}
			}
			$sHtml .= "<td class=\"$sCSSMessageClass\" style=\"background-color:#f1f1f1;\">$sMessage</td>";
			$sHtml .= '</tr>';
		}
		
		$iUnchanged = count($aRes) - $iErrors - $iModified - $iCreated;
		$sHtml .= '</table>';
		$oPage->add('<div class="wizContainer" style="width:auto;display:inline-block;">');
		$oPage->add('<form enctype="multipart/form-data" id="wizForm" method="post">');
		$oPage->add('<input type="hidden" name="step" value="'.($iCurrentStep+1).'"/>');
		$oPage->add('<input type="hidden" name="separator" value="'.htmlentities($sSeparator, ENT_QUOTES, 'UTF-8').'"/>');
		$oPage->add('<input type="hidden" name="text_qualifier" value="'.htmlentities($sTextQualifier, ENT_QUOTES, 'UTF-8').'"/>');
		$oPage->add('<input type="hidden" name="header_line" value="'.$bHeaderLine.'"/>');
		$oPage->add('<input type="hidden" name="nb_skipped_lines" value="'.utils::ReadParam('nb_skipped_lines', '0').'"/>');
		$oPage->add('<input type="hidden" name="box_skiplines" value="'.utils::ReadParam('box_skiplines', '0').'"/>');
		$oPage->add('<input type="hidden" name="csvdata" value="'.htmlentities($sCSVData, ENT_QUOTES, 'UTF-8').'"/>');
		$oPage->add('<input type="hidden" name="csvdata_truncated" value="'.htmlentities($sCSVDataTruncated, ENT_QUOTES, 'UTF-8').'"/>');
		$oPage->add('<input type="hidden" name="class_name" value="'.$sClassName.'"/>');
		$oPage->add('<input type="hidden" name="advanced" value="'.$bAdvanced.'"/>');
		$oPage->add('<input type="hidden" name="encoding" value="'.$sEncoding.'"/>');
		$oPage->add('<input type="hidden" name="synchro_scope" value="'.$sSynchroScope.'"/>');
		if (!empty($sSynchroScope))
		{
			foreach($aSynchroUpdate as $sKey => $value)
			{
				$oPage->add('<input type="hidden" name="synchro_update['.$sKey.']" value="'.$value.'"/>');				
			}
		}
		foreach($aFieldsMapping as $iNumber => $sAttCode)
		{
			$oPage->add('<input type="hidden" name="field['.$iNumber.']" value="'.$sAttCode.'"/>');
		}
		foreach($aSearchFields as $index => $sDummy)
		{
			$oPage->add('<input type="hidden" name="search_field['.$index.']" value="1"/>');
		}
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
		$oPage->add('<div class="white" style="display:inline-block">');
		$oPage->add($sHtml);
		$oPage->add('</div> <!-- end of preview -->');
		$oPage->add('<p>');
		if($bSimulate)
		{
			$oPage->add('<input type="button" value="'.Dict::S('UI:Button:Restart').'" onClick="CSVRestart()"/>&nbsp;&nbsp;');
		}
		$oPage->add('<input type="button" value="'.Dict::S('UI:Button:Back').'" onClick="CSVGoBack()"/>&nbsp;&nbsp;');

		$bShouldConfirm = false;
		if ($bSimulate)
		{
			// if there are *too many* changes, we should ask the user for a confirmation
			if (count($aRes) >= MetaModel::GetConfig()->Get('csv_import_min_object_confirmation'))
			{
				$fErrorsPercentage = (100.0*$iErrors)/count($aRes);
				if ($fErrorsPercentage >= MetaModel::GetConfig()->Get('csv_import_errors_percentage'))
				{
					$sMessage = Dict::Format('UI:CSVReport-Stats-Errors', $fErrorsPercentage);
					$bShouldConfirm = true;
				}
				$fCreatedPercentage = (100.0*$iCreated)/count($aRes);
				if ($fCreatedPercentage >= MetaModel::GetConfig()->Get('csv_import_creations_percentage'))
				{
					$sMessage = Dict::Format('UI:CSVReport-Stats-Created', $fCreatedPercentage);
					$bShouldConfirm = true;
				}
				$fModifiedPercentage = (100.0*$iModified)/count($aRes);
				if ($fModifiedPercentage >= MetaModel::GetConfig()->Get('csv_import_modifications_percentage'))
				{
					$sMessage = Dict::Format('UI:CSVReport-Stats-Modified', $fModifiedPercentage);
					$bShouldConfirm = true;
				}
				
			}
			$iCount = count($aRes);
			//$oPage->add('<input type="submit" value="'.Dict::S('UI:Button:DoImport').'" onClick="$(\'#wizForm\').block();"/></p>');
			$sConfirm = $bShouldConfirm ? 'true' : 'false';
			$oPage->add('<input type="button" value="'.Dict::S('UI:Button:DoImport')."\" onClick=\"return DoSubmit($sConfirm);\"/></p>");
		}
		else
		{
			$oPage->add('<input type="submit" value="'.Dict::S('UI:Button:Done').'"/></p>');
		}
		$oPage->add('</form>');
		$oPage->add('</div> <!-- end of wizForm -->');
		
		
		if ($bShouldConfirm)
		{
			$sYesButton = Dict::S('UI:Button:Ok');
			$sNoButton = Dict::S('UI:Button:Cancel');
			$oPage->add('<div id="dlg_confirmation" title="'.htmlentities(Dict::S('UI:CSVImportConfirmTitle'), ENT_QUOTES, 'UTF-8').'">');
			$oPage->add('<p style="text-align:center"><b>'.$sMessage.'</b></p>');
			$oPage->add('<p style="text-align:center">'.htmlentities(Dict::S('UI:CSVImportConfirmMessage'), ENT_QUOTES, 'UTF-8').'</p>');
			$oPage->add('<div id="confirmation_chart"></div>');
			$oPage->add('</div> <!-- end of dlg_confirmation -->');
			$oPage->add_ready_script(
<<<EOF
	$('#dlg_confirmation').dialog( 
		{
			height: 'auto',
			width: 500,
			modal:true, 
			autoOpen: false, 
			buttons:
			{
				'$sYesButton': RunImport,
				'$sNoButton': CancelImport 
			} 
		});
		swfobject.embedSWF(	"../images/open-flash-chart.swf", 
							"confirmation_chart", 
							"100%", "300","9.0.0",
							"expressInstall.swf",
							{}, 
							{'wmode': 'transparent'}
						);
EOF
);
		}
		
		$sErrors = addslashes(Dict::Format('UI:CSVImportError_items', $iErrors));
		$sCreated = addslashes(Dict::Format('UI:CSVImportCreated_items', $iCreated));
		$sModified = addslashes(Dict::Format('UI:CSVImportModified_items', $iModified));
		$sUnchanged = addslashes(Dict::Format('UI:CSVImportUnchanged_items', $iUnchanged));
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

function open_flash_chart_data()
{
	var iErrors = $iErrors;
	var iModified = $iModified;
	var iCreated = $iCreated;
	var iUnchanged = $iUnchanged;
	var fAlpha = 0.9;
	
	var oResult = {
		"elements": [
			{
				"type": "pie",
				"tip": "#label# (#percent#)",
				"gradient-fill": true,
				"font-size": 14,
				"colours":[],
				"values": [],
				"animate":[
			        {
			          "type": "fade"
			        }
		        ]
			}
		],
		"x_axis": null,
		"font-size": 14,
		"bg_colour": "#EEEEEE"
	};

	if (iErrors > 0)
	{
		var oErrors =
		{
			"value":  iErrors,
			"label": "$sErrors",
			"alpha": fAlpha,
			"label-colour": "#CC3333",
		};
		oResult.elements[0].values.push(oErrors);
		oResult.elements[0].colours.push('#FF6666');
	}
	if (iModified > 0)
	{
		var oModified =
		{
			"value":  iModified,
			"label": "$sModified",
			"alpha": fAlpha,
			"label-colour": "#3333CC",
		};
		oResult.elements[0].values.push(oModified);
		oResult.elements[0].colours.push('#6666FF');
	}
	if (iCreated > 0)
	{
		var oCreated =
		{
			"value":  iCreated,
			"label": "$sCreated",
			"alpha": fAlpha,
			"label-colour": "#33CC33",
			
		};
		oResult.elements[0].values.push(oCreated);
		oResult.elements[0].colours.push('#66FF66');
	}
	if (iUnchanged > 0)
	{
		var oUnchanged =
		{
			"value":  iUnchanged,
			"label": "$sUnchanged",
			"alpha": fAlpha,
			"label-colour": "#333333",
			
		};
		oResult.elements[0].values.push(oUnchanged);
		oResult.elements[0].colours.push('#666666');
	}

	return JSON.stringify(oResult);
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
		$oPage->add('<h2>'.Dict::S('UI:Title:CSVImportStep5').'</h2>');
		$aResult = ProcessCSVData($oPage, false /* simulate = false */);
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
	 * @return void
	 */
	function Preview(WebPage $oPage)
	{
		$oPage->add('<h2>'.Dict::S('UI:Title:CSVImportStep4').'</h2>');
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
	
		$sSynchroScope = utils::ReadParam('synchro_scope', '', false, 'raw_data');
		if (!empty($sSynchroScope))
		{
			$oSearch = DBObjectSearch::FromOQL($sSynchroScope);
			$sClassName = $oSearch->GetClass(); // If a synchronization scope is set, then the class is fixed !
			$oSet = new DBObjectSet($oSearch);
			$iCount = $oSet->Count();
			DisplaySynchroBanner($oPage, $sClassName, $iCount);
			$sClassesSelect = "<select id=\"select_class_name\" name=\"class_name\"><option value=\"$sClassName\" selected>".MetaModel::GetName($sClassName)."</option>";
			$aSynchroUpdate = utils::ReadParam('synchro_update', array());
		}
		else
		{
			$sClassesSelect = GetClassesSelect('class_name', $sClassName, 300, UR_ACTION_BULK_MODIFY);
		}

		$oPage->add('<h2>'.Dict::S('UI:Title:CSVImportStep3').'</h2>');
		$oPage->add('<div class="wizContainer">');
		$oPage->add('<form enctype="multipart/form-data" id="wizForm" method="post" onSubmit="return CheckValues()"><table style="width:100%" class="transparent"><tr><td>'.Dict::S('UI:CSVImport:SelectClass').' ');
		$oPage->add($sClassesSelect);
		$oPage->add('</td><td style="text-align:right"><input type="checkbox" name="advanced" value="1" '.IsChecked($bAdvanced, 1).' onClick="DoMapping()">&nbsp;'.Dict::S('UI:CSVImport:AdvancedMode').'</td></tr></table>');
		$oPage->add('<div style="padding:1em;display:none" id="advanced_help" style="display:none">'.Dict::S('UI:CSVImport:AdvancedMode+').'</div>');
		$oPage->add('<div id="mapping" class="white"><p style="text-align:center;width:100%;font-size:1.5em;padding:1em;">'.Dict::S('UI:CSVImport:SelectAClassFirst').'<br/></p></div>');
		$oPage->add('<input type="hidden" name="step" value="4"/>');
		$oPage->add('<input type="hidden" name="separator" value="'.htmlentities($sSeparator, ENT_QUOTES, 'UTF-8').'"/>');
		$oPage->add('<input type="hidden" name="text_qualifier" value="'.htmlentities($sTextQualifier, ENT_QUOTES, 'UTF-8').'"/>');
		$oPage->add('<input type="hidden" name="header_line" value="'.$bHeaderLine.'"/>');
		$oPage->add('<input type="hidden" name="nb_skipped_lines" value="'.utils::ReadParam('nb_skipped_lines', '0').'"/>');
		$oPage->add('<input type="hidden" name="box_skiplines" value="'.utils::ReadParam('box_skiplines', '0').'"/>');
		$oPage->add('<input type="hidden" name="csvdata_truncated" id="csvdata_truncated" value="'.htmlentities($sCSVDataTruncated, ENT_QUOTES, 'UTF-8').'"/>');
		$oPage->add('<input type="hidden" name="csvdata" value="'.htmlentities($sCSVData, ENT_QUOTES, 'UTF-8').'"/>');
		$oPage->add('<input type="hidden" name="encoding" value="'.$sEncoding.'">');
		$oPage->add('<input type="hidden" name="synchro_scope" value="'.$sSynchroScope.'">');
		if (!empty($sSynchroScope))
		{
			foreach($aSynchroUpdate as $sKey => $value)
			{
				$oPage->add('<input type="hidden" name="synchro_update['.$sKey.']" value="'.$value.'"/>');				
			}
		}
		$oPage->add('<p><input type="button" value="'.Dict::S('UI:Button:Restart').'" onClick="CSVRestart()"/>&nbsp;&nbsp;');
		$oPage->add('<input type="button" value="'.Dict::S('UI:Button:Back').'" onClick="CSVGoBack()"/>&nbsp;&nbsp;');
		$oPage->add('<input type="submit" value="'.Dict::S('UI:Button:SimulateImport').'"/></p>');
		$oPage->add('</form>');
		$oPage->add('</div>');
		
		$sAlertIncompleteMapping = addslashes(Dict::S('UI:CSVImport:AlertIncompleteMapping'));
		$sAlertMultipleMapping = addslashes(Dict::S('UI:CSVImport:AlertMultipleMapping'));
		$sAlertNoSearchCriteria = addslashes(Dict::S('UI:CSVImport:AlertNoSearchCriteria'));
		
		$oPage->add_ready_script(
<<<EOF
	$('#select_class_name').change( function(ev) { DoMapping(); } );
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
		
		$sSeparator = utils::ReadParam('separator', '', false, 'raw_data');
		if ($sSeparator == '') // May be set to an empty value by the previous page
		{
			$sSeparator = $aGuesses['separator'];	
		}
		$iSkippedLines = utils::ReadParam('nb_skipped_lines', '');
		$bBoxSkipLines = utils::ReadParam('box_skiplines', 0);
		if ($sSeparator == 'tab') $sSeparator = "\t";
		$sOtherSeparator = in_array($sSeparator, array(',', ';', "\t")) ? '' : $sSeparator;
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
		if ($iMaxLen > 0)
		{
			$iMaxLines = 20;
			$iCurPos = true;
			while ( ($iCurPos > 0) && ($iMaxLines > 0))
			{
				$pos = strpos($sUTF8Data, "\n", $iCurPos);
				if ($pos !== false)
				{
					$iCurPos = 1+$pos;
				}
				else
				{
					$iCurPos = strlen($sUTF8Data);
					$iMaxLines = 1;
				}
				$iMaxLines--;
			}
			$sCSVDataTruncated = substr($sUTF8Data, 0, $iCurPos);
		}
		else
		{
			$sCSVDataTruncated = '';
		}

		$sSynchroScope = utils::ReadParam('synchro_scope', '', false, 'raw_data');
		if (!empty($sSynchroScope))
		{
			$oSearch = DBObjectSearch::FromOQL($sSynchroScope);
			$sClassName = $oSearch->GetClass();
			$oSet = new DBObjectSet($oSearch);
			$iCount = $oSet->Count();
			DisplaySynchroBanner($oPage, $sClassName, $iCount);
			$aSynchroUpdate = utils::ReadParam('synchro_update', array());
		}
		
		$oPage->add('<h2>'.Dict::S('UI:Title:CSVImportStep2').'</h2>');
		$oPage->add('<div class="wizContainer">');
		$oPage->add('<table><tr><td style="vertical-align:top;padding-right:50px;">');
		$oPage->add('<form enctype="multipart/form-data" id="wizForm" method="post" id="csv_options">');
		$oPage->add('<h3>'.Dict::S('UI:CSVImport:SeparatorCharacter').'</h3>');
		$oPage->add('<p><input type="radio" name="separator" value="," onClick="DoPreview()"'.IsChecked($sSeparator, ',').'/> '.Dict::S('UI:CSVImport:SeparatorComma+').'<br/>');
		$oPage->add('<input type="radio" name="separator" value=";" onClick="DoPreview()"'.IsChecked($sSeparator, ';').'/> '.Dict::S('UI:CSVImport:SeparatorSemicolon+').'<br/>');
		$oPage->add('<input type="radio" name="separator" value="tab" onClick="DoPreview()"'.IsChecked($sSeparator, "\t").'/> '.Dict::S('UI:CSVImport:SeparatorTab+').'<br/>');
		$oPage->add('<input type="radio" name="separator" value="other"  onClick="DoPreview()"'.IsChecked($sOtherSeparator, '', true).'/> '.Dict::S('UI:CSVImport:SeparatorOther').' <input type="text" size="3" maxlength="1" name="other_separator" id="other_separator" value="'.$sOtherSeparator.'" onClick="DoPreview()"/>');
		$oPage->add('</p>');
		$oPage->add('</td><td style="vertical-align:top;padding-right:50px;">');
		$oPage->add('<h3>'.Dict::S('UI:CSVImport:TextQualifierCharacter').'</h3>');
		$oPage->add('<p><input type="radio" name="text_qualifier" value="&#34;" onClick="DoPreview()"'.IsChecked($sTextQualifier, '"').'/> '.Dict::S('UI:CSVImport:QualifierDoubleQuote+').'<br/>');
		$oPage->add('<input type="radio" name="text_qualifier" value="&#39;"  onClick="DoPreview()"'.IsChecked($sTextQualifier, "'").'/> '.Dict::S('UI:CSVImport:QualifierSimpleQuote+').'<br/>');
		$oPage->add('<input type="radio" name="text_qualifier" value="other"  onClick="DoPreview()"'.IsChecked($sOtherTextQualifier, '', true).'/> '.Dict::S('UI:CSVImport:QualifierOther').' <input type="text" size="3" maxlength="1" name="other_qualifier"  value="'.htmlentities($sOtherTextQualifier, ENT_QUOTES, 'UTF-8').'" onChange="DoPreview()"/>');
		$oPage->add('</p>');
		$oPage->add('</td><td style="vertical-align:top;">');
		$oPage->add('<h3>'.Dict::S('UI:CSVImport:CommentsAndHeader').'</h3>');
		$oPage->add('<p><input type="checkbox" name="header_line" id="box_header" value="1" onClick="DoPreview()"'.IsChecked($bHeaderLine, 1).'/> '.Dict::S('UI:CSVImport:TreatFirstLineAsHeader').'<p>');
		$oPage->add('<p><input type="checkbox" name="box_skiplines" value="1" id="box_skiplines" onClick="DoPreview()"'.IsChecked($bBoxSkipLines, 1).'/> '.Dict::Format('UI:CSVImport:Skip_N_LinesAtTheBeginning', '<input type="text" size=2 name="nb_skipped_lines" id="nb_skipped_lines" onChange="DoPreview()" value="'.$iSkippedLines.'">').'<p>');
		$oPage->add('</td></tr></table>');
		$oPage->add('<input type="hidden" name="csvdata_truncated" id="csvdata_truncated" value="'.htmlentities($sCSVDataTruncated, ENT_QUOTES, 'UTF-8').'"/>');
		$oPage->add('<input type="hidden" name="csvdata" id="csvdata" value="'.htmlentities($sUTF8Data, ENT_QUOTES, 'UTF-8').'"/>');
		// The encoding has changed, keep that information within the wizard
		$oPage->add('<input type="hidden" name="encoding" value="UTF-8">');
		$oPage->add('<input type="hidden" name="class_name" value="'.$sClassName.'"/>');
		$oPage->add('<input type="hidden" name="advanced" value="'.$bAdvanced.'"/>');
		$oPage->add('<input type="hidden" name="synchro_scope" value="'.$sSynchroScope.'"/>');
		foreach($aFieldsMapping as $iNumber => $sAttCode)
		{
			$oPage->add('<input type="hidden" name="field['.$iNumber.']" value="'.$sAttCode.'"/>');
		}
		foreach($aSearchFields as $index => $sDummy)
		{
			$oPage->add('<input type="hidden" name="search_field['.$index.']" value="1"/>');
		}
		$oPage->add('<input type="hidden" name="step" value="3"/>');
		if (!empty($sSynchroScope))
		{
			foreach($aSynchroUpdate as $sKey => $value)
			{
				$oPage->add('<input type="hidden" name="synchro_update['.$sKey.']" value="'.$value.'"/>');				
			}
		}
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
		$oPage->add_ready_script('DoPreview();');
	}

	/**
	 *  Prompt for the data to be loaded (either via a file or a copy/paste)
	 * @param WebPage $oPage The current web page
	 * @return void
	 */
	function Welcome(iTopWebPage $oPage)
	{
		$sSynchroScope = utils::ReadParam('synchro_scope', '', false, 'raw_data');
		if (!empty($sSynchroScope))
		{
			$oSearch = DBObjectSearch::FromOQL($sSynchroScope);
			$sClassName = $oSearch->GetClass();
			$oSet = new DBObjectSet($oSearch);
			$iCount = $oSet->Count();
			DisplaySynchroBanner($oPage, $sClassName, $iCount);
			$aSynchroUpdate = utils::ReadParam('synchro_update', array());
		}
		else
		{
			$aSynchroUpdate = null;
		}
		
		$oPage->add("<div><p><h1>".Dict::S('UI:Title:BulkImport+')."</h1></p></div>\n");
		$oPage->AddTabContainer('tabs1');	
	
		$sSeparator = utils::ReadParam('separator', '', false, 'raw_data');
		$sTextQualifier = utils::ReadParam('text_qualifier', '', false, 'raw_data');
		$bHeaderLine = utils::ReadParam('header_line', true);
		$sClassName = utils::ReadParam('class_name', '');
		$bAdvanced = utils::ReadParam('advanced', 0);
		$sEncoding = utils::ReadParam('encoding', '');
		if ($sEncoding == '')
		{
			$sEncoding = MetaModel::GetConfig()->Get('csv_file_default_charset');
		}
		$aFieldsMapping = utils::ReadParam('field', array(), false, 'raw_data');
		$aSearchFields = utils::ReadParam('search_field', array(), false, 'field_name');

		$sFileLoadHtml = '<div><form enctype="multipart/form-data" method="post"><p>'.Dict::S('UI:CSVImport:SelectFile').'</p>'.
				'<p><input type="file" name="csvdata"/></p>';
				
		$sFileLoadHtml .= '<p>'.Dict::S('UI:CSVImport:Encoding').': ';
		$sFileLoadHtml .= '<select name="encoding" style="font-family:Arial,Helvetica,Sans-serif">'; // IE 8 has some troubles if the font is different
		$aPossibleEncodings = utils::GetPossibleEncodings(MetaModel::GetConfig()->GetCSVImportCharsets());
		foreach($aPossibleEncodings as $sIconvCode => $sDisplayName )
		{
			$sSelected  = '';
			if ($sEncoding == $sIconvCode)
			{
				$sSelected = ' selected';
			}
			$sFileLoadHtml .= '<option value="'.$sIconvCode.'"'.$sSelected.'>'.$sDisplayName.'</option>';
		}
		$sFileLoadHtml .= '</select></p>';
		$sFileLoadHtml .= '<p><input type="submit" value="'.Dict::S('UI:Button:Next').'"/></p>'.
				'<input type="hidden" name="step" value="2"/>'.
				'<input type="hidden" name="operation" value="file_upload"/>'.
				'<input type="hidden" name="header_line" value="'.$bHeaderLine.'"/>'.
				'<input type="hidden" name="nb_skipped_lines" value="'.utils::ReadParam('nb_skipped_lines', '0').'"/>'.
				'<input type="hidden" name="box_skiplines" value="'.utils::ReadParam('box_skiplines', '0').'"/>'.
				'<input type="hidden" name="class_name" value="'.$sClassName.'"/>'.
				'<input type="hidden" name="advanced" value="'.$bAdvanced.'"/>'.
				'<input type="hidden" name="synchro_scope" value="'.$sSynchroScope.'"/>';
		if (!empty($sSynchroScope))
		{
			foreach($aSynchroUpdate as $sKey => $value)
			{
				$sFileLoadHtml .= '<input type="hidden" name="synchro_update['.$sKey.']" value="'.$value.'"/>';				
			}
		}
		foreach($aFieldsMapping as $iNumber => $sAttCode)
		{
			$oPage->add('<input type="hidden" name="field['.$iNumber.']" value="'.$sAttCode.'"/>');
		}
		foreach($aSearchFields as $index => $sDummy)
		{
			$oPage->add('<input type="hidden" name="search_field['.$index.']" value="1"/>');
		}

		$sFileLoadHtml .= '</form></div>';
		
		$oPage->AddToTab('tabs1', Dict::S('UI:CSVImport:Tab:LoadFromFile'), $sFileLoadHtml);	
		$sCSVData = utils::ReadParam('csvdata', '', false, 'raw_data');
		$sPasteDataHtml = '<div><form enctype="multipart/form-data" method="post"><p>'.Dict::S('UI:CSVImport:PasteData').'</p>'.
						  '<p><textarea cols="100" rows="30" name="csvdata">'.htmlentities($sCSVData, ENT_QUOTES, 'UTF-8').'</textarea></p>';
		$sPasteDataHtml .= '<hidden name="encoding" value="UTF-8">';
		$sPasteDataHtml .=
				'<p><input type="submit" value="'.Dict::S('UI:Button:Next').'"/></p>'.
				'<input type="hidden" name="step" value="2"/>'.
				'<input type="hidden" name="operation" value="csv_data"/>'.
				'<input type="hidden" name="separator" value="'.htmlentities($sSeparator, ENT_QUOTES, 'UTF-8').'"/>'.
				'<input type="hidden" name="text_qualifier" value="'.htmlentities($sTextQualifier, ENT_QUOTES, 'UTF-8').'"/>'.
				'<input type="hidden" name="header_line" value="'.$bHeaderLine.'"/>'.
				'<input type="hidden" name="nb_skipped_lines" value="'.utils::ReadParam('nb_skipped_lines', '0').'"/>'.
				'<input type="hidden" name="box_skiplines" value="'.utils::ReadParam('box_skiplines', '0').'"/>'.
				'<input type="hidden" name="class_name" value="'.$sClassName.'"/>'.
				'<input type="hidden" name="advanced" value="'.$bAdvanced.'"/>'.
				'<input type="hidden" name="synchro_scope" value="'.$sSynchroScope.'"/>';
		if (!empty($sSynchroScope))
		{
			foreach($aSynchroUpdate as $sKey => $value)
			{
				$sPasteDataHtml .= '<input type="hidden" name="synchro_update['.$sKey.']" value="'.$value.'"/>';				
			}
		}
		foreach($aFieldsMapping as $iNumber => $sAttCode)
		{
			$sPasteDataHtml .= '<input type="hidden" name="field['.$iNumber.']" value="'.$sAttCode.'"/>';
		}
		foreach($aSearchFields as $index => $sDummy)
		{
			$sPasteDataHtml .= '<input type="hidden" name="search_field['.$index.']" value="1"/>';
		}
		$sPasteDataHtml .= '</form></div>';
				
		$oPage->AddToTab('tabs1', Dict::S('UI:CSVImport:Tab:CopyPaste'), $sPasteDataHtml);		
		if (!empty($sCSVData))
		{
			// When there are some data, activate the 'copy & paste' tab by default
			$oPage->SelectTab('tabs1', Dict::S('UI:CSVImport:Tab:CopyPaste'));
		}
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
			$oPage->AddAjaxTab(Dict::S('UI:History:BulkImports'), utils::GetAbsoluteUrlAppRoot().'pages/csvimport.php?step=11', true /* bCache */);
		}
	}
			
	switch($iStep)
	{
		case 11:
			// Asynchronous tab
			$oPage = new ajax_page('');
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
	$oP = new SetupPage(Dict::S('UI:PageTitle:FatalError'));
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
	$oP = new SetupPage(Dict::S('UI:PageTitle:FatalError'));
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
?>
