<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian.feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Operator;

use SebastianFeldmann\Git\Command\Log\ChangedFiles;
use SebastianFeldmann\Git\Command\Log\Commits;
use SebastianFeldmann\Git\Command\Log\Commits\Xml;
use SebastianFeldmann\Git\Command\Output\Exploded;
use SebastianFeldmann\Git\Command\RefLog\BranchRevs;

/**
 * Class Log
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 0.9.0
 */
class Log extends Base
{
    /**
     * Get the list of files that changed since a given revision.
     *
     * @param string        $revision
     * @param array<string> $filter
     * @return array<string>
     */
    public function getChangedFilesSince(string $revision, array $filter = []): array
    {
        $cmd    = (new ChangedFiles($this->repo->getRoot()))->byRange($revision)->withDiffFilter($filter);
        $result = $this->runner->run($cmd);

        return $result->getBufferedOutput();
    }

    /**
     * Get the list of files that changed since a given revision.
     *
     * @param  array<string> $revisions
     * @return array<string>
     */
    public function getChangedFilesInRevisions(array $revisions): array
    {
        $cmd    = (new ChangedFiles($this->repo->getRoot()))->byRevisions(...$revisions);
        $result = $this->runner->run($cmd);

        return $result->getBufferedOutput();
    }

    /**
     * Uses the reflog to return all commit hashes for a branch
     *
     * @param  string $branch
     * @return array<string>
     * @throws \Exception
     */
    public function getBranchRevsFromRefLog(string $branch): array
    {
        $result = $this->runner->run(
            (new BranchRevs($this->repo->getRoot()))->format('%h<--<|>-->%gs')->fromBranch($branch),
            new Exploded('<--<|>-->', ['shortHash', 'subject'])
        );
        $logs = $result->getFormattedOutput();
        $revs = [];
        foreach ($logs as $log) {
            if (stripos($log['subject'], 'branch: Created from') === false) {
                $revs[] = $log['shortHash'];
            }
        }
        return $revs;
    }

    /**
     * Uses the reflog to return the start hash of given branch
     *
     * @param  string $branch
     * @return string
     * @throws \Exception
     */
    public function getBranchRevFromRefLog(string $branch): string
    {
        $result = $this->runner->run(
            (new BranchRevs($this->repo->getRoot()))->format('%h<--<|>-->%gs')->fromBranch($branch),
            new Exploded('<--<|>-->', ['shortHash', 'subject'])
        );
        $logs   = $result->getFormattedOutput();
        foreach ($logs as $log) {
            if (stripos($log['subject'], 'branch: Created from') !== false) {
                return $log['shortHash'];
            }
        }
        return '';
    }

    /**
     * Get list of commits since given revision.
     *
     * @param  string $revision
     * @return array<\SebastianFeldmann\Git\Log\Commit>
     * @throws \Exception
     */
    public function getCommitsSince(string $revision): array
    {
        $cmd    = (new Commits($this->repo->getRoot()))->byRange($revision)->prettyFormat(Commits\Xml::FORMAT);
        $result = $this->runner->run($cmd);
        return Xml::parseLogOutput($result->getStdOut());
    }

    /**
     * Get list of commits between to given revisions
     *
     * @param  string $from
     * @param  string $to
     * @return array<\SebastianFeldmann\Git\Log\Commit>
     * @throws \Exception
     */
    public function getCommitsBetween(string $from, string $to): array
    {
        $cmd    = (new Commits($this->repo->getRoot()))->byRange($from, $to)->prettyFormat(Commits\Xml::FORMAT);
        $result = $this->runner->run($cmd);
        return Xml::parseLogOutput($result->getStdOut());
    }
}
