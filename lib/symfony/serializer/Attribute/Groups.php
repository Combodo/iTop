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
 * Annotation class for @Groups().
 *
 * @Annotation
 * @NamedArgumentConstructor
 * @Target({"PROPERTY", "METHOD", "CLASS"})
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY | \Attribute::TARGET_CLASS)]
class Groups
{
    /**
     * @var string[]
     */
    private readonly array $groups;

    /**
     * @param string|string[] $groups
     */
    public function __construct(string|array $groups)
    {
        $this->groups = (array) $groups;

        if (!$this->groups) {
            throw new InvalidArgumentException(sprintf('Parameter given to "%s" cannot be empty.', static::class));
        }

        foreach ($this->groups as $group) {
            if (!\is_string($group) || '' === $group) {
                throw new InvalidArgumentException(sprintf('Parameter given to "%s" must be a string or an array of non-empty strings.', static::class));
            }
        }
    }

    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }
}

if (!class_exists(\Symfony\Component\Serializer\Annotation\Groups::class, false)) {
    class_alias(Groups::class, \Symfony\Component\Serializer\Annotation\Groups::class);
}
