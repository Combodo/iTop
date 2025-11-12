<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Git\ChangedFiles\Detector;

use CaptainHook\App\Console\IO;
use CaptainHook\App\Git\ChangedFiles\Detector;
use CaptainHook\App\Git\Range\Detector\PrePush as RangeDetector;
use CaptainHook\App\Git\Range\PrePush as Range;
use SebastianFeldmann\Git\Repository;

/**
 * Class PrePush
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.20.0
 */
class PrePush extends Detector
{
    /**
     * Reflog fallback switch
     *
     * @var bool
     */
    private bool $reflogFallback = false;

    /**
     * Activate the reflog fallback file detection
     *
     * @param bool $bool
     * @return void
     */
    public function useReflogFallback(bool $bool): void
    {
        $this->reflogFallback = $bool;
    }

    /**
     * Return list of changed files
     *
     * @param  array<string> $filter
     * @return array<string>
     */
    public function getChangedFiles(array $filter = []): array
    {
        $ranges = $this->getRanges();
        if (empty($ranges)) {
            return [];
        }
        $files = $this->collectChangedFiles($ranges, $filter);
        if (count($files) > 0 || !$this->reflogFallback) {
            return $files;
        }
        // by now we should have found something, but if the "branch: created" entry is gone from the reflog,
        // try to find as many commits belonging to this branch as possible
        $branch    = $ranges[0]->to()->branch();
        $revisions = $this->repository->getLogOperator()->getBranchRevsFromRefLog($branch);
        return $this->repository->getLogOperator()->getChangedFilesInRevisions($revisions);
    }

    /**
     * Create ranges from stdIn
     *
     * @return array<\CaptainHook\App\Git\Range\PrePush>
     */
    private function getRanges(): array
    {
        $detector = new RangeDetector();
        return $detector->getRanges($this->io);
    }

    /**
     * Collect all changed files from all ranges
     *
     * @param  array<\CaptainHook\App\Git\Range\PrePush> $ranges
     * @param  array<string> $filter
     * @return array<string>
     */
    private function collectChangedFiles(array $ranges, array $filter): array
    {
        $files = [];
        foreach ($ranges as $range) {
            if ($this->isKnownBranch($range)) {
                $oldHash = $range->from()->id();
                $newHash = $range->to()->id();
            } else {
                if (!$this->reflogFallback) {
                    continue;
                }
                // remote branch does not exist
                // try to find the branch starting point with the reflog
                $oldHash = $this->repository->getLogOperator()->getBranchRevFromRefLog($range->to()->branch());
                $newHash = 'HEAD';
            }
            if (!empty($oldHash)) {
                $files[] = $this->repository->getDiffOperator()->getChangedFiles($oldHash, $newHash, $filter);
            }
        }
        return array_unique(array_merge(...$files));
    }

    /**
     * If the remote branch is known the diff can  be easily determined
     *
     * @param  \CaptainHook\App\Git\Range\PrePush $range
     * @return bool
     */
    private function isKnownBranch(Range $range): bool
    {
        return !$range->from()->isZeroRev();
    }
}
