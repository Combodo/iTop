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

use Combodo\iTop\Portal\Helper\ContextManipulatorHelper;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CreateBrickController
 *
 * @package Combodo\iTop\Portal\Controller
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since   2.3.0
 */
class CreateBrickController extends BrickController
{

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param string                                    $sBrickId
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \Combodo\iTop\Portal\Brick\BrickNotFoundException
	 */
	public function DisplayAction(Request $oRequest, $sBrickId)
	{
		/** @var \Combodo\iTop\Portal\Brick\BrickCollection $oBrickCollection */
		$oBrickCollection = $this->get('brick_collection');

		/** @var \Combodo\iTop\Portal\Brick\CreateBrick $oBrick */
		$oBrick = $oBrickCollection->GetBrickById($sBrickId);

		$aRouteParams = array(
			'sBrickId' => $sBrickId,
			'sObjectClass' => $oBrick->GetClass(),
			'ar_token' => null,
		);

		// Checking for actions rules
		$aRules = $oBrick->GetRules();
		if (!empty($aRules))
		{
			$aRouteParams['ar_token'] = ContextManipulatorHelper::PrepareAndEncodeRulesToken($aRules);
		}

		return $this->ForwardToRoute('p_object_create', $aRouteParams, $oRequest->query->all());
	}

}
