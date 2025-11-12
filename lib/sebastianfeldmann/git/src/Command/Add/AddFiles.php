<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\Add;

use SebastianFeldmann\Git\Command\Base;

/**
 * Class AddFiles
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 3.7.0
 */
class AddFiles extends Base
{
    /**
     * Dry run.
     *
     * @var string
     */
    private string $dryRun = '';

    /**
     * Update.
     *
     * @var string
     */
    private string $update = '';

    /**
     * All.
     *
     * @var string
     */
    private string $all = '';

    /**
     * No all.
     *
     * @var string
     */
    private string $noAll = '';

    /**
     * Intent to add.
     *
     * @var string
     */
    private string $intentToAdd = '';

    /**
     * Files to add content from to the index.
     *
     * @var string[]
     */
    private array $files = [];

    /**
     * Set dry run.
     *
     * @param  bool $bool
     *
     * @return \SebastianFeldmann\Git\Command\Add\AddFiles
     */
    public function dryRun(bool $bool = true): AddFiles
    {
        $this->dryRun = $this->useOption('--dry-run', $bool);
        return $this;
    }

    /**
     * Update the index just where it already has an entry matching <pathspec>.
     *
     * This removes as well as modifies index entries to match the working
     * tree, but adds no new files.
     *
     * @param bool $bool
     *
     * @return \SebastianFeldmann\Git\Command\Add\AddFiles
     */
    public function update(bool $bool = true): AddFiles
    {
        $this->update = $this->useOption('--update', $bool);
        return $this;
    }

    /**
     * Update the index not only where the working tree has a file matching
     * <pathspec> but also where the index already has an entry.
     *
     * This adds, modifies, and removes index entries to match the working tree.
     *
     * @param bool $bool
     *
     * @return \SebastianFeldmann\Git\Command\Add\AddFiles
     */
    public function all(bool $bool = true): AddFiles
    {
        $this->all = $this->useOption('--all', $bool);
        return $this;
    }

    /**
     * Update the index by adding new files that are unknown to the index and
     * files modified in the working tree, but ignore files that have been
     * removed from the working tree.
     *
     * @param bool $bool
     *
     * @return \SebastianFeldmann\Git\Command\Add\AddFiles
     */
    public function noAll(bool $bool = true): AddFiles
    {
        $this->noAll = $this->useOption('--no-all', $bool);
        return $this;
    }

    /**
     * Record only the fact that the path will be added later.
     *
     * An entry for the path is placed in the index with no content.
     *
     * @param bool $bool
     *
     * @return \SebastianFeldmann\Git\Command\Add\AddFiles
     */
    public function intentToAdd(bool $bool = true): AddFiles
    {
        $this->intentToAdd = $this->useOption('--intent-to-add', $bool);
        return $this;
    }

    /**
     * Files to add content from to the index.
     *
     * Fileglobs (e.g. `*.c`) can be given to add all matching files. Also a
     * leading directory name (e.g. `dir` to add `dir/file1` and `dir/file2`)
     * can be given to update the index to match the current state of the
     * directory as a whole (e.g. specifying `dir` will record not just a file
     * `dir/file1` modified in the working tree, a file `dir/file2` added to the
     * working tree, but also a file `dir/file3` removed from the working tree).
     *
     * @param array<string> $files
     *
     * @return \SebastianFeldmann\Git\Command\Add\AddFiles
     */
    public function files(array $files): AddFiles
    {
        $this->files = $files;
        return $this;
    }

    /**
     * Return the command to execute.
     *
     * @return string
     * @throws \RuntimeException
     */
    protected function getGitCommand(): string
    {
        return 'add'
            . $this->dryRun
            . $this->update
            . $this->all
            . $this->noAll
            . $this->intentToAdd
            . ' -- '
            . implode(' ', array_map('escapeshellarg', $this->files));
    }
}
