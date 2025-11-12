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

use CaptainHook\App\Config\Action;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Runner\Action\Log as ActionLog;
use CaptainHook\App\Runner\Hook;

/**
 * Class Printer
 *
 * Is handling the output for the hook execution
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.19.0
 */
class Printer
{
    /**
     * @var \CaptainHook\App\Console\IO
     */
    private IO $io;

    /**
     * Current verbosity
     *
     * @var int
     */
    private int $verbosity;

    /**
     * Constructor
     *
     * @param \CaptainHook\App\Console\IO $io
     */
    public function __construct(IO $io)
    {
        $this->io = $io;
        if ($io->isDebug()) {
            $this->verbosity = IO::DEBUG;
        } elseif ($io->isVeryVerbose()) {
            $this->verbosity = IO::VERY_VERBOSE;
        } elseif ($io->isVerbose()) {
            $this->verbosity = IO::VERBOSE;
        } else {
            $this->verbosity = IO::NORMAL;
        }
    }

    /**
     * Prints the action success line
     *
     * @param \CaptainHook\App\Config\Action $action
     * @return void
     */
    public function actionSucceeded(Action $action): void
    {
        $this->io->write($this->actionHeadline($action) . '<info>done</info>');
    }

    /**
     * Prints the action skipped line
     *
     * @param \CaptainHook\App\Config\Action $action
     * @return void
     */
    public function actionSkipped(Action $action): void
    {
        $this->io->write($this->actionHeadline($action) . '<comment>skipped</comment>');
    }

    /**
     * Prints the action failed line
     *
     * @param \CaptainHook\App\Config\Action $action
     * @return void
     */
    public function actionFailed(Action $action): void
    {
        $this->io->write($this->actionHeadline($action) . '<fg=red>failed</>');
    }

    /**
     * Prints the action deactivated line
     *
     * @param \CaptainHook\App\Config\Action $action
     * @return void
     */
    public function actionDeactivated(Action $action): void
    {
        $this->io->write($this->actionHeadline($action) . '<comment>deactivated</comment>');
    }

    private function actionHeadline(Action $action): string
    {
        return ' - <fg=blue>' . $this->formatActionHeadline($action->getLabel()) . '</> : ';
    }

    /**
     * Some fancy output formatting
     *
     * @param  string $action
     * @return string
     */
    private function formatActionHeadline(string $action): string
    {
        $actionLength = 65;
        if (mb_strlen($action) < $actionLength) {
            return str_pad($action, $actionLength, ' ');
        }

        return mb_substr($action, 0, $actionLength - 3) . '...';
    }

    /**
     * Prints the action log
     *
     * @param int                              $status
     * @param \CaptainHook\App\Runner\Hook\Log $log
     * @param float                            $seconds
     * @return void
     */
    public function hookEnded(int $status, Log $log, float $seconds): void
    {
        $msg = '<fg=green>captainhook executed all actions successfully, took: %01.2fs</>';
        if ($status === Hook::HOOK_FAILED) {
            $msg = '<fg=red>captainhook failed executing all actions, took: %01.2fs</>';
        }
        $this->io->write(sprintf($msg, $seconds));

        $tags = [
            ActionLog::ACTION_FAILED    => 'fg=red',
            ActionLog::ACTION_SKIPPED   => 'fg=yellow',
            ActionLog::ACTION_SUCCEEDED => 'fg=green',
        ];
        if ($log->hasMessageForVerbosity($this->verbosity)) {
            $this->io->write('');
            foreach ($log->logs() as $actionLog) {
                if ($actionLog->hasMessageForVerbosity($this->verbosity)) {
                    $tag = $tags[$actionLog->status()];
                    $this->io->write('<' . $tag . '>' . $actionLog->name() . '</>');
                    foreach ($actionLog->messages() as $msg) {
                        $this->io->write($msg->message(), $msg->newLine(), $msg->verbosity());
                    }
                }
            }
        }
    }
}
