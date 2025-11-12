<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\Status\Porcelain;

use SebastianFeldmann\Cli\Command\OutputFormatter;
use SebastianFeldmann\Git\Status\Path;

/**
 * Class PathList
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 3.6.0
 */
class PathList implements OutputFormatter
{
    /**
     * Nul-byte used as a separator in `--porcelain=v1 -z` output
     */
    private const NUL_BYTE = "\x00";

    /**
     * Format the output
     *
     * @param  array<string> $output
     * @return iterable<Path>
     */
    public function format(array $output): iterable
    {
        if (empty($output)) {
            return [];
        }

        $statusLine = implode('', $output);
        $paths = [];

        foreach ($this->parseStatusLine($statusLine) as $pathParts) {
            $paths[] = new Path(...$pathParts);
        }

        return $paths;
    }

    /**
     * Parse the status line and return a 3-tuple of path parts
     *
     * - 0: status code
     * - 1: path
     * - 2: original path, if renamed or copied
     *
     * @return array<int, array<int, string|null>>
     */
    private function parseStatusLine(string $statusLine): array
    {
        $pathParts = [];

        $parts = array_reverse($this->splitOnNulByte($statusLine));

        while ($parts) {
            $part       = array_pop($parts);
            $statusCode = substr($part, 0, 2);
            $path       = substr($part, 3);

            $originalPath = null;
            if (in_array($statusCode[0], [Path::COPIED, Path::RENAMED])) {
                $originalPath = array_pop($parts);
            }

            $pathParts[] = [$statusCode, $path, $originalPath];
        }

        return $pathParts;
    }

    /**
     * Split the status line on the nul-byte
     *
     * @param  string $statusLine
     * @return array<string>
     */
    private function splitOnNulByte(string $statusLine): array
    {
        return explode(self::NUL_BYTE, trim($statusLine, self::NUL_BYTE));
    }
}
