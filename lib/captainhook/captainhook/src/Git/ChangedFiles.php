<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Git;

use CaptainHook\App\Console\IO;
use CaptainHook\App\Git\ChangedFiles\Detector\Factory;
use SebastianFeldmann\Git\Repository;

/**
 * Class ChangedFiles
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.2.0
 */
abstract class ChangedFiles
{
    /**
     * Returns the list of changed files using the necessary Detector
     *
     * @param  \CaptainHook\App\Console\IO       $io
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @param  array<string>                     $filter
     * @return array<string>
     */
    public static function getChangedFiles(IO $io, Repository $repository, array $filter): array
    {
        $factory  = new Factory();
        $detector = $factory->getDetector($io, $repository);

        $files = $detector->getChangedFiles($filter);
        self::displayFilesFound($io, $files);
        return $files;
    }

    /**
     * Output the changed files in verbose mode
     *
     * @param  \CaptainHook\App\Console\IO $io
     * @param  array<string>               $files
     * @return void
     */
    private static function displayFilesFound(IO $io, array $files): void
    {
        if ($io->isVerbose()) {
            $io->write('  <comment>Changed files</comment>', true, IO::VERBOSE);
            foreach ($files as $file) {
                $io->write('   - ' . $file, true, IO::VERBOSE);
            }
        }
    }
}
