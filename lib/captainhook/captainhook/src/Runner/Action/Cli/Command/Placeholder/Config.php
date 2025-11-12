<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Runner\Action\Cli\Command\Placeholder;

/**
 * Class Config
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.6.0
 */
class Config extends Foundation
{
    /**
     * Maps the config value names to actual methods that have to be called to retrieve the value
     *
     * @var array<string, string>
     */
    private $valueToMethod = [
        'bootstrap'           => 'getBootstrap',
        'git-directory'       => 'getGitDirectory',
        'php-path'            => 'getPhpPath',
    ];

    /**
     * @param  array<string, mixed> $options
     * @return string
     */
    public function replacement(array $options): string
    {
        if (!isset($options['value-of'])) {
            return '';
        }

        return $this->getConfigValueFor($options['value-of']);
    }

    /**
     * Returns the config value '' by default if value is unknown
     *
     * @param  string $value
     * @return string
     */
    private function getConfigValueFor(string $value): string
    {
        if (strpos($value, 'custom>>') === 0) {
            $key    = substr($value, 8);
            $custom = $this->config->getCustomSettings();
            return $custom[$key] ?? '';
        }
        if (!isset($this->valueToMethod[$value])) {
            return '';
        }

        $method = $this->valueToMethod[$value];
        return $this->config->$method();
    }
}
