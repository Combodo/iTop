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
 * Class Get
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 1.0.2
 */
class Get extends Base
{
    /**
     * The name of the configuration key to get
     *
     * @var string
     */
    private string $name;

    /**
     * The name of the configuration key to get.
     *
     * @param string $name
     * @return \SebastianFeldmann\Git\Command\Config\Get
     */
    public function name(string $name): Get
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Return the command to execute.
     *
     * @return string
     */
    protected function getGitCommand(): string
    {
        return 'config --get ' .  escapeshellarg($this->name);
    }
}
