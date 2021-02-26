<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Panel;


use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;

class PanelEnhanced extends Panel
{
	public const BLOCK_CODE = 'ibo-panel-enhanced';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/panel/panelenhanced';

	/** @var UIContentBlock */
	protected $oSubTitleBlock;
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
		$this->oSubTitleBlock = new UIContentBlock();
		$this->sIconUrl = $sIconUrl;
	}

	/**
	 * @return UIContentBlock
	 */
	public function GetSubTitleBlock(): UIContentBlock
	{
		return $this->oSubTitleBlock;
	}

	/**
	 * @param \Combodo\iTop\Application\UI\Base\Layout\UIContentBlock $oSubTitleBlock
	 *
	 * @return PanelEnhanced
	 */
	public function SetSubTitleBlock(UIContentBlock $oSubTitleBlock): PanelEnhanced
	{
		$this->oSubTitleBlock = $oSubTitleBlock;
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