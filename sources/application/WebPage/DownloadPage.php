<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class DownloadPage extends AjaxPage
{
	/**@var  string */
	protected $sContent;

	/**
	 * @param string $sContent
	 *
	 */
	public function add($sContent)
	{
		$this->sContent = $sContent;
	}

	public function output()
	{
		if (!empty($this->sContentType)) {
			$this->add_header('Content-type: '.$this->sContentType);
		}
		if (!empty($this->sContentDisposition)) {
			$this->add_header('Content-Disposition: '.$this->sContentDisposition.'; filename="'.$this->sContentFileName.'"');
		}
		foreach ($this->a_headers as $s_header) {
			header($s_header);
		}

		echo $this->sContent;
	}
}
