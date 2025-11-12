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

use CaptainHook\App\Config;

/**
 * Class Action
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
class Action
{
    /**
     * Action php class, php static method, or cli script
     *
     * @var string
     */
    private string $action;

    /**
     * Map of options name => value
     *
     * @var \CaptainHook\App\Config\Options
     */
    private Options $options;

    /**
     * List of action conditions
     *
     * @var \CaptainHook\App\Config\Condition[]
     */
    private array $conditions = [];

    /**
     * Action settings
     *
     * @var array<string, mixed>
     */
    private array $settings = [];

    /**
     * List of available settings
     *
     * @var string[]
     */
    private static array $availableSettings = [
        Config::SETTING_ALLOW_FAILURE,
        Config::SETTING_LABEL
    ];

    /**
     * Indicates if an action config was included from another file
     *
     * @var bool
     */
    private bool $isIncluded = false;

    /**
     * Action constructor
     *
     * @param string               $action
     * @param array<string, mixed> $options
     * @param array<string, mixed> $conditions
     * @param array<string, mixed> $settings
     */
    public function __construct(string $action, array $options = [], array $conditions = [], array $settings = [])
    {
        $this->action = $action;
        $this->setupOptions($options);
        $this->setupConditions($conditions);
        $this->setupSettings($settings);
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
     * Setup action conditions
     *
     * @param array<string, array<string, mixed>> $conditions
     */
    private function setupConditions(array $conditions): void
    {
        foreach ($conditions as $condition) {
            $this->conditions[] = new Condition($condition['exec'], $condition['args'] ?? []);
        }
    }

    /**
     * Setting up the action settings
     *
     * @param array<string, mixed> $settings
     * @return void
     */
    private function setupSettings(array $settings): void
    {
        foreach (self::$availableSettings as $setting) {
            if (isset($settings[$setting])) {
                $this->settings[$setting] = $settings[$setting];
            }
        }
    }

    /**
     * Marks a action config as included
     *
     * @return void
     */
    public function markIncluded(): void
    {
        $this->isIncluded = true;
    }

    /**
     * Check if an action config was included
     *
     * @return bool
     */
    public function isIncluded(): bool
    {
        return $this->isIncluded;
    }

    /**
     * Indicates if the action can fail without stopping the git operation
     *
     * @param  bool $default
     * @return bool
     */
    public function isFailureAllowed(bool $default = false): bool
    {
        return (bool) ($this->settings[Config::SETTING_ALLOW_FAILURE] ?? $default);
    }

    /**
     * Return the label or the action if no label is set
     *
     * @return string
     */
    public function getLabel(): string
    {
        return (string) ($this->settings[Config::SETTING_LABEL] ?? $this->getAction());
    }

    /**
     * Action getter
     *
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Return option map
     *
     * @return \CaptainHook\App\Config\Options
     */
    public function getOptions(): Options
    {
        return $this->options;
    }

    /**
     * Return condition configurations
     *
     * @return \CaptainHook\App\Config\Condition[]
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * Return config data
     *
     * @return array<string, mixed>
     */
    public function getJsonData(): array
    {
        $data = [
            'action'  => $this->action
        ];

        $options = $this->options->getAll();
        if (!empty($options)) {
            $data['options'] = $options;
        }

        $conditions = $this->getConditionJsonData();
        if (!empty($conditions)) {
            $data['conditions'] = $conditions;
        }

        if (!empty($this->settings)) {
            $data['config'] = $this->settings;
        }

        return $data;
    }

    /**
     * Return conditions json data
     *
     * @return array<int, mixed>
     */
    private function getConditionJsonData(): array
    {
        $json = [];
        foreach ($this->conditions as $condition) {
            $json[] = $condition->getJsonData();
        }
        return $json;
    }
}
