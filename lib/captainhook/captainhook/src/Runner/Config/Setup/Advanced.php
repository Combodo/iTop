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
use CaptainHook\App\Hook\Util as HookUtil;
use CaptainHook\App\Runner\Util as RunnerUtil;
use CaptainHook\App\Runner\Config\Setup;

/**
 * Class Advanced
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 2.2.0
 */
class Advanced extends Guided implements Setup
{
    /**
     * Setup hook configurations by asking some questions
     *
     * @param  \CaptainHook\App\Config $config
     * @return void
     * @throws \Exception
     */
    public function configureHooks(Config $config): void
    {
        foreach (HookUtil::getHooks() as $hook) {
            $this->configureHook($config->getHookConfig($hook), $hook);
        }
    }

    /**
     * Configure a hook by asking some questions
     *
     * @param  \CaptainHook\App\Config\Hook $config
     * @param  string                       $name
     * @return void
     * @throws \Exception
     */
    public function configureHook(Config\Hook $config, string $name): void
    {
        $answer = $this->io->ask('  <info>Enable \'' . $name . '\' hook?</info> <comment>[y,n]</comment> ', 'n');
        $enable = IOUtil::answerToBool($answer);

        $config->setEnabled($enable);

        if ($enable) {
            $addAction = $this->io->ask('  <info>Add a validation action?</info> <comment>[y,n]</comment> ', 'n');

            while (IOUtil::answerToBool($addAction)) {
                $config->addAction($this->getActionConfig());
                // add another action?
                $addAction = $this->io->ask(
                    '  <info>Add another validation action?</info> <comment>[y,n]</comment> ',
                    'n'
                );
            }
        }
    }

    /**
     * Setup a action config with user input
     *
     * @return \CaptainHook\App\Config\Action
     * @throws \Exception
     */
    public function getActionConfig(): Config\Action
    {
        $call    = $this->io->ask('  <info>PHP class or shell command to execute?</info> ');
        $options = $this->getActionOptions(RunnerUtil::getExecType($call));

        return new Config\Action($call, $options);
    }

    /**
     * Ask the user for any action options
     *
     * @param  string $type
     * @return array<string, string>
     * @throws \Exception
     */
    public function getActionOptions(string $type): array
    {
        return 'php' === $type ? $this->getPHPActionOptions() : [];
    }

    /**
     * Get the php action options
     *
     * @return array<string, string>
     * @throws \Exception
     */
    protected function getPHPActionOptions(): array
    {
        $options = [];
        $addOption = $this->io->ask('  <info>Add a validator option?</info> <comment>[y,n]</comment> ', 'n');
        while (IOUtil::answerToBool($addOption)) {
            $options[] = $this->getPHPActionOption();
            // add another action?
            $addOption = $this->io->ask('  <info>Add another validator option?</info> <comment>[y,n]</comment> ', 'n');
        }
        return array_merge(...$options);
    }

    /**
     * Ask the user for a php action option
     *
     * @return array<string, string>
     * @throws \Exception
     */
    protected function getPHPActionOption(): array
    {
        $result = [];
        $answer = $this->io->askAndValidate(
            '  <info>Specify options key and value</info> <comment>[key:value]</comment> ',
            ['\\CaptainHook\\App\\Runner\\Config\\Setup\\Guided', 'isPHPActionOptionValid'],
            3,
            null
        );
        if (null !== $answer) {
            list($key, $value) = explode(':', $answer);
            $result = [$key => $value];
        }
        return $result;
    }
}
