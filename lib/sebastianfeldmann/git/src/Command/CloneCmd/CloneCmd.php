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

namespace SebastianFeldmann\Git\Command\CloneCmd;

use SebastianFeldmann\Git\Command\Base;
use SebastianFeldmann\Git\Url;

/**
 * Class CloneCmd
 *
 * @package SebastianFeldmann\Git
 * @author  Andreas Frömer
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 3.8.0
 */
final class CloneCmd extends Base
{
    /**
     * @var Url
     */
    private Url $url;

    /**
     * @var string
     */
    private string $dir = '';

    /**
     * @var string
     */
    private string $depth = '';

    public function __construct(Url $url)
    {
        $this->url = $url;
        parent::__construct();
    }

    /**
     * Specify the directory to clone into
     *
     * @param  string $dir
     * @return $this
     */
    public function dir(string $dir = ''): CloneCmd
    {
        $this->dir = $dir;
        return $this;
    }

    /**
     * Limit the history to the number of commits
     *
     * @param  int $depth
     * @return $this
     */
    public function depth(int $depth): CloneCmd
    {
        $this->depth = $this->useOption('--depth=' . $depth, true);
        return $this;
    }

    /**
     * Returns the git command to execute the clone
     *
     * @return string
     */
    protected function getGitCommand(): string
    {
        return 'clone'
            . $this->depth
            . ' '
            . escapeshellarg($this->url->getUrl())
            . $this->useOption(escapeshellarg($this->dir), !empty($this->dir));
    }
}
