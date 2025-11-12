<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Event;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use SebastianFeldmann\Git\Repository;

/**
 * Event Dispatcher
 *
 * This allows the user to hook into the Cap'n on a deeper level. For example execute code if the hook execution fails.
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.11.0
 */
class Dispatcher
{
    /**
     * List of all registered handlers
     *
     * @var array<string, array<int, \CaptainHook\App\Event\Handler>>
     */
    private $config = [];

    /**
     * Event factory to create all necessary events
     *
     * @var \CaptainHook\App\Event\Factory
     */
    private $factory;

    /**
     * Event Dispatcher
     *
     * @param \CaptainHook\App\Console\IO       $io
     * @param \CaptainHook\App\Config           $config
     * @param \SebastianFeldmann\Git\Repository $repository
     */
    public function __construct(IO $io, Config $config, Repository $repository)
    {
        $this->factory = new Factory($io, $config, $repository);
    }

    /**
     * Register handlers received from a Listener to the dispatcher
     *
     * @param  array<string, array<int, \CaptainHook\App\Event\Handler>> $eventConfig
     * @return void
     */
    public function subscribeHandlers(array $eventConfig): void
    {
        foreach ($eventConfig as $event => $handlers) {
            foreach ($handlers as $handler) {
                $this->subscribeHandlerToEvent($handler, $event);
            }
        }
    }

    /**
     * Register a single event handler to an event
     *
     * @param  \CaptainHook\App\Event\Handler $handler
     * @param  string                         $event
     * @return void
     */
    public function subscribeHandlerToEvent(Handler $handler, string $event): void
    {
        $this->config[$event][] = $handler;
    }

    /**
     * Trigger all event handlers registered for a given event
     *
     * @param  string $eventName
     * @throws \Exception
     * @return void
     */
    public function dispatch(string $eventName): void
    {
        $event = $this->factory->createEvent($eventName);

        foreach ($this->handlersFor($event->name()) as $handler) {
            $handler->handle($event);
        }
    }

    /**
     * Return a list of handlers for a given event
     *
     * @param  string $event
     * @return \CaptainHook\App\Event\Handler[];
     */
    private function handlersFor(string $event): array
    {
        return $this->config[$event] ?? [];
    }
}
