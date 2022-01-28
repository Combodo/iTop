<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\TwigBundle\Tests\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\TwigBundle\DependencyInjection\Compiler\ExtensionPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class ExtensionPassTest extends TestCase
{
    public function testProcessDoesNotDropExistingFileLoaderMethodCalls()
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.debug', false);

        $container->register('twig.app_variable', '\Symfony\Bridge\Twig\AppVariable');
        $container->register('templating', '\Symfony\Bundle\TwigBundle\TwigEngine');
        $container->register('twig.extension.yaml');
        $container->register('twig.extension.debug.stopwatch');
        $container->register('twig.extension.expression');

        $nativeTwigLoader = new Definition('\Twig\Loader\FilesystemLoader');
        $nativeTwigLoader->addMethodCall('addPath', []);
        $container->setDefinition('twig.loader.native_filesystem', $nativeTwigLoader);

        $filesystemLoader = new Definition('\Symfony\Bundle\TwigBundle\Loader\FilesystemLoader');
        $filesystemLoader->setArguments([null, null, null]);
        $filesystemLoader->addMethodCall('addPath', []);
        $container->setDefinition('twig.loader.filesystem', $filesystemLoader);

        $extensionPass = new ExtensionPass();
        $extensionPass->process($container);

        $this->assertCount(2, $filesystemLoader->getMethodCalls());
    }
}
