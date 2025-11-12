<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Runner\Config\Change;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Runner\Config\Change;

/**
 * Class AddAction
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 4.2.0
 */
abstract class Hook implements Change
{
    /**
     * @var \CaptainHook\App\Console\IO
     */
    protected $io;

    /**
     * Name of the hook to add the action to
     *
     * @var string
     */
    protected $hookToChange;

    /**
     * AddAction constructor
     *
     * @param \CaptainHook\App\Console\IO $io
     * @param string                      $hookToChange
     */
    public function __construct(IO $io, string $hookToChange)
    {
        $this->io           = $io;
        $this->hookToChange = $hookToChange;
    }

    /**
     * Apply changes to the given config
     *
     * @param  \CaptainHook\App\Config $config
     * @return void
     * @throws \Exception
     */
    abstract public function applyTo(Config $config): void;
}
