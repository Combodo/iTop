<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
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

namespace Combodo\iTop\Application\UI\Base\Layout\TabContainer;


use appUserPreferences;
use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Application\UI\Base\Layout\iUIContentBlock;
use Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab\AjaxTab;
use Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab\Tab;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Application\UI\Base\UIException;
use Dict;

/**
 * Class TabContainer
 *
 * @package Combodo\iTop\Application\UI\Base\Layout\TabContainer
 */
class TabContainer extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-tab-container';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/tab-container/layout';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'base/layouts/tab-container/layout';
	public const DEFAULT_JS_FILES_REL_PATH = [
		'js/jquery.ba-bbq.min.js',
		'node_modules/scrollmagic/scrollmagic/minified/ScrollMagic.min.js',
		'js/layouts/tab-container/tab-container.js',
		'js/layouts/tab-container/regular-tabs.js',
		'js/layouts/tab-container/scrollable-tabs.js'
	];

	// Specific constants
	/** @var string */
	public const ENUM_LAYOUT_HORIZONTAL = 'horizontal';
	/** @var string */
	public const ENUM_LAYOUT_VERTICAL = 'vertical';
	/** @var string */
	public const DEFAULT_LAYOUT = self::ENUM_LAYOUT_HORIZONTAL;
	/** @var bool */
	public const DEFAULT_SCROLLABLE = false;


	/** @var string $sName */
	private $sName;
	/** @var string $sPrefix */
	private $sPrefix;
	/** @var string $sLayout Layout of the tabs (horizontal, vertical, ...), see static::ENUM_LAYOUT_XXX */
	private $sLayout;
	/** @var bool $bIsScrollable Define if we can scroll through tabs */
	private $bIsScrollable;

	/**
	 * TabContainer constructor.
	 *
	 * @param string $sId
	 * @param string $sPrefix
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public function __construct($sId, $sPrefix)
	{
		// Intention is to pass the $sPrefix as the UIBlock ID even when no $sId is provided (eg. sometimes $sId is "")
		// But in case everything comes down to an empty string (""), we give a null to the constructor so it can generate a good one to prevent collision
		$sRealIdSeparator = (!empty($sId) && !empty($sPrefix)) ? '-' : '';
		$sRealId = $sId.$sRealIdSeparator.$sPrefix;
		if (empty($sRealId)) {
			$sRealId = null;
		}
		parent::__construct($sRealId);

		$this->sName = $sId;
		$this->sPrefix = $sPrefix;
		$this->sLayout = appUserPreferences::GetPref('tab_layout', static::DEFAULT_LAYOUT);
		$this->bIsScrollable = appUserPreferences::GetPref('tab_scrollable', static::DEFAULT_SCROLLABLE);

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
		/** @var \Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab\Tab $oTab */
		$oTab = $this->GetSubBlock($sTabCode);
		return $oTab;
	}

	/**
	 * @param string $sTabCode
	 * @param string $sTitle
	 * @param string|null $sPlaceholder
	 * @param string|null $sDescription {@see \Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab\Tab::$sDescription}
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab\Tab
	 * @throws \Combodo\iTop\Application\UI\Base\UIException
	 * @since 3.1.0 N°5920 Add $sDescription argument
	 */
	public function AddAjaxTab(string $sTabCode, string $sTitle, ?string $sPlaceholder = null, ?string $sDescription = null): Tab
	{
		if($sPlaceholder === null){
			$sPlaceholder = AjaxTab::DEFAULT_TAB_PLACEHOLDER;
		}
		$oTab = new AjaxTab($sTabCode, $sTitle, $sPlaceholder);
		$this->AddSubBlock($oTab);
		return $oTab;
	}

	/**
	 * @param string $sTabCode
	 * @param string $sTitle
	 * @param string|null $sDescription {@see \Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab\Tab::$sDescription}
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab\Tab
	 * @throws \Combodo\iTop\Application\UI\Base\UIException
	 * @since 3.1.0 N°5920 Add $sDescription argument
	 */
	public function AddTab(string $sTabCode, string $sTitle, ?string $sDescription = null): Tab
	{
		$oTab = new Tab($sTabCode, $sTitle, $sDescription);
		$this->AddSubBlock($oTab);
		return $oTab;
	}

	public function RemoveTab(string $sTabCode)
	{
		$this->RemoveSubBlock($sTabCode);
		return $this;
	}

	/**
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock $oSubBlock
	 *
	 * @return iUIContentBlock
	 * @throws \Combodo\iTop\Application\UI\Base\UIException
	 */
	public function AddSubBlock(?iUIBlock $oSubBlock): iUIContentBlock
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

	/**
	 * @param bool $bIsScrollable
	 * @return $this
	 */
	public function SetIsScrollable($bIsScrollable) {
		$this->bIsScrollable = $bIsScrollable;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function GetIsScrollable(): bool {
		return $this->bIsScrollable;
	}
}
