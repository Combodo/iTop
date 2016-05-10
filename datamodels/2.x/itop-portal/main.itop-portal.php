<?php

// Copyright (C) 2016 Combodo SARL
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

/**
 * main.itop-portal.php
 * 
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class iTopPortalUrlMaker implements iDBObjectURLMaker
{

	public static function MakeObjectURL($sClass, $iId)
	{
		require_once APPROOT . '/lib/silex/vendor/autoload.php';
		require_once APPROOT . '/env-' . utils::GetCurrentEnvironment() . '/itop-portal-base/portal/src/providers/urlgeneratorserviceprovider.class.inc.php';
		require_once APPROOT . '/env-' . utils::GetCurrentEnvironment() . '/itop-portal-base/portal/src/helpers/urlgeneratorhelper.class.inc.php';
		require_once APPROOT . '/env-' . utils::GetCurrentEnvironment() . '/itop-portal-base/portal/src/helpers/applicationhelper.class.inc.php';

		// Using a static var allows to preserve the object through function calls
		static $oApp = null;
		static $sPortalId = null;

		// Initializing Silex app
		if ($oApp === null)
		{
			// Initializing Silex framework
			$oApp = new Silex\Application();
			// Registering optional silex components
			$oApp->register(new Combodo\iTop\Portal\Provider\UrlGeneratorServiceProvider());
			// Registering routes
			Combodo\iTop\Portal\Helper\ApplicationHelper::LoadRouters();
			Combodo\iTop\Portal\Helper\ApplicationHelper::RegisterRoutes($oApp);
			// Retrieving portal id
			$sPortalId = basename(__DIR__);
		}

		$sObjectQueryString = $oApp['url_generator']->generate('p_object_view', array('sObjectClass' => $sClass, 'sObjectId' => $iId));
		$sPortalAbsoluteUrl = utils::GetAbsoluteUrlModulePage($sPortalId, 'index.php');
		$sUrl = str_replace('?', $sObjectQueryString . '?', $sPortalAbsoluteUrl);
		
		return $sUrl;
	}

}

DBObject::RegisterURLMakerClass('portal', 'iTopPortalUrlMaker');

