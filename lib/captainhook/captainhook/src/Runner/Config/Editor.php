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
use CaptainHook\App\Exception;
use CaptainHook\App\Hook\Util;
use CaptainHook\App\Runner;
use RuntimeException;

/**
 * Class Editor
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 4.2.0
 */
class Editor extends Runner
{
    /**
     * @var string
     */
    private $hookToEdit;

    /**
     * The name of the change that will be applied to the configuration
     *
     * @var string
     */
    private $change;

    /**
     * Set the hook that should be changed
     *
     * @param  string $hook
     * @return \CaptainHook\App\Runner\Config\Editor
     * @throws \CaptainHook\App\Exception\InvalidHookName
     */
    public function setHook(string $hook): Editor
    {
        if (!Util::isValid($hook)) {
            throw new Exception\InvalidHookName('Invalid hook name \'' . $hook . '\'');
        }
        $this->hookToEdit = $hook;
        return $this;
    }

    /**
     * Set the name of the change that should be performed on the configuration
     *
     * @param  string $change
     * @return \CaptainHook\App\Runner\Config\Editor
     */
    public function setChange(string $change): Editor
    {
        $this->change = $change;
        return $this;
    }

    /**
     * Executes the Runner
     *
     * @return void
     * @throws \RuntimeException
     */
    public function run(): void
    {
        if (!$this->config->isLoadedFromFile()) {
            throw new RuntimeException('No configuration to edit');
        }

        $this->checkHook();
        $this->checkChange();

        $change = $this->createChange();
        $change->applyTo($this->config);
        Config\Util::writeToDisk($this->config);

        $this->io->write('Configuration successfully updated');
    }

    /**
     * Create a config edit command
     *
     * @return \CaptainHook\App\Runner\Config\Change
     * @throws \RuntimeException
     */
    private function createChange(): Change
    {
        /** @var class-string<\CaptainHook\App\Runner\Config\Change> $changeClass */
        $changeClass = '\\CaptainHook\\App\\Runner\\Config\\Change\\' . $this->change;
        if (!class_exists($changeClass)) {
            throw new RuntimeException('Invalid change requested');
        }

        return new $changeClass($this->io, $this->hookToEdit);
    }

    /**
     * Makes sure a hook is selected
     *
     * @return void
     * @throws \RuntimeException
     */
    private function checkHook(): void
    {
        if (empty($this->hookToEdit)) {
            throw new RuntimeException('No hook set');
        }
    }

    /**
     * Makes sure a command is set
     *
     * @return void
     * @throws \RuntimeException
     */
    private function checkChange(): void
    {
        if (empty($this->change)) {
            throw new RuntimeException('No change set');
        }
    }
}
