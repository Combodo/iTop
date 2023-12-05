<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Completion\CompletionInput;
use Symfony\Component\Console\Completion\CompletionSuggestions;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\CacheClearer\Psr6CacheClearer;

/**
 * Delete an item from a cache pool.
 *
 * @author Pierre du Plessis <pdples@gmail.com>
 */
#[AsCommand(name: 'cache:pool:delete', description: 'Delete an item from a cache pool')]
final class CachePoolDeleteCommand extends Command
{
    private Psr6CacheClearer $poolClearer;
    private ?array $poolNames;

    /**
     * @param string[]|null $poolNames
     */
    public function __construct(Psr6CacheClearer $poolClearer, array $poolNames = null)
    {
        parent::__construct();

        $this->poolClearer = $poolClearer;
        $this->poolNames = $poolNames;
    }

    protected function configure(): void
    {
        $this
            ->setDefinition([
                new InputArgument('pool', InputArgument::REQUIRED, 'The cache pool from which to delete an item'),
                new InputArgument('key', InputArgument::REQUIRED, 'The cache key to delete from the pool'),
            ])
            ->setHelp(<<<'EOF'
The <info>%command.name%</info> deletes an item from a given cache pool.

    %command.full_name% <pool> <key>
EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $pool = $input->getArgument('pool');
        $key = $input->getArgument('key');
        $cachePool = $this->poolClearer->getPool($pool);

        if (!$cachePool->hasItem($key)) {
            $io->note(sprintf('Cache item "%s" does not exist in cache pool "%s".', $key, $pool));

            return 0;
        }

        if (!$cachePool->deleteItem($key)) {
            throw new \Exception(sprintf('Cache item "%s" could not be deleted.', $key));
        }

        $io->success(sprintf('Cache item "%s" was successfully deleted.', $key));

        return 0;
    }

    public function complete(CompletionInput $input, CompletionSuggestions $suggestions): void
    {
        if (\is_array($this->poolNames) && $input->mustSuggestArgumentValuesFor('pool')) {
            $suggestions->suggestValues($this->poolNames);
        }
    }
}
