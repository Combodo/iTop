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

namespace Combodo\iTop\Application\UI\Component\PopoverMenu\NewsroomMenu;


use Combodo\iTop\Application\UI\Component\PopoverMenu\PopoverMenu;

/**
 * Class NewsroomMenu
 *
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @package Combodo\iTop\Application\UI\Component\NewsroomMenu
 * @internal
 * @since 2.8.0
 */
class NewsroomMenu extends PopoverMenu
{
	const HTML_TEMPLATE_REL_PATH = 'components/popover-menu/newsroom-menu/layout';
	const JS_TEMPLATE_REL_PATH = 'components/popover-menu/newsroom-menu/layout';

	const JS_FILES_REL_PATH = [
		'js/components/newsroom-menu.js',
	];

	/** @var array $aParams */
	protected $aParams;
	
	public function SetParams($aParams)
	{
		$this->aParams = $aParams;
		return $this;
	}

	public function GetParams()
	{
		return json_encode($this->aParams);
	}
}