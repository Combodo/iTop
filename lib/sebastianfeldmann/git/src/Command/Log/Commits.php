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
 * Class Commits
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 0.9.0
 */
class Commits extends Log
{
    /**
     * Return the command to execute.
     *
     * @return string
     * @throws \RuntimeException
     */
    protected function getGitCommand(): string
    {
        return 'log --pretty=' . $this->escape('format:' . $this->format)
               . $this->abbrev
               . $this->author
               . $this->merges
               . $this->date
               . $this->revSelection;
    }

    /**
     * This makes sure the % and ! signs within the format string will not be replaced on windows by 'escapeshellarg'
     *
     * @param  string $arg
     * @return string
     */
    private function escape(string $arg): string
    {
        // this is a dirty hack to make it work under windows
        return defined('PHP_WINDOWS_VERSION_MAJOR') ? '"' . $arg . '"' : escapeshellarg($arg);
    }
}
