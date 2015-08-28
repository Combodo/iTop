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
				$oP->add('</tr>');
				$oP->add('</table>');
				$oP->add('</fieldset>');
				break;
				
			default:
				return parent:: DisplayFormPart($oP, $sPartId);
		}
	}
	
	protected function GetSampleData($oObj, $sAttCode)
	{
		if ($oObj)
		{
			$sRet = $oObj->GetAsHTML($sAttCode);
		}
		else
		{
			$sRet = '';
		}
		return $sRet;
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
		}
		$sData = "<table border=\"1\">\n";
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
					$sData .= "<td x:str>$sField</td>";
					continue;
				}
				
				switch($sAttCode)
				{
					case 'id':
					$sField = $oObj->GetName();
					$sData .= "<td>$sField</td>";
					break;
							
					default:
					$oAttDef = MetaModel::GetAttributeDef(get_class($oObj), $sAttCode);
					$oFinalAttDef = $oAttDef->GetFinalAttDef();
					if (get_class($oFinalAttDef) == 'AttributeDateTime')
					{
						$iDate = AttributeDateTime::GetAsUnixSeconds($oObj->Get($sAttCode));
						$sData .= '<td>'.date('Y-m-d', $iDate).'</td>'; // Add the first column directly
						$sField = date('H:i:s', $iDate); // Will add the second column below
						$sData .= "<td>$sField</td>";
					}
					else if($oAttDef instanceof AttributeCaseLog)
					{
						$rawValue = $oObj->Get($sAttCode);
						$sField = str_replace("\n", "<br/>", htmlentities($rawValue->__toString(), ENT_QUOTES, 'UTF-8'));
						// Trick for Excel: treat the content as text even if it begins with an equal sign
						$sData .= "<td x:str>$sField</td>";
					}
					else
					{
						$rawValue = $oObj->Get($sAttCode);
						// Due to custom formatting rules, empty friendlynames may be rendered as non-empty strings
						// let's fix this and make sure we render an empty string if the key == 0
						if ($oAttDef instanceof AttributeFriendlyName)
						{
							$sKeyAttCode = $oAttDef->GetKeyAttCode();
							if ($sKeyAttCode != 'id')
							{
								if ($oObj->Get($sKeyAttCode) == 0)
								{
									$sValue = '';
								}
							}
						}
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
