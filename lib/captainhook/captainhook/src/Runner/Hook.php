<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Runner;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Event\Dispatcher;
use CaptainHook\App\Exception\ActionFailed;
use CaptainHook\App\Hook\Constrained;
use CaptainHook\App\Hook\Template\Inspector;
use CaptainHook\App\Plugin;
use CaptainHook\App\Runner\Action\Log as ActionLog;
use CaptainHook\App\Runner\Hook\Log as HookLog;
use CaptainHook\App\Runner\Hook\Printer;
use Exception;
use SebastianFeldmann\Git\Repository;

/**
 * Hook
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
abstract class Hook extends RepositoryAware
{
    /**
     * Hook status constants
     */
    public const HOOK_SUCCEEDED = 0;
    public const HOOK_FAILED    = 1;

    /**
     * Hook that should be handled
     *
     * @var string
     */
    protected string $hook;

    /**
     * Set to `true` to skip processing this hook's actions
     *
     * @var bool
     */
    private bool $skipActions = false;

    /**
     * Event dispatcher
     *
     * @var \CaptainHook\App\Event\Dispatcher
     */
    protected Dispatcher $dispatcher;

    /**
     * Plugins to apply to this hook
     *
     * @var array<Plugin\Hook>|null
     */
    private ?array $hookPlugins = null;

    /**
     * Handling the hook output
     *
     * @var \CaptainHook\App\Runner\Hook\Printer
     */
    private Printer $printer;

    /**
     * Logs all output to do it at the end
     *
     * @var \CaptainHook\App\Runner\Hook\Log
     */
    private HookLog $hookLog;

    /**
     * Should the plugins be disabled?
     *
     * @var boolean
     */
    private bool $pluginsDisabled = false;

    /**
     * Set up the hook runner
     *
     * @param \CaptainHook\App\Console\IO       $io
     * @param \CaptainHook\App\Config           $config
     * @param \SebastianFeldmann\Git\Repository $repository
     */
    public function __construct(IO $io, Config $config, Repository $repository)
    {
        $this->dispatcher = new Dispatcher($io, $config, $repository);
        $this->printer    = new Printer($io);
        $this->hookLog    = new HookLog();

        parent::__construct($io, $config, $repository);
    }

    /**
     * Allow plugins to be disabled
     *
     * @param  bool $disabled
     * @return void
     */
    public function setPluginsDisabled(bool $disabled): void
    {
        $this->pluginsDisabled = $disabled;
    }

    /**
     * Return this hook's name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->hook;
    }

    /**
     * Execute stuff before executing any actions
     *
     * @return void
     */
    public function beforeHook(): void
    {
        $this->executeHookPluginsFor('beforeHook');
    }

    /**
     * Execute stuff before every action
     *
     * @param Config\Action $action
     * @return void
     */
    public function beforeAction(Config\Action $action): void
    {
        $this->executeHookPluginsFor('beforeAction', $action);
    }

    /**
     * Execute stuff after every action
     *
     * @param Config\Action $action
     * @return void
     */
    public function afterAction(Config\Action $action): void
    {
        $this->executeHookPluginsFor('afterAction', $action);
    }

    /**
     * Execute stuff after all actions
     *
     * @return void
     */
    public function afterHook(): void
    {
        $this->executeHookPluginsFor('afterHook');
    }

    /**
     * Execute the hook and all its actions
     *
     * @return void
     * @throws \Exception
     */
    public function run(): void
    {
        $this->io->write('<comment>' . $this->hook . ':</comment> ');

        if (!$this->config->isHookEnabled($this->hook)) {
            $this->io->write(' - hook is disabled');
            return;
        }

        if ($this->pluginsDisabled) {
            $this->io->write('<fg=magenta>Running with plugins disabled</>');
        }

        $this->checkHookScript();

        $this->beforeHook();
        try {
            $this->runHook();
        } finally {
            $this->afterHook();
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    protected function runHook(): void
    {
        $hookConfig = $this->config->getHookConfigToExecute($this->hook);
        $actions    = $hookConfig->getActions();
        // are any actions configured?
        if (count($actions) === 0) {
            $this->io->write(' - no actions to execute');
        } else {
            $this->executeActions($actions);
        }
    }

    /**
     * Returns `true` if something has indicated that the hook should skip all
     * remaining actions; pass a boolean value to toggle this
     *
     * There may be times you want to conditionally skip all actions, based on
     * logic in {@see beforeHook()}. Other times, you may wish to skip the rest
     * of the actions based on some condition of the current action.
     *
     * - To skip all actions for a hook, set this to `true`
     *   in {@see beforeHook()}.
     * - To skip the current action and all remaining actions, set this
     *   to `true` in {@see beforeAction()}.
     * - To run the current action but skip all remaining actions, set this
     *   to `true` in {@see afterAction()}.
     *
     * @param bool|null $shouldSkip
     * @return bool
     */
    public function shouldSkipActions(?bool $shouldSkip = null): bool
    {
        if ($shouldSkip !== null) {
            $this->skipActions = $shouldSkip;
        }
        return $this->skipActions;
    }

    /**
     * Executes all the Actions configured for the hook
     *
     * @param  \CaptainHook\App\Config\Action[] $actions
     * @throws \Exception
     */
    private function executeActions(array $actions): void
    {
        $status = self::HOOK_SUCCEEDED;
        $start  = microtime(true);
        try {
            if ($this->config->failOnFirstError()) {
                $this->executeFailOnFirstError($actions);
            } else {
                $this->executeFailAfterAllActions($actions);
            }
            $this->dispatcher->dispatch('onHookSuccess');
        } catch (Exception $e) {
            $status = self::HOOK_FAILED;
            $this->dispatcher->dispatch('onHookFailure');
            throw $e;
        } finally {
            $duration = microtime(true) - $start;
            $this->printer->hookEnded($status, $this->hookLog, $duration);
        }
    }

    /**
     * Executes all actions and fails at the first error
     *
     * @param  \CaptainHook\App\Config\Action[] $actions
     * @return void
     * @throws \Exception
     */
    private function executeFailOnFirstError(array $actions): void
    {
        foreach ($actions as $action) {
            $this->handleAction($action);
        }
    }

    /**
     * Executes all actions but does not fail immediately
     *
     * @param \CaptainHook\App\Config\Action[] $actions
     * @return void
     * @throws \CaptainHook\App\Exception\ActionFailed
     */
    private function executeFailAfterAllActions(array $actions): void
    {
        $failedActions = 0;
        foreach ($actions as $action) {
            try {
                $this->handleAction($action);
            } catch (Exception $exception) {
                $failedActions++;
            }
        }
        if ($failedActions > 0) {
            throw new ActionFailed($failedActions . ' action' . ($failedActions > 1 ? 's' : '') . ' failed');
        }
    }

    /**
     * Executes a configured hook action
     *
     * @param  \CaptainHook\App\Config\Action $action
     * @return void
     * @throws \Exception
     */
    private function handleAction(Config\Action $action): void
    {
        if ($this->shouldSkipActions()) {
            $this->printer->actionDeactivated($action);
            return;
        }

        $io     = new IO\CollectorIO($this->io);
        $status = ActionLog::ACTION_SUCCEEDED;

        try {
            if (!$this->doConditionsApply($action->getConditions(), $io)) {
                $this->printer->actionSkipped($action);
                return;
            }

            $this->beforeAction($action);

            // The beforeAction() method may indicate that the current and all
            // remaining actions should be skipped. If so, return here.
            if ($this->shouldSkipActions()) {
                return;
            }

            $runner = $this->createActionRunner(Util::getExecType($action->getAction()));
            $runner->execute($this->config, $io, $this->repository, $action);
            $this->printer->actionSucceeded($action);
        } catch (Exception  $e) {
            $status = ActionLog::ACTION_FAILED;
            $this->printer->actionFailed($action);
            if (!$action->isFailureAllowed($this->config->isFailureAllowed())) {
                throw $e;
            }
            $io->write('<fg=yellow>' . $e->getMessage() . '</>');
        } finally {
            $this->hookLog->addActionLog(new ActionLog($action, $status, $io->getMessages()));
            $this->afterAction($action);
        }
    }

    /**
     * Return the right method name to execute an action
     *
     * @param  string $type
     * @return \CaptainHook\App\Runner\Action
     */
    private function createActionRunner(string $type): Action
    {
        $valid = [
            'php' => fn(): Action => new Action\PHP($this->hook, $this->dispatcher),
            'cli' => fn(): Action => new Action\Cli(),
        ];
        return $valid[$type]();
    }

    /**
     * Check if conditions apply
     *
     * @param \CaptainHook\App\Config\Condition[] $conditions
     * @param \CaptainHook\App\Console\IO         $collectorIO
     * @return bool
     */
    private function doConditionsApply(array $conditions, IO $collectorIO): bool
    {
        $conditionRunner = new Condition($collectorIO, $this->repository, $this->config, $this->hook);
        foreach ($conditions as $config) {
            $collectorIO->write('  <fg=cyan>Condition: ' . $config->getExec() . '</>', true, IO::VERBOSE);
            if (!$conditionRunner->doesConditionApply($config)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Return plugins to apply to this hook.
     *
     * @return array<Plugin\Hook>
     */
    private function getHookPlugins(): array
    {
        if ($this->hookPlugins == null) {
            $this->hookPlugins = $this->setupHookPlugins();
        }
        return $this->hookPlugins;
    }

    /**
     *
     * Setup configured hook plugins
     *
     * @return array<Plugin\Hook>
     */
    private function setupHookPlugins(): array
    {
        $this->hookPlugins = [];

        foreach ($this->config->getPlugins() as $pluginConfig) {
            $pluginClass = $pluginConfig->getPlugin();
            if (!is_a($pluginClass, Plugin\Hook::class, true)) {
                continue;
            }

            $this->io->write(
                ['', 'Configuring Hook Plugin: <comment>' . $pluginClass . '</comment>'],
                true,
                IO::VERBOSE
            );

            if ($this->isPluginApplicableForCurrentHook($pluginClass)) {
                $this->io->write('Skipped because plugin is not applicable for hook ' . $this->hook, true, IO::VERBOSE);
                continue;
            }

            $plugin = new $pluginClass();
            $plugin->configure($this->config, $this->io, $this->repository, $pluginConfig);

            $this->hookPlugins[] = $plugin;
        }
        return $this->hookPlugins;
    }

    /**
     * Execute hook plugins for the given method name (i.e., beforeHook,
     * beforeAction, afterAction, afterHook).
     *
     * @param string $method
     * @param Config\Action|null $action
     * @return void
     */
    private function executeHookPluginsFor(string $method, ?Config\Action $action = null): void
    {
        if ($this->pluginsDisabled) {
            return;
        }

        $plugins = $this->getHookPlugins();

        if (count($plugins) === 0) {
            $this->io->write(['No plugins to execute for: <comment>' . $method . '</comment>'], true, IO::DEBUG);
            return;
        }

        $params = [$this];

        if ($action !== null) {
            $params[] = $action;
        }

        $this->io->write(['Executing plugins for: <comment>' . $method . '</comment>'], true, IO::DEBUG);

        foreach ($plugins as $plugin) {
            $this->io->write('<info>- Running ' . get_class($plugin) . '::' . $method . '</info>', true, IO::DEBUG);
            $plugin->{$method}(...$params);
        }
    }

    /**
     * Makes sure the hook script was installed/created with a decent enough version
     *
     * @return void
     * @throws \Exception
     */
    private function checkHookScript(): void
    {
        $inspector = new Inspector($this->hook, $this->io, $this->repository);
        $inspector->inspect();
    }

    /**
     * @param  string $pluginClass
     * @return bool
     */
    private function isPluginApplicableForCurrentHook(string $pluginClass): bool
    {
        return is_a($pluginClass, Constrained::class, true)
               && !$pluginClass::getRestriction()->isApplicableFor($this->hook);
    }
}
