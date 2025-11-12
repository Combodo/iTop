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
 * Class Hook
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 * @internal
 */
class Hook
{
    /**
     * Hook name e.g. pre-commit
     *
     * @var string
     */
    private string $name;

    /**
     * Is hook enabled
     *
     * @var bool
     */
    private bool $isEnabled;

    /**
     * List of Actions
     *
     * @var \CaptainHook\App\Config\Action[]
     */
    private $actions = [];

    /**
     * Hook constructor
     *
     * @param string $name
     * @param bool   $enabled
     */
    public function __construct(string $name, bool $enabled = false)
    {
        $this->name      = $name;
        $this->isEnabled = $enabled;
    }

    /**
     * Name getter
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Enable or disable the hook
     *
     * @param  bool $enabled
     * @return void
     */
    public function setEnabled(bool $enabled): void
    {
        $this->isEnabled = $enabled;
    }

    /**
     * Is this hook enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    /**
     * Check if a hook config has actions
     *
     * @return bool
     */
    public function hasActions(): bool
    {
        return !empty($this->actions);
    }

    /**
     * Add an action to the list
     *
     * @param \CaptainHook\App\Config\Action ...$actions
     * @return void
     */
    public function addAction(Action ...$actions): void
    {
        foreach ($actions as $action) {
            $this->actions[] = $action;
        }
    }

    /**
     * Return the action list
     *
     * @return \CaptainHook\App\Config\Action[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * Return config data
     *
     * @return array<string, mixed>
     */
    public function getJsonData(): array
    {
        $config = ['enabled' => $this->isEnabled, 'actions' => []];
        foreach ($this->actions as $action) {
            if (!$action->isIncluded()) {
                $config['actions'][] = $action->getJsonData();
            }
        }
        return $config;
    }
}
