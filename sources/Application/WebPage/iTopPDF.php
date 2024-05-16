<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\WebPage;

use Combodo\iTop\Application\Branding;
use Dict;
use TCPDF;
use TCPDF_IMAGES;
use utils;


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
	 * @uses iTopPDF::GetPdfFont()
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
	 * Add image
	 *
	 * @param string $sImagePath Name of the SVG file or a '@' character followed by the SVG data string.
	 * @param $x (float) Abscissa of the upper-left corner.
	 * @param $y (float) Ordinate of the upper-left corner.
	 * @param $w (float) Width of the image in the page. If not specified or equal to zero, it is automatically calculated.
	 * @param $h (float) Height of the image in the page. If not specified or equal to zero, it is automatically calculated.
	 */
	public function AddImage($sImagePath, $x = '', $y = '', $w = 0, $h = 0)
	{
		/*if (endsWith(strtolower($sImagePath), ".svg")) {
			$this->ImageSVG($sImagePath, $x, $y, $w, $h);
		} else {
			$this->Image($sImagePath, $x, $y, $w, $h);
		}*/
		$imgtype = TCPDF_IMAGES::getImageFileType($sImagePath);
		if (($imgtype == 'eps') or ($imgtype == 'ai')) {
			$this->ImageEps($sImagePath, $x, $y, $w, $h);;
		} elseif ($imgtype == 'svg') {
			$this->ImageSVG($sImagePath, $x, $y, $w, $h);;
		} else {
			$this->Image($sImagePath, $x, $y, $w, $h);;
		}
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
		$sBrandingIcon = Branding::GetLogoRelativePath(Branding::ENUM_LOGO_TYPE_MAIN_LOGO_FULL);

		$this->AddImage($sBrandingIcon, $aMargins['left'], 5, 0, 10);
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
