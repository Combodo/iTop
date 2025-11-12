<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\Notify;

/**
 * Class Extractor
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.4.5
 */
class Extractor
{
    /**
     * Find the notification inside a commit message and return a Notification model
     *
     * @param  string $message
     * @param  string $prefix
     * @return \CaptainHook\App\Hook\Notify\Notification
     */
    public static function extractNotification(string $message, string $prefix = 'git-notify:'): Notification
    {
        return new Notification(self::getLines($message, $prefix));
    }

    /**
     * @param  string $message
     * @param  string $prefix
     * @return array<string>
     */
    private static function getLines(string $message, string $prefix): array
    {
        $matches = [];
        if (preg_match('#' . $prefix . '(.*)#is', $message, $matches)) {
            $split = preg_split("/\r\n|\n|\r/", $matches[1]);

            return is_array($split) ? array_map('trim', $split) : [];
        }
        return [];
    }
}
