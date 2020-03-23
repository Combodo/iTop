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

use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Debug\Debug;
use Symfony\Component\Dotenv\Dotenv;



class Kernel extends BaseKernel
{
	use \Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;

	/** @var string CONFIG_EXTS */
	const CONFIG_EXTS = '.{php,xml,yaml,yml}';

	public function __construct()
	{

//TODO cleanup and deduplicate this code COPIED FROM datamodels/2.x/itop-portal-base/portal/config/bootstrap.php

		if (is_array($sEnv = @include dirname(APPROOT).'/.env.local.php'))
		{
			$_ENV += $sEnv;
		}
		elseif (!class_exists(Dotenv::class))
		{
			throw new RuntimeException('Please run "composer require symfony/dotenv" to load the ".env" files configuring the application.');
		}
		else
		{
			$sPath = APPROOT.'/.env';
			$oDotenv = new Dotenv();

			// load all the .env files
			if (method_exists($oDotenv, 'loadEnv'))
			{
				$oDotenv->loadEnv($sPath);
			}
			else
			{
				// fallback code in case your Dotenv component is not 4.2 or higher (when loadEnv() was added)

				if (file_exists($sPath) || !file_exists($sPathDist = "$sPath.dist"))
				{
					$oDotenv->load($sPath);
				}
				else
				{
					$oDotenv->load($sPathDist);
				}

				if (null === $sEnv = (isset($_SERVER['APP_ENV']) ? $_SERVER['APP_ENV'] : (isset($_ENV['APP_ENV']) ? $_ENV['APP_ENV'] : null)))
				{
					$oDotenv->populate(array('APP_ENV' => $sEnv = 'prod'));
				}

				if ('test' !== $sEnv && file_exists($sPathDist = "$sPath.local"))
				{
					$oDotenv->load($sPathDist);
					$sEnv = isset($_SERVER['APP_ENV']) ? $_SERVER['APP_ENV'] : (isset($_ENV['APP_ENV']) ? $_ENV['APP_ENV'] : $sEnv);
				}

				if (file_exists($sPathDist = "$sPath.$sEnv"))
				{
					$oDotenv->load($sPathDist);
				}

				if (file_exists($sPathDist = "$sPath.$sEnv.local"))
				{
					$oDotenv->load($sPathDist);
				}
			}
		}

// Set debug mode only when necessary
		if (utils::ReadParam('debug', 'false') === 'true')
		{
			$_SERVER['APP_DEBUG'] = true;
		}

		$_SERVER += $_ENV;
		$_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = (isset($_SERVER['APP_ENV']) ? $_SERVER['APP_ENV'] : (isset($_ENV['APP_ENV']) ? $_ENV['APP_ENV'] : null)) ?: 'prod';
		$_SERVER['APP_DEBUG'] = isset($_SERVER['APP_DEBUG']) ? $_SERVER['APP_DEBUG'] : (isset($_ENV['APP_DEBUG']) ? $_ENV['APP_DEBUG'] : ('prod' !== $_SERVER['APP_ENV']));
		$_SERVER['APP_DEBUG'] = $_ENV['APP_DEBUG'] = (int)$_SERVER['APP_DEBUG'] || filter_var($_SERVER['APP_DEBUG'],
			FILTER_VALIDATE_BOOLEAN) ? '1' : '0';

		if ($_SERVER['APP_DEBUG'])
		{
			umask(0000);

			if (class_exists(Debug::class))
			{
				Debug::enable();
			}
		}

		parent::__construct($_SERVER['APP_ENV'], $_SERVER['APP_DEBUG']);
	}

	public function registerBundles()
	{
		$contents = require APPCONF.utils::GetCurrentEnvironment().'/config-symfony-bundles.php';
		foreach ($contents as $class => $envs) {
			if (isset($envs[$this->environment]) || isset($envs['all'])) {
				yield new $class();
			}
		}
	}

	public function getProjectDir()
	{
		return APPROOT;
	}

	protected function configureContainer(\Symfony\Component\DependencyInjection\ContainerBuilder $container, \Symfony\Component\Config\Loader\LoaderInterface $loader)
	{
		$confDir = APPCONF.utils::GetCurrentEnvironment();


		$container->addResource(new FileResource($confDir.'/config-symfony-bundles.php'));
		$container->setParameter('container.autowiring.strict_mode', true);
		$container->setParameter('container.dumper.inline_class_loader', true);

		$loader->load("$confDir/config-symfony.yaml");


		$loader->load($confDir.'/{packages}/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{packages}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}_'.$this->environment.self::CONFIG_EXTS, 'glob');


        $sExtensionsConfGlob = APPROOT.'env-'.utils::GetCurrentEnvironment().'/*/config';

		$loader->load($sExtensionsConfGlob.'/{packages}/*'.self::CONFIG_EXTS, 'glob');
		$loader->load($sExtensionsConfGlob.'/{packages}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, 'glob');
		$loader->load($sExtensionsConfGlob.'/{services}'.self::CONFIG_EXTS, 'glob');
		$loader->load($sExtensionsConfGlob.'/{services}_'.$this->environment.self::CONFIG_EXTS, 'glob');

//		TODO: the portal conf. cannot be loaded into iTop global because it require some environement to be set, that are not outside a portal
//		$SPortalBase = APPROOT.'env-'.utils::GetCurrentEnvironment().'/itop-portal-base/portal/config';
//
//		$loader->load($SPortalBase.'/{packages}/*'.self::CONFIG_EXTS, 'glob');
//		$loader->load($SPortalBase.'/{packages}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, 'glob');
//		$loader->load($SPortalBase.'/{services}'.self::CONFIG_EXTS, 'glob');
//		$loader->load($SPortalBase.'/{services}_'.$this->environment.self::CONFIG_EXTS, 'glob');
	}

	protected function configureRoutes(\Symfony\Component\Routing\RouteCollectionBuilder $routes)
	{
		$confDir = APPCONF.utils::GetCurrentEnvironment();

		$routes->import($confDir.'/{routes}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, '/', 'glob');
		$routes->import($confDir.'/{routes}/*'.self::CONFIG_EXTS, '/', 'glob');
		$routes->import($confDir.'/{routes}'.self::CONFIG_EXTS, '/', 'glob');


		$sExtensionsConfGlob = APPROOT.'env-'.utils::GetCurrentEnvironment().'/*/config';

		$routes->import($sExtensionsConfGlob.'/{routes}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, '/', 'glob');
		$routes->import($sExtensionsConfGlob.'/{routes}/*'.self::CONFIG_EXTS, '/', 'glob');
		$routes->import($sExtensionsConfGlob.'/{routes}'.self::CONFIG_EXTS, '/', 'glob');

//		TODO: the portal conf. cannot be loaded into iTop global because it require some environement to be set, that are not outside a portal
//		$SPortalBase = APPROOT.'env-'.utils::GetCurrentEnvironment().'/itop-portal-base/portal/config';
//
//		$routes->import($SPortalBase.'/{routes}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, '/', 'glob');
//		$routes->import($SPortalBase.'/{routes}/*'.self::CONFIG_EXTS, '/', 'glob');
//		$routes->import($SPortalBase.'/{routes}'.self::CONFIG_EXTS, '/', 'glob');
	}

	// optional, to use the standard Symfony cache directory
	public function getCacheDir()
	{
		return utils::GetCachePath().'/symfony/'.$this->getEnvironment();
	}

	// optional, to use the standard Symfony logs directory
	public function getLogDir()
	{

		return utils::GetLogPath().'/symfony';
	}
}