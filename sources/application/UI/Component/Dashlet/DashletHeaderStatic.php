<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Component\Dashlet;


class DashletHeaderStatic extends DashletComponent
{
	public const BLOCK_CODE = 'ibo-dashlet-header-static';
	public const HTML_TEMPLATE_REL_PATH = 'components/dashlet/dashletheaderstatic';

	/** @var string */
	protected $sTitle;
	/** @var string */
	protected $sIconUrl;
	/** @var string */
	protected $sText;

	/**
	 * DashletHeaderStatic constructor.
	 *
	 * @param string $sTitle
	 * @param string $sIconUrl
	 * @param string $sText
	 */
	public function __construct(string $sId = null, string $sTitle, string $sIconUrl, string $sText = '')
	{
		parent::__construct($sId);

		$this->sTitle = $sTitle;
		$this->sIconUrl = $sIconUrl;
		$this->sText = $sText;
	}


	/**
	 * @return string
	 */
	public function GetTitle(): string
	{
		return $this->sTitle;
	}

	/**
	 * @param string $sTitle
	 *
	 * @return DashletHeaderStatic
	 */
	public function SetTitle(string $sTitle): DashletHeaderStatic
	{
		$this->sTitle = $sTitle;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetIconUrl(): string
	{
		return $this->sIconUrl;
	}

	/**
	 * @param string $sIconUrl
	 *
	 * @return DashletHeaderStatic
	 */
	public function SetIconUrl(string $sIconUrl): DashletHeaderStatic
	{
		$this->sIconUrl = $sIconUrl;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetText(): string
	{
		return $this->sText;
	}

	/**
	 * @param string $sText
	 *
	 * @return DashletHeaderStatic
	 */
	public function SetText(string $sText): DashletHeaderStatic
	{
		$this->sText = $sText;
		return $this;
	}


}