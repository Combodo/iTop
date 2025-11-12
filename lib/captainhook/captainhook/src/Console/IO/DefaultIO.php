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
use RuntimeException;
use SebastianFeldmann\Cli\Reader\StandardInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * Class DefaultIO
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
class DefaultIO extends Base
{
    /**
     * Contents of the STDIN
     *
     * @var array<string>
     */
    private array $stdIn = [];

    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected InputInterface $input;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected OutputInterface $output;

    /**
     * @var \Symfony\Component\Console\Helper\HelperSet|null
     */
    protected ?HelperSet $helperSet;

    /**
     * @var array<int, int>
     */
    private array $verbosityMap;

    /**
     * Constructor
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Symfony\Component\Console\Helper\HelperSet|null  $helperSet
     */
    public function __construct(InputInterface $input, OutputInterface $output, ?HelperSet $helperSet = null)
    {
        $this->input        = $input;
        $this->output       = $output;
        $this->helperSet    = $helperSet;
        $this->verbosityMap = [
            IO::QUIET        => OutputInterface::VERBOSITY_QUIET,
            IO::NORMAL       => OutputInterface::VERBOSITY_NORMAL,
            IO::VERBOSE      => OutputInterface::VERBOSITY_VERBOSE,
            IO::VERY_VERBOSE => OutputInterface::VERBOSITY_VERY_VERBOSE,
            IO::DEBUG        => OutputInterface::VERBOSITY_DEBUG
        ];
    }

    /**
     * Return the original cli arguments
     *
     * @return array<string, mixed>
     */
    public function getArguments(): array
    {
        return $this->input->getArguments();
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
        return (string)($this->getArguments()[$name] ?? $default);
    }

    /**
     * Return the piped in standard input
     *
     * @return string[]
     */
    public function getStandardInput(): array
    {
        if (empty($this->stdIn)) {
            $this->stdIn = explode(PHP_EOL, trim($this->input->getOption('input'), '"'));
        }
        return $this->stdIn;
    }

    /**
     * {@inheritDoc}
     */
    public function isInteractive()
    {
        return $this->input->isInteractive();
    }

    /**
     * {@inheritDoc}
     */
    public function isVerbose()
    {
        return $this->output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE;
    }

    /**
     * {@inheritDoc}
     */
    public function isVeryVerbose()
    {
        return $this->output->getVerbosity() >= OutputInterface::VERBOSITY_VERY_VERBOSE;
    }

    /**
     * {@inheritDoc}
     */
    public function isDebug()
    {
        return $this->output->getVerbosity() >= OutputInterface::VERBOSITY_DEBUG;
    }

    /**
     * {@inheritDoc}
     */
    public function write($messages, $newline = true, $verbosity = self::NORMAL)
    {
        $this->doWrite($messages, $newline, false, $verbosity);
    }

    /**
     * {@inheritDoc}
     */
    public function writeError($messages, $newline = true, $verbosity = self::NORMAL)
    {
        $this->doWrite($messages, $newline, true, $verbosity);
    }

    /**
     * Write to the appropriate user output
     *
     * @param  array<string>|string $messages
     * @param  bool                 $newline
     * @param  bool                 $stderr
     * @param  int                  $verbosity
     * @return void
     */
    private function doWrite($messages, $newline, $stderr, $verbosity)
    {
        $sfVerbosity = $this->verbosityMap[$verbosity];
        if ($sfVerbosity > $this->output->getVerbosity()) {
            return;
        }

        $this->getOutputToWriteTo($stderr)->write($messages, $newline, $sfVerbosity);
    }

    /**
     * {@inheritDoc}
     */
    public function ask($question, $default = null)
    {
        if ($this->helperSet === null) {
            throw new RuntimeException('You must set the helperSet before asking');
        }
        /** @var \Symfony\Component\Console\Helper\QuestionHelper $helper */
        $helper   = $this->helperSet->get('question');
        $question = new Question($question, $default);

        return $helper->ask($this->input, $this->getOutputToWriteTo(), $question);
    }

    /**
     * {@inheritDoc}
     */
    public function askConfirmation($question, $default = true)
    {
        if ($this->helperSet === null) {
            throw new RuntimeException('You must set the helperSet before asking');
        }
        /** @var \Symfony\Component\Console\Helper\QuestionHelper $helper */
        $helper   = $this->helperSet->get('question');
        $question = new ConfirmationQuestion($question, $default);

        return IOUtil::answerToBool($helper->ask($this->input, $this->getOutputToWriteTo(), $question));
    }

    /**
     * {@inheritDoc}
     */
    public function askAndValidate($question, $validator, $attempts = null, $default = null)
    {
        if ($this->helperSet === null) {
            throw new RuntimeException('You must set the helperSet before asking');
        }
        /** @var \Symfony\Component\Console\Helper\QuestionHelper $helper */
        $helper   = $this->helperSet->get('question');
        $question = new Question($question, $default);
        $question->setValidator($validator);
        $question->setMaxAttempts($attempts);

        return $helper->ask($this->input, $this->getOutputToWriteTo(), $question);
    }

    /**
     * Return the output to write to
     *
     * @param  bool $stdErr
     * @return \Symfony\Component\Console\Output\OutputInterface
     */
    private function getOutputToWriteTo($stdErr = false)
    {
        if ($stdErr && $this->output instanceof ConsoleOutputInterface) {
            return $this->output->getErrorOutput();
        }

        return $this->output;
    }
}
