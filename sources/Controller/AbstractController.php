<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Controller;

/**
 * Class AbstractController
 *
 * Abstract controller to centralize common features of business controllers which are still to be defined.
 * Note that this can be extended by "TwigBase" controllers or standalone controllers.
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Controller
 * @since 3.1.0
 */
abstract class AbstractController implements iController
{
	/**
	 * @inheritDoc
	 */
	public function IsHandlingXmlHttpRequest(): bool
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');
	}
}