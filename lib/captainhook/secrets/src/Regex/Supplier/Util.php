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

/**
 * Regex utility constants and functions
 *
 * @package CaptainHook-Secrets
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/secrets
 * @since   Class available since Release 0.9.1
 */
abstract class Util
{
    public const QUOTE          = '("|\')';
    public const OPTIONAL_QUOTE = self::QUOTE . '?';
    public const CONNECT        = '\s*(:|=>|=|:=)\s*';
}
