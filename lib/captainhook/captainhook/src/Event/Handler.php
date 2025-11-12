<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Event;

use CaptainHook\App\Event;

/**
 * Interface EventListener
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.11.0
 */
interface Handler
{
    /**
     * Executes the handler to handle the given event
     *
     * @param  \CaptainHook\App\Event $event
     * @return void
     * @throws \Exception
     */
    public function handle(Event $event);
}
