<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Runner\Action\Cli\Command\Placeholder;

use CaptainHook\App\Git;
use CaptainHook\App\Hook\FileList;

/**
 * Changed Files Placeholder
 *
 * This placeholder only works for pre-push, post-rewrite, post-checkout and post-merge actions.
 * If it is used in a pre-push hook and multiple refs are pushed the placeholder will contain
 * all changed files for all refs.
 *
 * Usage examples:
 *  - {$CHANGED_FILES|separated-by:,}
 *  - {$CHANGED_FILES|in-dir:foo/bar}
 *  - {$CHANGED_FILES|of-type:php}
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.15.3
 */
class ChangedFiles extends Foundation
{
    /**
     * @param  array<string, string> $options
     * @return string
     */
    public function replacement(array $options): string
    {
        $files = Git\ChangedFiles::getChangedFiles($this->io, $this->repository, ['A', 'C', 'M', 'R']);
        return implode(($options['separated-by'] ?? ' '), FileList::filter($files, $options));
    }
}
