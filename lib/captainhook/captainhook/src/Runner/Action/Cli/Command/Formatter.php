<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Runner\Action\Cli\Command;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Hooks;
use CaptainHook\App\Runner\Action\Cli\Command\Placeholder\Arg;
use SebastianFeldmann\Git\Repository;

/**
 * Class Formatter
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.0.0
 */
class Formatter
{
    /**
     * Cache storage for computed placeholder values
     *
     * @var array<string, string>
     */
    private static array $cache = [];

    /**
     * Input output handler
     *
     * @var \CaptainHook\App\Console\IO
     */
    private IO $io;

    /**
     * CaptainHook configuration
     *
     * @var \CaptainHook\App\Config
     */
    private Config $config;

    /**
     * List of available placeholders
     *
     * @var array<string, string>
     */
    private static array $placeholders = [
        'arg'           => Placeholder\Arg::class,
        'config'        => Placeholder\Config::class,
        'env'           => Placeholder\Env::class,
        'staged_files'  => Placeholder\StagedFiles::class,
        'changed_files' => Placeholder\ChangedFiles::class,
        'branch_files'  => Placeholder\BranchFiles::class,
        'stdin'         => Placeholder\StdIn::class,
    ];

    /**
     * Previously used placeholders to replace arguments
     *
     * @var array<string, string>
     */
    private static array $legacyPlaceHolder = [
        'FILE'         => Hooks::ARG_MESSAGE_FILE,
        'GITCOMMAND'   => Hooks::ARG_GIT_COMMAND,
        'HASH'         => Hooks::ARG_HASH,
        'MODE'         => Hooks::ARG_MODE,
        'NEWHEAD'      => Hooks::ARG_NEW_HEAD,
        'PREVIOUSHEAD' => Hooks::ARG_PREVIOUS_HEAD,
        'SQUASH'       => Hooks::ARG_SQUASH,
        'TARGET'       => Hooks::ARG_TARGET,
        'URL'          => Hooks::ARG_URL,
    ];

    /**
     * Git repository
     *
     * @var \SebastianFeldmann\Git\Repository
     */
    private Repository $repository;

    /**
     * Formatter constructor
     *
     * @param \CaptainHook\App\Console\IO       $io
     * @param \CaptainHook\App\Config           $config
     * @param \SebastianFeldmann\Git\Repository $repository
     */
    public function __construct(IO $io, Config $config, Repository $repository)
    {
        $this->io         = $io;
        $this->config     = $config;
        $this->repository = $repository;
    }

    /**
     * Replaces all placeholders in a cli command
     *
     * @param  string $command
     * @return string
     */
    public function format(string $command): string
    {
        // find all replacements {SOMETHING}
        $placeholders = $this->findAllPlaceholders($command);
        foreach ($placeholders as $placeholder) {
            $command = str_replace('{$' . $placeholder . '}', $this->replace($placeholder), $command);
        }

        return $command;
    }

    /**
     * Returns al list of all placeholders
     *
     * @param  string $command
     * @return array<int, string>
     */
    private function findAllPlaceholders(string $command): array
    {
        $placeholders = [];
        $matches      = [];

        if (preg_match_all('#{\$([a-z_]+(\|[a-z\-]+:.*)?)}#iU', $command, $matches)) {
            foreach ($matches[1] as $match) {
                $placeholders[] = $match;
            }
        }

        return $placeholders;
    }

    /**
     * Return a given placeholder value
     *
     * @param  string $placeholder
     * @return string
     */
    private function replace(string $placeholder): string
    {
        // if the placeholder references an original hook argument set up the real placeholder
        // {$FILE} => ARG|value-of:message-file
        if (array_key_exists($placeholder, self::$legacyPlaceHolder)) {
            $argument = self::$legacyPlaceHolder[$placeholder];
            $placeholder = 'ARG|value-of:' . Arg::toPlaceholder($argument);
        }
        return $this->computedPlaceholder($placeholder);
    }

    /**
     * Compute the placeholder value
     *
     * @param  string $rawPlaceholder Placeholder syntax {$NAME[|OPTION:VALUE]...}
     * @return string
     */
    private function computedPlaceholder(string $rawPlaceholder): string
    {
        // to not compute the same placeholder multiple times
        if (!$this->isCached($rawPlaceholder)) {
            // extract placeholder name and options
            $parts       = explode('|', $rawPlaceholder);
            $placeholder = strtolower($parts[0]);
            $options     = $this->parseOptions(array_slice($parts, 1));

            if (!$this->isPlaceholderValid($placeholder)) {
                return '';
            }

            $this->io->write('  <fg=cyan>Placeholder: ' . $placeholder . '</>', true, IO::VERBOSE);
            $processor = $this->createPlaceholder($placeholder);
            $this->cache($rawPlaceholder, $processor->replacement($options));
        }
        return $this->cached($rawPlaceholder);
    }

    /**
     * Placeholder factory method
     *
     * @param  string $placeholder
     * @return \CaptainHook\App\Runner\Action\Cli\Command\Placeholder
     */
    private function createPlaceholder(string $placeholder): Placeholder
    {
        /** @var class-string<\CaptainHook\App\Runner\Action\Cli\Command\Placeholder> $class */
        $class = self::$placeholders[$placeholder];
        return new $class($this->io, $this->config, $this->repository);
    }

    /**
     * Checks if a placeholder is available for computation
     *
     * @param  string $placeholder
     * @return bool
     */
    private function isPlaceholderValid(string $placeholder): bool
    {
        return isset(self::$placeholders[$placeholder]);
    }

    /**
     * Parse options from ["name:'value'", "name:'value'"] to ["name" => "value", "name" => "value"]
     *
     * @param  array<int, string> $raw
     * @return array<string, string>
     */
    private function parseOptions(array $raw): array
    {
        $options = [];
        foreach ($raw as $rawOption) {
            $matches = [];
            if (preg_match('#^([a-z_\-]+):(.*)?$#i', $rawOption, $matches)) {
                $options[strtolower($matches[1])] = $matches[2] ?? '';
            }
        }
        return $options;
    }

    /**
     * Check if a placeholder is cached already
     *
     * @param  string $placeholder
     * @return bool
     */
    private static function isCached(string $placeholder): bool
    {
        return isset(self::$cache[$placeholder]);
    }

    /**
     * Cache a given placeholder value
     *
     * @param string $placeholder
     * @param string $replacement
     */
    private static function cache(string $placeholder, string $replacement): void
    {
        self::$cache[$placeholder] = $replacement;
    }

    /**
     * Return cached value for given placeholder
     *
     * @param  string $placeholder
     * @return string
     */
    private static function cached(string $placeholder): string
    {
        return self::$cache[$placeholder] ?? '';
    }
}
