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
 * @author Bernhard Schussek <bschussek@gmail.com>
 *
 * @implements DataTransformerInterface<array, array>
 */
class ArrayToPartsTransformer implements DataTransformerInterface
{
    private array $partMapping;

    public function __construct(array $partMapping)
    {
        $this->partMapping = $partMapping;
    }

    public function transform(mixed $array): mixed
    {
        if (!\is_array($array ??= [])) {
            throw new TransformationFailedException('Expected an array.');
        }

        $result = [];

        foreach ($this->partMapping as $partKey => $originalKeys) {
            if (!$array) {
                $result[$partKey] = null;
            } else {
                $result[$partKey] = array_intersect_key($array, array_flip($originalKeys));
            }
        }

        return $result;
    }

    public function reverseTransform(mixed $array): mixed
    {
        if (!\is_array($array)) {
            throw new TransformationFailedException('Expected an array.');
        }

        $result = [];
        $emptyKeys = [];

        foreach ($this->partMapping as $partKey => $originalKeys) {
            if (!empty($array[$partKey])) {
                foreach ($originalKeys as $originalKey) {
                    if (isset($array[$partKey][$originalKey])) {
                        $result[$originalKey] = $array[$partKey][$originalKey];
                    }
                }
            } else {
                $emptyKeys[] = $partKey;
            }
        }

        if (\count($emptyKeys) > 0) {
            if (\count($emptyKeys) === \count($this->partMapping)) {
                // All parts empty
                return null;
            }

            throw new TransformationFailedException(sprintf('The keys "%s" should not be empty.', implode('", "', $emptyKeys)));
        }

        return $result;
    }
}
