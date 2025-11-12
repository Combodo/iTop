<?php

/**
 * This file is part ofBranch SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\MergeBase;

use SebastianFeldmann\Git\Command\Base;

/**
 * Class MergeBase
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 3.15.1
 */
class MergeBase extends Base
{
    /**
     * Dry run.
     *
     * @var string
     */
    private string $base = '';

    /**
     * Update.
     *
     * @var string
     */
    private string $branch = 'HEAD';

    /**
     * Set dry run.
     *
     * @param  string $branch     *
     * @return \SebastianFeldmann\Git\Command\MergeBase\MergeBase
     */
    public function ofBranch(string $branch): MergeBase
    {
        $this->branch = $branch;
        return $this;
    }

    /**
     * Set dry run.
     *
     * @param  string $base     *
     * @return \SebastianFeldmann\Git\Command\MergeBase\MergeBase
     */
    public function relativeTo(string $base): MergeBase
    {
        $this->base = $base;
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
        return 'merge-base ' . escapeshellarg($this->base) . ' ' . escapeshellarg($this->branch);
    }
}
