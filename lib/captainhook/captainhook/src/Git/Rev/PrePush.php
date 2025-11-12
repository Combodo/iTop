<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Git\Rev;

use CaptainHook\App\Git\Rev;

/**
 * Git pre-push reference
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.15.0
 */
class PrePush implements Rev
{
    /**
     * Head path - refs/heads/main
     *
     * @var string
     */
    private string $head;

    /**
     * Git hash
     *
     * @var string
     */
    private string $hash;

    /**
     * Branch name
     *
     * @var string
     */
    private string $branch;

    /**
     * Constructor
     *
     * @param string $head
     * @param string $hash
     * @param string $branch
     */
    public function __construct(string $head, string $hash, string $branch)
    {
        $this->head   = $head;
        $this->hash   = $hash;
        $this->branch = $branch;
    }

    /**
     * Head getter
     *
     * @return string
     */
    public function head(): string
    {
        return $this->head;
    }

    /**
     * Hash getter
     *
     * @return string
     */
    public function hash(): string
    {
        return $this->hash;
    }

    /**
     * Branch getter
     *
     * @return string
     */
    public function branch(): string
    {
        return $this->branch;
    }

    /**
     * Returns the ref id that can be used in a git command
     *
     * This can be completely different thing hash, branch name, ref-log position...
     *
     * @return string
     */
    public function id(): string
    {
        return $this->hash;
    }

    /**
     * Is this a git dummy hash
     *
     * @return bool
     */
    public function isZeroRev(): bool
    {
        return Util::isZeroHash($this->hash);
    }
}
