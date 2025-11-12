<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Config;

/**
 * Run Config
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.18.0
 */
class Run
{
    private const MODE = 'mode';
    private const PATH = 'path';
    private const EXEC = 'exec';
    private const GIT = 'git';

    /**
     * Map of options name => value
     *
     * @var \CaptainHook\App\Config\Options
     */
    private Options $options;

    /**
     * Run constructor
     *
     * @param array<string, mixed> $options
     */
    public function __construct(array $options = [])
    {
        $this->setupOptions($options);
    }

    /**
     * Setup options
     *
     * @param array<string, mixed> $options
     */
    private function setupOptions(array $options): void
    {
        $this->options = new Options($options);
    }

    /**
     * Return the run mode shell|docker|php|local|wsl
     *
     * @return string
     */
    public function getMode(): string
    {
        return $this->options->get(self::MODE, 'shell');
    }

    /**
     * Return the path to the captain from within the container or to overwrite symlink resolution
     *
     * Since realpath() returns the real absolute path and not the absolute symlink path this
     * setting could be used to overwrite this behaviour.
     *
     * @return string
     */
    public function getCaptainsPath(): string
    {
        return $this->options->get(self::PATH, '');
    }

    /**
     * Return the docker command to use to execute the captain
     *
     * @return string
     */
    public function getDockerCommand(): string
    {
        return $this->options->get(self::EXEC, '');
    }

    /**
     * Return the path mapping setting
     *
     * @return string
     */
    public function getGitPath(): string
    {
        return $this->options->get(self::GIT, '');
    }

    /**
     * Return config data
     *
     * @return array<string, mixed>
     */
    public function getJsonData(): array
    {
        return $this->options->getAll();
    }
}
