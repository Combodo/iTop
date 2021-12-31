<?php

/**
 * Copyright (C) 2013-2021 Combodo SARL
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

namespace Combodo\iTop\Portal\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class AbstractController
 *
 * @package Combodo\iTop\Portal\Controller
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since   2.3.0
 */
abstract class AbstractController extends Controller
{
	/**
	 * Unlike {@see \Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait::redirectToRoute()}, this method directly calls the route controller without creating a redirection client side
	 *
	 * Default route params will be preserved (see N°4356)
	 *
	 * @param string $sRouteName
	 * @param array $aRouteParams
	 * @param array $aQueryParameters
	 * @param bool $bPreserveDefaultRouteParams if true will merge in aRouteParams the default parameters defined for the specified route
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @since 2.7.6 3.0.0 N°4356 method creation
	 */
	protected function ForwardToRoute($sRouteName, $aRouteParams, $aQueryParameters, $bPreserveDefaultRouteParams = true)
	{
		$oRouteCollection = $this->get('router')->getRouteCollection();
		$aRouteDefaults = $oRouteCollection->get($sRouteName)->getDefaults();

		if ($bPreserveDefaultRouteParams) {
			$aRouteParams = array_merge($aRouteDefaults, $aRouteParams);
		}

		return $this->forward($aRouteDefaults['_controller'], $aRouteParams, $aQueryParameters);
	}

	/**
	 * @param string $sRouteName
	 * @param array  $aRouteParams
	 * @param array  $aQueryParameters
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @deprecated 2.7.6 N°4356 use {@see ForwardToRoute} instead !
	 */
	protected function ForwardFromRoute($sRouteName, $aRouteParams, $aQueryParameters)
	{
		return $this->forward($this->GetControllerNameFromRoute($sRouteName), $aRouteParams, $aQueryParameters);
	}

	/**
	 * Returns a string containing the controller and action name of a specific route, typically used for request forwarding.
	 *
	 * Example: 'p_object_create' returns 'Combodo\iTop\Portal\Controller\ObjectController::CreateAction'
	 *
	 * @param string $sRouteName
	 *
	 * @return string
	 *
	 * @deprecated 2.7.6 N°4356 use {@see ForwardToRoute} instead !
	 */
	protected function GetControllerNameFromRoute($sRouteName)
	{
		$oRouteCollection = $this->get('router')->getRouteCollection();
		$aRouteDefaults = $oRouteCollection->get($sRouteName)->getDefaults();

		return $aRouteDefaults['_controller'];
	}
}
