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
 * Password regex supplier
 *
 * Provides the regex to find generic passwords.
 *
 * @package CaptainHook-Secrets
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/secrets
 * @since   Class available since Release 0.9.1
 */
class Password implements Supplier
{
    /**
     * Returns a list of patterns to check
     *
     * @return array<string>
     */
    public function patterns(): array
    {
        return [
            // Generic passwords
            '#password' . Util::OPTIONAL_QUOTE . Util::CONNECT . Util::OPTIONAL_QUOTE
            . '([a-z\\-_\\#/\\+0-9]{16,})' . Util::OPTIONAL_QUOTE . '#i',
        ];
    }
}
