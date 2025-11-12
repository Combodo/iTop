<?php

/**
 * This file is part of camino.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Camino;

/**
 * Class Util
 *
 * @package SebastianFeldmann\Camino
 */
abstract class Check
{
    /**
     * Is given path absolute?
     *
     * @param  string $path
     * @return bool
     */
    public static function isAbsolutePath(string $path): bool
    {
        // path already absolute?
        if (substr($path, 0, 1) === '/') {
            return true;
        }
        if (self::isStream($path)) {
            return true;
        }
        if (self::isAbsoluteWindowsPath($path)) {
            return true;
        }
        return false;
    }

    /**
     * Is given path a stream reference?
     *
     * @param string $path
     * @return bool
     */
    public static function isStream(string $path): bool
    {
        return strpos($path, '://') !== false;
    }

    /**
     * Is given path an absolute windows path?
     *
     * matches the following on Windows:
     *  - \\NetworkComputer\Path
     *  - \\.\D:
     *  - \\.\c:
     *  - C:\Windows
     *  - C:\windows
     *  - C:/windows
     *  - c:/windows
     *
     * @param  string $path
     * @return bool
     */
    public static function isAbsoluteWindowsPath(string $path): bool
    {
        return ($path[0] === '\\' || (strlen($path) >= 3 && preg_match('#^[A-Z]\:[/\\\]#i', substr($path, 0, 3))));
    }
}
