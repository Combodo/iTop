<?php

/**
 * This file is part of SebastianFeldmann\Cli.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Cli\Command;

use SebastianFeldmann\Cli\Command;
use SebastianFeldmann\Cli\Util;

/**
 * Class Executable
 *
 * @package SebastianFeldmann\Cli
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/cli
 * @since   Class available since Release 0.9.0
 */
class Executable implements Command
{
    /**
     * Command name
     *
     * @var string
     */
    private $cmd;

    /**
     * Display stdErr
     *
     * @var boolean
     */
    private $isSilent = false;

    /**
     * Command options
     *
     * @var string[]
     */
    private $options = [];

    /**
     * List of variables to define
     *
     * @var string[]
     */
    private $vars = [];

    /**
     * List of acceptable exit codes.
     *
     * @var array
     */
    private $acceptableExitCodes = [];

    /**
     * Constructor.
     *
     * @param string $cmd
     * @param int[]  $exitCodes
     */
    public function __construct(string $cmd, array $exitCodes = [0])
    {
        $this->cmd                 = $cmd;
        $this->acceptableExitCodes = $exitCodes;
    }

    /**
     * Returns the string to execute on the command line.
     *
     * @return string
     */
    public function getCommand(): string
    {
        $cmd = $this->getVars() . sprintf('"%s"', $this->cmd)
             . (count($this->options)   ? ' ' . implode(' ', $this->options)   : '')
             . ($this->isSilent         ? ' 2> /dev/null'                      : '');

        return Util::escapeSpacesIfOnWindows($cmd);
    }

    /**
     * Returns a list of exit codes that are valid.
     *
     * @return int[]
     */
    public function getAcceptableExitCodes(): array
    {
        return $this->acceptableExitCodes;
    }

    /**
     * Silence the 'Cmd' by redirecting its stdErr output to /dev/null.
     * The silence feature is disabled for Windows systems.
     *
     * @param  bool $bool
     * @return \SebastianFeldmann\Cli\Command\Executable
     */
    public function silence($bool = true): Executable
    {
        $this->isSilent = $bool && !defined('PHP_WINDOWS_VERSION_BUILD');
        return $this;
    }

    /**
     * Add option to list.
     *
     * @param  string               $option
     * @param  mixed                $value
     * @param  string               $glue
     * @return \SebastianFeldmann\Cli\Command\Executable
     */
    public function addOption(string $option, $value = null, string $glue = '='): Executable
    {
        if ($value !== null) {
            // force space for multiple arguments e.g. --option 'foo' 'bar'
            if (is_array($value)) {
                $glue = ' ';
            }
            $value = $glue . $this->escapeArgument($value);
        } else {
            $value = '';
        }
        $this->options[] = $option . $value;

        return $this;
    }

    /**
     * Add a var definition to a command
     *
     * @param  string $name
     * @param  string $value
     * @return $this
     */
    public function addVar(string $name, string $value): Executable
    {
        $this->vars[$name] = $value;

        return $this;
    }

    /**
     * Return variable definition string e.g. "MYFOO='sometext' MYBAR='nothing' "
     *
     * @return string
     */
    protected function getVars(): string
    {
        $varStrings = [];

        foreach ($this->vars as $name => $value) {
            $varStrings[] = $name . '=' . escapeshellarg($value);
        }

        return count($varStrings) ? implode(' ', $varStrings) . ' ' : '';
    }

    /**
     * Adds an option to a command if it is not empty.
     *
     * @param  string $option
     * @param  mixed  $check
     * @param  bool   $asValue
     * @param  string $glue
     * @return \SebastianFeldmann\Cli\Command\Executable
     */
    public function addOptionIfNotEmpty(string $option, $check, bool $asValue = true, string $glue = '='): Executable
    {
        if (!empty($check)) {
            if ($asValue) {
                $this->addOption($option, $check, $glue);
            } else {
                $this->addOption($option);
            }
        }
        return $this;
    }

    /**
     * Add argument to list.
     *
     * @param  mixed $argument
     * @return \SebastianFeldmann\Cli\Command\Executable
     */
    public function addArgument($argument): Executable
    {
        $this->options[] = $this->escapeArgument($argument);
        return $this;
    }

    /**
     * Escape a shell argument.
     *
     * @param  mixed $argument
     * @return string
     */
    protected function escapeArgument($argument): string
    {
        if (is_array($argument)) {
            $argument = array_map('escapeshellarg', $argument);
            $escaped  = implode(' ', $argument);
        } else {
            $escaped = escapeshellarg($argument);
        }
        return $escaped;
    }

    /**
     * Returns the command to execute.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getCommand();
    }
}
