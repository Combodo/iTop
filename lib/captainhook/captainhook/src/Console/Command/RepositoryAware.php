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

use CaptainHook\App\Console\Runtime\Resolver;
use SebastianFeldmann\Git\Repository;
use Symfony\Component\Console\Input\InputOption;

/**
 * Trait RepositoryAware
 *
 * Trait for all commands that needs to be aware of the git repository.
 *
 * @package CaptainHook\App\Console\Command
 */
class RepositoryAware extends ConfigAware
{
    /**
     * Configure method to set up the git-directory command option
     *
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();

        $this->addOption(
            'git-directory',
            'g',
            InputOption::VALUE_OPTIONAL,
            'Path to your .git directory'
        );
    }

    /**
     * Create a new git repository representation
     *
     * @param  string $path
     * @return \SebastianFeldmann\Git\Repository
     */
    protected function createRepository(string $path): Repository
    {
        return Repository::createVerified($path);
    }
}
