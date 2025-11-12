<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Git\Range\Detector;

use CaptainHook\App\Console\IO;
use CaptainHook\App\Git\Range\Detecting;
use CaptainHook\App\Git\Range\PrePush as Range;
use CaptainHook\App\Git\Rev\PrePush as Rev;
use CaptainHook\App\Git\Rev\Util;

/**
 * Class to access the pre-push stdIn data
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.15.0
 */
class PrePush implements Detecting
{
    /**
     * Returns list of refs
     *
     * @param  \CaptainHook\App\Console\IO $io
     * @return array<\CaptainHook\App\Git\Range\PrePush>
     */
    public function getRanges(IO $io): array
    {
        return $this->createFromStdIn($io->getStandardInput());
    }

    /**
     * Factory method
     *
     * @param  array<string> $stdIn
     * @return array<\CaptainHook\App\Git\Range\PrePush>
     */
    private function createFromStdIn(array $stdIn): array
    {
        $ranges = [];
        foreach ($stdIn as $line) {
            if (empty($line)) {
                continue;
            }

            [$localRef, $localHash, $remoteRef, $remoteHash] = explode(' ', trim($line));

            $from     = new Rev($remoteRef, $remoteHash, Util::extractBranchInfo($remoteRef)['branch']);
            $to       = new Rev($localRef, $localHash, Util::extractBranchInfo($localRef)['branch']);
            $ranges[] = new Range($from, $to);
        }
        return $ranges;
    }
}
