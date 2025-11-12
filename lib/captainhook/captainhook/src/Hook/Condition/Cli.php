<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\Condition;

use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Condition;
use SebastianFeldmann\Cli\Processor;
use SebastianFeldmann\Git\Repository;

/**
 * Class Cli
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 4.2.0
 */
class Cli implements Condition
{
    /**
     * Binary executor
     *
     * @var \SebastianFeldmann\Cli\Processor
     */
    private $processor;

    /**
     * @var string
     */
    private $command;

    /**
     * Cli constructor.
     *
     * @param \SebastianFeldmann\Cli\Processor $processor
     * @param string                           $command
     */
    public function __construct(Processor $processor, string $command)
    {
        $this->processor = $processor;
        $this->command   = $command;
    }

    /**
     * Evaluates a condition
     *
     * @param  \CaptainHook\App\Console\IO       $io
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @return bool
     */
    public function isTrue(IO $io, Repository $repository): bool
    {
        $result = $this->processor->run($this->command);

        return $result->isSuccessful();
    }
}
