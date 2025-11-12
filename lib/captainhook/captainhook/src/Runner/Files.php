<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptainHook\App\Runner;

use CaptainHook\App\Exception;
use CaptainHook\App\Hook\Util as HookUtil;
use CaptainHook\App\Hooks;
use CaptainHook\App\Runner\Hook\Arg;
use RuntimeException;
use SebastianFeldmann\Camino\Check;

/**
 * Class HookMover
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.11.0
 */
abstract class Files extends RepositoryAware
{
    /**
     * Handle hooks brute force
     *
     * @var bool
     */
    protected bool $force = false;

    /**
     * Path where the existing hooks should be moved to
     *
     * @var string
     */
    protected string $moveExistingTo = '';

    /**
     * Hook(s) that should be handled.
     *
     * @var array<int, string>
     */
    protected array $hooksToHandle;

    /**
     * @param  bool $force
     * @return static
     */
    public function setForce(bool $force): self
    {
        $this->force = $force;
        return $this;
    }

    /**
     * Set the path where the current hooks should be moved to
     *
     * @param  string $backup
     * @return static
     */
    public function setMoveExistingTo(string $backup): static
    {
        $this->moveExistingTo = $backup;
        return $this;
    }

    /**
     * Limit uninstall to s specific hook
     *
     * @param  string $hook
     * @return static
     * @throws \CaptainHook\App\Exception\InvalidHookName
     */
    public function setHook(string $hook): self
    {
        $arg = new Arg(
            $hook,
            static function (string $hook): bool {
                return !HookUtil::isInstallable($hook);
            }
        );

        $this->hooksToHandle = $arg->hooks();
        return $this;
    }

    /**
     * Return list of hooks to handle
     *
     * [
     *   string    => bool
     *   HOOK_NAME => ASK_USER_TO_CONFIRM_INSTALL
     * ]
     *
     * @return array<string, bool>
     */
    protected function getHooksToHandle(): array
    {
        // if specific hooks are set, the user has actively chosen it, so don't ask for permission anymore
        // if all hooks get installed ask for permission
        return !empty($this->hooksToHandle)
            ? array_map(fn($hook) => false, array_flip($this->hooksToHandle))
            : array_map(fn($hook) => true, Hooks::nativeHooks());
    }

    /**
     * If a path to incorporate the existing hook is set we should incorporate existing hooks
     *
     * @return bool
     */
    protected function shouldHookBeMoved(): bool
    {
        return !empty($this->moveExistingTo);
    }

    /**
     * Move the existing hook to the configured location
     *
     * @param string $hook
     */
    protected function backupHook(string $hook): void
    {
        // no hook to move just exit
        if (!$this->repository->hookExists($hook)) {
            return;
        }

        $hookFileOrig   = $this->repository->getHooksDir() . DIRECTORY_SEPARATOR . $hook;
        $hookCmd        = rtrim($this->moveExistingTo, '/\\') . DIRECTORY_SEPARATOR . $hook;
        $hookCmdArgs    = $hookCmd . Hooks::getOriginalHookArguments($hook);
        $hookFileTarget = !Check::isAbsolutePath($this->moveExistingTo)
                        ? dirname($this->config->getPath()) . DIRECTORY_SEPARATOR . $hookCmd
                        : $hookCmd;

        $this->moveExistingHook($hookFileOrig, $hookFileTarget);

        $this->io->write(
            [
                '  Moved existing ' . $hook . ' hook to ' . $hookCmd,
                '  Add <comment>\'' . $hookCmdArgs . '\'</comment> to your '
                . $hook . ' configuration to execute it.'
            ]
        );
    }

    /**
     * If the hook exists the user has to confirm the action
     *
     * @param  string $hook The name of the hook to check
     * @return bool
     */
    protected function needConfirmation(string $hook): bool
    {
        return $this->repository->hookExists($hook) && !$this->force;
    }

    /**
     * Move the existing hook script to the new location
     *
     * @param  string $originalLocation
     * @param  string $newLocation
     * @return void
     * @throws \RuntimeException
     */
    protected function moveExistingHook(string $originalLocation, string $newLocation): void
    {
        $dir = dirname($newLocation);
        // make sure the target directory isn't a file
        if (file_exists($dir) && !is_dir($dir)) {
            throw new RuntimeException($dir . ' is not a directory');
        }
        // create the directory if it does not exist
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        // move the hook into the target directory
        rename($originalLocation, $newLocation);
    }
}
