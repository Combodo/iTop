<?php

// Copyright (C) 2010-2015 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

namespace Combodo\iTop\Portal\Router;

use Silex\Application;

/**
 * AbstractRouter class is where URLs are defined with their callback, parameters and constraints (assertions).
 * It allows us to have URL pattern at one place only and to generate them anywhere in the code, avoiding to maintain URLs in multiple places.
 * 
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
abstract class AbstractRouter
{
	/**
	 * List of routes for that Router.
	 *
	 * Each route is defined as an associative array and can have the following parameters :
	 * - pattern : URL pattern with its parameters names (eg: '/{sBrickId}/browse/{sBrowseMode}')
	 * - hash : String to append to the URL with an '#' (eg: 'modal-popup' will append '#modal-popup' to the above URL)
	 * - callback : Function to be called for that route, usally in a Controller. (eg: 'Combodo\\iTop\\Portal\\Controller\\CreateBrickController::DisplayAction')
	 * - bind : Unique name of the route, must not contain blanks. Usually lowercase with underscore (eg: 'p_browse_brick')
	 * - asserts : Associative array of assertions to check for the pattern parameters (eg: array(	'sBrowseMode' => 'list|tree'))
	 * - values : Associative array of default values for the pattern parameters (eg: array('sBrowseMode' => 'tree'))
	 *
	 * @var array
	 */
	static $aRoutes = array();

	/**
	 * Returns routes of the current AbstractRouter defined in $aRoutes.
	 *
	 * @return array
	 */
	static function GetRoutes()
	{
		return static::$aRoutes;
	}

	/**
	 * Returns the route named $name of the current AbstractRouter.
	 * Throws an exception if not found.
	 *
	 * @param string $name
	 * @return array
	 * @throws \Exception
	 */
	static function GetRoute($name)
	{
		$bFound = false;
		$aFoundRoute = array();

		foreach (static::$aRoutes as $aRoute)
		{
			if (isset($aRoute['bind']) && $aRoute['bind'] === $name)
			{
				$bFound = true;
				$aFoundRoute = $aRoute;
				break;
			}
		}

		if (!$bFound)
		{
			throw new \Exception('Unknown route "' . $name . '" for ' . get_class() . '');
		}

		return $aRoute;
	}

	/**
	 * Registers all routes of the current AbstractRouter to the Application $oApp.
	 *
	 * @param Application $oApp
	 * @return int Number of succesfully registered routes
	 * @throws \Exception
	 */
	static function RegisterAllRoutes(Application $oApp)
	{
		$iCounter = 0;

		foreach (static::$aRoutes as $aRoute)
		{
			// Check if we have the base parameters to register the route
			if (!isset($aRoute['pattern']) || !isset($aRoute['callback']))
			{
				throw new \Exception('Unable to register routes from ' . get_class() . ', some parameters are missing.');
			}

			// Registering base route
			$controller = $oApp->match($aRoute['pattern'], $aRoute['callback']);

			// Checking if route has optionnal parameters
			if (isset($aRoute['bind']))
			{
				$controller->bind($aRoute['bind']);
			}
			if (isset($aRoute['asserts']))
			{
				foreach ($aRoute['asserts'] as $sKey => $sValue)
				{
					$controller->assert($sKey, $sValue);
				}
			}
			if (isset($aRoute['values']))
			{
				foreach ($aRoute['values'] as $sKey => $sValue)
				{
					$controller->value($sKey, $sValue);
				}
			}

			$iCounter++;
		}

		return $iCounter;
	}

}

?>