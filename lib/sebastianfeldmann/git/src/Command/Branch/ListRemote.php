<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\Branch;

use SebastianFeldmann\Git\Command\Base;

/**
 * Class ListRemote
 *
 * @package SebastianFeldmann\Git
 * @author  David Eckhaus
 */
class ListRemote extends Base
{
    /**
     * Return the command to execute
     *
     * @return string
     */
    protected function getGitCommand(): string
    {
        return 'branch -r';
    }
}
