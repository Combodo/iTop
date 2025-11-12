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
 * Stripe regex supplier
 *
 * Provides the regex to find Stripe secrets.
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/secrets
 * @since   Class available since Release 0.9.1
 */
class Stripe implements Supplier
{
    /**
     * Returns a list of patterns to check
     *
     * @return array<string>
     */
    public function patterns(): array
    {
        return [
            // Standard API Key & Restricted API Key
            '#' . Util::OPTIONAL_QUOTE . '(sk_live_[0-9a-z]{24})' . Util::OPTIONAL_QUOTE . '#',
        ];
    }
}
