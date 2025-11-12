<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\Message\EventHandler;

use CaptainHook\App\Event;
use CaptainHook\App\Event\Handler;

/**
 * Writes to commit message cache file to load it for a later commit
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.11.0
 */
class WriteCacheFile implements Handler
{
    /**
     * Path to the commit message cache file
     *
     * @var string
     */
    private $file;

    /**
     * @param string $file
     */
    public function __construct(string $file)
    {
        $this->file = $file;
    }
    /**
     * Writes the commit message to a cache file to reuse it for the next commit
     *
     * @return void
     */
    public function handle(Event $event)
    {
        $msg  = $event->repository()->getCommitMsg()->getRawContent();
        $path = $event->repository()->getRoot() . '/' . $this->file;
        file_put_contents($path, $msg);
    }
}
