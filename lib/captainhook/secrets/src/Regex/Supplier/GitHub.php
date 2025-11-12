<?php

/**
 * This file is part of CaptainHook Secrets.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptainHook\Secrets\Regex\Supplier;

use CaptainHook\Secrets\Regex\Supplier;

/**
 * GitHub regex supplier
 *
 * Provides the regex to find GitHub secrets.
 *
 * @package CaptainHook-Secrets
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/secrets
 * @since   Class available since Release 0.9.1
 */
class GitHub implements Supplier
{
    /**
     * Returns a list of patterns to check
     *
     * @return array<string>
     */
    public function patterns(): array
    {
        return [
            // Personal Access Token (Classic)
            '#' . Util::OPTIONAL_QUOTE . '(ghp_[a-zA-Z0-9]{36})' . Util::OPTIONAL_QUOTE . '#',

            // Personal Access Token (Fine-Grained)
            '#' . Util::OPTIONAL_QUOTE . '(github_pat_[a-zA-Z0-9]{22}_[a-zA-Z0-9]{59})' . Util::OPTIONAL_QUOTE . '#',

            // User-To-Server Access Token
            '#' . Util::OPTIONAL_QUOTE . '(ghu_[a-zA-Z0-9]{36})' . Util::OPTIONAL_QUOTE . '#',

            // Server-To-Server Access Token
            '#' . Util::OPTIONAL_QUOTE . '(ghs_[a-zA-Z0-9]{36})' . Util::OPTIONAL_QUOTE . '#',
        ];
    }
}
