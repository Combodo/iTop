<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Console\Command\Hook;

use CaptainHook\App\Console\Command\Hook;
use CaptainHook\App\Hooks;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class PostMerge
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 4.0.1
 */
class PostMerge extends Hook
{
    /**
     * Hook to execute.
     *
     * @var string
     */
    protected string $hookName = Hooks::POST_MERGE;

    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this->addArgument(Hooks::ARG_SQUASH, InputArgument::OPTIONAL, 'Merge was done with a squash merge.');
    }
}
