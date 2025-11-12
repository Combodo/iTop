<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\Condition\FileChanged;

use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Condition\FileChanged;
use SebastianFeldmann\Git\Repository;

/**
 * Class Any
 *
 * The FileChange condition is applicable for `post-merge` and `post-checkout` hooks.
 * For example it can be used to trigger an automatic composer install if the composer.json
 * or composer.lock file is changed during a checkout or merge.
 *
 * Example configuration:
 *
 * "action": "composer install"
 * "conditions": [
 *   {"exec": "\\CaptainHook\\App\\Hook\\Condition\\FileChange\\Any",
 *    "args": [
 *      [
 *        "composer.json",
 *        "composer.lock"
 *      ]
 *    ]}
 * ]
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 4.2.0
 */
class Any extends FileChanged
{
    /**
     * Check if any of the configured files was changed within the applied change set
     *
     * IMPORTANT: If no files are configured this condition is always false.
     *
     * @param  \CaptainHook\App\Console\IO       $io
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @return bool
     */
    public function isTrue(IO $io, Repository $repository): bool
    {
        return $this->anyFileInHaystack($this->filesToWatch, $this->getChangedFiles($io, $repository));
    }
}
