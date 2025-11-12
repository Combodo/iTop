<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Runner\Hook;

use CaptainHook\App\Runner\Action\Log as ActionLog;

class Log
{
    /**
     * List if all Action Logs
     *
     * @var array<\CaptainHook\App\Runner\Action\Log>
     */
    private array $logs = [];

    /**
     * Adds an action log to the hook log
     *
     * @param \CaptainHook\App\Runner\Action\Log $actionLog
     * @return void
     */
    public function addActionLog(ActionLog $actionLog): void
    {
        $this->logs[] = $actionLog;
    }

    /**
     * Checks if any of the collected action logs has a message to display
     *
     * @param int $verbosity
     * @return bool
     */
    public function hasMessageForVerbosity(int $verbosity): bool
    {
        foreach ($this->logs as $actionLog) {
            if ($actionLog->hasMessageForVerbosity($verbosity)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns all collected action logs
     *
     * @return array<\CaptainHook\App\Runner\Action\Log>
     */
    public function logs(): array
    {
        return $this->logs;
    }
}
