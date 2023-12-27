<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Serializer\Mapping\Factory;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Serializer\Mapping\ClassMetadataInterface;

/**
 * Caches metadata using a PSR-6 implementation.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class CacheClassMetadataFactory implements ClassMetadataFactoryInterface
{
    use ClassResolverTrait;

    /**
     * @var array<string, ClassMetadataInterface>
     */
    private array $loadedClasses = [];

    public function __construct(
        private readonly ClassMetadataFactoryInterface $decorated,
        private readonly CacheItemPoolInterface $cacheItemPool,
    ) {
    }

    public function getMetadataFor(string|object $value): ClassMetadataInterface
    {
        $class = $this->getClass($value);

        if (isset($this->loadedClasses[$class])) {
            return $this->loadedClasses[$class];
        }

        $key = rawurlencode(strtr($class, '\\', '_'));

        $item = $this->cacheItemPool->getItem($key);
        if ($item->isHit()) {
            return $this->loadedClasses[$class] = $item->get();
        }

        $metadata = $this->decorated->getMetadataFor($value);
        $this->cacheItemPool->save($item->set($metadata));

        return $this->loadedClasses[$class] = $metadata;
    }

    public function hasMetadataFor(mixed $value): bool
    {
        return $this->decorated->hasMetadataFor($value);
    }
}
