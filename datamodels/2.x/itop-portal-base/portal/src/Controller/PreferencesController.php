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

namespace Combodo\iTop\Portal\Controller;

use appUserPreferences;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PreferencesController
 *
 * @package Combodo\iTop\Portal\Controller
 * @since   3.3.0
 */
class PreferencesController extends SymfonyAbstractController
{

	/**
	 * Set a preference for the current user.
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 *
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function SetPreferenceAction(Request $oRequest): JsonResponse
	{
		$sStatus = 'success';

		// retrieve the parameters from the request
		$sKey = $oRequest->request->get('key');
		$sValue = $oRequest->request->get('value');

		// set user preference
		try{
			appUserPreferences::SetPref($sKey, $sValue);
		}
		catch(Exception){
			$sStatus = 'error';
		}

		return new JsonResponse([
			'status' => $sStatus,
		]);
	}
}
