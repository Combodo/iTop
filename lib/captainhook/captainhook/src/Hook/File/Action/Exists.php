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
 * Exists (in repository)
 *
 * This hook makes sure that a configured list of files exist in the repository.
 * For example, you can use this to make sure you have committed some unit tests
 * before pushing your changes.
 *
 * {
 *     "action": "\\CaptainHook\\App\\Hook\\File\\Action\\Exists",
 *     "options": {
 *         "files" : [
 *             "tests / CaptainHook/ ** / * Test.php",
 *             "README.md"
 *         ]
 *     }
 * }
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.4.3
 */
class Exists extends Check
{
    /**
     * List of files that should exist
     *
     * @var string[]
     */
    private array $files;

    /**
     * Extract and validate config settings
     *
     * @param  \CaptainHook\App\Config\Options $options
     * @throws \CaptainHook\App\Exception\ActionFailed
     */
    protected function setUp(Config\Options $options): void
    {
        $this->files = $options->get('files', []);
        if (!is_array($this->files) || empty($this->files)) {
            throw new ActionFailed('no files configured');
        }
        parent::setUp($options);
    }

    /**
     * Return the list of files that should be checked
     *
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @return string[]
     */
    protected function getFilesToCheck(Repository $repository): array
    {
        return $this->files;
    }

    /**
     * @param \SebastianFeldmann\Git\Repository $repository
     * @param string                            $file
     * @return bool
     */
    protected function isValid(Repository $repository, string $file): bool
    {
        $repoFiles = $repository->getInfoOperator()->getFilesInTree($file);
        return !empty($repoFiles);
    }

    /**
     * Custom exception message
     *
     * @param  int $filesFailed
     * @return string
     */
    protected function errorMessage(int $filesFailed): string
    {
        return 'Error: ' . $filesFailed . ' file(s) could not be found';
    }
}
