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

namespace Combodo\iTop\Application\UI\Component\PopoverMenu;


use Combodo\iTop\Application\UI\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItem;
use Combodo\iTop\Application\UI\UIBlock;
use Exception;

/**
 * Class PopoverMenu
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Component\PopoverMenu
 * @internal
 * @since 2.8.0
 */
class PopoverMenu extends UIBlock
{
	// Overloaded constants
	const BLOCK_CODE = 'ibo-popover-menu';
	const HTML_TEMPLATE_REL_PATH = 'components/popover-menu/layout';
	//const JS_TEMPLATE_REl_PATH = 'components/popover-menu/layout';
	const JS_FILES_REL_PATH = [
		'js/components/popover-menu.js',
	];

	/** @var array $aSections */
	protected $aSections;

	/**
	 * PopoverMenu constructor.
	 *
	 * @param string|null $sId
	 */
	public function __construct($sId = null)
	{
		parent::__construct($sId);
		$this->aSections = [];
	}

	/**
	 * Add a section $sId if not already existing.
	 * Important: It does not reset the section.
	 *
	 * @param string $sId
	 *
	 * @return $this
	 */
	public function AddSection($sId)
	{
		if (false === $this->HasSection($sId))
		{
			$this->aSections[$sId] = [
				'aItems' => [],
			];
		}

		return $this;
	}

	/**
	 * Remove the $sId section.
	 * Note: If the section does not exist, we silently proceed anyway.
	 *
	 * @param string $sId
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function RemoveSection($sId)
	{
		if (true === $this->HasSection($sId))
		{
			unset($this->aSections[$sId]);
		}

		return $this;
	}

	/**
	 * Return true if the $sId section exists
	 *
	 * @param string $sId
	 *
	 * @return bool
	 */
	public function HasSection($sId)
	{
		return array_key_exists($sId, $this->aSections);
	}

	/**
	 * Clear the $sId section from all its items.
	 *
	 * @param string $sId
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function ClearSection($sId)
	{
		if (false === $this->HasSection($sId))
		{
			throw new Exception('Could not clear section "'.$sId.'" as it does not exist in the "'.$this->GetId().'" menu');
		}

		$this->aSections[$sId]['aItems'] = [];

		return $this;
	}

	/**
	 * Return the sections
	 *
	 * @return array
	 */
	public function GetSections()
	{
		return $this->aSections;
	}

	/**
	 * Add the $oItem in the $sSectionId. If an item with the same ID already exists it will be overwritten.
	 *
	 * @param string $sSectionId
	 * @param \Combodo\iTop\Application\UI\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItem $oItem
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function AddItem($sSectionId, PopoverMenuItem $oItem)
	{
		if(false === $this->HasSection($sSectionId))
		{
			throw new Exception('Could not add an item to the "'.$sSectionId.'" section has it does not seem to exist in the "'.$this->GetId().'" menu.');
		}

		$this->aSections[$sSectionId]['aItems'][$oItem->GetId()] = $oItem;

		return $this;
	}

	/**
	 * Remove the $sItemId from the $sSectionId.
	 * Note: If the item is not in the section, we proceed silently.
	 *
	 * @param string $sSectionId
	 * @param string $sItemId
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function RemoveItem($sSectionId, $sItemId)
	{
		if(false === $this->HasSection($sSectionId))
		{
			throw new Exception('Could not remove en item from the "'.$sSectionId.'" as it does not seem to exist in the "'.$this->GetId().'" menu.');
		}

		if(array_key_exists($sItemId, $this->aSections[$sSectionId]['aItems']))
		{
			unset($this->aSections[$sSectionId]['aItems'][$sItemId]);
		}

		return $this;
	}

	/**
	 * Set all $aItems at once in the $sSectionId, overwriting all existing.
	 *
	 * @param string $sSectionId
	 * @param PopoverMenuItem[] $aItems
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function SetItems($sSectionId, $aItems)
	{
		if(false === $this->HasSection($sSectionId))
		{
			throw new Exception('Could not set items to the "'.$sSectionId.'" section has it does not seem to exist in the "'.$this->GetId().'" menu.');
		}

		$this->aSections[$sSectionId]['aItems'] = $aItems;

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function GetSubBlocks()
	{
		$aSubBlocks = [];

		foreach($this->aSections as $sSectionId => $aSectionData)
		{
			foreach($aSectionData['aItems'] as $sItemId => $oItem)
			{
				$aSubBlocks[$sItemId] = $oItem;
			}
		}

		return $aSubBlocks;
	}
}