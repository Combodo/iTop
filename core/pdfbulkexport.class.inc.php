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
use Combodo\iTop\Application\WebPage\Page;
use Combodo\iTop\Application\WebPage\PDFPage;
use Combodo\iTop\Application\WebPage\WebPage;

/**
 * Bulk export: PDF export, based on the HTML export converted to PDF
 *
 * @copyright   Copyright (C) 2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
class PDFBulkExport extends HTMLBulkExport
{
	/**
	 * @var string For sample purposes
	 * @internal
	 * @since 2.7.8
	 */
	const ENUM_OUTPUT_TYPE_SAMPLE = 'sample';
	/**
	 * @var string For the real export
	 * @internal
	 * @since 2.7.8
	 */
	const ENUM_OUTPUT_TYPE_REAL = 'real';

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
	 * @param WebPage $oP
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
				$oFieldSetFormat->AddSubBlock($oSelectFormat);

				$aPossibleFormat = ['A3', 'A4', 'Letter'];
				$sDefaultFormat = 'A4';
				foreach ($aPossibleFormat as $sVal) {
					$oSelectFormat->AddSubBlock(SelectOptionUIBlockFactory::MakeForSelectOption($sVal, utils::EscapeHtml(Dict::S('Core:BulkExport:PageSize-'.$sVal)), ($sVal == $sDefaultFormat)));
				}
				$oFieldSetFormat->AddSubBlock(new Html('</br>'));

				$oSelectOrientation = SelectUIBlockFactory::MakeForSelectWithLabel("page_orientation", Dict::S('Core:BulkExport:PDFPageOrientation'));
				$oFieldSetFormat->AddSubBlock($oSelectOrientation);

				$aPossibleOrientation = ['P', 'L'];
				$sDefaultOrientation = 'L';
				foreach ($aPossibleOrientation as $sVal) {
					$oSelectOrientation->AddSubBlock(SelectOptionUIBlockFactory::MakeForSelectOption($sVal, utils::EscapeHtml(Dict::S('Core:BulkExport:PageOrientation-'.$sVal)), ($sVal == $sDefaultOrientation)));
				}

				//date format
				$oFieldSetDate = FieldSetUIBlockFactory::MakeStandard(Dict::S('Core:BulkExport:DateTimeFormat'));
				$oMulticolumn->AddColumn(ColumnUIBlockFactory::MakeForBlock($oFieldSetDate));

				$sDateTimeFormat = utils::ReadParam('date_format', (string)AttributeDateTime::GetFormat(), true, 'raw_data');

				$sDefaultFormat = utils::EscapeHtml((string)AttributeDateTime::GetFormat());
				$sExample = utils::EscapeHtml(date((string)AttributeDateTime::GetFormat()));
				$oRadioDefault = InputUIBlockFactory::MakeForInputWithLabel(Dict::Format('Core:BulkExport:DateTimeFormatDefault_Example', $sDefaultFormat, $sExample), "pdf_date_format_radio", "default", "pdf_date_time_format_default", "radio");
				$oRadioDefault->GetInput()->SetIsChecked(($sDateTimeFormat == (string)AttributeDateTime::GetFormat()));
				$oRadioDefault->SetBeforeInput(false);
				$oRadioDefault->GetInput()->AddCSSClass('ibo-input-checkbox');
				$oFieldSetDate->AddSubBlock($oRadioDefault);
				$oFieldSetDate->AddSubBlock(new Html('</br>'));

				$sFormatInput = '<input type="text" size="15" name="date_format" id="pdf_custom_date_time_format" title="" value="'.utils::EscapeHtml($sDateTimeFormat).'"/>';
				$oRadioCustom = InputUIBlockFactory::MakeForInputWithLabel(Dict::Format('Core:BulkExport:DateTimeFormatCustom_Format', $sFormatInput), "pdf_date_format_radio", "custom", "pdf_date_time_format_custom", "radio");
				$oRadioCustom->SetDescription(Dict::S('UI:CSVImport:CustomDateTimeFormatTooltip'));
				$oRadioCustom->GetInput()->SetIsChecked($sDateTimeFormat !== (string)AttributeDateTime::GetFormat());
				$oRadioCustom->SetBeforeInput(false);
				$oRadioCustom->GetInput()->AddCSSClass('ibo-input-checkbox');
				$oFieldSetDate->AddSubBlock($oRadioCustom);

				$oP->add_ready_script(
					<<<EOF
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
		set_time_limit(60 * 10); // 10 minutes max ???

		$oPage = new PDFPage(Dict::Format('Core:BulkExportOf_Class', MetaModel::GetName($this->oSearch->GetClass())), $this->aStatusInfo['page_size'], $this->aStatusInfo['page_orientation']);
		$oPDF = $oPage->get_tcpdf();
		$oPDF->SetFontSize(8);

		$oPage->add(file_get_contents($this->aStatusInfo['tmp_file']));
		$oPage->add($sData);

		$sPDF = $oPage->get_pdf();

		return $sPDF;
	}

	/**
	 * @inheritDoc
	 * @since 2.7.8
	 */
	protected function GetSampleData($oObj, $sAttCode)
	{
		if ($sAttCode !== 'id')
		{
			$oAttDef = MetaModel::GetAttributeDef(get_class($oObj), $sAttCode);

			// As sample data will be displayed in the web browser, AttributeImage needs to be rendered with a regular HTML format, meaning its "src" looking like "data:image/png;base64,iVBORw0KGgoAAAANSUh..."
			// Whereas for the PDF generation it needs to be rendered with a TCPPDF-compatible format, meaning its "src" looking like "@iVBORw0KGgoAAAANSUh..."
			if ($oAttDef instanceof AttributeImage) {
				return $this->GetAttributeImageValue($oObj, $sAttCode, static::ENUM_OUTPUT_TYPE_SAMPLE);
			}
		}
		return parent::GetSampleData($oObj, $sAttCode);
	}

	/**
	 * @param \DBObject $oObj
	 * @param string $sAttCode
	 *
	 * @return int|string
	 * @throws \Exception
	 */
	protected function GetValue($oObj, $sAttCode)
	{
		switch ($sAttCode) {
			case 'id':
				$sRet = parent::GetValue($oObj, $sAttCode);
				break;

			default:
				$value = $oObj->Get($sAttCode);
				if ($value instanceof ormDocument) {
					$oAttDef = MetaModel::GetAttributeDef(get_class($oObj), $sAttCode);
					if ($oAttDef instanceof AttributeImage)
					{
						$sRet = $this->GetAttributeImageValue($oObj, $sAttCode, static::ENUM_OUTPUT_TYPE_REAL);
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

	/**
	 * @param \DBObject $oObj
	 * @param string $sAttCode
	 * @param string $sOutputType {@see \PDFBulkExport::ENUM_OUTPUT_TYPE_SAMPLE}, {@see \PDFBulkExport::ENUM_OUTPUT_TYPE_REAL}
	 *
	 * @return string Rendered value of $oAttDef / $oValue according to the desired $sOutputType
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 *
	 * @since 2.7.8 N°2244 method creation
	 * @since 2.7.9 N°5588 signature change to get the object so that we can log all the needed information
	 */
	protected function GetAttributeImageValue(DBObject $oObj, string $sAttCode, string $sOutputType)
	{
		$oValue = $oObj->Get($sAttCode);
		$oAttDef = MetaModel::GetAttributeDef(get_class($oObj), $sAttCode);

		// To limit the image size in the PDF output, we have to enforce the size as height/width because max-width/max-height have no effect
		//
		$iDefaultMaxWidthPx = 48;
		$iDefaultMaxHeightPx = 48;
		if ($oValue->IsEmpty()) {
			$iNewWidth = $iDefaultMaxWidthPx;
			$iNewHeight = $iDefaultMaxHeightPx;

			$sUrl = $oAttDef->Get('default_image');
		} else {
			$iMaxWidthPx = min($iDefaultMaxWidthPx, $oAttDef->Get('display_max_width'));
			$iMaxHeightPx = min($iDefaultMaxHeightPx, $oAttDef->Get('display_max_height'));

			list($iWidth, $iHeight) = utils::GetImageSize($oValue->GetData());
			if ((is_null($iWidth)) || (is_null($iHeight)) || ($iWidth === 0) || ($iHeight === 0)) {
				// Avoid division by zero exception (SVGs, corrupted images, ...)
				$iNewWidth = $iDefaultMaxWidthPx;
				$iNewHeight = $iDefaultMaxHeightPx;

				$sAttCode = $oAttDef->GetCode();
				IssueLog::Warning('AttributeImage: Cannot read image size', LogChannels::EXPORT, [
					'ObjClass'        => get_class($oObj),
					'ObjKey'          => $oObj->GetKey(),
					'ObjFriendlyName' => $oObj->GetName(),
					'AttCode'         => $sAttCode,
				]);
			} else {
				$fScale = min($iMaxWidthPx / $iWidth, $iMaxHeightPx / $iHeight);
				$iNewWidth = $iWidth * $fScale;
				$iNewHeight = $iHeight * $fScale;
			}

			$sValueAsBase64 = base64_encode($oValue->GetData());
			switch ($sOutputType) {
				case static::ENUM_OUTPUT_TYPE_SAMPLE:
					$sUrl = 'data:'.$oValue->GetMimeType().';base64,'.$sValueAsBase64;
					break;

				case static::ENUM_OUTPUT_TYPE_REAL:
				default:
					// TCPDF requires base64-encoded images to be rendered without the usual "data:<MIMETYPE>;base64" header but with an "@"
					// @link https://tcpdf.org/examples/example_009/
					$sUrl = '@'.$sValueAsBase64;
					break;
			}
		}

		$sRet = ($sUrl !== null) ? '<img src="'.$sUrl.'" style="width: '.$iNewWidth.'px; height: '.$iNewHeight.'px;">' : '';
		$sRet = '<div class="ibo-input-image--image-view">'.$sRet.'</div>';

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
