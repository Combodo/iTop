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

namespace Combodo\iTop\Test\Application\DependencyInjection;;

use Combodo\iTop\Application\DependencyInjection\ContainerBuilderBridge;
use Combodo\iTop\Event\iTopObjectManipulated;
use Combodo\iTop\Event\iTopOnUpdate;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ContainerBuilderBridgeTest extends ItopTestCase
{
	public function setUp()
	{
		parent::setUp();
		require_once(APPROOT.'application/utils.inc.php');
	}

	public function testBuildContainer()
	{
		$_ENV['ITOP_CONFIG_PLACEHOLDERS'] = true;
		$oContainerBuilderBridge = new ContainerBuilderBridge(__DIR__.'/ContainerBuilderBridgeTest/default');
		$container = $oContainerBuilderBridge->GetContainer();
		$this->assertInstanceOf($oContainerBuilderBridge->getContainerClass(), $container);

		var_dump($container->getServiceIds());
		var_dump($container->getParameterBag()->all());

		$oConfig = $container->get('Config');
		$this->assertInstanceOf(\Config::class, $oConfig);


		$oEventDispatcher = $container->get('itop_event_dispatcher');
		$this->assertInstanceOf(EventDispatcher::class, $oEventDispatcher);

		$DBObject = $this->createMock(\DBObject::class);
		$event = new iTopOnUpdate($DBObject);
		$oEventDispatcher->dispatch(iTopOnUpdate::NAME, $event);
		$oEventDispatcher->dispatch(iTopObjectManipulated::NAME, $event);



	}
}
