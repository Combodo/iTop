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

use SebastianFeldmann\Cli\Command\Runner;
use SebastianFeldmann\Git\Repository;

/**
 * Class Base
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 0.9.0
 */
abstract class Base
{
    /**
     * Runner to execute git system calls.
     *
     * @var \SebastianFeldmann\Cli\Command\Runner
     */
    protected Runner $runner;

    /**
     * Git repository to use.
     *
     * @var \SebastianFeldmann\Git\Repository
     */
    protected Repository $repo;

    /**
     * Base constructor.
     *
     * @param \SebastianFeldmann\Cli\Command\Runner $runner
     * @param \SebastianFeldmann\Git\Repository     $repo
     */
    public function __construct(Runner $runner, Repository $repo)
    {
        $this->runner = $runner;
        $this->repo   = $repo;
    }
}
