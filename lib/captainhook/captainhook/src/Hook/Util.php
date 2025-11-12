<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook;

use CaptainHook\App\Console\IO;
use CaptainHook\App\Hooks;
use RuntimeException;

/**
 * Class Util
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
final class Util
{
    /**
     * Checks if a hook name is valid
     *
     * @param  string $hook
     * @return bool
     */
    public static function isValid(string $hook): bool
    {
        return isset(Hooks::getValidHooks()[$hook]);
    }

    /**
     * Answers if a hook is installable
     *
     * Only native hooks are installable, virtual hooks used by the Cap'n should not be installed.
     *
     * @param  string $hook
     * @return bool
     */
    public static function isInstallable(string $hook): bool
    {
        return isset(Hooks::nativeHooks()[$hook]);
    }

    /**
     * Returns list of valid hooks
     *
     * @return array<string>
     */
    public static function getValidHooks(): array
    {
        return Hooks::getValidHooks();
    }

    /**
     * Returns hooks command class
     *
     * @param  string $hook
     * @return string
     */
    public static function getHookCommand(string $hook): string
    {
        if (!self::isValid($hook)) {
            throw new RuntimeException(sprintf('Hook \'%s\' is not supported', $hook));
        }
        return Hooks::getValidHooks()[$hook];
    }

    /**
     * Get a list of all supported hooks
     *
     * @return array<string>
     */
    public static function getHooks(): array
    {
        return array_keys(Hooks::getValidHooks());
    }

    /**
     * Checks if a given hook was executed
     *
     * @param \CaptainHook\App\Console\IO $io
     * @param string                      $hook
     * @return bool
     */
    public static function isRunningHook(IO $io, string $hook): bool
    {
        return str_contains($io->getArgument(Hooks::ARG_COMMAND), $hook);
    }

    /**
     * Detects the previous head commit hash
     *
     * @param \CaptainHook\App\Console\IO $io
     * @return string
     */
    public static function findPreviousHead(IO $io): string
    {
        // Check if a list of rewritten commits is supplied via stdIn.
        // This happens if the 'post-rewrite' hook is triggered.
        // The stdIn is formatted like this:
        //
        // old-hash new-hash extra-info
        // old-hash new-hash extra-info
        // ...
        $stdIn = $io->getStandardInput();
        if (!empty($stdIn)) {
            $info = explode(' ', $stdIn[0]);
            // If we find a rewritten commit, we return the first commit before the rewritten one.
            // If we do not find any rewritten commits (awkward) we use the last ref-log position.
            return isset($info[1]) ? trim($info[1]) . '^' :  'HEAD@{1}';
        }

        return $io->getArgument(Hooks::ARG_PREVIOUS_HEAD, 'HEAD@{1}');
    }
}
