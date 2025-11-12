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

use CaptainHook\App\Config as AppConfig;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Condition;
use RuntimeException;
use SebastianFeldmann\Git\Repository;

/**
 * Class FileChange
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 4.2.0
 */
abstract class Config implements ConfigDependant, Condition
{
    /**
     * @var \CaptainHook\App\Config|null
     */
    protected ?AppConfig $config = null;

    /**
     * Config setter
     *
     * @param  \CaptainHook\App\Config $config
     * @return void
     */
    public function setConfig(AppConfig $config): void
    {
        $this->config = $config;
    }

    /**
     * Check if the customer value exists and return izs boolish value
     *
     * @param  string $value
     * @param  bool   $default
     * @return bool
     */
    protected function checkCustomValue(string $value, bool $default): bool
    {
        if (null === $this->config) {
            throw new RuntimeException('config not set');
        }
        $customSettings = $this->config->getCustomSettings();
        $valueToCheck   = $customSettings[$value] ?? $default;
        return filter_var($valueToCheck, FILTER_VALIDATE_BOOL);
    }

    /**
     * Evaluates a condition
     *
     * @param  \CaptainHook\App\Console\IO       $io
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @return bool
     */
    abstract public function isTrue(IO $io, Repository $repository): bool;
}
