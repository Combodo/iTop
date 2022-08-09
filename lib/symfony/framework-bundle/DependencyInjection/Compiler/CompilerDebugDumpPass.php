<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\DependencyInjection\Compiler;

@trigger_error(sprintf('The %s class is deprecated since Symfony 3.3 and will be removed in 4.0.', CompilerDebugDumpPass::class), \E_USER_DEPRECATED);

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @deprecated since version 3.3, to be removed in 4.0.
 */
class CompilerDebugDumpPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $filename = self::getCompilerLogFilename($container);

        $filesystem = new Filesystem();
        $filesystem->dumpFile($filename, implode("\n", $container->getCompiler()->getLog()), null);
        try {
            $filesystem->chmod($filename, 0666, umask());
        } catch (IOException $e) {
            // discard chmod failure (some filesystem may not support it)
        }
    }

    public static function getCompilerLogFilename(ContainerInterface $container)
    {
        $class = $container->getParameter('kernel.container_class');

        return $container->getParameter('kernel.cache_dir').'/'.$class.'Compiler.log';
    }
}
