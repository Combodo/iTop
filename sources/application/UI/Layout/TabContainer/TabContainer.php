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

namespace Combodo\iTop\Application\UI\Layout\TabContainer;


use appUserPreferences;
use Combodo\iTop\Application\UI\iUIBlock;
use Combodo\iTop\Application\UI\Layout\iUIContentBlock;
use Combodo\iTop\Application\UI\Layout\TabContainer\Tab\AjaxTab;
use Combodo\iTop\Application\UI\Layout\TabContainer\Tab\Tab;
use Combodo\iTop\Application\UI\Layout\UIContentBlock;
use Combodo\iTop\Application\UI\UIException;
use Dict;

/**
 * Class TabContainer
 *
 * @package Combodo\iTop\Application\UI\Layout\TabContainer
 */
class TabContainer extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-tab-container';
	public const HTML_TEMPLATE_REL_PATH = 'layouts/tab-container/layout';
	public const JS_TEMPLATE_REL_PATH = 'layouts/tab-container/layout';
	public const JS_FILES_REL_PATH = [
		'js/layouts/tab-container.js'
	];

	// Specific constants
	/** @var string */
	public const ENUM_LAYOUT_HORIZONTAL = 'horizontal';
	/** @var string */
	public const ENUM_LAYOUT_VERTICAL = 'vertical';
	/** @var string */
	public const DEFAULT_LAYOUT = self::ENUM_LAYOUT_HORIZONTAL;

	/** @var string $sName */
	private $sName;
	/** @var string $sPrefix */
	private $sPrefix;
	/** @var string $sLayout Layout of the tabs (horizontal, vertical, ...), see static::ENUM_LAYOUT_XXX */
	private $sLayout;

	/**
	 * TabContainer constructor.
	 *
	 * @param string $sName
	 * @param string $sPrefix
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public function __construct($sName, $sPrefix)
	{
		$sId = null;
		if (!empty($sName) || !empty($sPrefix)) {
			$sId = "{$sName}".((!empty($sPrefix)) ? "-{$sPrefix}" : "");
		}
		parent::__construct($sId);

		$this->sName = $sName;
		$this->sPrefix = $sPrefix;
		$this->sLayout = appUserPreferences::GetPref('tab_layout', static::DEFAULT_LAYOUT);
	}

	/**
	 * @param string $sTabCode
	 *
	 * @return bool
	 */
	public function TabExists(string $sTabCode): bool
	{
		return $this->HasSubBlock($sTabCode);
	}

	public function GetTab($sTabCode): ?Tab
	{
		/** @var \Combodo\iTop\Application\UI\Layout\TabContainer\Tab\Tab $oTab */
		$oTab = $this->GetSubBlock($sTabCode);
		return $oTab;
	}

	/**
	 * @param string $sTabCode
	 * @param string $sTitle
	 *
	 * @return \Combodo\iTop\Application\UI\Layout\TabContainer\Tab\Tab
	 * @throws \Combodo\iTop\Application\UI\UIException
	 */
	public function AddAjaxTab(string $sTabCode, string $sTitle): Tab
	{
		$oTab = new AjaxTab($sTabCode, $sTitle);
		$this->AddSubBlock($oTab);
		return $oTab;
	}

	/**
	 * @param string $sTabCode
	 * @param string $sTitle
	 *
	 * @return \Combodo\iTop\Application\UI\Layout\TabContainer\Tab\Tab
	 * @throws \Combodo\iTop\Application\UI\UIException
	 */
	public function AddTab(string $sTabCode, string $sTitle): Tab
	{
		$oTab = new Tab($sTabCode, $sTitle);
		$this->AddSubBlock($oTab);
		return $oTab;
	}

	public function RemoveTab(string $sTabCode): self
	{
		$this->RemoveSubBlock($sTabCode);
		return $this;
	}

	/**
	 * @param \Combodo\iTop\Application\UI\iUIBlock $oSubBlock
	 *
	 * @return iUIContentBlock
	 * @throws \Combodo\iTop\Application\UI\UIException
	 */
	public function AddSubBlock(iUIBlock $oSubBlock): iUIContentBlock
	{
		if (!($oSubBlock instanceof Tab)) {
			throw new UIException($this, Dict::Format('UIBlock:Error:AddBlockNotTabForbidden', $oSubBlock->GetId(), $this->GetId()));
		}
		return parent::AddSubBlock($oSubBlock);
	}

	/**
	 * Return tab list
	 *
	 * @return array
	 */
	public function Get(): array
	{
		$aTabs = [];

		foreach ($this->GetSubBlocks() as $oTab) {
			$aTabs[] = $oTab->GetParameters();
		}

		return [
			'sBlockId' => $this->GetId(),
			'aTabs' => $aTabs
		];
	}

	/**
	 * @param string $sLayout
	 *
	 * @return $this
	 */
	public function SetLayout($sLayout) {
		$this->sLayout = $sLayout;

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetLayout(): string {
		return $this->sLayout;
	}
}
