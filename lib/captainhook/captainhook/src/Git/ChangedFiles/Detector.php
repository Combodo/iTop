<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Git\ChangedFiles;

use CaptainHook\App\Console\IO;
use SebastianFeldmann\Git\Repository;

/**
 * Class Detector
 *
 * Base class for all ChangedFiles Detecting implementations.
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.20.0
 */
abstract class Detector implements Detecting
{
    /**
     * Input output handling
     *
     * @var \CaptainHook\App\Console\IO
     */
    protected IO $io;

    /**
     * Git repository
     *
     * @var \SebastianFeldmann\Git\Repository
     */
    protected Repository $repository;

    /**
     * Constructor
     *
     * @param \CaptainHook\App\Console\IO       $io
     * @param \SebastianFeldmann\Git\Repository $repository
     */
    public function __construct(IO $io, Repository $repository)
    {
        $this->io         = $io;
        $this->repository = $repository;
    }

    /**
     * Returns a list of changed files
     *
     * @param  array<string> $filter
     * @return array<string>
     */
    abstract public function getChangedFiles(array $filter = []): array;
}
