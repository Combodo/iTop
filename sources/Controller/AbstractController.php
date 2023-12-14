<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Controller;

use DeprecatedCallsLog;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseAbstractControllerAlias;

/**
 * Class AbstractController
 *
 * Abstract controller to centralize common features of business controllers which are still to be defined.
 * Note that this can be extended by "TwigBase" controllers or standalone controllers.
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Controller
 * @since 3.1.0
 * @since 3.2.0 N°6935 Controller is now based on Symfony controller
 */
abstract class AbstractController extends BaseAbstractControllerAlias implements iController
{
	/**
	 * @inheritDoc
	 * @deprecated 3.2.0 N°6935 Use \Symfony\Component\HttpFoundation\Request::isXmlHttpRequest() instead
	 */
	public function IsHandlingXmlHttpRequest(): bool
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod("Use \Symfony\Component\HttpFoundation\Request::isXmlHttpRequest() instead");
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');
	}
}