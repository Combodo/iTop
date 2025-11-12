<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian.feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Operator;

use RuntimeException;
use SebastianFeldmann\Git\Command\Add\AddFiles;
use SebastianFeldmann\Git\Command\DiffIndex\GetStagedFiles;
use SebastianFeldmann\Git\Command\DiffIndex\GetStagedFiles\FilterByStatus;
use SebastianFeldmann\Git\Command\Fetch\Fetch;
use SebastianFeldmann\Git\Command\Pull\Pull;
use SebastianFeldmann\Git\Command\RevParse\GetCommitHash;
use SebastianFeldmann\Git\Command\Rm\RemoveFiles;
use SebastianFeldmann\Git\Diff\FilterUtil;

/**
 * Remote Operator
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 3.9.4
 */
class Remote extends Base
{
    /**
     * Fetch remote updates for a branch
     *
     * @param string $remote
     * @param string $branch
     * @return void
     */
    public function fetchBranch(string $remote = 'origin', string $branch = ''): void
    {
        $cmd = (new Fetch($this->repo->getRoot()))->remote($remote)->branch($branch);
        $this->runner->run($cmd);
    }

    /**
     * Fetch remote update and merge them into current branch
     *
     * @param string $remote
     * @param string $branch
     * @return void
     */
    public function pullBranch(string $remote = 'origin', string $branch = ''): void
    {
        $cmd = (new Pull($this->repo->getRoot()))->remote($remote)->branch($branch);
        $this->runner->run($cmd);
    }
}
