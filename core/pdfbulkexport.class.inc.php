<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\UI\Base\Component\FieldSet\FieldSetUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectOptionUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\SelectUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\MultiColumn\Column\ColumnUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\MultiColumn\MultiColumnUIBlockFactory;

/**
 * Bulk export: PDF export, based on the HTML export converted to PDF
 *
 * @copyright   Copyright (C) 2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
class PDFBulkExport extends HTMLBulkExport
{
	public function DisplayUsage(Page $oP)
	{
		$oP->p(" * pdf format options:");
		$oP->p(" *\tfields: (mandatory) the comma separated list of field codes to export (e.g: name,org_id,service_name...).");
		$oP->p(" *\tpage_size: (optional) size of the page. One of A4, A3, Letter (default is 'A4').");
		$oP->p(" *\tpage_orientation: (optional) the orientation of the page. Either Portrait or Landscape (default is 'Portrait').");
		$oP->p(" *\tdate_format: the format to use when exporting date and time fields (default = the SQL format). e.g. 'Y-m-d H:i:s'");
	}

	public function EnumFormParts()
	{
		return array_merge(array('pdf_options' => array('pdf_options')), parent::EnumFormParts());
	}

	/**
	 * @param \WebPage $oP
	 * @param $sPartId
	 *
	 * @return UIContentBlock
	 */
	public function GetFormPart(WebPage $oP, $sPartId)
	{
		switch ($sPartId) {
			case 'pdf_options':
				$oPanel = PanelUIBlockFactory::MakeNeutral(Dict::S('Core:BulkExport:PDFOptions'));

				$oMulticolumn = MultiColumnUIBlockFactory::MakeStandard();
				$oPanel->AddSubBlock($oMulticolumn);

				$oFieldSetFormat = FieldSetUIBlockFactory::MakeStandard(Dict::S('Core:BulkExport:PDFPageFormat'));
				$oMulticolumn->AddColumn(ColumnUIBlockFactory::MakeForBlock($oFieldSetFormat));

				//page format
				$oSelectFormat = SelectUIBlockFactory::MakeForSelectWithLabel("page_size", Dict::S('Core:BulkExport:PDFPageSize'));
				$oSelectFormat->SetBeforeInput(false);
				$oSelectFormat->GetInput()->AddCSSClass('ibo-input-checkbox');
				$oFieldSetFormat->AddSubBlock($oSelectFormat);

				$aPossibleFormat = ['A3', 'A4', 'Letter'];
				$sDefaultFormat = 'A4';
				foreach ($aPossibleFormat as $sVal) {
					$oSelectFormat->GetInput()->AddSubBlock(SelectOptionUIBlockFactory::MakeForSelectOption($sVal, htmlentities(Dict::S('Core:BulkExport:PageSize-'.$sVal), ENT_QUOTES, 'UTF-8'), ($sVal == $sDefaultFormat)));
				}
				$oFieldSetFormat->AddSubBlock(new Html('</br>'));

				$oSelectOrientation = SelectUIBlockFactory::MakeForSelectWithLabel("page_size",
					Dict::S('Core:BulkExport:PDFPageOrientation'));
				$oSelectOrientation->SetBeforeInput(false);
				$oSelectOrientation->GetInput()->AddCSSClass('ibo-input-checkbox');
				$oFieldSetFormat->AddSubBlock($oSelectOrientation);

				$aPossibleOrientation = ['P', 'L'];
				$sDefaultOrientation = 'L';
				foreach ($aPossibleOrientation as $sVal) {
					$oSelectOrientation->GetInput()->AddSubBlock(SelectOptionUIBlockFactory::MakeForSelectOption($sVal, htmlentities(Dict::S('Core:BulkExport:PageOrientation-'.$sVal), ENT_QUOTES, 'UTF-8'), ($sVal == $sDefaultOrientation)));
				}

				//date format
				$oFieldSetDate = FieldSetUIBlockFactory::MakeStandard(Dict::S('Core:BulkExport:DateTimeFormat'));
				$oMulticolumn->AddColumn(ColumnUIBlockFactory::MakeForBlock($oFieldSetDate));

				$sDateTimeFormat = utils::ReadParam('date_format', (string)AttributeDateTime::GetFormat(), true, 'raw_data');

				$sDefaultFormat = htmlentities((string)AttributeDateTime::GetFormat(), ENT_QUOTES, 'UTF-8');
				$sExample = htmlentities(date((string)AttributeDateTime::GetFormat()), ENT_QUOTES, 'UTF-8');
				$oRadioDefault = InputUIBlockFactory::MakeForInputWithLabel(Dict::Format('Core:BulkExport:DateTimeFormatDefault_Example', $sDefaultFormat, $sExample), "pdf_custom_date_time_format", "default", "pdf_date_time_format_default", "radio");
				$oRadioDefault->GetInput()->SetIsChecked(($sDateTimeFormat == (string)AttributeDateTime::GetFormat()));
				$oRadioDefault->SetBeforeInput(false);
				$oRadioDefault->GetInput()->AddCSSClass('ibo-input-checkbox');
				$oFieldSetDate->AddSubBlock($oRadioDefault);
				$oFieldSetDate->AddSubBlock(new Html('</br>'));

				$sFormatInput = '<input type="text" size="15" name="date_format" id="excel_custom_date_time_format" title="" value="'.htmlentities($sDateTimeFormat, ENT_QUOTES, 'UTF-8').'"/>';
				$oRadioCustom = InputUIBlockFactory::MakeForInputWithLabel(Dict::Format('Core:BulkExport:DateTimeFormatCustom_Format', $sFormatInput), "pdf_custom_date_time_format", "custom", "pdf_date_time_format_custom", "radio");
				$oRadioCustom->GetInput()->SetIsChecked($sDateTimeFormat !== (string)AttributeDateTime::GetFormat());
				$oRadioCustom->SetBeforeInput(false);
				$oRadioCustom->GetInput()->AddCSSClass('ibo-input-checkbox');
				$oFieldSetDate->AddSubBlock($oRadioCustom);

				$sJSTooltip = json_encode('<div id="date_format_tooltip">'.Dict::S('UI:CSVImport:CustomDateTimeFormatTooltip').'</div>');
				$oP->add_ready_script(
					<<<EOF
$('#pdf_custom_date_time_format').tooltip({content: function() { return $sJSTooltip; } });
$('#form_part_pdf_options').on('preview_updated', function() { FormatDatesInPreview('pdf', 'html'); });
$('#pdf_date_time_format_default').on('click', function() { FormatDatesInPreview('pdf', 'html'); });
$('#pdf_date_time_format_custom').on('click', function() { FormatDatesInPreview('pdf', 'html'); });
$('#pdf_custom_date_time_format').on('click', function() { $('#pdf_date_time_format_custom').prop('checked', true); FormatDatesInPreview('pdf', 'html'); }).on('keyup', function() { FormatDatesInPreview('pdf', 'html'); });					
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
		$this->aStatusInfo['page_size'] = utils::ReadParam('page_size', 'A4', true, 'raw_data');
		$this->aStatusInfo['page_orientation'] = utils::ReadParam('page_orientation', 'L', true);
		
		$sDateFormatRadio = utils::ReadParam('pdf_date_format_radio', '');
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

	public function GetHeader()
	{
		$this->aStatusInfo['tmp_file'] = $this->MakeTmpFile('data');
		$sData = parent::GetHeader();
		$hFile = @fopen($this->aStatusInfo['tmp_file'], 'ab');
		if ($hFile === false)
		{
			throw new Exception('PDFBulkExport: Failed to open temporary data file: "'.$this->aStatusInfo['tmp_file'].'" for writing.');
		}
		fwrite($hFile, $sData."\n");
		fclose($hFile);
		return '';
	}

	public function GetNextChunk(&$aStatus)
	{
		$oPrevFormat = AttributeDateTime::GetFormat();
		$oPrevDateFormat = AttributeDate::GetFormat();
		$oDateTimeFormat = new DateTimeFormat($this->aStatusInfo['date_format']);
		AttributeDateTime::SetFormat($oDateTimeFormat);
		AttributeDate::SetFormat(new DateTimeFormat($oDateTimeFormat->ToDateFormat()));
		$sData = parent::GetNextChunk($aStatus);
		AttributeDateTime::SetFormat($oPrevFormat);
		AttributeDate::SetFormat($oPrevDateFormat);
		$hFile = @fopen($this->aStatusInfo['tmp_file'], 'ab');
		if ($hFile === false)
		{
			throw new Exception('PDFBulkExport: Failed to open temporary data file: "'.$this->aStatusInfo['tmp_file'].'" for writing.');
		}
		fwrite($hFile, $sData."\n");
		fclose($hFile);
		return '';
	}

	public function GetFooter()
	{
		$sData = parent::GetFooter();

		// We need a lot of time for the PDF conversion
		set_time_limit(60*10); // 10 minutes max ???
		
		require_once(APPROOT.'application/pdfpage.class.inc.php');
		$oPage = new PDFPage(Dict::Format('Core:BulkExportOf_Class', MetaModel::GetName($this->oSearch->GetClass())), $this->aStatusInfo['page_size'], $this->aStatusInfo['page_orientation']);
		$oPDF = $oPage->get_tcpdf();
		$oPDF->SetFontSize(8);

		$oPage->add(file_get_contents($this->aStatusInfo['tmp_file']));
		$oPage->add($sData);

		$sPDF = $oPage->get_pdf();

		return $sPDF;
	}

	protected function GetValue($oObj, $sAttCode)
	{
		switch($sAttCode)
		{
			case 'id':
				$sRet = parent::GetValue($oObj, $sAttCode);
				break;

			default:
				$value = $oObj->Get($sAttCode);
				if ($value instanceof ormDocument)
				{
					$oAttDef = MetaModel::GetAttributeDef(get_class($oObj), $sAttCode);
					if ($oAttDef instanceof AttributeImage)
					{
						// To limit the image size in the PDF output, we have to enforce the size as height/width because max-width/max-height have no effect
						//
						$iDefaultMaxWidthPx = 48;
						$iDefaultMaxHeightPx = 48;
						if ($value->IsEmpty())
						{
							$iNewWidth = $iDefaultMaxWidthPx;
							$iNewHeight = $iDefaultMaxHeightPx;

							$sUrl = $oAttDef->Get('default_image');
						}
						else
						{
							list($iWidth, $iHeight) = utils::GetImageSize($value->GetData());
							$iMaxWidthPx = min($iDefaultMaxWidthPx, $oAttDef->Get('display_max_width'));
							$iMaxHeightPx = min($iDefaultMaxHeightPx, $oAttDef->Get('display_max_height'));

							$fScale = min($iMaxWidthPx / $iWidth, $iMaxHeightPx / $iHeight);
							$iNewWidth = $iWidth * $fScale;
							$iNewHeight = $iHeight * $fScale;

							$sUrl = 'data:'.$value->GetMimeType().';base64,'.base64_encode($value->GetData());
						}
						$sRet = ($sUrl !== null) ? '<img src="'.$sUrl.'" style="width: '.$iNewWidth.'px; height: '.$iNewHeight.'px">' : '';
						$sRet = '<div class="ibo-input-image--image-view">'.$sRet.'</div>';
					}
					else
					{
						$sRet = parent::GetValue($oObj, $sAttCode);
					}
				}
				else
				{
					$sRet = parent::GetValue($oObj, $sAttCode);
				}
		}
		return $sRet;
	}

	public function GetSupportedFormats()
	{
		return array('pdf' => Dict::S('Core:BulkExport:PDFFormat'));
	}

	public function GetMimeType()
	{
		return 'application/x-pdf';
	}

	public function GetFileExtension()
	{
		return 'pdf';
	}
}
