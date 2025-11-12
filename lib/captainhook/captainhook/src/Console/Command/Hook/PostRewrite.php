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
 * Class PostRewrite
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.4.0
 */
class PostRewrite extends Hook
{
    /**
     * Hook to execute.
     *
     * @var string
     */
    protected string $hookName = Hooks::POST_REWRITE;

    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this->addArgument(Hooks::ARG_GIT_COMMAND, InputArgument::OPTIONAL, 'Executed command');
    }
}
