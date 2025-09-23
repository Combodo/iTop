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

namespace Combodo\iTop\Application\UI\Base\Component\PopoverMenu\NewsroomMenu;


use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu;

/**
 * Class NewsroomMenu
 *
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\NewsroomMenu
 * @internal
 * @since 3.0.0
 */
class NewsroomMenu extends PopoverMenu
{
	// Overloaded constants
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/popover-menu/newsroom-menu/layout';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'base/components/popover-menu/newsroom-menu/layout';
	public const DEFAULT_JS_FILES_REL_PATH = [
		'js/components/newsroom-menu.js',
	];

	/** @var array $aParams */
	protected $aParams;

	/**
	 * Set all parameters at once
	 *
	 * @param array $aParams
	 *
	 * @return $this
	 */
	public function SetParams(array $aParams)
	{
		$this->aParams = $aParams;

		return $this;
	}

	/**
	 * Return all parameters as a JSON string
	 *
	 * @return false|string
	 */
	public function GetParamsAsJson(): string
	{
		return json_encode($this->aParams);
	}
}