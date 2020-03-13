<?php
/**
 * Copyright (C) 2010-2020 Combodo SARL
 *
 *   This file is part of iTop.
 *
 *   iTop is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU Affero General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   iTop is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU Affero General Public License for more details.
 *
 *   You should have received a copy of the GNU Affero General Public License
 *   along with iTop. If not, see <http: *www.gnu.org/licenses/>
 *
 */

namespace Combodo\iTop\Portal\EventListener;


use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LegacyCodeServiceContainerInjector
{
	use ContainerAwareTrait;

	/**
	 * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $oGetResponseEvent
	 *
	 * @throws \Exception
	 */
	public function onKernelRequest(GetResponseEvent $oGetResponseEvent)
	{
		\ExecutionKPI::setContainer($this->container);
		\DBObjectSet::setContainer($this->container);
	}
}