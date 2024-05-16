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

use Combodo\iTop\Portal\Brick\BrickCollection;
use Combodo\iTop\Portal\Brick\BrickNotFoundException;
use IssueLog;
use LogChannels;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use UserRights;


/**
 * Class AggregatePageBrickController
 *
 * @package Combodo\iTop\Portal\Controller
 * @author  Pierre Goiffon <pierre.goiffon@combodo.com>
 * @author  Stephen Abello <stephen.abello@combodo.com>
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since   2.5.0
 */
class AggregatePageBrickController extends BrickController
{

	/**
	 * Constructor.
	 *
	 * @param \Combodo\iTop\Portal\Brick\BrickCollection $oBrickCollection
	 *
	 * @since 3.2.0 N°6933
	 */
	public function __construct(
		protected BrickCollection $oBrickCollection
	)
	{

	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param string $sBrickId
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @throws BrickNotFoundException
	 * @throws \Exception
	 */
	public function DisplayAction(Request $oRequest, $sBrickId)
	{
		/** @var \Combodo\iTop\Portal\Brick\AggregatePageBrick $oBrick */
		$oBrick = $this->oBrickCollection->GetBrickById($sBrickId);

		$aAggregatePageBricksConf = $oBrick->GetAggregatePageBricks();
		$aAggregatePageBricks = $this->GetOrderedAggregatePageBricksObjectsById($aAggregatePageBricksConf);

		$aTilesRendering = $this->GetBricksTileRendering($oRequest, $aAggregatePageBricks);

		$sLayoutTemplate = $oBrick->GetPageTemplatePath();
		$aData = array(
			'oBrick' => $oBrick,
			'aggregatepage_bricks' => $aAggregatePageBricks,
			'aTilesRendering' => $aTilesRendering,
		);
		$oResponse = $this->render($sLayoutTemplate, $aData);

		return $oResponse;
	}

	/**
	 * @param array $aAggregatePageBricksConf
	 *
	 * @return array
	 * @throws \Exception
	 */
	private function GetOrderedAggregatePageBricksObjectsById($aAggregatePageBricksConf)
	{
		$aAggregatePageBricks = array();
		foreach ($aAggregatePageBricksConf as $sBrickId => $iBrickRank)
		{
			try
			{
				$oPortalBrick = $this->oBrickCollection->GetBrickById($sBrickId);
			}
			catch (BrickNotFoundException $oException)
			{
				IssueLog::Debug('AggregatePageBrick: Could not display brick, either wrong id or user profile not allowed', LogChannels::PORTAL, [
					'brick_id' => $sBrickId,
					'user_profiles' => UserRights::ListProfiles(),
				]);
				continue;
			}

			$aAggregatePageBricks[] = $oPortalBrick;
		}

		return $aAggregatePageBricks;
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param \Combodo\iTop\Portal\Brick\PortalBrick[]  $aBricks
	 *
	 * @return array rendering for each included tile (key = brick id, value = rendering)
	 */
	private function GetBricksTileRendering(Request $oRequest, $aBricks)
	{
		$aTilesRendering = array();
		foreach ($aBricks as $oBrick)
		{
			if ($oBrick->GetTileControllerAction() !== null)
			{
				$aControllerActionParts = explode('::', $oBrick->GetTileControllerAction());
				if (count($aControllerActionParts) !== 2)
				{
					throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Tile controller action must be of form "\Namespace\ControllerClass::FunctionName" for brick "'.$oBrick->GetId().'"');
				}

				$aRouteParams = array();
				// Add sBrickId in the route params as it is necessary for each brick actions
				if (is_a($aControllerActionParts[0], BrickController::class, true))
				{
					$aRouteParams['sBrickId'] = $oBrick->GetId();
				}

				/** @var \Symfony\Component\HttpFoundation\Response $oResponse */
				$oResponse = $this->forward($oBrick->GetTileControllerAction(), $aRouteParams, $oRequest->query->all());
				$aTilesRendering[$oBrick->GetId()] = $oResponse->getContent();
			}
		}

		return $aTilesRendering;
	}
}