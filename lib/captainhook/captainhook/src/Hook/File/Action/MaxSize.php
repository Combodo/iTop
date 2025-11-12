<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\File\Action;

use CaptainHook\App\Config;
use RuntimeException;
use SebastianFeldmann\Git\Repository;

/**
 * MaxSize
 *
 * Check all staged files for file size
 *
 * {
 *     "action": "\\CaptainHook\\App\\Hook\\File\\Action\\MaxSize",
 *     "options": {
 *         "size" : "5M"
 *     }
 * }
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.4.3
 */
class MaxSize extends Check
{
    /**
     * @var int
     */
    private int $maxBytes;

    /**
     * File sizes for all checked files
     *
     * @var array<string, int>
     */
    private array $fileSizes = [];

    protected function setUp(Config\Options $options): void
    {
        $this->maxBytes = $this->toBytes($options->get('maxSize', ''));
    }

    /**
     * Make sure the given file is not too big
     *
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @param  string                            $file
     * @return bool
     */
    protected function isValid(Repository $repository, string $file): bool
    {
        return !$this->isTooBig($file);
    }

    /**
     * Append the actual file size
     *
     * @param string $file
     * @return string
     */
    protected function errorDetails(string $file): string
    {
        return ' <comment>(' . $this->toMegaBytes($this->fileSizes[$file]) . ' MB)</comment>';
    }

    /**
     * Custom error message
     *
     * @param  int $filesFailed
     * @return string
     */
    protected function errorMessage(int $filesFailed): string
    {
        return  $filesFailed . ' file' . ($filesFailed > 1 ? ' s are' : ' is') . ' too big';
    }

    /**
     * Compare a file to configured max file size
     *
     * @param  string $file
     * @return bool
     */
    private function isTooBig(string $file): bool
    {
        if (!file_exists($file) || is_dir($file)) {
            return false;
        }

        $this->fileSizes[$file] = (int) filesize($file);

        if ($this->fileSizes[$file] > $this->maxBytes) {
            return true;
        }
        return false;
    }

    /**
     * Return given size in bytes
     * Allowed units:
     *   B => byte
     *   K => kilobyte
     *   M => megabyte
     *   G => gigabyte
     *   T => terra byte
     *   P => peta byte
     *
     * e.g.
     * 1K => 1024
     * 2K => 2048
     * ...
     *
     * @param  string $value
     * @throws \RuntimeException
     * @return int
     */
    public function toBytes(string $value): int
    {
        if (!preg_match('#^[0-9]*[BKMGTP]$#i', $value)) {
            throw new RuntimeException('Invalid size value');
        }
        $units  = ['B' => 0, 'K' => 1, 'M' => 2, 'G' => 3, 'T' => 4, 'P' => 5];
        $unit   = strtoupper(substr($value, -1));
        $number = intval(substr($value, 0, -1));

        return $number * pow(1024, $units[$unit]);
    }

    /**
     * Display bytes in a readable format
     *
     * @param  int $bytes
     * @return float
     */
    private function toMegaBytes(int $bytes): float
    {
        return round($bytes / 1024 / 1024, 3);
    }
}
