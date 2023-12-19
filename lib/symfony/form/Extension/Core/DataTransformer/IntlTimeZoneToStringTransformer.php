<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Extension\Core\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Transforms between a timezone identifier string and a IntlTimeZone object.
 *
 * @author Roland Franssen <franssen.roland@gmail.com>
 *
 * @implements DataTransformerInterface<\IntlTimeZone|array<\IntlTimeZone>, string|array<string>>
 */
class IntlTimeZoneToStringTransformer implements DataTransformerInterface
{
    private bool $multiple;

    public function __construct(bool $multiple = false)
    {
        $this->multiple = $multiple;
    }

    public function transform(mixed $intlTimeZone): mixed
    {
        if (null === $intlTimeZone) {
            return null;
        }

        if ($this->multiple) {
            if (!\is_array($intlTimeZone)) {
                throw new TransformationFailedException('Expected an array of \IntlTimeZone objects.');
            }

            return array_map([new self(), 'transform'], $intlTimeZone);
        }

        if (!$intlTimeZone instanceof \IntlTimeZone) {
            throw new TransformationFailedException('Expected a \IntlTimeZone object.');
        }

        return $intlTimeZone->getID();
    }

    public function reverseTransform(mixed $value): mixed
    {
        if (null === $value) {
            return null;
        }

        if ($this->multiple) {
            if (!\is_array($value)) {
                throw new TransformationFailedException('Expected an array of timezone identifier strings.');
            }

            return array_map([new self(), 'reverseTransform'], $value);
        }

        if (!\is_string($value)) {
            throw new TransformationFailedException('Expected a timezone identifier string.');
        }

        $intlTimeZone = \IntlTimeZone::createTimeZone($value);

        if ('Etc/Unknown' === $intlTimeZone->getID()) {
            throw new TransformationFailedException(sprintf('Unknown timezone identifier "%s".', $value));
        }

        return $intlTimeZone;
    }
}
