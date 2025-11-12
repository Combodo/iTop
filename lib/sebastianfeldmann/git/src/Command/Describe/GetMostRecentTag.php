<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\Describe;

use SebastianFeldmann\Git\Command\Base;

/**
 * Class GetCurrentTag
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 1.0.8
 */
class GetMostRecentTag extends Base
{
    /**
     * @var string
     */
    private string $before = '';

    /**
     * Glob to define excluded tags e.g **-RC* to exclude release candidate tags
     * @var string
     */
    private string $exclude = '';

    /**
     * Sets the start point to search for a tag
     *
     * @param  string $hash
     * @return \SebastianFeldmann\Git\Command\Describe\GetMostRecentTag
     */
    public function before(string $hash): GetMostRecentTag
    {
        $this->before = $hash;

        return $this;
    }

    /**
     * Glob of tags to ignore e.g. **-RC* to ignore release candidate tags like '1.0.0-RC3'
     *
     * @param  string $glob
     * @return \SebastianFeldmann\Git\Command\Describe\GetMostRecentTag
     */
    public function ignore(string $glob): GetMostRecentTag
    {
        $this->exclude = $glob;
        return $this;
    }

    /**
     * Return the command to execute.
     *
     * @return string
     */
    protected function getGitCommand(): string
    {
        return 'describe --tags --abbrev=0' . $this->tagsToIgnore() . $this->startingPoint();
    }

    /**
     * Return the --exclude='xxx' option
     *
     * @return string
     */
    private function tagsToIgnore(): string
    {
        return empty($this->exclude) ? '' : ' --exclude=' . escapeshellarg($this->exclude);
    }

    /**
     * Return the starting point where to start the search for a tag
     *
     * @return string
     */
    private function startingPoint(): string
    {
        return empty($this->before) ? '' : ' ' . $this->before . '^';
    }
}
