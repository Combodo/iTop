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
 * Bulk export: "spreadsheet" export: a simplified HTML export in which the date/time columns are split in two column: date AND time
*
* @copyright   Copyright (C) 2015 Combodo SARL
* @license     http://opensource.org/licenses/AGPL-3.0
*/

class SpreadsheetBulkExport extends TabularBulkExport
{
	public function DisplayUsage(Page $oP)
	{
		$oP->p(" * spreadsheet format options:");
		$oP->p(" *\tfields: (mandatory) the comma separated list of field codes to export (e.g: name,org_id,service_name...).");
		$oP->p(" *\tno_localize: (optional) pass 1 to retrieve the raw (untranslated) values for enumerated fields. Default: 0.");
		$oP->p(" *\tdate_format: the format to use when exporting date and time fields (default = the SQL format). e.g. 'Y-m-d H:i:s'");
	}

	public function EnumFormParts()
	{
		return array_merge(parent::EnumFormParts(), array('spreadsheet_options' => array('no-localize') ,'interactive_fields_spreadsheet' => array('interactive_fields_spreadsheet')));
	}

	public function DisplayFormPart(WebPage $oP, $sPartId)
	{
		switch($sPartId)
		{
			case 'interactive_fields_spreadsheet':
				$this->GetInteractiveFieldsWidget($oP, 'interactive_fields_spreadsheet');
				break;
					
			case 'spreadsheet_options':
				$sChecked = (utils::ReadParam('no_localize', 0) == 1) ? ' checked ' : '';
				$oP->add('<fieldset><legend>'.Dict::S('Core:BulkExport:SpreadsheetOptions').'</legend>');
				$oP->add('<table>');
				$oP->add('<tr>');
				$oP->add('<td><input type="checkbox" id="spreadsheet_no_localize" name="no_localize" value="1"'.$sChecked.'><label for="spreadsheet_no_localize"> '.Dict::S('Core:BulkExport:OptionNoLocalize').'</label></td>');
				
				$sDateTimeFormat = utils::ReadParam('date_format', (string)AttributeDateTime::GetFormat(), true, 'raw_data');
				$sDefaultChecked = ($sDateTimeFormat == (string)AttributeDateTime::GetFormat()) ? ' checked' : '';
				$sCustomChecked = ($sDateTimeFormat !== (string)AttributeDateTime::GetFormat()) ? ' checked' : '';
				
				$oP->add('<td>');
				$oP->add('<h3>'.Dict::S('Core:BulkExport:DateTimeFormat').'</h3>');
				$sDefaultFormat = htmlentities((string)AttributeDateTime::GetFormat(), ENT_QUOTES, 'UTF-8');
				$sExample = htmlentities(date((string)AttributeDateTime::GetFormat()), ENT_QUOTES, 'UTF-8');
				$oP->add('<input type="radio" id="spreadsheet_date_time_format_default" name="spreadsheet_date_format_radio" value="default"'.$sDefaultChecked.'><label for="spreadsheet_date_time_format_default"> '.Dict::Format('Core:BulkExport:DateTimeFormatDefault_Example', $sDefaultFormat, $sExample).'</label><br/>');
				$sFormatInput = '<input type="text" size="15" name="date_format" id="spreadsheet_custom_date_time_format" title="" value="'.htmlentities($sDateTimeFormat, ENT_QUOTES, 'UTF-8').'"/>';
				$oP->add('<input type="radio" id="spreadsheet_date_time_format_custom" name="spreadsheet_date_format_radio" value="custom"'.$sCustomChecked.'><label for="spreadsheet_date_time_format_custom"> '.Dict::Format('Core:BulkExport:DateTimeFormatCustom_Format', $sFormatInput).'</label>');
				$oP->add('</td>');
				
				$oP->add('</tr>');
				$oP->add('</table>');
				$oP->add('</fieldset>');
				$sJSTooltip = json_encode('<div class="date_format_tooltip">'.Dict::S('UI:CSVImport:CustomDateTimeFormatTooltip').'</div>');
				$oP->add_ready_script(
<<<EOF
$('#spreadsheet_custom_date_time_format').tooltip({content: function() { return $sJSTooltip; } });
$('#form_part_spreadsheet_options').on('preview_updated', function() { FormatDatesInPreview('spreadsheet', 'spreadsheet'); });
$('#spreadsheet_date_time_format_default').on('click', function() { FormatDatesInPreview('spreadsheet', 'spreadsheet'); });
$('#spreadsheet_date_time_format_custom').on('click', function() { FormatDatesInPreview('spreadsheet', 'spreadsheet'); });
$('#spreadsheet_custom_date_time_format').on('click', function() { $('#spreadsheet_date_time_format_custom').prop('checked', true); });
$('#spreadsheet_custom_date_time_format').on('click', function() { $('#spreadsheet_date_time_format_custom').prop('checked', true); FormatDatesInPreview('spreadsheet', 'spreadsheet'); }).on('keyup', function() { FormatDatesInPreview('spreadsheet', 'spreadsheet'); });											
EOF
				);
				break;
				
			default:
				return parent:: DisplayFormPart($oP, $sPartId);
		}
	}

	public function ReadParameters()
	{
		parent::ReadParameters();

		$sDateFormatRadio = utils::ReadParam('spreadsheet_date_format_radio', '');
		switch($sDateFormatRadio)
		{
			case 'default':
			// Export from the UI => format = same as is the UI
			$this->aStatusInfo['date_format'] = (string)AttributeDateTime::GetFormat();
			break;
			
			case 'custom':
			// Custom format specified from the UI
			$this->aStatusInfo['date_format'] = utils::ReadParam('date_format', (string)AttributeDateTime::GetFormat(), true, 'raw_data');
			break;
			
			default:
			// Export from the command line (or scripted) => default format is SQL, as in previous versions of iTop, unless specified otherwise
			$this->aStatusInfo['date_format'] = utils::ReadParam('date_format', (string)AttributeDateTime::GetSQLFormat(), true, 'raw_data');
		}
	}
	
	protected function GetSampleData($oObj, $sAttCode)
	{
		if ($sAttCode != 'id')
		{
			$oAttDef = MetaModel::GetAttributeDef(get_class($oObj), $sAttCode);
			if ($oAttDef instanceof AttributeDateTime) // AttributeDate is derived from AttributeDateTime
			{
				$sClass = (get_class($oAttDef) == 'AttributeDateTime') ? 'user-formatted-date-time' : 'user-formatted-date';
				return '<div class="'.$sClass.'" data-date="'.$oObj->Get($sAttCode).'">'.htmlentities($oAttDef->GetEditValue($oObj->Get($sAttCode), $oObj), ENT_QUOTES, 'UTF-8').'</div>';
			}
		}
		return $this->GetValue($oObj, $sAttCode);
	}

	protected function GetValue($oObj, $sAttCode)
	{
		switch($sAttCode)
		{
			case 'id':
				$sRet = $oObj->GetKey();
				break;
					
			default:
				$value = $oObj->Get($sAttCode);
				$oAttDef = MetaModel::GetAttributeDef(get_class($oObj), $sAttCode);
				if ($value instanceof ormCaseLog)
				{
					$sRet = str_replace("\n", "<br/>", htmlentities($value->__toString(), ENT_QUOTES, 'UTF-8'));
				}
				elseif ($value instanceof ormStopWatch)
				{
					$sRet = $value->GetTimeSpent();
				}
				elseif ($value instanceof ormDocument)
				{
					$sRet = '';
				}
				elseif ($oAttDef instanceof AttributeString)
				{
					$sRet = $oObj->GetAsHTML($sAttCode);
				}
				elseif ($oAttDef instanceof AttributeCustomFields)
				{
					$sRet = $oObj->GetAsHTML($sAttCode);
				}
				else
				{
					if ($this->bLocalizeOutput)
					{
						$sRet = htmlentities($oObj->GetEditValue(), ENT_QUOTES, 'UTF-8');
					}
					else
					{
						$sRet = htmlentities((string)$value, ENT_QUOTES, 'UTF-8');
					}
				}
		}

		return $sRet;
	}

	public function SetHttpHeaders(WebPage $oPage)
	{
		// Integration within MS-Excel web queries + HTTPS + IIS:
		// MS-IIS set these header values with no-cache... while Excel fails to do the job if using HTTPS
		// Then the fix is to force the reset of header values Pragma and Cache-control 
		$oPage->add_header("Pragma:", true);
		$oPage->add_header("Cache-control:", true);
	}

	public function GetHeader()
	{
		$oSet = new DBObjectSet($this->oSearch);
		$this->aStatusInfo['status'] = 'running';
		$this->aStatusInfo['position'] = 0;
		$this->aStatusInfo['total'] = $oSet->Count();

		$aData = array();
		foreach($this->aStatusInfo['fields'] as $iCol => $aFieldSpec)
		{
			$sColLabel = $aFieldSpec['sColLabel'];
			if ($aFieldSpec['sAttCode'] != 'id')
			{
				$oAttDef = MetaModel::GetAttributeDef($aFieldSpec['sClass'], $aFieldSpec['sAttCode']);
				$oFinalAttDef = $oAttDef->GetFinalAttDef();
				if (get_class($oFinalAttDef) == 'AttributeDateTime')
				{
					$aData[] = $sColLabel.' ('.Dict::S('UI:SplitDateTime-Date').')';
					$aData[] = $sColLabel.' ('.Dict::S('UI:SplitDateTime-Time').')';
				}
				else
				{
					$aData[] = $sColLabel;
				}
			}
			else
			{
				$aData[] = $sColLabel;
			}
		}
		$sData = '';
		$sData .= '<style>table br {mso-data-placement:same-cell;}</style>'; // Trick for Excel: keep line breaks inside the same cell !
		$sData .= "<table border=\"1\">\n";
		$sData .= "<tr>\n";
		foreach($aData as $sLabel)
		{
			$sData .= "<td>".$sLabel."</td>\n";
		}
		$sData .= "</tr>\n";
		return $sData;
	}

	public function GetNextChunk(&$aStatus)
	{
		$sRetCode = 'run';
		$iPercentage = 0;

		$oSet = new DBObjectSet($this->oSearch);
		$oSet->SetLimit($this->iChunkSize, $this->aStatusInfo['position']);
		$this->OptimizeColumnLoad($oSet);
		
		$sExportDateTimeFormat = $this->aStatusInfo['date_format'];
		// Date & time formats
		$oDateTimeFormat = new DateTimeFormat($sExportDateTimeFormat);
		$oDateFormat = new DateTimeFormat($oDateTimeFormat->ToDateFormat());
		$oTimeFormat = new DateTimeFormat($oDateTimeFormat->ToTimeFormat());
		
		$iCount = 0;
		$sData = '';
		$iPreviousTimeLimit = ini_get('max_execution_time');
		$iLoopTimeLimit = MetaModel::GetConfig()->Get('max_execution_time_per_loop');
		while($aRow = $oSet->FetchAssoc())
		{
			set_time_limit($iLoopTimeLimit);

			$sData .= "<tr>";
			foreach($this->aStatusInfo['fields'] as $iCol => $aFieldSpec)
			{
				$sAlias = $aFieldSpec['sAlias'];
				$sAttCode = $aFieldSpec['sAttCode'];

				$sField = '';
				$oObj = $aRow[$sAlias];
				if ($oObj == null)
				{
					$sData .= "<td x:str></td>";
					continue;
				}
				
				switch($sAttCode)
				{
					case 'id':
					$sField = $oObj->GetKey();
					$sData .= "<td>$sField</td>";
					break;
							
					default:
					$oAttDef = MetaModel::GetAttributeDef(get_class($oObj), $sAttCode);
					$oFinalAttDef = $oAttDef->GetFinalAttDef();
					if (get_class($oFinalAttDef) == 'AttributeDateTime')
					{
						// Split the date and time in two columns
						$sDate = $oDateFormat->Format($oObj->Get($sAttCode));
						$sTime = $oTimeFormat->Format($oObj->Get($sAttCode));
						$sData .= "<td>$sDate</td>";
						$sData .= "<td>$sTime</td>";
					}
					else if (get_class($oFinalAttDef) == 'AttributeDate')
					{
						$sDate = $oDateFormat->Format($oObj->Get($sAttCode));
						$sData .= "<td>$sDate</td>";
					}
					else if($oAttDef instanceof AttributeCaseLog)
					{
						$rawValue = $oObj->Get($sAttCode);
						$sField = str_replace("\n", "<br/>", htmlentities($rawValue->__toString(), ENT_QUOTES, 'UTF-8'));
						// Trick for Excel: treat the content as text even if it begins with an equal sign
						$sData .= "<td x:str>$sField</td>";
					}
					else if($oAttDef instanceof AttributeString)
					{
						$sField = $oObj->GetAsHTML($sAttCode, $this->bLocalizeOutput);
						$sData .= "<td x:str>$sField</td>";
					}
					else
					{
						$rawValue = $oObj->Get($sAttCode);
						if ($this->bLocalizeOutput)
						{
							$sField = htmlentities($oFinalAttDef->GetEditValue($rawValue), ENT_QUOTES, 'UTF-8');
						}
						else
						{
							$sField = htmlentities($rawValue, ENT_QUOTES, 'UTF-8');
						}
						$sData .= "<td>$sField</td>";
					}
				}

			}
			$sData .= "</tr>";
			$iCount++;
		}
		set_time_limit($iPreviousTimeLimit);
		$this->aStatusInfo['position'] += $this->iChunkSize;
		if ($this->aStatusInfo['total'] == 0)
		{
			$iPercentage = 100;
		}
		else
		{
			$iPercentage = floor(min(100.0, 100.0*$this->aStatusInfo['position']/$this->aStatusInfo['total']));
		}

		if ($iCount < $this->iChunkSize)
		{
			$sRetCode = 'done';
		}

		$aStatus = array('code' => $sRetCode, 'message' => Dict::S('Core:BulkExport:RetrievingData'), 'percentage' => $iPercentage);
		return $sData;
	}

	public function GetFooter()
	{
		$sData = "</table>\n";

		return $sData;
	}

	public function GetSupportedFormats()
	{
		return array('spreadsheet' => Dict::S('Core:BulkExport:SpreadsheetFormat'));
	}

	public function GetMimeType()
	{
		return 'text/html';
	}

	public function GetFileExtension()
	{
		return 'html';
	}
}
