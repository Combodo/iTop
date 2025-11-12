<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Runner\Config\Setup;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IOUtil;
use CaptainHook\App\Hooks;
use CaptainHook\App\Runner\Config\Setup;

/**
 * Class Express
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 2.2.0
 */
class Express extends Guided implements Setup
{
    /**
     * Setup hooks by asking some basic questions
     *
     * @param  \CaptainHook\App\Config $config
     * @throws \Exception
     */
    public function configureHooks(Config $config): void
    {
        $msgHook = $config->getHookConfig(Hooks::COMMIT_MSG);
        $preHook = $config->getHookConfig(Hooks::PRE_COMMIT);
        $msgHook->setEnabled(true);
        $preHook->setEnabled(true);

        $this->setupMessageHook($msgHook);
        $this->setupPHPLintingHook($preHook);
        $this->setupPHPUnitHook($preHook);
        $this->setupPHPCodesnifferHook($preHook);
    }

    /**
     * Set up the commit message hook
     *
     * @param  \CaptainHook\App\Config\Hook $config
     * @return void
     * @throws \Exception
     */
    private function setupMessageHook(Config\Hook $config): void
    {
        $answer = $this->io->ask(
            '  <info>Do you want to validate your commit messages?</info> <comment>[y,n]</comment> ',
            'n'
        );

        if (IOUtil::answerToBool($answer)) {
            $call    = '\\CaptainHook' . '\\App' . '\\Hook\\Message\\Action\\Beams';
            $options = ['subjectLength' => 50, 'bodyLineLength' => 72];
            $config->addAction(new Config\Action($call, $options));
        }
    }

    /**
     * Set up the linting hook
     *
     * @param  \CaptainHook\App\Config\Hook $config
     * @return void
     * @throws \Exception
     */
    private function setupPHPLintingHook(Config\Hook $config): void
    {
        $answer = $this->io->ask(
            '  <info>Do you want to check your files for syntax errors?</info> <comment>[y,n]</comment> ',
            'n'
        );

        if (IOUtil::answerToBool($answer)) {
            $call    = '\\CaptainHook' . '\\App' . '\\Hook\\PHP\\Action\\Linting';
            $config->addAction(new Config\Action($call));
        }
    }

    /**
     * Setup the phpunit hook
     *
     * @param  \CaptainHook\App\Config\Hook $config
     * @return void
     * @throws \Exception
     */
    private function setupPHPUnitHook(Config\Hook $config): void
    {
        $answer = $this->io->ask(
            '  <info>Do you want to run phpunit before committing?</info> <comment>[y,n]</comment> ',
            'n'
        );

        if (IOUtil::answerToBool($answer)) {
            $call = $this->io->ask(
                '  <info>Enter the phpunit command you want to execute.</info> <comment>[phpunit]</comment> ',
                'phpunit'
            );
            $config->addAction(new Config\Action($call));
        }
    }

    /**
     * Setup the code sniffer hook
     *
     * @param  \CaptainHook\App\Config\Hook $config
     * @return void
     * @throws \Exception
     */
    private function setupPHPCodesnifferHook(Config\Hook $config): void
    {
        $answer = $this->io->ask(
            '  <info>Do you want to run phpcs before committing?</info> <comment>[y,n]</comment> ',
            'n'
        );

        if (IOUtil::answerToBool($answer)) {
            $call    = $this->io->ask(
                '  <info>Enter the phpcs command you want to execute.</info> '
                . '<comment>[phpcs --standard=psr2 src]</comment> ',
                'phpcs --standard=psr2 src'
            );
            $config->addAction(new Config\Action($call));
        }
    }
}
