<?php
require_once(APPROOT.'lib/tcpdf/tcpdf.php');

/**
 * Custom class derived from TCPDF for providing custom headers and footers
 * @author denis
 *
 */
class iTopPDF extends TCPDF
{
	protected $sDocumentTitle;
	
	public function SetDocumentTitle($sDocumentTitle)
	{
		$this->sDocumentTitle = $sDocumentTitle;
	}

	/**
	 * Builds the custom header. Called for each new page.
	 * @see TCPDF::Header()
	 */
	public function Header()
	{
		// Title
		// Set font
		$this->SetFont('dejavusans', 'B', 10);
		
		$iPageNumberWidth = 25;
		$aMargins = $this->getMargins();
		
		// Display the title (centered)
		$this->SetXY($aMargins['left'] + $iPageNumberWidth, 0);
		$this->MultiCell($this->getPageWidth() - $aMargins['left'] - $aMargins['right'] - 2*$iPageNumberWidth, 15, $this->sDocumentTitle, 0, 'C', false, 0 /* $ln */, '', '', true, 0, false, true, 15, 'M' /* $valign */);
		$this->SetFont('dejavusans', '', 10);
		
		// Display the page number (right aligned)
		// Warning: the 'R'ight alignment does not work when using placeholders like $this->getAliasNumPage() or $this->getAliasNbPages()
		$this->MultiCell($iPageNumberWidth, 15, 'Page '.$this->page, 0, 'R', false, 0 /* $ln */, '', '', true, 0, false, true, 15, 'M' /* $valign */);
		
		// Branding logo
		$sBrandingIcon = APPROOT.'images/itop-logo.png';
		if (file_exists(MODULESROOT.'branding/main-logo.png'))
		{
			$sBrandingIcon = MODULESROOT.'branding/main-logo.png';
		}
		$this->Image($sBrandingIcon, $aMargins['left'], 5, 0, 10);
	}

	// Page footer
	public function Footer()
	{
		// No footer
	}
}

/**
 * Special class of WebPage for printing into a PDF document
 */
class PDFPage extends WebPage
{
	/**
	 * Instance of the TCPDF object for creating the PDF
	 * @var TCPDF
	 */
	protected $oPdf;
	
	public function __construct($s_title, $sPageFormat = 'A4', $sPageOrientation = 'L')
	{
		parent::__construct($s_title);
		define(K_PATH_FONTS, APPROOT.'lib/tcpdf/fonts');
		$this->oPdf = new iTopPDF($sPageOrientation, 'mm', $sPageFormat, true, 'UTF-8', false);
		
		// set document information
		$this->oPdf->SetCreator(PDF_CREATOR);
		$this->oPdf->SetAuthor('iTop');
		$this->oPdf->SetTitle($s_title);
		$this->oPdf->SetDocumentTitle($s_title);
		
		$this->oPdf->setFontSubsetting(true);
		
		// Set font
		// dejavusans is a UTF-8 Unicode font. Standard PDF fonts like helvetica or times new roman are NOT UTF-8
		$this->oPdf->SetFont('dejavusans', '', 10, '', true);
		
		// set auto page breaks
		$this->oPdf->SetAutoPageBreak(true, 15); // 15 mm break margin at the bottom
		$this->oPdf->SetTopMargin(15);
		
		// Add a page, we're ready to start
		$this->oPdf->AddPage();
		
		$this->SetContentDisposition('inline', $s_title.'.pdf');
		$this->SetDefaultStyle();
		
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
table.listResults td {
	border: 0.5pt solid #000 ;
}
table.listResults th {
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
EOF
		);		
	}
	
	/**
	 * Get access to the underlying TCPDF object
	 * @return TCPDF
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
		if (strlen($this->s_content) > 0)
		{
			$sHtml = '';
			if (count($this->a_styles) > 0)
			{
				$sHtml .= "<style>\n".implode("\n", $this->a_styles)."\n</style>\n";
			}
			$sHtml .= $this->s_content;
			$this->oPdf->writeHTML($sHtml); // The style(s) must be supplied each time we call writeHtml
			$this->s_content = '';
		}
	}
	
	/**
	 * Whether or not the page is a PDF page
	 * @return boolean
	 */
	public function is_pdf()
	{
		return true;
	}
	
	/**
	 * Generates the PDF document and returns the PDF content as a string
	 * @return string
	 * @see WebPage::output()
	 */
	public function output()
	{
		$this->add_header('Content-type: application/x-pdf');
    	if (!empty($this->sContentDisposition))
    	{
			$this->add_header('Content-Disposition: '.$this->sContentDisposition.'; filename="'.$this->sContentFileName.'"');
    	}
    	foreach($this->a_headers as $s_header)
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