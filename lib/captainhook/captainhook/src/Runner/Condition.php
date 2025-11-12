<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Runner;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Condition as ConditionInterface;
use CaptainHook\App\Hook\Condition\Cli;
use CaptainHook\App\Hook\Constrained;
use SebastianFeldmann\Cli\Processor\ProcOpen as Processor;
use SebastianFeldmann\Git\Repository;
use RuntimeException;

/**
 * Condition Runner
 *
 * Executes a condition of an action by creating a `Condition` object from a condition configuration.
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 4.2.0
 * @internal
 */
class Condition
{
    /**
     * @var \CaptainHook\App\Console\IO
     */
    private IO $io;

    /**
     * @var \SebastianFeldmann\Git\Repository
     */
    private Repository $repository;

    /**
     * @var \CaptainHook\App\Config
     */
    private Config $config;

    /**
     * Currently executed hook
     *
     * @var string
     */
    private string $hook;

    /**
     * Condition constructor.
     *
     * @param \CaptainHook\App\Console\IO       $io
     * @param \SebastianFeldmann\Git\Repository $repository
     * @param \CaptainHook\App\Config           $config
     * @param string                            $hook
     */
    public function __construct(IO $io, Repository $repository, Config $config, string $hook)
    {
        $this->io         = $io;
        $this->repository = $repository;
        $this->config     = $config;
        $this->hook       = $hook;
    }

    /**
     * Creates the configured condition and evaluates it
     *
     * @param  \CaptainHook\App\Config\Condition $config
     * @return bool
     */
    public function doesConditionApply(Config\Condition $config): bool
    {
        $condition = $this->createCondition($config);
        // check all given restrictions
        if (!$this->isApplicable($condition)) {
            $this->io->write('Condition skipped due to hook constraint', true, IO::VERBOSE);
            return true;
        }
        return $condition->isTrue($this->io, $this->repository);
    }

    /**
     * Return the configured condition
     *
     * In case of a cli condition it returns an special condition class that deals with
     * the binary execution with implementing the same interface.
     *
     * @param  \CaptainHook\App\Config\Condition $config
     * @return \CaptainHook\App\Hook\Condition
     * @throws \RuntimeException
     */
    private function createCondition(Config\Condition $config): ConditionInterface
    {
        if ($this->isLogicCondition($config)) {
            return $this->createLogicCondition($config);
        }

        if (Util::getExecType($config->getExec()) === 'cli') {
            return new Cli(new Processor(), $config->getExec());
        }

        /** @var class-string<\CaptainHook\App\Hook\Condition> $class */
        $class = $config->getExec();
        if (!class_exists($class)) {
            throw new RuntimeException('could not find condition class: ' . $class);
        }
        $condition = new $class(...$config->getArgs());
        if ($condition instanceof ConditionInterface\ConfigDependant) {
            $condition->setConfig($this->config);
        }
        return $condition;
    }

    /**
     * Create a logic condition with configures sub conditions
     *
     * @param  \CaptainHook\App\Config\Condition $config
     * @return \CaptainHook\App\Hook\Condition
     */
    private function createLogicCondition(Config\Condition $config): ConditionInterface
    {
        $class      = '\\CaptainHook\\App\\Hook\\Condition\\Logic\\Logic' . ucfirst(strtolower($config->getExec()));
        $conditions = [];
        foreach ($config->getArgs() as $conf) {
            $condition = $this->createCondition(new Config\Condition($conf['exec'], $conf['args'] ?? []));
            if (!$this->isApplicable($condition)) {
                $this->io->write('Condition skipped due to hook constraint', true, IO::VERBOSE);
                continue;
            }
            $conditions[] = $condition;
        }
        return $class::fromConditionsArray($conditions);
    }

    /**
     * Make sure the condition can be used during this hook
     *
     * @param  \CaptainHook\App\Hook\Condition $condition
     * @return bool
     */
    private function isApplicable(ConditionInterface $condition): bool
    {
        if ($condition instanceof Constrained) {
            return $condition->getRestriction()->isApplicableFor($this->hook);
        }
        return true;
    }

    /**
     * Is the condition a logic 'AND' or 'OR' condition
     *
     * @param \CaptainHook\App\Config\Condition $config
     * @return bool
     */
    private function isLogicCondition(Config\Condition $config): bool
    {
        return in_array(strtolower($config->getExec()), ['and', 'or']);
    }
}
