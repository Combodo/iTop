<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\UI\Base\Component\FieldSet\FieldSetUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectOptionUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\MultiColumn\Column\ColumnUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\MultiColumn\MultiColumnUIBlockFactory;
use Combodo\iTop\Application\Helper\ExportHelper;
use Combodo\iTop\Application\WebPage\Page;
use Combodo\iTop\Application\WebPage\WebPage;

/**
 * Bulk export: CSV export
 *
 * @copyright   Copyright (C) 2015-2024 Combodo SAS
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
		$oP->p(" *\tformatted_text: set to 1 to export case logs and formatted text fields with their HTML markup. Default is 0 (= plain text)");
		$oP->p(" *\tdate_format: the format to use when exporting date and time fields (default = the SQL format used in the user interface). e.g. 'Y-m-d H:i:s'");
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
		$this->aStatusInfo['formatted_text'] = (bool)utils::ReadParam('formatted_text', 0, true);
		
		$sDateFormatRadio = utils::ReadParam('csv_date_format_radio', '');
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
		return array_merge(parent::EnumFormParts(), array('csv_options' => array('separator', 'charset', 'text-qualifier', 'no_localize', 'formatted_text'), 'interactive_fields_csv' => array('interactive_fields_csv')));
	}

	/**
	 * @param WebPage $oP
	 * @param $sPartId
	 *
	 * @return UIContentBlock
	 */
	public function GetFormPart(WebPage $oP, $sPartId)
	{
		switch ($sPartId) {
			case 'interactive_fields_csv':
				return $this->GetInteractiveFieldsWidget($oP, 'interactive_fields_csv');
				break;

			case 'csv_options':
				$oPanel = PanelUIBlockFactory::MakeNeutral(Dict::S('Core:BulkExport:CSVOptions'));
				$oPanel->AddSubBlock(ExportHelper::GetAlertForExcelMaliciousInjection());

				$oMulticolumn = MultiColumnUIBlockFactory::MakeStandard();
				$oPanel->AddSubBlock($oMulticolumn);

				//SeparatorCharacter
				$oFieldSetSeparator = FieldSetUIBlockFactory::MakeStandard(Dict::S('UI:CSVImport:SeparatorCharacter'));
				$oMulticolumn->AddColumn(ColumnUIBlockFactory::MakeForBlock($oFieldSetSeparator));

				$sRawSeparator = utils::ReadParam('separator', ',', true, 'raw_data');
				$sCustomDateTimeFormat = utils::ReadParam('', ',', true, 'raw_data');
				$aSep = array(
					';'   => Dict::S('UI:CSVImport:SeparatorSemicolon+'),
					','   => Dict::S('UI:CSVImport:SeparatorComma+'),
					'tab' => Dict::S('UI:CSVImport:SeparatorTab+'),
				);
				$sOtherSeparator = '';
				if (!array_key_exists($sRawSeparator, $aSep)) {
					$sOtherSeparator = $sRawSeparator;
					$sRawSeparator = 'other';
				}
				$aSep['other'] = Dict::S('UI:CSVImport:SeparatorOther').' <input type="text" size="3" name="other-separator" value="'.utils::EscapeHtml($sOtherSeparator).'"/>';

				foreach ($aSep as $sVal => $sLabel) {
					$oRadio = InputUIBlockFactory::MakeForInputWithLabel($sLabel, "separator", $sVal, $sLabel, "radio");
					$oRadio->GetInput()->SetIsChecked(($sVal == $sRawSeparator));
					$oRadio->SetBeforeInput(false);
					$oRadio->GetInput()->AddCSSClass('ibo-input--label-right');
					$oRadio->GetInput()->AddCSSClass('ibo-input-checkbox');
					$oFieldSetSeparator->AddSubBlock($oRadio);
					$oFieldSetSeparator->AddSubBlock(new Html('</br>'));
				}

				//TextQualifierCharacter
				$oFieldSetTextQualifier = FieldSetUIBlockFactory::MakeStandard(Dict::S('UI:CSVImport:TextQualifierCharacter'));
				$oMulticolumn->AddColumn(ColumnUIBlockFactory::MakeForBlock($oFieldSetTextQualifier));

				$sRawQualifier = utils::ReadParam('text-qualifier', '"', true, 'raw_data');
				$aQualifiers = array(
					'"'  => Dict::S('UI:CSVImport:QualifierDoubleQuote+'),
					'\'' => Dict::S('UI:CSVImport:QualifierSimpleQuote+'),
				);
				$sOtherQualifier = '';
				if (!array_key_exists($sRawQualifier, $aQualifiers)) {
					$sOtherQualifier = $sRawQualifier;
					$sRawQualifier = 'other';
				}
				$aQualifiers['other'] = Dict::S('UI:CSVImport:QualifierOther').' <input type="text" size="3" name="other-text-qualifier" value="'.utils::EscapeHtml($sOtherQualifier).'"/>';

				foreach ($aQualifiers as $sVal => $sLabel) {
					$oRadio = InputUIBlockFactory::MakeForInputWithLabel($sLabel, "text-qualifier", $sVal, $sLabel, "radio");
					$oRadio->GetInput()->SetIsChecked(($sVal == $sRawQualifier));
					$oRadio->SetBeforeInput(false);
					$oRadio->GetInput()->AddCSSClass('ibo-input--label-right');
					$oRadio->GetInput()->AddCSSClass('ibo-input-checkbox');
					$oFieldSetTextQualifier->AddSubBlock($oRadio);
					$oFieldSetTextQualifier->AddSubBlock(new Html('</br>'));
				}

				//Localization
				$oFieldSetLocalization = FieldSetUIBlockFactory::MakeStandard(Dict::S('Core:BulkExport:CSVLocalization'));
				$oMulticolumn->AddColumn(ColumnUIBlockFactory::MakeForBlock($oFieldSetLocalization));

				$oCheckBox = InputUIBlockFactory::MakeForInputWithLabel(Dict::S('Core:BulkExport:OptionNoLocalize'), "no_localize", "1", "csv_no_localize", "checkbox");
				$oCheckBox->GetInput()->SetIsChecked((utils::ReadParam('no_localize', 0) == 1));
				$oCheckBox->SetBeforeInput(false);
				$oCheckBox->GetInput()->AddCSSClass('ibo-input--label-right');
				$oCheckBox->GetInput()->AddCSSClass('ibo-input-checkbox');
				$oFieldSetLocalization->AddSubBlock($oCheckBox);
				$oFieldSetLocalization->AddSubBlock(new Html('</br>'));

				$oSelect = SelectUIBlockFactory::MakeForSelectWithLabel("charset", Dict::S('UI:CSVImport:Encoding'));
				$oSelect->SetIsLabelBefore(true);
				$oFieldSetLocalization->AddSubBlock($oSelect);

				$aPossibleEncodings = utils::GetPossibleEncodings(MetaModel::GetConfig()->GetCSVImportCharsets());
				$sDefaultEncoding = MetaModel::GetConfig()->Get('csv_file_default_charset');
				foreach ($aPossibleEncodings as $sIconvCode => $sDisplayName) {
					$oSelect->AddSubBlock(SelectOptionUIBlockFactory::MakeForSelectOption($sIconvCode, $sDisplayName, ($sIconvCode == $sDefaultEncoding)));
				}
				//markup
				$oFieldSetMarkup = FieldSetUIBlockFactory::MakeStandard(Dict::S('Core:BulkExport:TextFormat'));
				$oMulticolumn->AddColumn(ColumnUIBlockFactory::MakeForBlock($oFieldSetMarkup));

				$oCheckBoxMarkup = InputUIBlockFactory::MakeForInputWithLabel(Dict::S('Core:BulkExport:OptionFormattedText'), "formatted_text", "1", "csv_formatted_text", "checkbox");
				$oCheckBoxMarkup->GetInput()->SetIsChecked((utils::ReadParam('formatted_text', 0) == 1));
				$oCheckBoxMarkup->SetBeforeInput(false);
				$oCheckBoxMarkup->GetInput()->AddCSSClass('ibo-input-checkbox');
				$oFieldSetMarkup->AddSubBlock($oCheckBoxMarkup);

				//date format
				$oFieldSetDate = FieldSetUIBlockFactory::MakeStandard(Dict::S('Core:BulkExport:DateTimeFormat'));
				$oMulticolumn->AddColumn(ColumnUIBlockFactory::MakeForBlock($oFieldSetDate));

				$sDateTimeFormat = utils::ReadParam('date_format', (string)AttributeDateTime::GetFormat(), true, 'raw_data');

				$sDefaultFormat = utils::EscapeHtml((string)AttributeDateTime::GetFormat());
				$sExample = utils::EscapeHtml(date((string)AttributeDateTime::GetFormat()));
				$oRadioDefault = InputUIBlockFactory::MakeForInputWithLabel(Dict::Format('Core:BulkExport:DateTimeFormatDefault_Example', $sDefaultFormat, $sExample), "csv_date_format_radio", "default", "csv_date_time_format_default", "radio");
				$oRadioDefault->GetInput()->SetIsChecked(($sDateTimeFormat == (string)AttributeDateTime::GetFormat()));
				$oRadioDefault->SetBeforeInput(false);
				$oRadioDefault->GetInput()->AddCSSClass('ibo-input-checkbox');
				$oFieldSetDate->AddSubBlock($oRadioDefault);
				$oFieldSetDate->AddSubBlock(new Html('</br>'));

				$sFormatInput = '<input type="text" size="15" name="date_format" id="csv_custom_date_time_format" title="" value="'.utils::EscapeHtml($sDateTimeFormat).'"/>';
				$oRadioCustom = InputUIBlockFactory::MakeForInputWithLabel(Dict::Format('Core:BulkExport:DateTimeFormatCustom_Format', $sFormatInput), "csv_date_format_radio", "custom", "csv_date_time_format_custom", "radio");
				$oRadioCustom->SetDescription(Dict::S('UI:CSVImport:CustomDateTimeFormatTooltip'));
				$oRadioCustom->GetInput()->SetIsChecked($sDateTimeFormat !== (string)AttributeDateTime::GetFormat());
				$oRadioCustom->SetBeforeInput(false);
				$oRadioCustom->GetInput()->AddCSSClass('ibo-input-checkbox');
				$oFieldSetDate->AddSubBlock($oRadioCustom);


				$oP->add_ready_script(
					<<<EOF
$('#form_part_csv_options').on('preview_updated', function() { FormatDatesInPreview('csv', 'csv'); });
$('#csv_date_time_format_default').on('click', function() { FormatDatesInPreview('csv', 'csv'); });
$('#csv_date_time_format_custom').on('click', function() { FormatDatesInPreview('csv', 'csv'); });
$('#csv_custom_date_time_format').on('click', function() { $('#csv_date_time_format_custom').prop('checked', true); FormatDatesInPreview('csv', 'csv'); }).on('keyup', function() { FormatDatesInPreview('csv', 'csv'); });
EOF
				);

				return $oPanel;
				break;


			default:
				return parent:: GetFormPart($oP, $sPartId);
		}
	}

	protected function GetSampleData($oObj, $sAttCode)
	{
		if ($sAttCode != 'id') {
			$oAttDef = MetaModel::GetAttributeDef(get_class($oObj), $sAttCode);
			if ($oAttDef instanceof AttributeDateTime) // AttributeDate is derived from AttributeDateTime
			{
				$sClass = (get_class($oAttDef) == 'AttributeDateTime') ? 'user-formatted-date-time' : 'user-formatted-date';

				return '<div class="'.$sClass.'" data-date="'.$oObj->Get($sAttCode).'">'.utils::EscapeHtml($oAttDef->GetEditValue($oObj->Get($sAttCode), $oObj)).'</div>';
			}
		}

		return '<div class="text-preview">'.utils::EscapeHtml($this->GetValue($oObj, $sAttCode)).'</div>';
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
				// and thus to convert field by field and not the whole row or file at once (see ticket N°991)
				$aData[$idx] = @iconv('UTF-8', $this->aStatusInfo['charset'].'//IGNORE//TRANSLIT', $aData[$idx]);
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
		$sExportDateTimeFormat = $this->aStatusInfo['date_format'];
		$oPrevDateTimeFormat = AttributeDateTime::GetFormat();
		$oPrevDateFormat = AttributeDate::GetFormat();
		if ($sExportDateTimeFormat !== (string)$oPrevDateTimeFormat)
		{
			// Change date & time formats
			$oDateTimeFormat = new DateTimeFormat($sExportDateTimeFormat);
			$oDateFormat = new DateTimeFormat($oDateTimeFormat->ToDateFormat());
			AttributeDateTime::SetFormat($oDateTimeFormat);
			AttributeDate::SetFormat($oDateFormat);
		}
		while($aRow = $oSet->FetchAssoc())
		{
			set_time_limit(intval($iLoopTimeLimit));
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
							$sField = $oObj->GetAsCSV($sAttCode, $this->aStatusInfo['separator'], $this->aStatusInfo['text_qualifier'], $this->bLocalizeOutput, !$this->aStatusInfo['formatted_text']);
					}
				}
				if ($this->aStatusInfo['charset'] != 'UTF-8')
				{
					// Note: due to bugs in the glibc library it's safer to call iconv on the smallest possible string
					// and thus to convert field by field and not the whole row or file at once (see ticket N°991)
					$aData[] = @iconv('UTF-8', $this->aStatusInfo['charset'].'//IGNORE//TRANSLIT', $sField);
				}
				else
				{
					$aData[] = $sField;
				}
			}
			$sData .= implode($this->aStatusInfo['separator'], $aData)."\n";
			$iCount++;
		}
		// Restore original date & time formats
		AttributeDateTime::SetFormat($oPrevDateTimeFormat);
		AttributeDate::SetFormat($oPrevDateFormat);
		set_time_limit(intval($iPreviousTimeLimit));
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
