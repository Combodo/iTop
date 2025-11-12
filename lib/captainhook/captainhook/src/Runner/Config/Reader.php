<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Runner\Config;

use CaptainHook\App\Config;
use CaptainHook\App\Hook\Util as HookUtil;
use CaptainHook\App\Runner;
use CaptainHook\App\Runner\Hook\Arg;
use RuntimeException;

/**
 * Class Info
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.24.0
 */
class Reader extends Runner\RepositoryAware
{
    /**
     * Option values
     */
    public const OPT_ACTIONS    = 'actions';
    public const OPT_CONDITIONS = 'conditions';
    public const OPT_OPTIONS    = 'options';

    /**
     * The hook to display
     *
     * @var array<int, string>
     */
    private array $hooks = [];

    /**
     * @var array<string, bool>
     */
    private array $options = [];

    /**
     * Show more detailed information
     * @var bool
     */
    private bool $extensive = false;

    /**
     * Limit uninstall to s specific hook
     *
     * @param  string $hook
     * @return static
     * @throws \CaptainHook\App\Exception\InvalidHookName
     */
    public function setHook(string $hook): self
    {
        $arg = new Arg(
            $hook,
            static function (string $hook): bool {
                return !HookUtil::isValid($hook);
            }
        );
        $this->hooks = $arg->hooks();
        return $this;
    }

    /**
     * Set the display setting for a config section (actions, conditions, options)
     *
     * @param  string $name
     * @param  bool   $value
     * @return $this
     */
    public function display(string $name, bool $value): Reader
    {
        if ($value) {
            $this->options[$name] = true;
        }
        return $this;
    }

    /**
     * Show more detailed information
     *
     * @param bool $value
     * @return $this
     */
    public function extensive(bool $value): Reader
    {
        $this->extensive = $value;
        return $this;
    }

    /**
     * Executes the Runner
     *
     * @return void
     * @throws \RuntimeException
     */
    public function run(): void
    {
        if (!$this->config->isLoadedFromFile()) {
            throw new RuntimeException('No configuration to read');
        }
        foreach ($this->config->getHookConfigs() as $hookConfig) {
            $this->displayHook($hookConfig);
        }
    }

    /**
     * Display a hook configuration
     * @param  \CaptainHook\App\Config\Hook $config
     * @return void
     */
    private function displayHook(Config\Hook $config): void
    {
        if ($this->shouldHookBeDisplayed($config->getName())) {
            $this->io->write('<info>' . $config->getName() . '</info>', !$this->extensive);
            $this->displayExtended($config);
            $this->displayActions($config);
        }
    }

    /**
     * Display detailed information
     *
     * @param \CaptainHook\App\Config\Hook $config
     * @return void
     */
    private function displayExtended(Config\Hook $config): void
    {
        if ($this->extensive) {
            $this->io->write(
                ' ' . str_repeat('-', 52 - strlen($config->getName())) .
                '--[enabled: ' . $this->yesOrNo($config->isEnabled()) .
                ', installed: ' . $this->yesOrNo($this->repository->hookExists($config->getName())) . ']'
            );
        }
    }

    /**
     * Display all actions
     *
     * @param \CaptainHook\App\Config\Hook $config
     * @return void
     */
    private function displayActions(Config\Hook $config): void
    {
        foreach ($config->getActions() as $action) {
            $this->displayAction($action);
        }
    }

    /**
     * Display a single Action
     *
     * @param \CaptainHook\App\Config\Action $action
     * @return void
     */
    private function displayAction(Config\Action $action): void
    {
        $this->io->write(' - <fg=cyan>' . $action->getAction() . '</>');
        $this->displayOptions($action->getOptions());
        $this->displayConditions($action->getConditions());
    }

    /**
     * Display all options
     *
     * @param \CaptainHook\App\Config\Options $options
     * @return void
     */
    private function displayOptions(Config\Options $options): void
    {
        if (empty($options->getAll())) {
            return;
        }
        if ($this->show(self::OPT_OPTIONS)) {
            $this->io->write('   <comment>Options:</comment>');
            foreach ($options->getAll() as $key => $value) {
                $this->displayOption($key, $value);
            }
        }
    }

    /**
     * Display a singe option
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * @param  string $prefix
     * @return void
     */
    private function displayOption(mixed $key, mixed $value, string $prefix = ''): void
    {
        if (is_array($value)) {
            $value = implode(', ', $value);
        }
        $this->io->write($prefix . '    - ' . $key . ': ' . $value);
    }

    /**
     * Display all conditions
     *
     * @param array<\CaptainHook\App\Config\Condition> $conditions
     * @param string                                   $prefix
     * @return void
     */
    private function displayConditions(array $conditions, string $prefix = ''): void
    {
        if (empty($conditions)) {
            return;
        }
        if ($this->show(self::OPT_CONDITIONS)) {
            if (empty($prefix)) {
                $this->io->write($prefix . '   <comment>Conditions:</comment>');
            }
            foreach ($conditions as $condition) {
                $this->displayCondition($condition, $prefix);
            }
        }
    }

    /**
     * Display a single Condition
     *
     * @param \CaptainHook\App\Config\Condition $condition
     * @param string                            $prefix
     * @return void
     */
    private function displayCondition(Config\Condition $condition, string $prefix = ''): void
    {
        $this->io->write($prefix . '    - ' . $condition->getExec());

        if (in_array(strtoupper($condition->getExec()), ['OR', 'AND'])) {
            $conditions = [];
            foreach ($condition->getArgs() as $conf) {
                $conditions[] = new Config\Condition($conf['exec'], $conf['args'] ?? []);
            }
            $this->displayConditions($conditions, $prefix . '  ');
            return;
        }
        if ($this->show(self::OPT_OPTIONS)) {
            if (empty($condition->getArgs())) {
                return;
            }
            $this->io->write($prefix . '      <comment>Args:</comment>');
            foreach ($condition->getArgs() as $key => $value) {
                $this->displayOption($key, $value, $prefix . '   ');
            }
        }
    }

    /**
     * Check if a specific config part should be shown
     *
     * @param string $option
     * @return bool
     */
    private function show(string $option): bool
    {
        if (empty($this->options)) {
            return true;
        }
        return $this->options[$option] ?? false;
    }

    /**
     * Check if a hook should be displayed
     *
     * @param string $name
     * @return bool
     */
    private function shouldHookBeDisplayed(string $name): bool
    {
        if (empty($this->hooks)) {
            return true;
        }
        return in_array($name, $this->hooks);
    }

    /**
     * Return yes or no emoji
     *
     * @param bool $bool
     * @return string
     */
    private function yesOrNo(bool $bool): string
    {
        return $bool ? '✅ ' : '❌ ';
    }
}
