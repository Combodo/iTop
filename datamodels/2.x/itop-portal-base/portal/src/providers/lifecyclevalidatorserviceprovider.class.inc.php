<?php

// Copyright (C) 2010-2017 Combodo SARL
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

namespace Combodo\iTop\Portal\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Combodo\iTop\Portal\Helper\LifecycleValidatorHelper;

/**
 * LifecycleValidatorHelper service provider
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class LifecycleValidatorServiceProvider implements ServiceProviderInterface
{

	public function register(Container $oApp)
	{
		$oApp['lifecycle_validator'] = function ($oApp)
		{
			$oApp->flush();

			$oLifecycleValidatorHelper = new LifecycleValidatorHelper($oApp['lifecycle_validator.lifecycle_filename'], $oApp['lifecycle_validator.lifecycle_path']);
			if (isset($oApp['lifecycle_validator.instance_name']))
			{
                $oLifecycleValidatorHelper->SetInstancePrefix($oApp['lifecycle_validator.instance_name'] . '-');
			}

			return $oLifecycleValidatorHelper;
		};
	}

	public function boot(Container $oApp)
	{

	}

}
