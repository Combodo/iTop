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

use CaptainHook\App\CH;
use CaptainHook\App\Config;
use CaptainHook\App\Hook\Util as HookUtil;
use CaptainHook\App\Storage\File\Json;
use RuntimeException;

/**
 * Class Factory
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 * @internal
 */
final class Factory
{
    /**
     * Maximal level in including config files
     *
     * @var int
     */
    private int $maxIncludeLevel = 1;

    /**
     * Current level of inclusion
     *
     * @var int
     */
    private int $includeLevel = 0;

    /**
     * Create a CaptainHook configuration
     *
     * @param  string               $path     Path to the configuration file
     * @param  array<string, mixed> $settings Settings passed as options on the command line
     * @return \CaptainHook\App\Config
     * @throws \Exception
     */
    public function createConfig(string $path = '', array $settings = []): Config
    {
        $path     = $path ?: getcwd() . DIRECTORY_SEPARATOR . CH::CONFIG;
        $file     = new Json($path);
        $settings = $this->combineArgumentsAndSettingFile($file, $settings);

        return $this->setupConfig($file, $settings);
    }

    /**
     * Read settings from a local 'config' file
     *
     * If you prefer a different verbosity or use a different run mode locally then your teammates do.
     * You can create a 'captainhook.config.json' in the same directory as your captainhook
     * configuration file and use it to overwrite the 'config' settings of that configuration file.
     * Exclude the 'captainhook.config.json' from version control, and you don't have to edit the
     * version controlled configuration for your local specifics anymore.
     *
     * Settings provided as arguments still overrule config file settings:
     *
     * ARGUMENTS > SETTINGS_FILE > CONFIGURATION
     *
     * @param  \CaptainHook\App\Storage\File\Json $file
     * @param  array<string, mixed>               $settings
     * @return array<string, mixed>
     */
    private function combineArgumentsAndSettingFile(Json $file, array $settings): array
    {
        $settingsFile = new Json(dirname($file->getPath()) . '/captainhook.config.json');
        if ($settingsFile->exists()) {
            $fileSettings = $settingsFile->readAssoc();
            $settings     = array_merge($fileSettings, $settings);
        }
        return $settings;
    }

    /**
     * Includes an external captainhook configuration
     *
     * @param  string $path
     * @return \CaptainHook\App\Config
     * @throws \Exception
     */
    private function includeConfig(string $path): Config
    {
        $file = new Json($path);
        if (!$file->exists()) {
            throw new RuntimeException('Config to include not found: ' . $path);
        }
        return $this->setupConfig($file);
    }

    /**
     * Return a configuration with data loaded from json file if it exists
     *
     * @param  \CaptainHook\App\Storage\File\Json $file
     * @param  array<string, mixed>               $settings
     * @return \CaptainHook\App\Config
     * @throws \Exception
     */
    private function setupConfig(Json $file, array $settings = []): Config
    {
        return $file->exists()
            ? $this->loadConfigFromFile($file, $settings)
            : new Config($file->getPath(), false, $settings);
    }

    /**
     * Loads a given file into given the configuration
     *
     * @param  \CaptainHook\App\Storage\File\Json $file
     * @param  array<string, mixed>               $settings
     * @return \CaptainHook\App\Config
     * @throws \Exception
     */
    private function loadConfigFromFile(Json $file, array $settings): Config
    {
        $json = $file->readAssoc();
        Util::validateJsonConfiguration($json);

        $settings = Util::mergeSettings($this->extractSettings($json), $settings);
        $config   = new Config($file->getPath(), true, $settings);
        if (!empty($settings)) {
            $json['config'] = $settings;
        }

        $this->appendIncludedConfigurations($config, $json);

        foreach (HookUtil::getValidHooks() as $hook => $class) {
            if (isset($json[$hook])) {
                $this->configureHook($config->getHookConfig($hook), $json[$hook]);
            }
        }

        $this->validatePhpPath($config);
        return $config;
    }

    /**
     * Return the `config` section of some json
     *
     * @param  array<string, mixed> $json
     * @return array<string, mixed>
     */
    private function extractSettings(array $json): array
    {
        return Util::extractListFromJson($json, 'config');
    }

    /**
     * Returns the `conditions` section of an actionJson
     *
     * @param array<string, mixed> $json
     * @return array<string, mixed>
     */
    private function extractConditions(mixed $json): array
    {
        return Util::extractListFromJson($json, 'conditions');
    }

    /**
     * Returns the `options` section af some json
     *
     * @param array<string, mixed> $json
     * @return array<string, string>
     */
    private function extractOptions(mixed $json): array
    {
        return Util::extractListFromJson($json, 'options');
    }

    /**
     * Set up a hook configuration by json data
     *
     * @param  \CaptainHook\App\Config\Hook $config
     * @param  array<string, mixed>         $json
     * @return void
     * @throws \Exception
     */
    private function configureHook(Config\Hook $config, array $json): void
    {
        $config->setEnabled($json['enabled'] ?? true);
        foreach ($json['actions'] as $actionJson) {
            $options    = $this->extractOptions($actionJson);
            $conditions = $this->extractConditions($actionJson);
            $settings   = $this->extractSettings($actionJson);
            $config->addAction(new Config\Action($actionJson['action'], $options, $conditions, $settings));
        }
    }

    /**
     * Makes sure the configured PHP executable exists
     *
     * @param  \CaptainHook\App\Config $config
     * @return void
     */
    private function validatePhpPath(Config $config): void
    {
        if (empty($config->getPhpPath())) {
            return;
        }
        $pathToCheck = [$config->getPhpPath()];
        $parts       = explode(' ', $config->getPhpPath());
        // if there are spaces in the php-path and they are not escaped
        // it looks like an executable is used to find the PHP binary
        // so at least check if the executable exists
        if ($this->usesPathResolver($parts)) {
            $pathToCheck[] = $parts[0];
        }

        foreach ($pathToCheck as $path) {
            if (file_exists($path)) {
                return;
            }
        }
        throw new RuntimeException('The configured php-path is wrong: ' . $config->getPhpPath());
    }

    /**
     * Is a binary used to resolve the php path
     *
     * @param array<int, string> $parts
     * @return bool
     */
    private function usesPathResolver(array $parts): bool
    {
        return count($parts) > 1 && !str_ends_with($parts[0], '\\');
    }

    /**
     * Append all included configuration to the current configuration
     *
     * @param  \CaptainHook\App\Config $config
     * @param  array<string, mixed>    $json
     * @throws \Exception
     */
    private function appendIncludedConfigurations(Config $config, array $json): void
    {
        $this->readMaxIncludeLevel($json);

        if ($this->includeLevel < $this->maxIncludeLevel) {
            $this->includeLevel++;
            $includes = $this->loadIncludedConfigs($json, $config->getPath());
            foreach (HookUtil::getValidHooks() as $hook => $class) {
                $this->mergeHookConfigFromIncludes($config->getHookConfig($hook), $includes);
            }
            $this->includeLevel--;
        }
    }

    /**
     * Check config section for 'includes-level' setting
     *
     * @param array<string, mixed> $json
     */
    private function readMaxIncludeLevel(array $json): void
    {
        // read the include-level setting only for the actual configuration
        if ($this->includeLevel === 0 && isset($json['config'][Config::SETTING_INCLUDES_LEVEL])) {
            $this->maxIncludeLevel = (int) $json['config'][Config::SETTING_INCLUDES_LEVEL];
        }
    }

    /**
     * Merge a given hook config with the corresponding hook configs from a list of included configurations
     *
     * @param  \CaptainHook\App\Config\Hook $hook
     * @param  \CaptainHook\App\Config[]    $includes
     * @return void
     */
    private function mergeHookConfigFromIncludes(Hook $hook, array $includes): void
    {
        foreach ($includes as $includedConfig) {
            $includedHook = $includedConfig->getHookConfig($hook->getName());
            if ($includedHook->isEnabled()) {
                $hook->setEnabled(true);
                // This `setEnable` is solely to overwrite the main configuration in the special case that the hook
                // is not configured at all. In this case the empty config is disabled by default, and adding an
                // empty hook config just to enable the included actions feels a bit dull.
                // Since the main hook is processed last (if one is configured) the enabled flag will be overwritten
                // once again by the main config value. This is to make sure that if somebody disables a hook in its
                // main configuration, no actions will get executed, even if we have enabled hooks in any include file.
                $this->copyActionsFromTo($includedHook, $hook);
            }
        }
    }

    /**
     * Return list of included configurations to add them to the main configuration afterwards
     *
     * @param  array<string, mixed> $json
     * @param  string               $path
     * @return \CaptainHook\App\Config[]
     * @throws \Exception
     */
    protected function loadIncludedConfigs(array $json, string $path): array
    {
        $includes  = [];
        $directory = dirname($path);
        $files     = Util::extractListFromJson(Util::extractListFromJson($json, 'config'), Config::SETTING_INCLUDES);

        foreach ($files as $file) {
            $includes[] = $this->includeConfig($directory . DIRECTORY_SEPARATOR . $file);
        }
        return $includes;
    }

    /**
     * Copy action from a given configuration to the second given configuration
     *
     * @param \CaptainHook\App\Config\Hook $sourceConfig
     * @param \CaptainHook\App\Config\Hook $targetConfig
     */
    private function copyActionsFromTo(Hook $sourceConfig, Hook $targetConfig): void
    {
        foreach ($sourceConfig->getActions() as $action) {
            $action->markIncluded();
            $targetConfig->addAction($action);
        }
    }

    /**
     * Config factory method
     *
     * @param  string               $path
     * @param  array<string, mixed> $settings
     * @return \CaptainHook\App\Config
     * @throws \Exception
     */
    public static function create(string $path = '', array $settings = []): Config
    {
        $factory = new static();
        return $factory->createConfig($path, $settings);
    }
}
