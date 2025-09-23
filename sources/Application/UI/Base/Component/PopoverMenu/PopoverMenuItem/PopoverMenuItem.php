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

namespace Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem;


use ApplicationPopupMenuItem;
use Combodo\iTop\Application\UI\Base\UIBlock;
use utils;

/**
 * Class PopoverMenuItem
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem
 * @internal
 * @since 3.0.0
 */
class PopoverMenuItem extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-popover-menu--item';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/popover-menu/item/layout';

	/** @var \ApplicationPopupMenuItem $oPopupMenuItem We decorate the class with the original \ApplicationPopupMenuItem as it is used among the application (backoffice, portal, extensions) and cannot be refactored without BC breaks */
	protected $oPopupMenuItem;

	/**
	 * PopoverMenuItem constructor.
	 *
	 * @param \ApplicationPopupMenuItem $oPopupMenuItem
	 */
	public function __construct(ApplicationPopupMenuItem $oPopupMenuItem)
	{
		$this->oPopupMenuItem = $oPopupMenuItem;
		parent::__construct(/* ID will be generated from $oPopupMenuItem */);
	}

	/**
	 * @inheritDoc
	 */
	protected function GenerateId(): string
	{
		return parent::GenerateId().'--'.utils::GetSafeId($this->oPopupMenuItem->GetUID());
	}

	/**
	 * @see \ApplicationPopupMenuItem::GetLabel()
	 * @return string
	 */
	public function GetLabel()
	{
		return $this->oPopupMenuItem->GetLabel();
	}

	/**
	 * @param array $aCssClasses
	 *
	 * @return $this
	 * @uses oPopupMenuItem
	 * @uses \ApplicationPopupMenuItem::SetCssClasses()
	 */
	public function SetMenuItemCssClasses(array $aCssClasses)
	{
		$this->oPopupMenuItem->SetCssClasses($aCssClasses);

		return $this;
	}

	/**
	 * @param string $sCssClass
	 *
	 * @return $this
	 * @uses oPopupMenuItem
	 * @uses \ApplicationPopupMenuItem::AddCssClass()
	 */
	public function AddMenuItemCssClass(string $sCssClass)
	{
		$this->oPopupMenuItem->AddCssClass($sCssClass);

		return $this;
	}

	/**
	 * @return array
	 * @uses oPopupMenuItem
	 * @uses \ApplicationPopupMenuItem::GetCssClasses()
	 */
	public function GetMenuItemCssClasses(): array
	{
		return $this->oPopupMenuItem->GetCssClasses();
	}
	
	/**
	 * @return string
	 * @uses oPopupMenuItem
	 * @uses \ApplicationPopupMenuItem::GetIconClass()
	 */
	public function GetIconClass()
	{
		return $this->oPopupMenuItem->GetIconClass();
	}

	/**
	 * @return $this
	 * @uses oPopupMenuItem
	 * @uses \ApplicationPopupMenuItem::SetIconClass()
	 */
	public function SetIconClass($sIconClas)
	{
		$this->oPopupMenuItem->SetIconClass($sIconClas);
		return $this;
	}

	/**
	 * @return string
	 * @uses oPopupMenuItem
	 * @uses \ApplicationPopupMenuItem::GetTooltip()
	 */
	public function GetTooltip()
	{
		return $this->oPopupMenuItem->GetTooltip();
	}
	
	/**
	 * @return $this
	 * @uses oPopupMenuItem
	 * @uses \ApplicationPopupMenuItem::SetTooltip()
	 */
	public function SetTooltip($sTooltip)
	{
		$this->oPopupMenuItem->SetTooltip($sTooltip);
		return $this;
	}


	/**
	 * @return string
	 * @uses oPopupMenuItem
	 * @uses \ApplicationPopupMenuItem::GetUID()
	 */
	public function GetUID()
	{
		return $this->oPopupMenuItem->GetUID();
	}


}