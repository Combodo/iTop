<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\Config;

use SebastianFeldmann\Git\Command\Base;

/**
 * Class ListSettings
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 1.0.8
 */
class ListSettings extends Base
{
    /**
     * Return the command to execute.
     *
     * @return string
     */
    protected function getGitCommand(): string
    {
        return 'config --list';
    }
}
