<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Application\WebPage;

use Combodo\iTop\Renderer\BlockRenderer;
use ExecutionKPI;


/**
 * Special class of WebPage for printing into a PDF document
 */
class PDFPage extends WebPage
{
	/** @var iTopPDF Instance of the TCPDF object for creating the PDF */
	protected $oPdf;

	public function __construct($s_title, $sPageFormat = 'A4', $sPageOrientation = 'L')
	{
		$oKpi = new ExecutionKPI();
		parent::__construct($s_title);
		if (!defined('K_PATH_FONTS')){
			define('K_PATH_FONTS', APPROOT.'lib/combodo/tcpdf/fonts/');
		}
		$this->oPdf = new iTopPDF($sPageOrientation, 'mm', $sPageFormat, true, self::PAGES_CHARSET, false);

		// set document information
		$this->oPdf->SetCreator(PDF_CREATOR);
		$this->oPdf->SetAuthor('iTop');
		$this->oPdf->SetTitle($s_title);
		$this->oPdf->SetDocumentTitle($s_title);

		$this->oPdf->setFontSubsetting(true);

		// dejavusans is a UTF-8 Unicode font. Standard PDF fonts like helvetica or times new roman are NOT UTF-8
		$this->oPdf->SetFontParams('', 10, '', true);

		// set auto page breaks
		$this->oPdf->SetAutoPageBreak(true, 15); // 15 mm break margin at the bottom
		$this->oPdf->SetTopMargin(15);

		// Add a page, we're ready to start
		$this->oPdf->AddPage();

		$this->SetContentDisposition('inline', $s_title.'.pdf');
		$this->SetDefaultStyle();
		$oKpi->ComputeStats(get_class($this).' creation', 'PDFPage');
	}

	/**
	 * Sets a default style (suitable for printing) to be included each time $this->oPdf->writeHTML() is called
	 */
	protected function SetDefaultStyle()
	{
		$this->add_style(
			<<<EOF
table {
	padding: 2pt;
}
table.ibo-datatable td, table.listResults td  {
	border: 0.5pt solid #000 ;
}
table.ibo-datatable th, table.listResults th  {
	background-color: #eee;
	border: 0.5pt solid #000 ;
}
a {
	text-decoration: none;
	color: #000;
}
table.section td {
	vertical-align: middle;
	font-size: 10pt;
	background-color:#eee;
}
td.icon {
	width: 30px;
}
h2{
	font-size: 10pt;
	vertical-align: middle;
	background-color:#eee;
	padding: 2pt;
	margin:2pt;
}
EOF
		);
	}

	/**
	 * Get access to the underlying TCPDF object
	 *
	 * @return iTopPDF
	 */
	public function get_tcpdf()
	{
		$this->flush();

		return $this->oPdf;
	}

	/**
	 * Writes the currently buffered HTML content into the PDF. This can be useful:
	 * - to sync the flow in case you want to access the underlying TCPDF object for some specific/graphic output
	 * - to process the HTML by smaller chunks instead of processing the whole page at once for performance reasons
	 */
	public function flush()
	{
		$sHtml = '';
		if (count($this->a_styles) > 0) {
			$sHtml .= "<style>\n".implode("\n", $this->a_styles)."\n</style>\n";
		}
		if (strlen($this->s_content) > 0) {
			$sHtml .= $this->s_content;
			$this->s_content = '';
		}
		$sHtml .= BlockRenderer::RenderBlockTemplates($this->oContentLayout);
		$this->oPdf->writeHTML($sHtml); // The style(s) must be supplied each time we call writeHtml
	}

	/**
	 * Whether or not the page is a PDF page
	 *
	 * @return boolean
	 */
	public function is_pdf()
	{
		return true;
	}

	/**
	 * Generates the PDF document and returns the PDF content as a string
	 *
	 * @see WebPage::output()
	 */
	public function output()
	{
		$this->add_header('Content-type: application/x-pdf');
		if (!empty($this->sContentDisposition))
		{
			$this->add_header('Content-Disposition: '.$this->sContentDisposition.'; filename="'.$this->sContentFileName.'"');
		}
		foreach ($this->a_headers as $s_header)
		{
			header($s_header);
		}
		$this->flush();
		echo $this->oPdf->Output($this->s_title.'.pdf', 'S');
	}

	public function get_pdf()
	{
		$this->flush();

		return $this->oPdf->Output($this->s_title.'.pdf', 'S');
	}
}
