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
 * Class PrePush
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
class PrePush extends Hook
{
    /**
     * Hook to execute.
     *
     * @var string
     */
    protected string $hookName = Hooks::PRE_PUSH;

    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this->addArgument(Hooks::ARG_TARGET, InputArgument::OPTIONAL, 'Target repository name');
        $this->addArgument(Hooks::ARG_URL, InputArgument::OPTIONAL, 'Target repository url');
    }
}
