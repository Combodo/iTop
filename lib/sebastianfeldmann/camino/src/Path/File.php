<?php

/**
 * This file is part of Camino.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Camino\Path;

use RuntimeException;

/**
 * Class File
 *
 * @package SebastianFeldmann\Camino
 */
final class File extends Base
{
    /**
     * Returns a directory instance of its location
     *
     * @return \SebastianFeldmann\Camino\Path\Directory
     */
    public function getDirectory(): Directory
    {
        return new Directory(dirname($this->raw));
    }

    /**
     * Is the file located in a given directory
     *
     * @param  \SebastianFeldmann\Camino\Path\Directory $directory
     * @return bool
     */
    public function isInDirectory(Directory $directory): bool
    {
        return $this->isChildOf($directory);
    }

    /**
     * Factory method to create files that actually exist
     *
     * @param  string $path
     * @return \SebastianFeldmann\Camino\Path\File
     */
    public static function create(string $path): File
    {
        $realPath = realpath($path);
        if (empty($realPath) || is_dir($realPath)) {
            throw new RuntimeException('file does not exist: ' . $path);
        }
        return new self($realPath);
    }
}
