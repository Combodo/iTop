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
use SebastianFeldmann\Camino\Path;
use SebastianFeldmann\Camino\Path\Directory;

/**
 * Local class
 *
 * Generates the sourcecode for the php hook scripts in .git/hooks/*.
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 4.3.0
 */
class PHP extends Template\Local
{
    /**
     * Return the code for the git hook scripts
     *
     * @param  string $hook Name of the hook to generate the sourcecode for
     * @return array<string>
     */
    public function getHookLines(string $hook): array
    {
        return $this->pathInfo->isPhar() ? $this->getPharHookLines($hook) : $this->getSrcHookLines($hook);
    }

    /**
     * Returns lines of code for the local src installation
     *
     * @param  string $hook
     * @return array<string>
     */
    private function getSrcHookLines(string $hook): array
    {
        $configPath = $this->pathInfo->getConfigPath();
        $bootstrap  = $this->config->getBootstrap();
        $stdIn      = $this->getStdInHandling($hook);

        return array_merge(
            [
                '#!/usr/bin/env php',
                '<?php',
                '',
                '# installed by CaptainHook ' . CH::VERSION,
                '',
                'use CaptainHook\App\Console\Application as CaptainHook;',
                'use SebastianFeldmann\Cli\Reader\StandardInput;',
                'use Symfony\Component\Console\Input\ArgvInput;',
                '',
                '(static function($argv)',
                '{',
                '    $bootstrap = \'' . dirname($configPath) . '/' . $bootstrap . '\';',
                '    if (!file_exists($bootstrap)) {',
                '        fwrite(STDERR, \'Boostrap file \\\'\' . $bootstrap . \'\\\' could not be found\');',
                '        exit(1);',
                '    }',
                '    require $bootstrap;',
                '',
            ],
            $stdIn,
            [
                '    $argv = array_merge(',
                '        [',
                '            $argv[0],',
                '            \'hook:' . $hook . '\',',
                '            \'--configuration=' . $configPath . '\',',
                '            \'--git-directory=\' . dirname(__DIR__, 2) . \'/.git\',',
                '            \'--input=\' . trim($input) . \'\',',
                '        ],',
                '        array_slice($argv, 1)',
                '    );',
                '    $captainHook = new CaptainHook($argv[0]);',
                '    $captainHook->run(new ArgvInput($argv));',
                '}',
                ')($argv);',
            ]
        );
    }

    /**
     * Returns the lines of code for the local phar installation
     *
     * @param  string $hook
     * @return array<string>
     */
    private function getPharHookLines(string $hook): array
    {
        $configPath     = $this->pathInfo->getConfigPath();
        $executablePath = $this->pathInfo->getExecutablePath();
        $stdIn          = $this->getStdInHandling($hook);

        $executableInclude = substr($executablePath, 0, 1) == '/'
                           ? '\'' . $executablePath . '\''
                           : '__DIR__ . \'/../../' . $executablePath  . '\'';

        $bootstrapOption       = $this->getBootstrapCmdOption();
        $bootstrapOptionQuoted = empty($bootstrapOption) ? '' : '            \'' . $bootstrapOption . '\',';

        return array_merge(
            [
                '#!/usr/bin/env php',
                '<?php',
                '',
                '(static function($argv)',
                '{',
            ],
            $stdIn,
            [
                '    $argv = array_merge(',
                '        [',
                '            $argv[0],',
                '            \'hook:' . $hook . '\',',
                '            \'--configuration=' . $configPath . ',',
                '            \'--git-directory=\' . dirname(__DIR__, 2) . \'/.git\',',
                $bootstrapOptionQuoted,
                '            \'--input=\' . trim($input) . \'\',',
                '        ],',
                '        array_slice($argv, 1)',
                '    );',
                '    include ' . $executableInclude . ';',
                '}',
                ')($argv);',
            ]
        );
    }

    /**
     * Read data from stdIn or allow hooks to ask for user input
     *
     * @param  string $hook
     * @return string[]
     */
    private function getStdInHandling(string $hook): array
    {
        // if the hook supplies input via std in we have to read that data
        // then we can't support reading user input from the TTY anymore
        $useStdIn = [
            '    $stdIn = new StandardInput(STDIN);',
            '    $i     = [];',
            '    foreach ($stdIn as $line) {',
            '        $i[] = $line;',
            '    }',
            '    $input = implode(PHP_EOL, $i);',
            '',
        ];

        // if the hook does not receive data via stdIn ignore it and just use the tty
        // sp the user can be asked for some input
        $useTTY = [
            '    $input     = \'\';'
        ];

        return Hooks::receivesStdIn($hook) ? $useStdIn : $useTTY;
    }
}
