<?php

namespace Laminas\Mail\Storage;

use Traversable;
use Webmozart\Assert\Assert;

/**
 * @internal
 */
final class ParamsNormalizer
{
    /**
     * @param mixed $params
     * @return array<string, mixed>
     */
    public static function normalizeParams($params): array
    {
        if ($params instanceof Traversable) {
            $params = iterator_to_array($params);
        }

        if (is_object($params)) {
            $params = get_object_vars($params);
        }

        if (! is_array($params)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Invalid $params provided; expected array|Traversable|object, received %s',
                gettype($params)
            ));
        }

        Assert::isMap($params, 'Expected $params to have only string keys');
        return $params;
    }
}
