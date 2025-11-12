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

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Exception\ActionFailed;
use Exception;
use SebastianFeldmann\Git\Repository;

/**
 * Debug hook to test hook triggering
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 4.0.4
 */
abstract class Debug implements Action
{
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
    abstract public function execute(Config $config, IO $io, Repository $repository, Config\Action $action): void;

    /**
     * Generate some debug output
     *
     * @param \CaptainHook\App\Console\IO       $io
     * @param \SebastianFeldmann\Git\Repository $repository
     * @return void
     */
    protected function debugOutput(IO $io, Repository $repository): void
    {
        $originalHookArguments = $io->getArguments();

        $currentGitTag = 'no tags yet';
        try {
            $currentGitTag = $repository->getInfoOperator()->getCurrentTag();
        } catch (Exception $e) {
            // ignore it, it just means there are no tags yet
        }
        $io->write($this->getArgumentOutput($originalHookArguments), false);
        $io->write('  <comment>Current git-tag:</comment> ' . $currentGitTag);
        $io->write(
            '  <comment>StandardInput:</comment> ' . PHP_EOL .
            '    ' . implode(PHP_EOL . '    ', $io->getStandardInput())
        );
    }

    /**
     * Format output to display original hook arguments
     *
     * Returns a string with a newline character at the end.
     *
     * @param  array<string> $args
     * @return string
     */
    protected function getArgumentOutput(array $args): string
    {
        $out = '  <comment>Original arguments:</comment>' . PHP_EOL;
        foreach ($args as $name => $value) {
            $out .= '    <fg=cyan>' . $name . ' =></> ' . $value . PHP_EOL;
        }
        return $out;
    }
}
