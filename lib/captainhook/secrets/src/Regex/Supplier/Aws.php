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
 * Aws regex supplier
 *
 * Provides the regex to find AWS secrets.
 *
 * @package CaptainHook-Secrets
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/secrets
 * @since   Class available since Release 0.9.1
 */
class Aws implements Supplier
{
    /**
     * Possible AWS pattern
     */
    public const AWS = '(AWS|aws|Aws)?_?';

    /**
     * Returns a list of patterns to check
     *
     * @return array<string>
     */
    public function patterns(): array
    {
        return [
            // AWS token
            '#(A3T[A-Z0-9]|AKIA|AGPA|AIDA|AROA|AIPA|ANPA|ANVA|ASIA)[A-Z0-9]{16}#',

            // AWS secrets, keys, access token
            '#' . Util::OPTIONAL_QUOTE . self::AWS . '(SECRET|secret|Secret)?_?(ACCESS|access|Access)?_?(KEY|key|Key)'
            . Util::OPTIONAL_QUOTE . Util::CONNECT
            . Util::OPTIONAL_QUOTE . '([A-Za-z0-9/\\+=]{40})' . Util::OPTIONAL_QUOTE . '#',

            // AWS account id
            '#' . Util::OPTIONAL_QUOTE . self::AWS . '(ACCOUNT|account|Account)_?(ID|id|Id)?' . Util::OPTIONAL_QUOTE
            . Util::CONNECT . Util::OPTIONAL_QUOTE . '([0-9]{4}\\-?[0-9]{4}\\-?[0-9]{4})' . Util::OPTIONAL_QUOTE . '#',
        ];
    }
}
