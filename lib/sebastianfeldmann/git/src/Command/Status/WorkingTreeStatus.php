<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\Status;

use SebastianFeldmann\Git\Command\Base;

/**
 * Class GetWorkingTreeStatus
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 3.6.0
 */
class WorkingTreeStatus extends Base
{
    /**
     * Ignore submodules.
     *
     * @var string
     */
    private string $ignoreSubmodules = '';

    /**
     * Set ignore submodules.
     *
     * @param  bool $bool
     *
     * @return \SebastianFeldmann\Git\Command\Status\WorkingTreeStatus
     */
    public function ignoreSubmodules(bool $bool = true): WorkingTreeStatus
    {
        $this->ignoreSubmodules = $this->useOption('--ignore-submodules', $bool);
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
        return 'status --porcelain=v1 -z'
               . $this->ignoreSubmodules;
    }
}
