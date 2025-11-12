<?php

/**
 * This file is part of SebastianFeldmann\Cli.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Cli\Processor;

use SebastianFeldmann\Cli\Command\Result;
use SebastianFeldmann\Cli\Processor;
use Symfony\Component\Process\Process;

/**
 * Class ProcOpen
 *
 * @package SebastianFeldmann\Cli
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/cli
 * @since   Class available since Release 3.2.2
 */
class Symfony implements Processor
{
    /**
     * Execute the command
     *
     * @param  string $cmd
     * @param  int[]  $acceptableExitCodes
     * @return \SebastianFeldmann\Cli\Command\Result
     */
    public function run(string $cmd, array $acceptableExitCodes = [0]): Result
    {
        // the else (:) variant is there to keep backwards compatibility with previous symfony versions
        // and is only getting executed in those. This is the reason why the Process constructor is
        // given a string instead of an array. The whole ternary can be removed if Symfony versions
        // below 4.2 are not supported anymore.
        $process = method_exists(Process::class, 'fromShellCommandline')
                 ? Process::fromShellCommandline($cmd)
                 : new Process($cmd); // @phpstan-ignore-line

        $process->setTimeout(null);
        $process->run();
        return new Result(
            $cmd,
            $process->getExitCode(),
            $process->getOutput(),
            $process->getErrorOutput(),
            '',
            $acceptableExitCodes
        );
    }
}
