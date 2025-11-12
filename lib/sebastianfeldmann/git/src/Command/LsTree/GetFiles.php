<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\LsTree;

use SebastianFeldmann\Git\Command\Base;

/**
 * Class GetFiles
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 3.4.0
 */
class GetFiles extends Base
{
    /**
     * Tree to check head by default
     * @var string
     */
    private string $tree = 'HEAD';

    /**
     * Path to check for files
     *
     * @var string
     */
    private string $path = '';

    /**
     * Define the tree to search through
     *
     * @param string $tree
     * @return \SebastianFeldmann\Git\Command\LsTree\GetFiles
     */
    public function fromTree(string $tree): GetFiles
    {
        $this->tree = $tree;
        return $this;
    }

    /**
     * Compares the index to a given commit hash
     *
     * @param  string $path
     * @return \SebastianFeldmann\Git\Command\LsTree\GetFiles
     */
    public function inPath(string $path): GetFiles
    {
        $this->path = !empty($path) ? ' ' . $path : $path;
        return $this;
    }

    /**
     * Return the command to execute.
     *
     * @return string
     * @throws \RuntimeException
     */
    protected function getGitCommand(): string
    {
        return 'ls-tree --name-only -r ' . $this->tree . $this->path;
    }
}
