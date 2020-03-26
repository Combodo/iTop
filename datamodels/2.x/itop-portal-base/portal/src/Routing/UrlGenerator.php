<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
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

use utils;
use Symfony\Component\Routing\Generator\UrlGenerator as BaseUrlGenerator;

/**
 * Class UrlGenerator
 *
 * @package Combodo\iTop\Portal\Routing
 * @since   2.7.0
 * @author  Bruno Da Silva <bruno.dasilva@combodo.com>
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class UrlGenerator extends BaseUrlGenerator
{
	/** @noinspection PhpTooManyParametersInspection */
	/**
	 * Overloading of the parent function to add the $_REQUEST parameters to the url parameters.
	 * This is used to keep additional parameters in the url, especially when portal is accessed from the /pages/exec.php
	 *
	 * Note: As of now, it only adds the exec_module/exec_page/portal_id/env_switch/debug parameters. Any other parameter will be ignored.
	 *
	 * @param       $variables
	 * @param       $defaults
	 * @param       $requirements
	 * @param       $tokens
	 * @param       $parameters
	 * @param       $name
	 * @param       $referenceType
	 * @param       $hostTokens
	 * @param array $requiredSchemes
	 *
	 * @return string
	 */
	protected function doGenerate($variables, $defaults, $requirements, $tokens, $parameters, $name, $referenceType, $hostTokens, array $requiredSchemes = array())
	{
		$parameters = $this->getExtraParams($parameters);

		return parent::doGenerate($variables, $defaults, $requirements, $tokens, $parameters, $name, $referenceType, $hostTokens, $requiredSchemes);
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
		if ($sExecModule !== '' && $sExecPage !== '')
		{
			$aParameters['exec_module'] = $sExecModule;
			$aParameters['exec_page'] = $sExecPage;
		}

		// Optional parameters
		$sPortalId = utils::ReadParam('portal_id', '', false, 'string');
		if ($sPortalId !== '')
		{
			$aParameters['portal_id'] = $sPortalId;
		}
		$sEnvSwitch = utils::ReadParam('env_switch', '', false, 'string');
		if ($sEnvSwitch !== '')
		{
			$aParameters['env_switch'] = $sEnvSwitch;
		}
		$sDebug = utils::ReadParam('debug', '', false, 'string');
		if ($sDebug !== '')
		{
			$aParameters['debug'] = $sDebug;
		}

		return $aParameters;
	}
}