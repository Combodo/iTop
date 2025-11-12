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
 * Class ThatIs
 *
 * All FileStaged conditions are only applicable for `pre-commit` hooks.
 *
 * Example configuration:
 *
 * "action": "some-action"
 * "conditions": [
 *   {"exec": "\\CaptainHook\\App\\Hook\\Condition\\FileStaged\\ThatIs",
 *    "args": [
 *      {"ofType": "php", "inDirectory": "foo/", "diff-filter": ["A", "C"]}
 *    ]}
 * ]
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.16.0
 */
class ThatIs implements Condition, Constrained
{
    /**
     * Directory path to check e.g. 'src/' or 'path/To/Custom/Directory/'
     *
     * @var string[]
     */
    private array $directories;

    /**
     * File type to check e.g. 'php' or 'html'
     *
     * @var string[]
     */
    private array $suffixes;

    /**
     * --diff-filter options
     *
     * @var array<int, string>
     */
    private array $diffFilter;

    /**
     * OfType constructor
     *
     * @param array<string, mixed> $options
     */
    public function __construct(array $options)
    {
        $this->directories = (array)($options['inDirectory'] ?? []);
        $this->suffixes    = (array)($options['ofType'] ?? []);

        $diffFilter = $options['diffFilter'] ?? [];
        $this->diffFilter  = FilterUtil::sanitize(is_array($diffFilter) ? $diffFilter : str_split($diffFilter));
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
        $files = $this->filterFilesByDirectory($files);
        $files = $this->filterFilesByType($files);
        return count($files) > 0;
    }

    /**
     * Remove all files not in a given directory
     *
     * @param  array<string> $files
     * @return array<string>
     */
    private function filterFilesByDirectory(array $files): array
    {
        if (empty($this->directories)) {
            return $files;
        }
        return array_filter($files, function ($file) {
            foreach ($this->directories as $directory) {
                if (str_starts_with($file, $directory)) {
                    return true;
                }
            }
            return false;
        });
    }

    /**
     * Remove all files not of a configured type
     *
     * @param  array<string> $files
     * @return array<string>
     */
    private function filterFilesByType(array $files): array
    {
        if (empty($this->suffixes)) {
            return $files;
        }
        return array_filter($files, fn($file) => in_array(
            strtolower(pathinfo($file, PATHINFO_EXTENSION)),
            $this->suffixes,
            true
        ));
    }
}
