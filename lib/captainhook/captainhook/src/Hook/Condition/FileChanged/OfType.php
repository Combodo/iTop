<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\Condition\FileChanged;

use CaptainHook\App\Console\IO;
use CaptainHook\App\Git;
use CaptainHook\App\Hook\Condition;
use CaptainHook\App\Hook\Constrained;
use CaptainHook\App\Hook\FileList;
use CaptainHook\App\Hook\Restriction;
use CaptainHook\App\Hooks;
use SebastianFeldmann\Git\Repository;

/**
 * Class OfType
 *
 * Example configuration:
 *
 * "action": "some-action"
 * "conditions": [
 *   {"exec": "\\CaptainHook\\App\\Hook\\Condition\\FileChanged\\OfType",
 *    "args": [
 *      "php"
 *    ]}
 * ]
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.0.0
 */
class OfType implements Condition, Constrained
{
    /**
     * File type to check e.g. 'php' or 'html'
     *
     * @var string
     */
    private string $suffix;

    /**
     * OfType constructor
     *
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->suffix = $type;
    }

    /**
     * Return the hook restriction information
     *
     * @return \CaptainHook\App\Hook\Restriction
     */
    public static function getRestriction(): Restriction
    {
        return Restriction::fromArray([Hooks::PRE_PUSH, Hooks::POST_CHECKOUT, Hooks::POST_MERGE, Hooks::POST_REWRITE]);
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
        $files = FileList::filterByType(
            Git\ChangedFiles::getChangedFiles($io, $repository, ['A', 'C', 'M', 'R']),
            ['of-type' => $this->suffix]
        );

        if (count($files) > 0) {
            return true;
        }
        return false;
    }
}
