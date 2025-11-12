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

use CaptainHook\App\CH;
use CaptainHook\App\Console\Command as Cmd;
use CaptainHook\App\Console\Runtime\Resolver;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Application
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
class Application extends SymfonyApplication
{
    /**
     * Path to captainhook binary
     *
     * @var string
     */
    protected string $executable;

    /**
     * Cli constructor.
     *
     * @param string $executable
     */
    public function __construct(string $executable)
    {
        $this->executable = $executable;

        parent::__construct('CaptainHook', CH::VERSION);

        $this->setDefaultCommand('list');
        $this->silenceXDebug();
    }

    /**
     * Make sure the list command is run on default `-h|--help` executions
     *
     * @param  \Symfony\Component\Console\Input\InputInterface   $input
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     * @throws \Symfony\Component\Console\Exception\ExceptionInterface
     * @throws \Throwable
     */
    public function doRun(InputInterface $input, OutputInterface $output): int
    {
        if ($this->isHelpWithoutCommand($input)) {
            // Run the `list` command not `list --help`
            return $this->find('list')->run($input, $output);
        }
        return parent::doRun($input, $output);
    }

    /**
     * Initializes all the CaptainHook commands
     *
     * @return \Symfony\Component\Console\Command\Command[]
     */
    public function getDefaultCommands(): array
    {
        $resolver        = new Resolver($this->executable);
        $symfonyDefaults = parent::getDefaultCommands();

        return array_merge(
            array_slice($symfonyDefaults, 0, 2),
            [
                new Cmd\Install($resolver),
                new Cmd\Uninstall($resolver),
                new Cmd\Configuration($resolver),
                new Cmd\Info($resolver),
                new Cmd\Add($resolver),
                new Cmd\Disable($resolver),
                new Cmd\Enable($resolver),
                new Cmd\Hook\CommitMsg($resolver),
                new Cmd\Hook\PostCheckout($resolver),
                new Cmd\Hook\PostCommit($resolver),
                new Cmd\Hook\PostMerge($resolver),
                new Cmd\Hook\PostRewrite($resolver),
                new Cmd\Hook\PreCommit($resolver),
                new Cmd\Hook\PrepareCommitMsg($resolver),
                new Cmd\Hook\PrePush($resolver),
            ]
        );
    }

    /**
     * Append release date to version output
     *
     * @return string
     */
    public function getLongVersion(): string
    {
        return sprintf(
            '<info>%s</info> version <comment>%s</comment> %s <fg=blue>#StandWith</><fg=yellow>Ukraine</>',
            $this->getName(),
            $this->getVersion(),
            CH::RELEASE_DATE
        );
    }

    /**
     * Make sure X-Debug does not interfere with the exception handling
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    private function silenceXDebug(): void
    {
        if (function_exists('ini_set') && extension_loaded('xdebug')) {
            ini_set('xdebug.show_exception_trace', '0');
            ini_set('xdebug.scream', '0');
        }
    }

    /**
     * Checks if the --help is called without any sub command
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @return bool
     */
    private function isHelpWithoutCommand(InputInterface $input): bool
    {
        return $input->hasParameterOption(['--help', '-h'], true) && !$input->getFirstArgument();
    }
}
