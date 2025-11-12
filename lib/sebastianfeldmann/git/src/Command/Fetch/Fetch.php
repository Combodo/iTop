<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\Fetch;

use SebastianFeldmann\Git\Command\Base;

/**
 * Class Fetch
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 3.9.4
 */
class Fetch extends Base
{
    /**
     * Dry run.
     *
     * @var string
     */
    private string $dryRun = '';

    /**
     * --all
     *
     * @var string
     */
    private string $all = '';

    /**
     * --force
     *
     * @var string
     */
    private string $force = '';

    /**
     * Remote to fetchBranch refs from
     *
     * @var string
     */
    private $remote = '';

    /**
     * Branch name to fetchBranch
     *
     * @var string
     */
    private $refSpec = '';

    /**
     * Set dry run.
     *
     * @param  bool $bool
     * @return \SebastianFeldmann\Git\Command\Fetch\Fetch
     */
    public function dryRun(bool $bool = true): Fetch
    {
        $this->dryRun = $this->useOption('--dry-run', $bool);
        return $this;
    }

    /**
     * Fetch all remotes
     *
     * @param bool $bool
     * @return \SebastianFeldmann\Git\Command\Fetch\Fetch
     */
    public function all(bool $bool = true): Fetch
    {
        $this->all = $this->useOption('--all', $bool);
        return $this;
    }

    /**
     * Force update
     *
     * @param bool $bool
     * @return \SebastianFeldmann\Git\Command\Fetch\Fetch
     */
    public function force(bool $bool = true): Fetch
    {
        $this->force = $this->useOption('--force', $bool);
        return $this;
    }

    /**
     * Set the remote to fetchBranch from
     *
     * @param string $remote
     * @return $this
     */
    public function remote(string $remote): Fetch
    {
        $this->remote = $remote;
        return $this;
    }

    /**
     * Set the branch to fetchBranch
     *
     * @param string $branch
     * @return $this
     */
    public function branch(string $branch): Fetch
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
        return 'fetch'
            . $this->dryRun
            . $this->all
            . $this->force
            . $this->getRepoAndRefSpec();
    }

    /**
     * Returns the arguments for the fetchBranch command <repository> <ref-spec>
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
