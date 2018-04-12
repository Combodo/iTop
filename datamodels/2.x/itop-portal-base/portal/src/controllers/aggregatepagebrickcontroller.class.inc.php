<?php

// Copyright (c) 2010-2018 Combodo SARL
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
//

namespace Combodo\iTop\Portal\Controller;

use Combodo\iTop\Portal\Helper\ApplicationHelper;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class AggregatePageBrickController
{
	/**
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param \Silex\Application $oApp
	 * @param string $sBrickId
	 *
	 * @return response
	 * @throws \Exception
	 */
	public function DisplayAction(Request $oRequest, Application $oApp, $sBrickId)
	{
		/** @var \Combodo\iTop\Portal\Brick\AggregatePageBrick $oBrick */
		$oBrick = ApplicationHelper::GetLoadedBrickFromId($oApp, $sBrickId);

		$aPortalInstanceBricks = $oApp['combodo.portal.instance.conf']['bricks'];
		$aAggregatePageBricksConf = $oBrick->GetAggregatePageBricks();
		$aAggregatePageBricks = $this->GetOrderedAggregatePageBricksObjectsById($aPortalInstanceBricks,
			$aAggregatePageBricksConf);

		$aTilesRendering = $this->GetBricksTileRendering($oRequest, $oApp, $aAggregatePageBricks);

		$sLayoutTemplate = $oBrick->GetPageTemplatePath();
		$aData = array(
			'oBrick' => $oBrick,
			'aggregatepage_bricks' => $aAggregatePageBricks,
			'aTilesRendering' => $aTilesRendering,
		);
		$oResponse = $oApp['twig']->render($sLayoutTemplate, $aData);

		return $oResponse;
	}

	/**
	 * @param \Combodo\iTop\Portal\Brick\PortalBrick[] $aPortalInstanceBricks
	 * @param array $aAggregatePageBricksConf
	 *
	 * @return array
	 * @throws \Exception
	 */
	private function GetOrderedAggregatePageBricksObjectsById($aPortalInstanceBricks, $aAggregatePageBricksConf)
	{
		$aAggregatePageBricks = array();
		foreach ($aAggregatePageBricksConf as $sBrickId => $iBrickRank)
		{
			$oPortalBrick = $this->GetBrickFromId($aPortalInstanceBricks, $sBrickId);
			if (!isset($oPortalBrick))
			{
				throw new \Exception("AggregatePageBrick : non existing brick '$sBrickId'");
			}
			$aAggregatePageBricks[] = $oPortalBrick;
		}

		return $aAggregatePageBricks;
	}

	/**
	 * @param \Combodo\iTop\Portal\Brick\PortalBrick[] $aBrickList
	 * @param string $sBrickId
	 *
	 * @return \Combodo\iTop\Portal\Brick\PortalBrick found brick using the given id, null if not found
	 */
	private function GetBrickFromId($aBrickList, $sBrickId)
	{
		$aFilteredBricks = array_filter(
			$aBrickList,
			function ($oSearchBrick) use ($sBrickId) {
				return ($oSearchBrick->GetId() == $sBrickId);
			}
		);
		$oFoundBrick = null;
		if (count($aFilteredBricks) > 0)
		{
			$oFoundBrick = reset($aFilteredBricks);
		}

		return $oFoundBrick;
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param \Silex\Application $oApp
	 * @param \Combodo\iTop\Portal\Brick\PortalBrick[] $aBricks
	 *
	 * @return array rendering for each included tile (key = brick id, value = rendering)
	 */
	private function GetBricksTileRendering(Request $oRequest, Application $oApp, $aBricks)
	{
		$aTilesRendering = array();
		foreach ($aBricks as $oBrick)
		{
			if ($oBrick->GetTileControllerAction() !== null)
			{
				$aControllerActionParts = explode('::', $oBrick->GetTileControllerAction());
				if (count($aControllerActionParts) !== 2)
				{
					$oApp->abort(500,
						'Tile controller action must be of form "\Namespace\ControllerClass::FunctionName" for brick "'.$oBrick->GetId().'"');
				}

				$sControllerName = $aControllerActionParts[0];
				$sControllerAction = $aControllerActionParts[1];

				$oController = new $sControllerName($oRequest, $oApp, $oBrick->GetId());
				$aTilesRendering[$oBrick->GetId()] = $oController->$sControllerAction($oRequest, $oApp,
					$oBrick->GetId());
			}
		}

		return $aTilesRendering;
	}
}