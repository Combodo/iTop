<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Console\IO;

use CaptainHook\App\Console\IO;

/**
 * Class Base
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
abstract class Base implements IO
{
    /**
     * Return the original cli arguments
     *
     * @return array<mixed>
     */
    public function getArguments(): array
    {
        return [];
    }

    /**
     * Return the original cli argument or a given default
     *
     * @param  string $name
     * @param  string $default
     * @return string
     */
    public function getArgument(string $name, string $default = ''): string
    {
        return $default;
    }

    /**
     * Return the piped in standard input
     *
     * @return string[]
     */
    public function getStandardInput(): array
    {
        return [];
    }
}
