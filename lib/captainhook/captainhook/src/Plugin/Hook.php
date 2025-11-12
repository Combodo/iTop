<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Plugin;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Runner\Hook as RunnerHook;
use SebastianFeldmann\Git\Repository;

/**
 * Runner plugin interface
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.9.0.
 */
interface Hook extends CaptainHook
{
    /**
     * Configure the runner plugin.
     *
     * @param Config $config
     * @param IO $io
     * @param Repository $repository
     * @param Config\Plugin $plugin
     * @return void
     */
    public function configure(Config $config, IO $io, Repository $repository, Config\Plugin $plugin): void;

    /**
     * Execute before all actions
     *
     * @param RunnerHook $hook
     * @return void
     */
    public function beforeHook(RunnerHook $hook): void;

    /**
     * Execute before each action
     *
     * @param RunnerHook $hook
     * @param Config\Action $action
     * @return void
     */
    public function beforeAction(RunnerHook $hook, Config\Action $action): void;

    /**
     * Execute after each action
     *
     * @param RunnerHook $hook
     * @param Config\Action $action
     * @return void
     */
    public function afterAction(RunnerHook $hook, Config\Action $action): void;

    /**
     * Execute after all actions
     *
     * @param RunnerHook $hook
     * @return void
     */
    public function afterHook(RunnerHook $hook): void;
}
