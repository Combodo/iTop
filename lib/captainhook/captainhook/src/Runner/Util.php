<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Runner;

/**
 * Class Util
 *
 * @package CaptainHook\App\Runner
 */
final class Util
{
    /**
     * List of valid action types
     *
     * @var array<bool>
     */
    private static $validTypes = ['php' => true, 'cli' => true];


    /**
     * Check the validity of a exec type
     *
     * @param  string $type
     * @return bool
     */
    public static function isTypeValid(string $type): bool
    {
        return isset(self::$validTypes[$type]);
    }

    /**
     * Return action type
     *
     * @param  string $action
     * @return string
     */
    public static function getExecType(string $action): string
    {
        return substr($action, 0, 1) === '\\' ? 'php' : 'cli';
    }

    /**
     * Try to read an environment variable
     *
     * @param  string $name
     * @param  string $default
     * @return string
     */
    public static function getEnv(string $name, string $default = ''): string
    {
        $var = getenv($name);
        return $var ?: $_ENV[$name] ?? $_SERVER[$name] ?? $default;
    }
}
