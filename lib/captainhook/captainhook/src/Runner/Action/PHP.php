<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Runner\Action;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Event\Dispatcher;
use CaptainHook\App\Exception\ActionFailed;
use CaptainHook\App\Hook\Action;
use CaptainHook\App\Hook\Constrained;
use CaptainHook\App\Hook\EventSubscriber;
use CaptainHook\App\Runner\Action as ActionRunner;
use Error;
use Exception;
use RuntimeException;
use SebastianFeldmann\Git\Repository;

/**
 * Class PHP
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 * @internal
 */
class PHP implements ActionRunner
{
    /**
     * Name of the currently executed hook
     *
     * @var string
     */
    private $hook;

    /**
     *
     * @var \CaptainHook\App\Event\Dispatcher
     */
    private $dispatcher;

    /**
     * PHP constructor.
     *
     * @param string $hook Name of the currently executed hook
     */
    public function __construct(string $hook, Dispatcher $dispatcher)
    {
        $this->hook       = $hook;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Execute the configured action
     *
     * @param  \CaptainHook\App\Config           $config
     * @param  \CaptainHook\App\Console\IO       $io
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @param  \CaptainHook\App\Config\Action    $action
     * @return void
     * @throws \CaptainHook\App\Exception\ActionFailed
     */
    public function execute(Config $config, IO $io, Repository $repository, Config\Action $action): void
    {
        $class = $action->getAction();

        try {
            // if the configured action is a static php method display the captured output and exit
            if ($this->isStaticMethodCall($class)) {
                $io->write($this->executeStatic($class));
                return;
            }

            // if not static it has to be an 'Action' so let's instantiate
            $exe = $this->createAction($class);
            // check for any given restrictions
            if (!$this->isApplicable($exe)) {
                $io->write('Action skipped due to hook constraint', true, IO::VERBOSE);
                return;
            }

            // make sure to collect all event handlers before executing the action
            if ($exe instanceof EventSubscriber) {
                $this->dispatcher->subscribeHandlers($class::getEventHandlers($action));
            }

            // no restrictions run it!
            $exe->execute($config, $io, $repository, $action);
        } catch (ActionFailed $e) {
            throw $e;
        } catch (Exception $e) {
            throw new ActionFailed(
                'Execution failed: ' . PHP_EOL .
                $e->getMessage() . ' in ' . $e->getFile() . ' line ' . $e->getLine()
            );
        } catch (Error $e) {
            throw new ActionFailed('PHP Error:' . $e->getMessage() . ' in ' . $e->getFile() . ' line ' . $e->getLine());
        }
    }

    /**
     * Execute static method call and return its output
     *
     * @param  string $class
     * @return string
     */
    private function executeStatic(string $class): string
    {
        [$class, $method] = explode('::', $class);
        if (!class_exists($class)) {
            throw new RuntimeException('could not find class: ' . $class);
        }
        if (!method_exists($class, $method)) {
            throw new RuntimeException('could not find method in class: ' . $method);
        }
        ob_start();
        $class::$method();
        return (string)ob_get_clean();
    }

    /**
     * Create an action instance
     *
     * @param string $class
     * @return \CaptainHook\App\Hook\Action
     * @throws \CaptainHook\App\Exception\ActionFailed
     */
    private function createAction(string $class): Action
    {
        $action = new $class();
        if (!$action instanceof Action) {
            throw new ActionFailed(
                'PHP class ' . $class . ' has to implement the \'Action\' interface'
            );
        }
        return $action;
    }

    /**
     * Is this a static method call
     *
     * @param  string $class
     * @return bool
     */
    private function isStaticMethodCall(string $class): bool
    {
        return (bool)preg_match('#^\\\\.+::.+$#i', $class);
    }

    /**
     * Make sure the action can be used during this hook
     *
     * @param  \CaptainHook\App\Hook\Action $action
     * @return bool
     */
    private function isApplicable(Action $action)
    {
        if ($action instanceof Constrained) {
            /** @var \CaptainHook\App\Hook\Constrained $action */
            return $action->getRestriction()->isApplicableFor($this->hook);
        }
        return true;
    }
}
