<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SebastianFeldmann\Git\Repository;

use SebastianFeldmann\Cli\Command\Runner;
use SebastianFeldmann\Git\Command\CloneCmd\CloneCmd;
use SebastianFeldmann\Git\Repository;
use SebastianFeldmann\Git\Url;

/**
 * Class Cloner
 *
 * Responsible for all `git clone` operations
 *
 * @package SebastianFeldmann\Git
 * @author  Andreas Frömer
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 3.8.0
 */
final class Cloner
{
    /**
     * @var string
     */
    private string $root;

    /**
     * @var int
     */
    private int $depth = 0;

    /**
     * @var \SebastianFeldmann\Cli\Command\Runner
     */
    private Runner $runner;

    /**
     * Cloner constructor
     *
     * @param string $root
     * @param Runner|null $runner
     */
    public function __construct(string $root = '', ?Runner $runner = null)
    {
        $this->root   = empty($root) ? (string) getcwd() : $root;
        $this->runner = $runner ?? new Runner\Simple();
    }

    /**
     * Set the `--depth` option
     *
     * @param  int $depth
     * @return $this
     */
    public function depth(int $depth): Cloner
    {
        $this->depth = $depth;
        return $this;
    }

    /**
     * Clone a given repository $url.
     *
     * @param string $url Url of repository
     * @param string $dir The directory where the content should be cloned into.
     *                    If this is an absolute path this directory will be used.
     *                    If this is a relative path, and new folder will be created inside
     *                    the current working directory.
     */
    public function clone(string $url, string $dir = ''): Repository
    {
        $repositoryUrl = new Url($url);
        $cloneCommand  = new CloneCmd($repositoryUrl);

        if (empty($dir)) {
            $dir = $this->root . '/' . $repositoryUrl->getRepoName();
        }

        $cloneCommand->dir($dir);

        if ($this->depth > 0) {
            $cloneCommand->depth($this->depth);
        }

        $this->runner->run($cloneCommand);

        return Repository::createVerified($dir, $this->runner);
    }
}
