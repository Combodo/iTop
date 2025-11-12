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

use CaptainHook\App\CH;
use CaptainHook\App\Config;
use CaptainHook\App\Console\Command;
use CaptainHook\App\Console\IOUtil;
use RuntimeException;
use SebastianFeldmann\Camino\Check;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConfigAware
 *
 * Base class for all commands that need to be aware of the CaptainHook configuration.
 *
 * @package CaptainHook\App
 */
abstract class ConfigAware extends Command
{
    /**
     * Set up the configuration command option
     *
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this->addOption(
            'configuration',
            'c',
            InputOption::VALUE_OPTIONAL,
            'Path to your captainhook.json configuration',
            './' . CH::CONFIG
        );
    }

    /**
     * Create a new Config object
     *
     * @param  \Symfony\Component\Console\Input\InputInterface $input
     * @param  bool                                            $failIfNotFound
     * @param  array<string>                                   $settings
     * @return \CaptainHook\App\Config
     * @throws \Exception
     */
    protected function createConfig(InputInterface $input, bool $failIfNotFound = false, array $settings = []): Config
    {
        $config = Config\Factory::create($this->getConfigPath($input), $this->fetchConfigSettings($input, $settings));
        if ($failIfNotFound && !$config->isLoadedFromFile()) {
            throw new RuntimeException(
                'Please create a captainhook configuration first' . PHP_EOL .
                'Run \'captainhook configure\'' . PHP_EOL .
                'If you have a configuration located elsewhere use the --configuration option'
            );
        }
        return $config;
    }

    /**
     * Return the given config path option
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @return string
     */
    private function getConfigPath(InputInterface $input): string
    {
        $path = IOUtil::argToString($input->getOption('configuration'));

        // if path not absolute
        if (!Check::isAbsolutePath($path)) {
            // try to guess the config location and
            // transform relative path to absolute path
            if (substr($path, 0, 2) === './') {
                return getcwd() . substr($path, 1);
            }
            return getcwd() . '/' . $path;
        }
        return $path;
    }

    /**
     * Return list of available options to overwrite the configuration settings
     *
     * @param  \Symfony\Component\Console\Input\InputInterface $input
     * @param  array<string>                                   $settingNames
     * @return array<string, string>
     */
    private function fetchConfigSettings(InputInterface $input, array $settingNames): array
    {
        $settings = [];
        foreach ($settingNames as $setting) {
            $value = IOUtil::argToString($input->getOption($setting));
            if (!empty($value)) {
                $settings[$setting] = $value;
            }
        }
        return $settings;
    }

    /**
     * Check which verbosity to use, either the cli option or the config file setting
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $out
     * @param \CaptainHook\App\Config                           $config
     * @return void
     */
    protected function determineVerbosity(OutputInterface $out, Config $config): void
    {
        $verbosity     = IOUtil::mapConfigVerbosity($config->getVerbosity());
        $cliVerbosity  = $out->getVerbosity();
        if ($cliVerbosity !== OutputInterface::VERBOSITY_NORMAL) {
            $verbosity = $cliVerbosity;
        }
        $out->setVerbosity($verbosity);
    }
}
