<?php

/**
 * This file is part of SebastianFeldmann\Cli.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Cli;

/**
 * Class Command
 *
 * @package SebastianFeldmann\Cli
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/cli
 * @since   Class available since Release 0.9.0
 */
interface Command
{
    /**
     * Get the cli command.
     *
     * @return string
     */
    public function getCommand(): string;

    /**
     * Returns a list of exit codes that are valid.
     *
     * @return array
     */
    public function getAcceptableExitCodes(): array;

    /**
     * Convert command to string
     *
     * @return string
     */
    public function __toString(): string;
}
