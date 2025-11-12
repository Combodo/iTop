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
use CaptainHook\App\Hooks;

/**
 * Class Fallback
 *
 * This class should not be used it is just a fallback if the `pre-push` or `post-rewrite`
 * variants are somehow not applicable.
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.20.0
 */
class Fallback extends Detector
{
    /**
     * Returns the list of changed files in a best-guess kind of way
     *
     * @param  array<string> $filter
     * @return array<string>
     */
    public function getChangedFiles(array $filter = []): array
    {
        return $this->repository->getDiffOperator()->getChangedFiles(
            $this->io->getArgument(Hooks::ARG_PREVIOUS_HEAD, 'HEAD@{1}'),
            'HEAD',
            $filter
        );
    }
}
