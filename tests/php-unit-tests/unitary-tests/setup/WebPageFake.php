<?php

class WebPageFake extends WebPage
{
	public string $sContent = '';

	public function __construct(string $s_title = '', bool $bPrintable = false)
	{
		//parent::__construct($s_title,$bPrintable);
	}

	public function add($sContent)
	{
		$this->sContent .= $sContent;
	}
}
