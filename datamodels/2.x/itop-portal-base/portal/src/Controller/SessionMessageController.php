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

use Combodo\iTop\Portal\Helper\RequestManipulatorHelper;
use Combodo\iTop\Portal\Helper\SessionMessageHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class SessionMessageController
 *
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Portal\Controller
 * @since   2.7.0
 */
class SessionMessageController extends AbstractController
{

	/**
	 * @param \Combodo\iTop\Portal\Helper\RequestManipulatorHelper $oRequestManipulatorHelper
	 * @param \Combodo\iTop\Portal\Helper\SessionMessageHelper $oSessionMessageHelper
	 *
	 * @since 3.2.0 N°6933
	 */
	public function __construct(
		protected RequestManipulatorHelper $oRequestManipulatorHelper,
		protected SessionMessageHelper $oSessionMessageHelper
	)
	{
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function AddMessageAction(Request $oRequest)
	{
		$aData = array();

		// Retrieve parameters
		$sMessageSeverity = $this->oRequestManipulatorHelper->ReadParam('sSeverity');
		$sMessageContent = $this->oRequestManipulatorHelper->ReadParam('sContent');

		// Check parameters consistency
		if (empty($sMessageSeverity) || empty($sMessageContent))
		{
			throw new HttpException(Response::HTTP_BAD_REQUEST, 'Message must have a severity and a content, make sure both sSeverity & sContent parameters are sent.');
		}

		// Add message
		$this->oSessionMessageHelper->AddMessage(uniqid(), $sMessageContent, $sMessageSeverity);

		return new JsonResponse($aData);
	}

}
