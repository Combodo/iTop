<?php
/**
 * Copyright (C) 2013-2019 Combodo SARL
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
 *
 *
 */

/**
 * Created by Bruno DA SILVA, working for Combodo
 * Date: 31/01/19
 * Time: 16:44
 */

namespace Combodo\iTop\Portal\Routing;


use Exception;

/**
 * Class ItopExtensionsExtraRoutes
 *
 * @deprecated Compatibility layer for migrating brick's routes to iTop 2.7+
 * @package Combodo\iTop\Portal\Routing
 */
class ItopExtensionsExtraRoutes
{
    static private $routes = array();

    /**
     * @deprecated Since 2.7.0
     *
     * @param array $extraRoutes
     *
     * @throws Exception
     */
    public static function addRoutes($extraRoutes)
    {
	    @trigger_error(
		    sprintf(
			    'Usage of legacy route "%s" is deprecated. You should declare routes in YAML format.',
			    __FILE__
		    ),
		    E_USER_DEPRECATED
	    );

        if (!is_array($extraRoutes)) {
            throw new Exception('Only array are allowed as parameter to '.__METHOD__);
        }

        self::$routes = array_merge(self::$routes, $extraRoutes);
    }

    public static function getRoutes()
    {
        return self::$routes;
    }
}