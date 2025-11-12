<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Runner\Action;

use CaptainHook\App\Config\Action;

/**
 * Class Log
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.19.0
 */
class Log
{
    public const ACTION_SUCCEEDED   = 0;
    public const ACTION_SKIPPED     = 1;
    public const ACTION_DEACTIVATED = 2;
    public const ACTION_FAILED      = 4;

    /**
     * @var \CaptainHook\App\Config\Action
     */
    private Action $action;

    /**
     * Was the action successful
     *
     * @var int
     */
    private int $status;

    /**
     * @var array<\CaptainHook\App\Console\IO\Message>
     */
    private array $log = [];

    /**
     * @param \CaptainHook\App\Config\Action             $action
     * @param int                                        $status
     * @param array<\CaptainHook\App\Console\IO\Message> $log
     */
    public function __construct(Action $action, int $status, array $log)
    {
        $this->action = $action;
        $this->status = $status;
        $this->log    = $log;
    }

    /**
     * Returns the action name
     *
     * @return string
     */
    public function name(): string
    {
        return $this->action->getLabel();
    }

    /**
     * Returns the action status
     *
     * @return int
     */
    public function status(): int
    {
        return $this->status;
    }

    /**
     * Returns the list of messages
     *
     * @return array<\CaptainHook\App\Console\IO\Message>
     */
    public function messages(): array
    {
        return $this->log;
    }

    /**
     * Check if the log has collected a message that should be displayed at a given verbosity
     *
     * @param  int $verbosity
     * @return bool
     */
    public function hasMessageForVerbosity(int $verbosity): bool
    {
        foreach ($this->log as $msg) {
            if ($msg->verbosity() <= $verbosity) {
                return true;
            }
        }
        return false;
    }
}
