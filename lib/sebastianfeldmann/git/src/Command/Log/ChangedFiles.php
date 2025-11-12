<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\Log;

/**
 * Class ChangedFiles
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 0.9.0
 */
class ChangedFiles extends Log
{
    /**
     * Return the command to execute.
     *
     * @return string
     * @throws \RuntimeException
     */
    protected function getGitCommand(): string
    {
        return 'log --format=' . escapeshellarg('')
               . ' --name-only'
               . $this->diffFilter
               . $this->author
               . $this->merges
               . $this->date
               . $this->revSelection;
    }
}
