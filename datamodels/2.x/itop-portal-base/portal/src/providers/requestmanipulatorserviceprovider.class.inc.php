<?php

/**
 * Copyright (C) 2012-2018 Combodo SARL
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
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 */

namespace Combodo\iTop\Portal\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Combodo\iTop\Portal\Helper\RequestManipulatorHelper;

/**
 * RequestManipulatorHelper service provider
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since 2.4.3
 */
class RequestManipulatorServiceProvider implements ServiceProviderInterface
{

    /**
     * @param \Silex\Application $oApp
     */
	public function register(Application $oApp)
	{
		$oApp['request_manipulator'] = $oApp->share(function ($oApp)
		{
			$oApp->flush();

			$oRequestManipulatorHelper = new RequestManipulatorHelper($oApp['request_stack']);

			return $oRequestManipulatorHelper;
		});
	}

    /**
     * @param \Silex\Application $oApp
     */
	public function boot(Application $oApp)
	{

	}

}
