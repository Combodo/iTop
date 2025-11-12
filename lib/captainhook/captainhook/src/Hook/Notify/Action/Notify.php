<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\Notify\Action;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Exception\ActionFailed;
use CaptainHook\App\Hook\Action;
use CaptainHook\App\Hook\Constrained;
use CaptainHook\App\Hook\Notify\Extractor;
use CaptainHook\App\Hook\Notify\Notification;
use CaptainHook\App\Hook\Restriction;
use CaptainHook\App\Hook\Util;
use CaptainHook\App\Hooks;
use SebastianFeldmann\Cli\Processor\ProcOpen as Processor;
use SebastianFeldmann\Git\Repository;

/**
 * Class Notify
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.4.5
 */
class Notify implements Action, Constrained
{
    private const DEFAULT_PREFIX = 'git-notify:';

    /**
     * git-notify trigger
     *
     * @var string
     */
    private $prefix;

    /**
     * Returns a list of applicable hooks
     *
     * @return \CaptainHook\App\Hook\Restriction
     */
    public static function getRestriction(): Restriction
    {
        return Restriction::fromArray([Hooks::POST_CHECKOUT, Hooks::POST_MERGE, Hooks::POST_REWRITE]);
    }

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
        $this->prefix  = $action->getOptions()->get('prefix', self::DEFAULT_PREFIX);
        $oldHash       = Util::findPreviousHead($io);
        $newHash       = $io->getArgument(Hooks::ARG_NEW_HEAD, 'HEAD');

        $logOp = $repository->getLogOperator();
        $log   = $logOp->getCommitsBetween($oldHash, $newHash);

        foreach ($log as $commit) {
            $message = $commit->getSubject() . PHP_EOL . $commit->getBody();
            if ($this->containsNotification($message)) {
                $notification = Extractor::extractNotification($message, $this->prefix);
                $this->notify($io, $notification);
            }
        }
    }

    /**
     * Checks if the commit message contains the notification prefix 'git-notify:'
     *
     * @param  string $message
     * @return bool
     */
    private function containsNotification(string $message): bool
    {
        return strpos($message, $this->prefix) !== false;
    }

    /**
     * Write the notification to the
     *
     * @param  \CaptainHook\App\Console\IO               $io
     * @param  \CaptainHook\App\Hook\Notify\Notification $notification
     * @return void
     */
    private function notify(IO $io, Notification $notification): void
    {
        $io->write(['', '', $notification->banner(), '']);
    }
}
