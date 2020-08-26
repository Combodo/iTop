<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Application\UI\Component\Panel;


use Combodo\iTop\Application\UI\UIBlock;

/**
 * Class Panel
 *
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @package Combodo\iTop\Application\UI\Component\Panel
 * @since 2.8.0
 */
class Panel extends UIBlock
{
	// Overloaded constants
	const BLOCK_CODE = 'ibo-panel';
	const HTML_TEMPLATE_REL_PATH = 'components/panel/layout';
	const JS_TEMPLATE_REL_PATH = 'components/panel/layout';

	// Specific constants
	/** @var string ENUM_COLOR_PRIMARY */
	const ENUM_COLOR_PRIMARY = 'primary';
	/** @var string ENUM_COLOR_SECONDARY */
	const ENUM_COLOR_SECONDARY = 'secondary';

	/** @var string ENUM_COLOR_NEUTRAL */
	const ENUM_COLOR_NEUTRAL = 'neutral';
	/** @var string ENUM_COLOR_INFORMATION */
	const ENUM_COLOR_INFORMATION = 'information';
	/** @var string ENUM_COLOR_SUCCESS */
	const ENUM_COLOR_SUCCESS = 'success';
	/** @var string ENUM_COLOR_FAILURE */
	const ENUM_COLOR_FAILURE = 'failure';
	/** @var string ENUM_COLOR_WARNING */
	const ENUM_COLOR_WARNING = 'warning';
	/** @var string ENUM_COLOR_DANGER */
	const ENUM_COLOR_DANGER = 'danger';

	/** @var string ENUM_COLOR_GREY */
	const ENUM_COLOR_GREY = 'grey';
	/** @var string ENUM_COLOR_BLUEGREY */
	const ENUM_COLOR_BLUEGREY = 'blue-grey';
	/** @var string ENUM_COLOR_BLUE */
	const ENUM_COLOR_BLUE = 'blue';
	/** @var string ENUM_COLOR_CYAN */
	const ENUM_COLOR_CYAN = 'cyan';
	/** @var string ENUM_COLOR_GREEN */
	const ENUM_COLOR_GREEN = 'green';
	/** @var string ENUM_COLOR_ORANGE */
	const ENUM_COLOR_ORANGE = 'orange';
	/** @var string ENUM_COLOR_RED */
	const ENUM_COLOR_RED = 'red';
	/** @var string ENUM_COLOR_PINK */
	const ENUM_COLOR_PINK = 'pink';

	/** @var string DEFAULT_COLOR */
	const DEFAULT_COLOR = self::ENUM_COLOR_NEUTRAL;

	/** @var string $sTitle */
	protected $sTitle;
	/** @var array $aSubBlocks */
	protected $aSubBlocks;
	/** @var string $sColor */
	protected $sColor;

	/**
	 * Panel constructor.
	 *
	 * @param string $sTitle
	 * @param \Combodo\iTop\Application\UI\iUIBlock[] $aSubBlocks
	 * @param string $sColor
	 * @param string|null $sId
	 */
	public function __construct($sTitle = '', $aSubBlocks = [], $sColor = self::DEFAULT_COLOR, $sId = null)
	{
		$this->sTitle = $sTitle;
		$this->aSubBlocks = $aSubBlocks;
		$this->sColor = $sColor;
		parent::__construct($sId);
	}

	/**
	 * @return string
	 */
	public function GetTitle()
	{
		return $this->sTitle;
	}

	/**
	 * @param string $sTitle
	 * @return $this
	 */
	public function SetTitle($sTitle)
	{
		$this->sTitle = $sTitle;

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function GetSubBlocks()
	{
		return $this->aSubBlocks;
	}

	/**
	 * Set all sub blocks at once, replacing all existing ones
	 *
	 * @param \Combodo\iTop\Application\UI\iUIBlock[] $aSubBlocks
	 *
	 * @return $this
	 */
	public function SetSubBlocks($aSubBlocks)
	{
		foreach ($aSubBlocks as $oSubBlock)
		{
			$this->AddSubBlock($oSubBlock);
		}

		return $this;
	}

	/**
	 * Add $oSubBlock, replacing any block with the same ID
	 *
	 * @param \Combodo\iTop\Application\UI\iUIBlock $oSubBlock
	 *
	 * @return $this
	 */
	public function AddSubBlock($oSubBlock)
	{
		$this->aSubBlocks[$oSubBlock->GetId()] = $oSubBlock;

		return $this;
	}

	/**
	 * Remove the sub block identified by $sId.
	 * Note that if no sub block matches the ID, it proceeds silently.
	 *
	 * @param string $sId ID of the sub block to remove
	 *
	 * @return $this
	 */
	public function RemoveSubBlock($sId)
	{
		if (array_key_exists($sId, $this->aSubBlocks))
		{
			unset($this->aSubBlocks[$sId]);
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetColor()
	{
		return $this->sColor;
	}

	/**
	 * @param string $sColor
	 * @return $this
	 */
	public function SetColor($sColor)
	{
		$this->sColor = $sColor;
		return $this;
	}
}