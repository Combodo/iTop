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

use RuntimeException;

/**
 * Class CommandLine
 *
 * @package SebastianFeldmann\Cli
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/cli
 * @since   Class available since Release 0.9.0
 */
class CommandLine implements Command
{
    /**
     * List of system commands to execute
     *
     * @var \SebastianFeldmann\Cli\Command[]
     */
    private $commands = [];

    /**
     * Redirect the output
     *
     * @var string
     */
    private $redirectOutput;

    /**
     * Output pipeline
     *
     * @var \SebastianFeldmann\Cli\Command[]
     */
    private $pipeline = [];

    /**
     * Should 'pipefail' be set?
     *
     * @var bool
     */
    private $pipeFail = false;

    /**
     * List of acceptable exit codes.
     *
     * @var array
     */
    private $acceptedExitCodes = [0];

    /**
     * Set the list of accepted exit codes
     *
     * @param  int[] $codes
     * @return void
     */
    public function acceptExitCodes(array $codes): void
    {
        $this->acceptedExitCodes = $codes;
    }

    /**
     * Redirect the stdOut.
     *
     * @param  string $path
     * @return void
     */
    public function redirectOutputTo($path): void
    {
        $this->redirectOutput = $path;
    }

    /**
     * Should the output be redirected
     *
     * @return bool
     */
    public function isOutputRedirected(): bool
    {
        return !empty($this->redirectOutput);
    }

    /**
     * Redirect getter.
     *
     * @return string
     */
    public function getRedirectPath(): string
    {
        return $this->redirectOutput;
    }

    /**
     * Pipe the command into given command
     *
     * @param  \SebastianFeldmann\Cli\Command $cmd
     * @return void
     */
    public function pipeOutputTo(Command $cmd): void
    {
        if (!$this->canPipe()) {
            throw new RuntimeException('Can\'t pipe output');
        }
        $this->pipeline[] = $cmd;
    }

    /**
     * Get the 'pipefail' option command snippet
     *
     * @return string
     */
    public function getPipeFail(): string
    {
        return ($this->isPiped() && $this->pipeFail) ? 'set -o pipefail; ' : '';
    }

    /**
     * Can the pipe '|' operator be used
     *
     * @return bool
     */
    public function canPipe(): bool
    {
        return !defined('PHP_WINDOWS_VERSION_BUILD');
    }

    /**
     * Is there a command pipeline
     *
     * @return bool
     */
    public function isPiped(): bool
    {
        return !empty($this->pipeline);
    }

    /**
     * Should the pipefail option be set
     *
     * @param bool $pipeFail
     */
    public function pipeFail(bool $pipeFail)
    {
        $this->pipeFail = $pipeFail;
    }

    /**
     * Return command pipeline
     *
     * @return string
     */
    public function getPipeline(): string
    {
        return $this->isPiped() ? ' | ' . implode(' | ', $this->pipeline) : '';
    }

    /**
     * Adds a cli command to list of commands to execute
     *
     * @param  \SebastianFeldmann\Cli\Command $cmd
     * @return void
     */
    public function addCommand(Command $cmd): void
    {
        $this->commands[] = $cmd;
    }

    /**
     * Generates the system command
     *
     * @return string
     */
    public function getCommand(): string
    {
        $amount = count($this->commands);
        if ($amount < 1) {
            throw new RuntimeException('no command to execute');
        }
        $cmd = $this->getPipeFail()
             . ($amount > 1 ? '(' . implode(' && ', $this->commands) . ')' : $this->commands[0])
             . $this->getPipeline()
             . (!empty($this->redirectOutput) ? ' > ' . $this->redirectOutput : '');

        return $cmd;
    }

    /**
     * Returns a list of exit codes that are valid
     *
     * @return array
     */
    public function getAcceptableExitCodes(): array
    {
        return $this->acceptedExitCodes;
    }

    /**
     * Returns the command to execute
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getCommand();
    }
}
