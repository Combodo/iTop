<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\Rm;

use SebastianFeldmann\Git\Command\Base;

/**
 * Class RemoveFiles
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 3.7.0
 */
class RemoveFiles extends Base
{
    /**
     * Dry run.
     *
     * @var string
     */
    private string $dryRun = '';

    /**
     * Cached.
     *
     * @var string
     */
    private string $cached = '';

    /**
     * Recursive removal.
     *
     * @var string
     */
    private string $recursive = '';

    /**
     * Files to remove.
     *
     * @var string[]
     */
    private array $files = [];

    /**
     * Set dry run.
     *
     * @param  bool $bool
     *
     * @return \SebastianFeldmann\Git\Command\Rm\RemoveFiles
     */
    public function dryRun(bool $bool = true): RemoveFiles
    {
        $this->dryRun = $this->useOption('--dry-run', $bool);
        return $this;
    }

    /**
     * Unstage and remove paths only from the index.
     *
     * Working tree files, whether modified or not, will be left alone..
     *
     * @param  bool $bool
     *
     * @return \SebastianFeldmann\Git\Command\Rm\RemoveFiles
     */
    public function cached(bool $bool = true): RemoveFiles
    {
        $this->cached = $this->useOption('--cached', $bool);
        return $this;
    }

    /**
     * Allow recursive removal when a leading directory name is given.
     *
     * @param  bool $bool
     *
     * @return \SebastianFeldmann\Git\Command\Rm\RemoveFiles
     */
    public function recursive(bool $bool = true): RemoveFiles
    {
        $this->recursive = $this->useOption('-r', $bool);
        return $this;
    }

    /**
     * Files to remove.
     *
     * A leading directory name (e.g. `dir` to remove `dir/file1` and
     * `dir/file2`) can be given to remove all files in the directory, and
     * recursively all sub-directories, but this requires the `-r` option to be
     * explicitly given.
     *
     * The command removes only the paths that are known to Git.
     *
     * File globbing matches across directory boundaries. Thus, given two
     * directories `d` and `d2`, there is a difference between using
     * `git rm 'd*'` and `git rm 'd/*'`, as the former will also remove all of
     * directory `d2`.
     *
     * @param  array<string> $files
     * @return \SebastianFeldmann\Git\Command\Rm\RemoveFiles
     */
    public function files(array $files): RemoveFiles
    {
        $this->files = $files;
        return $this;
    }

    /**
     * Return the command to execute.
     *
     * @return string
     */
    protected function getGitCommand(): string
    {
        return 'rm'
            . $this->dryRun
            . $this->cached
            . $this->recursive
            . ' -- '
            . implode(' ', array_map('escapeshellarg', $this->files));
    }
}
