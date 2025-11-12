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
 * NotOnMatching condition
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.20.2
 */
class NotOnMatching extends Name
{
    /**
     * Check is the current branch is not matched by the configured regex
     *
     * @param  \CaptainHook\App\Console\IO       $io
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @return bool
     */
    public function isTrue(IO $io, Repository $repository): bool
    {
        return preg_match($this->name, trim($repository->getInfoOperator()->getCurrentBranch())) === 0;
    }
}
