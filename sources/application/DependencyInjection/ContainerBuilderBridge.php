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

namespace Combodo\iTop\Application\DependencyInjection;


use Symfony\Component\ClassLoader\ClassCollectionLoader;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\ClosureLoader;
use Symfony\Component\DependencyInjection\Loader\DirectoryLoader;
use Symfony\Component\DependencyInjection\Loader\GlobFileLoader;
use Symfony\Component\DependencyInjection\Loader\IniFileLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Config\EnvParametersResource;
use Symfony\Component\HttpKernel\DependencyInjection\AddAnnotatedClassesToCachePass;

final class ContainerBuilderBridge
{

	/** @var string CONFIG_EXTS */
	const CONFIG_EXTS = '.{php,xml,yaml,yml}';
	/**
	 * @var string|null
	 */
	private $containerConfigDir;
	/**
	 * @var null
	 */
	private $containerCacheDir;
	/**
	 * @var bool
	 */
	private $debug;

	/** @var ContainerInterface|null */
	private $container;

	public function __construct($containerConfigDir = null, $containerCacheDir = null, $debug = false)
	{
		$this->containerConfigDir = $containerConfigDir ?: $this->GetDefaultContainerConfigDir();
		$this->containerCacheDir = $containerCacheDir ?: $this->GetDefaultContainerCacheDir();
		$this->debug = $debug;
	}

	public function GetDefaultContainerConfigDir()
	{
		return \utils::GetAbsoluteUrlModulesRoot().'/container_builder_bridge/config';
	}

	private function GetDefaultContainerCacheDir()
	{
		return \utils::GetCachePath().'/container_builder_bridge';
	}

	public function GetContainer()
	{
		if (empty($this->container))
		{
			$this->compile();
		}

		return $this->container;
	}


	private function compile()
	{
		$class = $this->getContainerClass();
		$cache = new ConfigCache($this->containerCacheDir.'/'.$class.'.php', $this->debug);

		$container = $this->buildContainer();

		$this->registerContainerConfiguration($this->getContainerLoader($container));


		$container->addCompilerPass(
			new RegisterListenersPass( 'itop_event_dispatcher', 'itop.event_listener', 'itop.event_subscriber')
		);

		$container->compile();
		$this->dumpContainer($cache, $container, $class, $this->getContainerBaseClass());


		$this->container = require $cache->getPath();
	}

	protected function getContainerBaseClass()
	{
		return 'Container';
	}

	/**
	 * Gets the container class.
	 *
	 * @return string The container class
	 */
	public function getContainerClass()
	{
		$sModuleRoot = preg_replace('/[^a-zA-Z0-9_]+/', '', basename(\utils::GetAbsoluteUrlModulesRoot()));
		return "{$sModuleRoot}ProjectContainer";
	}

	/**
	 * Builds the service container.
	 *
	 * @return ContainerBuilder The compiled service container
	 *
	 * @throws \Exception
	 */
	private function buildContainer()
	{
		$container = $this->getContainerBuilder();



		return $container;
	}
	/**
	 * Gets a new ContainerBuilder instance used to build the service container.
	 *
	 * @return ContainerBuilder
	 */
	private function getContainerBuilder()
	{
		$container = new ContainerBuilder();
		$container->getParameterBag()->add($this->GetBuiltinParameters());

		return $container;
	}

	private function GetBuiltinParameters()
	{
		return [
			'itop.approot' => APPROOT,
			'itop.appconf' => APPCONF,
			'itop.environment' => \utils::GetCurrentEnvironment(),
			'itop.module_root' => \utils::GetAbsoluteUrlModulesRoot(),
		];
	}

	/**
	 * Returns a loader for the container.
	 *
	 * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 *
	 * @return DelegatingLoader The loader
	 * @throws \Exception
	 */
	private function getContainerLoader(ContainerBuilder $container)
	{
		$locator = new FileLocator($this->containerConfigDir);
		$resolver = new LoaderResolver([
			new XmlFileLoader($container, $locator),
			new YamlFileLoader($container, $locator),
			new IniFileLoader($container, $locator),
			new PhpFileLoader($container, $locator),
			new GlobFileLoader($container, $locator),
			new DirectoryLoader($container, $locator),
			new ClosureLoader($container),
		]);

		return new DelegatingLoader($resolver);
	}

	private function registerContainerConfiguration(LoaderInterface $loader)
	{
		$loader->load(function (ContainerBuilder $container) use ($loader) {
			$this->configureContainer($container, $loader);
		});
	}

	private function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
	{
//		$container->setParameter('container.autowiring.strict_mode', true);

		$loader->load($this->containerConfigDir.'/{services}'.self::CONFIG_EXTS, 'glob');
	}


	/**
	 * Dumps the service container to PHP code in the cache.
	 *
	 * @param ConfigCache      $cache     The config cache
	 * @param ContainerBuilder $container The service container
	 * @param string           $class     The name of the class to generate
	 * @param string           $baseClass The name of the container's base class
	 */
	protected function dumpContainer(ConfigCache $cache, ContainerBuilder $container, $class, $baseClass)
	{
		// cache the container
		$dumper = new PhpDumper($container);

		if (class_exists('ProxyManager\Configuration') && class_exists('Symfony\Bridge\ProxyManager\LazyProxy\PhpDumper\ProxyDumper')) {
			$dumper->setProxyDumper(new ProxyDumper());
		}

		$content = $dumper->dump([
			'class' => $class,
			'base_class' => $baseClass,
			'file' => $cache->getPath(),
			'as_files' => true,
			'debug' => $this->debug,
			'inline_class_loader_parameter' => null,
			'build_time' => $container->hasParameter('kernel.container_build_time') ? $container->getParameter('kernel.container_build_time') : time(),
		]);

		$rootCode = array_pop($content);
		$dir = \dirname($cache->getPath()).'/';
		$fs = new Filesystem();

		foreach ($content as $file => $code) {
			$fs->dumpFile($dir.$file, $code);
			@chmod($dir.$file, 0666 & ~umask());
		}
		$legacyFile = \dirname($dir.key($content)).'.legacy';
		if (file_exists($legacyFile)) {
			@unlink($legacyFile);
		}

		$cache->write($rootCode, $container->getResources());
	}
}