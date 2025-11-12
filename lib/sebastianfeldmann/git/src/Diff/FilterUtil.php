<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Diff;

/**
 * Filter utility class
 *
 *
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 3.9.0
 */
abstract class FilterUtil
{
    /**
     * Remove all invalid filter options
     *
     * @param  array<int, string> $filter
     * @return array<int, string>
     */
    public static function sanitize(array $filter): array
    {
        return array_filter($filter, function ($e) {
            return in_array($e, ['A', 'C', 'D', 'M', 'R', 'T', 'U', 'X', 'B', '*']);
        });
    }
}
