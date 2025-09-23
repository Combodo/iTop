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


/**
 * Class UrlPopoverMenuItem
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem
 * @property \URLPopupMenuItem $oPopupMenuItem
 * @since 3.0.0
 */
class UrlPopoverMenuItem extends PopoverMenuItem
{
	// Overloaded constants
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/popover-menu/item/mode_url';

	/**
	 * @see \URLPopupMenuItem::GetUrl()
	 * @return string
	 */
	public function GetUrl()
	{
		return $this->oPopupMenuItem->GetUrl();
	}
	
	/**
	 * @see \URLPopupMenuItem::GetTarget()
	 * @return string
	 */
	public function GetTarget()
	{
		return $this->oPopupMenuItem->GetTarget();
	}
}