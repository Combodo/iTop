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

namespace Combodo\iTop\Portal\Routing;

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouterInterface;
use utils;

/**
 * Class UrlGenerator
 *
 * @author  Bruno Da Silva <bruno.dasilva@combodo.com>
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Portal\Routing
 * @since   2.7.0
 */
class UrlGenerator implements RouterInterface
{
	private $router;

	public function __construct(RouterInterface $router)
	{
		$this->router = $router;
	}

	public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
	{
		$parameters = $this->getExtraParams($parameters);

		return $this->router->generate($name, $parameters, $referenceType);
	}

	public function setContext(RequestContext $context)
	{
		$this->router->setContext($context);
	}

	public function getContext()
	{
		return $this->router->getContext();
	}

	public function getRouteCollection()
	{
		return $this->router->getRouteCollection();
	}

	public function match($pathinfo)
	{
		return $this->router->match($pathinfo);
	}

	/**
	 * @param array $aParameters
	 *
	 * @return mixed
	 */
	private function getExtraParams($aParameters)
	{
		$sExecModule = utils::ReadParam('exec_module', '', false, 'string');
		$sExecPage = utils::ReadParam('exec_page', '', false, 'string');
		if ($sExecModule !== '' && $sExecPage !== '') {
			$aParameters['exec_module'] = $sExecModule;
			$aParameters['exec_page'] = $sExecPage;
		}

		// Optional parameters
		$sPortalId = utils::ReadParam('portal_id', '', false, 'string');
		if ($sPortalId !== '') {
			$aParameters['portal_id'] = $sPortalId;
		}
		$sEnvSwitch = utils::ReadParam('env_switch', '', false, 'string');
		if ($sEnvSwitch !== '') {
			$aParameters['env_switch'] = $sEnvSwitch;
		}
		$sDebug = utils::ReadParam('debug', '', false, 'string');
		if ($sDebug !== '') {
			$aParameters['debug'] = $sDebug;
		}

		return $aParameters;
	}
}