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

require_once(APPROOT.'application/pdfpage.class.inc.php');

class PDFBulkExport extends HTMLBulkExport
{
	public function DisplayUsage(Page $oP)
	{
		$oP->p(" * pdf format options:");
		$oP->p(" *\tfields: (mandatory) the comma separated list of field codes to export (e.g: name,org_id,service_name...).");
		$oP->p(" *\tpage_size: (optional) size of the page. One of A4, A3, Letter (default is 'A4').");
		$oP->p(" *\tpage_orientation: (optional) the orientation of the page. Either Portrait or Landscape (default is 'Portrait').");
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
				$oP->add('<table>');
				$oP->add('<tr>');
				$oP->add('<td>'.Dict::S('Core:BulkExport:PDFPageSize').'</td>');
				$oP->add('<td>'.$this->GetSelectCtrl('page_size', array('A3', 'A4', 'Letter'), 'Core:BulkExport:PageSize-', 'A4').'</td>');
				$oP->add('</tr>');
				$oP->add('<td>'.Dict::S('Core:BulkExport:PDFPageOrientation').'</td>');
				$oP->add('<td>'.$this->GetSelectCtrl('page_orientation', array('P', 'L'), 'Core:BulkExport:PageOrientation-', 'L').'</td>');
				$oP->add('</tr>');
				$oP->add('</table>');

				$oP->add('</fieldset>');
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
		$sData = parent::GetNextChunk($aStatus);
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

		$oPage = new PDFPage(Dict::Format('Core:BulkExportOf_Class', MetaModel::GetName($this->oSearch->GetClass())), $this->aStatusInfo['page_size'], $this->aStatusInfo['page_orientation']);
		$oPDF = $oPage->get_tcpdf();
		$oPDF->SetFont('dejavusans', '', 8, '', true);

		$oPage->add(file_get_contents($this->aStatusInfo['tmp_file']));
		$oPage->add($sData);

		$sPDF = $oPage->get_pdf();

		return $sPDF;
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
