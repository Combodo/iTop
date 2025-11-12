<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Console;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * IOUtil class
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
abstract class IOUtil
{
    public const PREFIX_OK = '<info>✔</info>';
    public const PREFIX_FAIL = '<fg=red>✘</>';

    /**
     * Maps config values to Symfony verbosity values
     *
     * @var array<string, 16|32|64|128|256>
     */
    private static array $verbosityMap = [
        'quiet'        => OutputInterface::VERBOSITY_QUIET,
        'normal'       => OutputInterface::VERBOSITY_NORMAL,
        'verbose'      => OutputInterface::VERBOSITY_VERBOSE,
        'very-verbose' => OutputInterface::VERBOSITY_VERY_VERBOSE,
        'debug'        => OutputInterface::VERBOSITY_DEBUG
    ];

    /**
     * Return the Symfony verbosity for a given config value
     *
     * @param string $verbosity
     * @return OutputInterface::VERBOSITY_*
     */
    public static function mapConfigVerbosity(string $verbosity): int
    {
        return self::$verbosityMap[strtolower($verbosity)] ?? OutputInterface::VERBOSITY_NORMAL;
    }

    /**
     * Convert a user answer to boolean
     *
     * @param  string $answer
     * @return bool
     */
    public static function answerToBool(string $answer): bool
    {
        return in_array(strtolower($answer), ['y', 'yes', 'ok']);
    }

    /**
     * Create formatted cli headline
     *
     * ">>>> HEADLINE <<<<"
     * "==== HEADLINE ===="
     *
     * @param  string $headline
     * @param  int    $length
     * @param  string $pre
     * @param  string $post
     * @return string
     */
    public static function formatHeadline(string $headline, int $length, string $pre = '=', string $post = '='): string
    {
        $headlineLength = mb_strlen($headline);
        if ($headlineLength > ($length - 3)) {
            return $headline;
        }

        $prefix = (int) floor(($length - $headlineLength - 2) / 2);
        $suffix = (int) ceil(($length - $headlineLength - 2) / 2);

        return str_repeat($pre, $prefix) . ' ' . $headline . ' ' . str_repeat($post, $suffix);
    }

    /**
     * Convert everything to a string
     *
     * @param  array<string>|bool|string|null $arg
     * @param  string                         $default
     * @return string
     */
    public static function argToString(mixed $arg, string $default = ''): string
    {
        return is_string($arg) ? $arg : $default;
    }

    /**
     * Convert everything to a boolean
     *
     * @param  array<string>|bool|string|null $arg
     * @param  bool                           $default
     * @return bool
     */
    public static function argToBool(mixed $arg, bool $default = false): bool
    {
        return is_bool($arg) ? $arg : $default;
    }
}
