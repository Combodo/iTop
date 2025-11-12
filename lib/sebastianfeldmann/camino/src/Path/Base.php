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
use SebastianFeldmann\Camino\Path;

/**
 * Base path class for files and directories
 *
 * @package SebastianFeldmann\Camino
 */
abstract class Base implements Path
{
    /**
     * The originally given path
     *
     * @var string
     */
    protected $raw;

    /**
     * The path without the root (/, C:/, stream://)
     *
     * @var string
     */
    protected $path;

    /**
     * Amount of path segments
     *
     * @var int
     */
    protected $depth;

    /**
     * List of path segments
     *
     * @var string[]
     */
    protected $segments;

    /**
     * The path root (/, C:/, stream://)
     *
     * @var string
     */
    protected $root;

    /**
     * Absolute constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->raw = $path;
        $this->normalize($path);
    }

    /**
     * Normalize a path detect root and segments
     *
     * @param string $path
     */
    private function normalize(string $path): void
    {
        // absolute linux|unix path
        if (substr($path, 0, 1) === '/') {
            $this->root = '/';
            $this->path = ltrim($path, '/');
            $this->detectSegments($this->path);
            return;
        }

        // check streams
        if ($this->normalizeStream($path)) {
            return;
        }

        // check windows path
        if ($this->normalizeWindows($path)) {
            return;
        }

        throw new RuntimeException('path must be absolute');
    }

    /**
     * Normalize a windows path
     *
     * @param  string $path
     * @return bool
     */
    private function normalizeWindows(string $path): bool
    {
        // check for C:\ or C:/
        $driveMatch = [];
        if (strlen($path) >= 3 && preg_match('#^([A-Z]:)[/\\\]#i', substr($path, 0, 3), $driveMatch)) {
            $this->root = $driveMatch[1];
            $path       = substr($path, 2);
        }

        // normalize \ to /
        if (substr($path, 0, 1) === '\\') {
            $path = str_replace('\\', '/', $path);
        }

        if (substr($path, 0, 1) === '/') {
            $this->root = trim($this->root, '/\\') . '/';
            $this->path = trim($path, '/');
            $this->detectSegments($this->path);
            return true;
        }

        return false;
    }

    /**
     * Normalize a stream path
     *
     * @param  string $path
     * @return bool
     */
    private function normalizeStream(string $path): bool
    {
        $schemeMatch = [];
        if (strlen($path) > 4 && preg_match('#^([A-Z]+://).#i', $path, $schemeMatch)) {
            $this->root = $schemeMatch[1];
            $this->path = substr($path, strlen($this->root));
            $this->detectSegments($this->path);
            return true;
        }
        return false;
    }

    /**
     * Detect all path segments
     *
     * @param string $path
     */
    private function detectSegments(string $path)
    {
        $segments       = empty($path) ? [] : explode('/', trim($path, '/'));
        $segments       = array_filter($segments);
        $this->segments = [];

        foreach ($segments as $segment) {
            if ($segment === '.') {
                continue;
            }
            if ($segment === '..') {
                array_pop($this->segments);
                continue;
            }
            $this->segments[] = $segment;
        }

        $this->depth = count($this->segments);
    }

    /**
     * Path getter
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->raw;
    }

    /**
     * Root getter
     *
     * @return string
     */
    public function getRoot(): string
    {
        return $this->root;
    }

    /**
     * Depth getter
     *
     * @return int
     */
    public function getDepth(): int
    {
        return $this->depth;
    }

    /**
     * Segments getter
     *
     * @return array
     */
    public function getSegments(): array
    {
        return $this->segments;
    }

    /**
     * Check if a path is child of a given parent path
     *
     * @param \SebastianFeldmann\Camino\Path\Directory $parent
     * @return bool
     */
    public function isChildOf(Directory $parent): bool
    {
        if (!$this->isPossibleParent($parent)) {
            return false;
        }

        // check every path segment of the parent
        foreach ($parent->getSegments() as $index => $name) {
            if ($this->segments[$index] !== $name) {
                return false;
            }
        }
        return true;
    }

    /**
     * Returns the relative path from a parent directory to this one
     *
     * @param  \SebastianFeldmann\Camino\Path\Directory $parent
     * @return string
     */
    public function getRelativePathFrom(Directory $parent): string
    {
        if (!$this->isChildOf($parent)) {
            throw new RuntimeException($this->getPath() . ' is not a child of ' . $parent->getPath());
        }
        return implode('/', array_slice($this->segments, $parent->getDepth()));
    }

    /**
     * Check if a Directory possibly be a parent directory
     *
     * @param  \SebastianFeldmann\Camino\Path\Directory $parent
     * @return bool
     */
    protected function isPossibleParent(Directory $parent): bool
    {

        // if the root is different it can't be a subdirectory
        if (!$this->hasSameRootAs($parent)) {
            return false;
        }
        // if the parent has a deeper nesting level it can't be a parent
        if ($parent->getDepth() > $this->getDepth()) {
            return false;
        }
        return true;
    }

    /**
     * Check if a given path has the same root
     *
     * @param  \SebastianFeldmann\Camino\Path $path
     * @return bool
     */
    protected function hasSameRootAs(Path $path): bool
    {
        return $this->root === $path->getRoot();
    }

    /**
     * To string conversion method
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->raw;
    }
}
