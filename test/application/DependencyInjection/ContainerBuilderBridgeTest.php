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
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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
//		$container->compile();
//		var_dump($container->getServiceIds());



//		$service_container = $container->get('service_container');
//		$this->assertInstanceOf($oContainerBuilderBridge->getContainerClass(), $service_container);


		$oConfig = $container->get('Config');
		$this->assertInstanceOf(\Config::class, $oConfig);


	}
}
