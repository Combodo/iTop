<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\Tag;

use SebastianFeldmann\Git\Command\Base;

/**
 * Class GetCurrentTag
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 2.3.0
 */
class GetTags extends Base
{
    /**
     * Commit to check for a tag
     *
     * @var string
     */
    private string $hash = 'HEAD';

    /**
     * Set the hash you want to check for tags, HEAD by default
     *
     * @param  string $hash
     * @return \SebastianFeldmann\Git\Command\Tag\GetTags
     */
    public function pointingTo(string $hash): GetTags
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * Return the command to execute.
     *
     * @return string
     */
    protected function getGitCommand(): string
    {
        return 'tag --points-at ' . escapeshellarg($this->hash);
    }
}
