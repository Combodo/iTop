<?php
/**
 * Copyright (C) 2013-2023 Combodo SARL
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

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

require_once(__DIR__.'/../../../../approot.inc.php');
require_once(__DIR__.'/../../../../application/startup.inc.php');

return static function (ContainerConfigurator $container) {

	$oConfig = utils::GetConfig();

	// kernel.secret
	$container->parameters()->set('kernel.secret', $oConfig->Get('application.secret'));

};
