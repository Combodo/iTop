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


use JSPopupMenuItem;

/**
 * Class JsPopoverMenuItem
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Component\PopoverMenu\PopoverMenuItem
 * @property \JSPopupMenuItem $oPopupMenuItem 
 * @since 2.8.0
 */
class JsPopoverMenuItem extends PopoverMenuItem
{
	// Overloaded constants
	public const HTML_TEMPLATE_REL_PATH = 'components/popover-menu/item/mode_js';

	/**
	 * @see \JSPopupMenuItem::GetJsCode()
	 * @return string
	 */
	public function GetJsCode()
	{
		return $this->oPopupMenuItem->GetJSCode();
	}

	/**
	 * @see \JSPopupMenuItem::GetUrl()
	 * @return string
	 */
	public function GetUrl()
	{
		return $this->oPopupMenuItem->GetUrl();
	}
	
	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function GetJsFilesUrlRecursively($bAbsoluteUrl = false)
	{
		$aJsFiles = array_merge(parent::GetJsFilesUrlRecursively($bAbsoluteUrl), $this->oPopupMenuItem->GetLinkedScripts());
		return $aJsFiles;
	}
}