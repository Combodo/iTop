<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\DiffTree;

use SebastianFeldmann\Git\Command\Base;

/**
 * Class ChangedFiles
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 2.0.1
 */
class ChangedFiles extends Base
{
    /**
     * @var string
     */
    private string $from;

    /**
     * @var string
     */
    private string $to;

    /**
     * @var array<string>
     */
    private array $filter;

    /**
     * @param  string $from
     * @return \SebastianFeldmann\Git\Command\DiffTree\ChangedFiles
     */
    public function fromRevision(string $from): ChangedFiles
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @param  string $to
     * @return \SebastianFeldmann\Git\Command\DiffTree\ChangedFiles
     */
    public function toRevision(string $to): ChangedFiles
    {
        $this->to = $to;
        return $this;
    }

    /**
     * Set --diff-filter
     *
     * @param  array<string> $filter
     * @return \SebastianFeldmann\Git\Command\DiffTree\ChangedFiles
     */
    public function useFilter(array $filter): ChangedFiles
    {
        $this->filter = $filter;
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
        return 'diff-tree'
            . ' --diff-algorithm=myers'
            . ' --no-ext-diff'
            . ' --no-commit-id'
            . ' --name-only'
            . ' -r'
            . (!empty($this->filter) ? ' --diff-filter=' . implode('', $this->filter) : '')
            . ' ' . $this->getVersionsToCompare();
    }

    /**
     * Returns the commit range for the diff command
     *
     * @return string
     */
    protected function getVersionsToCompare(): string
    {
        return escapeshellarg($this->from) . (empty($this->to) ? '' : ' ' . escapeshellarg($this->to));
    }
}
