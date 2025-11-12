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

namespace CaptainHook\App\Hook\Template;

use CaptainHook\App\CH;
use CaptainHook\App\Config;
use CaptainHook\App\Hook\Template;
use CaptainHook\App\Hooks;
use CaptainHook\App\Runner\Bootstrap\Util;

/**
 * Docker class
 *
 * Generates the bash scripts placed in .git/hooks/* for every hook
 * to execute CaptainHook inside a Docker container.
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 4.3.0
 */
class Docker implements Template
{
    /**
     * All path required for template creation
     *
     * @var \CaptainHook\App\Hook\Template\PathInfo
     */
    private PathInfo $pathInfo;

    /**
     * CaptainHook configuration
     *
     * @var \CaptainHook\App\Config
     */
    private Config $config;

    /**
     * Docker constructor
     *
     * @param \CaptainHook\App\Hook\Template\PathInfo $pathInfo
     * @param \CaptainHook\App\Config                 $config
     */
    public function __construct(PathInfo $pathInfo, Config $config)
    {
        $this->pathInfo = $pathInfo;
        $this->config   = $config;
    }

    /**
     * Return the code for the git hook scripts
     *
     * @param  string $hook Name of the hook to generate the sourcecode for
     * @return string
     */
    public function getCode(string $hook): string
    {
        $path2Config = $this->pathInfo->getConfigPath();
        $config      = $path2Config !== CH::CONFIG ? ' --configuration=' . escapeshellarg($path2Config) : '';
        $bootstrap   = Util::bootstrapCmdOption($this->pathInfo->isPhar(), $this->config);

        $lines = [
            '#!/bin/sh',
            '',
            '# installed by CaptainHook ' . CH::VERSION,
            '',
            '# if necessary read original hook stdIn to pass it in as --input option',
            Hooks::receivesStdIn($hook) ? 'input=$(cat)' : 'input=""',
            '',
            'if sh -c ": >/dev/tty" >/dev/null 2>/dev/null; then',
            '    exec < /dev/tty',
            'fi',
            '',
            $this->getOptimizeDockerCommand($hook) . ' '
            . $this->resolveBinaryPath()
            . $config
            . $bootstrap
            . ' --input=\""$input"\"'
            . ' hook:' . $hook
            . ' "$@"'
        ];
        return implode(PHP_EOL, $lines) . PHP_EOL;
    }


    /**
     * Returns the optimized docker exec command
     *
     * This tries to optimize the `docker exec` commands. Docker exec should always run in --interactive mode.
     * During hooks that could need user input it should use --tty.
     * In case of `commit -a` we have to pass the GIT_INDEX_FILE env variable so `git` inside the container
     * can recognize the temp index.
     *
     * @param  string $hook
     * @return string
     */
    private function getOptimizeDockerCommand(string $hook): string
    {
        $command  = $this->config->getRunConfig()->getDockerCommand();
        $position = strpos($command, 'docker exec');
        // add interactive and tty flags if docker exec is used
        if ($position !== false) {
            $endExec    = $position + 11;
            $executable = substr($command, 0, $endExec);
            $options    = substr($command, $endExec);

            $command     = trim($executable)
                         . $this->createInteractiveOptions($options)
                         . $this->createTTYOptions($options)
                         . $this->createEnvOptions($options, $hook)
                         . ' ' . trim($options);
        }
        return $command;
    }

    /**
     * Creates the TTY options if needed
     *
     * @param  string $options
     * @return string
     */
    private function createTTYOptions(string $options): string
    {
        // Because this currently breaks working with Jetbrains IDEs it is deactivated for now
        // $tty        = Hooks::allowsUserInput($hook) ? ' -t' : '';
        // $useTTY      = !preg_match('# -[a-z]*t| --tty#', $options) ? $tty : '';
        return '';
    }

    /**
     * Create the env settings if needed
     *
     * Only force the -e option for pre-commit hooks because with `commit -a` needs
     * the GIT_INDEX_FILE environment variable.
     *
     * @param  string $options
     * @param  string $hook
     * @return string
     */
    private function createEnvOptions(string $options, string $hook): string
    {
        return (
            $this->hasGitPathMappingConfigured()
            && $this->dockerHasNoEnvSettings($options)
            && in_array($hook, [Hooks::PRE_COMMIT, Hooks::PREPARE_COMMIT_MSG])
        )
            ? ' -e GIT_INDEX_FILE="' . $this->config->getRunConfig()->getGitPath() . '/$(basename $GIT_INDEX_FILE)"'
            : '';
    }

    /**
     * Checks if the ENV settings are present
     *
     * @param string $options
     * @return bool
     */
    private function dockerHasNoEnvSettings(string $options): bool
    {
        return !preg_match('# (-[a-z]*e|--env)[= ]+GIT_INDEX_FILE#', $options);
    }

    /**
     * Creates the interactive option if needed, returns ' -i' or ''
     *
     * @param  string $options
     * @return string
     */
    private function createInteractiveOptions(string $options): string
    {
        return $this->dockerIsNotInteractive($options) ? ' -i' : '';
    }

    /**
     * Checks if the interactive flag is set
     *
     * @param  string $options
     * @return bool
     */
    private function dockerIsNotInteractive(string $options): bool
    {
        return !preg_match('# -[a-z]*i| --interactive#', $options);
    }

    /**
     * Check if a git path is configured
     *
     * @return bool
     */
    private function hasGitPathMappingConfigured(): bool
    {
        return !empty($this->config->getRunConfig()->getGitPath());
    }

    /**
     * Resolves the path to the captainhook binary and returns it
     *
     * @return string
     */
    private function resolveBinaryPath(): string
    {
        // if a specific executable is configured use just that
        if (!empty($this->config->getRunConfig()->getCaptainsPath())) {
            return $this->config->getRunConfig()->getCaptainsPath();
        }

        // For Docker, we need to strip down the current working directory.
        // This is caused because docker will always connect to a specific working directory
        // where the absolute path will not be recognized.
        // E.g.:
        //   cwd    => /project/
        //   path   => /project/vendor/bin/captainhook
        //   docker => ./vendor/bin/captainhook
        // if the executable is located inside the repository we can use a relative path
        // by default this should return something like ./vendor/bin/captainhook
        // if the executable is not located in your git repository it will return the absolute path
        // which will most likely not work from within the docker container
        // you have to use the 'run' 'path' config then
        return $this->pathInfo->getExecutablePath();
    }
}
