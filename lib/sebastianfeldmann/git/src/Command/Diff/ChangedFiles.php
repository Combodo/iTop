<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\Diff;

use SebastianFeldmann\Git\Command\Base;

/**
 * Class ChangedFiles
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 3.15.0
 */
class ChangedFiles extends Base
{
    /**
     * @var string
     */
    private string $mode = '..';

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
     * @return \SebastianFeldmann\Git\Command\Diff\ChangedFiles
     */
    public function tipToTip(): ChangedFiles
    {
        $this->mode = '..';
        return $this;
    }

    /**
     * @return \SebastianFeldmann\Git\Command\Diff\ChangedFiles
     */
    public function mergeBase(): ChangedFiles
    {
        $this->mode = '...';
        return $this;
    }

    /**
     * @param  string $from
     * @return \SebastianFeldmann\Git\Command\Diff\ChangedFiles
     */
    public function fromRevision(string $from): ChangedFiles
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @param  string $to
     * @return \SebastianFeldmann\Git\Command\Diff\ChangedFiles
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
     * @return \SebastianFeldmann\Git\Command\Diff\ChangedFiles
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
        return 'diff'
            . ' --diff-algorithm=myers'
            . ' --name-only'
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
        return escapeshellarg($this->from) . $this->mode . (empty($this->to) ? 'head' : escapeshellarg($this->to));
    }
}
