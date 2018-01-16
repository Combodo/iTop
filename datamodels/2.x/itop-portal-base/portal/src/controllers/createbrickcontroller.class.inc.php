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
use Symfony\Component\HttpKernel\HttpKernelInterface;
use MetaModel;
use Combodo\iTop\Portal\Helper\ApplicationHelper;
use Combodo\iTop\Portal\Helper\ContextManipulatorHelper;
use Combodo\iTop\Portal\Helper\SecurityHelper;

class CreateBrickController extends BrickController
{

	public function DisplayAction(Request $oRequest, Application $oApp, $sBrickId)
	{
		$oBrick = ApplicationHelper::GetLoadedBrickFromId($oApp, $sBrickId);
        $sObjectClass = $oBrick->GetClass();

		$aRouteParams = array(
			'sObjectClass' => $oBrick->GetClass()
		);

        // Checking for actions rules
        $aRules = $oBrick->GetRules();
        if (!empty($aRules))
        {
            $aRouteParams['ar_token'] = ContextManipulatorHelper::PrepareAndEncodeRulesToken($aRules);
        }

        // Checking if the target object class is asbtract or not
        // - If is not abstract, we redirect to object creation form
        if (!MetaModel::IsAbstract($sObjectClass))
        {
            // Preparing redirection route
            // - Adding brick id to the params
            $aRouteParams['sBrickId'] = $sBrickId;
            // - Generating route
            $sRedirectRoute = $oApp['url_generator']->generate('p_object_create', $aRouteParams);
            // - Request
            $oSubRequest = Request::create($sRedirectRoute, 'GET', $oRequest->query->all(), $oRequest->cookies->all(), array(), $oRequest->server->all());

            $oResponse = $oApp->handle($oSubRequest, HttpKernelInterface::SUB_REQUEST, true);
        }
        // - Else, we list the leaf classes as an intermediate step
        else
        {
            $aData = array(
                'oBrick' => $oBrick,
                'sBrickId' => $sBrickId,
                'aLeafClasses' => array(),
                'ar_token' => $aRouteParams['ar_token']
            );

            $aLeafClasses = array();
            $aChildClasses = MetaModel::EnumChildClasses($sObjectClass);
            foreach ($aChildClasses as $sChildClass)
            {
                if (!MetaModel::IsAbstract($sChildClass) && SecurityHelper::IsActionAllowed($oApp, UR_ACTION_CREATE, $sChildClass))
                {
                    $aLeafClasses[] = array(
                        'id' => $sChildClass,
                        'name' => MetaModel::GetName($sChildClass)
                    );
                }
            }
            $aData['aLeafClasses'] = $aLeafClasses;

            $oResponse = $oApp['twig']->render($oBrick->GetPageTemplatePath(), $aData);
        }

        return $oResponse;
	}

}

