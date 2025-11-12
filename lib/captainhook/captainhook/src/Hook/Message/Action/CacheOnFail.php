<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptainHook\App\Hook\Message\Action;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Exception\ActionFailed;
use CaptainHook\App\Hook\Action;
use CaptainHook\App\Hook\EventSubscriber;
use CaptainHook\App\Hook\Message\EventHandler\WriteCacheFile;
use SebastianFeldmann\Git\Repository;

/**
 * Class FailedStore
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.11.0
 */
class CacheOnFail implements Action, EventSubscriber
{
    /**
     * Execute the configured action
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
        // make sure the cache file is configured
        if (empty($action->getOptions()->get('file', ''))) {
            throw new ActionFailed('CacheOnFail requires \'file\' option');
        }
        return [
            'onHookFailure' => [new WriteCacheFile($action->getOptions()->get('file', ''))]
        ];
    }
}
