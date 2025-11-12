<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Git\ChangedFiles;

/**
 * Detector interface
 *
 * Interface to detect changed files for the different hooks.
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.20.0
 */
interface Detecting
{
    /**
     * Returns a list of changed files
     *
     * @param  array<string> $filter
     * @return array<string>
     */
    public function getChangedFiles(array $filter = []): array;
}
