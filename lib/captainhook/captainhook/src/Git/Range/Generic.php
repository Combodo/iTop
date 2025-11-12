<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Git\Range;

use CaptainHook\App\Git\Range;
use CaptainHook\App\Git\Rev;

/**
 * Generic range implementation
 *
 * Most simple range implementation
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.15.0
 */
class Generic implements Range
{
    /**
     * Starting reference
     *
     * @var \CaptainHook\App\Git\Rev
     */
    private Rev $from;

    /**
     * Ending reference
     *
     * @var \CaptainHook\App\Git\Rev
     */
    private Rev $to;

    /**
     * Constructor
     *
     */
    public function __construct(Rev $from, Rev $to)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    /**
     * Return the git reference
     *
     * @return \CaptainHook\App\Git\Rev
     */
    public function from(): Rev
    {
        return $this->from;
    }

    /**
     * @return \CaptainHook\App\Git\Rev
     */
    public function to(): Rev
    {
        return $this->to;
    }
}
