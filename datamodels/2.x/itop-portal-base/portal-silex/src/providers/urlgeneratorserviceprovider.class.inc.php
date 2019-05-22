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

namespace Combodo\iTop\Portal\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Combodo\iTop\Portal\Helper\UrlGenerator;

/**
 * Based on Symfony Routing component Provider for URL generation.
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class UrlGeneratorServiceProvider implements ServiceProviderInterface
{

	public function register(Container $oApp)
	{
		$oApp['url_generator'] = function ($oApp)
		{
			$oApp->flush();

			return new UrlGenerator($oApp['routes'], $oApp['request_context']);
		};
	}

	public function boot(Container $oApp)
	{
		
	}

}
