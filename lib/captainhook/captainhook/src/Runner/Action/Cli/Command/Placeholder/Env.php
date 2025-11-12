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

use CaptainHook\App\Runner\Util;

/**
 * Class Env
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.8.0
 */
class Env extends Foundation
{
    /**
     * Return the requested ENVIRONMENT variable or a given default, returns empty string by default
     *
     * @param  array<string, mixed> $options
     * @return string
     */
    public function replacement(array $options): string
    {
        if (!isset($options['value-of'])) {
            return '';
        }

        $var     = $options['value-of'];
        $default = $options['default'] ?? '';

        return Util::getEnv($var, $default);
    }
}
