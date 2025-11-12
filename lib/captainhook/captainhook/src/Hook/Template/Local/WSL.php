<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptainHook\App\Hook\Template\Local;

/**
 * WSL class
 *
 * Generates the sourcecode for the php hook scripts in .git/hooks/*.
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @author  Christoph Kappestein <me@chrisk.app>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.23.0
 */
class WSL extends Shell
{
    protected function getExecutable(): string
    {
        return 'wsl.exe ' . parent::getExecutable();
    }
}
