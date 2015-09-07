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
 * Bulk export: CSV export
 *
 * @copyright   Copyright (C) 2015 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class CSVBulkExport extends TabularBulkExport
{
	public function DisplayUsage(Page $oP)
	{
		$oP->p(" * csv format options:");
		$oP->p(" *\tfields: (mandatory) the comma separated list of field codes to export (e.g: name,org_id,service_name...).");
		$oP->p(" *\tseparator: (optional) character to be used as the separator (default is ',').");
		$oP->p(" *\tcharset: (optional) character set for encoding the result (default is 'UTF-8').");
		$oP->p(" *\ttext-qualifier: (optional) character to be used around text strings (default is '\"').");
		$oP->p(" *\tno_localize: set to 1 to retrieve non-localized values (for instance for ENUM values). Default is 0 (= localized values)");
	}

	public function ReadParameters()
	{
		parent::ReadParameters();
		$this->aStatusInfo['separator'] = utils::ReadParam('separator', ',', true, 'raw_data');
		if (strtolower($this->aStatusInfo['separator']) == 'tab')
		{
			$this->aStatusInfo['separator'] = "\t";
		}
		else if (strtolower($this->aStatusInfo['separator']) == 'other')
		{
			$this->aStatusInfo['separator'] = utils::ReadParam('other-separator', ',', true, 'raw_data');
		}
			
		$this->aStatusInfo['text_qualifier'] = utils::ReadParam('text-qualifier', '"', true, 'raw_data');
		if (strtolower($this->aStatusInfo['text_qualifier']) == 'other')
		{
			$this->aStatusInfo['text_qualifier'] = utils::ReadParam('other-text-qualifier', '"', true, 'raw_data');
		}

		$this->aStatusInfo['charset'] = strtoupper(utils::ReadParam('charset', 'UTF-8', true, 'raw_data'));
	}


	protected function SuggestField($sClass, $sAttCode)
	{
		switch($sAttCode)
		{
			case 'id': // replace 'id' by 'friendlyname'
				$sAttCode = 'friendlyname';
				break;
					
			default:
				$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
				if ($oAttDef instanceof AttributeExternalKey)
				{
					$sAttCode .= '_friendlyname';
				}
		}

		return parent::SuggestField($sClass, $sAttCode);
	}

	public function EnumFormParts()
	{
		return array_merge(parent::EnumFormParts(), array('csv_options' => array('separator', 'charset', 'text-qualifier', 'no_localize') ,'interactive_fields_csv' => array('interactive_fields_csv')));
	}

	public function DisplayFormPart(WebPage $oP, $sPartId)
	{
		switch($sPartId)
		{
			case 'interactive_fields_csv':
				$this->GetInteractiveFieldsWidget($oP, 'interactive_fields_csv');
				break;

			case 'csv_options':
				$oP->add('<fieldset><legend>'.Dict::S('Core:BulkExport:CSVOptions').'</legend>');
				$oP->add('<table class="export_parameters"><tr><td style="vertical-align:top">');
				$oP->add('<h3>'.Dict::S('UI:CSVImport:SeparatorCharacter').'</h3>');
				$sRawSeparator = utils::ReadParam('separator', ',', true, 'raw_data');
				$aSep = array(
					';' => Dict::S('UI:CSVImport:SeparatorSemicolon+'),
					',' => Dict::S('UI:CSVImport:SeparatorComma+'),
					'tab' => Dict::S('UI:CSVImport:SeparatorTab+'),
				);
				$sOtherSeparator = '';
				if (!array_key_exists($sRawSeparator, $aSep))
				{
					$sOtherSeparator = $sRawSeparator;
					$sRawSeparator = 'other';
				}
				$aSep['other'] = Dict::S('UI:CSVImport:SeparatorOther').' <input type="text" size="3" name="other-separator" value="'.htmlentities($sOtherSeparator, ENT_QUOTES, 'UTF-8').'"/>';

				foreach($aSep as $sVal => $sLabel)
				{
					$sChecked = ($sVal == $sRawSeparator) ? 'checked' : '';
					$oP->add('<input type="radio" name="separator" value="'.htmlentities($sVal, ENT_QUOTES, 'UTF-8').'" '.$sChecked.'/>&nbsp;'.$sLabel.'<br/>');
				}
					
				$oP->add('</td><td style="vertical-align:top">');
					
				$oP->add('<h3>'.Dict::S('UI:CSVImport:TextQualifierCharacter').'</h3>');

				$sRawQualifier = utils::ReadParam('text-qualifier', '"', true, 'raw_data');
				$aQualifiers = array(
					'"' => Dict::S('UI:CSVImport:QualifierDoubleQuote+'),
					'\'' => Dict::S('UI:CSVImport:QualifierSimpleQuote+'),
				);
				$sOtherQualifier = '';
				if (!array_key_exists($sRawQualifier, $aQualifiers))
				{
					$sOtherQualifier = $sRawQualifier;
					$sRawQualifier = 'other';
				}
				$aQualifiers['other'] = Dict::S('UI:CSVImport:QualifierOther').' <input type="text" size="3" name="other-text-qualifier" value="'.htmlentities($sOtherQualifier, ENT_QUOTES, 'UTF-8').'"/>';
					
				foreach($aQualifiers as $sVal => $sLabel)
				{
					$sChecked = ($sVal == $sRawQualifier) ? 'checked' : '';
					$oP->add('<input type="radio" name="text-qualifier" value="'.htmlentities($sVal, ENT_QUOTES, 'UTF-8').'" '.$sChecked.'/>&nbsp;'.$sLabel.'<br/>');
				}
				
				$sChecked = (utils::ReadParam('no_localize', 0) == 1) ? ' checked ' : '';
				$oP->add('</td><td style="vertical-align:top">');
				$oP->add('<h3>'.Dict::S('Core:BulkExport:CSVLocalization').'</h3>');
				$oP->add('<input type="checkbox" id="csv_no_localize" name="no_localize" value="1"'.$sChecked.'><label for="csv_no_localize"> '.Dict::S('Core:BulkExport:OptionNoLocalize').'</label>');
				$oP->add('<br/>');
				$oP->add('<br/>');
				$oP->add(Dict::S('UI:CSVImport:Encoding').': <select name="charset" style="font-family:Arial,Helvetica,Sans-serif">'); // IE 8 has some troubles if the font is different
				$aPossibleEncodings = utils::GetPossibleEncodings(MetaModel::GetConfig()->GetCSVImportCharsets());
				$sDefaultEncoding = MetaModel::GetConfig()->Get('csv_file_default_charset');
				foreach($aPossibleEncodings as $sIconvCode => $sDisplayName )
				{
					$sSelected  = '';
					if ($sIconvCode == $sDefaultEncoding)
					{
						$sSelected = ' selected';
					}
					$oP->add('<option value="'.$sIconvCode.'"'.$sSelected.'>'.$sDisplayName.'</option>');
				}
				$oP->add('</select>');

				$oP->add('</td></tr></table>');
				
				$oP->add('</fieldset>');
				break;
					
					
			default:
				return parent:: DisplayFormPart($oP, $sPartId);
		}
	}

	protected function GetSampleData($oObj, $sAttCode)
	{
		return '<div class="text-preview">'.htmlentities($this->GetValue($oObj, $sAttCode), ENT_QUOTES, 'UTF-8').'</div>';
	}

	protected function GetValue($oObj, $sAttCode)
	{
		switch($sAttCode)
		{
			case 'id':
				$sRet = $oObj->GetKey();
				break;
					
			default:
				$sRet = trim($oObj->GetAsCSV($sAttCode), '"');
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
			$aData[] = $aFieldSpec['sColLabel'];
		}
		$sFrom = array("\r\n", $this->aStatusInfo['text_qualifier']);
		$sTo = array("\n", $this->aStatusInfo['text_qualifier'].$this->aStatusInfo['text_qualifier']);
		foreach($aData as $idx => $sData)
		{
			// Escape and encode (if needed) the headers
			$sEscaped = str_replace($sFrom, $sTo, (string)$sData);
			$aData[$idx] = $this->aStatusInfo['text_qualifier'].$sEscaped.$this->aStatusInfo['text_qualifier'];
			if ($this->aStatusInfo['charset'] != 'UTF-8')
			{
				// Note: due to bugs in the glibc library it's safer to call iconv on the smallest possible string
				// and thus to convert field by field and not the whole row or file at once (see ticket #991)
				$aData[$idx] = iconv('UTF-8', $this->aStatusInfo['charset'].'//IGNORE//TRANSLIT', $aData[$idx]);
			}
		}
		$sData = implode($this->aStatusInfo['separator'], $aData)."\n";

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
			$aData = array();
			foreach($this->aStatusInfo['fields'] as $iCol => $aFieldSpec)
			{
				$sAlias = $aFieldSpec['sAlias'];
				$sAttCode = $aFieldSpec['sAttCode'];

				$sField = '';
				$oObj = $aRow[$sAlias];
				if ($oObj != null)
				{
					switch($sAttCode)
					{
						case 'id':
							$sField = $oObj->GetKey();
							break;
								
						default:
							$sField = $oObj->GetAsCSV($sAttCode, $this->aStatusInfo['separator'], $this->aStatusInfo['text_qualifier'], $this->bLocalizeOutput);
					}
				}
				if ($this->aStatusInfo['charset'] != 'UTF-8')
				{
					// Note: due to bugs in the glibc library it's safer to call iconv on the smallest possible string
					// and thus to convert field by field and not the whole row or file at once (see ticket #991)
					$aData[] = iconv('UTF-8', $this->aStatusInfo['charset'].'//IGNORE//TRANSLIT', $sField);
				}
				else
				{
					$aData[] = $sField;
				}
			}
			$sData .= implode($this->aStatusInfo['separator'], $aData)."\n";
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

	public function GetSupportedFormats()
	{
		return array('csv' => Dict::S('Core:BulkExport:CSVFormat'));
	}

	public function GetMimeType()
	{
		return 'text/csv';
	}

	public function GetFileExtension()
	{
		return 'csv';
	}
	public function GetCharacterSet()
	{
		return $this->aStatusInfo['charset'];
	}

}
