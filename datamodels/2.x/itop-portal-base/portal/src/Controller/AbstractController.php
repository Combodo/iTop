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
	 * @param string $sRouteName
	 * @param array  $aRouteParams
	 * @param array  $aQueryParameters
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
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
	 */
	protected function GetControllerNameFromRoute($sRouteName)
	{
		$oRouteCollection = $this->get('router')->getRouteCollection();
		$aRouteDefaults = $oRouteCollection->get($sRouteName)->getDefaults();

		return $aRouteDefaults['_controller'];
	}
}
