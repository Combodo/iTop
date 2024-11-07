<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Combodo\iTop\Portal\Routing\ItopExtensionsExtraRoutes;

/**
 * Extensions controllers registration.
 *
 * @author Benjamin Dalsass
 * @since 3.1.0
 * @package Symfony\Component\DependencyInjection\Loader\Configurator
 */
return static function (ContainerConfigurator $oContainer) {

	// retrieve extension controller classes
	$aControllersClasses = ItopExtensionsExtraRoutes::GetControllersClasses();

	// iterate throw extensions controller classes...
	foreach ($aControllersClasses as $sController) {

		// register as service
		$oContainer->services()->set($sController, $sController)
			->public()
			->tag('controller.service_arguments')
			->tag('container.service_suscriber')
			->autowire()
			->autoconfigure();
	}

};