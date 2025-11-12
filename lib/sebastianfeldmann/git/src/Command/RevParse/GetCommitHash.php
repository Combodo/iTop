<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\RevParse;

use SebastianFeldmann\Git\Command\Base;

/**
 * Class GetCommitHash
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 0.9.0
 */
class GetCommitHash extends Base
{
    /**
     * Revision to look up.
     *
     * @var string
     */
    private string $rev = 'HEAD';

    /**
     * Set revision to look up.
     *
     * @param  string $revision
     * @return \SebastianFeldmann\Git\Command\RevParse\GetCommitHash
     */
    public function revision(string $revision): GetCommitHash
    {
        $this->rev = $revision;
        return $this;
    }

    /**
     * Return the command to execute.
     *
     * @return string
     */
    protected function getGitCommand(): string
    {
        return 'rev-parse --verify ' . $this->rev;
    }
}
