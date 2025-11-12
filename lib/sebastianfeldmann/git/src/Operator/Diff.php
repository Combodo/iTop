<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Operator;

use SebastianFeldmann\Git\Command\Apply\ApplyPatch;
use SebastianFeldmann\Git\Command\Diff\Compare;
use SebastianFeldmann\Git\Command\DiffIndex\GetUnstagedPatch;
use SebastianFeldmann\Git\Command\Diff\ChangedFiles as DiffChangedFiles;
use SebastianFeldmann\Git\Command\DiffTree\ChangedFiles as DiffTreeChangedFiles;
use SebastianFeldmann\Git\Command\WriteTree\CreateTreeObject;

/**
 * Diff operator
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 1.2.0
 */
class Diff extends Base
{
    /**
     * Returns a list of files and their changes.
     *
     * @param  string $from
     * @param  string $to
     * @return \SebastianFeldmann\Git\Diff\File[]
     */
    public function compare(string $from, string $to): array
    {
        $compare = (new Compare($this->repo->getRoot()))->revisions($from, $to)
                                                        ->ignoreWhitespacesAtEndOfLine();

        $result = $this->runner->run($compare, new Compare\FullDiffList());

        return $result->getFormattedOutput();
    }

    /**
     * Returns a list of files and their changes staged for the next commit
     *
     * @param  string $to
     * @return \SebastianFeldmann\Git\Diff\File[]
     */
    public function compareIndexTo(string $to = 'head'): array
    {
        $compare = (new Compare($this->repo->getRoot()))->indexTo($to)
                                                        ->withContextLines(0)
                                                        ->ignoreWhitespacesAtEndOfLine();

        $result = $this->runner->run($compare, new Compare\FullDiffList());

        return $result->getFormattedOutput();
    }

    /**
     * Returns a list of files and their changes not yet staged
     *
     * @param string $to
     * @return \SebastianFeldmann\Git\Diff\File[]
     */
    public function compareTo(string $to = 'HEAD'): array
    {
        $compare = (new Compare($this->repo->getRoot()))->to($to)
                                                        ->ignoreSubmodules()
                                                        ->withContextLines(0);

        $result = $this->runner->run($compare, new Compare\FullDiffList());

        return $result->getFormattedOutput();
    }

    /**
     * Uses 'diff-tree' to list the files that changed between two revisions
     *
     * @param  string        $from
     * @param  string        $to
     * @param  array<string> $filter
     * @return string[]
     */
    public function getChangedFiles(string $from, string $to, array $filter = []): array
    {
        $cmd    = (new DiffTreeChangedFiles($this->repo->getRoot()))->fromRevision($from)
                                                                    ->toRevision($to)
                                                                    ->useFilter($filter);
        $result = $this->runner->run($cmd);

        return $result->getBufferedOutput();
    }

    /**
     * Uses 'diff' to list the files that changed
     *
     * List files that changed in a branch (to) since it diverged (branched of) from another branch (from).
     * Does not include changes that are not reachable from to.
     *
     * @param  string        $from   Base branch
     * @param  string        $to     Diverged (feature) branch
     * @param  array<string> $filter A|C|D|M|R|T|U|X|B Added, Copied, Deleted, Modified, Renamed, Type changed...
     * @return string[]
     */
    public function getChangedFilesSinceBranch(string $from, string $to, array $filter = []): array
    {
        $cmd    = (new DiffChangedFiles($this->repo->getRoot()))->mergeBase()
                                                                ->fromRevision($from)
                                                                ->toRevision($to)
                                                                ->useFilter($filter);
        $result = $this->runner->run($cmd);

        return $result->getBufferedOutput();
    }

    /**
     * Uses 'diff-tree' to list the files with a given suffix that changed between two revisions
     *
     * @param  string        $from
     * @param  string        $to
     * @param  string        $suffix
     * @param  array<string> $filter
     * @return string[]
     */
    public function getChangedFilesOfType(string $from, string $to, string $suffix, array $filter = []): array
    {
        $suffix      = strtolower($suffix);
        $cmd         = (new DiffTreeChangedFiles($this->repo->getRoot()))->fromRevision($from)
                                                                 ->toRevision($to)
                                                                 ->useFilter($filter);
        $result      = $this->runner->run($cmd);
        $files       = $result->getBufferedOutput();
        $filesByType = [];

        foreach ($files as $file) {
            $ext                 = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $filesByType[$ext][] = $file;
        }
        return $filesByType[$suffix] ?? [];
    }

    /**
     * Returns a binary diff of unstaged changes to the working tree that can be
     * applied with `git-apply`.
     *
     * @return string|null String patch, if there are unstaged changes; null otherwise.
     */
    public function getUnstagedPatch(): ?string
    {
        $treeCmd = new CreateTreeObject($this->repo->getRoot());
        $treeResult = $this->runner->run($treeCmd);

        $treeId = null;
        if ($treeResult->isSuccessful()) {
            $treeId = trim($treeResult->getStdOut());
        }

        $cmd = (new GetUnstagedPatch($this->repo->getRoot()))->tree($treeId);
        $result = $this->runner->run($cmd);

        // A status code of 1 means there were differences, and we have a patch.
        if ($result->getCode() === 1) {
            return $result->getStdOut();
        }

        return null;
    }

    /**
     * Applies the supplied diff patches to files.
     *
     * @param string[] $patches An array of paths to patch files.
     * @param bool $disableAutoCrlfSetting If true, explicitly set core.autocrlf
     *     to "false" to override the global Git configuration.
     * @return bool True if the patches apply cleanly.
     */
    public function applyPatches(array $patches, bool $disableAutoCrlfSetting = false): bool
    {
        $cmd = (new ApplyPatch($this->repo->getRoot()))
            ->patches($patches)
            ->whitespace('nowarn');

        if ($disableAutoCrlfSetting === true) {
            $cmd->setConfigParameter('core.autocrlf', false);
        }

        $result = $this->runner->run($cmd);

        return $result->isSuccessful();
    }
}
