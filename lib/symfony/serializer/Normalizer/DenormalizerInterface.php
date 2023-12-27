<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Serializer\Normalizer;

use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

/**
 * @author Jordi Boggiano <j.boggiano@seld.be>
 *
 * @method array getSupportedTypes(?string $format)
 */
interface DenormalizerInterface
{
    public const COLLECT_DENORMALIZATION_ERRORS = 'collect_denormalization_errors';

    /**
     * Denormalizes data back into an object of the given class.
     *
     * @param mixed       $data    Data to restore
     * @param string      $type    The expected class to instantiate
     * @param string|null $format  Format the given data was extracted from
     * @param array       $context Options available to the denormalizer
     *
     * @return mixed
     *
     * @throws BadMethodCallException   Occurs when the normalizer is not called in an expected context
     * @throws InvalidArgumentException Occurs when the arguments are not coherent or not supported
     * @throws UnexpectedValueException Occurs when the item cannot be hydrated with the given data
     * @throws ExtraAttributesException Occurs when the item doesn't have attribute to receive given data
     * @throws LogicException           Occurs when the normalizer is not supposed to denormalize
     * @throws RuntimeException         Occurs if the class cannot be instantiated
     * @throws ExceptionInterface       Occurs for all the other cases of errors
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []);

    /**
     * Checks whether the given class is supported for denormalization by this normalizer.
     *
     * @param mixed       $data    Data to denormalize from
     * @param string      $type    The class to which the data should be denormalized
     * @param string|null $format  The format being deserialized from
     * @param array       $context Options available to the denormalizer
     *
     * @return bool
     */
    public function supportsDenormalization(mixed $data, string $type, string $format = null /* , array $context = [] */);

    /**
     * Returns the types potentially supported by this denormalizer.
     *
     * For each supported formats (if applicable), the supported types should be
     * returned as keys, and each type should be mapped to a boolean indicating
     * if the result of supportsDenormalization() can be cached or not
     * (a result cannot be cached when it depends on the context or on the data.)
     * A null value means that the denormalizer does not support the corresponding
     * type.
     *
     * Use type "object" to match any classes or interfaces,
     * and type "*" to match any types.
     *
     * @return array<class-string|'*'|'object'|string, bool|null>
     */
    /* public function getSupportedTypes(?string $format): array; */
}
