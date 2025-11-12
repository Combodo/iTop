<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Git\Diff;

abstract class FilterUtil
{
    /**
     * Converts a value into a valid diff filter array
     *
     * @param  mixed $value
     * @return array<int, string>
     */
    public static function filterFromConfigValue($value): array
    {
        return self::sanitize(
            is_array($value) ? $value : str_split((string) strtoupper($value === null ? '' : $value))
        );
    }

    /**
     * Remove all invalid filter options
     *
     * @param  array<int, string> $data
     * @return array<int, string>
     */
    public static function sanitize(array $data): array
    {
        return array_filter($data, fn($e) => in_array($e, ['A', 'C', 'D', 'M', 'R', 'T', 'U', 'X', 'B', '*']));
    }
}
