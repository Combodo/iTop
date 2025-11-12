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

use CaptainHook\App\Config;
use CaptainHook\App\Console\IOUtil;
use CaptainHook\App\Hook\Template;
use CaptainHook\App\Runner\Installer;
use Exception;
use RuntimeException;
use SebastianFeldmann\Git\Repository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Install
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
class Install extends RepositoryAware
{
    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this->setName('install')
             ->setDescription('Install hooks to your .git/hooks directory')
             ->setHelp('Install git hooks to your .git/hooks directory')
             ->addArgument(
                 'hook',
                 InputArgument::OPTIONAL,
                 'Limit the hooks you want to install. ' .
                 'You can specify multiple hooks with comma as delimiter. ' .
                 'By default all hooks get installed'
             )
             ->addOption(
                 'only-enabled',
                 null,
                 InputOption::VALUE_NONE,
                 'Limit the hooks you want to install to those enabled in your conf. ' .
                 'By default all hooks get installed'
             )
             ->addOption(
                 'force',
                 'f',
                 InputOption::VALUE_NONE,
                 'Force install without confirmation'
             )
             ->addOption(
                 'skip-existing',
                 's',
                 InputOption::VALUE_NONE,
                 'Do not overwrite existing hooks'
             )
             ->addOption(
                 'move-existing-to',
                 null,
                 InputOption::VALUE_OPTIONAL,
                 'Move existing hooks to given directory'
             )
             ->addOption(
                 'bootstrap',
                 'b',
                 InputOption::VALUE_OPTIONAL,
                 'Path to composers vendor/autoload.php'
             )
             ->addOption(
                 'run-mode',
                 'm',
                 InputOption::VALUE_OPTIONAL,
                 'Git hook run mode [php|shell|docker]'
             )
             ->addOption(
                 'run-exec',
                 'e',
                 InputOption::VALUE_OPTIONAL,
                 'The Docker command to start your container e.g. \'docker exec CONTAINER\''
             )
             ->addOption(
                 'run-path',
                 'p',
                 InputOption::VALUE_OPTIONAL,
                 'The path to the CaptainHook executable \'/usr/bin/captainhook\''
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
            $args     = ['git-directory', 'run-mode', 'run-exec', 'run-path', 'bootstrap'];
            $io       = $this->getIO($input, $output);
            $config   = $this->createConfig($input, true, $args);
            $repo     = $this->createRepository(dirname($config->getGitDirectory()));
            $template = $this->createTemplate($config, $repo);

            $this->determineVerbosity($output, $config);

            $installer = new Installer($io, $config, $repo, $template);
            $installer->setHook(IOUtil::argToString($input->getArgument('hook')))
                      ->setForce(IOUtil::argToBool($input->getOption('force')))
                      ->setSkipExisting(IOUtil::argToBool($input->getOption('skip-existing')))
                      ->setMoveExistingTo(IOUtil::argToString($input->getOption('move-existing-to')))
                      ->setOnlyEnabled(IOUtil::argToBool($input->getOption('only-enabled')))
                      ->run();

            return 0;
        } catch (Exception $e) {
            return $this->crash($output, $e);
        }
    }

    /**
     * Create the template to generate the hook source code
     *
     * @param  \CaptainHook\App\Config           $config
     * @param  \SebastianFeldmann\Git\Repository $repo
     * @return \CaptainHook\App\Hook\Template
     */
    private function createTemplate(Config $config, Repository $repo): Template
    {
        if (
            $config->getRunConfig()->getMode() === Template::DOCKER
            && empty($config->getRunConfig()->getDockerCommand())
        ) {
            throw new RuntimeException('Run "exec" option missing for run-mode docker.');
        }

        return Template\Builder::build($config, $repo, $this->resolver);
    }
}
