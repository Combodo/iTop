<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptainHook\App\Hook\Condition\Branch;

use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Condition;
use CaptainHook\App\Hook\FileList;
use SebastianFeldmann\Git\Repository;

/**
 * Files condition
 *
 * Example configuration:
 *
 *   "action": "some-action"
 *   "conditions": [
 *     {"exec": "\\CaptainHook\\App\\Hook\\Condition\\Branch\\Files",
 *      "args": [
 *        {"compare-to": "main", "of-type": "php"}
 *     ]}
 *   ]
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.21.0
 */
class Files implements Condition
{
    /**
     * Options
     *  - compare-to:   source branch if known, otherwise the reflog is used to figure it out
     *  - in-directory: only check for files in given directory
     *  - of-type:      only check for files of given type
     *
     * @var array<string>
     */
    private array $options;

    /**
     * Constructor
     *
     * @param array<string> $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * Check if the current branch contains changes to files
     *
     * @param  \CaptainHook\App\Console\IO       $io
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @return bool
     */
    public function isTrue(IO $io, Repository $repository): bool
    {
        $branch = $repository->getInfoOperator()->getCurrentBranch();
        $start  = $this->options['compared-to'] ?? $repository->getLogOperator()->getBranchRevFromRefLog($branch);

        if (empty($start)) {
            return false;
        }

        $files = $repository->getLogOperator()->getChangedFilesSince($start, ['A', 'C', 'M', 'R']);

        return count(FileList::filter($files, $this->options)) > 0;
    }
}
