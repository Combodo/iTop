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
 * Specific to the interactive csv import
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/webpage.class.inc.php');
require_once(APPROOT.'/application/ajaxwebpage.class.inc.php');
require_once(APPROOT.'/application/wizardhelper.class.inc.php');
require_once(APPROOT.'/application/ui.linkswidget.class.inc.php');
require_once(APPROOT.'/application/csvpage.class.inc.php');

/**
 * Determines if the name of the field to be mapped correspond
 * to the name of an external key or an Id of the given class
 * @param string $sClassName The name of the class
 * @param string $sFieldCode The attribute code of the field , or empty if no match
 * @return bool true if the field corresponds to an id/External key, false otherwise
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
 * @param string $sExtKeyAttCode Attribute code of the external key
 * @param AttributeDefinition $oExtKeyAttDef Attribute definition of the external key
 * @param bool $bAdvanced True if advanced mode
 * @return Ash List of codes=>display name: xxx->yyy where yyy are the reconciliation keys for the object xxx 
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
 * @param string $sClassName Name of the class used for the mapping
 * @param string $sFieldName Name of the field, as it comes from the data file (header line)
 * @param integer $iFieldIndex Number of the field in the sequence
 * @param bool $bAdvancedMode Whether or not advanced mode was chosen
 * @param string $sDefaultChoice If set, this will be the item selected by default
 * @return string The HTML code corresponding to the drop-down list for this field
 */
function GetMappingForField($sClassName, $sFieldName, $iFieldIndex, $bAdvancedMode, $sDefaultChoice)
{
	$aChoices = array('' => Dict::S('UI:CSVImport:MappingSelectOne'));
	$aChoices[':none:'] = Dict::S('UI:CSVImport:MappingNotApplicable');
	$sFieldCode = ''; // Code of the attribute, if there is a match
	$aMatches  = array();
	if (preg_match('/^(.+)\*$/', $sFieldName, $aMatches))
	{
		// Remove any trailing "star" character.
		// A star character at the end can be used to indicate a mandatory field
		$sFieldName = $aMatches[1];
	}
	else if (preg_match('/^(.+)\*->(.+)$/', $sFieldName, $aMatches))
	{
		// Remove any trailing "star" character before the arrow (->)
		// A star character at the end can be used to indicate a mandatory field
		$sFieldName = $aMatches[1].'->'.$aMatches[2];
	}
	if (($sFieldName == 'id') || ($sFieldName == Dict::S('UI:CSVImport:idField')))
	{
		$sFieldCode = 'id';
	}
	if ($bAdvancedMode)
	{
		$aChoices['id'] = Dict::S('UI:CSVImport:idField');
	}
	foreach(MetaModel::ListAttributeDefs($sClassName) as $sAttCode => $oAttDef)
	{
		$sStar = '';
		if ($oAttDef->IsExternalKey())
		{
			if (($sFieldName == $oAttDef->GetLabel()) || ($sFieldName == $sAttCode))
			{
				$sFieldCode = $sAttCode;
			}
			if ($bAdvancedMode)
			{
				$aChoices[$sAttCode] = $oAttDef->GetLabel();
			}
			$oExtKeyAttDef = MetaModel::GetAttributeDef($sClassName, $oAttDef->GetKeyAttCode());
			if (!$oExtKeyAttDef->IsNullAllowed())
			{
				$sStar = '*';
			}
			// Get fields of the external class that are considered as reconciliation keys
			$sTargetClass = $oAttDef->GetTargetClass();
			foreach(MetaModel::ListAttributeDefs($sTargetClass) as $sTargetAttCode => $oTargetAttDef)
			{
				if (MetaModel::IsReconcKey($sTargetClass, $sTargetAttCode))
				{
					$bExtKey = $oTargetAttDef->IsExternalKey();
					$aSignatures = array();
					$aSignatures[] = $oAttDef->GetLabel().'->'.$oTargetAttDef->GetLabel();
					$aSignatures[] = $sAttCode.'->'.$sTargetAttCode;
					if ($bExtKey)
					{
						$aSignatures[] = $oAttDef->GetLabel().'->'.$oTargetAttDef->GetLabel().'->id';
						$aSignatures[] = $sAttCode.'->'.$sTargetAttCode.'->id';
					}
					if ($bAdvancedMode || !$bExtKey)
					{
					
						// When not in advanced mode do not allow to use reconciliation keys (on external keys) if they are themselves external keys !
						$aChoices[$sAttCode.'->'.$sTargetAttCode] = MetaModel::GetLabel($sClassName, $sAttCode.'->'.$sTargetAttCode, true);
						foreach ($aSignatures as $sSignature)
						{
							if (strcasecmp($sFieldName, $sSignature) == 0)
							{
								$sFieldCode = $sAttCode.'->'.$sTargetAttCode;
							}
						}
					}
				}
			}
		}
		else if ($oAttDef->IsWritable() && (!$oAttDef->IsLinkset() || ($bAdvancedMode && $oAttDef->IsIndirect())))
		{
			$aChoices[$sAttCode] = MetaModel::GetLabel($sClassName, $sAttCode, true);
			if ( ($sFieldName == $oAttDef->GetLabel()) || ($sFieldName == $sAttCode))
			{
				$sFieldCode = $sAttCode;
			}
		}		
	}
	asort($aChoices);

	$sHtml = "<select id=\"mapping_{$iFieldIndex}\" name=\"field[$iFieldIndex]\">\n";
	$bIsIdField = IsIdField($sClassName, $sFieldCode);
	foreach($aChoices as $sAttCode => $sLabel)
	{
		$sSelected = '';
		if ($bIsIdField && (!$bAdvancedMode)) // When not in advanced mode, ID are mapped to n/a
		{
			if ($sAttCode == ':none:')
			{
				$sSelected = ' selected';
			}
		}
		else if (empty($sFieldCode) && (strpos($sFieldName, '->') !== false))
		{
			if ($sAttCode == ':none:')
			{
				$sSelected = ' selected';
			}
		}
		else if (is_null($sDefaultChoice) && ($sFieldCode == $sAttCode))
		{
			$sSelected = ' selected';
		}
		else if (!is_null($sDefaultChoice) && ($sDefaultChoice == $sAttCode))
		{
			$sSelected = ' selected';
		}

		$sHtml .= "<option value=\"$sAttCode\"$sSelected>$sLabel</option>\n";
	}
	$sHtml .= "</select>\n";
	return $sHtml;
}

try
{
	require_once(APPROOT.'/application/startup.inc.php');

	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	LoginWebPage::DoLogin(); // Check user rights and prompt if needed


	$sOperation = utils::ReadParam('operation', '');

	switch($sOperation)
	{
		case 'parser_preview':
		$oPage = new ajax_page("");
		$oPage->no_cache();
		$oPage->SetContentType('text/html');
		$sSeparator = utils::ReadParam('separator', ',', false, 'raw_data');
		if ($sSeparator == 'tab') $sSeparator = "\t";
		$sTextQualifier = utils::ReadParam('qualifier', '"', false, 'raw_data');
		$iLinesToSkip = utils::ReadParam('do_skip_lines', 0);
		$bFirstLineAsHeader = utils::ReadParam('header_line', true);
		$sEncoding = utils::ReadParam('encoding', 'UTF-8');
		$sData = stripslashes(utils::ReadParam('csvdata', true, false, 'raw_data'));
		$oCSVParser = new CSVParser($sData, $sSeparator, $sTextQualifier);
		$aData = $oCSVParser->ToArray($iLinesToSkip);
		$iTarget = count($aData);
		if ($iTarget == 0)
		{
			$oPage->p(Dict::S('UI:CSVImport:NoData'));
		}
		else
		{
			$sMaxLen = (strlen(''.$iTarget) < 3) ? 3 : strlen(''.$iTarget); // Pad line numbers to the appropriate number of chars, but at least 3
			$sFormat = '%0'.$sMaxLen.'d';
			$oPage->p("<h3>".Dict::S('UI:Title:DataPreview')."</h3>\n");
			$oPage->p("<div style=\"overflow-y:auto\" class=\"white\">\n");
			$oPage->add("<table cellspacing=\"0\" style=\"overflow-y:auto\">");
			$iMaxIndex= 10; // Display maximum 10 lines for the preview
			$index = 1;
			foreach($aData as $aRow)
			{
				$sCSSClass = 'csv_row'.($index % 2);
				if ( ($bFirstLineAsHeader) && ($index == 1))
				{
					$oPage->add("<tr class=\"$sCSSClass\"><td style=\"border-left:#999 3px solid;padding-right:10px;padding-left:10px;\">".sprintf($sFormat, $index)."</td>");
					foreach ($aRow as $sCell)
					{
						$oPage->add('<th>'.htmlentities($sCell, ENT_QUOTES, 'UTF-8').'</th>');
					}
					$oPage->add("</tr>\n");
					$iNbCols = count($aRow);
									
				}
				else
				{
					if ($index == 1) $iNbCols = count($aRow);
					$oPage->add("<tr class=\"$sCSSClass\"><td style=\"border-left:#999 3px solid;padding-right:10px;padding-left:10px;\">".sprintf($sFormat, $index)."</td>");
					foreach ($aRow as $sCell)
					{
						$oPage->add('<td>'.htmlentities($sCell, ENT_QUOTES, 'UTF-8').'</td>');
					}
					$oPage->add("</tr>\n");
				}
				$index++;
				if ($index > $iMaxIndex) break;
			}
			$oPage->add("</table>\n");
			$oPage->add("</div>\n");
			if($iNbCols == 1)
			{
				$oPage->p('<img src="../images/error.png">&nbsp;'.Dict::S('UI:CSVImport:ErrorOnlyOneColumn'));
			}
			else
			{
				$oPage->p('&nbsp;');
			}
		}
		break;

		case 'display_mapping_form':
		$oPage = new ajax_page("");
		$oPage->no_cache();
		$oPage->SetContentType('text/html');
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

		$oCSVParser = new CSVParser($sData, $sSeparator, $sTextQualifier);
		$aData = $oCSVParser->ToArray($iLinesToSkip);
		$iTarget = count($aData);
		if ($iTarget == 0)
		{
			$oPage->p(Dict::S('UI:CSVImport:NoData'));
		}
		else
		{
			$oPage->add("<table>");
			$aFirstLine = $aData[0]; // Use the first row to determine the number of columns
			$iStartLine = 0;
			$iNbColumns = count($aFirstLine);
			if ($bFirstLineAsHeader)
			{
				$iStartLine = 1;
				foreach($aFirstLine as $sField)
				{
					$aHeader[] = $sField;
				}
			}
			else
			{
				// Build some conventional name for the fields: field1...fieldn
				$index= 1;
				foreach($aFirstLine as $sField)
				{
					$aHeader[] = Dict::Format('UI:CSVImport:FieldName', $index);
					$index++;
				}
			}
			$oPage->add("<table>\n");
			$oPage->add('<tr>');
			$oPage->add('<th>'.Dict::S('UI:CSVImport:HeaderFields').'</th><th>'.Dict::S('UI:CSVImport:HeaderMappings').'</th><th>&nbsp;</th><th>'.Dict::S('UI:CSVImport:HeaderSearch').'</th><th>'.Dict::S('UI:CSVImport:DataLine1').'</th><th>'.Dict::S('UI:CSVImport:DataLine2').'</th>');
			$oPage->add('</tr>');
			$index = 1;
			foreach($aHeader as $sField)
			{
				$sDefaultChoice = null;
				if (isset($aInitFieldMapping[$index]))
				{
					$sDefaultChoice = $aInitFieldMapping[$index];
				}
				$oPage->add('<tr>');
				$oPage->add("<th>$sField</th>");
				$oPage->add('<td>'.GetMappingForField($sClassName, $sField, $index, $bAdvanced, $sDefaultChoice).'</td>');
				$oPage->add('<td>&nbsp;</td>');
				$oPage->add('<td><input id="search_'.$index.'" type="checkbox" name="search_field['.$index.']" value="1" /></td>');
				$oPage->add('<td>'.(isset($aData[$iStartLine][$index-1]) ? htmlentities($aData[$iStartLine][$index-1], ENT_QUOTES, 'UTF-8') : '&nbsp;').'</td>');
				$oPage->add('<td>'.(isset($aData[$iStartLine+1][$index-1]) ? htmlentities($aData[$iStartLine+1][$index-1], ENT_QUOTES, 'UTF-8') : '&nbsp;').'</td>');
				$oPage->add('</tr>');
				$index++;
			}
			$oPage->add("</table>\n");

			if (empty($sInitSearchField))
			{
				// Propose a reconciliation scheme
				//
				$aReconciliationKeys = MetaModel::GetReconcKeys($sClassName);
				$aMoreReconciliationKeys = array(); // Store: key => void to automatically remove duplicates
				foreach($aReconciliationKeys as $sAttCode)
				{
					if (!MetaModel::IsValidAttCode($sClassName, $sAttCode)) continue;
					$oAttDef = MetaModel::GetAttributeDef($sClassName, $sAttCode);
					if ($oAttDef->IsExternalKey())
					{
						// An external key is specified as a reconciliation key: this means that all the reconciliation
						// keys of this class are proposed to identify the target object
						$aMoreReconciliationKeys = array_merge($aMoreReconciliationKeys, GetMappingsForExtKey($sAttCode, $oAttDef, $bAdvanced));
					}
					elseif($oAttDef->IsExternalField())
					{
						// An external field is specified as a reconciliation key, translate the field into a field on the target class
						// since external fields are not writable, and thus never appears in the mapping form
						$sKeyAttCode = $oAttDef->GetKeyAttCode();
						$sTargetAttCode = $oAttDef->GetExtAttCode();
						$aMoreReconciliationKeys[$sKeyAttCode.'->'.$sTargetAttCode] = '';			
					}
				}
				$sDefaultKeys = '"'.implode('", "',array_merge($aReconciliationKeys, array_keys($aMoreReconciliationKeys))).'"';
			}
			else
			{
				// The reconciliation scheme is given (navigating back in the wizard)
				//
				$aDefaultKeys = array();
				foreach ($aInitSearchField as $iSearchField => $void)
				{
					$sAttCodeEx = $aInitFieldMapping[$iSearchField];
					$aDefaultKeys[] = $sAttCodeEx;
				}
				$sDefaultKeys = '"'.implode('", "', $aDefaultKeys).'"';
			}
			$oPage->add_ready_script(
<<<EOF
		$('select[name^=field]').change( DoCheckMapping );
		aDefaultKeys = new Array($sDefaultKeys);
		DoCheckMapping();
EOF
);	
		}
		break;

		case 'get_csv_template':
		$sClassName = utils::ReadParam('class_name');
		$sFormat = utils::ReadParam('format', 'csv');
		if (MetaModel::IsValidClass($sClassName))
		{
			$oSearch = new DBObjectSearch($sClassName);
			$oSearch->AddCondition('id', 0, '='); // Make sure we create an empty set
			$oSet = new CMDBObjectSet($oSearch);
			$sResult = cmdbAbstractObject::GetSetAsCSV($oSet, array('showMandatoryFields' => true));
	
			$sClassDisplayName = MetaModel::GetName($sClassName);
			$sDisposition = utils::ReadParam('disposition', 'inline');
			if ($sDisposition == 'attachment')
			{
				switch($sFormat)
				{
					case 'xlsx':
					$oPage = new ajax_page("");
					$oPage->SetContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					$oPage->SetContentDisposition('attachment', $sClassDisplayName.'.xlsx');
					require_once(APPROOT.'/application/excelexporter.class.inc.php');
					$writer = new XLSXWriter();
					$writer->setAuthor(UserRights::GetUserFriendlyName());
					$aHeaders = array( 0 => explode(',', $sResult)); // comma is the default separator
					$writer->writeSheet($aHeaders, $sClassDisplayName, array());
					$oPage->add($writer->writeToString());
					break;
				
					case 'csv':
					default:
					$oPage = new CSVPage("");
					$oPage->add_header("Content-type: text/csv; charset=utf-8");
					$oPage->add_header("Content-disposition: attachment; filename=\"{$sClassDisplayName}.csv\"");
					$oPage->no_cache();		
					$oPage->add($sResult);
				}
			}
			else
			{
				$oPage = new ajax_page("");
				$oPage->no_cache();
				$oPage->add('<p style="text-align:center">');
				$oPage->add('<div style="display:inline-block;margin:0.5em;"><a style="text-decoration:none" href="'.utils::GetAbsoluteUrlAppRoot().'pages/ajax.csvimport.php?operation=get_csv_template&disposition=attachment&class_name='.$sClassName.'"><img border="0" src="../images/csv.png"><br/>'.$sClassDisplayName.'.csv</a></div>');		
				$oPage->add('<div style="display:inline-block;margin:0.5em;"><a style="text-decoration:none" href="'.utils::GetAbsoluteUrlAppRoot().'pages/ajax.csvimport.php?operation=get_csv_template&disposition=attachment&format=xlsx&class_name='.$sClassName.'"><img border="0" src="../images/xlsx.png"><br/>'.$sClassDisplayName.'.xlsx</a></div>');		
				$oPage->add('</p>');		
				$oPage->add('<p><textarea rows="5" cols="100">'.$sResult.'</textarea></p>');
			}		
		}
		else
		{
			$oPage = new ajax_page("Class $sClassName is not a valid class !");
		}
		break;
	}
	$oPage->output();
}
catch (Exception $e)
{
	IssueLog::Error($e->getMessage());
}

?>
