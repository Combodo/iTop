<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Serializer\Encoder;

use Symfony\Component\Serializer\Exception\NotEncodableValueException;

/**
 * Encodes JSON data.
 *
 * @author Sander Coolen <sander@jibber.nl>
 */
class JsonEncode implements EncoderInterface
{
    /**
     * Configure the JSON flags bitmask.
     */
    public const OPTIONS = 'json_encode_options';

    private array $defaultContext = [
        self::OPTIONS => \JSON_PRESERVE_ZERO_FRACTION,
    ];

    public function __construct(array $defaultContext = [])
    {
        $this->defaultContext = array_merge($this->defaultContext, $defaultContext);
    }

    public function encode(mixed $data, string $format, array $context = []): string
    {
        $options = $context[self::OPTIONS] ?? $this->defaultContext[self::OPTIONS];

        try {
            $encodedJson = json_encode($data, $options);
        } catch (\JsonException $e) {
            throw new NotEncodableValueException($e->getMessage(), 0, $e);
        }

        if (\JSON_THROW_ON_ERROR & $options) {
            return $encodedJson;
        }

        if (\JSON_ERROR_NONE !== json_last_error() && (false === $encodedJson || !($options & \JSON_PARTIAL_OUTPUT_ON_ERROR))) {
            throw new NotEncodableValueException(json_last_error_msg());
        }

        return $encodedJson;
    }

    public function supportsEncoding(string $format): bool
    {
        return JsonEncoder::FORMAT === $format;
    }
}
