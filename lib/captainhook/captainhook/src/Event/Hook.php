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
use SebastianFeldmann\Git\Repository;

/**
 * Basic event class
 *
 * Makes sure the handler has access to the output the current app setup and the repository.
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.11.0
 */
abstract class Hook implements Event
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var \CaptainHook\App\Console\IO
     */
    protected $io;

    /**
     * @var \CaptainHook\App\Config
     */
    protected $config;

    /**
     * @var \SebastianFeldmann\Git\Repository
     */
    protected $repository;

    /**
     * Event
     *
     * @param \CaptainHook\App\Console\IO       $io
     * @param \CaptainHook\App\Config           $config
     * @param \SebastianFeldmann\Git\Repository $repository
     */
    public function __construct(IO $io, Config $config, Repository $repository)
    {
        $this->io         = $io;
        $this->config     = $config;
        $this->repository = $repository;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return \CaptainHook\App\Config
     */
    public function config(): Config
    {
        return $this->config;
    }

    /**
     * @return \CaptainHook\App\Console\IO
     */
    public function io(): IO
    {
        return $this->io;
    }

    /**
     * @return \SebastianFeldmann\Git\Repository
     */
    public function repository(): Repository
    {
        return $this->repository;
    }
}
