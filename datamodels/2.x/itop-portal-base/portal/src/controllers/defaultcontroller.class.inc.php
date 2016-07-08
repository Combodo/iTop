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

namespace Combodo\iTop\Portal\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Combodo\iTop\Portal\Brick\PortalBrick;

class DefaultController
{

	public function homeAction(Request $oRequest, Application $oApp)
	{
		$aData = array();

		// Rendering tiles
		$aData['aTilesRendering'] = array();
		foreach($oApp['combodo.portal.instance.conf']['bricks'] as $oBrick)
		{
			// Doing it only for tile visible on home page to avoid unnecessary rendering
			if (($oBrick->GetVisibleHome() === true) && ($oBrick->GetTileControllerAction() !== null))
			{
				$aControllerActionParts = explode('::', $oBrick->GetTileControllerAction());
				if (count($aControllerActionParts) !== 2)
				{
					$oApp->abort(500, 'Tile controller action must be of form "\Namespace\ControllerClass::FunctionName" for brick "' . $oBrick->GetId() . '"');
				}

				$sControllerName = $aControllerActionParts[0];
				$sControllerAction = $aControllerActionParts[1];

				$oController = new $sControllerName($oRequest, $oApp, $oBrick->GetId());
				$aData['aTilesRendering'][$oBrick->GetId()] = $oController->$sControllerAction($oRequest, $oApp, $oBrick->GetId());
			}
		}

		// Home page template
		$template = $oApp['combodo.portal.instance.conf']['properties']['templates']['home'];

		return $oApp['twig']->render($template, $aData);
	}

}

?>