<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\Checkout;

use SebastianFeldmann\Git\Command\Base;

/**
 * Class RestoreWorkingTree
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 3.7.0
 */
class RestoreWorkingTree extends Base
{
    /**
     * Files and directories to restore
     *
     * @var array<string>
     */
    private array $files = ['.'];

    /**
     * Skip the checkout hooks?
     *
     * @var bool
     */
    private bool $noMoreHooks = false;

    /**
     * Do not trigger git hooks while restoring
     *
     * @param  bool $bool
     * @return $this
     */
    public function skipHooks(bool $bool = true): RestoreWorkingTree
    {
        $this->noMoreHooks = $bool;
        return $this;
    }

    /**
     * Limits the paths affected by the operation to those specified here
     *
     * @param array<string> $files
     *
     * @return \SebastianFeldmann\Git\Command\Checkout\RestoreWorkingTree
     */
    public function files(array $files): RestoreWorkingTree
    {
        $this->files = $files;
        return $this;
    }

    /**
     * Return the command to execute
     *
     * @return string
     */
    protected function getGitCommand(): string
    {
        return ($this->noMoreHooks ? '-c core.hooksPath=/dev/null ' : '') . 'checkout --quiet'
            . ' -- '
            . implode(' ', array_map('escapeshellarg', $this->files));
    }
}
