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

namespace Combodo\iTop\Portal\Routing;


use Exception;

/**
 * Class ItopExtensionsExtraRoutes
 *
 * @package    Combodo\iTop\Portal\Routing
 * @since      2.7.0
 * @author     Bruno Da Silva <bruno.dasilva@combodo.com>
 */
class ItopExtensionsExtraRoutes
{
	/** @var array $aRoutes */
	static private $aRoutes = array();

	/**
	 * @var array $aControllersClasses
	 * @since 3.1.0
	 */
	static private $aControllersClasses = array();

	/**
	 * @param array $extraRoutes
	 *
	 * @throws Exception
	 */
	public static function AddRoutes($extraRoutes)
	{
		if (!is_array($extraRoutes)) {
			throw new Exception('Only array are allowed as parameter to '.__METHOD__);
		}

		self::$aRoutes = array_merge(self::$aRoutes, $extraRoutes);
	}

	/**
	 * @return array
	 */
	public static function GetRoutes()
	{
		return self::$aRoutes;
	}

	/**
	 * @param array $extraControllersClasses
	 *
	 * @throws Exception
	 * @since 3.1.0
	 */
	public static function AddControllersClasses($extraControllersClasses)
	{
		if (!is_array($extraControllersClasses)) {
			throw new Exception('Only array are allowed as parameter to '.__METHOD__);
		}

		self::$aControllersClasses = array_merge(self::$aControllersClasses, $extraControllersClasses);
	}

	/**
	 * @return array
	 * @since 3.1.0
	 */
	public static function GetControllersClasses()
	{
		return self::$aControllersClasses;
	}
}