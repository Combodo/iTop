<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Operator;

use SebastianFeldmann\Git\Command\Branch\ListRemote;
use SebastianFeldmann\Git\Command\Describe\GetCurrentTag;
use SebastianFeldmann\Git\Command\Describe\GetMostRecentTag;
use SebastianFeldmann\Git\Command\LsTree\GetFiles;
use SebastianFeldmann\Git\Command\MergeBase\MergeBase;
use SebastianFeldmann\Git\Command\RevParse\GetBranch;
use SebastianFeldmann\Git\Command\RevParse\GetCommitHash;
use SebastianFeldmann\Git\Command\Tag\GetTags;

/**
 * Class Info
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 1.0.8
 */
class Info extends Base
{
    /**
     * Returns the tag of the current commit
     *
     * @return string
     */
    public function getCurrentTag(): string
    {
        $cmd    = new GetCurrentTag($this->repo->getRoot());
        $result = $this->runner->run($cmd);

        return trim($result->getStdOut());
    }

    /**
     * Returns the most recent tag
     *
     * @param  string $ignore Unix glob to ignore tags e.g. **-RC*
     * @return string
     */
    public function getMostRecentTag(string $ignore = ''): string
    {
        $cmd = new GetMostRecentTag($this->repo->getRoot());
        $cmd->ignore($ignore);

        $result = $this->runner->run($cmd);

        return trim($result->getStdOut());
    }

    /**
     * Returns the most recent tag before the given commit
     *
     * @param  string $hash
     * @param  string $ignore Unix glob to ignore tags e.g. **-RC*
     * @return string
     */
    public function getMostRecentTagBefore(string $hash, string $ignore = ''): string
    {
        $cmd = new GetMostRecentTag($this->repo->getRoot());
        $cmd->ignore($ignore);
        $cmd->before($hash);

        $result = $this->runner->run($cmd);

        return trim($result->getStdOut());
    }

    /**
     * Returns a list of tags for a given commit hash
     *
     * @param  string $hash
     * @return string[]
     */
    public function getTagsPointingTo(string $hash): array
    {
        $cmd = new GetTags($this->repo->getRoot());
        $cmd->pointingTo($hash);

        $result = $this->runner->run($cmd);

        return $result->getBufferedOutput();
    }

    /**
     * Returns the hash of the current commit
     *
     * @return string
     */
    public function getCurrentCommitHash(): string
    {
        $cmd    = new GetCommitHash($this->repo->getRoot());
        $result = $this->runner->run($cmd);

        return trim($result->getStdOut());
    }

    /**
     * Returns the current branch name
     *
     * @return string
     */
    public function getCurrentBranch(): string
    {
        $cmd    = new GetBranch($this->repo->getRoot());
        $result = $this->runner->run($cmd);

        return trim($result->getStdOut());
    }

    public function getMergeBase(string $base, string $branch): string
    {
        $cmd    = (new MergeBase($this->repo->getRoot()))->ofBranch($branch)->relativeTo($base);
        $result = $this->runner->run($cmd);

        return trim($result->getStdOut());
    }

    /**
     * Return all files in the repository matching a given path
     *
     * This will return all files in the repository if no path is given.
     *
     * @param  string $path
     * @param  string $tree
     * @return string[]
     */
    public function getFilesInTree(string $path = '', string $tree = 'HEAD'): array
    {
        $cmd = new GetFiles($this->repo->getRoot());
        $cmd->inPath($path);
        $cmd->fromTree($tree);

        $result = $this->runner->run($cmd);
        return $result->getBufferedOutput();
    }

    /**
     * Returns all the branches available on the remote git
     *
     * @return array<string>
     */
    public function getRemoteBranches(): array
    {
        $cmd    = new ListRemote($this->repo->getRoot());
        $result = $this->runner->run($cmd);

        return $result->getBufferedOutput();
    }
}
