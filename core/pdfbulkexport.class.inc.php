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
 * Bulk export: PDF export, based on the HTML export converted to PDF
 *
 * @copyright   Copyright (C) 2015 Combodo SARL
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

	public function DisplayFormPart(WebPage $oP, $sPartId)
	{
		switch($sPartId)
		{
			case 'pdf_options':
				$oP->add('<fieldset><legend>'.Dict::S('Core:BulkExport:PDFOptions').'</legend>');
				$oP->add('<table class="export_parameters"><tr><td style="vertical-align:top">');
				$oP->add('<h3>'.Dict::S('Core:BulkExport:PDFPageFormat').'</h3>');
				$oP->add('<table>');
				$oP->add('<tr>');
				$oP->add('<td>'.Dict::S('Core:BulkExport:PDFPageSize').'</td>');
				$oP->add('<td>'.$this->GetSelectCtrl('page_size', array('A3', 'A4', 'Letter'), 'Core:BulkExport:PageSize-', 'A4').'</td>');
				$oP->add('</tr>');
				$oP->add('<td>'.Dict::S('Core:BulkExport:PDFPageOrientation').'</td>');
				$oP->add('<td>'.$this->GetSelectCtrl('page_orientation', array('P', 'L'), 'Core:BulkExport:PageOrientation-', 'L').'</td>');
				$oP->add('</tr>');
				$oP->add('</table>');
				
				$oP->add('</td><td style="vertical-align:top">');
				
				$sDateTimeFormat = utils::ReadParam('date_format', (string)AttributeDateTime::GetFormat(), true, 'raw_data');
				$sDefaultChecked = ($sDateTimeFormat == (string)AttributeDateTime::GetFormat()) ? ' checked' : '';
				$sCustomChecked = ($sDateTimeFormat !== (string)AttributeDateTime::GetFormat()) ? ' checked' : '';
				$oP->add('<h3>'.Dict::S('Core:BulkExport:DateTimeFormat').'</h3>');
				$sDefaultFormat = htmlentities((string)AttributeDateTime::GetFormat(), ENT_QUOTES, 'UTF-8');
				$sExample = htmlentities(date((string)AttributeDateTime::GetFormat()), ENT_QUOTES, 'UTF-8');
				$oP->add('<input type="radio" id="pdf_date_time_format_default" name="pdf_date_format_radio" value="default"'.$sDefaultChecked.'><label for="pdf_date_time_format_default"> '.Dict::Format('Core:BulkExport:DateTimeFormatDefault_Example', $sDefaultFormat, $sExample).'</label><br/>');
				$sFormatInput = '<input type="text" size="15" name="date_format" id="pdf_custom_date_time_format" title="" value="'.htmlentities($sDateTimeFormat, ENT_QUOTES, 'UTF-8').'"/>';
				$oP->add('<input type="radio" id="pdf_date_time_format_custom" name="pdf_date_format_radio" value="custom"'.$sCustomChecked.'><label for="pdf_date_time_format_custom"> '.Dict::Format('Core:BulkExport:DateTimeFormatCustom_Format', $sFormatInput).'</label>');
				
				$oP->add('</td></tr></table>');
				
				
				$oP->add('</fieldset>');
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
				break;
					
			default:
				return parent:: DisplayFormPart($oP, $sPartId);
		}
	}

	protected function GetSelectCtrl($sName, $aValues, $sDictPrefix, $sDefaultValue)
	{
		$sCurrentValue = utils::ReadParam($sName, $sDefaultValue, false, 'raw_data');
		$aLabels = array();
		foreach($aValues as $sVal)
		{
			$aLabels[$sVal] = Dict::S($sDictPrefix.$sVal);
		}
		asort($aLabels);

		$sHtml = '<select name="'.$sName.'">';
		foreach($aLabels as $sVal => $sLabel)
		{
			$sSelected = ($sVal == $sCurrentValue) ? 'selected' : '';
			$sHtml .= '<option value="'.$sVal.'" '.$sSelected.'>'.htmlentities($sLabel, ENT_QUOTES, 'UTF-8').'</option>';
		}
		$sHtml .= '</select>';
		return $sHtml;
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
						$sRet = '<div class="view-image">'.$sRet.'</div>';
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
