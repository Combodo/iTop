<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
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

use utils;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

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
	public function getCacheDir()
    {
	    $cacheDir = $_ENV['PORTAL_ID'] . '-' . $this->environment;

	    return utils::GetCachePath() . "/portals/$cacheDir";
    }

	/**
	 * @return string
	 */
	public function getLogDir()
    {
	    $logDir = $_ENV['PORTAL_ID'] . '-' . $this->environment;

	    return utils::GetLogPath() . "/portals/$logDir";
    }

	/**
	 * @return \Generator|iterable|\Symfony\Component\HttpKernel\Bundle\BundleInterface[]
	 */
	public function registerBundles()
    {
        $contents = require $this->getProjectDir().'/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if (isset($envs[$this->environment]) || isset($envs['all'])) {
                yield new $class();
            }
        }
    }

	/**
	 * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 * @param \Symfony\Component\Config\Loader\LoaderInterface        $loader
	 *
	 * @throws \Exception
	 */
	protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        $container->addResource(new FileResource($this->getProjectDir().'/config/bundles.php'));
        // Feel free to remove the "container.autowiring.strict_mode" parameter
        // if you are using symfony/dependency-injection 4.0+ as it's the default behavior
        $container->setParameter('container.autowiring.strict_mode', true);
        $container->setParameter('container.dumper.inline_class_loader', true);
        $confDir = $this->getProjectDir().'/config';

        $loader->load($confDir.'/{packages}/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{packages}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}_'.$this->environment.self::CONFIG_EXTS, 'glob');
    }

	/**
	 * @param \Symfony\Component\Routing\RouteCollectionBuilder $routes
	 *
	 * @throws \Symfony\Component\Config\Exception\FileLoaderLoadException
	 */
	protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $confDir = $this->getProjectDir().'/config';

        $routes->import($confDir.'/{routes}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}'.self::CONFIG_EXTS, '/', 'glob');
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
		// TODO: Implement isClassInActiveBundle() method.
	}
}
