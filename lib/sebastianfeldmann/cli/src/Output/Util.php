<?php

/**
 * This file is part of SebastianFeldmann\Cli.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Cli\Output;

/**
 * Class Util
 *
 * @package SebastianFeldmann\Cli
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/cli
 * @since   Class available since Release 2.1.0
 */
class Util
{
    /**
     * Remove empty entries at the end of an array.
     *
     * @param  array $lines
     * @return array
     */
    public static function trimEmptyLines(array $lines): array
    {
        for ($last = count($lines) - 1; $last > -1; $last--) {
            if (!empty($lines[$last])) {
                return $lines;
            }
            unset($lines[$last]);
        }
        return $lines;
    }

    /**
     * Replaces all 'known' line endings with unix \n line endings
     *
     * @param  string $text
     * @return string
     */
    public static function normalizeLineEndings(string $text): string
    {
        $mod = preg_match('/[\p{Cyrillic}]/u', $text) ? 'u' : '';
        return preg_replace('~(*BSR_UNICODE)\R~' . $mod, "\n", $text);
    }
}
