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

use CaptainHook\App\Git;
use CaptainHook\App\Git\Rev\PrePush as Rev;

/**
 * Class
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.15.0
 */
class PrePush implements Git\Range
{
    /**
     * @var \CaptainHook\App\Git\Rev\PrePush
     */
    private Rev $from;

    /**
     * @var \CaptainHook\App\Git\Rev\PrePush
     */
    private Rev $to;

    /**
     * Constructor
     *
     * @param \CaptainHook\App\Git\Rev\PrePush $from
     * @param \CaptainHook\App\Git\Rev\PrePush $to
     */
    public function __construct(Rev $from, Rev $to)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    /**
     * Returns the start ref
     *
     * @return \CaptainHook\App\Git\Rev\PrePush
     */
    public function from(): Rev
    {
        return $this->from;
    }

    /**
     * Returns the end ref
     *
     * @return \CaptainHook\App\Git\Rev\PrePush
     */
    public function to(): Rev
    {
        return $this->to;
    }
}
