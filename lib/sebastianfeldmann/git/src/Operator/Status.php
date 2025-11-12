<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Operator;

use SebastianFeldmann\Git\Command\Checkout\RestoreWorkingTree;
use SebastianFeldmann\Git\Command\Status\WorkingTreeStatus;
use SebastianFeldmann\Git\Command\Status\Porcelain\PathList;

/**
 * Class Status
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 3.6.0
 */
class Status extends Base
{
    /**
     * Returns a list of paths in the working tree and index, with statuses.
     *
     * @return \SebastianFeldmann\Git\Status\Path[]
     */
    public function getWorkingTreeStatus(): iterable
    {
        $cmd = (new WorkingTreeStatus($this->repo->getRoot()))->ignoreSubmodules();

        $result = $this->runner->run($cmd, new PathList());

        return $result->getFormattedOutput();
    }

    /**
     * Performs a checkout (restore) operation on the given paths
     * (or the entire repo, by default).
     *
     * @param string[] $limitToPaths
     * @return bool
     */
    public function restoreWorkingTree(array $limitToPaths = ['.']): bool
    {
        $cmd = (new RestoreWorkingTree($this->repo->getRoot()))->skipHooks()->files($limitToPaths);

        $result = $this->runner->run($cmd);

        return $result->isSuccessful();
    }
}
