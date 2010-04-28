<?php
require_once('../application/application.inc.php');
require_once('../application/webpage.class.inc.php');
require_once('../application/ajaxwebpage.class.inc.php');
require_once('../application/wizardhelper.class.inc.php');
require_once('../application/ui.linkswidget.class.inc.php');
require_once('../application/csvpage.class.inc.php');

/**
 * Helper function to build the mapping drop-down list for a field
 */
function GetMappingForField($sClassName, $sFieldName, $iFieldIndex)
{
	$aChoices = array('' => '-- select one --');
	$aChoices[':none:'] = '------ n/a ------';
	$aChoices['id'] = 'id (Primary Key)';
	foreach(MetaModel::ListAttributeDefs($sClassName) as $sAttCode => $oAttDef)
	{
		if ($oAttDef->IsExternalKey())
		{
			$aChoices[$sAttCode] = $oAttDef->GetLabel();
			// Get fields of the external class that are considered as reconciliation keys
			$sTargetClass = $oAttDef->GetTargetClass();
			foreach(MetaModel::ListAttributeDefs($sTargetClass) as $sTargetAttCode => $oTargetAttDef)
			{
				if (MetaModel::IsReconcKey($sTargetClass, $sTargetAttCode))
				{
					$aChoices[$sAttCode.'->'.$sTargetAttCode] = $oAttDef->GetLabel().'->'.$oTargetAttDef->GetLabel();
				}
			}
		}
		else if ($oAttDef->IsWritable() && ($sAttCode != 'finalclass')) // finalclass should not be considered as 'writable' isn't it ?
		{
			$aChoices[$sAttCode] = $oAttDef->GetLabel();
		}
	}
	asort($aChoices);
	
	$sHtml = "<select id=\"mapping_{$iFieldIndex}\" name=\"field[$iFieldIndex]\">\n";
	foreach($aChoices as $sAttCode => $sLabel)
	{
		$sSelected = '';
		if ( ($sFieldName == $sAttCode) || ($sFieldName == $sLabel))
		{
			$sSelected = ' selected';
		}
		$sHtml .= "<option value=\"$sAttCode\"$sSelected>$sLabel</option>\n";
	}
	$sHtml .= "</select>\n";
	return $sHtml;
}

require_once('../application/startup.inc.php');
session_start();
if (isset($_SESSION['auth_user']))
{
	$sAuthUser = $_SESSION['auth_user'];
	$sAuthPwd = $_SESSION['auth_pwd'];
	// Attempt to login, fails silently
	UserRights::Login($sAuthUser, $sAuthPwd);
}
else
{
	// No session information
	echo "<p>No session information</p>\n";
}


$oContext = new UserContext();
$sOperation = utils::ReadParam('operation', '');

switch($sOperation)
{
	case 'parser_preview':
	$oPage = new ajax_page("");
	$oPage->no_cache();
	$sSeparator = utils::ReadParam('separator', ',');
	if ($sSeparator == 'tab') $sSeparator = "\t";
	$sTextQualifier = utils::ReadParam('qualifier', '"');
	$iLinesToSkip = utils::ReadParam('nb_lines_skipped', 0);
	$bFirstLineAsHeader = utils::ReadParam('header_line', true);
	$sData = stripslashes(utils::ReadParam('csvdata', true));
	$oCSVParser = new CSVParser($sData, $sSeparator, $sTextQualifier);
	$aData = $oCSVParser->ToArray($iLinesToSkip);
	$iTarget = count($aData);
	if ($iTarget == 0)
	{
		$oPage->p("Empty data set..., please provide some data!");
	}
	else
	{
		$sMaxLen = (strlen(''.$iTarget) < 3) ? 3 : strlen(''.$iTarget); // Pad line numbers to the appropriate number of chars, but at least 3
		$sFormat = '%0'.$sMaxLen.'d';
		$oPage->p("<h3>Data Preview</h3>\n");
		$oPage->p("<div style=\"overflow-y:auto\">\n");
		$oPage->add("<table cellspacing=\"0\" style=\"overflow-y:auto\">");
		$iMaxIndex= 10; // Display maximum 10 lines for the preview
		$index = 1;
		foreach($aData as $aRow)
		{
			$sCSSClass = 'csv_row'.($index % 2);
			if ( ($bFirstLineAsHeader) && ($index == 1))
			{
				$oPage->add("<tr class=\"$sCSSClass\"><td style=\"border-left:#999 3px solid;padding-right:10px;padding-left:10px;\">".sprintf($sFormat, $index)."</td><th>");
				$oPage->add(implode('</th><th>', $aRow));
				$oPage->add("</th></tr>\n");
				$iNbCols = count($aRow);
								
			}
			else
			{
				if ($index == 1) $iNbCols = count($aRow);
				$oPage->add("<tr class=\"$sCSSClass\"><td style=\"border-left:#999 3px solid;padding-right:10px;padding-left:10px;\">".sprintf($sFormat, $index)."</td><td>");
				$oPage->add(implode('</td><td>', $aRow));
				$oPage->add("</td></tr>\n");
			}
			$index++;
			if ($index > $iMaxIndex) break;
		}
		$oPage->add("</table>\n");
		$oPage->add("</div>\n");
		if($iNbCols == 1)
		{
			$oPage->p('<img src="../images/error.png">&nbsp;Error: The data contains only one column. Did you select the appropriate separator character ?');
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
	$sSeparator = utils::ReadParam('separator', ',');
	$sTextQualifier = utils::ReadParam('qualifier', '"');
	$iLinesToSkip = utils::ReadParam('nb_lines_skipped', 0);
	$bFirstLineAsHeader = utils::ReadParam('header_line', true);
	$sData = stripslashes(utils::ReadParam('csvdata', true));
	$sClassName = utils::ReadParam('class_name', '');
	
	$oCSVParser = new CSVParser($sData, $sSeparator, $sTextQualifier);
	$aData = $oCSVParser->ToArray($iLinesToSkip);
	$iTarget = count($aData);
	if ($iTarget == 0)
	{
		$oPage->p("Empty data set..., please provide some data!");
	}
	else
	{
		$oPage->add("<table>");
		$index = 1;
		$aFirstLine = $aData[0]; // Use the first row to determine the number of columns
		$iStartLine = 0;
		$iNbColumns = count($aFirstLine);
		if ($bFirstLineAsHeader)
		{			$iStartLine = 1;
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
				$aHeader[] = 'Field'+$index;
				$index++;
			}
		}
		$oPage->add("<table>\n");
		$oPage->add('<tr>');
		$oPage->add("<th>Fields</th><th>Mapping</th><th>&nbsp;</th><th>Search ?</th><th>Data line 1</th><th>Data line 2</th>");
		$oPage->add('</tr>');
		foreach($aHeader as $sField)
		{
			$oPage->add('<tr>');
			$oPage->add("<th>$sField</th>");
			$oPage->add('<td>'.GetMappingForField($sClassName, $sField, $index).'</td>');
			$oPage->add('<td>&nbsp;</td>');
			$oPage->add('<td><input id="search_'.$index.'" type="checkbox" name="search_field['.$index.']" value="1" /></td>');
			$oPage->add('<td>'.(isset($aData[$iStartLine][$index-1]) ? htmlentities($aData[$iStartLine][$index-1]) : '&nbsp;').'</td>');
			$oPage->add('<td>'.(isset($aData[$iStartLine+1][$index-1]) ? htmlentities($aData[$iStartLine+1][$index-1]) : '&nbsp;').'</td>');
			$oPage->add('</tr>');
			$index++;
		}
		$oPage->add("</table>\n");
	}
	break;
	
	case 'get_csv_template':
	$sClassName = utils::ReadParam('class_name');
	$oSearch = new DBObjectSearch($sClassName);
	$oSearch->AddCondition('id', 0); // Make sure we create an empty set
	$oSet = new CMDBObjectSet($oSearch);
	$sCSV = cmdbAbstractObject::GetSetAsCSV($oSet);
	$aCSV = explode("\n", $sCSV);
	// If there are more than one line, let's assume that the first line is a comment and skip it.
	if (count($aCSV) > 1)
	{
		$sResult = $aCSV[1];
	}
	else
	{
		$sResult = $sCSV;
	}

	$sClassDisplayName = MetaModel::GetName($sClassName);
	$sDisposition = utils::ReadParam('disposition', 'inline');
	if ($sDisposition == 'attachment')
	{
		$oPage = new CSVPage("");
		$oPage->add_header("Content-disposition: attachment; filename=\"{$sClassDisplayName}.csv\"");
		$oPage->no_cache();		
		$oPage->add($sResult);	
	}
	else
	{
		$oPage = new ajax_page("");
		$oPage->no_cache();
		$oPage->add('<p style="text-align:center"><a style="text-decoration:none" href="../pages/ajax.csvimport.php?operation=get_csv_template&disposition=attachment&class_name='.$sClassName.'"><img border="0" src="../images/csv.png"><br/>'.$sClassDisplayName.'.csv</a></p>');		
		$oPage->add('<p><textarea rows="5" cols="100">'.$sResult.'</textarea></p>');
	}
	break;
}
$oPage->output();
?>
