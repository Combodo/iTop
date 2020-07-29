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

namespace Combodo\iTop\Application\UI\Layout\TopBar;


use Combodo\iTop\Application\UI\Component\Breadcrumbs\Breadcrumbs;
use Combodo\iTop\Application\UI\Component\GlobalSearch\GlobalSearch;
use Combodo\iTop\Application\UI\Component\QuickCreate\QuickCreate;
use Combodo\iTop\Application\UI\UIBlock;

/**
 * Class TopBar
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Layout\TopBar
 * @internal
 * @since 2.8.0
 */
class TopBar extends UIBlock
{
	const BLOCK_CODE = 'ibo-top-bar';

	const HTML_TEMPLATE_REL_PATH = 'layouts/top-bar/layout';

	/** @var QuickCreate|null $oQuickCreate */
	protected $oQuickCreate;
	/** @var GlobalSearch|null $oGlobalSearch */
	protected $oGlobalSearch;
	/** @var Breadcrumbs|null $oBreadcrumbs */
	protected $oBreadcrumbs;

	/**
	 * TopBar constructor.
	 *
	 * @param string $sId
	 * @param \Combodo\iTop\Application\UI\Component\QuickCreate\QuickCreate $oQuickCreate
	 * @param \Combodo\iTop\Application\UI\Component\GlobalSearch\GlobalSearch $oGlobalSearch
	 * @param \Combodo\iTop\Application\UI\Component\Breadcrumbs\Breadcrumbs $oBreadcrumbs
	 */
	public function __construct($sId = null, QuickCreate $oQuickCreate = null, GlobalSearch $oGlobalSearch = null, Breadcrumbs $oBreadcrumbs = null)
	{
		parent::__construct($sId);

		$this->oQuickCreate = $oQuickCreate;
		$this->oGlobalSearch = $oGlobalSearch;
		$this->oBreadcrumbs = $oBreadcrumbs;
	}

	/**
	 * Set the quick create component
	 *
	 * @param \Combodo\iTop\Application\UI\Component\QuickCreate\QuickCreate $oQuickCreate
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
	 * @return \Combodo\iTop\Application\UI\Component\QuickCreate\QuickCreate|null
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
	public function HasQuickCreate()
	{
		return ($this->oQuickCreate !== null);
	}

	/**
	 * Set the global search component
	 *
	 * @param \Combodo\iTop\Application\UI\Component\GlobalSearch\GlobalSearch $oGlobalSearch
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
	 * @return \Combodo\iTop\Application\UI\Component\GlobalSearch\GlobalSearch|null
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
	public function HasGlobalSearch()
	{
		return ($this->oGlobalSearch !== null);
	}

	/**
	 * Set the breadcrumbs component
	 *
	 * @param \Combodo\iTop\Application\UI\Component\Breadcrumbs\Breadcrumbs $oBreadcrumbs
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
	 * @return \Combodo\iTop\Application\UI\Component\Breadcrumbs\Breadcrumbs|null
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
	public function HasBreadcrumbs()
	{
		return ($this->oBreadcrumbs !== null);
	}

	/**
	 * @inheritDoc
	 */
	public function GetSubBlocks()
	{
		$aSubBlocks = [];

		$aSubBlocksNames = ['QuickCreate', 'GlobalSearch', 'Breadcrumbs'];
		foreach($aSubBlocksNames as $sSubBlockName)
		{
			$sHasMethodName = 'Has'.$sSubBlockName;
			if(true === call_user_func_array([$this, $sHasMethodName], []))
			{
				$sPropertyName = 'o'.$sSubBlockName;
				$aSubBlocks[$this->$sPropertyName->GetId()] = $this->$sPropertyName;
			}
		}

		return $aSubBlocks;
	}
}