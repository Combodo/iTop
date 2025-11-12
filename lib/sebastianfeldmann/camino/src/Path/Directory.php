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
 * Class Directory
 *
 * @package SebastianFeldmann\Camino
 */
final class Directory extends Base
{
    /**
     * Checks if the directory is a sub directory of a given directory
     *
     * @param  \SebastianFeldmann\Camino\Path\Directory $parent
     * @return bool
     */
    public function isSubDirectoryOf(Directory $parent): bool
    {
        return $this->isChildOf($parent);
    }

    /**
     * Factory method to create directories that actually exist
     *
     * @param  string $path
     * @return \SebastianFeldmann\Camino\Path\Directory
     */
    public static function create(string $path): Directory
    {
        $realPath = realpath($path);
        if (empty($realPath) || is_file($realPath)) {
            throw new RuntimeException('directory does not exist: ' . $path);
        }
        return new self($realPath);
    }
}
