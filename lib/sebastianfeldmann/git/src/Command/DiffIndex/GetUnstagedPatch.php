<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\DiffIndex;

use SebastianFeldmann\Git\Command\Base;
use SebastianFeldmann\Git\Command\Status\WorkingTreeStatus;

/**
 * Class GetUnstagedPatch
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 3.7.0
 */
class GetUnstagedPatch extends Base
{
    /**
     * Tree object ID.
     *
     * @var string|null
     */
    private ?string $treeId = null;

    /**
     * Return list of acceptable exit codes.
     *
     * @return array<int>
     */
    public function getAcceptableExitCodes(): array
    {
        return [0, 1];
    }

    /**
     * Set tree object ID.
     *
     * @param string|null $treeId
     *
     * @return \SebastianFeldmann\Git\Command\DiffIndex\GetUnstagedPatch
     */
    public function tree(?string $treeId): GetUnstagedPatch
    {
        $this->treeId = $treeId;
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
        return 'diff-index'
            . ' --diff-algorithm=myers'
            . ' --ignore-submodules'
            . ' --binary'
            . ' --exit-code'
            . ' --no-color'
            . ' --no-ext-diff'
            . ($this->treeId ? ' ' . escapeshellarg($this->treeId) : '')
            . ' -- ';
    }
}
