<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\Message\Action;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Message\Rule;
use CaptainHook\App\Hook\Message\RuleBook;
use Exception;
use RuntimeException;
use SebastianFeldmann\Git\Repository;

/**
 * Class Rules
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
class Rules extends Book
{
    /**
     * Execute the configured action
     *
     * @param  \CaptainHook\App\Config           $config
     * @param  \CaptainHook\App\Console\IO       $io
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @param  \CaptainHook\App\Config\Action    $action
     * @return void
     * @throws \Exception
     */
    public function execute(Config $config, IO $io, Repository $repository, Config\Action $action): void
    {
        $rules = $action->getOptions()->getAll();
        $book  = new RuleBook();
        foreach ($rules as $rule) {
            if (is_string($rule)) {
                $book->addRule($this->createRule($rule));
                continue;
            }
            $book->addRule($this->createRuleFromConfig($rule));
        }
        $this->validate($book, $repository, $io);
    }

    /**
     * Create a new rule
     *
     * @param  string $class
     * @param  array<string> $args
     * @return \CaptainHook\App\Hook\Message\Rule
     * @throws \Exception
     */
    protected function createRule(string $class, array $args = []): Rule
    {
        // make sure the class is available
        if (!class_exists($class)) {
            throw new Exception('Unknown rule: ' . $class);
        }

        $rule = empty($args) ? new $class() : new $class(...$args);

        // make sure the class implements the Rule interface
        if (!$rule instanceof Rule) {
            throw new Exception('Class \'' . $class . '\' must implement the Rule interface');
        }

        return $rule;
    }

    /**
     * Create a rule from a argument containing configuration
     *
     * @param  array<int, mixed> $config
     * @return \CaptainHook\App\Hook\Message\Rule
     * @throws \Exception
     */
    private function createRuleFromConfig(array $config): Rule
    {
        if (!is_string($config[0]) || !is_array($config[1])) {
            throw new RuntimeException('Invalid rule configuration');
        }
        return $this->createRule($config[0], $config[1]);
    }
}
