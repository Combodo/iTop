<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian.feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Operator;

use RuntimeException;
use SebastianFeldmann\Cli\Command\Runner\Result;
use SebastianFeldmann\Git\Command\Config\Get;
use SebastianFeldmann\Git\Command\Config\GetVar;
use SebastianFeldmann\Git\Command\Config\ListSettings;
use SebastianFeldmann\Git\Command\Config\ListVars;
use SebastianFeldmann\Git\Command\Config\MapValues;

/**
 * Class Config
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 1.0.2
 */
class Config extends Base
{
    private const TYPE_CONFIG = 'config';
    private const TYPE_VAR    = 'var';

    /**
     * @deprectaed since 3.14.0 replaced by `hasSetting`
     */
    public function has(string $name): bool
    {
        trigger_error('\'has\' should be replaced by \'hasSetting\'', E_USER_DEPRECATED);
        return $this->hasSetting($name);
    }

    /**
     * Does git have a config value set
     *
     * @param  string $name
     * @return boolean
     */
    public function hasSetting(string $name): bool
    {
        return $this->hasIt(self::TYPE_CONFIG, $name);
    }

    /**
     * Does git have a var set
     *
     * @param  string $name
     * @return boolean
     */
    public function hasVar(string $name): bool
    {
        return $this->hasIt(self::TYPE_VAR, $name);
    }

    /**
     * Use the `config` or `var` method to check
     *
     * @param  string $type
     * @param  string $name
     * @return bool
     */
    private function hasIt(string $type, string $name): bool
    {
        $method = $type . 'Command';
        try {
            $result = $this->{$method}($name);
        } catch (RuntimeException $exception) {
            return false;
        }

        return $result->isSuccessful();
    }

    /**
     * @deprecated since 3.14.0 replaced by `getSetting`
     */
    public function get(string $name): string
    {
        trigger_error('\'get\' should be replaced by \'getSetting\'', E_USER_DEPRECATED);
        return $this->getSetting($name);
    }

    /**
     * Get a configuration value
     *
     * @param  string $name
     * @return string
     */
    public function getSetting(string $name): string
    {
        $result = $this->configCommand($name);
        return $result->getBufferedOutput()[0] ?? '';
    }

    /**
     * Get a var value
     *
     * @param  string $name
     * @return string
     */
    public function getVar(string $name): string
    {
        $result = $this->varCommand($name);
        return $result->getBufferedOutput()[0] ?? '';
    }

    /**
     * @deprecated since 3.14.0 replaced by `getSettingSafely`
     */
    public function getSafely(string $name, string $default = ''): string
    {
        trigger_error('\'getSafely\' should be replaced by \'getSettingSafely\'', E_USER_DEPRECATED);
        return $this->getSettingSafely($name, $default);
    }

    /**
     * Get config values without throwing exceptions.
     *
     * You can provide a default value to return.
     * By default, the return value on unset config values is the empty string.
     *
     * @param  string $name    Name of the config value to retrieve
     * @param  string $default Value to return if config value is not set, empty string by default
     * @return string
     */
    public function getSettingSafely(string $name, string $default = ''): string
    {
        return $this->hasSetting($name) ? $this->getSetting($name) : $default;
    }

    /**
     * Get var value without throwing an exception if it does not exist
     *
     * @param  string $name
     * @param  string $default
     * @return string
     */
    public function getVarSafely(string $name, string $default = ''): string
    {
        return $this->hasVar($name) ? $this->getVar($name) : $default;
    }

    /**
     * Return a map of all configuration settings.
     *
     * For example: ['color.branch' => 'auto', 'color.diff' => 'auto']
     *
     * @return array<string, string>
     */
    public function getSettings(): iterable
    {
        $cmd = new ListSettings($this->repo->getRoot());
        $res = $this->runner->run($cmd, new MapValues());
        return $res->getFormattedOutput();
    }


    /**
     * Return a map of all defined git vars
     *
     * For example: ['color.branch' => 'auto', 'color.diff' => 'auto']
     *
     * @return array<string, string>
     */
    public function getVars(): iterable
    {
        $cmd = new ListVars($this->repo->getRoot());
        $res = $this->runner->run($cmd, new MapValues());
        return $res->getFormattedOutput();
    }

    /**
     * Run the get config command.
     *
     * @param  string $name
     * @return \SebastianFeldmann\Cli\Command\Runner\Result
     */
    private function configCommand(string $name): Result
    {
        $cmd = (new Get($this->repo->getRoot()));
        $cmd->name($name);
        return $this->runner->run($cmd);
    }

    /**
     * Return the var command
     *
     * @param  string $name
     * @return \SebastianFeldmann\Cli\Command\Runner\Result
     */
    private function varCommand(string $name): Result
    {
        $cmd = (new GetVar($this->repo->getRoot()));
        $cmd->name($name);
        return $this->runner->run($cmd);
    }
}
