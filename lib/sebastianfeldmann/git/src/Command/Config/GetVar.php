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
 * Class GetVar
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 3.14.0
 */
class GetVar extends Base
{
    /**
     * The name of the configuration key to get
     *
     * @var string
     */
    private string $name;

    /**
     * Set the name of the var to get
     *
     * @param string $name
     * @return \SebastianFeldmann\Git\Command\Config\GetVar
     */
    public function name(string $name): GetVar
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Return the `git var` command to execute
     *
     * @return string
     */
    protected function getGitCommand(): string
    {
        return 'var ' .  escapeshellarg($this->name);
    }
}
