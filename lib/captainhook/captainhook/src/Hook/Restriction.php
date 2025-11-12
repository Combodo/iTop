<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptainHook\App\Hook;

use CaptainHook\App\Hooks;

/**
 * Class PHP
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.0.0
 */
final class Restriction
{
    /**
     * List of applicable hooks
     *
     * Map HookName => isApplicable to not have to scan the whole array for hook names.
     *
     * @var array<string, bool>
     */
    private array $applicableHooks;

    /**
     * Restriction constructor
     *
     * @param string ...$hooks
     */
    public function __construct(string ...$hooks)
    {
        foreach ($hooks as $hook) {
            $this->allowHook($hook);
        }
    }

    /**
     * Add an allowed hook to the restriction
     *
     * If the Restrictions is already applicable it returns itself
     * if not the current instance get cloned and the new hook is
     * added to the applicable hook list.
     *
     * @param  string $hook
     * @return \CaptainHook\App\Hook\Restriction
     */
    public function with(string $hook): self
    {
        if ($this->isApplicableFor($hook)) {
            return $this;
        }

        $restriction = clone ($this);
        $restriction->allowHook($hook);
        return $restriction;
    }

    /**
     * Check if a given hook is applicable for this restriction
     *
     * @param  string $hook
     * @return bool
     */
    public function isApplicableFor(string $hook): bool
    {
        return $this->applicableHooks[$hook] ?? false;
    }

    /**
     * Add hook to allow execution, invalid hooks will be ignored
     *
     * @param string $hook
     */
    private function allowHook(string $hook): void
    {
        if (Util::isValid($hook)) {
            $this->applicableHooks[$hook] = true;
            // also allow all native hook if a virtual hook is used
            foreach (Hooks::getNativeHooksForVirtualHook($hook) as $nativeHook) {
                $this->applicableHooks[$nativeHook] = true;
            }
        }
    }

    /**
     * Create restriction from array
     *
     * @param  array<string> $hooks
     * @return \CaptainHook\App\Hook\Restriction
     */
    public static function fromArray(array $hooks): Restriction
    {
        return new self(...$hooks);
    }

    /**
     * Create restriction from string
     *
     * @param  string $hook
     * @return \CaptainHook\App\Hook\Restriction
     */
    public static function fromString(string $hook): self
    {
        return new self($hook);
    }

    /**
     * Create empty restriction
     *
     * @return \CaptainHook\App\Hook\Restriction
     */
    public static function empty(): self
    {
        return new self(...[]);
    }
}
