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
final class CachePoolDeleteCommand extends Command
{
    protected static $defaultName = 'cache:pool:delete';
    protected static $defaultDescription = 'Delete an item from a cache pool';

    private $poolClearer;
    private $poolNames;

    /**
     * @param string[]|null $poolNames
     */
    public function __construct(Psr6CacheClearer $poolClearer, array $poolNames = null)
    {
        parent::__construct();

        $this->poolClearer = $poolClearer;
        $this->poolNames = $poolNames;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDefinition([
                new InputArgument('pool', InputArgument::REQUIRED, 'The cache pool from which to delete an item'),
                new InputArgument('key', InputArgument::REQUIRED, 'The cache key to delete from the pool'),
            ])
            ->setDescription(self::$defaultDescription)
            ->setHelp(<<<'EOF'
The <info>%command.name%</info> deletes an item from a given cache pool.

    %command.full_name% <pool> <key>
EOF
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
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
