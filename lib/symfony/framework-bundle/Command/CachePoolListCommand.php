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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * List available cache pools.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
#[AsCommand(name: 'cache:pool:list', description: 'List available cache pools')]
final class CachePoolListCommand extends Command
{
    private array $poolNames;

    /**
     * @param string[] $poolNames
     */
    public function __construct(array $poolNames)
    {
        parent::__construct();

        $this->poolNames = $poolNames;
    }

    protected function configure(): void
    {
        $this
            ->setHelp(<<<'EOF'
The <info>%command.name%</info> command lists all available cache pools.
EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->table(['Pool name'], array_map(fn ($pool) => [$pool], $this->poolNames));

        return 0;
    }
}
