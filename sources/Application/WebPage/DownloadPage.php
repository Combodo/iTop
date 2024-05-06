<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\WebPage;

use ExecutionKPI;

/**
 * Class DownloadPage
 *
 * Use it to download a file raw content (no extra / meta data from iTop)
 *
 * @api
 * @author Eric Espie <eric.espie@combodo.com>
 * @since 3.0.0
 */
class DownloadPage extends AjaxPage
{
	/** @var string */
	protected $sContent;

	/**
	 * @inheritDoc
	 */
	public function add($sContent)
	{
		$this->sContent .= $sContent;
	}

	/**
	 * @inheritDoc
	 */
	public function output()
	{
		$oKpi = new ExecutionKPI();
		if (!empty($this->sContentType)) {
			$this->add_header('Content-type: '.$this->sContentType);
		}
		if (!empty($this->sContentDisposition)) {
			$this->add_header('Content-Disposition: '.$this->sContentDisposition.'; filename="'.$this->sContentFileName.'"');
		}
		foreach ($this->a_headers as $s_header) {
			header($s_header);
		}

		if (($this->sContentType == 'text/html') && ($this->sContentDisposition == 'inline')) {
			// inline content != attachment && html => filter all scripts for malicious XSS scripts
			$sContent = self::FilterXSS($this->sContent);
		} else {
			$sContent = $this->sContent;
		}
		$oKpi->ComputeAndReport(get_class($this).' output');
		echo $sContent;
		$oKpi->ComputeAndReport('Echoing ('.round(strlen($sContent) / 1024).' Kb)');
		ExecutionKPI::ReportStats();
	}
}
