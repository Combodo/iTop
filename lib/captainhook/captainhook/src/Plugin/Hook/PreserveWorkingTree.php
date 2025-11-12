<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Plugin\Hook;

use CaptainHook\App\Config;
use CaptainHook\App\Hooks;
use CaptainHook\App\Plugin;
use CaptainHook\App\Runner;
use SebastianFeldmann\Git\Status\Path;
use Symfony\Component\Filesystem\Filesystem;

/**
 * PreserveWorkingTree runner plugin
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.9.0.
 */
class PreserveWorkingTree extends Base implements Plugin\Hook
{
    /**
     * The name of the environment variable used to indicate the post-checkout
     * hook should be skipped, to avoid recursion.
     */
    public const SKIP_POST_CHECKOUT_VAR = '_CH_PLUGIN_PRESERVE_WORKING_TREE_SKIP_POST_CHECKOUT';

    /**
     * Files marked in the index as "intent to add."
     *
     * @var Path[]
     */
    private $intentToAddFiles = [];

    /**
     * A file where unstaged changes are stored as a patch.
     *
     * @var string|null
     */
    private $unstagedPatchFile = null;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * PreserveWorkingTree constructor.
     *
     * @param Filesystem|null $filesystem
     */
    public function __construct(?Filesystem $filesystem = null)
    {
        $this->filesystem = $filesystem ?? new Filesystem();
    }

    public function beforeHook(Runner\Hook $hook): void
    {
        $this->clearIntentToAddFiles();
        $this->clearUnstagedChanges();

        if ($hook->getName() === Hooks::POST_CHECKOUT) {
            // If this environment variable is set and is `true`, then we want
            // to skip all actions configured by the post-checkout hook.
            $hook->shouldSkipActions(filter_var(
                getenv(self::SKIP_POST_CHECKOUT_VAR),
                FILTER_VALIDATE_BOOLEAN
            ));
        }
    }

    public function beforeAction(Runner\Hook $hook, Config\Action $action): void
    {
        // Do nothing.
    }

    public function afterAction(Runner\Hook $hook, Config\Action $action): void
    {
        // Do nothing.
    }

    public function afterHook(Runner\Hook $hook): void
    {
        $this->restoreUnstagedChanges();
        $this->restoreIntentToAddFiles();
    }

    /**
     * Find whether there are any files marked as intent-to-add, cache them, and
     * remove them from the index.
     *
     * @return void
     */
    private function clearIntentToAddFiles(): void
    {
        $status = $this->repository->getStatusOperator()->getWorkingTreeStatus();

        // Make sure we don't have something already set.
        $this->intentToAddFiles = [];

        foreach ($status as $path) {
            if ($path->isAddedInWorkingTree() === true) {
                $this->intentToAddFiles[] = $path;
            }
        }

        if (count($this->intentToAddFiles) === 0) {
            return;
        }

        $this->io->write('<info>Unstaged intent-to-add files detected.</info>');

        $this->repository->getIndexOperator()->removeFiles(
            array_map(function (Path $path): string {
                return $path->getPath();
            }, $this->intentToAddFiles),
            false,
            true
        );
    }

    /**
     * If we cached and removed from the index any files that were marked as
     * intent-to-add, restore them to the index.
     *
     * @return void
     */
    private function restoreIntentToAddFiles(): void
    {
        if (count($this->intentToAddFiles) === 0) {
            return;
        }

        $this->repository->getIndexOperator()->recordIntentToAddFiles(
            array_map(function (Path $path): string {
                return $path->getPath();
            }, $this->intentToAddFiles)
        );

        $this->io->write('<info>Restored intent-to-add files.</info>');

        $this->intentToAddFiles = [];
    }

    /**
     * Find whether we have any unstaged changes in the working tree, cache them,
     * and reset the working tree so we can continue processing the hook.
     *
     * @return void
     * @throws \Exception
     */
    private function clearUnstagedChanges(): void
    {
        $unstagedChanges = $this->repository->getDiffOperator()->getUnstagedPatch();

        // Make sure we don't already have something set.
        $this->unstagedPatchFile = null;

        if ($unstagedChanges === null) {
            return;
        }

        $patchFile = sys_get_temp_dir()
            . '/CaptainHook/patches/'
            . time() . '-' . bin2hex(random_bytes(4))
            . '.patch';

        $this->filesystem->dumpFile($patchFile, $unstagedChanges);

        $this->unstagedPatchFile = $patchFile;
        $this->restoreWorkingTree();
    }

    /**
     * If we have cached unstaged changes, restore them to the working tree.
     *
     * @return void
     */
    private function restoreUnstagedChanges(): void
    {
        if ($this->unstagedPatchFile === null) {
            return;
        }

        if (!$this->applyPatch($this->unstagedPatchFile)) {
            $this->io->writeError([
                '<error>Stashed changes conflicted with hook auto-fixes...</error>',
                '<comment>Rolling back fixes...</comment>',
            ]);

            $this->restoreWorkingTree();

            // At this point, the working tree should be pristine, so the
            // patch should cleanly apply.
            $this->applyPatch((string)$this->unstagedPatchFile);
        }

        $this->io->write("<info>Restored changes from {$this->unstagedPatchFile}.</info>");

        $this->unstagedPatchFile = null;
    }

    /**
     * Apply a patch file to the working tree.
     *
     * We'll try twice, the second time disabling Git's core.autocrlf
     * setting, in case the local system has it turned on and it's causing
     * problems when trying to apply the patch.
     *
     * @param string $patchFile
     * @return bool
     */
    private function applyPatch(string $patchFile): bool
    {
        $diff = $this->repository->getDiffOperator();

        if (!$diff->applyPatches([$patchFile])) {
            if (!$diff->applyPatches([$patchFile], true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Restores the working tree by running `git checkout`, while also setting
     * an environment variable to instruct CaptainHook not to run post-checkout
     * actions.
     *
     * @return void
     */
    private function restoreWorkingTree(): void
    {
        // Set environment variable that tells this plugin to skip all
        // actions when running the post-checkout hook, to avoid recursion.
        putenv(self::SKIP_POST_CHECKOUT_VAR . '=1');

        $this->repository->getStatusOperator()->restoreWorkingTree();

        // Unset the environment variable.
        putenv(self::SKIP_POST_CHECKOUT_VAR);
    }
}
