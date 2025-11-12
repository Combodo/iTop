<?php

/**
 * This file is part of SebastianFeldmann\Cli.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Cli;

use SebastianFeldmann\Cli\Command\Result;

/**
 * Interface Processor
 *
 * @package SebastianFeldmann\Cli
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/cli
 * @since   Class available since Release 0.9.0
 */
interface Processor
{
    /**
     * Execute a system call.
     *
     * @param  string $cmd
     * @param  int[]  $acceptableExitCodes
     * @return \SebastianFeldmann\Cli\Command\Result
     */
    public function run(string $cmd, array $acceptableExitCodes = [0]): Result;
}
