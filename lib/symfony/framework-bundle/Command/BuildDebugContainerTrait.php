<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\Command;

use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @internal
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 * @author Nicolas Grekas <p@tchwork.com>
 */
trait BuildDebugContainerTrait
{
    protected ContainerBuilder $container;

    /**
     * Loads the ContainerBuilder from the cache.
     *
     * @throws \LogicException
     */
    protected function getContainerBuilder(KernelInterface $kernel): ContainerBuilder
    {
        if (isset($this->container)) {
            return $this->container;
        }

        if (!$kernel->isDebug() || !$kernel->getContainer()->getParameter('debug.container.dump') || !(new ConfigCache($kernel->getContainer()->getParameter('debug.container.dump'), true))->isFresh()) {
            $buildContainer = \Closure::bind(function () {
                $this->initializeBundles();

                return $this->buildContainer();
            }, $kernel, $kernel::class);
            $container = $buildContainer();
            $container->getCompilerPassConfig()->setRemovingPasses([]);
            $container->getCompilerPassConfig()->setAfterRemovingPasses([]);
            $container->compile();
        } else {
            $buildContainer = \Closure::bind(function () {
                $containerBuilder = $this->getContainerBuilder();
                $this->prepareContainer($containerBuilder);

                return $containerBuilder;
            }, $kernel, $kernel::class);
            $container = $buildContainer();
            (new XmlFileLoader($container, new FileLocator()))->load($kernel->getContainer()->getParameter('debug.container.dump'));
            $locatorPass = new ServiceLocatorTagPass();
            $locatorPass->process($container);

            $container->getCompilerPassConfig()->setBeforeOptimizationPasses([]);
            $container->getCompilerPassConfig()->setOptimizationPasses([]);
            $container->getCompilerPassConfig()->setBeforeRemovingPasses([]);
        }

        return $this->container = $container;
    }
}
