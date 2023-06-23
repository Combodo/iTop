<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\PsrCachedReader;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Symfony\Bundle\FrameworkBundle\CacheWarmer\AnnotationsCacheWarmer;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\PhpArrayAdapter;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('annotations.reader', AnnotationReader::class)
            ->call('addGlobalIgnoredName', [
                'required',
                service('annotations.dummy_registry')->nullOnInvalid(), // dummy arg to register class_exists as annotation loader only when required
            ])

        ->set('annotations.dummy_registry', AnnotationRegistry::class)
            ->call('registerUniqueLoader', ['class_exists'])

        ->set('annotations.cached_reader', PsrCachedReader::class)
            ->args([
                service('annotations.reader'),
                inline_service(ArrayAdapter::class),
                abstract_arg('Debug-Flag'),
            ])
            ->tag('annotations.cached_reader')
            ->tag('container.do_not_inline')

        ->set('annotations.filesystem_cache_adapter', FilesystemAdapter::class)
            ->args([
                '',
                0,
                abstract_arg('Cache-Directory'),
            ])

        ->set('annotations.filesystem_cache', DoctrineProvider::class)
            ->factory([DoctrineProvider::class, 'wrap'])
            ->args([
                service('annotations.filesystem_cache_adapter'),
            ])
            ->deprecate('symfony/framework-bundle', '5.4', '"%service_id% is deprecated"')

        ->set('annotations.cache_warmer', AnnotationsCacheWarmer::class)
            ->args([
                service('annotations.reader'),
                param('kernel.cache_dir').'/annotations.php',
                '#^Symfony\\\\(?:Component\\\\HttpKernel\\\\|Bundle\\\\FrameworkBundle\\\\Controller\\\\(?!.*Controller$))#',
                param('kernel.debug'),
            ])

        ->set('annotations.cache_adapter', PhpArrayAdapter::class)
            ->factory([PhpArrayAdapter::class, 'create'])
            ->args([
                param('kernel.cache_dir').'/annotations.php',
                service('cache.annotations'),
            ])
            ->tag('container.hot_path')

        ->set('annotations.cache', DoctrineProvider::class)
            ->factory([DoctrineProvider::class, 'wrap'])
            ->args([
                service('annotations.cache_adapter'),
            ])
            ->deprecate('symfony/framework-bundle', '5.4', '"%service_id% is deprecated"')

        ->alias('annotation_reader', 'annotations.reader')
        ->alias(Reader::class, 'annotation_reader');
};
