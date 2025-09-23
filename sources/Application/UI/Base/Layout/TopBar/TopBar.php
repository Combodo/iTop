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

namespace Combodo\iTop\Application\UI\Base\Layout\TopBar;


use Combodo\iTop\Application\UI\Base\Component\Breadcrumbs\Breadcrumbs;
use Combodo\iTop\Application\UI\Base\Component\GlobalSearch\GlobalSearch;
use Combodo\iTop\Application\UI\Base\Component\QuickCreate\QuickCreate;
use Combodo\iTop\Application\UI\Base\Component\Toolbar\Toolbar;
use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class TopBar
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Layout\TopBar
 * @internal
 * @since 3.0.0
 */
class TopBar extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-top-bar';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/top-bar/layout';

	/** @var QuickCreate|null $oQuickCreate */
	protected $oQuickCreate;
	/** @var GlobalSearch|null $oGlobalSearch */
	protected $oGlobalSearch;
	/** @var Breadcrumbs|null $oBreadcrumbs */
	protected $oBreadcrumbs;
	/** @var Toolbar|null */
	protected $oToolbar;

	/**
	 * TopBar constructor.
	 *
	 * @param string|null $sId
	 * @param \Combodo\iTop\Application\UI\Base\Component\QuickCreate\QuickCreate|null $oQuickCreate
	 * @param \Combodo\iTop\Application\UI\Base\Component\GlobalSearch\GlobalSearch|null $oGlobalSearch
	 * @param \Combodo\iTop\Application\UI\Base\Component\Breadcrumbs\Breadcrumbs|null $oBreadcrumbs
	 */
	public function __construct(
		$sId = null, QuickCreate $oQuickCreate = null, GlobalSearch $oGlobalSearch = null, Breadcrumbs $oBreadcrumbs = null
	) {
		parent::__construct($sId);

		$this->oQuickCreate = $oQuickCreate;
		$this->oGlobalSearch = $oGlobalSearch;
		$this->oBreadcrumbs = $oBreadcrumbs;
	}

	/**
	 * Set the quick create component
	 *
	 * @param \Combodo\iTop\Application\UI\Base\Component\QuickCreate\QuickCreate $oQuickCreate
	 *
	 * @return $this
	 */
	public function SetQuickCreate(QuickCreate $oQuickCreate)
	{
		$this->oQuickCreate = $oQuickCreate;
		return $this;
	}

	/**
	 * Return the global search component
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\QuickCreate\QuickCreate|null
	 */
	public function GetQuickCreate()
	{
		return $this->oQuickCreate;
	}

	/**
	 * Return true if the quick create has been set
	 *
	 * @return bool
	 */
	public function HasQuickCreate(): bool
	{
		return ($this->oQuickCreate !== null);
	}

	/**
	 * Set the global search component
	 *
	 * @param \Combodo\iTop\Application\UI\Base\Component\GlobalSearch\GlobalSearch $oGlobalSearch
	 *
	 * @return $this
	 */
	public function SetGlobalSearch(GlobalSearch $oGlobalSearch)
	{
		$this->oGlobalSearch = $oGlobalSearch;
		return $this;
	}

	/**
	 * Return the global search component
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\GlobalSearch\GlobalSearch|null
	 */
	public function GetGlobalSearch()
	{
		return $this->oGlobalSearch;
	}

	/**
	 * Return true if the global search has been set
	 *
	 * @return bool
	 */
	public function HasGlobalSearch(): bool
	{
		return ($this->oGlobalSearch !== null);
	}

	/**
	 * Set the breadcrumbs component
	 *
	 * @param \Combodo\iTop\Application\UI\Base\Component\Breadcrumbs\Breadcrumbs $oBreadcrumbs
	 *
	 * @return $this
	 */
	public function SetBreadcrumbs(Breadcrumbs $oBreadcrumbs)
	{
		$this->oBreadcrumbs = $oBreadcrumbs;
		return $this;
	}

	/**
	 * Return the breadcrumbs component
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Breadcrumbs\Breadcrumbs|null
	 */
	public function GetBreadcrumbs()
	{
		return $this->oBreadcrumbs;
	}

	/**
	 * Return true if the breadcrumb has been set
	 *
	 * @return bool
	 */
	public function HasBreadcrumbs(): bool
	{
		return ($this->oBreadcrumbs !== null);
	}

	/**
	 * @return Toolbar|null
	 */
	public function GetToolbar(): ?Toolbar
	{
		return $this->oToolbar;
	}

	/**
	 * @param Toolbar|null $oToolbar
	 *
	 * @return TopBar
	 */
	public function SetToolbar(?Toolbar $oToolbar)
	{
		$this->oToolbar = $oToolbar;
		return $this;
	}

	/**
	 * Return true if the breadcrumb has been set
	 *
	 * @return bool
	 */
	public function HasToolbar(): bool
	{
		return ($this->oToolbar !== null);
	}

	/**
	 * @inheritDoc
	 */
	public function GetSubBlocks(): array
	{
		$aSubBlocks = [];

		$aSubBlocksNames = ['QuickCreate', 'GlobalSearch', 'Breadcrumbs', 'Toolbar'];
		foreach ($aSubBlocksNames as $sSubBlockName) {
			$sHasMethodName = 'Has'.$sSubBlockName;
			if (true === call_user_func_array([$this, $sHasMethodName], [])) {
				$sPropertyName = 'o'.$sSubBlockName;
				$aSubBlocks[$this->$sPropertyName->GetId()] = $this->$sPropertyName;
			}
		}

		return $aSubBlocks;
	}
}