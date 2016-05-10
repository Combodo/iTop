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

use \UserRights;
use \Silex\Application;
use \Symfony\Component\HttpFoundation\Request;
use \Combodo\iTop\Portal\Helper\ApplicationHelper;
use \Combodo\iTop\Portal\Brick\UserProfileBrick;
use \Combodo\iTop\Portal\Controller\ObjectController;

class UserProfileBrickController extends BrickController
{

	public function DisplayAction(Request $oRequest, Application $oApp, $sBrickId)
	{
		// If the brick id was not specified, we get the first one registered that is an instance of UserProfileBrick as default
		if ($sBrickId === null)
		{
			foreach ($oApp['combodo.portal.instance.conf']['bricks'] as $oTmpBrick)
			{
				if ($oTmpBrick instanceof UserProfileBrick)
				{
					$oBrick = $oTmpBrick;
				}
			}

			// We make sure a UserProfileBrick was found
			if (!isset($oBrick) || $oBrick === null)
			{
				$oBrick = new UserProfileBrick();
				//$oApp->abort(500, 'UserProfileBrick : Brick could not be loaded as there was no UserProfileBrick loaded in the application.');
			}
		}
		else
		{
			$oBrick = ApplicationHelper::GetLoadedBrickFromId($oApp, $sBrickId);
		}

		$aData = array();

		// Retrieving current contact
		$oCurContact = UserRights::GetContactObject();
		$sCurContactClass = get_class($oCurContact);
		$sCurContactId = $oCurContact->GetKey();
		
		// Preparing contact form
		$aData['forms']['contact'] = ObjectController::HandleForm($oRequest, $oApp, ObjectController::ENUM_MODE_EDIT, $sCurContactClass, $sCurContactId);
//		var_dump($aData['forms']['contact']);
//		die();

		$aData = $aData + array(
			'oBrick' => $oBrick
		);

		return $oApp['twig']->render($oBrick->GetPageTemplatePath(), $aData);
	}

}

?>