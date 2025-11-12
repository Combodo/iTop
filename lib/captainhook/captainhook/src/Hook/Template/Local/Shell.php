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

use CaptainHook\App\CH;
use CaptainHook\App\Hook\Template;
use CaptainHook\App\Hooks;

/**
 * Shell class
 *
 * Generates the sourcecode for the php hook scripts in .git/hooks/*.
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.0.0
 */
class Shell extends Template\Local
{
    /**
     * Returns lines of code for the local src installation
     *
     * @param  string $hook
     * @return array<string>
     */
    protected function getHookLines(string $hook): array
    {
        return [
            '#!/bin/sh',
            '',
            '# installed by CaptainHook ' . CH::VERSION,
            '',
            'INTERACTIVE="--no-interaction"',
            '',
            '# if necessary read original hook stdIn to pass it in as --input option',
            Hooks::receivesStdIn($hook) ? 'input=$(cat)' : 'input=""',
            '',
            'if [ -t 1 ]; then',
            '    # If we\'re in a terminal, redirect stdout and stderr to /dev/tty and',
            '    # read stdin from /dev/tty. Allow interactive mode for CaptainHook.',
            '    exec >/dev/tty 2>/dev/tty </dev/tty',
            '    INTERACTIVE=""',
            'fi',
            '',
            $this->getExecutable()
                . ' $INTERACTIVE'
                . ' --configuration=' . $this->pathInfo->getConfigPath()
                . $this->getBootstrapCmdOption()
                . ' --input="$input"'
                . ' hook:' . $hook . ' "$@"'
        ];
    }

    /**
     * Returns the path to the executable including a configured php executable
     *
     * @return string
     */
    protected function getExecutable(): string
    {
        $executable = !empty($this->config->getPhpPath()) ? $this->config->getPhpPath() . ' ' : '';

        if (!empty($this->config->getRunConfig()->getCaptainsPath())) {
            return $executable . $this->config->getRunConfig()->getCaptainsPath();
        }

        return $executable . $this->pathInfo->getExecutablePath();
    }
}
