<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Git\Rev;

/**
 * Util class
 *
 * Does some simple format and validation stuff
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.15.0
 */
abstract class Util
{
    /**
     * Indicates if commit hash is a zero hash 0000000000000000000000000000000000000000
     *
     * @param  string $hash
     * @return bool
     */
    public static function isZeroHash(string $hash): bool
    {
        return (bool) preg_match('/^0+$/', $hash);
    }

    /**
     * Splits remote and branch
     *
     *   origin/main     => ['remote' => 'origin', 'branch' => 'main']
     *   main            => ['remote' => 'origin', 'branch' => 'main']
     *   ref/origin/main => ['remote' => 'origin', 'branch' => 'main']
     *
     * @param string $ref
     * @return array<string, string>
     */
    public static function extractBranchInfo(string $ref): array
    {
        $ref   = (string) preg_replace('#^refs/#', '', $ref);
        $parts = explode('/', $ref);

        return [
            'remote' => count($parts) > 1 ? array_shift($parts) : 'origin',
            'branch' => implode('/', $parts),
        ];
    }
}
