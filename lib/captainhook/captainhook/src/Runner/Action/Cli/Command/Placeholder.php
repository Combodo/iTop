<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Runner\Action\Cli\Command;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use SebastianFeldmann\Git\Repository;

/**
 * Interface Placeholder
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.0.0
 */
interface Placeholder
{
    /**
     * Placeholder constructor
     *
     * @param \CaptainHook\App\Console\IO       $io
     * @param \CaptainHook\App\Config           $config
     * @param \SebastianFeldmann\Git\Repository $repository
     */
    public function __construct(IO $io, Config $config, Repository $repository);

    /**
     * Return the replacement value for this placeholder
     *
     * @param  array<string, mixed> $options
     * @return string
     */
    public function replacement(array $options): string;
}
