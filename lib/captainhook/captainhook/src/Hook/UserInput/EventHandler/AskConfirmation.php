<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\UserInput\EventHandler;

use CaptainHook\App\Console\IOUtil;
use CaptainHook\App\Event;
use CaptainHook\App\Event\Handler;
use CaptainHook\App\Exception\ActionFailed;

/**
 * Writes to commit message cache file to load it for a later commit
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.11.0
 */
class AskConfirmation implements Handler
{
    /**
     * Question to ask
     *
     * @var string
     */
    private string $question;

    /**
     * No input ok or not
     *
     * @var bool
     */
    private bool $default;

    /**
     * @param string $question
     * @param bool   $default
     */
    public function __construct(string $question, bool $default = false)
    {
        $this->question = $question;
        $this->default  = $default;
    }

    /**
     * Writes the commit message to a cache file to reuse it for the next commit
     *
     * @param \CaptainHook\App\Event $event
     * @return void
     * @throws \CaptainHook\App\Exception\ActionFailed
     */
    public function handle(Event $event): void
    {
        if (!IOUtil::answerToBool($event->io()->ask(PHP_EOL .  $this->question . ' ', $this->default ? 'y' : 'n'))) {
            throw new ActionFailed('no confirmation, abort!');
        }
    }
}
