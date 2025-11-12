<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Runner\Action;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Exception;
use CaptainHook\App\Runner\Action as ActionRunner;
use CaptainHook\App\Runner\Action\Cli\Command\Formatter;
use SebastianFeldmann\Cli\Processor\Symfony as Processor;
use SebastianFeldmann\Git\Repository;

/**
 * Class Cli
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 * @internal
 */
class Cli implements ActionRunner
{
    /**
     * Execute the configured action
     *
     * @param \CaptainHook\App\Config           $config
     * @param \CaptainHook\App\Console\IO       $io
     * @param \SebastianFeldmann\Git\Repository $repository
     * @param \CaptainHook\App\Config\Action    $action
     * @return void
     * @throws \CaptainHook\App\Exception\ActionFailed
     */
    public function execute(Config $config, IO $io, Repository $repository, Config\Action $action): void
    {
        $processor    = new Processor();
        $cmdOriginal  = $action->getAction();
        $cmdFormatted = $this->formatCommand($io, $config, $repository, $cmdOriginal);

        $io->write(
            '  <comment>cmd:</comment> ' . $cmdFormatted,
            true,
            IO::VERBOSE
        );

        $result = $processor->run($cmdFormatted);
        $output = '';

        if (!empty($result->getStdOut())) {
            $output .= PHP_EOL . $result->getStdOut();
        }
        if (!empty($result->getStdErr())) {
            $output .= PHP_EOL . $result->getStdErr();
        }

        if (!empty($output)) {
            $io->write(
                ['  <comment>command output:</comment>', trim($output)],
                true,
                !$result->isSuccessful() ? IO::NORMAL : IO::VERBOSE
            );
        }

        if (!$result->isSuccessful()) {
            throw new Exception\ActionFailed(
                'failed to execute: ' . $cmdFormatted
            );
        }
    }

    /**
     * Replace argument placeholder with their original values
     *
     * This replaces the hook argument and other placeholders
     *  - prepare-commit-msg => FILE, MODE, HASH
     *  - commit-msg         => FILE
     *  - pre-push           => TARGET, URL
     *  - pre-commit         => -
     *  - post-checkout      => PREVIOUSHEAD, NEWHEAD, MODE
     *  - post-merge         => SQUASH
     *
     * @param \CaptainHook\App\Console\IO       $io
     * @param \CaptainHook\App\Config           $config
     * @param \SebastianFeldmann\Git\Repository $repository
     * @param string                            $command
     * @return string
     */
    protected function formatCommand(IO $io, Config $config, Repository $repository, string $command): string
    {
        $formatter = new Formatter($io, $config, $repository);
        return $formatter->format($command);
    }
}
