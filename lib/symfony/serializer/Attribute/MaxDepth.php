<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Serializer\Attribute;

use Symfony\Component\Serializer\Exception\InvalidArgumentException;

/**
 * Annotation class for @MaxDepth().
 *
 * @Annotation
 * @NamedArgumentConstructor
 * @Target({"PROPERTY", "METHOD"})
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY)]
class MaxDepth
{
    public function __construct(private readonly int $maxDepth)
    {
        if ($maxDepth <= 0) {
            throw new InvalidArgumentException(sprintf('Parameter given to "%s" must be a positive integer.', static::class));
        }
    }

    /**
     * @return int
     */
    public function getMaxDepth()
    {
        return $this->maxDepth;
    }
}

if (!class_exists(\Symfony\Component\Serializer\Annotation\MaxDepth::class, false)) {
    class_alias(MaxDepth::class, \Symfony\Component\Serializer\Annotation\MaxDepth::class);
}
