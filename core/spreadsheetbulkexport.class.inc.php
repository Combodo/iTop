<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\UI\Base\Component\FieldSet\FieldSetUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\MultiColumn\Column\ColumnUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\MultiColumn\MultiColumnUIBlockFactory;
use Combodo\iTop\Application\WebPage\Page;
use Combodo\iTop\Application\WebPage\WebPage;

/**
 * Bulk export: "spreadsheet" export: a simplified HTML export in which the date/time columns are split in two column: date AND time
 *
 * @copyright   Copyright (C) 2024 Combodo SAS
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
		$oP->p(" *\tformatted_text: set to 1 to formatted text fields with their HTML markup, 0 to remove formatting. Default is 1 (= formatted text)");
	}

	public function EnumFormParts()
	{
		return array_merge(parent::EnumFormParts(), array('spreadsheet_options' => array('no-localize'), 'interactive_fields_spreadsheet' => array('interactive_fields_spreadsheet')));
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
			case 'interactive_fields_spreadsheet':
				return $this->GetInteractiveFieldsWidget($oP, 'interactive_fields_spreadsheet');
				break;

			case 'spreadsheet_options':
				$oPanel = PanelUIBlockFactory::MakeNeutral(Dict::S('Core:BulkExport:SpreadsheetOptions'));

				$oMulticolumn = MultiColumnUIBlockFactory::MakeStandard();
				$oPanel->AddSubBlock($oMulticolumn);

				$oFieldSetFormat = FieldSetUIBlockFactory::MakeStandard(Dict::S('Core:BulkExport:TextFormat'));
				$oMulticolumn->AddColumn(ColumnUIBlockFactory::MakeForBlock($oFieldSetFormat));

				$oCheckBox = InputUIBlockFactory::MakeForInputWithLabel(Dict::S('Core:BulkExport:OptionFormattedText'), "formatted_text", "1", "spreadsheet_formatted_text", "checkbox");
				$oCheckBox->GetInput()->SetIsChecked((utils::ReadParam('formatted_text', 0) == 1));
				$oCheckBox->GetInput()->AddCSSClass('ibo-input-checkbox');
				$oCheckBox->SetBeforeInput(false);
				$oFieldSetFormat->AddSubBlock($oCheckBox);
				$oFieldSetFormat->AddSubBlock(new Html('<br>'));

				$oCheckBox = InputUIBlockFactory::MakeForInputWithLabel(Dict::S('Core:BulkExport:OptionNoLocalize'), "no_localize", "1", "spreadsheet_no_localize", "checkbox");
				$oCheckBox->GetInput()->SetIsChecked((utils::ReadParam('no_localize', 0) == 1));
				$oCheckBox->GetInput()->AddCSSClass('ibo-input-checkbox');
				$oCheckBox->SetBeforeInput(false);
				$oFieldSetFormat->AddSubBlock($oCheckBox);

				$oFieldSetDate = FieldSetUIBlockFactory::MakeStandard(Dict::S('Core:BulkExport:DateTimeFormat'));
				$oMulticolumn->AddColumn(ColumnUIBlockFactory::MakeForBlock($oFieldSetDate));

				$sDateTimeFormat = utils::ReadParam('date_format', (string)AttributeDateTime::GetFormat(), true, 'raw_data');

				$sDefaultFormat = utils::EscapeHtml((string)AttributeDateTime::GetFormat());
				$sExample = utils::EscapeHtml(date((string)AttributeDateTime::GetFormat()));
				$oRadioDefault = InputUIBlockFactory::MakeForInputWithLabel(Dict::Format('Core:BulkExport:DateTimeFormatDefault_Example', $sDefaultFormat, $sExample), "spreadsheet_date_format_radio", "default", "spreadsheet_date_time_format_default", "radio");
				$oRadioDefault->GetInput()->SetIsChecked(($sDateTimeFormat == (string)AttributeDateTime::GetFormat()));
				$oRadioDefault->GetInput()->AddCSSClass('ibo-input-checkbox');
				$oRadioDefault->SetBeforeInput(false);
				$oFieldSetDate->AddSubBlock($oRadioDefault);
				$oFieldSetDate->AddSubBlock(new Html('</br>'));

				$sFormatInput = '<input type="text" size="15" name="date_format" id="spreadsheet_custom_date_time_format" title="" value="'.utils::EscapeHtml($sDateTimeFormat).'"/>';
				$oRadioCustom = InputUIBlockFactory::MakeForInputWithLabel(Dict::Format('Core:BulkExport:DateTimeFormatCustom_Format', $sFormatInput), "spreadsheet_date_format_radio", "custom", "spreadsheet_date_time_format_custom", "radio");
				$oRadioCustom->SetDescription(Dict::S('UI:CSVImport:CustomDateTimeFormatTooltip'));
				$oRadioCustom->GetInput()->SetIsChecked($sDateTimeFormat !== (string)AttributeDateTime::GetFormat());
				$oRadioCustom->GetInput()->AddCSSClass('ibo-input-checkbox');
				$oRadioCustom->SetBeforeInput(false);
				$oFieldSetDate->AddSubBlock($oRadioCustom);

				$oP->add_ready_script(
					<<<EOF
$('#form_part_spreadsheet_options').on('preview_updated', function() { FormatDatesInPreview('spreadsheet', 'spreadsheet'); });
$('#spreadsheet_date_time_format_default').on('click', function() { FormatDatesInPreview('spreadsheet', 'spreadsheet'); });
$('#spreadsheet_date_time_format_custom').on('click', function() { FormatDatesInPreview('spreadsheet', 'spreadsheet'); });
$('#spreadsheet_custom_date_time_format').on('click', function() { $('#spreadsheet_date_time_format_custom').prop('checked', true); });
$('#spreadsheet_custom_date_time_format').on('click', function() { $('#spreadsheet_date_time_format_custom').prop('checked', true); FormatDatesInPreview('spreadsheet', 'spreadsheet'); }).on('keyup', function() { FormatDatesInPreview('spreadsheet', 'spreadsheet'); });
EOF
				);

				return $oPanel;
				break;

			default:
				return parent:: GetFormPart($oP, $sPartId);
		}
	}

	public function ReadParameters()
	{
		parent::ReadParameters();
		$this->aStatusInfo['formatted_text'] = (bool)utils::ReadParam('formatted_text', 1, true);

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

				return '<div class="'.$sClass.'" data-date="'.$oObj->Get($sAttCode).'">'.utils::EscapeHtml($oAttDef->GetEditValue($oObj->Get($sAttCode), $oObj)).'</div>';
			}
		}
		return $this->GetValue($oObj, $sAttCode);
	}

	protected function GetValue($oObj, $sAttCode)
	{
		$bFormattedText =  (array_key_exists('formatted_text', $this->aStatusInfo) ? $this->aStatusInfo['formatted_text'] : false);
		switch($sAttCode)
		{
			case 'id':
				$sRet = $oObj->GetKey();
				break;
					
			default:
				$value = $oObj->Get($sAttCode);
				$oAttDef = MetaModel::GetAttributeDef(get_class($oObj), $sAttCode);
				if ($value instanceof ormCaseLog) {
					$sRet = str_replace("\n", "<br/>", utils::EscapeHtml($value->__toString()));
				} elseif ($value instanceof ormStopWatch) {
					$sRet = $value->GetTimeSpent();
				} elseif ($value instanceof ormDocument) {
					$sRet = '';
				} elseif ($oAttDef instanceof AttributeText)
				{
					if ($bFormattedText)
					{
						// Replace paragraphs (<p...>...</p>, etc) by line breaks (<br/>) since Excel (pre-2016) splits the cells when there is a paragraph
						$sRet = static::HtmlToSpreadsheet($oObj->GetAsHTML($sAttCode));
					}
					else
					{
						$sRet = utils::HtmlToText($oObj->GetAsHTML($sAttCode));
					}
				}
				elseif ($oAttDef instanceof AttributeString)
				{
					$sRet = $oObj->GetAsHTML($sAttCode);
				}
				elseif ($oAttDef instanceof AttributeCustomFields)
				{
					// Stick to the weird implementation made in GetNextChunk
					$sRet = utils::TextToHtml($oObj->GetEditValue($sAttCode));
				}
				else {
					if ($this->bLocalizeOutput) {
						$sRet = utils::EscapeHtml($oObj->GetEditValue());
					} else {
						$sRet = utils::EscapeHtml((string)$value);
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
		$oPage->add_header("Pragma:");
		$oPage->add_header("Cache-control:");
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
		$bFormattedText =  (array_key_exists('formatted_text', $this->aStatusInfo) ? $this->aStatusInfo['formatted_text'] : false);
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
			set_time_limit(intval($iLoopTimeLimit));

			$sData .= "<tr>";
			foreach($this->aStatusInfo['fields'] as $iCol => $aFieldSpec)
			{
				$sAlias = $aFieldSpec['sAlias'];
				$sAttCode = $aFieldSpec['sAttCode'];

				$sField = '';
				/** @var \DBObject $oObj */
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
						else if (get_class($oFinalAttDef) == 'AttributeDate') {
							$sDate = $oDateFormat->Format($oObj->Get($sAttCode));
							$sData .= "<td>$sDate</td>";
						} else if ($oAttDef instanceof AttributeCaseLog) {
							$rawValue = $oObj->Get($sAttCode);
							$sField = str_replace("\n", "<br/>", utils::EscapeHtml($rawValue->__toString()));
							// Trick for Excel: treat the content as text even if it begins with an equal sign
							$sData .= "<td x:str>$sField</td>";
						} elseif ($oAttDef instanceof AttributeText) {
							if ($bFormattedText) {
								// Replace paragraphs (<p...>...</p>, etc) by line breaks (<br/>) since Excel (pre-2016) splits the cells when there is a paragraph
								$sField = static::HtmlToSpreadsheet($oObj->GetAsHTML($sAttCode));
							}
							else
							{
								// Convert to plain text
								$sField = utils::HtmlToText($oObj->GetAsHTML($sAttCode));
							}
							$sData .= "<td x:str>$sField</td>";
						}
						elseif ($oAttDef instanceof AttributeCustomFields)
						{
							// GetAsHTML returns a table that would not fit
							$sField = utils::TextToHtml($oObj->GetEditValue($sAttCode));
							$sData .= "<td x:str>$sField</td>";
						}
						else if ($oAttDef instanceof AttributeString)
						{
							$sField = $oObj->GetAsHTML($sAttCode, $this->bLocalizeOutput);
							$sData .= "<td x:str>$sField</td>";
						}
						else if ($oAttDef instanceof AttributeTagSet)
						{
							$sField = utils::HtmlEntities($oObj->GetAsCSV($sAttCode, $this->bLocalizeOutput, ''));
							$sData .= "<td x:str>$sField</td>";
						}
						else {
							$rawValue = $oObj->Get($sAttCode);
							if ($this->bLocalizeOutput) {
								$sField = utils::EscapeHtml($oFinalAttDef->GetEditValue($rawValue));
							} else {
								$sField = utils::EscapeHtml($rawValue);
							}
							$sData .= "<td>$sField</td>";
						}
				}

			}
			$sData .= "</tr>";
			$iCount++;
		}
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

	/**
	 * Cleanup all markup displayed as line breaks (except <br> tags) since this
	 * causes Excel (pre-2016) to generate extra lines in the table, thus breaking
	 * the tabular disposition of the export
	 * Note: Excel 2016 also refuses line breaks, so the only solution for this case is alas plain text
	 * @param string $sHtml The HTML to cleanup
	 * @return string The cleaned HTML
	 */
	public static function HtmlToSpreadsheet($sHtml)
	{
		if (trim(strip_tags($sHtml)) === '')
		{
			// Display this value as an empty cell in the table
			return '&nbsp;';
		}
		// The tags listed here are a subset of the whitelist defined in HTMLDOMSanitizer
		// Tags causing a visual "line break" in the displayed page (i.e. display: block) are to be replaced by a <span> followed by a <br/>
		// in order to preserve any inline style/attribute of the removed tag
		$aTagsToReplace = array(
				'pre', 'div', 'p', 'hr', 'center', 'h1', 'h2', 'h3', 'h4', 'li', 'fieldset', 'legend', 'nav', 'section', 'tr', 'caption',
		);
		// Tags to completely remove from the markup
		$aTagsToRemove = array(
				'table', 'thead', 'tbody', 'ul', 'ol', 'td', 'th',
		);

		// Remove the englobing <div class="HTML" >...</div> to prevent an extra line break
		$sHtml = preg_replace('|^<div class="HTML" >(.*)</div>$|s', '$1', $sHtml); // Must use the "s" (. matches newline) modifier
		
		foreach($aTagsToReplace as $sTag)
		{
			$sHtml = preg_replace("|<{$sTag} ?([^>]*)>|is", '<span $1>', $sHtml);
			$sHtml = preg_replace("|</{$sTag}>|i", '</span><br/>', $sHtml);
		}

		foreach($aTagsToRemove as $sTag)
		{
			$sHtml = preg_replace("|<{$sTag} ?([^>]*)>|is", '', $sHtml);
			$sHtml = preg_replace("|</{$sTag}>|i", '', $sHtml);
		}

		// Remove any trailing <br/>, if any, to prevent an extra line break
		$sHtml = preg_replace("|<br/>$|", '', $sHtml);

		return $sHtml;
	}
}
