<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptainHook\App\Hook\Condition\Logic;

use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Condition\Logic;
use SebastianFeldmann\Git\Repository;

/**
 * Connects multiple conditions with 'and'
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @author  Andreas Heigl <andreas@heigl.org>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.7.0
 */
final class LogicAnd extends Logic
{
    public function isTrue(IO $io, Repository $repository): bool
    {
        foreach ($this->conditions as $condition) {
            if (false === $condition->isTrue($io, $repository)) {
                return false;
            }
        }
        return true;
    }
}
