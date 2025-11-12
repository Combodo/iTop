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
use CaptainHook\App\Event;
use RuntimeException;
use SebastianFeldmann\Git\Repository;

/**
 * Event Factory
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.11.0
 */
class Factory
{
    /**
     * @var \CaptainHook\App\Config
     */
    protected $config;

    /**
     * @var \CaptainHook\App\Console\IO
     */
    private $io;

    /**
     * @var \SebastianFeldmann\Git\Repository
     */
    private $repository;

    /**
     * List of available events
     *
     * @var string[]
     */
    private $validEventIDs = [
        'onHookFailure' => HookFailed::class,
        'onHookSuccess' => HookSucceeded::class,
    ];

    /**
     * Event Factory
     *
     * @param \CaptainHook\App\Console\IO       $io
     * @param \CaptainHook\App\Config           $config
     * @param \SebastianFeldmann\Git\Repository $repository
     */
    public function __construct(IO $io, Config $config, Repository $repository)
    {
        $this->config     = $config;
        $this->io         = $io;
        $this->repository = $repository;
    }

    /**
     * Create a CaptainHook event
     *
     * @param string $name
     * @return \CaptainHook\App\Event
     */
    public function createEvent(string $name): Event
    {
        if (!$this->isEventIDValid($name)) {
            throw new RuntimeException('invalid event name: ' . $name);
        }

        $class = $this->validEventIDs[$name];
        /** @var \CaptainHook\App\Event $event */
        $event = new $class($this->io, $this->config, $this->repository);
        return $event;
    }

    /**
     * Validates an event name
     *
     * @param  string $name
     * @return bool
     */
    public function isEventIDValid(string $name): bool
    {
        return array_key_exists($name, $this->validEventIDs);
    }
}
