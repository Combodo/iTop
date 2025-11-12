<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Git;

/**
 * Range class
 *
 * Represents a git range with a starting ref and an end ref.
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.15.0
 */
interface Range
{
    /**
     * Returns the start ref
     *
     * @return \CaptainHook\App\Git\Rev
     */
    public function from(): Rev;

    /**
     * Returns the end ref
     *
     * @return \CaptainHook\App\Git\Rev
     */
    public function to(): Rev;
}
