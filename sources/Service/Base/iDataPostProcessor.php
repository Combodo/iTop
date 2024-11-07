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

namespace Combodo\iTop\Service\Base;

/**
 * Interface iDataPostProcessor
 *
 * @package Combodo\iTop\Service\Base
 * @since 3.1.0
 */
interface iDataPostProcessor
{
	/**
	 * Execute.
	 *
	 * @param array $aData Array of elements
	 * @param array $aSettings Array of settings
	 *
	 * @return array
	 */
	public static function Execute(array $aData, array $aSettings): array;

}