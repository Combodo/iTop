<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\Condition;

use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Condition;
use CaptainHook\App\Hook\Constrained;
use CaptainHook\App\Hook\Restriction;
use CaptainHook\App\Hooks;
use SebastianFeldmann\Git\Repository;

/**
 * Class FileChange
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.2.0
 */
abstract class File implements Condition, Constrained
{
    /**
     * Return the hook restriction information
     *
     * @return \CaptainHook\App\Hook\Restriction
     */
    abstract public static function getRestriction(): Restriction;

    /**
     * Evaluates a condition
     *
     * @param  \CaptainHook\App\Console\IO       $io
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @return bool
     */
    abstract public function isTrue(IO $io, Repository $repository): bool;

    /**
     * Check if all of the given files can be found in a haystack of files
     *
     * IMPORTANT: If no files are provided this is always true.
     *
     * @param  array<string> $files
     * @param  array<string> $haystack
     * @return bool
     */
    protected function allFilesInHaystack(array $files, array $haystack): bool
    {
        foreach ($files as $filePattern) {
            if (!$this->isFileInList($haystack, $filePattern)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if any of the given files can be found in a haystack of files
     *
     * IMPORTANT: If no files are provided this is always false.
     *
     * @param  array<string> $files
     * @param  array<string> $haystack
     * @return bool
     */
    protected function anyFileInHaystack(array $files, array $haystack): bool
    {
        foreach ($files as $filePattern) {
            if ($this->isFileInList($haystack, $filePattern)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if a file matching a `fnmatch` pattern was changed
     *
     * @param  array<string> $listOfFiles List of files to scan
     * @param  string        $pattern     Pattern in fnmatch format to look for
     * @return bool
     */
    protected function isFileInList(array $listOfFiles, string $pattern): bool
    {
        foreach ($listOfFiles as $file) {
            if (fnmatch($pattern, $file)) {
                return true;
            }
        }
        return false;
    }
}
