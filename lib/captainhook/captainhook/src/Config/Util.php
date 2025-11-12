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

use CaptainHook\App\Hook\Util as HookUtil;
use CaptainHook\App\Config;
use CaptainHook\App\Storage\File\Json;
use RuntimeException;

/**
 * Class Util
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 1.0.3
 * @internal
 */
abstract class Util
{
    /**
     * Validate a configuration
     *
     * @param  array<string, mixed> $json
     * @return void
     * @throws \RuntimeException
     */
    public static function validateJsonConfiguration(array $json): void
    {
        self::validatePluginConfig($json);

        foreach (HookUtil::getValidHooks() as $hook => $class) {
            if (isset($json[$hook])) {
                self::validateHookConfig($json[$hook]);
            }
        }
    }

    /**
     * Validate a hook configuration
     *
     * @param  array<string, mixed> $json
     * @return void
     * @throws \RuntimeException
     */
    public static function validateHookConfig(array $json): void
    {
        if (!self::keysExist(['enabled', 'actions'], $json)) {
            throw new RuntimeException('Config error: invalid hook configuration');
        }
        if (!is_array($json['actions'])) {
            throw new RuntimeException('Config error: \'actions\' must be an array');
        }
        self::validateActionsConfig($json['actions']);
    }

    /**
     * Validate a plugin configuration
     *
     * @param  array<string, mixed> $json
     * @return void
     * @throws \RuntimeException
     */
    public static function validatePluginConfig(array $json): void
    {
        if (!isset($json['config']['plugins'])) {
            return;
        }
        if (!is_array($json['config']['plugins'])) {
            throw new RuntimeException('Config error: \'plugins\' must be an array');
        }
        foreach ($json['config']['plugins'] as $plugin) {
            if (!self::keysExist(['plugin'], $plugin)) {
                throw new RuntimeException('Config error: \'plugin\' missing');
            }
            if (empty($plugin['plugin'])) {
                throw new RuntimeException('Config error: \'plugin\' can\'t be empty');
            }
        }
    }

    /**
     * Validate a list of action configurations
     *
     * @param  array<string, mixed> $json
     * @return void
     * @throws \RuntimeException
     */
    public static function validateActionsConfig(array $json): void
    {
        foreach ($json as $action) {
            if (!self::keysExist(['action'], $action)) {
                throw new RuntimeException('Config error: \'action\' missing');
            }
            if (empty($action['action'])) {
                throw new RuntimeException('Config error: \'action\' can\'t be empty');
            }
            if (!empty($action['conditions'])) {
                self::validateConditionsConfig($action['conditions']);
            }
        }
    }

    /**
     * Validate a list of condition configurations
     *
     * @param  array<int, array<string, mixed>> $json
     * @throws \RuntimeException
     */
    public static function validateConditionsConfig(array $json): void
    {
        foreach ($json as $condition) {
            if (!self::keysExist(['exec'], $condition) || empty($condition['exec'])) {
                throw new RuntimeException('Config error: \'exec\' is required for conditions');
            }
            if (!empty($condition['args']) && !is_array($condition['args'])) {
                throw new RuntimeException('Config error: invalid \'args\' configuration');
            }
        }
    }

    /**
     * Extracts a list from a json data struct with the necessary safeguards
     *
     * @param  array<string, mixed>  $json
     * @param  string                $value
     * @return array<string, mixed>
     */
    public static function extractListFromJson(array $json, string $value): array
    {
        return isset($json[$value]) && is_array($json[$value]) ? $json[$value] : [];
    }

    /**
     * Write the config to disk
     *
     * @param  \CaptainHook\App\Config $config
     * @return void
     */
    public static function writeToDisk(Config $config): void
    {
        $filePath = $config->getPath();
        $file     = new Json($filePath);
        $file->write($config->getJsonData());
    }

    /**
     * Merges a various list of settings arrays
     *
     * @param  array<string, mixed> $settings
     * @return array<string, mixed>
     */
    public static function mergeSettings(array ...$settings): array
    {
        $includes       = array_column($settings, Config::SETTING_INCLUDES);
        $custom         = array_column($settings, Config::SETTING_CUSTOM);
        $mergedSettings = array_merge(...$settings);
        if (!empty($includes)) {
            $mergedSettings[Config::SETTING_INCLUDES] = array_merge(...$includes);
        }
        if (!empty($custom)) {
            $mergedSettings[Config::SETTING_CUSTOM] = array_merge(...$custom);
        }

        return $mergedSettings;
    }

    /**
     * Does an array have the expected keys
     *
     * @param  array<string>        $keys
     * @param  array<string, mixed> $subject
     * @return bool
     */
    private static function keysExist(array $keys, array $subject): bool
    {
        foreach ($keys as $key) {
            if (!isset($subject[$key])) {
                return false;
            }
        }
        return true;
    }
}
