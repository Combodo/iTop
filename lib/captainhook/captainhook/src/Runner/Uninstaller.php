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

use CaptainHook\App\Console\IOUtil;
use RuntimeException;

/**
 * Class Uninstaller
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.17.0
 */
class Uninstaller extends Files
{
    /**
     * Remove only disabled hooks
     *
     * @var bool
     */
    private bool $onlyDisabled = false;

    /**
     * Execute installation
     *
     * @return void
     */
    public function run(): void
    {
        foreach ($this->getHooksToUninstall() as $hook => $ask) {
            $this->uninstallHook($hook, ($ask && !$this->force));
        }
    }

    /**
     * Disabled only setter
     *
     * @param  bool $disabledOnly
     * @return \CaptainHook\App\Runner\Uninstaller
     */
    public function setOnlyDisabled(bool $disabledOnly): Uninstaller
    {
        if ($disabledOnly && !empty($this->hooksToHandle)) {
            throw new RuntimeException('choose --only-disabled or specific hooks');
        }
        $this->onlyDisabled = $disabledOnly;
        return $this;
    }

    /**
     * Returns the list of hooks to uninstall
     *
     * [
     *   string    => bool
     *   HOOK_NAME => ASK_USER_TO_CONFIRM_INSTALL
     * ]
     *
     * @return array<string, bool>
     */
    private function getHooksToUninstall(): array
    {
        $hooks = $this->getHooksToHandle();

        // if only disabled hooks should be removed, remove enabled ones from $hooks array
        if ($this->onlyDisabled) {
            $hooks = array_filter(
                $hooks,
                fn(string $key): bool => !$this->config->isHookEnabled($key),
                ARRAY_FILTER_USE_KEY
            );
        }
        return $hooks;
    }

    /**
     * Install given hook
     *
     * @param string $hook
     * @param bool   $ask
     */
    private function uninstallHook(string $hook, bool $ask): void
    {
        if (!$this->repository->hookExists($hook)) {
            $this->io->write('<comment>' . $hook . '</comment> not installed');
            return;
        }

        $doIt = true;
        if ($ask) {
            $answer = $this->io->ask('Remove <info>' . $hook . '</info> hook? <comment>[y,n]</comment> ', 'y');
            $doIt   = IOUtil::answerToBool($answer);
        }

        if ($doIt) {
            if ($this->shouldHookBeMoved()) {
                $this->backupHook($hook);
                return;
            }
            unlink($this->repository->getHooksDir() . DIRECTORY_SEPARATOR . $hook);
            $this->io->write(IOUtil::PREFIX_OK . ' <info>' . $hook . '</info> removed');
            return;
        }
        $this->io->write(IOUtil::PREFIX_FAIL . ' <comment>' . $hook . '</comment> skipped');
    }
}
