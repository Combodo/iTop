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

use RuntimeException;
use SebastianFeldmann\Cli\Command\Result;
use SebastianFeldmann\Cli\Processor;

/**
 * Class ProcOpen
 *
 * @package SebastianFeldmann\Cli
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/cli
 * @since   Class available since Release 0.9.0
 */
class ProcOpen implements Processor
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
        $old            = error_reporting(0);
        $descriptorSpec = [
            ['pipe', 'r'],
            ['pipe', 'w'],
            ['pipe', 'w'],
        ];

        $process = proc_open($cmd, $descriptorSpec, $pipes);
        if (!is_resource($process)) {
            throw new RuntimeException('can\'t execute \'proc_open\'');
        }

        // Loop on process until it exits normally.
        $stdOut = "";
        $stdErr = "";
        do {
            // Consume output streams while the process runs. The buffer will block process updates when full
            $status = proc_get_status($process);
            $stdOut .= stream_get_contents($pipes[1]);
            $stdErr .= stream_get_contents($pipes[2]);
        } while ($status['running']);

        // make sure all pipes are closed before calling proc_close
        foreach ($pipes as $index => $pipe) {
            fclose($pipe);
            unset($pipes[$index]);
        }

        proc_close($process);
        error_reporting($old);

        return new Result($cmd, $status['exitcode'], $stdOut, $stdErr, '', $acceptableExitCodes);
    }
}
