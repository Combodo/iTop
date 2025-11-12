<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook;

/**
 * Class FileList
 *
 * Helper class performing some manipulation operations on plain file lists.
 *
 *   ['file1.txt', 'file2.txt' ...]
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.20.0
 */
abstract class FileList
{
    /**
     * Use all filters
     *
     * @param  array<string> $files
     * @param  array<string> $options
     * @return array<string>
     */
    public static function filter(array $files, array $options): array
    {
        $files = self::filterByType($files, $options);
        $files = self::filterByDirectory($files, $options);
        return self::replaceInAll($files, $options);
    }

    /**
     * Filter files by type
     *
     * @param  array<string>         $files
     * @param  array<string, string> $options
     * @return array<string>
     */
    public static function filterByType(array $files, array $options): array
    {
        if (!isset($options['of-type'])) {
            return $files;
        }

        $filtered = [];
        foreach ($files as $file) {
            if (str_ends_with($file, $options['of-type'])) {
                $filtered[] = $file;
            }
        }
        return $filtered;
    }

    /**
     * Filter staged files by directory
     *
     * @param  array<string>         $files
     * @param  array<string, string> $options
     * @return array<string>
     */
    public static function filterByDirectory(array $files, array $options): array
    {
        if (!isset($options['in-dir'])) {
            return $files;
        }

        $directory = $options['in-dir'];
        $filtered  = [];
        foreach ($files as $file) {
            if (str_starts_with($file, $directory)) {
                $filtered[] = $file;
            }
        }

        return $filtered;
    }

    /**
     * Run search replace for all files
     *
     * @param  array<string>         $files
     * @param  array<string, string> $options
     * @return array<string>
     */
    public static function replaceInAll(array $files, array $options): array
    {
        if (!isset($options['replace'])) {
            return $files;
        }

        $search  = $options['replace'];
        $replace = $options['with'] ?? '';

        foreach ($files as $index => $file) {
            $files[$index] = str_replace($search, $replace, $file);
        }
        return $files;
    }
}
