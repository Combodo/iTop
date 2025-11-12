<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Runner\Hook;

use CaptainHook\App\Exception\InvalidHookName;

/**
 * Hook argument for lots of commands
 *
 *  - install pre-push,pre-commit
 *  - info commit-message
 */
class Arg
{
    /**
     * List of hooks
     *
     * @var array<int, string>
     */
    private array $hooks = [];

    /**
     * @param  string   $hook
     * @param  callable $hookValidation
     * @throws \CaptainHook\App\Exception\InvalidHookName
     */
    public function __construct(string $hook, callable $hookValidation)
    {
        if (empty($hook)) {
            return;
        }

        /** @var array<string> $hooks */
        $hooks = explode(',', $hook);
        $hooks = array_map('trim', $hooks);

        if (!empty(($invalidHooks = array_filter($hooks, $hookValidation)))) {
            throw new InvalidHookName(
                'Invalid hook name \'' . implode('\', \'', $invalidHooks) . '\''
            );
        }
        $this->hooks = $hooks;
    }

    /**
     * Return the list of hooks provided as an argument
     *
     * @return array<int, string>
     */
    public function hooks(): array
    {
        return $this->hooks;
    }
}
