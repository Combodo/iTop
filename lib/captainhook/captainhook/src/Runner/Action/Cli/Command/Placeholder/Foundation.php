<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Runner\Action\Cli\Command\Placeholder;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Runner\Action\Cli\Command\Placeholder as PlaceholderInterface;
use SebastianFeldmann\Git\Repository;

/**
 * Class Foundation
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.6.0
 */
abstract class Foundation implements PlaceholderInterface
{
    /**
     * Input Output handler
     *
     * @var \CaptainHook\App\Console\IO
     */
    protected IO $io;

    /**
     * CaptainHook configuration
     *
     * @var \CaptainHook\App\Config
     */
    protected Config $config;

    /**
     * Git repository
     *
     * @var \SebastianFeldmann\Git\Repository
     */
    protected Repository $repository;

    /**
     * StagedFile constructor
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
}
