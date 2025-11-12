<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptainHook\Secrets\Regex;

/**
 * Supplier Interface
 *
 * @package CaptainHook-Secrets
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/secrets
 * @since   Class available since Release 0.9.1
 */
interface Supplier
{
    /**
     * Return a list of regex patterns
     *
     * @return array<string>
     */
    public function patterns(): array;
}
