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

namespace Combodo\iTop\Application\UI\Component\Alert\Alert;



use Combodo\iTop\Application\UI\UIBlock;

/**
 * Class Alert
 *
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @package Combodo\iTop\Application\UI\Component\Alert\Alert
 * @since 2.8.0
 */
class Alert extends UIBlock
{
	// Overloaded constants
	const BLOCK_CODE = 'ibo-alert';
	const HTML_TEMPLATE_REL_PATH = 'components/alert/layout';
	const JS_TEMPLATE_REL_PATH = 'components/alert/layout';

	/** @var string $sTitle */
	protected $sTitle;
	/** @var array $sMainText */
	protected $sMainText;
	/** @var string $sColor */
	protected $sColor;

	/**
	 * Alert constructor.
	 *
	 * @param string $sId
	 * @param string $sTitle
	 * @param string $sMainText
	 * @param string $sColor
	 */
	public function __construct($sId, $sTitle = '', $sMainText = '', $sColor = 'secondary')
	{
		$this->sTitle = $sTitle;
		$this->sMainText = $sMainText;
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
	 * @return string
	 */
	public function GetMainText()
	{
		return $this->sMainText;
	}

	/**
	 * @param string $aMainText
	 * @return $this
	 */
	public function SetMainText($aMainText)
	{
		$this->sMainText = $aMainText;
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