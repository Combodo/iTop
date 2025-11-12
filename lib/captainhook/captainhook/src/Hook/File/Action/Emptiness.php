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
use CaptainHook\App\Exception\ActionFailed;
use SebastianFeldmann\Git\Repository;

/**
 * Class Check
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.10.0
 */
abstract class Emptiness extends Check
{
    /**
     * Actual action name
     *
     * @var string
     */
    protected string $actionName;

    /**
     * List of configured file patterns to watch
     *
     * @var array<string>
     */
    private array $filePatterns;

    /**
     * Extract and validate all config settings
     *
     * @param  \CaptainHook\App\Config\Options $options
     * @throws \Exception
     */
    protected function setUp(Config\Options $options): void
    {
        $this->filePatterns = $options->get('files', []);
        if (empty($this->filePatterns)) {
            throw new ActionFailed('Missing option "files" for ' . $this->actionName . ' action');
        }
    }

    /**
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @return array<string>
     * @throws \Exception
     */
    protected function getFilesToCheck(Repository $repository): array
    {
        $stagedFiles = parent::getFilesToCheck($repository);
        return $this->extractFilesToCheck($this->getFilesToWatch(), $stagedFiles);
    }

    /**
     * Return a list of files to watch
     *
     * ['pattern1' => ['file1', 'file2'], 'pattern2' => ['file3']...]
     *
     * @return array<string, array<string>>
     * @throws \Exception
     */
    private function getFilesToWatch(): array
    {
        $filesToWatch = [];
        // collect all files that should be watched
        foreach ($this->filePatterns as $glob) {
            $globbed = glob($glob);
            if (is_array($globbed)) {
                $filesToWatch[$glob] = $globbed;
            }
        }

        return $filesToWatch;
    }

    /**
     * Extract files list from the action configuration
     *
     * @param  array<string, array<string>> $filesToWatch  ['pattern1' => ['file1', 'file2'], 'pattern2' => ['file3']..]
     * @param  array<string>               $stagedFiles
     * @return array<string>
     */
    private function extractFilesToCheck(array $filesToWatch, array $stagedFiles): array
    {
        $filesToCheck = [];
        // check if any staged file should be watched
        foreach ($stagedFiles as $stagedFile) {
            if ($this->isFileUnderWatch($stagedFile, $filesToWatch)) {
                $filesToCheck[] = $stagedFile;
            }
        }
        return $filesToCheck;
    }

    /**
     * Check if a file is in the list of watched files
     *
     * @param  string                      $stagedFile
     * @param  array<string, array<string>> $filesToWatch
     * @return bool
     */
    private function isFileUnderWatch(string $stagedFile, array $filesToWatch): bool
    {
        // check the list of files found for each pattern
        foreach ($filesToWatch as $files) {
            foreach ($files as $fileToWatch) {
                if ($fileToWatch === $stagedFile) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Returns true if the file has no contents
     *
     * @param  string $file
     * @return bool
     */
    protected function isEmpty(string $file): bool
    {
        return filesize($file) === 0;
    }
}
