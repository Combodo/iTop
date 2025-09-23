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

namespace Combodo\iTop\Portal\DependencyInjection\SilexCompatBootstrap\PortalXmlConfiguration;

use ModuleDesign;

/**
 * Class AbstractConfiguration
 *
 * @package Combodo\iTop\Portal\DependencyInjection\SilexCompatBootstrap\PortalXmlConfiguration
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since   2.7.0
 */
class AbstractConfiguration
{
	/** @var \ModuleDesign $oModuleDesign */
	private $oModuleDesign;

	/**
	 * AbstractConfiguration constructor.
	 *
	 * @param \ModuleDesign $oModuleDesign
	 */
	public function __construct(ModuleDesign $oModuleDesign)
	{
		$this->oModuleDesign = $oModuleDesign;
	}

	/**
	 * @return \ModuleDesign
	 */
	public function GetModuleDesign()
	{
		return $this->oModuleDesign;
	}

}