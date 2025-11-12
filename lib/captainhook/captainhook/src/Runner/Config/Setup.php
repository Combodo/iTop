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

/**
 * Interface Setup
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 2.1.3
 */
interface Setup
{
    /**
     * Setup hook configurations by asking some questions
     *
     * @param  \CaptainHook\App\Config $config
     * @return void
     */
    public function configureHooks(Config $config): void;
}
