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
use CaptainHook\App\Config\Options;
use CaptainHook\App\Exception\ActionFailed;
use SebastianFeldmann\Git\Repository;

/**
 * Class DoesNotContainRegex
 *
 * @package CaptainHook
 * @author  Felix Edelmann <fxedel@gmail.com>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.4.0
 */
class DoesNotContainRegex extends Check
{
    /**
     * Regex to check files against
     *
     * @var string
     */
    private string $regex;

    /**
     * Descriptive regex name
     *
     * @var mixed|string
     */
    private $regexName;

    /**
     * List of file types to check
     *
     * @var array<string>
     */
    private array $fileExtensions;

    /**
     * Log of all checked files and found matches
     *
     * @var array<string, int>
     */
    private array $fileMatches = [];

    /**
     * Total amount of found matches
     *
     * @var int
     */
    private int $totalMatches = 0;

    /**
     * Extract and validate config settings
     *
     * @param  \CaptainHook\App\Config\Options $options
     * @throws \CaptainHook\App\Exception\ActionFailed
     */
    protected function setUp(Config\Options $options): void
    {
        $this->regex          = $this->getRegex($options);
        $this->regexName      = $options->get('regexName', $this->regex);
        $this->fileExtensions = $this->getFileExtensions($options);
    }

    /**
     * Returns a list of files to check
     *
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @return array<string>
     */
    protected function getFilesToCheck(Repository $repository): array
    {
        // no filtering by file type, just return all staged files
        if (empty($this->fileExtensions)) {
            return parent::getFilesToCheck($repository);
        }

        $index = $repository->getIndexOperator();
        $files = [];
        foreach ($this->fileExtensions as $ext) {
            $files[] = $index->getStagedFilesOfType($ext);
        }
        return array_merge(...$files);
    }

    /**
     * Tests if the given file doesn't contain invalid content
     *
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @param  string                            $file
     * @return bool
     */
    protected function isValid(Repository $repository, string $file): bool
    {
        $fileContent = (string) file_get_contents($file);
        $matchCount  = (int) preg_match_all($this->regex, $fileContent, $matches);

        $this->fileMatches[$file] = $matchCount;
        $this->totalMatches      += $matchCount;

        return $matchCount === 0;
    }

    /**
     * Return an error message appendix
     *
     * @param  string $file
     * @return string
     */
    protected function errorDetails(string $file): string
    {
        return ' <comment>('
               . $this->fileMatches[$file] . ' match'
               . ($this->fileMatches[$file] > 1 ? 'es' : '')
               . ')</comment>';
    }

    /**
     * Define the exception error message
     *
     * @param  int $filesFailed
     * @return string
     */
    protected function errorMessage(int $filesFailed): string
    {
        return 'Regex \'' . $this->regexName . '\' failed: '
               . 'found ' . $this->totalMatches . ' match'
               . ($this->totalMatches > 1 ? 'es' : '')
               . ' in ' . $filesFailed . ' file'
               . ($filesFailed > 1 ? 's' : '' );
    }

    /**
     * Returns the configured file extensions
     *
     * @param  \CaptainHook\App\Config\Options $options
     * @return string[]
     */
    private function getFileExtensions(Options $options): array
    {
        $fileExtensions = $options->get('fileExtensions', []);

        if (!is_array($fileExtensions)) {
            return [];
        }
        return $fileExtensions;
    }

    /**
     * Extract and check configured regex
     *
     * @param \CaptainHook\App\Config\Options $options
     * @return mixed|string
     * @throws \CaptainHook\App\Exception\ActionFailed
     */
    private function getRegex(Options $options)
    {
        $regex = $options->get('regex', '');

        if (empty($regex)) {
            throw new ActionFailed('Missing option "regex" for DoesNotContainRegex action');
        }
        return $regex;
    }
}
