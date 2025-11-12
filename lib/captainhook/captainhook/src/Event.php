<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App;

use CaptainHook\App\Console\IO;
use SebastianFeldmann\Git\Repository;

/**
 * Event interface
 *
 * Allows event handlers to do output access the app setup or the repository.
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.11.0
 */
interface Event
{
    /**
     * Returns the event trigger name
     *
     * @return string
     */
    public function name(): string;

    /**
     * Returns the captainhook config, most likely needed to access any original command line arguments
     *
     * @return \CaptainHook\App\Config
     */
    public function config(): Config;

    /**
     * Returns IO to do some output
     *
     * @return \CaptainHook\App\Console\IO
     */
    public function io(): IO;

    /**
     * Returns the git repository
     *
     * @return \SebastianFeldmann\Git\Repository
     */
    public function repository(): Repository;
}
