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
use CaptainHook\App\Git\Diff\FilterUtil;
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
abstract class FileStaged extends File
{
    /**
     * List of file to watch
     *
     * @var array<string>
     */
    protected array $filesToWatch;

    /**
     * --diff-filter options
     *
     * @var array<int, string>
     */
    protected array $diffFilter;

    /**
     * FileStaged constructor
     *
     * @param mixed $files
     * @param mixed $diffFilter
     */
    public function __construct($files, $diffFilter = [])
    {
        $this->filesToWatch = is_array($files) ? $files : explode(',', (string) $files);
        $this->diffFilter   = FilterUtil::filterFromConfigValue($diffFilter);
    }

    /**
     * Return the hook restriction information
     *
     * @return \CaptainHook\App\Hook\Restriction
     */
    public static function getRestriction(): Restriction
    {
        return Restriction::fromArray([Hooks::PRE_COMMIT]);
    }

    /**
     * Evaluates a condition
     *
     * @param  \CaptainHook\App\Console\IO       $io
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @return bool
     */
    abstract public function isTrue(IO $io, Repository $repository): bool;

    /**
     * Use 'diff-index --cached' to find the staged files before the commit
     *
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @return array<string>
     */
    protected function getStagedFiles(Repository $repository): array
    {
        return $repository->getIndexOperator()->getStagedFiles($this->diffFilter);
    }
}
