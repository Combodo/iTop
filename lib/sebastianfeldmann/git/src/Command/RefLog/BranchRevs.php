<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\RefLog;

use SebastianFeldmann\Git\Command\Base;

/**
 * Class BranchRevs
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 3.10.0
 */
class BranchRevs extends Base
{
    /**
     * git log format
     *
     * @var string
     */
    private string $format = '';

    /**
     * branch name
     *
     * @var string
     */
    private string $branch;

    public function format(string $format): BranchRevs
    {
        $this->format = $format;
        return $this;
    }

    public function fromBranch(string $branch): BranchRevs
    {
        $this->branch = $branch;
        return $this;
    }

    /**
     * Return the command to execute.
     *
     * @return string
     */
    protected function getGitCommand(): string
    {
        $format = !empty($this->format) ? ' --format=\'' . $this->format . '\'' : '';
        return 'reflog' . $format . ' ' . $this->branch;
    }
}
