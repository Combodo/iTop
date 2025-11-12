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
use CaptainHook\App\Console\Runtime\Resolver;
use CaptainHook\App\Runner\Config\Creator;
use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Config
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
class Configuration extends ConfigAware
{
    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this->setName('configure')
             ->setDescription('Create or update a captainhook.json configuration')
             ->setHelp('Create or update a captainhook.json configuration')
             ->addOption('extend', 'e', InputOption::VALUE_NONE, 'Extend existing configuration file')
             ->addOption('force', 'f', InputOption::VALUE_NONE, 'Overwrite existing configuration file')
             ->addOption('advanced', 'a', InputOption::VALUE_NONE, 'More options, but more to type')
             ->addOption(
                 'bootstrap',
                 null,
                 InputOption::VALUE_OPTIONAL,
                 'Path to composers vendor/autoload.php'
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
        try {
            $io     = $this->getIO($input, $output);
            $config = $this->createConfig($input, false, ['bootstrap']);

            $this->determineVerbosity($output, $config);

            $configurator = new Creator($io, $config);
            $configurator->force(IOUtil::argToBool($input->getOption('force')))
                         ->extend(IOUtil::argToBool($input->getOption('extend')))
                         ->advanced(IOUtil::argToBool($input->getOption('advanced')))
                         ->setExecutable($this->resolver->getExecutable())
                         ->run();

            return 0;
        } catch (Exception $e) {
            return $this->crash($output, $e);
        }
    }
}
