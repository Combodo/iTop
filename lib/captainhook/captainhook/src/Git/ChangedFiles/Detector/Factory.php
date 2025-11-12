<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Git\ChangedFiles\Detector;

use CaptainHook\App\Console\IO;
use CaptainHook\App\Git\ChangedFiles\Detecting;
use CaptainHook\App\Hooks;
use SebastianFeldmann\Git\Repository;

/**
 * Factory class
 *
 * Responsible for finding the previous - current ranges in every scenario
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.15.0
 */
class Factory
{
    /**
     * List of available range detectors
     *
     * @var array<string, string>
     */
    private static array $detectors = [
        'hook:pre-push'      => '\\CaptainHook\\App\\Git\\ChangedFiles\\Detector\\PrePush',
        'hook:post-rewrite'  => '\\CaptainHook\\App\\Git\\ChangedFiles\\Detector\\PostRewrite',
    ];

    /**
     * Returns a ChangedFiles Detector
     *
     * @param \CaptainHook\App\Console\IO       $io
     * @param \SebastianFeldmann\Git\Repository $repository
     * @return \CaptainHook\App\Git\ChangedFiles\Detecting
     */
    public function getDetector(IO $io, Repository $repository): Detecting
    {
        $command = $io->getArgument(Hooks::ARG_COMMAND);

        /** @var \CaptainHook\App\Git\ChangedFiles\Detecting $class */
        $class    = self::$detectors[$command] ?? '\\CaptainHook\\App\\Git\\ChangedFiles\\Detector\\Fallback';
        return new $class($io, $repository);
    }
}
