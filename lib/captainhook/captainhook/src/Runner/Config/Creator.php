<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Runner\Config;

use CaptainHook\App\Config;
use CaptainHook\App\Runner;
use RuntimeException;

/**
 * Class Configurator
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
class Creator extends Runner
{
    /**
     * Force mode
     *
     * @var bool
     */
    private bool $force = false;

    /**
     * Extend existing config or create new one
     *
     * @var string
     */
    private string $mode = 'create';

    /**
     * Use express setup mode
     *
     * @var bool
     */
    private bool $advanced = false;

    /**
     * Path to the currently executed 'binary'
     *
     * @var string
     */
    protected string $executable = '';

    /**
     * Execute the configurator
     *
     * @return void
     */
    public function run(): void
    {
        $config = $this->getConfigToManipulate();
        $setup  = $this->getHookSetup();

        $setup->configureHooks($config);
        Config\Util::writeToDisk($config);

        $this->io->write(
            [
                '<info>Configuration created successfully</info>',
                'Run <comment>\'' . $this->getExecutable() . ' install\'</comment> to activate your hook configuration',
            ]
        );
    }

    /**
     * Force mode setter
     *
     * @param  bool $force
     * @return \CaptainHook\App\Runner\Config\Creator
     */
    public function force(bool $force): Creator
    {
        $this->force = $force;
        return $this;
    }

    /**
     * Set configuration mode
     *
     * @param  bool $extend
     * @return \CaptainHook\App\Runner\Config\Creator
     */
    public function extend(bool $extend): Creator
    {
        $this->mode = $extend ? 'extend' : 'create';
        return $this;
    }

    /**
     * Set configuration speed
     *
     * @param  bool $advanced
     * @return \CaptainHook\App\Runner\Config\Creator
     */
    public function advanced(bool $advanced): Creator
    {
        $this->advanced = $advanced;
        return $this;
    }

    /**
     * Set the currently executed 'binary'
     *
     * @param  string $executable
     * @return \CaptainHook\App\Runner\Config\Creator
     */
    public function setExecutable(string $executable): Creator
    {
        $this->executable = $executable;
        return $this;
    }

    /**
     * Return config to handle
     *
     * @return \CaptainHook\App\Config
     */
    public function getConfigToManipulate(): Config
    {
        if (!$this->isExtending()) {
            // make sure the force option is set if the configuration file exists
            $this->ensureForce();
            // create a blank configuration to overwrite the old one
            return new Config($this->config->getPath());
        }
        return $this->config;
    }

    /**
     * Return the setup handler to ask the user questions
     *
     * @return \CaptainHook\App\Runner\Config\Setup
     */
    private function getHookSetup(): Setup
    {
        return $this->advanced
            ? new Setup\Advanced($this->io)
            : new Setup\Express($this->io);
    }

    /**
     * Should the config file be extended
     *
     * @return bool
     */
    private function isExtending(): bool
    {
        return 'extend' === $this->mode;
    }

    /**
     * Make sure force mode is set if config file exists
     *
     * @return void
     * @throws \RuntimeException
     */
    private function ensureForce(): void
    {
        if ($this->config->isLoadedFromFile() && !$this->force) {
            throw new RuntimeException('Configuration file exists, use -f to overwrite, or -e to extend');
        }
    }

    /**
     * Return path to currently executed 'binary'
     *
     * @return string
     */
    private function getExecutable(): string
    {
        return !empty($this->executable) ? $this->executable : 'vendor/bin/captainhook';
    }
}
