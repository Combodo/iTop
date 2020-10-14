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

namespace Combodo\iTop\Application\UI\Component\PopoverMenu\PopoverMenuItem;


use ApplicationPopupMenuItem;
use Combodo\iTop\Application\UI\UIBlock;

/**
 * Class PopoverMenuItem
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Component\PopoverMenu\PopoverMenuItem
 * @internal
 * @since 3.0.0
 */
class PopoverMenuItem extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-popover-menu--item';
	public const HTML_TEMPLATE_REL_PATH = 'components/popover-menu/item/layout';

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
	protected function GenerateId()
	{
		return static::BLOCK_CODE.'-'.$this->oPopupMenuItem->GetUID();
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
	 * @see \ApplicationPopupMenuItem::SetCssClasses()
	 *
	 * @param array $aCssClasses
	 *
	 * @return $this
	 */
	public function SetCssClasses(array $aCssClasses)
	{
		$this->oPopupMenuItem->SetCssClasses($aCssClasses);

		return $this;
	}

	/**
	 * @see \ApplicationPopupMenuItem::AddCssClass()
	 *
	 * @param string $sCssClass
	 *
	 * @return $this
	 */
	public function AddCssClass(string $sCssClass)
	{
		$this->oPopupMenuItem->AddCssClass($sCssClass);

		return $this;
	}

	/**
	 * @see \ApplicationPopupMenuItem::GetCssClasses()
	 * @return array
	 */
	public function GetCssClasses()
	{
		return $this->oPopupMenuItem->GetCssClasses();
	}
}