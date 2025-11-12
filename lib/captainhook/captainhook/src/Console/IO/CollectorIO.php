<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Console\IO;

use CaptainHook\App\Console\IO;
use CaptainHook\App\Console\IOUtil;
use SebastianFeldmann\Cli\Reader\StandardInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * Class CollectorIO
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.19.0
 */
class CollectorIO implements IO
{
    /**
     * @var \CaptainHook\App\Console\IO
     */
    private IO $io;

    /**
     * @var array<\CaptainHook\App\Console\IO\Message>
     */
    private array $messages = [];

    /**
     * Constructor
     *
     */
    public function __construct(IO $io)
    {
        $this->io = $io;
    }


    /**
     *
     * @param  string $messages
     * @param  bool   $newline
     * @param  int    $verbosity
     * @return void
     */
    public function write($messages, $newline = true, $verbosity = IO::NORMAL)
    {
        $this->messages[] = new Message($messages, $newline, $verbosity);
    }

    /**
     * @param  string $messages
     * @param  bool   $newline
     * @param  int    $verbosity
     * @return void
     */
    public function writeError($messages, $newline = true, $verbosity = IO::NORMAL)
    {
        $this->messages[] = new Message($messages, $newline, $verbosity);
    }

    /**
     * @return array<\CaptainHook\App\Console\IO\Message>
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * Return the original cli arguments
     *
     * @return array<string, mixed>
     */
    public function getArguments(): array
    {
        return $this->io->getArguments();
    }

    /**
     * Return the original cli argument or a given default
     *
     * @param  string $name
     * @param  string $default
     * @return string
     */
    public function getArgument(string $name, string $default = ''): string
    {
        return $this->io->getArgument($name, $default);
    }

    /**
     * Return the piped in standard input
     *
     * @return string[]
     */
    public function getStandardInput(): array
    {
        return $this->io->getStandardInput();
    }

    public function isInteractive()
    {
        return $this->io->isInteractive();
    }

    public function isVerbose()
    {
        return $this->io->isVerbose();
    }

    public function isVeryVerbose()
    {
        return $this->io->isVeryVerbose();
    }

    public function isDebug()
    {
        return $this->io->isDebug();
    }

    public function ask($question, $default = null)
    {
        return $this->io->ask($question, $default);
    }

    public function askConfirmation($question, $default = true)
    {
        return $this->io->askConfirmation($question, $default);
    }

    public function askAndValidate($question, $validator, $attempts = null, $default = null)
    {
        return $this->io->askAndValidate($question, $validator, $attempts, $default);
    }
}
