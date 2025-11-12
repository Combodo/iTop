<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\Apply;

use SebastianFeldmann\Git\Command\Base;

/**
 * Class ApplyPatch
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 3.7.0
 */
class ApplyPatch extends Base
{
    /**
     * Patch files to apply.
     *
     * @var string[]
     */
    private array $patchFiles = [];

    /**
     * Action to take when encountering whitespace.
     *
     * @var string
     */
    private string $whitespace = ' --whitespace=\'warn\'';

    /**
     * Number of leading path components to remove from the diff paths.
     *
     * @var string
     */
    private string $pathComponents = ' -p1';

    /**
     * Ignore changes in whitespace in context lines.
     *
     * @var string
     */
    private string $ignoreSpaceChange = '';

    /**
     * Patch files to apply.
     *
     * @param string[] $patchFiles
     * @return \SebastianFeldmann\Git\Command\Apply\ApplyPatch
     */
    public function patches(array $patchFiles): ApplyPatch
    {
        $this->patchFiles = $patchFiles;
        return $this;
    }

    /**
     * Set the action to take when encountering whitespace.
     *
     * @param  string $action
     * @return \SebastianFeldmann\Git\Command\Apply\ApplyPatch
     */
    public function whitespace(string $action = 'warn'): ApplyPatch
    {
        $this->whitespace = ' --whitespace=' . escapeshellarg($action);
        return $this;
    }

    /**
     * Set the number of leading path components to remove from the diff paths.
     *
     * @param  int $pathComponents
     * @return \SebastianFeldmann\Git\Command\Apply\ApplyPatch
     */
    public function pathComponents(int $pathComponents = 1): ApplyPatch
    {
        $this->pathComponents = ' -p' . $pathComponents;
        return $this;
    }

    /**
     * Ignore changes in whitespace in context lines if necessary.
     *
     * @param bool $bool
     * @return \SebastianFeldmann\Git\Command\Apply\ApplyPatch
     */
    public function ignoreSpaceChange(bool $bool = true): ApplyPatch
    {
        $this->ignoreSpaceChange = $this->useOption('--ignore-space-change', $bool);
        return $this;
    }

    /**
     * Return the command to execute.
     *
     * @return string
     */
    protected function getGitCommand(): string
    {
        return 'apply'
            . $this->pathComponents
            . $this->whitespace
            . $this->ignoreSpaceChange
            . ' '
            . implode(' ', array_map('escapeshellarg', $this->patchFiles));
    }
}
