<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\UserInput;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Action;
use CaptainHook\App\Hook\EventSubscriber;
use SebastianFeldmann\Git\Repository;

/**
 * Debug hook to test hook triggering that fails the hook execution
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.20.1
 */
class AskConfirmation implements Action, EventSubscriber
{
    /**
     * Default question to ask the user
     *
     * @var string
     */
    private static string $defaultMessage = 'Do you want to continue? [yes|no]';

    /**
     * Executes the action
     *
     * @param  \CaptainHook\App\Config           $config
     * @param  \CaptainHook\App\Console\IO       $io
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @param  \CaptainHook\App\Config\Action    $action
     * @return void
     * @throws \Exception
     */
    public function execute(Config $config, IO $io, Repository $repository, Config\Action $action): void
    {
        // this action is just registering some event handler, so nothing to see here
    }

    /**
     * Returns a list of event handlers
     *
     * @param  \CaptainHook\App\Config\Action   $action
     * @return array<string, array<int, \CaptainHook\App\Event\Handler>>
     * @throws \Exception
     */
    public static function getEventHandlers(Config\Action $action): array
    {
        $msg     = $action->getOptions()->get('message', self::$defaultMessage);
        $default = (bool) $action->getOptions()->get('default', false);
        return [
            'onHookSuccess' => [new EventHandler\AskConfirmation($msg, $default)]
        ];
    }
}
