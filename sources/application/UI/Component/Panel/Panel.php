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
	 * @param array $aSubBlocks
	 * @param string $sColor
	 * @param string|null $sId
	 */
	public function __construct($sTitle = '', $aSubBlocks = [], $sColor = 'secondary', $sId = null)
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
	 * @param \Combodo\iTop\Application\UI\UIBlock $oSubBlock
	 * @return $this
	 */
	public function AddSubBlock($oSubBlock)
	{
		$this->aSubBlocks[] = $oSubBlock;
		return $this;
	}
	
	/**
	 * @param array $aSubBlocks
	 * @return $this
	 */
	public function SetSubBlocks($aSubBlocks)
	{
		$this->aSubBlocks = $aSubBlocks;
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