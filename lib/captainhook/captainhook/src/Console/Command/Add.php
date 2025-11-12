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
use CaptainHook\App\Runner\Config\Editor;
use Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Add
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 4.2.0
 */
class Add extends ConfigAware
{
    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this->setName('config:add')
             ->setAliases(['add'])
             ->setDescription('Add an action to your hook configuration')
             ->setHelp('Add an action to your hook configuration')
             ->addArgument('hook', InputArgument::REQUIRED, 'Hook you want to add the action to');
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
            $config = $this->createConfig($input, true);

            $this->determineVerbosity($output, $config);

            $editor = new Editor($io, $config);
            $editor->setHook(IOUtil::argToString($input->getArgument('hook')))
                   ->setChange('AddAction')
                   ->run();

            return 0;
        } catch (Exception $e) {
            return $this->crash($output, $e);
        }
    }
}
