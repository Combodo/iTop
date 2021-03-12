<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


/**
 * Custom class derived from TCPDF for providing custom headers and footers
 *
 * @author denis
 *
 */
class iTopPDF extends TCPDF
{
	protected $sDocumentTitle;

	/**
	 * Shortcut for {@link TCPDF::SetFont}, to use the font configured
	 *
	 * @param string $style
	 * @param int $size
	 * @param string $fontfile
	 * @param string $subset
	 * @param bool $out
	 *
	 * @uses \TCPDF::SetFont()
	 * @uses \iTopPDF::GetPdfFont()
	 * @since 2.7.0
	 */
	public function SetFontParams($style, $size, $fontfile = '', $subset = 'default', $out = true)
	{
		$siTopFont = self::GetPdfFont();
		$this->SetFont($siTopFont, $style, $size, $fontfile, $subset, $out);
	}

	public function SetDocumentTitle($sDocumentTitle)
	{
		$this->sDocumentTitle = $sDocumentTitle;
	}

	/**
	 * Builds the custom header. Called for each new page.
	 *
	 * @see TCPDF::Header()
	 */
	public function Header()
	{
		// Title
		// Set font
		$this->SetFontParams('B', 10);

		$iPageNumberWidth = 25;
		$aMargins = $this->getMargins();

		// Display the title (centered)
		$this->SetXY($aMargins['left'] + $iPageNumberWidth, 0);
		$this->MultiCell($this->getPageWidth() - $aMargins['left'] - $aMargins['right'] - 2 * $iPageNumberWidth, 15, $this->sDocumentTitle,
			0, 'C', false, 0 /* $ln */, '', '', true, 0, false, true, 15, 'M' /* $valign */);
		$this->SetFontParams('', 10);

		// Display the page number (right aligned)
		// Warning: the 'R'ight alignment does not work when using placeholders like $this->getAliasNumPage() or $this->getAliasNbPages()
		$this->MultiCell($iPageNumberWidth, 15, Dict::Format('Core:BulkExport:PDF:PageNumber', $this->page), 0, 'R', false, 0 /* $ln */, '',
			'', true, 0, false, true, 15, 'M' /* $valign */);

		// Branding logo
		$sBrandingIcon = APPROOT.'images/itop-logo.png';
		if (file_exists(MODULESROOT.'branding/main-logo.png')) {
			$sBrandingIcon = MODULESROOT.'branding/main-logo.png';
		}
		$this->Image($sBrandingIcon, $aMargins['left'], 5, 0, 10);
	}

	// Page footer
	public function Footer()
	{
		// No footer
	}

	/**
	 * dejavusans is a UTF-8 Unicode font. Standard PDF fonts like helvetica or times new roman are NOT UTF-8
	 *
	 * @return string font in the config file (export_pdf_font)
	 */
	public static function GetPdfFont()
	{
		$oConfig = utils::GetConfig();
		$sPdfFont = $oConfig->Get('export_pdf_font');

		return $sPdfFont;
	}
}
