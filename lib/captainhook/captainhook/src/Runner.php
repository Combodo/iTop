<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App;

use CaptainHook\App\Console\IO;

/**
 * Class Runner
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
abstract class Runner
{
    /**
     * @var \CaptainHook\App\Console\IO
     */
    protected $io;

    /**
     * @var \CaptainHook\App\Config
     */
    protected $config;

    /**
     * Installer constructor.
     *
     * @param \CaptainHook\App\Console\IO $io
     * @param \CaptainHook\App\Config     $config
     */
    public function __construct(IO $io, Config $config)
    {
        $this->io     = $io;
        $this->config = $config;
    }

    /**
     * Executes the Runner.
     */
    abstract public function run(): void;
}
