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


namespace Combodo\iTop\Portal\Helper;

/**
 * Class BrickControllerHelper
 *
 * @package Combodo\iTop\Portal\Helper
 * @since   2.7.0
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class BrickControllerHelper
{
	/** @var \Combodo\iTop\Portal\Helper\RequestManipulatorHelper $oRequestManipulator */
	private $oRequestManipulator;

	/**
	 * BrowseBrickHelper constructor.
	 *
	 * @param \Combodo\iTop\Portal\Helper\RequestManipulatorHelper $oRequestManipulator
	 */
	public function __construct(RequestManipulatorHelper $oRequestManipulator)
	{
		$this->oRequestManipulator = $oRequestManipulator;
	}

	/**
	 * Extract sort params from request and convert them to iTop OQL format
	 *
	 * @return array
	 *
	 * @since 2.7.0
	 */
	public function ExtractSortParams()
	{
		// Getting sort params
		$aSortParams = $this->oRequestManipulator->ReadParam('aSortParams', array(), FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY);

		// Converting sort direction to proper format for DBObjectSet as it only accept real booleans
		foreach ($aSortParams as $sAttributeAlias => $sDirection)
		{
			$aSortParams[$sAttributeAlias] = ($sDirection === 'true');
		}

		return $aSortParams;
	}
}