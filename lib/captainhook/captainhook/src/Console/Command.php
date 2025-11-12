<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Console;

use CaptainHook\App\Console\Runtime\Resolver;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

/**
 * Class Command
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.0.0
 */
abstract class Command extends SymfonyCommand
{
    /**
     * Input output handler
     *
     * @var \CaptainHook\App\Console\IO|null
     */
    private ?IO $io = null;

    /**
     * Runtime resolver
     *
     * @var \CaptainHook\App\Console\Runtime\Resolver
     */
    protected Resolver $resolver;

    /**
     * Command constructor
     *
     * @param \CaptainHook\App\Console\Runtime\Resolver $resolver
     */
    public function __construct(Resolver $resolver)
    {
        $this->resolver = $resolver;
        parent::__construct();
    }

    /**
     * IO setter
     *
     * @param \CaptainHook\App\Console\IO $io
     */
    public function setIO(IO $io): void
    {
        $this->io = $io;
    }

    /**
     * IO interface getter
     *
     * @param  \Symfony\Component\Console\Input\InputInterface   $input
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return \CaptainHook\App\Console\IO
     */
    public function getIO(InputInterface $input, OutputInterface $output): IO
    {
        if (null === $this->io) {
            $this->io = new IO\DefaultIO($input, $output, $this->getHelperSet());
        }
        return $this->io;
    }

    /**
     * Write a final error message
     *
     * @param  \Symfony\Component\Console\Output\OutputInterface $out
     * @param  \Throwable                                        $t
     * @return int
     * @throws \Throwable
     */
    public function crash(OutputInterface $out, Throwable $t): int
    {
        if ($out->isDebug()) {
            throw $t;
        }

        $out->writeln('<fg=red>' . $t->getMessage() . '</>');
        if ($out->isVerbose()) {
            $out->writeln(
                '<comment>Error triggered in file:</comment> ' . $t->getFile() .
                ' <comment>in line:</comment> ' . $t->getLine()
            );
        }
        return 1;
    }
}
