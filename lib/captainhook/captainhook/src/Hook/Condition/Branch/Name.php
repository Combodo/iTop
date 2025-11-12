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
use SebastianFeldmann\Git\Repository;

/**
 * OnBranch condition
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.0.0
 */
abstract class Name implements Condition
{
    /**
     * Branch name to compare
     *
     * @var string
     */
    protected string $name;

    /**
     * OnBranch constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Check is the current branch is equal to the configured one
     *
     * @param  \CaptainHook\App\Console\IO       $io
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @return bool
     */
    abstract public function isTrue(IO $io, Repository $repository): bool;
}
