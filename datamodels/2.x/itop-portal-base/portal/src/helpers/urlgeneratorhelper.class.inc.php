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

namespace Combodo\iTop\Portal\Helper;

use Symfony\Component\Routing\Generator\UrlGenerator as SymfonyUrlGenerator;
use utils;

/**
 * Based on Symfony UrlGenerator
 *
 * UrlGenerator can generate a URL or a path for any route in the RouteCollection
 * based on the passed parameters.
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 *
 * @api
 */
class UrlGenerator extends SymfonyUrlGenerator
{
	/**
	 * Overloading of the parent function to add the $_REQUEST parameters to the url parameters.
	 * This is used to keep additionnal parameters in the url, especially when portal is accessed from the /pages/exec.php
	 *
	 * Note : As of now, it only adds the exec_module and exec_page parameters. Any other parameter will be ignored.
	 *
	 * @return string
	 */
	public function generate($name, $parameters = array(), $referenceType = SymfonyUrlGenerator::ABSOLUTE_PATH)
	{
		// Mandatory parameters
		$sExecModule = utils::ReadParam('exec_module', '', false, 'string');
		$sExecPage = utils::ReadParam('exec_page', '', false, 'string');
		if ($sExecModule !== '' && $sExecPage !== '')
		{
			$parameters['exec_module'] = $sExecModule;
			$parameters['exec_page'] = $sExecPage;
		}

		// Optional parameters
		$sEnvSwitch = utils::ReadParam('env_switch', '', false, 'string');
		if ($sEnvSwitch !== '')
		{
			$parameters['env_switch'] = $sEnvSwitch;
		}
		$sDebug = utils::ReadParam('debug', '', false, 'string');
		if ($sDebug !== '')
		{
			$parameters['debug'] = $sDebug;
		}
		
		return parent::generate($name, $parameters, $referenceType);
	}

}

?>