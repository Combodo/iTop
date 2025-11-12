<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\Template;

use SebastianFeldmann\Camino\Check;
use SebastianFeldmann\Camino\Path;
use SebastianFeldmann\Camino\Path\Directory;
use SebastianFeldmann\Camino\Path\File;

class PathInfo
{
    /**
     * Absolute path to repository
     * @var string
     */
    private string $repositoryAbsolute;

    /**
     * @var \SebastianFeldmann\Camino\Path\Directory
     */
    protected Directory $repository;

    /**
     * Absolute path to config file
     * @var string
     */
    private string $configAbsolute;

    /**
     * @var \SebastianFeldmann\Camino\Path\File
     */
    protected File $config;

    /**
     * Absolute path to captainhook executable
     * @var string
     */
    protected string $executableAbsolute;

    /**
     * @var \SebastianFeldmann\Camino\Path\File
     */
    private File $executable;

    /**
     * PHAR or composer runtime
     *
     * @var bool
     */
    private bool $isPhar;

    /**
     * @param string $repositoryPath
     * @param string $configPath
     * @param string $execPath
     * @param bool   $isPhar
     */
    public function __construct(string $repositoryPath, string $configPath, string $execPath, bool $isPhar)
    {
        $this->repositoryAbsolute = self::toAbsolutePath($repositoryPath);
        $this->repository         = new Directory($this->repositoryAbsolute);
        $this->configAbsolute     = self::toAbsolutePath($configPath);
        $this->config             = new File($this->configAbsolute);
        $this->executableAbsolute = self::toAbsolutePath($execPath);
        $this->executable         = new File($this->executableAbsolute);
        $this->isPhar             = $isPhar;
    }

    /**
     * Returns the path to the captainhook executable
     *
     * @return string
     */
    public function getExecutablePath(): string
    {
        // check if the captainhook binary is in the repository bin directory
        // this should only be the case if we work in the captainhook repository
        if (file_exists($this->repositoryAbsolute . '/bin/captainhook')) {
            return './bin/captainhook';
        }
        return $this->getPathFromTo($this->repository, $this->executable);
    }

    /**
     * Returns the path to the captainhook configuration file
     * @return string
     */
    public function getConfigPath(): string
    {
        return $this->getPathFromTo($this->repository, $this->config);
    }

    /**
     * Runtime indicator
     *
     * @return bool
     */
    public function isPhar(): bool
    {
        return $this->isPhar;
    }

    /**
     * Return the path to the target path from inside the .git/hooks directory f.e. __DIR__ ../../vendor
     *
     * @param  \SebastianFeldmann\Camino\Path\Directory $repo
     * @param  \SebastianFeldmann\Camino\Path           $target
     * @return string
     */
    private function getPathFromTo(Directory $repo, Path $target): string
    {
        if (!$target->isChildOf($repo)) {
            return $target->getPath();
        }
        return $target->getRelativePathFrom($repo);
    }


    /**
     * Make sure the given path is absolute
     *
     * @param  string $path
     * @return string
     */
    private static function toAbsolutePath(string $path): string
    {
        if (Check::isAbsolutePath($path)) {
            return $path;
        }
        return (string) realpath($path);
    }
}
