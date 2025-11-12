<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\Condition;

use CaptainHook\App\Config;

/**
 * Interface Conditions
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.17.2
 */
interface ConfigDependant
{
    /**
     * Evaluates a condition
     *
     * This will be deprecated in version 6.0.0
     * In version 6.0.0 the condition interface should change to include the Config
     *
     * @param  \CaptainHook\App\Config $config
     * @return void
     */
    public function setConfig(Config $config): void;
}
