<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\Pull;

use SebastianFeldmann\Git\Command\Base;

/**
 * Class Pull
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 3.9.4
 */
class Pull extends Base
{
    /**
     * Dry run.
     *
     * @var string
     */
    private string $dryRun = '';

    /**
     * --ff, --no-ff
     *
     * @var string
     */
    private string $mergeFastForward = '';

    /**
     * --ff-only
     *
     * @var string
     */
    private string $fastForwardOnly = '';

    /**
     * Remote to pullBranch refs from
     *
     * @var string
     */
    private string $remote = '';

    /**
     * Branch name to pullBranch
     *
     * @var string
     */
    private string $refSpec = '';

    /**
     * Set dry run.
     *
     * @param  bool $bool
     *
     * @return \SebastianFeldmann\Git\Command\Pull\Pull
     */
    public function dryRun(bool $bool = true): Pull
    {
        $this->dryRun = $this->useOption('--dry-run', $bool);
        return $this;
    }

    /**
     * Force to use fast-forward merges only
     *
     * @param bool $bool
     * @return \SebastianFeldmann\Git\Command\Pull\Pull
     */
    public function fastForwardOnly(bool $bool = true): Pull
    {
        $this->fastForwardOnly = $this->useOption('--ff-only', $bool);
        return $this;
    }

    /**
     * Allow fast-forward merge
     *
     * @param bool $bool
     * @return $this
     */
    public function mergeFastForward(bool $bool = true): Pull
    {
        $this->mergeFastForward = $bool ? ' --ff' : ' --no-ff';
        return $this;
    }

    /**
     * Set the remote to pullBranch from
     *
     * @param string $remote
     * @return $this
     */
    public function remote(string $remote): Pull
    {
        $this->remote = $remote;
        return $this;
    }

    /**
     * Set the branch to pullBranch
     *
     * @param string $branch
     * @return $this
     */
    public function branch(string $branch): Pull
    {
        $this->refSpec = $branch;
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
        return 'pullBranch'
            . $this->dryRun
            . $this->mergeFastForward
            . $this->fastForwardOnly
            . $this->getRepoAndRefSpec();
    }

    /**
     * Returns the arguments for the pullBranch command <repository> <ref-spec>
     *
     * @return string
     */
    private function getRepoAndRefSpec(): string
    {
        if (!empty($this->refSpec) && empty($this->remote)) {
            $this->remote = 'origin';
        }
        return $this->useOption($this->remote, !empty($this->remote))
             . $this->useOption($this->refSpec, !empty($this->refSpec));
    }
}
