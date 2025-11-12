<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command;

use SebastianFeldmann\Cli\Command;

/**
 * Class Base
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 0.9.0
 */
abstract class Base implements Command
{
    /**
     * Repository root directory.
     *
     * @var string
     */
    protected string $repositoryRoot;

    /**
     * Configuration parameters to pass along with the command.
     *
     * @var array<string, string>
     */
    private array $configParameters = [];

    /**
     * Base constructor.
     *
     * @param string $root
     */
    public function __construct(string $root = '')
    {
        $this->repositoryRoot = $root;
    }

    /**
     * Return cli command to execute.
     *
     * @return string
     */
    public function getCommand(): string
    {
        return 'git'
            . $this->getRootOption()
            . $this->getConfigParameterOptions()
            . ' '
            . $this->getGitCommand();
    }

    /**
     * Return list of acceptable exit codes.
     *
     * @return array<int>
     */
    public function getAcceptableExitCodes(): array
    {
        return [0];
    }

    /**
     * Adds a configuration parameter to pass to the command.
     *
     * This only modifies the current command object. It does not change other
     * command objects, nor does it affect `~/.gitconfig` or `.git/config`.
     *
     * @param string $name Configuration parameter name in the same format as
     *     listed by git config (subkeys separated by dots).
     * @param scalar|null $value The parameter value, or `null` to unset a
     *     previously set configuration parameter.
     * @return self
     */
    public function setConfigParameter(string $name, $value): self
    {
        if ($value === null) {
            unset($this->configParameters[$name]);
            return $this;
        }

        if (is_bool($value)) {
            $value = ($value === true) ? 'true' : 'false';
        }

        $this->configParameters[$name] = (string) $value;

        return $this;
    }

    /**
     * Do we need the -C option.
     *
     * @return string
     */
    protected function getRootOption(): string
    {
        $option = '';
        // if root is set
        if (!empty($this->repositoryRoot)) {
            // and it's not the current working directory
            if (getcwd() !== $this->repositoryRoot) {
                $option = ' -C ' . escapeshellarg($this->repositoryRoot);
            }
        }
        return $option;
    }

    /**
     * Returns a string of any config parameters set for use in the command.
     *
     * @return string
     */
    protected function getConfigParameterOptions(): string
    {
        $options = '';
        foreach ($this->configParameters as $name => $value) {
            $options .= ' -c ' . escapeshellarg($name . '=' . $value);
        }
        return $options;
    }

    /**
     * Should an option be used or not.
     *
     * @param  string $option
     * @param  bool   $switch
     * @return string
     */
    protected function useOption(string $option, bool $switch): string
    {
        return ($switch ? ' ' . $option : '');
    }

    /**
     * Auto cast method.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getCommand();
    }

    /**
     * Return the command to execute.
     *
     * @return string
     * @throws \RuntimeException
     */
    abstract protected function getGitCommand(): string;
}
