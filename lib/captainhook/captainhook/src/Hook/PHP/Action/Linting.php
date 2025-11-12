<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\PHP\Action;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Console\IOUtil;
use CaptainHook\App\Exception\ActionFailed;
use CaptainHook\App\Hook\Action;
use SebastianFeldmann\Cli\Processor\ProcOpen as Processor;
use SebastianFeldmann\Git\Repository;

/**
 * Class Linter
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 1.0.5
 */
class Linting implements Action
{
    /**
     * Path to php executable, default 'php'
     *
     * @var string
     */
    private $php;

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
        // we have to provide a custom filter because we do not want to check any deleted files
        $changedPHPFiles  = $repository->getIndexOperator()->getStagedFilesOfType('php', ['A', 'C', 'M']);
        $this->php        = !empty($config->getPhpPath()) ? $config->getPhpPath() : 'php';
        $failedFilesCount = 0;

        foreach ($changedPHPFiles as $file) {
            $prefix = IOUtil::PREFIX_OK;
            if ($this->hasSyntaxErrors($file)) {
                $failedFilesCount++;
                $io->write('  ' . IOUtil::PREFIX_FAIL . ' ' . $file, true, IO::NORMAL);
            }
            $io->write('  ' . $prefix . ' ' . $file, true, IO::VERBOSE);
        }

        if ($failedFilesCount > 0) {
            $s = $failedFilesCount > 1 ? 's' : '';
            throw new ActionFailed(
                'Linting failed: PHP syntax errors in ' . $failedFilesCount . ' file' . $s
            );
        }
    }

    /**
     * Lint a php file
     *
     * @param  string $file
     * @return bool
     */
    protected function hasSyntaxErrors(string $file): bool
    {
        $process = new Processor();
        $result  = $process->run($this->php . ' -l ' . escapeshellarg($file));

        return !$result->isSuccessful();
    }
}
