<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook;

/**
 * Template interface
 *
 * Templates generate the hook sourcecode to place in .git/hooks/* to execute CaptainHook.
 * There are 3 types of templates:
 *  - SHELL  Writes a shell script, this is the recommended way for all unix or linux based systems.
 *  - PHP    Writes a PHP script, this is useful if you are running windows and shell scripts aren't an option.
 *  - DOCKER Writes a shell script that executes captainhook inside a docker container. This is useful if you
 *           don't want to install PHP locally.
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 4.3.0
 */
interface Template
{
    public const SHELL  = 'shell';
    public const PHP    = 'php';
    public const DOCKER = 'docker';
    public const WSL    = 'wsl';

    /**
     * Return the code for the git hook scripts
     *
     * @param  string $hook Name of the hook to generate the sourcecode for
     * @return string
     */
    public function getCode(string $hook): string;
}
