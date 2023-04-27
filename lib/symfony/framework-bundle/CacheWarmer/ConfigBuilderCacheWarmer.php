<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\CacheWarmer;

use Psr\Log\LoggerInterface;
use Symfony\Component\Config\Builder\ConfigBuilderGenerator;
use Symfony\Component\Config\Builder\ConfigBuilderGeneratorInterface;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ConfigurationExtensionInterface;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Generate all config builders.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class ConfigBuilderCacheWarmer implements CacheWarmerInterface
{
    private $kernel;
    private $logger;

    public function __construct(KernelInterface $kernel, LoggerInterface $logger = null)
    {
        $this->kernel = $kernel;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     *
     * @return string[]
     */
    public function warmUp(string $cacheDir)
    {
        $generator = new ConfigBuilderGenerator($cacheDir);

        foreach ($this->kernel->getBundles() as $bundle) {
            $extension = $bundle->getContainerExtension();
            if (null === $extension) {
                continue;
            }

            try {
                $this->dumpExtension($extension, $generator);
            } catch (\Exception $e) {
                if ($this->logger) {
                    $this->logger->warning('Failed to generate ConfigBuilder for extension {extensionClass}.', ['exception' => $e, 'extensionClass' => \get_class($extension)]);
                }
            }
        }

        // No need to preload anything
        return [];
    }

    private function dumpExtension(ExtensionInterface $extension, ConfigBuilderGeneratorInterface $generator): void
    {
        $configuration = null;
        if ($extension instanceof ConfigurationInterface) {
            $configuration = $extension;
        } elseif ($extension instanceof ConfigurationExtensionInterface) {
            $configuration = $extension->getConfiguration([], new ContainerBuilder($this->kernel->getContainer()->getParameterBag()));
        }

        if (!$configuration) {
            return;
        }

        $generator->build($configuration);
    }

    /**
     * {@inheritdoc}
     */
    public function isOptional()
    {
        return true;
    }
}
