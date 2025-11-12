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

use CaptainHook\App\Config\Action as ActionConfig;

/**
 * Interface EventSubscriber
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.11.0
 */
interface EventSubscriber
{
    /**
     * Returns a list of event handlers
     *
     * @param  \CaptainHook\App\Config\Action $action
     * @return array<string, array<int, \CaptainHook\App\Event\Handler>>
     * @throws \Exception
     */
    public static function getEventHandlers(ActionConfig $action): array;
}
