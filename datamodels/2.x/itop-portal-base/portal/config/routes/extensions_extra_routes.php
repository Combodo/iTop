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

use Combodo\iTop\Portal\Routing\ItopExtensionsExtraRoutes;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$oRouteCollection = new RouteCollection();

$aRoutes = ItopExtensionsExtraRoutes::GetRoutes();
foreach ($aRoutes as $aRoute)
{
	$aRoute['values'] = (isset($aRoute['values'])) ? $aRoute['values'] : [];
	$aRoute['asserts'] = (isset($aRoute['asserts'])) ? $aRoute['asserts'] : [];

	$oRouteCollection->add(
		$aRoute['bind'],
		new Route(
			$aRoute['pattern'],
			array_merge(
				['_controller' => $aRoute['callback']],
				$aRoute['values']
			),
			$aRoute['asserts']
		)
	);
}

return $oRouteCollection;