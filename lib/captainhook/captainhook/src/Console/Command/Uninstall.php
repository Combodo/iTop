<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Console\Command;

use CaptainHook\App\Console\IOUtil;
use CaptainHook\App\Runner\Uninstaller;
use Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Uninstall
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.17.0
 */
class Uninstall extends RepositoryAware
{
    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this->setName('uninstall')
             ->setDescription('Remove all git hooks from your .git/hooks directory')
             ->setHelp('Remove all git hooks from your .git/hooks directory')
             ->addArgument(
                 'hook',
                 InputArgument::OPTIONAL,
                 'Remove only this one hook. By default all hooks get uninstalled'
             )
             ->addOption(
                 'force',
                 'f',
                 InputOption::VALUE_NONE,
                 'Force install without confirmation'
             )
             ->addOption(
                 'only-disabled',
                 null,
                 InputOption::VALUE_NONE,
                 'Limit the hooks you want to remove to those that are not enabled in your conf. ' .
                 'By default all hooks get uninstalled'
             )
             ->addOption(
                 'move-existing-to',
                 null,
                 InputOption::VALUE_OPTIONAL,
                 'Move existing hooks to this directory'
             );
    }

    /**
     * Execute the command
     *
     * @param  \Symfony\Component\Console\Input\InputInterface   $input
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getIO($input, $output);

        try {
            $config = $this->createConfig($input, true, ['git-directory']);
            $repo   = $this->createRepository(dirname($config->getGitDirectory()));

            $this->determineVerbosity($output, $config);

            $uninstaller = new Uninstaller($io, $config, $repo);
            $uninstaller->setHook(IOUtil::argToString($input->getArgument('hook')))
                        ->setForce(IOUtil::argToBool($input->getOption('force')))
                        ->setOnlyDisabled(IOUtil::argToBool($input->getOption('only-disabled')))
                        ->setMoveExistingTo(IOUtil::argToString($input->getOption('move-existing-to')))
                        ->run();

            return 0;
        } catch (Exception $e) {
            $this->crash($output, $e);
            return 1;
        }
    }
}
