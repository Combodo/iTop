<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Plugin\Hook;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Plugin;
use SebastianFeldmann\Git\Repository;

/**
 * Base runner plugin abstract class
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.9.0.
 */
abstract class Base implements Plugin\Hook
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var IO
     */
    protected $io;

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var Config\Plugin
     */
    protected $plugin;

    public function configure(Config $config, IO $io, Repository $repository, Config\Plugin $plugin): void
    {
        $this->config = $config;
        $this->io = $io;
        $this->repository = $repository;
        $this->plugin = $plugin;
    }
}
