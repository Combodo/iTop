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
use CaptainHook\App\Console\IO;
use CaptainHook\App\Console\IOUtil;
use CaptainHook\App\Exception\ActionFailed;
use CaptainHook\App\Hook\Action;
use CaptainHook\App\Hook\Constrained;
use CaptainHook\App\Hook\Restriction;
use SebastianFeldmann\Git\Repository;

/**
 * Class Check
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.4.1
 */
abstract class Check implements Action, Constrained
{
    /**
     * Actual action name
     *
     * @var string
     */
    protected string $actionName;

    /**
     * Make sure this action is only used pro pre-commit hooks
     *
     * @return \CaptainHook\App\Hook\Restriction
     */
    public static function getRestriction(): Restriction
    {
        return new Restriction('pre-commit');
    }

    /**
     * Executes the action
     *
     * @param  \CaptainHook\App\Config           $config
     * @param  \CaptainHook\App\Console\IO       $io
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @param  \CaptainHook\App\Config\Action    $action
     * @return void
     * @throws \Exception
     */
    public function execute(Config $config, IO $io, Repository $repository, Config\Action $action): void
    {
        $this->setUp($action->getOptions());

        $filesToCheck = $this->getFilesToCheck($repository);
        $filesFailed  = 0;

        if (!count($filesToCheck)) {
            $io->write('  no files had to be checked', true, IO::VERBOSE);
            return;
        }

        foreach ($filesToCheck as $file) {
            if (!$this->isValid($repository, $file)) {
                $io->write('  ' . IOUtil::PREFIX_FAIL . ' ' . $file . $this->errorDetails($file));
                $filesFailed++;
                continue;
            }
            $io->write('  ' . IOUtil::PREFIX_OK . ' ' . $file, true, IO::VERBOSE);
        }

        if ($filesFailed > 0) {
            throw new ActionFailed(
                $this->errorMessage($filesFailed)
            );
        }
    }

    /**
     * Setup the action, reading and validating all config settings
     *
     * @param \CaptainHook\App\Config\Options $options
     */
    protected function setUp(Config\Options $options): void
    {
        // can be used in child classes to extract and validate config settings
    }

    /**
     * Some output appendix for every file
     *
     * @param  string $file
     * @return string
     */
    protected function errorDetails(string $file): string
    {
        // can be used to enhance the output
        return '';
    }

    /**
     * Define the exception error message
     *
     * @param  int $filesFailed
     * @return string
     */
    protected function errorMessage(int $filesFailed): string
    {
        $s = $filesFailed > 1 ? 's' : '';
        return '<error>Error: ' . $filesFailed . ' file' . $s . ' failed</error>';
    }

    /**
     * Determine if the file is valid
     *
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @param  string                            $file
     * @return bool
     */
    abstract protected function isValid(Repository $repository, string $file): bool;

    /**
     * Return the list of files that should be checked
     *
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @return array<string>
     */
    protected function getFilesToCheck(Repository $repository): array
    {
        return $repository->getIndexOperator()->getStagedFiles();
    }
}
