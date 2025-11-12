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
use CaptainHook\App\Runner\Config\Reader;
use Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to display configuration information
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.24.0
 */
class Info extends RepositoryAware
{
    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this->setName('config:info')
             ->setAliases(['info'])
             ->setDescription('Displays information about the configuration')
             ->setHelp('Displays information about the configuration')
             ->addArgument('hook', InputArgument::OPTIONAL, 'Hook you want to investigate')
             ->addOption(
                 'list-actions',
                 'a',
                 InputOption::VALUE_NONE,
                 'List all actions'
             )
             ->addOption(
                 'list-conditions',
                 'p',
                 InputOption::VALUE_NONE,
                 'List all conditions'
             )
             ->addOption(
                 'list-options',
                 'o',
                 InputOption::VALUE_NONE,
                 'List all options'
             )
             ->addOption(
                 'list-config',
                 's',
                 InputOption::VALUE_NONE,
                 'List all config settings'
             )
             ->addOption(
                 'extensive',
                 'e',
                 InputOption::VALUE_NONE,
                 'Show more detailed information'
             );
    }

    /**
     * Execute the command
     *
     * @param  \Symfony\Component\Console\Input\InputInterface   $input
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     * @throws \CaptainHook\App\Exception\InvalidHookName
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $io     = $this->getIO($input, $output);
            $config = $this->createConfig($input, true, ['git-directory']);
            $repo   = $this->createRepository(dirname($config->getGitDirectory()));

            $this->determineVerbosity($output, $config);

            $editor = new Reader($io, $config, $repo);
            $editor->setHook(IOUtil::argToString($input->getArgument('hook')))
                   ->display(Reader::OPT_ACTIONS, $input->getOption('list-actions'))
                   ->display(Reader::OPT_CONDITIONS, $input->getOption('list-conditions'))
                   ->display(Reader::OPT_OPTIONS, $input->getOption('list-options'))
                   ->extensive($input->getOption('extensive'))
                   ->run();

            return 0;
        } catch (Exception $e) {
            return $this->crash($output, $e);
        }
    }
}
