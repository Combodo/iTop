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

use CaptainHook\App\Console\IO;

/**
 * Detecting interface
 *
 * Interface to gathering the previous state to current state ranges.
 * To handle gathering the ranges for pre-push, post-rewrite, post-checkout separately.
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.15.0
 */
interface Detecting
{
    /**
     * Returns a list of ranges marking before and after points to collect the changes happening in between
     *
     * @param  \CaptainHook\App\Console\IO $io
     * @return array<\CaptainHook\App\Git\Range>
     */
    public function getRanges(IO $io): array;
}
