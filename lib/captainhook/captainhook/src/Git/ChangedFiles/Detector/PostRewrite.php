<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Git\ChangedFiles\Detector;

use CaptainHook\App\Git\ChangedFiles\Detector;
use CaptainHook\App\Git\Range\Detector\PostRewrite as RangeDetector;

/**
 * Class PostRewrite
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.20.0
 */
class PostRewrite extends Detector
{
    /**
     * Returns a list of changed files
     *
     * @param  array<string> $filter
     * @return array<string>
     */
    public function getChangedFiles(array $filter = []): array
    {
        $detector = new RangeDetector();
        $ranges   = $detector->getRanges($this->io);
        $old      = $ranges[0]->from()->id();
        $new      = $ranges[0]->to()->id();

        return $this->repository->getDiffOperator()->getChangedFiles($old, $new, $filter);
    }
}
