<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class DownloadPage
{
	/** @var string */
	protected $sContentType;
	/** @var string */
	protected $sContentDisposition;
	/**@var  string */
	protected $sContent;
	/**
	 * @var string
	 */
	protected $sContentFileName;

	/**
	 * @param string $sContentType
	 *
	 * @return $this
	 */
	public function SetContentType(string $sContentType)
	{
		$this->sContentType = $sContentType;

		return $this;
	}

	/**
	 * Set the content-disposition (mime type) for the page's content
	 *
	 * @param $sDisposition string The disposition: 'inline' or 'attachment'
	 * @param $sFileName string The original name of the file
	 *
	 * @return $this
	 */
	public function SetContentDisposition($sDisposition, $sFileName)
	{
		$this->sContentDisposition = $sDisposition;
		$this->sContentFileName = $sFileName;

		return $this;
	}

	/**
	 * @param string $sContent
	 *
	 * @return $this
	 */
	public function SetContent(string $sContent)
	{
		$this->sContent = $sContent;

		return $this;
	}

	public function output()
	{
		if (!empty($this->sContentType)) {
			header('Content-type: '.$this->sContentType);
		}
		if (!empty($this->sContentDisposition)) {
			header('Content-Disposition: '.$this->sContentDisposition.'; filename="'.$this->sContentFileName.'"');
		}
		echo $this->sContent;
	}

}
