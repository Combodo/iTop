<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Serializer;

/**
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
interface SerializerInterface
{
    /**
     * Serializes data in the appropriate format.
     *
     * @param array<string, mixed> $context Options normalizers/encoders have access to
     */
    public function serialize(mixed $data, string $format, array $context = []): string;

    /**
     * Deserializes data into the given type.
     *
     * @template TObject of object
     * @template TType of string|class-string<TObject>
     *
     * @param TType                $type
     * @param array<string, mixed> $context
     *
     * @psalm-return (TType is class-string<TObject> ? TObject : mixed)
     *
     * @phpstan-return ($type is class-string<TObject> ? TObject : mixed)
     */
    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed;
}
