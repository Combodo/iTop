<?php

/**
 * This file is part of CaptainHook Secrets.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptainHook\Secrets\Entropy;

/**
 * Secret Detector
 *
 * @package CaptainHook-Secrets
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/secrets
 * @since   Class available since Release 0.9.1
 */
class Shannon
{
    /**
     * Return the entropy of a given string
     *
     * @param string $text
     * @return float
     */
    public static function entropy(string $text): float
    {
        if (empty($text)) {
            return 0;
        }

        $charMap = [];
        foreach (mb_str_split($text) as $char) {
            $charMap[$char] = isset($charMap[$char]) ? $charMap[$char] + 1 : 1;
        }

        $entropy = 0;
        $length  = strlen($text);
        foreach ($charMap as $char => $amount) {
            $p       = $amount / $length;
            $entropy = $entropy - ($p * log($p, 2));
        }
        return $entropy;
    }
}
