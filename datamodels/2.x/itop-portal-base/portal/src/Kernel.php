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

namespace Combodo\iTop\Portal;

use DeprecatedCallsLog;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use utils;

/**
 * Class Kernel
 *
 * @package Combodo\iTop\Portal
 * @since 2.7.0
 */
class Kernel extends BaseKernel
{
	use MicroKernelTrait;

    /** @var string CONFIG_EXTS */
    const CONFIG_EXTS = '.{php,xml,yaml,yml}';

	/**
	 * @return string
	 */
	public function getCacheDir(): string
	{
		$cacheDir = $_ENV['PORTAL_ID'].'-'.$this->environment;

		return utils::GetCachePath()."/portals/$cacheDir";
	}

	/**
	 * @return string
	 */
	public function getLogDir(): string
	{
	    $logDir = $_ENV['PORTAL_ID'] . '-' . $this->environment;

	    return utils::GetLogPath() . "/portals/$logDir";
    }

	/**
	 * @return \Generator|iterable|\Symfony\Component\HttpKernel\Bundle\BundleInterface[]
	 */
	public function registerBundles(): iterable
	{
        $contents = require $this->getProjectDir().'/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if (isset($envs[$this->environment]) || isset($envs['all'])) {
	            yield new $class();
            }
        }
    }

	/**
	 * @param \Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $container
	 *
	 * @return void
	 */
	protected function configureContainer(ContainerConfigurator $container)
	{
		$confDir = '../config';

		$container->import(new FileResource($this->getProjectDir().'/config/bundles.php'));
		$container->import($confDir.'/bridge.php');
		$container->parameters()->set('container.dumper.inline_class_loader', true);

		$container->import($confDir.'/{packages}/*'.self::CONFIG_EXTS);
		$container->import($confDir.'/{packages}/'.$this->environment.'/**/*'.self::CONFIG_EXTS);
		$container->import($confDir.'/{services}'.self::CONFIG_EXTS);
		$container->import($confDir.'/{services}_'.$this->environment.self::CONFIG_EXTS);
	}

	/**
	 * @param \Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator $routes
	 *
	 * @return void
	 */
	protected function configureRoutes(RoutingConfigurator $routes)
	{
		$confDir = '../config';

		$routes->import($confDir.'/{routes}/'.$this->environment.'/**/*'.self::CONFIG_EXTS);
		$routes->import($confDir.'/{routes}/*'.self::CONFIG_EXTS);
		$routes->import($confDir.'/{routes}'.self::CONFIG_EXTS);
	}


	/**
	 * Checks if a given class name belongs to an active bundle.
	 *
	 * @param string $class A class name
	 *
	 * @return void true if the class belongs to an active bundle, false otherwise
	 *
	 * @api
	 *
	 * @deprecated Deprecated since version 2.6, to be removed in 3.0.
	 */
	public function isClassInActiveBundle($class)
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod();
		// TODO: Implement isClassInActiveBundle() method.
	}
}
