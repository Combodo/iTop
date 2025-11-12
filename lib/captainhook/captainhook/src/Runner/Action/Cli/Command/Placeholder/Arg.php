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

/**
 * Class Arg
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.19.0
 */
class Arg extends Foundation
{
    /**
     * Return the requested command ARGUMENT or a given default, returns empty string by default
     *
     * @param  array<string, mixed> $options
     * @return string
     */
    public function replacement(array $options): string
    {
        $var     = $options['value-of'] ?? '_';
        $default = $options['default'] ?? '';

        return $this->io->getArgument(self::toArgument($var), $default);
    }

    /**
     * Converts an argument name to a placeholder string
     *
     * @param string $arg
     * @return string
     */
    public static function toPlaceholder(string $arg): string
    {
        return str_replace('-', '_', strtoupper($arg));
    }

    /**
     * Converts a placeholder string to an argument name
     *
     * @param string $placeholder
     * @return string
     */
    public static function toArgument(string $placeholder): string
    {
        return str_replace('_', '-', strtolower($placeholder));
    }
}
