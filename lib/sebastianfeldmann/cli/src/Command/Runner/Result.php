<?php

/**
 * This file is part of SebastianFeldmann\Cli.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Cli\Command\Runner;

use SebastianFeldmann\Cli\Command\Result as CommandResult;

/**
 * Class Result
 *
 * @package SebastianFeldmann\Cli
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/cli
 * @since   Class available since Release 0.9.0
 */
class Result
{
    /**
     * Result of executed command.
     *
     * @var \SebastianFeldmann\Cli\Command\Result
     */
    private $cmdResult;

    /**
     * Formatted output of executed result.
     *
     * @var iterable
     */
    private $formatted;

    /**
     * Result constructor.
     *
     * @param \SebastianFeldmann\Cli\Command\Result $cmdResult
     * @param iterable                              $formatted
     */
    public function __construct(CommandResult $cmdResult, iterable $formatted = [])
    {
        $this->cmdResult = $cmdResult;
        $this->formatted = $formatted;
    }

    /**
     * Get the raw command result.
     *
     * @return \SebastianFeldmann\Cli\Command\Result
     */
    public function getCommandResult(): CommandResult
    {
        return $this->cmdResult;
    }

    /**
     * Return true if command execution was successful.
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->cmdResult->isSuccessful();
    }

    /**
     * Return the command exit code.
     *
     * @return int
     */
    public function getCode(): int
    {
        return $this->cmdResult->getCode();
    }

    /**
     * Return the executed cli command.
     *
     * @return string
     */
    public function getCmd(): string
    {
        return $this->cmdResult->getCmd();
    }

    /**
     * Return commands output to stdOut.
     *
     * @return string
     */
    public function getStdOut(): string
    {
        return $this->cmdResult->getStdOut();
    }

    /**
     * Return commands error output to stdErr.
     *
     * @return string
     */
    public function getStdErr(): string
    {
        return $this->cmdResult->getStdErr();
    }

    /**
     * Is the output redirected to a file.
     *
     * @return bool
     */
    public function isOutputRedirected(): bool
    {
        return $this->cmdResult->isOutputRedirected();
    }

    /**
     * Return path to the file where the output is redirected to.
     *
     * @return string
     */
    public function getRedirectPath(): string
    {
        return $this->cmdResult->getRedirectPath();
    }

    /**
     * Return cmd output as array.
     *
     * @return array
     */
    public function getBufferedOutput(): array
    {
        return $this->cmdResult->getStdOutAsArray();
    }

    /**
     * Return formatted output.
     *
     * @return iterable
     */
    public function getFormattedOutput(): iterable
    {
        return $this->formatted;
    }
}
