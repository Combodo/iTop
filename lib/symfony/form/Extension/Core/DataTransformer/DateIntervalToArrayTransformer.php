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
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * Transforms between a normalized date interval and an interval string/array.
 *
 * @author Steffen Roßkamp <steffen.rosskamp@gimmickmedia.de>
 *
 * @implements DataTransformerInterface<\DateInterval, array>
 */
class DateIntervalToArrayTransformer implements DataTransformerInterface
{
    public const YEARS = 'years';
    public const MONTHS = 'months';
    public const DAYS = 'days';
    public const HOURS = 'hours';
    public const MINUTES = 'minutes';
    public const SECONDS = 'seconds';
    public const INVERT = 'invert';

    private const AVAILABLE_FIELDS = [
        self::YEARS => 'y',
        self::MONTHS => 'm',
        self::DAYS => 'd',
        self::HOURS => 'h',
        self::MINUTES => 'i',
        self::SECONDS => 's',
        self::INVERT => 'r',
    ];
    private array $fields;
    private bool $pad;

    /**
     * @param string[]|null $fields The date fields
     * @param bool          $pad    Whether to use padding
     */
    public function __construct(array $fields = null, bool $pad = false)
    {
        $this->fields = $fields ?? ['years', 'months', 'days', 'hours', 'minutes', 'seconds', 'invert'];
        $this->pad = $pad;
    }

    /**
     * Transforms a normalized date interval into an interval array.
     *
     * @param \DateInterval $dateInterval Normalized date interval
     *
     * @throws UnexpectedTypeException if the given value is not a \DateInterval instance
     */
    public function transform(mixed $dateInterval): array
    {
        if (null === $dateInterval) {
            return array_intersect_key(
                [
                    'years' => '',
                    'months' => '',
                    'weeks' => '',
                    'days' => '',
                    'hours' => '',
                    'minutes' => '',
                    'seconds' => '',
                    'invert' => false,
                ],
                array_flip($this->fields)
            );
        }
        if (!$dateInterval instanceof \DateInterval) {
            throw new UnexpectedTypeException($dateInterval, \DateInterval::class);
        }
        $result = [];
        foreach (self::AVAILABLE_FIELDS as $field => $char) {
            $result[$field] = $dateInterval->format('%'.($this->pad ? strtoupper($char) : $char));
        }
        if (\in_array('weeks', $this->fields, true)) {
            $result['weeks'] = '0';
            if (isset($result['days']) && (int) $result['days'] >= 7) {
                $result['weeks'] = (string) floor($result['days'] / 7);
                $result['days'] = (string) ($result['days'] % 7);
            }
        }
        $result['invert'] = '-' === $result['invert'];
        $result = array_intersect_key($result, array_flip($this->fields));

        return $result;
    }

    /**
     * Transforms an interval array into a normalized date interval.
     *
     * @param array $value Interval array
     *
     * @throws UnexpectedTypeException       if the given value is not an array
     * @throws TransformationFailedException if the value could not be transformed
     */
    public function reverseTransform(mixed $value): ?\DateInterval
    {
        if (null === $value) {
            return null;
        }
        if (!\is_array($value)) {
            throw new UnexpectedTypeException($value, 'array');
        }
        if ('' === implode('', $value)) {
            return null;
        }
        $emptyFields = [];
        foreach ($this->fields as $field) {
            if (!isset($value[$field])) {
                $emptyFields[] = $field;
            }
        }
        if (\count($emptyFields) > 0) {
            throw new TransformationFailedException(sprintf('The fields "%s" should not be empty.', implode('", "', $emptyFields)));
        }
        if (isset($value['invert']) && !\is_bool($value['invert'])) {
            throw new TransformationFailedException('The value of "invert" must be boolean.');
        }
        foreach (self::AVAILABLE_FIELDS as $field => $char) {
            if ('invert' !== $field && isset($value[$field]) && !ctype_digit((string) $value[$field])) {
                throw new TransformationFailedException(sprintf('This amount of "%s" is invalid.', $field));
            }
        }
        try {
            if (!empty($value['weeks'])) {
                $interval = sprintf(
                    'P%sY%sM%sWT%sH%sM%sS',
                    empty($value['years']) ? '0' : $value['years'],
                    empty($value['months']) ? '0' : $value['months'],
                    empty($value['weeks']) ? '0' : $value['weeks'],
                    empty($value['hours']) ? '0' : $value['hours'],
                    empty($value['minutes']) ? '0' : $value['minutes'],
                    empty($value['seconds']) ? '0' : $value['seconds']
                );
            } else {
                $interval = sprintf(
                    'P%sY%sM%sDT%sH%sM%sS',
                    empty($value['years']) ? '0' : $value['years'],
                    empty($value['months']) ? '0' : $value['months'],
                    empty($value['days']) ? '0' : $value['days'],
                    empty($value['hours']) ? '0' : $value['hours'],
                    empty($value['minutes']) ? '0' : $value['minutes'],
                    empty($value['seconds']) ? '0' : $value['seconds']
                );
            }
            $dateInterval = new \DateInterval($interval);
            if (isset($value['invert'])) {
                $dateInterval->invert = $value['invert'] ? 1 : 0;
            }
        } catch (\Exception $e) {
            throw new TransformationFailedException($e->getMessage(), $e->getCode(), $e);
        }

        return $dateInterval;
    }
}
