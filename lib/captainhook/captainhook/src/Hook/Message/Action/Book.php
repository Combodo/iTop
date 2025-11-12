<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\Message\Action;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Console\IOUtil;
use CaptainHook\App\Exception\ActionFailed;
use CaptainHook\App\Hook\Action;
use CaptainHook\App\Hook\Constrained;
use CaptainHook\App\Hook\Message\RuleBook;
use CaptainHook\App\Hook\Restriction;
use CaptainHook\App\Hooks;
use SebastianFeldmann\Cli\Output\Util as OutputUtil;
use SebastianFeldmann\Git\Repository;

/**
 * Class Book
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
abstract class Book implements Action, Constrained
{
    /**
     * Returns a list of applicable hooks
     *
     * @return \CaptainHook\App\Hook\Restriction
     */
    public static function getRestriction(): Restriction
    {
        return Restriction::fromArray([Hooks::COMMIT_MSG]);
    }

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
    abstract public function execute(Config $config, IO $io, Repository $repository, Config\Action $action): void;

    /**
     * Validate the message
     *
     * @param  \CaptainHook\App\Hook\Message\RuleBook $ruleBook
     * @param  \SebastianFeldmann\Git\Repository      $repository
     * @param  \CaptainHook\App\Console\IO            $io
     * @return void
     * @throws \CaptainHook\App\Exception\ActionFailed
     */
    protected function validate(RuleBook $ruleBook, Repository $repository, IO $io): void
    {
        // if this is a merge commit skip enforcing message rules
        if ($repository->isMerging()) {
            return;
        }

        $problems = $ruleBook->validate($repository->getCommitMsg());

        if (count($problems)) {
            $this->errorOutput($problems, $io, $repository);
            throw new ActionFailed('commit message validation failed');
        }
    }

    /**
     * Write the error message
     *
     * @param array<string>                     $problems
     * @param \CaptainHook\App\Console\IO       $io
     * @param \SebastianFeldmann\Git\Repository $repository
     * @return void
     */
    private function errorOutput(array $problems, IO $io, Repository $repository): void
    {
        $s = count($problems) > 1 ? 's' : '';
        $io->write('found ' . count($problems) . ' problem' . $s . ' in your commit message');
        foreach ($problems as $problem) {
            $io->write($this->formatProblem($problem));
        }
        $io->write('<comment>--------------------------------------------[ your original message ]----</comment>');
        $io->write(OutputUtil::trimEmptyLines($repository->getCommitMsg()->getLines()));
        $io->write('<comment>-------------------------------------------------------------------------</comment>');
    }

    /**
     * Indent multi line problems so the lines after the first one are indented for better readability
     *
     * @param  string $problem
     * @return array<string>
     */
    private function formatProblem(string $problem): array
    {
        $lines = explode(PHP_EOL, $problem);
        foreach ($lines as $index => $line) {
            $lines[$index] = '  ' . $line;
        }
        return $lines;
    }
}
