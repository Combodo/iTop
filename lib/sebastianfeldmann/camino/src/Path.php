<?php

/**
 * This file is part of Camino.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Camino;

use SebastianFeldmann\Camino\Path\Directory;

/**
 * Interface Path
 *
 * @package SebastianFeldmann\Camino
 */
interface Path
{
    /**
     * Returns the full absolute path
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Returns the root of a path e.g. /, c:/, stream://
     *
     * @return string
     */
    public function getRoot(): string;

    /**
     * Returns the amount of path segments
     *
     * @return int
     */
    public function getDepth(): int;

    /**
     * Returns the list of path segments
     *
     * @return array
     */
    public function getSegments(): array;


    /**
     * Returns the relative path from a given directory
     *
     * @param  \SebastianFeldmann\Camino\Path\Directory $directory
     * @return string
     */
    public function getRelativePathFrom(Directory $directory): string;

    /**
     * Is the given directory a parent directory
     * @param  \SebastianFeldmann\Camino\Path\Directory $path
     * @return bool
     */
    public function isChildOf(Directory $path): bool;
}
