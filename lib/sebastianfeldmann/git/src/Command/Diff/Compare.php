<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\Diff;

use SebastianFeldmann\Git\Command\Base;

/**
 * Class Between
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 1.2.0
 */
class Compare extends Base
{
    /**
     * Compare A/B command snippet.
     *
     * @var string
     */
    protected string $compare = '';

    /**
     * Ignore line endings.
     *
     * @var string
     */
    protected string $ignoreEOL = '';

    /**
     * Show statistics only.
     *
     * @var string
     */
    protected string $stats = '';

    /**
     * Ignore all whitespaces.
     *
     * @var string
     */
    private string $ignoreWhitespaces = '';

    /**
     * Ignore submodules.
     *
     * @var string
     */
    private string $ignoreSubmodules = '';

    /**
     * Number of context lines before and after the diff
     *
     * @var string
     */
    private string $unified = '';

    /**
     * View the changes staged for the next commit.
     *
     * @var string
     */
    private string $staged = '';

    /**
     * Compare two given revisions.
     *
     * @param  string $from
     * @param  string $to
     * @return \SebastianFeldmann\Git\Command\Diff\Compare
     */
    public function revisions(string $from, string $to): Compare
    {
        $this->compare = escapeshellarg($from) . ' ' . escapeshellarg($to);
        return $this;
    }

    /**
     * Compares the working tree or index to a given commit-ish
     *
     * @param  string $to
     * @return \SebastianFeldmann\Git\Command\Diff\Compare
     */
    public function to(string $to = 'HEAD'): Compare
    {
        $this->compare = escapeshellarg($to);
        return $this;
    }

    /**
     * Compares the index to a given commit hash
     *
     * This method is a shortcut for calling {@see staged()} and {@see to()}.
     *
     * @param  string $to
     * @return \SebastianFeldmann\Git\Command\Diff\Compare
     */
    public function indexTo(string $to = 'HEAD'): Compare
    {
        return $this->staged()->to($to);
    }

    /**
     * View the changes staged for the next commit relative to the <commit>
     * named with {@see to()}.
     *
     * @param  bool $bool
     * @return \SebastianFeldmann\Git\Command\Diff\Compare
     */
    public function staged(bool $bool = true): Compare
    {
        $this->stats = $this->useOption('--staged', $bool);
        return $this;
    }

    /**
     * Generate diffs with $amount lines of context instead of the usual three
     *
     * @param  int $amount
     * @return \SebastianFeldmann\Git\Command\Diff\Compare
     */
    public function withContextLines(int $amount): Compare
    {
        $this->unified = $amount === 3 ? '' : ' --unified=' . $amount;
        return $this;
    }

    /**
     * Set diff statistics option.
     *
     * @param  bool $bool
     * @return \SebastianFeldmann\Git\Command\Diff\Compare
     */
    public function statsOnly(bool $bool = true): Compare
    {
        $this->stats = $this->useOption('--numstat', $bool);
        return $this;
    }

    /**
     * Set ignore spaces at end of line.
     *
     * @param  bool $bool
     * @return \SebastianFeldmann\Git\Command\Diff\Compare
     */
    public function ignoreWhitespacesAtEndOfLine(bool $bool = true): Compare
    {
        $this->ignoreEOL = $this->useOption('--ignore-space-at-eol', $bool);
        return $this;
    }

    /**
     * Set ignore all whitespaces.
     *
     * @param  bool $bool
     * @return \SebastianFeldmann\Git\Command\Diff\Compare
     */
    public function ignoreWhitespaces(bool $bool = true): Compare
    {
        $this->ignoreWhitespaces = $this->useOption('-w', $bool);
        return $this;
    }

    /**
     * Set ignore submodules.
     *
     * @param  bool $bool
     * @return \SebastianFeldmann\Git\Command\Diff\Compare
     */
    public function ignoreSubmodules(bool $bool = true): Compare
    {
        $this->ignoreSubmodules = $this->useOption('--ignore-submodules', $bool);
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
        return 'diff'
               . ' --no-ext-diff'
               . ' --diff-algorithm=myers'
               . $this->unified
               . $this->ignoreWhitespaces
               . $this->ignoreSubmodules
               . $this->ignoreEOL
               . $this->stats
               . $this->staged
               . ' ' . $this->compare
               . ' -- ';
    }
}
