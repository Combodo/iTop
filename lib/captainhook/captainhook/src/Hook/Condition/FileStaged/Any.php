<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\Condition\FileStaged;

use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Condition\FileStaged;
use SebastianFeldmann\Git\Repository;

/**
 * Class Any
 *
 * The FileStaged condition is applicable for `pre-commit hooks.
 *
 *  Example configuration:
 *
 *   "action": "some-action"
 *   "conditions": [
 *     {"exec": "\\CaptainHook\\App\\Hook\\Condition\\FileStaged\\Any",
 *      "args": [
 *        ["file1", "file2", "file3"]
 *     ]}
 *   ]
 *
 *  The file list can also be defined as comma seperated string "file1,file2,file3"
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.2.0
 */
class Any extends FileStaged
{
    /**
     * Check if any of the configured files is staged for commit
     *
     * @param  \CaptainHook\App\Console\IO       $io
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @return bool
     */
    public function isTrue(IO $io, Repository $repository): bool
    {
        return $this->anyFileInHaystack($this->filesToWatch, $this->getStagedFiles($repository));
    }
}
