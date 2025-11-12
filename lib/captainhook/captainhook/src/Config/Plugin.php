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

use CaptainHook\App\Exception\InvalidPlugin;
use CaptainHook\App\Plugin\CaptainHook;

/**
 * Class Plugin
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.9.0
 */
class Plugin
{
    /**
     * Plugin class
     *
     * @var string
     */
    private string $plugin;

    /**
     * Map of options name => value
     *
     * @var Options
     */
    private Options $options;

    /**
     * Plugin constructor
     *
     * @param string               $plugin
     * @param array<string, mixed> $options
     */
    public function __construct(string $plugin, array $options = [])
    {
        if (!is_a($plugin, CaptainHook::class, true)) {
            throw new InvalidPlugin("{$plugin} is not a valid CaptainHook plugin.");
        }

        $this->plugin = $plugin;
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
     * Plugin class name getter
     *
     * @return string
     */
    public function getPlugin(): string
    {
        return $this->plugin;
    }

    /**
     * Return option map
     *
     * @return Options
     */
    public function getOptions(): Options
    {
        return $this->options;
    }

    /**
     * Return config data
     *
     * @return array<string, mixed>
     */
    public function getJsonData(): array
    {
        return [
            'plugin'  => $this->plugin,
            'options' => $this->options->getAll(),
        ];
    }
}
