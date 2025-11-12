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

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Console\IOUtil;
use CaptainHook\App\Hook\Template;
use CaptainHook\App\Storage\File;
use RuntimeException;
use SebastianFeldmann\Camino\Check;
use SebastianFeldmann\Git\Repository;

/**
 * Class Installer
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
class Installer extends Files
{
    /**
     * Don't overwrite existing hooks
     *
     * @var bool
     */
    private bool $skipExisting = false;

    /**
     * Install only enabled hooks
     *
     * @var bool
     */
    private bool $onlyEnabled = false;

    /**
     * Hook template
     *
     * @var \CaptainHook\App\Hook\Template
     */
    private Template $template;

    /**
     * HookHandler constructor.
     *
     * @param \CaptainHook\App\Console\IO       $io
     * @param \CaptainHook\App\Config           $config
     * @param \SebastianFeldmann\Git\Repository $repository
     * @param \CaptainHook\App\Hook\Template    $template
     */
    public function __construct(IO $io, Config $config, Repository $repository, Template $template)
    {
        $this->template = $template;
        parent::__construct($io, $config, $repository);
    }

    /**
     * @param  bool $skip
     * @return \CaptainHook\App\Runner\Installer
     */
    public function setSkipExisting(bool $skip): Installer
    {
        if ($skip && !empty($this->moveExistingTo)) {
            throw new RuntimeException('choose --move-existing-to or --skip-existing');
        }
        $this->skipExisting = $skip;
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
        if (!empty($backup) && $this->skipExisting) {
            throw new RuntimeException('choose --skip-existing or --move-existing-to');
        }
        return parent::setMoveExistingTo($backup);
    }

    /**
     * @param bool $onlyEnabled
     * @return \CaptainHook\App\Runner\Installer
     */
    public function setOnlyEnabled(bool $onlyEnabled): Installer
    {
        if ($onlyEnabled && !empty($this->hooksToHandle)) {
            throw new RuntimeException('choose --only-enabled or specific hooks');
        }

        $this->onlyEnabled = $onlyEnabled;
        return $this;
    }

    /**
     * Hook setter
     *
     * @param  string $hook
     * @return \CaptainHook\App\Runner\Installer
     * @throws \CaptainHook\App\Exception\InvalidHookName
     */
    public function setHook(string $hook): Installer
    {
        if (empty($hook)) {
            return $this;
        }

        if ($this->onlyEnabled) {
            throw new RuntimeException('choose --only-enabled or specific hooks');
        }

        return parent::setHook($hook);
    }

    /**
     * Execute installation
     *
     * @return void
     */
    public function run(): void
    {
        if (!$this->shouldRun()) {
            return;
        }
        foreach ($this->getHooksToInstall() as $hook => $ask) {
            $this->installHook($hook, ($ask && !$this->force));
        }
    }

    /**
     * Return list of hooks to install
     *
     * <code>
     * [
     *   string    => bool
     *   HOOK_NAME => ASK_USER_TO_CONFIRM_INSTALL
     * ]
     * </code>
     *
     * @return array<string, bool>
     */
    public function getHooksToInstall(): array
    {
        $hooks = $this->getHooksToHandle();
        // if only enabled hooks should be installed, remove disabled ones from the $hooks array
        if ($this->onlyEnabled) {
            $hooks = array_filter(
                $hooks,
                fn(string $key): bool => $this->config->isHookEnabled($key),
                ARRAY_FILTER_USE_KEY
            );
        }
        // make sure to ask for every remaining hook if it should be installed
        return $hooks;
    }

    /**
     * Install given hook
     *
     * @param string $hook
     * @param bool   $ask
     */
    private function installHook(string $hook, bool $ask): void
    {
        if ($this->shouldHookBeSkipped($hook)) {
            $hint = $this->io->isDebug() ? ', remove the --skip-existing option to overwrite.' : '';
            $this->io->write(
                IOUtil::PREFIX_FAIL . ' <comment>' . $hook . '</comment> exists' . $hint,
                true,
                IO::VERBOSE
            );
            return;
        }

        $doIt = true;
        if ($ask) {
            $answer = $this->io->ask('Install <comment>' . $hook . '</comment> hook? <comment>[Y,n]</comment> ', 'y');
            $doIt   = IOUtil::answerToBool($answer);
        }

        if ($doIt) {
            if ($this->shouldHookBeMoved()) {
                $this->backupHook($hook);
            }
            $this->writeHookFile($hook);
        }
    }

    /**
     * Check if the hook is installed and should be skipped
     *
     * @param  string $hook
     * @return bool
     */
    private function shouldHookBeSkipped(string $hook): bool
    {
        return $this->skipExisting && $this->repository->hookExists($hook);
    }

    /**
     * Write given hook to .git/hooks directory
     *
     * @param  string $hook
     * @return void
     */
    private function writeHookFile(string $hook): void
    {
        $hooksDir = $this->repository->getHooksDir();
        $hookFile = $hooksDir . DIRECTORY_SEPARATOR . $hook;
        $doIt     = true;

        // if a hook is configured and no force option is set,
        // ask the user if overwriting the hook is ok
        if ($this->needConfirmation($hook)) {
            $ans  = $this->io->ask(
                'The <comment>' . $hook . '</comment> hook exists! Overwrite? <comment>[y,N]</comment> ',
                'n'
            );
            $doIt = IOUtil::answerToBool($ans);
        }

        if ($doIt) {
            $code = $this->getHookSourceCode($hook);
            $file = new File($hookFile);
            $this->checkForBrokenSymlink($file);
            $file->write($code);
            chmod($hookFile, 0755);
            $this->io->write(IOUtil::PREFIX_OK . ' <comment>' . $hook . '</comment> installed');
            return;
        }
        $this->io->write(IOUtil::PREFIX_FAIL . ' <comment>' . $hook . '</comment> skipped');
    }

    /**
     * Return the source code for a given hook script
     *
     * @param  string $hook
     * @return string
     */
    private function getHookSourceCode(string $hook): string
    {
        return $this->template->getCode($hook);
    }

    /**
     * Checks if the provided file is a broken symbolic link
     *
     * @param  File $file The File object representing the file.
     * @return void
     * @throws RuntimeException If the file is determined to be a broken symbolic link.
     */
    protected function checkForBrokenSymlink(File $file): void
    {
        if ($file->isLink()) {
            $target = $file->linkTarget();
            if (!Check::isAbsolutePath($target)) {
                $target = dirname($file->getPath()) . DIRECTORY_SEPARATOR . $target;
            }
            if (!is_dir(dirname($target))) {
                throw new RuntimeException(
                    'The hook at \'' . $file->getPath() . '\' is a broken symbolic link. ' . PHP_EOL .
                    'Please remove the symbolic link and try again.'
                );
            }
        }
    }

    /**
     * Check for problems
     *
     * @return bool
     */
    private function shouldRun(): bool
    {
        $hooksDir = $this->repository->getHooksDir();
        // check nix and win systems black holes
        if ($hooksDir === '/dev/null' || $hooksDir === 'NUL') {
            $this->io->write('<fg=red>can\'t install hooks into hooksPath: /dev/null</>');
            return false;
        }
        return true;
    }
}
