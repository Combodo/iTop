<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Component\Panel;


use Combodo\iTop\Application\UI\Layout\UIContentBlock;

class PanelEnhanced extends Panel
{
	public const BLOCK_CODE = 'ibo-panel-enhanced';
	public const HTML_TEMPLATE_REL_PATH = 'components/panel/panelenhanced';

	/** @var UIContentBlock */
	protected $sSubTitle;
	/** @var string */
	protected $sIconUrl;

	/**
	 * PanelEnhanced constructor.
	 *
	 * @param $sTitle
	 * @param $sIconUrl
	 */
	public function __construct(string $sTitle, string $sIconUrl)
	{
		parent::__construct($sTitle);
		$this->sSubTitle = new UIContentBlock();
		$this->sIconUrl = $sIconUrl;
	}

	/**
	 * @return UIContentBlock
	 */
	public function GetSubTitle(): UIContentBlock
	{
		return $this->sSubTitle;
	}

	/**
	 * @param UIContentBlock $sSubTitle
	 *
	 * @return PanelEnhanced
	 */
	public function SetSubTitle(UIContentBlock $sSubTitle): PanelEnhanced
	{
		$this->sSubTitle = $sSubTitle;
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
	 * @return PanelEnhanced
	 */
	public function SetIconUrl(string $sIconUrl): PanelEnhanced
	{
		$this->sIconUrl = $sIconUrl;
		return $this;
	}

}