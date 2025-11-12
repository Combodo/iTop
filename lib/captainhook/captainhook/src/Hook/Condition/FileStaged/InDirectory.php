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
use CaptainHook\App\Git\Diff\FilterUtil;
use CaptainHook\App\Hook\Condition;
use CaptainHook\App\Hook\Constrained;
use CaptainHook\App\Hook\Restriction;
use CaptainHook\App\Hooks;
use SebastianFeldmann\Git\Repository;

/**
 * Class InDirectory
 *
 * All FileStaged conditions are only applicable for `pre-commit` hooks.
 *
 * Example configuration:
 *
 * "action": "some-action"
 * "conditions": [
 *   {"exec": "\\CaptainHook\\App\\Hook\\Condition\\FileStaged\\InDirectory",
 *    "args": [
 *      "src/"
 *   ]}
 * ]
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.6.1
 */
class InDirectory implements Condition, Constrained
{
    /**
     * Directory path to check e.g. 'src/' or 'path/To/Custom/Directory/'
     *
     * @var string
     */
    private string $directory;

    /**
     * --diff-filter options
     *
     * @var array<int, string>
     */
    private array $diffFilter;

    /**
     * InDirectory constructor
     *
     * @param mixed                     $directory
     * @param array<int, string>|string $diffFilter
     */
    public function __construct($directory, $diffFilter = [])
    {
        $this->directory  = (string) $directory;
        $this->diffFilter = FilterUtil::filterFromConfigValue($diffFilter);
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
     * Evaluates the condition
     *
     * @param  \CaptainHook\App\Console\IO       $io
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @return bool
     */
    public function isTrue(IO $io, Repository $repository): bool
    {
        $files = $repository->getIndexOperator()->getStagedFiles($this->diffFilter);

        $filtered = [];
        foreach ($files as $file) {
            if (strpos($file, $this->directory) === 0) {
                $filtered[] = $file;
            }
        }

        return count($filtered) > 0;
    }
}
